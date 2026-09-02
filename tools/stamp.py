#!/usr/bin/env python3
"""Bump the cache stamp across the whole site.

Run this before every push that touches style.css, site.js, markdown.js or
admin.html:

    python3 tools/stamp.py

GitHub Pages serves HTML with cache-control: max-age=600 and we cannot change
that. So a visitor can hold a stale index.html while fetching a fresh
stylesheet, and the mismatch renders as a broken page — rules referenced by the
old markup have been deleted, or vice versa. Versioning the asset URLs does not
stop the HTML going stale, but it does guarantee that whatever HTML a browser
has, it gets the CSS and JS that HTML was written against. The mismatch is what
actually looks broken; the lag on its own just looks old.

Bumping by hand meant six files plus admin's BUILD constant, so this does it in
one place. The stamp is date-based with a counter, matching APP_VERSION
discipline in the garden app.
"""
import datetime, glob, pathlib, re, sys

ASSETS = ("style.css", "site.js", "markdown.js")
ADMIN = pathlib.Path("admin.html")

def current():
    m = re.search(r'const BUILD = "([0-9-]+)\.(\d+)";', ADMIN.read_text())
    return (m.group(1), int(m.group(2))) if m else (None, 0)

def main():
    today = datetime.date.today().isoformat()
    day, n = current()
    stamp = f"{today}.{n + 1 if day == today else 1}"

    admin = ADMIN.read_text()
    ADMIN.write_text(re.sub(r'const BUILD = "[^"]+";', f'const BUILD = "{stamp}";', admin, count=1))

    touched = 0
    for f in sorted(glob.glob("*.html")):
        p = pathlib.Path(f)
        s = before = p.read_text()
        for a in ASSETS:
            # Matches both the bare filename and an already-stamped one.
            s = re.sub(rf'({re.escape(a)})(\?v=[0-9.\-]+)?(["\'])', rf'\1?v={stamp}\3', s)
        if s != before:
            p.write_text(s); touched += 1

    print(f"stamp {stamp} — {touched} html file(s) updated")

if __name__ == "__main__":
    sys.exit(main())
