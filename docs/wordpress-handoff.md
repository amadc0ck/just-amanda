# WordPress migration: handoff

The file a fresh Claude Code session (iPad, web, or a new Mac session) reads to
pick up the WordPress migration. Read `CLAUDE.md` first, then this, then
`docs/migration-audit.md` for the site inventory. **Keep this file current:**
tick items as they land, delete what stops being true. Last updated 2026-11-08.

## Current plan (2026-11-08, restarted from scratch)

Staging and the GitHub deploy Action are dropped for now. Target is the
**production** WordPress (`1310847.us21.myftpupload.com`, not live: justamanda.net
still points at GitHub Pages). Its login is **SFTP-only**, like staging, so: theme
uploaded from the Mac over SFTP (done 2026-11-08, all files verified), then settings
in wp-admin through Amanda's signed-in Chrome. Automatic deploys come later as their
own step.

Done on production 2026-11-08: theme active; search engines discouraged (was
already on); permalinks `/%postname%/` (was `//%postname%/`); display name
Amanda; `/author/amanda/` resolves; `nicename-once.php` removed after it ran.
Next: draft test pages, then side-by-side screenshots. Anything below about staging, secrets or `deploy-staging.yml` is history.

## The goal

Move justamanda.net from GitHub Pages to a WordPress block theme on GoDaddy
Managed WordPress that looks identical. Native WordPress features may replace
custom code. Phase 1 = theme + deploy pipeline + staging test pages.

**Out of scope for Phase 1:** content migration, journal import, the Supabase
plant inventory block, redirects, DNS, anything on production.

## Hard rules

- **The live static site must keep working** until DNS cutover. Never modify,
  move or delete the static files, `CNAME`, Pages settings, or
  `.github/workflows/journal.yml`.
- **Staging only.** Never use, test or connect with anything production.
- **`godaddy-workflow.yml`** (repo root, untracked) syncs the repo root to the
  WordPress root as written. Never run it, never commit it, leave it in place.
  Same for any other `godaddy-workflow*.yml`.
- **Never put the WordPress admin login name in this repo** (it is public). The
  author URL is `/author/amanda/`.
- **Credentials** live in `.env` on Amanda's Mac (a 1Password Environment, read
  with `op run --env-file=.env -- <cmd>`). STAGING_* only. Never print,
  log or write a resolved value anywhere.
- **No page builders. Every plugin needs Amanda's approval first.** Approved:
  Two Factor (wordpress.org/plugins/two-factor), 2026-11-04.
- **Decisions go to Amanda as AskUserQuestion Q&A** (see `CLAUDE.md`).

## What a session can do from where

| Need | iPad / cloud | Mac |
| --- | --- | --- |
| Edit theme, content, docs; commit; push | yes | yes |
| Deploy to staging (push to `wordpress/justamanda/**` runs the Action) | yes | yes |
| Check Action runs (`curl -s https://api.github.com/repos/amadc0ck/just-amanda/actions/runs`; the anonymous limit is 60 an hour, so poll gently, or read github.com/amadc0ck/just-amanda/actions in Chrome) | yes | yes |
| SFTP to staging (needs `.env` + sshpass) | **no** | yes |
| Local WordPress for testing (Playground) | probably not | yes |
| wp-admin on staging | Amanda does it in her browser | Claude via Chrome in her signed-in tab (decided 2026-11-08) |

SSH and SFTP go to `1311999.us21.ssh.myftpupload.com`, not the web host
`1311999.us21.myftpupload.com` that GoDaddy's provisioned workflow names
(its port 22 is closed). The staging login is **SFTP-only** (no shell, no WP-CLI), so every WordPress
setting is changed in wp-admin. With `sftp`, pipe the commands on stdin;
`sftp -b` forces batch mode, which disables password auth.

## Layout

