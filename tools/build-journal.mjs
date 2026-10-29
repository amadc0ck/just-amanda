#!/usr/bin/env node
/* Static pages for the journal, from the published posts in Supabase.

     node tools/build-journal.mjs

   Run every 30 minutes by .github/workflows/journal.yml, which commits any
   change. Safe to run by hand: it only ever writes journal/, 404.html and
   sitemap.xml, and running it twice in a row changes nothing.

   WHY THIS EXISTS. post.html fills itself in with JavaScript, and the things
   that read a link before a person does (Facebook, iMessage, Slack, and to a
   lesser degree Google) do not run JavaScript. Every shared post previewed as
   "Journal · just amanda" with no image. A static page per post, carrying its
   own title, description, image and the post itself, fixes that.

   WHY post.html IS THE TEMPLATE. The masthead, footer, fonts and asset stamps
   are taken from post.html at build time rather than copied in here, so a nav
   change or a stamp bump flows through on the next run with nothing to keep in
   step. Only <title>, the description, the <article> and the inline script are
   replaced.

   This is a generator for post pages, not a build step for the site. Every
   other page is still the file you edit, and post.html still works on its own:
   if this never ran again, /journal/<slug>/ would fall through to 404.html and
   render in the browser exactly as before.

   Node 20+, no dependencies. */
import fs from "node:fs";
import path from "node:path";

const SITE = "https://justamanda.net";
const ROOT = path.resolve(path.dirname(new URL(import.meta.url).pathname), "..");
const read = (f) => fs.readFileSync(path.join(ROOT, f), "utf8");

// The URL and publishable key come from site.js so there is one copy of each.
const siteJs = read("site.js");
const SB_URL = siteJs.match(/url:\s*"([^"]+)"/)[1];
const SB_KEY = siteJs.match(/key:\s*"([^"]+)"/)[1];

const esc = (s) => String(s ?? "").replace(/[&<>"']/g, (c) =>
  ({ "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" }[c]));
const abs = (u) => (/^https?:/i.test(u) ? u : `${SITE}/${String(u).replace(/^\.?\//, "")}`);
// Same wording as fmtDate in site.js, fixed to Pacific so the build server's
// zone never moves an evening post onto the next day.
const fmtDate = (v) => new Date(v).toLocaleDateString("en-US",
  { month: "long", day: "numeric", year: "numeric", timeZone: "America/Los_Angeles" });

/* <base href="/"> makes every relative path in the template and in the post
   body (images/..., journal.html, plants.html) resolve from the site root,
   which is what they were written against. The one thing it changes is a bare
   "#anchor" link, which would point at the home page; post bodies have none. */
function withBase(html) {
  return html.replace('<meta charset="utf-8">', '<meta charset="utf-8">\n<base href="/">');
}

function stripPostScript(html) {
  const marker = 'const host = document.getElementById("post");';
  const at = html.indexOf(marker);
  if (at < 0) throw new Error("post.html: inline post script not found; update build-journal.mjs");
  const open = html.lastIndexOf("<script>", at);
  const close = html.indexOf("</script>", at) + "</script>".length;
  return html.slice(0, open) + html.slice(close).replace(/^\n/, "");
}

function article(p) {
  const cover = p.cover_url
    ? `<img class="post-cover" src="${esc(p.cover_url)}" alt="${esc(p.cover_alt || "")}">` : "";
  const tags = (p.tags || []).length
    ? `<div class="tags">${p.tags.map((t) => `<span class="tag is-amethyst">${esc(t)}</span>`).join("")}</div>` : "";
  const dek = p.excerpt ? `\n        <p class="dek">${esc(p.excerpt)}</p>` : "";
  // body_html is rendered from Markdown at save time by the only account that
  // can write posts (RLS), and post.html inserts it as-is too.
  return `<article id="post">
  <div class="wrap">
      <div class="post-head">
        <a class="back" href="journal.html">&larr; Journal</a>
        <span class="eyebrow">${esc(fmtDate(p.published_at))}</span>
        <h1>${esc(p.title)}</h1>${dek}
      </div>
      ${cover}
      <div class="post-body">${p.body_html || ""}${tags}</div>
  </div>
</article>`;
}

function headTags(p, url) {
  const desc = p.excerpt || `${p.title}, from the just amanda journal.`;
  const image = p.cover_url ? abs(p.cover_url) : `${SITE}/images/hero.png`;
  const ld = {
    "@context": "https://schema.org", "@type": "BlogPosting",
    headline: p.title, description: desc, image, url,
    datePublished: p.published_at, dateModified: p.updated_at || p.published_at,
    author: { "@type": "Person", name: "Amanda", url: `${SITE}/about.html` },
    keywords: (p.tags || []).join(", "),
  };
  return `<link rel="canonical" href="${esc(url)}">
<meta property="og:type" content="article">
<meta property="og:site_name" content="just amanda">
<meta property="og:title" content="${esc(p.title)}">
<meta property="og:description" content="${esc(desc)}">
<meta property="og:url" content="${esc(url)}">
<meta property="og:image" content="${esc(image)}">
${p.cover_alt ? `<meta property="og:image:alt" content="${esc(p.cover_alt)}">\n` : ""}<meta property="article:published_time" content="${esc(p.published_at)}">
<meta name="twitter:card" content="summary_large_image">
<script type="application/ld+json">${JSON.stringify(ld).replace(/</g, "\\u003c")}</script>
`;
}

function postPage(template, p) {
  const url = `${SITE}/journal/${encodeURIComponent(p.slug)}/`;
  let html = withBase(stripPostScript(template));
  html = html.replace(/<title>[^<]*<\/title>/, `<title>${esc(p.title)} · just amanda</title>`);
  html = html.replace(/<meta name="description" content="[^"]*">/,
    `<meta name="description" content="${esc(p.excerpt || p.title)}">\n${headTags(p, url)}`);
  html = html.replace(/<article id="post">[\s\S]*?<\/article>/, article(p));
  return html;
}

