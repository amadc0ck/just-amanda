# justamanda.net to WordPress: Phase 1 audit

Written 2026-10-30 from the repo at `a173f40`, the Supabase public API, and direct
requests to GitHub Pages. Re-verified 2026-11-03 against the same commit (still
`origin/main`), the live site, the Actions API and a read-only SFTP listing of
staging. Nothing was changed on any server during either pass.

This repo is public and GitHub Pages publishes its root, so this file contains no
usernames, SSH details or keys.

## 0. Blockers found during the audit

### 0.1 justamanda.net was not serving the GitHub Pages site (fixed 2026-10-30)

Fixed the same day by restoring the four A records, TTL 1 hour. Afterwards the domain
served the real site over HTTPS from all four addresses, with a valid certificate.
Plain HTTP returned 200 rather than redirecting, so check "Enforce HTTPS" in the
GitHub Pages settings. The findings below describe the state before the fix.

2026-11-03: still healthy. The apex resolves to the four Pages addresses and serves
the real home page. **Plain HTTP still returns 200**, so "Enforce HTTPS" is still off.

On 2026-10-30 the apex A record resolves to `160.153.0.166` (GoDaddy, behind
Cloudflare) from local DNS, 1.1.1.1 and 8.8.8.8. It should resolve to GitHub's four
Pages addresses.

- `https://justamanda.net/` returns a GoDaddy holding page titled `justamanda.net`.
- `style.css`, `site.js` and `images/hero.png` return 404.
- `about.html` returns 200 with the same holding page, so every old address looks
  alive but shows the wrong thing.
- `www` still has a CNAME to `amadc0ck.github.io`. `garden.justamanda.net` is intact.
- GitHub Pages itself is healthy. Requesting it directly (`curl --resolve` to
  `185.199.108.153`) returns the real site, with Pages builds succeeding today.

Fix, in GoDaddy DNS: replace the apex A record with `185.199.108.153`,
`185.199.109.153`, `185.199.110.153` and `185.199.111.153`. If a Managed WordPress
production site has claimed the domain, that claim may need releasing first or it
may rewrite the record.

Consequence for validation: "justamanda.net unchanged" cannot pass until this is
fixed, and browser comparison shots need either the restored domain or a local
server over this repo.

### 0.2 Staging login works, but only for SFTP (re-verified 2026-11-03)

**2026-11-03:** unchanged. The password is accepted; `ssh ... "wp --info"` returns
`This service allows sftp connections only.` So `wp --info`, `wp user list`,
`wp theme list`, theme activation (Step 2.3) and all of Step 3.1 cannot run over this
login. Fix options: turn on SSH (shell) access for this user in the staging site's
GoDaddy settings, if the plan offers it; or do those steps in wp-admin by hand.

SFTP gotcha: `sftp -b file` forces batch mode, which skips password auth, so with
`sshpass` it fails as `Permission denied` without trying the password. Pipe the
commands on stdin instead.

SFTP listing, 2026-11-03: `html/` is a stock WordPress install. Themes:
`twentytwentyfive` only. Plugins: `akismet` only. Must-use: `gd-system-plugin`,
`object-cache-pro`. Nothing from this repo has been deployed, and there are **no
static site files** in the WordPress root. `wp-config.php` and `wp-content/upgrade`
were modified 2026-10-30 11:02, which matches GoDaddy provisioning, not a deploy.
Publicly, the staging URL serves a page titled `justamanda.net` with no `noindex`
robots tag in the HTML, and `wp-json` returns 401. Whether "Discourage search
engines" is on cannot be confirmed without WP-CLI or wp-admin.

Original 2026-10-30 notes follow.

After the password was corrected, the login is accepted, and a shell or remote command
returns `This service allows sftp connections only.` WP-CLI therefore still cannot
run. A read-only SFTP listing shows a WordPress install in `html/` with only
`twentytwentyfive` and `akismet`, plus GoDaddy's must-use plugins. Whether this is
really staging is confirmed by Amanda: the login details come from the staging site's
settings in GoDaddy. The table below is the state before the fix.

