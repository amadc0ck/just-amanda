# justamanda.net

Personal site. Plain HTML and CSS. No build step, no framework, matching the
approach used across the other Adcock projects. Edit a file, push, it is live.

## Files

    index.html   the homepage
    style.css    the whole design system, one file
    CNAME        added by GitHub when the custom domain is set (step 3)

## The design system

Warm editorial, deliberately **not** the plant app's palette. That one is a
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

## Artwork, the one real gap

The mockups this was built from lean on custom watercolour illustrations: the
desk scene, the hen, the yarn, the potted plants. Those are generated art, not
something CSS can produce, and they carry a lot of the warmth.

**Amanda is producing these as of 2026-08-31.** The exact sizes, ratios and
style notes are in `images/ASSETS.md`. Follow that and they will drop straight
in with no re-cropping.

The hero currently holds a labelled placeholder box. Three ways to fill it:

1. **Commission or generate proper illustrations.** Closest to the mockups.
   Needs one hero plus a small icon per section.
2. **Use photographs.** There are ~2,700 in the plant collection, and a good
   photograph of the real wall would be more honest than an illustration of one.
3. **Stay typographic.** Lean on the serif, the colour and the sprig marks. The
   layout already works without images; it is quieter, not broken.

Whichever way, **strip EXIF from any photograph before publishing**. Phone
photos carry GPS, and this is a public site.

## Adding a page

Copy `index.html`, change the `<title>`, move `aria-current="page"` to the right
nav item, replace the `<main>` content. The nav is duplicated in the header and
footer of each page. With eight pages that is worth a second look, but at this
size hand-editing beats introducing a build step.

## Journal pages, generated

Posts are written in admin and live in Supabase. `tools/build-journal.mjs`
turns each published one into a static page at `journal/<slug>/`, so shared
links get a real title, image and description, and writes `404.html` and
`sitemap.xml`. A GitHub Action (`.github/workflows/journal.yml`) runs it every
30 minutes and commits the result.

**That Action commits to `main`, so `git pull` before pushing local work.**

A post newer than the last run still works at its address: GitHub Pages
serves `404.html` there, which renders it in the browser. Never edit files in
`journal/` by hand; the next run overwrites them.

The RSS feed is the `rss` Edge Function in the `adcock-plant-inventory` repo.
Short bio links `/ig`, `/fb` and `/threads` forward to `/links` with a `?ref=`
for GoatCounter, because Instagram cannot mask a bio link's address.

## Deploying

GitHub Pages from the default branch, custom domain `justamanda.net`.
The subdomain `garden.justamanda.net` points at the plant app's repo instead:
one custom domain per repository, so they cannot share.
