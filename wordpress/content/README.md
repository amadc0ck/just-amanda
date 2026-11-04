# Draft page content (not deployed)

Block markup for each page, with the live site's copy (the `site_text` overrides
applied). Phase 1 loads these into staging as drafts for side-by-side comparison;
Phase 2 can load them as the real pages. Nothing here ships with the theme, and the
deploy workflow only copies `wordpress/justamanda/`.

Images are written as tokens, replaced at load time with the Media Library copy:

- `{{id:FILE}}` becomes the attachment ID (a number, so it sits unquoted in JSON).
- `{{url:FILE}}` becomes the attachment URL.
- `{{tag:SLUG}}` becomes the post_tag term ID, for a Query Loop tag filter
  (`"taxQuery":{"post_tag":[{{tag:books}}]}`).

`FILE` is the path under the static site's `images/`, e.g. `hero.png`, `covers/x.jpg`.

Links between pages use the WordPress slugs: `/`, `/about/`, `/plants/`,
`/inventory/`, `/journal/`, `/links/`, `/chickens/`, `/crochet/`, `/books/`, `/tech/`.
