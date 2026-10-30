# justamanda.net — Agent Operating Contract

Loaded automatically in this repo, on top of `~/Projects/CLAUDE.md` (which
still applies: whole SQL blocks, the secret scan, output economy). This file is
the site-specific part. The README explains the design; this is how to work.

## Shipping

- **Plain HTML/CSS/JS, no build step, GitHub Pages from `main`.** Repo
  `amadc0ck/just-amanda`. No `gh` CLI; check Action runs with
  `curl -s https://api.github.com/repos/amadc0ck/just-amanda/actions/runs`.
- **Approval to build includes the push.** When Amanda says go, commit and push
  when it is verified. Do not ask again. Do flag anything that must happen
  first (SQL she has to run), because pushing early breaks the live site.
- **`git pull --rebase origin main` before every push.** The journal Action
  commits to `main` every 30 minutes.
- **Stage explicit paths. Never `git add -A`, `git add .` or `'*.html'`.**
  `theme-abg.html`, `theme-rich.html` and `images/blog/` are hers and
  untracked; a glob committed and published the theme files once (2026-10-30).
  Check `git status --short` before committing.
- **Bump stamps with `python3 tools/stamp.py`**, never sed, whenever
  `style.css`, `site.js`, `markdown.js`, `edit.js` or `admin.html` change. It
  globs `*.html`, so it also rewrites the untracked theme files: copy them
  aside first and put them back after.
- **Load the page before calling it shipped.** Local check through Chrome, then
  curl the live URL after the push (Pages takes about a minute).
- Report a ship in 2 or 3 sentences. Commit subject plus at most 5 lines.

## Every public page carries

GoatCounter, then the Google tag (`G-00ZH9LDQ5B`, with the `skipgc` line so
signed-in visits are not counted), the RSS alternate link, canonical + `og:*` +
`twitter:card`, and the standard footer socials. New public pages also go in
`STATIC_PAGES` in `tools/build-journal.mjs` so they reach the sitemap.

Not on admin, the style guide, or the `/ig` `/fb` `/threads` redirects.
`post.html` is the template for `404.html` and every `journal/<slug>/` page:
change it, then run `node tools/build-journal.mjs`. Never hand-edit `journal/`.

**Coming soon:** pages in `COMING_SOON` (`site.js`) are curtained for the
public, carry `noindex`, and stay out of the sitemap. Opening one means
removing it from that list, the `is-soon` class and the `noindex` tag.

**Socials:** Instagram `justamanda_net`, Facebook `justamanda.net`, Threads
`@justamanda_net`, plus LinkedIn and GitHub. Bio links use `/ig` `/fb` `/threads`.

## Journal posts

Posts live in Supabase (`posts` table, project `fsckwgicmvviefuivgza`), not in
the repo. A brief from Amanda (a `.md`, usually with a zip of images) becomes a
**draft**, never published by me.

1. Unzip into its own scratchpad folder, never into the repo.
2. **Images:** screenshots to WebP (`cwebp -q 85 -metadata none`, max 1600px
   wide); photographs through `tools/prep-photo.py` so GPS is provably gone.
   Upload to the public `journal` bucket from `~/Projects/adcock-plant-inventory`
   (the linked project):
   `supabase storage cp <file> ss:///journal/<slug>-<name> --content-type <type> --experimental --linked`.
   Reference them by full public URL. Look at any screenshot that might show
   an email, address or key before uploading.
3. **Body:** Markdown rendered through `markdown.js` (the same code admin uses),
   with images as raw `<figure>` / `<img width height loading="lazy" alt>` /
   `<figcaption>` blocks, no blank lines inside. Real alt text, not empty.
4. **Placeholders** she must fill stay visible as `[[LABEL: what is needed]]`.
   Fill only what can be verified, and say which ones I filled.
5. **Check claims against the records.** For app posts, `git log` and
   `REFERENCE.md` / `BACKLOG.md` in `adcock-plant-inventory-app`. Flag a claim
   that the records contradict; do not silently rewrite her words.
6. Insert with `supabase db query -f sql/NNN-<slug>.sql --linked`. Use `-f`:
   inline SQL that starts with a `--` comment is parsed as a CLI flag. Keep the
   file in `sql/` as the record, with `on conflict (slug) do nothing`.
7. Preview locally (throwaway file, deleted after) and confirm every image loads.

**Copy style: no em dashes** in post copy or site text.

## Quality bars

- **Contrast 4.5:1 for text**, using palette tokens only. Known failures: white
  on sage (2.1:1), amethyst on lavender (2.6:1). Lighthouse flagged the masthead
  tagline, current-nav link, card titles and footer small text (2026-10-30).
- **Images:** WebP or properly sized JPEG. The home page PNGs are about 890 KB
  heavier than they need to be; `avatar.png` is 322 KB for a 124px circle.
- **PageSpeed:** the anonymous API quota is usually exhausted; read her report
  link in Chrome instead. "Efficient cache lifetimes" cannot be fixed on GitHub
  Pages; the `?v=` stamps already handle freshness.
- **Secrets:** the Supabase publishable key in `site.js` is public by design.
  Never a service-role key or Meta/Google tokens in this repo.