| Check | Result |
| --- | --- |
| `.env` ignored by git and absent from history | Yes |
| `op run` loads all four variables | Yes, no stray quotes or whitespace |
| SSH host contains the expected site number | Yes |
| The login is the interactive user, not the CI deploy user | Yes |
| The askpass helper delivers the password to ssh | Yes, verified |
| Server accepts it | **No: `Permission denied (password,publickey)`** |
| `wp --info`, `wp user list`, staging confirmation | **Not run** |

Four password attempts failed in total. I stopped there to avoid a lockout. The
1Password item does not match what the server expects, or SSH access for this user
is off.

Public facts about the staging address, which are not a substitute for the check:
it serves the same holding page, `wp-login.php` returns 200, and `wp-json` returns
401. Whether this address is really staging is **unconfirmed** until `wp option get
siteurl` can run.

`.env` note: it is a named pipe served by 1Password, and it hands `op run` resolved
values rather than `op://` references. `op run` only masks secrets it resolves
itself, so its output masking cannot be relied on here. Commands must simply never
print those variables.

### 0.3 The repo root is published, and that includes what this phase adds

GitHub Pages serves the root as is. Today `/CLAUDE.md`, `/README.md`, `/sql/*.sql`,
`/tools/*` and `/content/*` all return 200 from Pages. `docs/` and `wordpress/` would
be published the same way as soon as they are pushed.

Proposed: add a `_config.yml` containing only an `exclude:` list for `docs`,
`wordpress`, `CLAUDE.md`, `sql`, `tools` and `content`. It does not touch an existing
file, but it does change how Pages builds, so it needs a yes.

The provided workflow also hardcodes the deploy host and user, which would be public
in this repo. They are not credentials on their own, but I propose GitHub Actions
secrets for them.

2026-11-03: still true. `https://justamanda.net/CLAUDE.md` returns 200.

### 0.4 GitHub Actions history (2026-11-03)

The last 89 runs are only two workflows: `pages build and deployment` (67, of which
4 cancelled, the rest succeeded) and `Journal pages` (22: 20 scheduled, 2 on push,
all succeeded). No other workflow has ever run, so **the GoDaddy workflow has never
run** against this repo.

## 1. Page inventory

Global on every public page: masthead, footer, GoatCounter, Google tag
`G-00ZH9LDQ5B`, RSS alternate link, favicon, `theme-color #F7F3EB`.

Google tag (commit `6ef9217`), checked 2026-11-03: present on all 12 public pages
including `404.html` and `post.html`, and on both generated `journal/<slug>/` pages,
which inherit it from `post.html`. Absent, correctly, from `admin.html`,
`styleguide.html` and the `ig` / `fb` / `threads` redirects. Each copy carries the
`skipgc` line, so a signed-in browser is not counted. The theme's `inc/meta.php`
keeps that rule and adds one: nothing loads unless the host is `justamanda.net`.
Site name in OG tags: `just amanda`. `twitter:card` is `summary_large_image`.

| Page | Canonical | OG image | Robots | Status |
| --- | --- | --- | --- | --- |
| `index.html` | `/` | `hero.png` | index | Open |
| `about.html` | `/about.html` | `about-portrait.png` | index | Open |
| `plants.html` | `/plants.html` | `collection-wall.jpg` | index | Open |
| `inventory.html` | `/inventory.html` | `collection-wall.jpg` | index | Open. Not in the brief's list. |
| `journal.html` | `/journal.html` | `section-journal.png` | index | Open |
| `links.html` | `/links` | `avatar.png` | index | Open. Not in the brief's list. |
| `chickens.html` | `/chickens.html` | `section-chickens.png` | noindex | Coming soon |
| `crochet.html` | `/crochet.html` | `section-crochet.png` | noindex | Coming soon |
| `books.html` | `/books.html` | `books-hero.png` | noindex | Coming soon |
| `tech.html` | `/tech.html` | `section-3dprint.png` | noindex | Coming soon |
| `journal/<slug>/` | self | post cover | index | 2 generated posts |
| `post.html`, `404.html` | none | none | 404 is noindex | Templates |
| `admin.html` | none | none | noindex, nofollow | Tool, disallowed in robots.txt |
| `styleguide.html` | none | none | noindex, nofollow | Internal, disallowed in robots.txt |
| `ig`, `fb`, `threads` | `/links` | none | noindex | Redirects to `/links?ref=` |

