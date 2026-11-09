# justamanda.net — Agent Operating Contract

Loaded automatically in this repo, on top of `~/Projects/CLAUDE.md` (which
still applies: whole SQL blocks, the secret scan, output economy). This file is
the site-specific part.

**Since 2026-11-08 justamanda.net is a WordPress site** on GoDaddy Managed
WordPress, with the block theme in `wordpress/justamanda/`. Read the "Start here"
section of `docs/wordpress-handoff.md` first: it has the current state, how to
deploy, and the open items. The static files at the repo root are the old site,
kept only as a rollback until it is retired (around 2026-11-22).

## Shipping

- **Theme changes:** edit `wordpress/justamanda/`, bump `Version:` in its
  `style.css`, upload over SFTP (PRODUCTION_* from `.env` via `op run`, host
  contains 1310847), and in the same session `get -r` the theme and `diff -rq` it
  against the repo. GoDaddy refuses logins that come close together: wait and
  retry once, never loop. Details in the handoff.
- **Content changes** happen in WordPress, through Amanda's signed-in Chrome
  (wp-admin, or `wp.apiFetch` on a wp-admin page). Production pages are the
  source of truth; `wordpress/content/` holds the original import records.
- **Plugins need Amanda's approval first.** Installed: Akismet, Two Factor,
  LightSync Pro. No page builders.
- **Never create accounts, enter passwords, or handle 2FA codes**, even on her own
  site. Ask her to do those steps.
- **Approval to build includes the push.** When Amanda says go, commit and push
  when it is verified. Do not ask again.
- **`git pull --rebase origin main` before every push** while the old journal
  Action still runs (it commits to `main` every 30 minutes).
- **Stage explicit paths. Never `git add -A`, `git add .` or `'*.html'`.**
  `theme-abg.html`, `theme-rich.html`, `images/blog/` and `godaddy-workflow.yml`
  are untracked on purpose. Check `git status --short` before committing.
- **Load the page before calling it shipped**: signed in through Chrome for the
  editor view, and `curl https://justamanda.net/<path>?v=$RANDOM` for what the
  public sees.
- Report a ship in 2 or 3 sentences. Commit subject plus at most 5 lines.

## Decisions and clarifications: always Q&A (mandatory)

Set by Amanda 2026-11-03. Whenever a decision or clarification is needed from
her, present it with the AskUserQuestion tool (multiple-choice Q&A), never only
as prose questions at the end of a message. One question per decision, the
recommended option first and marked "(Recommended)", with each option's
tradeoff in its description. Each question must stand on its own, because the
dialog may hide the surrounding text.

## What the theme provides on every public page

Canonical, description (the page or post Excerpt), `og:*` with the featured
image's original PNG/JPEG, `twitter:card`, BlogPosting JSON-LD on posts, the RSS
link (`/feed/`), and GoatCounter + Google tag `G-00ZH9LDQ5B`, loaded only on the
justamanda.net host and never for signed-in visits (`inc/meta.php`). The sitemap
is WordPress's own (`/wp-sitemap.xml`).

**Coming soon:** a page is curtained by the "Coming soon" box in its page
settings (meta `_justamanda_coming_soon`; the old "Coming soon" template still
counts). Readers see only its `.soon-panel` group, links and cards to it are
marked SOON, it is noindex and out of the sitemap, and pages nested under it
inherit the curtain. Releasing it = untick the box and Save.

**Socials:** Instagram `justamanda_net`, Facebook `justamanda.net`, Threads
`@justamanda_net`, plus LinkedIn and GitHub. Bio links use `/ig` `/fb` `/threads`,
which the theme redirects to `/links/?ref=<network>`.

## Journal posts

Posts are WordPress posts. A brief from Amanda (a `.md`, usually with a zip of
images) becomes a **draft**, never published by me.

1. Unzip into its own scratchpad folder, never into the repo.
2. **Images:** photographs through `tools/prep-photo.py` so GPS is provably gone;
   look at any screenshot that might show an email, address or key. Upload to
   the Media Library through wp-admin's uploader; the theme makes AVIF/WebP sizes.
3. **Body:** core blocks (build with `wp.blocks`, check every block is valid),
   images as Image blocks with real alt text.
4. **Placeholders** she must fill stay visible as `[[LABEL: what is needed]]`.
   Fill only what can be verified, and say which ones I filled.
5. **Check claims against the records.** For app posts, `git log` and
   `REFERENCE.md` / `BACKLOG.md` in `adcock-plant-inventory-app`. Flag a claim
   that the records contradict; do not silently rewrite her words.
6. Save as a draft and preview it signed in; confirm every image loads.

**Her words are placed verbatim.** Formatting help (paragraph breaks, a closing
line as the handwritten beat) is fine; rewording is not, unless she asks.

**Copy style: no em dashes** in post copy or site text.

## Quality bars

- **Contrast 4.5:1 for text**, using palette tokens only (`theme.json` /
  `site.css` variables; no new hex values). Known failures: white on sage
  (2.1:1), amethyst on lavender (2.6:1).
- **Illustrations** are transparent PNG cut-outs (no backdrop, no vignette);
  hero art about 1120x840, card art about 600x450.
- **PageSpeed:** the anonymous API quota is usually exhausted; read her report
  link in Chrome instead. GoDaddy sends pages with a 31-day browser cache.
- **Secrets:** the Supabase publishable key (in `site.js` and the inventory
  block) is public by design. Never a service-role key, Meta/Google tokens or
  any `.env` value in this repo.
