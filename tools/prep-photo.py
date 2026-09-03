#!/usr/bin/env python3
"""Resize a photograph for the site and prove its GPS is gone.

    python3 tools/prep-photo.py ~/Desktop/IMG_4821.jpg images/plant-coffee-cactus.jpg

Phone photos carry GPS, and these are photographs taken at her house on a public
personal domain. Publishing home coordinates is not recoverable once indexed, so
this refuses to write the output at all if it cannot prove the location data is
gone.

WHY A SCRIPT AND NOT A ONE-LINER. Every obvious command fails, and all of them
fail SILENTLY, which is the worst way for a privacy step to fail. Measured
2026-09-03 against a JPEG built with a known GPS IFD:

  * `sips -d GPS f.jpg` does NOT strip GPS. It exits 0 and changes nothing.
    (`-d IPTC` errors outright: "Cannot do --deleteProperty IPTC on file".)
  * `sips -g all f.jpg | grep -i gps` NEVER prints a GPS line, even on a file
    that definitely has one. Empty output there means nothing at all.
  * Re-encoding through sips — `-Z`, `-s format jpeg`, `-s formatOptions` —
    does NOT strip it either. It carries the GPS IFD straight through and
    rewrites it big-endian, which also defeats any grep for the tag bytes.

So sips resizes, and this strips: the APP1 (Exif, XMP) and APP13 (IPTC) segments
are removed from the JPEG outright. APP0/JFIF and the APP2 ICC colour profile
are kept — they carry no location. Then it parses the result back and refuses to
write anything if a GPS tag is still reachable.

No exiftool, ImageMagick or Pillow on this machine — stdlib and sips only.
"""
import pathlib, shutil, struct, subprocess, sys, tempfile

GPS_IFD_POINTER = 0x8825


def exif_tags(path):
    """IFD0 tag ids from the first APP1/Exif segment. None if there is none."""
    d = path.read_bytes()
    if d[:2] != b"\xff\xd8":
        return None
    i = 2
    while i + 4 <= len(d) and d[i] == 0xFF:
        marker, size = d[i + 1], struct.unpack(">H", d[i + 2:i + 4])[0]
        if marker in (0xD8, 0xD9) or 0xD0 <= marker <= 0xD7:
            i += 2
            continue
        seg = d[i + 4:i + 2 + size]
        if marker == 0xE1 and seg[:6] == b"Exif\x00\x00":
            return _ifd0(seg[6:])
        if marker == 0xDA:            # start of scan — metadata is behind us
            break
        i += 2 + size
    return None


def strip_metadata(d):
    """The JPEG with its Exif/XMP/IPTC segments removed. Pixels untouched."""
    if d[:2] != b"\xff\xd8":
        return d
    out, i = bytearray(d[:2]), 2
    while i + 4 <= len(d) and d[i] == 0xFF:
        marker = d[i + 1]
        if marker in (0xD8, 0xD9) or 0xD0 <= marker <= 0xD7:
            out += d[i:i + 2]; i += 2; continue
        size = struct.unpack(">H", d[i + 2:i + 4])[0]
        seg = d[i:i + 2 + size]
        drop = marker == 0xE1 or marker == 0xED       # Exif/XMP, and Photoshop/IPTC
        if not drop:
            out += seg
        if marker == 0xDA:                            # scan data runs to the end
            out += d[i + 2 + size:]
            return bytes(out)
        i += 2 + size
    out += d[i:]
    return bytes(out)


def _ifd0(tiff):
    if tiff[:2] not in (b"II", b"MM"):
        return None
    e = "<" if tiff[:2] == b"II" else ">"
    magic, off = struct.unpack(e + "HI", tiff[2:8])
    if magic != 42 or off + 2 > len(tiff):
        return None
    n = struct.unpack(e + "H", tiff[off:off + 2])[0]
    tags = []
    for k in range(n):
        p = off + 2 + k * 12
        if p + 12 > len(tiff):
            break
        tags.append(struct.unpack(e + "H", tiff[p:p + 2])[0])
    return tags


def main():
    if len(sys.argv) < 3:
        sys.exit(__doc__)
    src, dst = pathlib.Path(sys.argv[1]).expanduser(), pathlib.Path(sys.argv[2])
    width = int(sys.argv[3]) if len(sys.argv) > 3 else 1200
    if not src.exists():
        sys.exit(f"no such file: {src}")

    before = exif_tags(src)
    if before is not None and GPS_IFD_POINTER in before:
        print(f"  input carries GPS   : YES — {src.name}")
    else:
        print(f"  input carries GPS   : none found (stripping anyway)")

    # sips -Z happily UPSCALES a small source, tripling the file size for no
    # extra detail. Only resize when there is something to shed.
    probe = subprocess.run(["sips", "-g", "pixelWidth", str(src)],
                           capture_output=True, text=True)
    src_w = next((int(l.split(":")[1]) for l in probe.stdout.splitlines()
                  if "pixelWidth" in l), 0)

    with tempfile.TemporaryDirectory() as td:
        tmp = pathlib.Path(td) / "out.jpg"
        cmd = ["sips"]
        if src_w > width:
            cmd += ["-Z", str(width)]
        else:
            print(f"  width               : {src_w}px, already under {width} — not resized")
        cmd += ["-s", "format", "jpeg", "-s", "formatOptions", "80",
                str(src), "--out", str(tmp)]
        subprocess.run(cmd, check=True, capture_output=True)

        tmp.write_bytes(strip_metadata(tmp.read_bytes()))

        after = exif_tags(tmp)
        if after is not None:
            sys.exit(f"REFUSING TO WRITE: EXIF survived stripping (tags {after}). "
                     "Do not commit this file.")

        dst.parent.mkdir(parents=True, exist_ok=True)
        shutil.copy(tmp, dst)

    kb = lambda p: round(p.stat().st_size / 1024)
    print(f"  EXIF after stripping: none (verified)")
    print(f"  {kb(src)} KB -> {kb(dst)} KB   {dst}")


if __name__ == "__main__":
    main()