Meta descriptions:

- **index:** Succulents, chickens, crochet, books and tech... the things I am deep in, and the site I made to keep them in.
- **about:** A wife, a mom, a technology leader, and a hobbyist of hobbies. On curiosity, the things I have learned, and keeping a little corner of the internet.
- **plants:** Succulents, favourites, the resources I use, and the app I built to catalogue it all.
- **inventory:** Every species in the collection, and how many of each. Read live from the catalogue.
- **journal:** Longer thoughts and project notes on plants, making, reading, and building things.
- **links:** Everything of mine in one place: the latest journal posts, the succulents, and where to find me.
- **chickens:** From one rescued chicken in a Vallejo apartment to a backyard flock. The story so far, the birds, and what I have learned.
- **crochet:** What I have made, what is on my hook, what finally made crochet click, and the short list of supplies worth buying.
- **books:** Romantasy, immersive worlds, long series and slow burn... what I read, and how I got here.
- **tech:** Apps I have built, web design, 3D printing, AI, and gaming... the nerd half of a creative life.

### Sections per page

Images below exclude the masthead logo and footer badge. Every `sec-head` also uses
`accent-lavender.png`.

**Home.** Hero (`hero.png`, two buttons). Explore row of 5 cards (`section-plants`,
`-chickens`, `-crochet`, `-books`, `-3dprint`). Divider (`divider-amethyst`). Latest
from the journal: lavender band, faint `ornament-amethyst` wash, 3 newest posts, link
to all entries.

**About.** Hero (`about-portrait`, portrait variant). Intro story row (`about-hobbies`).
Five alternating story rows with section heads (`about-rabbit-holes`, `about-journal`,
`about-geocities`, `about-building`, `about-archive`). Closing block with signature.
Divider (`divider-book`).

**Succulents.** Hero (`about-succulents`). Full-width wall photo with caption
(`collection-wall.jpg`). Two columns: story panel, and stats panel plus origin photo
(`plant-fred-ives.jpg`). Favourites: 3 photo cards (`plant-*.jpg`). From the journal:
carousel of posts tagged plant or succulent, hidden when empty. Resources: 3 trio
cards (`section-books`, `section-plants`, `section-journal`), one with a book list.
Divider (`divider-succulent`).

**Inventory.** Text-only hero. Back link. Search box and live count. Species cards
from Supabase. Resources panel with 10 links. Divider (`divider-amethyst`).

**Journal.** Text-only hero. Post list with cover, date, title, excerpt, "Read more".
Divider (`divider.png`).

**Single post.** Back link, eyebrow date, title, dek, cover, reading-measure body,
figures, blockquotes, code, `.materials` card, tag pills. Divider (`divider.png`).

**Chickens.** Soon panel. Hero with script eyebrow (`section-chickens`). 5 numbered
steps. 6 flock cards with photo slots. 4 trio cards (`section-chickens`,
`ornament-succulent`, `-lavender`, `-violets`). Pinned note. Dividers (`-succulent`,
`-book`, `-slim`).

**Crochet.** Soon panel. Hero (`section-crochet`). Two columns: story, and "On my
hook" status card with progress bar. 4 made cards with photo slots. 5 numbered steps.
Two columns: numbered tool kit, and yarn definition rows. Dividers (`-book`,
`-amethyst`, `-succulent`, `-slim`).

