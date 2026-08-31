# justamanda.net

Personal site. Plain HTML and CSS — no build step, no framework, matching the
approach used across the other Adcock projects. Edit a file, push, it is live.

## Files

    index.html   the homepage
    style.css    the whole design system, one file
    CNAME        added by GitHub when the custom domain is set (step 3)

## The design system

Warm editorial, deliberately **not** the plant app's palette — that one is a
dark tool for working in; this is a light space for reading.

| Token | Value | Used for |
| --- | --- | --- |
| `--bg` | `#FBF8F3` | page ground |
| `--bg-wash` | `#F4EDE2` | hero and footer bands |
| `--ink` | `#2C3A2E` | body text, deep green-black |
| `--terracotta` | `#B4553F` | links, emphasis, primary buttons |
| `--sage` | `#7E8C6A` | the sprig marks, quiet tags |
| `--gold` | `#C0A15A` | section ornaments |

Display face is **Playfair Display**, body is **Inter**. Both from Google Fonts.

## Artwork — the one real gap

The mockups this was built from lean on custom watercolour illustrations: the
desk scene, the hen, the yarn, the potted plants. Those are generated art, not
something CSS can produce, and they carry a lot of the warmth.

**Amanda is producing these as of 2026-08-31.** The exact sizes, ratios and
style notes are in `images/ASSETS.md` — follow that and they will drop straight
in with no re-cropping.

The hero currently holds a labelled placeholder box. Three ways to fill it:

1. **Commission or generate proper illustrations.** Closest to the mockups.
   Needs one hero plus a small icon per section.
2. **Use photographs.** There are ~2,700 in the plant collection, and a good
   photograph of the real wall would be more honest than an illustration of one.
3. **Stay typographic.** Lean on the serif, the colour and the sprig marks. The
   layout already works without images; it is quieter, not broken.

Whichever way, **strip EXIF from any photograph before publishing** — phone
photos carry GPS, and this is a public site.

## Adding a page

Copy `index.html`, change the `<title>`, move `aria-current="page"` to the right
nav item, replace the `<main>` content. The nav is duplicated in the header and
footer of each page — with eight pages that is worth a second look, but at this
size hand-editing beats introducing a build step.

## Deploying

GitHub Pages from the default branch, custom domain `justamanda.net`.
The subdomain `garden.justamanda.net` points at the plant app's repo instead —
one custom domain per repository, so they cannot share.
