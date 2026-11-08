#!/usr/bin/env python3
"""Builds a one-shot JS loader that puts the draft pages and the two live posts on
a WordPress site for side-by-side comparison (staging first, production from
2026-11-08, which is not live until DNS cutover). Never deployed with the theme.

    python3 wordpress/tools/build-staging-loader.py posts.json > loader.js

Paste or inject loader.js into a signed-in wp-admin tab on staging. It runs through
the REST API with that session's nonce (wp.apiFetch), so no password is involved:

  1. imports each image the pages reference, fetched from the live site
     (justamanda.net/images/..., which sends Access-Control-Allow-Origin: *),
     titled "ja:<path>" so a second run reuses it instead of uploading again;
  2. creates the posts' tags and the posts, published, with their covers;
  3. creates each page as a DRAFT with its template, tokens filled in.

Re-running updates the same pages and posts by slug.
"""
import json, os, re, sys

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
PAGES = [  # slug, title, file, template
    ("home", "Home", "home.html", ""),
    ("about", "About", "about.html", ""),
    ("plants", "Succulents", "plants.html", ""),
    ("inventory", "Inventory", "inventory.html", ""),
    ("journal", "Journal", "journal.html", ""),
    ("links", "Links", "links.html", "page-links"),
    ("chickens", "Chickens", "chickens.html", "coming-soon"),
    ("crochet", "Crochet", "crochet.html", "coming-soon"),
    ("books", "Books", "books.html", "coming-soon"),
    ("tech", "Tech", "tech.html", "coming-soon"),
]
pages, images = [], set()
for slug, title, f, tpl in PAGES:
    p = os.path.join(ROOT, "content", f)
    if not os.path.exists(p):
        continue
    markup = open(p).read()
    images |= set(re.findall(r"\{\{(?:id|url):([^}]+)\}\}", markup))
    pages.append({"slug": slug, "title": title, "template": tpl, "markup": markup})
posts = json.load(open(sys.argv[1]))

print("const JA = " + json.dumps({"pages": pages, "images": sorted(images), "posts": posts}) + ";")
print(r"""
(async () => {
  const api = wp.apiFetch, log = (...a) => console.log("[ja-loader]", ...a);
  const all = async (path) => { let out = [], page = 1;
    for (;;) { const r = await api({ path: `${path}${path.includes("?") ? "&" : "?"}per_page=100&page=${page}&context=edit`, parse: false });
      out = out.concat(await r.json()); if (page >= +r.headers.get("X-WP-TotalPages")) return out; page++; } };

  const media = new Map((await all("/wp/v2/media")).filter(m => m.title.raw.startsWith("ja:")).map(m => [m.title.raw.slice(3), m]));
  const upload = async (key, url) => {
    if (media.has(key)) return media.get(key);
    const blob = await (await fetch(url)).blob();
    // From the URL, not the key: a cover's key is "cover:<slug>", which has no
    // extension, and WordPress refuses an upload it cannot type by name.
    const name = new URL(url).pathname.split("/").pop();
    const m = await api({ path: "/wp/v2/media", method: "POST", body: blob,
      headers: { "Content-Disposition": `attachment; filename="${name}"`, "Content-Type": blob.type } });
    const done = await api({ path: `/wp/v2/media/${m.id}`, method: "POST", data: { title: "ja:" + key } });
    media.set(key, done); log("image", key, done.id); return done;
  };
  for (const f of JA.images) await upload(f, `https://justamanda.net/images/${f}`);

  const tags = new Map((await all("/wp/v2/tags")).map(t => [t.slug, t.id]));
  const tagId = async (name) => { const slug = name.toLowerCase().trim().replace(/[^a-z0-9]+/g, "-").replace(/^-|-$/g, "");
    if (!tags.has(slug)) tags.set(slug, (await api({ path: "/wp/v2/tags", method: "POST", data: { name, slug } })).id);
    return tags.get(slug); };

  const existing = async (type, slug) => (await api({ path: `/wp/v2/${type}?slug=${slug}&status=publish,draft,private&context=edit` }))[0];
  for (const p of JA.posts) {
    const cover = await upload("cover:" + p.slug, p.cover_url);
    if (p.cover_alt) await api({ path: `/wp/v2/media/${cover.id}`, method: "POST", data: { alt_text: p.cover_alt } });
    const data = { status: "publish", title: p.title, slug: p.slug, excerpt: p.excerpt, content: p.body_html,
      date_gmt: p.published_at.slice(0, 19), featured_media: cover.id, tags: await Promise.all(p.tags.map(tagId)) };
    const was = await existing("posts", p.slug);
    const r = await api({ path: was ? `/wp/v2/posts/${was.id}` : "/wp/v2/posts", method: "POST", data });
    log("post", p.slug, r.id);
  }

  for (const pg of JA.pages) {
    let html = pg.markup;
    for (const [k, m] of media) html = html.split(`{{id:${k}}}`).join(String(m.id)).split(`{{url:${k}}}`).join(m.source_url);
    for (const t of new Set([...html.matchAll(/\{\{tag:([^}]+)\}\}/g)].map(x => x[1]))) html = html.split(`{{tag:${t}}}`).join(String(await tagId(t)));
    if (/\{\{/.test(html)) { log("UNFILLED TOKEN in", pg.slug, html.match(/\{\{[^}]+\}\}/)[0]); continue; }
    const was = await existing("pages", pg.slug);
    const data = { status: was ? was.status : "draft", title: pg.title, slug: pg.slug, content: html, template: pg.template };
    const r = await api({ path: was ? `/wp/v2/pages/${was.id}` : "/wp/v2/pages", method: "POST", data });
    log("page", pg.slug, r.id, r.status);
  }
  log("DONE");
})().catch(e => console.error("[ja-loader] FAILED", e));
""")