**Books.** Soon panel. Hero (`books-hero`). Reader DNA grid of 7 (`dna-*`). Era
timeline of 4 eras with cover rows, including tinted plates for books with no cover.
Current obsessions: 2 authors in a pair tier, 3 in a trio tier, grouped by series with
noted cards, then `books-stack-journal`. Reading notes: posts tagged books plus a
"more coming" card, a button, find-me links and a sign-off. 27 cover files. Dividers
(`-amethyst`, `-book`, `-succulent`).

**Tech.** Soon panel. Hero with script eyebrow (`about-building`). 5 numbered steps.
Divider (`divider-slim`).

**Links.** Standalone layout with no masthead or footer. Share button, avatar, name,
script bio, topics, 3 socials, latest posts (feature card plus thumbnail rows), 5
explore tiles, 4 pill links, badge.

### The live words are not all in the HTML

`site_text` holds **17 overrides** applied in the browser, and all 17 differ from the
file: 11 on About, 4 on Chickens and 2 on Succulents. Example: `about.html` says "Plants are the
clearest example" and readers see "Chickens are the clearest example." Copying from
the HTML would silently restore old text. Test pages must take copy from HTML with
overrides applied. `home_cards` also has 5 rows, currently matching the HTML.

## 2. Dynamic features and WordPress replacements

| Today | How it works | WordPress replacement |
| --- | --- | --- |
| Journal list, home strip | `posts` table in Supabase, read in the browser | Posts with Query Loop block |
| Post pages | Action builds `journal/<slug>/` every 30 min, `404.html` as fallback | `single.html`, immediately live |
| Tag shelves on Succulents and Books | Client filter on `posts.tags`, loose match | Query Loop filtered by tag. Tags need normalising on import. |
| Plants carousel | Native scroll snap plus arrow script | Query Loop with the same CSS, small front-end script |
| Book notes eyebrow | First tag that is not "books" | Hard to do in core. Show the category, or a small `render_block` filter. |
| Admin (`admin.html`) | Supabase password login, Markdown editor, preview, image bucket, home cards | wp-admin, block editor, Media Library |
| In-place editing (`edit.js`) | Signed-in session loads an editor over the page, saved to `site_text` | Block editor, "Edit page" admin bar link. Retired. |
| `home_cards`, `site_text` | Supabase overrides | Retired. The words live in the page. |
| Coming soon | `is-soon` class on `<html>`, CSS hides everything but the panel, `COMING_SOON` greys links, `noindex` tag. A curtain: the words stay in the source. | "Coming soon" page template that shows only the panel to readers, sets noindex, and leaves the rest unrendered. Stronger than today. Link marking via an `is-soon` class on the nav item or card. |
| Inventory | Live read of `public_plant_inventory` view, client search | Supabase plant block, **out of scope in Phase 1** |
| RSS | `rss` Edge Function | Native `/feed/`. Subscribers on the old URL need that function repointed later. |
| Sitemap, robots | Generated `sitemap.xml`, static `robots.txt` | Core sitemap and `wp_robots`. Exclude coming-soon pages. |
| OG, canonical, JSON-LD | By hand and by generator | Core canonical. OG and BlogPosting via a small theme function so no SEO plugin is needed, or a plugin if you prefer one. |
| Analytics | GoatCounter, GA4, `skipgc` flag for signed-in visits | Enqueued by the theme, skipped when logged in, **and only on the production host** so staging does not pollute the numbers. |
| Share button on Links | Web Share API, clipboard fallback | Small front-end script |
| `/ig` `/fb` `/threads` | HTML redirects with `?ref=` | Redirects, Phase 2 |
| Dates | Reader's own zone in the browser, Pacific in the generator | Site timezone `America/Los_Angeles` |

### 2.1 How journal pages are regenerated today (`.github/workflows/journal.yml`)

- **Data source:** Supabase `posts` (project `fsckwgicmvviefuivgza`), read with the
  public key taken from `site.js`: `status=eq.published`, newest first. RLS hides
  drafts, so the generator never sees them.
