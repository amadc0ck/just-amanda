# WordPress migration: handoff

The file a fresh Claude Code session (iPad, web, or a new Mac session) reads to
pick up the WordPress migration. Read `CLAUDE.md` first, then this, then
`docs/migration-audit.md` for the site inventory. **Keep this file current:**
tick items as they land, delete what stops being true. Last updated 2026-11-04.

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
| Check Action runs (`curl -s https://api.github.com/repos/amadc0ck/just-amanda/actions/runs`) | yes | yes |
| SFTP to staging (needs `.env` + sshpass) | **no** | yes |
| Local WordPress for testing (Playground) | probably not | yes |
| wp-admin on staging | Amanda does it in her browser | Amanda, or Claude via Chrome in her signed-in tab |

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
- `wordpress/tools/playground-seed.php` — seeds a local Playground.
- `wordpress/tools/build-staging-loader.py` — builds a JS file Amanda runs in
  a signed-in staging wp-admin tab: uploads images from justamanda.net into the
  Media Library, copies the 2 published journal posts, creates the 10 pages as
  **drafts** with their templates and tokens filled.
- `.github/workflows/deploy-staging.yml` — lftp `mirror --reverse --delete` over
  SFTP, `wordpress/justamanda` to `html/wp-content/themes/justamanda`, on pushes
  to `wordpress/justamanda/**` or Run workflow. Secrets `STAGING_SFTP_HOST`,
  `STAGING_SFTP_USER`, `STAGING_SFTP_PASSWORD`. Not GoDaddy's deployer action:
  that needs SSH, and staging allows SFTP only (decided 2026-11-04).
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
- [x] Committed and pushed (cf74105, 70572f3).
- [x] GoDaddy's SSH action can't reach SFTP-only staging; switched to SFTP (lftp).
- [ ] **Waiting on Amanda:** the three `STAGING_SFTP_*` repo secrets. Then
      Actions → Deploy theme to staging → Run workflow. Never use the
      `DEPLOY_HOST` / `DEPLOY_USER` / `PRIVATE_KEY` secrets: unconfirmed which site they reach.
- [ ] Deploy Action green; `journal.yml` untouched; every
      justamanda.net page still loads after `_config.yml`.
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