- `wordpress/justamanda/` — the block theme. **The only folder deployed.**
  - `theme.json` tokens; fonts self-hosted in `assets/fonts/`.
  - `templates/`: index, front-page, page, single, archive, 404, plus custom
    `coming-soon` and `page-links`. `parts/`: header, footer (each just
    references a PHP pattern so theme image URLs resolve).
  - `patterns/`: 42 files; 5 divider patterns are registered in `inc/setup.php`.
  - `assets/css/site.css`: the static `style.css` ported, then WordPress
    adapter rules at the bottom. Most fixes there are specificity fights with
    core CSS; each has a comment saying which.
  - `inc/coming-soon.php`: pages on the Coming soon template show logged-out
    readers only their `.soon-panel` block, get noindex, leave the sitemap, and
    links to them go quiet. Opening a page = switch it to the default template.
  - `inc/setup.php`: image sizes, 2560 threshold, AVIF/WebP output chosen by a
    real test encode (Playground's GD claimed AVIF and wrote 0-byte files), and
    a safety net that rebuilds an upload as WebP if any output file is empty.
  - `inc/nicename-once.php`: **one-off.** Sets the sole admin's author slug to
    `amanda` on first load, then records that it ran. Remove the file and its
    `require` in `functions.php` once `/author/amanda/` works on staging.
- `wordpress/content/` — the 10 pages as block markup (not deployed). Tokens:
  `{{id:FILE}}`, `{{url:FILE}}` (Media Library attachment), `{{tag:SLUG}}`
  (term ID). See its README.
- `wordpress/tools/playground-seed.php` + `playground-blueprint.json` — a local
  WordPress on the Mac with the theme active and all pages and posts seeded.
  The seed reads `/ja/seed/posts.json` and covers (see the loader note below).
  Started with `npx @wp-playground/cli@3.1.59 server --port=9400 --login
  --blueprint=wordpress/tools/playground-blueprint.json` plus
  `--mount-before-install=<repo>/<dir>:<vm path>` for `wordpress/justamanda`
  → `/wordpress/wp-content/themes/justamanda`, `wordpress/content` →
  `/ja/content`, `wordpress/tools` → `/ja/tools`, `images` → `/ja/images`, and
  the seed folder → `/ja/seed`. Its GD writes empty WebP/AVIF files at real
  sizes, so image format can only be verified on staging. Local test login is
  Playground's default.
- Visual checks were done with headless Chrome screenshots, live vs local,
  diffed per 100px band with Pillow; phone width needs a 390px iframe, because
  headless Chrome will not lay out narrower than about 500px. Analytics hosts
  were blackholed so the checks never counted as visits.
- `wordpress/tools/build-staging-loader.py` — builds a JS file Amanda runs in
  a signed-in staging wp-admin tab: uploads images from justamanda.net into the
  Media Library, copies the 2 published journal posts, creates the 10 pages as
  **drafts** with their templates and tokens filled.
- `.github/workflows/deploy-staging.yml` — GoDaddy's `gd-wordpress-deployer@v1`
  as deploy user `git_deployer_e0299d279c_1311999` with secret `PRIVATE_KEY`,
  `wordpress/justamanda` to `wp-content/themes/justamanda`, deleted files removed,
  on pushes to `wordpress/justamanda/**` or Run workflow. Source and destination
  are fixed, no inputs. Must stay `@v1` (a SHA breaks its companion checkout).
  Decided 2026-11-08: fix the key rather than put the SFTP password in GitHub.
- `_config.yml` — keeps the working files (`CLAUDE.md`, `README.md`, `docs/`,
  `wordpress/`, `sql/`, `tools/`, `content/`) off Pages, as approved in the audit (0.3).
  Every page and asset the site uses is still served.

## Checklist

### Step 0, audit
- [x] State check and `docs/migration-audit.md` refreshed (2026-11-03).

### Step 1, theme
- [x] Templates, parts, patterns, CSS port, image handling.
- [x] All 10 pages, 47 patterns, all templates pass block-editor validation (2026-11-04).
- [x] Visual match vs live at 1280 / 820 / 390: every page the same height as
      live; remaining pixel differences are text rendering only.
- [x] 2FA plugin chosen: Two Factor.

### Step 2, deploy
- [x] Empty `html/wp-content/themes/justamanda` created on staging (775), 2026-11-04.
- [x] Committed and pushed: cf74105 theme + workflow, 70572f3 SSH host, 7a88611
      approved `_config.yml` list, 4d1159b SFTP workflow.
- [x] GoDaddy's SSH action can't reach SFTP-only staging; switched to SFTP (lftp).
- [x] 2026-11-08: the Nov 4 SSH run failed at **authentication** on the right
      host, so `PRIVATE_KEY` does not match the deploy user's registered key.
      Workflow back on GoDaddy's action (the SFTP variant never ran).
- [ ] **Waiting on Amanda:** re-copy (or regenerate) the staging deploy key from
      GoDaddy's CI/CD settings into the `PRIVATE_KEY` repo secret. Then Run workflow.
- [ ] Deploy Action green. Unknown until it runs: the action calls
      `html/git-deployer-app`, which the SFTP login cannot see.
- [x] Live site checked after `_config.yml` (2026-11-04): every page, script,
      image and journal post returns 200; the excluded working files return 404.
      `journal.yml` untouched; Pages build green.
- [ ] Amanda activates the theme (Appearance → Themes).

### Step 3, staging setup (Amanda, in wp-admin)
- [ ] Settings → Reading: "Discourage search engines" on.
- [ ] Settings → Permalinks: Post name.
- [ ] Users → Profile: nickname Amanda, "Display name publicly as" Amanda.
- [ ] Plugins → Add New: install and activate Two Factor; set it up on her profile.
- [ ] Run the loader (`python3 wordpress/tools/build-staging-loader.py <posts.json> > loader.js`;
      `posts.json` is a list of the published Supabase posts with keys `slug,
      title, excerpt, body_html, cover_url, cover_alt, tags, published_at`, the
      body rendered through `markdown.js`; the Mac session built it in its
      scratchpad, so regenerate it if starting fresh) in the wp-admin console.
- [ ] Confirm `/author/amanda/` resolves, then delete `inc/nicename-once.php`
      and its `require`, and push.
- [ ] Confirm uploads come out as AVIF or WebP on GoDaddy (check one image's sizes).

### Validation report (to Amanda)
- [ ] Side-by-side screenshots, live vs staging, desktop + mobile: home, books, journal.
- [ ] Remaining differences and why (list below).
- [ ] Editability on staging: add a card, reorder, change text, swap an image.
- [ ] Deploy green, theme active, live site unchanged, `journal.yml` untouched.
- [ ] `/author/amanda/` works; admin login name appears nowhere on the front end.
- [ ] No credentials in any commit; secret scan clean; `git status` clean except
      the intentionally untracked files; pushed.

## Known differences from live (keep for the report)

- **Social icons:** core's Social Icons block draws its glyphs a little smaller
  inside the same 18px box.
- **Inventory:** the plant list is a placeholder. The Supabase block is out of
  scope for Phase 1.
- **Nav on Inventory:** live highlights "Succulents" there; WordPress
  highlights nothing, because Inventory is not a child page. Making it one
  changes its URL, a redirects-phase decision.
- **Apostrophes:** WordPress turns straight quotes in copy into curly ones.
- **Coming-soon pages are stronger:** readers are never sent the hidden copy,
  where the static site only hid it with CSS.

## Untracked on purpose (never `git add`)

`godaddy-workflow.yml`, `theme-abg.html`, `theme-rich.html`, `images/blog/`,
`.env`. Stage explicit paths only.