- **Trigger:** cron `7,37 * * * *`, manual dispatch, and pushes to `main` that touch
  `post.html`, `site.js` or `tools/build-journal.mjs`.
- **What it does:** `node tools/build-journal.mjs` writes `journal/<slug>/index.html`
  for each published post (head, masthead and footer copied from `post.html`, so OG
  tags, JSON-LD, GA and stamps follow), deletes pages for unpublished or renamed
  posts, rewrites `404.html` (the browser-side fallback for a post not yet built) and
  `sitemap.xml` (`STATIC_PAGES` minus `COMING_SOON`, plus posts).
- **Commit:** `git add -A journal 404.html sitemap.xml`, commits as
  `github-actions[bot]` only when something changed, pushes to `main`, then asks the
  Pages API for a build. This is why every local push needs `git pull --rebase`.
- **Today:** 2 published posts (`ninety-two-cups` 2026-10-29,
  `i-miss-the-old-internet` 2026-09-01).

**In WordPress all of this goes away.** A published post is live at its permalink
immediately with server-rendered OG tags, so the generator, the cron, the `404.html`
fallback, the bot commits and the generated sitemap are all replaced by core. Until
DNS cutover the workflow keeps running unchanged; it must be retired at cutover, or
it will keep committing to a repo that no longer serves the site. Its paths filter
does not include `wordpress/**`, so theme pushes do not trigger it.

### 2.2 `admin.html` replacement

| `admin.html` does | WordPress |
| --- | --- |
| Supabase email and password sign-in | wp-admin login plus a 2FA plugin (needs approval) |
| Markdown editor with live preview through `markdown.js` | Block editor. Markdown can be pasted and converts; import converts `body_html` to blocks. |
| Draft / published status, slug, excerpt, cover, tags | Native post status, slug, excerpt, featured image, tags |
| Image upload to the `journal` bucket | Media Library, with WebP / AVIF sizes from the theme |
| Home cards (`home_cards`) and in-place text (`site_text`, `edit.js`) | Editing the page itself. Both tables retire. |

Nothing in `admin.html` needs a custom replacement.

## 3. Design tokens

Source of truth is `style.css`. **The token table in `README.md` is stale** (`#FBF8F3`,
`#B4553F` and so on), as are the values in `images/ASSETS.md`. Do not port from those.

**Colors**

| Token | Value | Token | Value |
| --- | --- | --- | --- |
| `forest` / ink | `#2E3D2F` | `bg-wash` | `#F1EADE` |
| `terracotta` | `#C8644A` | `card` | `#FFFFFF` |
| `ivory` / bg | `#F7F3EB` | `ink-soft` | `#5E6857` |
| `sage` | `#A7B897` | `ink-faint` | `#8B9382` |
| `amethyst` | `#8D79A6` | `terracotta-dim` | `#B0543C` |
| `lavender` | `#D8CFE5` | `sage-deep` | `#4C5B3E` |
| `gold` | `#C0A15A` | `line` | `rgba(46,61,47,.10)` |

One-offs in rules: `#26321F` (primary hover), `#4F5D42` (sage tag), `#5F5178`
(amethyst tag), `#BCA9D4` (crochet bar), `#4A5D45` (book spine 1). Many `rgba()`
tints of terracotta, sage, amethyst and lavender are written out by value.

**Fonts.** Google Fonts today. Playfair Display 500, 600, italic 500. Inter 400, 500,
600. Caveat 500, 600. Fallbacks: Georgia, serif; system-ui, sans-serif; cursive. Mono
is system only. **Inter italic is not loaded**, so `<em>` in body text is browser-
synthesised. To stay identical, do not self-host an Inter italic.

**Type scale** (px)

