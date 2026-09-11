/* justamanda.net shared front-end helpers.

   The journal lives in the same Supabase project as the garden app. The
   publishable key below is public by design; RLS is what actually keeps drafts
   private, so never relax posts_public_read. */
const SB = {
  url: "https://fsckwgicmvviefuivgza.supabase.co",
  key: "sb_publishable_60U_SBa3qnwU2jhWzyylnA_-6A3LMDt",
};

function escapeHtml(s) {
  return String(s == null ? "" : s).replace(/[&<>"']/g, (c) =>
    ({ "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" }[c]));
}

/* published_at is a timestamptz, so Date parses it correctly and
   toLocaleDateString renders it in the reader's own zone. Do not reach for
   toISOString here, because it would drag evening Pacific posts onto the next day. */
function fmtDate(v) {
  if (!v) return "";
  const d = new Date(v);
  return isNaN(d) ? "" : d.toLocaleDateString(undefined, { month: "long", day: "numeric", year: "numeric" });
}

async function sbGet(path, token) {
  const headers = { apikey: SB.key, Accept: "application/json" };
  if (token) headers.Authorization = `Bearer ${token}`;
  const res = await fetch(`${SB.url}/rest/v1/${path}`, { headers });
  if (!res.ok) throw new Error(`${res.status}: ${await res.text()}`);
  return res.json();
}

function postHref(slug) { return `post.html?slug=${encodeURIComponent(slug)}`; }

function postCard(p) {
  const cover = p.cover_url
    ? `<img class="cover" src="${escapeHtml(p.cover_url)}" alt="" loading="lazy">` : "";
  const excerpt = p.excerpt ? `<p>${escapeHtml(p.excerpt)}</p>` : "";
  return `<a class="post-card" href="${postHref(p.slug)}">${cover}
    <span class="pc-body">
      <span class="eyebrow">${escapeHtml(fmtDate(p.published_at))}</span>
      <h3>${escapeHtml(p.title)}</h3>${excerpt}
      <span class="go">Read more &rarr;</span>
    </span></a>`;
}

/* ---------- page text overrides ----------
   Every editable block on the site carries data-edit="page.tag-n" and keeps its
   authored words in the HTML. This replaces one only when site_text holds a row
   for it, so the page is correct with no JavaScript, no network and no database,
   and a failed fetch leaves the real writing on screen.

   innerHTML, not textContent, because 29% of the blocks carry an <em>, a link or
   a <strong> and losing those would be worse than not editing at all. The value
   is rendered to HTML once in admin.html when it is saved, the same way
   posts.body_html is, so no reader ever downloads a Markdown parser and nothing
   arbitrary is being evaluated here: writes are scoped by RLS to one address. */
async function applyTextOverrides() {
  const nodes = document.querySelectorAll("[data-edit]");
  if (!nodes.length) return;
  try {
    const rows = await sbGet("site_text?select=key,value_html");
    const map = new Map(rows.map((r) => [r.key, r.value_html]));
    nodes.forEach((n) => {
      const v = map.get(n.dataset.edit);
      if (v) n.innerHTML = v;
    });
  } catch (e) {
    /* The authored text stands. Never worth a visible error on a reader's page. */
    console.warn("site_text overrides unavailable:", e.message);
  }
}

/* site.js is loaded at the end of <body>, so DOMContentLoaded may already have
   fired by the time this runs. Check rather than assume. */
if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", applyTextOverrides);
} else {
  applyTextOverrides();
}

/* ---------- editing, for one person ----------
   A reader never fetches edit.js or markdown.js. They are pulled in only when a
   session is sitting in localStorage, which is why signing in can unlock editing
   on the real pages without costing everyone else a download.

   admin.html is excluded: it loads site.js for the helpers and has its own
   editing already. */
(function () {
  if (/admin\.html$/.test(location.pathname)) return;
  var a = null;
  try { a = JSON.parse(localStorage.getItem("ja_auth") || "null"); } catch (e) { return; }
  if (!a || !a.access_token) return;
  if (!document.querySelector("[data-edit]")) return;
  ["markdown.js?v=2026-09-12.2", "edit.js?v=2026-09-12.2"].forEach(function (src) {
    var el = document.createElement("script");
    el.src = src;
    el.async = false;          /* markdown.js must be parsed before edit.js runs */
    document.head.appendChild(el);
  });
})();