// Pages worth a search engine's time. The coming-soon pages carry noindex, so
// they stay out until they open; admin, the style guide and the short
// redirect pages (ig, fb, threads) never belong here.
const STATIC_PAGES = ["", "about.html", "plants.html", "inventory.html", "journal.html", "links"];

function sitemap(posts) {
  const soon = (siteJs.match(/const COMING_SOON = \[([^\]]*)\]/) || [, ""])[1];
  const open = STATIC_PAGES.filter((f) => !soon.includes(`"${f}"`));
  const urls = [
    ...open.map((f) => `  <url><loc>${SITE}/${f}</loc></url>`),
    ...posts.map((p) => `  <url><loc>${SITE}/journal/${encodeURIComponent(p.slug)}/</loc>` +
      `<lastmod>${(p.updated_at || p.published_at).slice(0, 10)}</lastmod></url>`),
  ];
  return `<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
${urls.join("\n")}
</urlset>
`;
}

function write(rel, content) {
  const f = path.join(ROOT, rel);
  fs.mkdirSync(path.dirname(f), { recursive: true });
  if (fs.existsSync(f) && fs.readFileSync(f, "utf8") === content) return false;
  fs.writeFileSync(f, content);
  return true;
}

const res = await fetch(
  `${SB_URL}/rest/v1/posts?select=slug,title,excerpt,body_html,cover_url,cover_alt,tags,published_at,updated_at` +
    `&status=eq.published&order=published_at.desc`,
  { headers: { apikey: SB_KEY, Accept: "application/json" } });
if (!res.ok) throw new Error(`posts: ${res.status} ${await res.text()}`);
const posts = (await res.json()).filter((p) => /^[a-z0-9-]+$/.test(p.slug));

const template = read("post.html");
const changed = [];
for (const p of posts) {
  if (write(`journal/${p.slug}/index.html`, postPage(template, p))) changed.push(`journal/${p.slug}/`);
}

// A post that was unpublished or renamed loses its static page; its address
// then falls through to 404.html, which says it is not found.
const live = new Set(posts.map((p) => p.slug));
const dir = path.join(ROOT, "journal");
for (const d of fs.existsSync(dir) ? fs.readdirSync(dir) : []) {
  if (!live.has(d)) { fs.rmSync(path.join(dir, d), { recursive: true }); changed.push(`removed journal/${d}/`); }
}

// GitHub Pages serves 404.html for any missing address. It is post.html with a
// root base, so a post too new to have its static page still renders.
const notFound = withBase(template).replace('<meta name="description"', '<meta name="robots" content="noindex">\n<meta name="description"');
if (write("404.html", notFound)) changed.push("404.html");
if (write("sitemap.xml", sitemap(posts))) changed.push("sitemap.xml");

console.log(`${posts.length} published post(s); ${changed.length ? "changed: " + changed.join(", ") : "nothing changed"}`);