| Role | Size | Role | Size |
| --- | --- | --- | --- |
| Hero h1 | 60, 44 at 900 | Post body | 17.5, line 1.75 |
| Post and soon h1 | 44, post 33 at 900 | Prose | 17 |
| Section h2 | 31 | Hero paragraph | 18.5 |
| Post h2 / h3 | 29 / 22 | Card text | 14 |
| Beat (Caveat) | 27, 19 at 900 | Eyebrows and labels | 11 to 12.5, tracked uppercase |
| Section sub (Caveat) | 22 | Base line height | 1.65 |

The sheet uses 35 distinct font sizes, so `theme.json` will carry a working subset for
the editor, and component CSS keeps the exact values.

**Spacing.** `.wrap` is 1180 max with 24 gutters, so 1132 of content. Sections pad 34,
and 44 at 900 and below (it grows on phones, and that is preserved). Hero pads 48 / 40.
Card grid gap 20, explore 18. Two-col gap 44, story gap 52, hero gap 48.

**Radii.** `--radius` 14 (cards, panels). Others: 10 (photos), 9 (buttons), 18, 20, 999
(pills), 6 (book cards), 4 (pinned note), 50% (avatars, icons).

**Shadow.** `0 1px 2px rgba(46,61,47,.04), 0 12px 28px -18px rgba(46,61,47,.30)`.
Section cards add `inset 0 2.5px 0` in a top-edge colour.

**Breakpoints** (max-width): 400, 440, 560, 640, 700, 760, 820, 900, 1000, 1080, 1100.
900 carries most of the layout, 700 the phone masthead.

**Global element selectors that will fight WordPress** and must be scoped in the port:
`nav{display:flex;gap:26px}`, `section{padding:34px 0}`, `img{display:block}`.

Known contrast failures per `CLAUDE.md` (masthead tagline, current nav, card titles,
footer small text, white on sage, amethyst on lavender) will be **carried over
unchanged**, because the brief says do not redesign.

## 4. Components

| Component | Key classes | Build |
| --- | --- | --- |
| Header, nav | `.masthead`, `.logo` | Template part: Site Logo, Navigation with overlay off. Sticky above 700, stacked below. |
| Footer | `footer`, `.foot-main`, `.foot-nav`, `.legal`, `.legal-tools` | Template part: badge, tagline, Navigation, Social Icons, legal text, Admin and Garden links. |
| Hero with italic accent | `.hero`, `h1 em` | Pattern: Columns, Heading with `<em>`, Buttons, Image. Variants: portrait, text-only, script eyebrow, books. |
| Buttons, CTA | `.btn-primary`, `.btn-ghost`, `.is-read`, `.go` | Button block styles |
| Section title | `.sec-head`, `.sprig`, `.sec-sub` | Pattern: Group, Heading, Image. Accent is a theme asset. |
| Dividers, ornaments | `.divider-wrap`, `.wash-art` | Patterns, one per divider. Theme assets. |
| Hobby card | `.card` + `.is-succulents` etc | Pattern: Group, Image, Heading, Paragraph. A whole-card link cannot be made in core, so the heading link stretches over the card with CSS. |
| Photo, trio, stacked cards | `.cards.photos`, `.trio`, `.stacked` | Patterns |
| Photo slot | `.photo-slot` | Group with text, replaceable by Image |
| Panel, stats, figures | `.panel`, `.stat-row`, `.wall-photo` | Group, Image with caption |
| Story row | `.story`, `.is-flipped`, `.prose`, `.beat` | Columns pattern |
| Numbered steps | `.steps`, `.step-n` | Group rows. Loses the `<ol>` meaning unless Group `tagName` works, to be tested. |
| Reader DNA | `.dna`, `.dna-item` | Pattern: Group grid of 7 |
| Era timeline | `.era-line`, `.era`, `.bookrow`, `.bookcard` | Pattern: Groups. Spine and marker from CSS. |
| Author spotlight | `.author-tier`, `.author-card`, `.series-name`, `.is-noted`, `.bc-plate` | Pattern: Groups, Image |
| Status card, progress | `.status-card`, `.bar` | Pattern, plus **one small custom block** for the progress value, since core has no way to edit a bar width |
| Pinned note, kit, tags | `.pinned`, `.kit`, `.tag` | Patterns, block styles |
| Post card, carousel, notes | `.post-card`, `.carousel-*`, `.note-card` | Query Loop patterns |
| Single post body | `.post-head`, `.post-body`, `.materials` | `single.html`, Materials as a block style |
| Coming-soon panel | `.soon-panel` | Pattern used by the page template |
| Links page | `.lt-*` | Blank template and patterns |
| Inventory | `.inv-*` | Later Supabase block |

Custom block count: one, written as plain JS with `block.json`, so no build step.

Asset split:

- **Theme** (design): `logo`, `logo-badge`, `favicon`, `apple-touch-icon`,
  `accent-lavender`, 5 `divider-*` plus `divider.png`, `ornament-*`.
- **Media Library** (content, Phase 2): `hero`, `about-*`, `books-*`, `section-*`,
  `dna-*`, `covers/`, `collection-wall`, `plant-*`, `avatar`, and the 11 `cups-*`
  files used by "Ninety-Two Cups".
- 18 of 101 tracked images are referenced nowhere: `about-3dprint-desk`, `app-*` (4),
  `books-banner`, `books-journal-lavender`, `books-library-card`, `books-reader`,
  `books-shelf-open`, `books-story-collage`, `divider-moon`, `first-attempt-folders`,
  `ornament-corner-a`, `ornament-corner-b`, `section-about`, `section-art`,
  `section-contact`. Not migrating unless you say so.

## 5. Untracked files

- **`images/blog/abg-app/`** (14 PNGs, 9.8 MB). Originals for the unpublished ABG app
  post, **not** the published journal images. The draft already references WebP
  copies in the Supabase `journal` bucket. Needed in Phase 2 only as a source.
  Untracked and must stay so (CLAUDE.md).
- **ABG app post draft** (`adcock-botanical-garden-app`). Inserted 2026-10-30 by
  `sql/007-abg-app-post.sql` (committed in `223b659` as the record) with
  `status = 'draft'`. Still a draft on 2026-11-03: it is not returned by the public
  API and has no `journal/` page. Its `cover_url` is a relative repo path
  (`images/collection-wall.jpg`), which will not resolve in WordPress; the import
  must map it to the Media Library copy. Drafts need an authenticated export.
- **Published post images are split.** "Ninety-Two Cups" uses 11 repo files in
  `images/cups-*`, covers and other images are in the Supabase `journal` bucket.
  Import must cover both, and drafts need an authenticated export because RLS hides
  them from the public API.
- **`theme-abg.html`, `theme-rich.html`.** Palette experiments on an old stylesheet
  stamp. Not part of the site. They do not matter for the migration.
- **`godaddy-workflow.yml`.** Stays untracked in the root. Step 2 writes a separate
  `deploy-staging.yml` from it. As provided it syncs the repo root when
  `deployment_dest` is blank, and has never run (0.4).
- **`.gitignore`** has an uncommitted change adding `.env` (one line, as of
  2026-11-03). Worth committing.

## 6. Phase 1 conflicts to resolve

1. **Test pages need images, but content images are Phase 2.** Pages cannot be
   compared or the "swap image" check run without them.
2. **Query Loops need posts, but journal import is out of scope.** The journal and
   home comparisons would be empty.
3. **Draft pages are only visible signed in.** Screenshots and editor checks need a
   staging admin session in Chrome.
4. **"git status clean" conflicts with leaving the untracked files alone.** Ignoring
   them through `.git/info/exclude` keeps them local without committing anything.
5. **Site Editor edits override theme files.** Once a template part is saved in the
   Site Editor, the database copy wins and later deploys of that file are ignored
   until it is reset. Patterns are also copied into pages when inserted, so pattern
   markup changes do not reach existing pages. CSS changes do reach them.
