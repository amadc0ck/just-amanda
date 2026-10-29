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

/* ---------- pages not open yet ----------
   Pages still being written. For the public, every link to one is greyed out
   and marked "soon", and the page itself shows only its coming-soon panel
   (the CSS hides the rest while <html> carries class="is-soon").

   A signed-in session sees everything as normal, because the words on those
   pages are edited in place and that needs the real page. A banner says the
   page is hidden, so it is never mistaken for live.

   This is a curtain, not a lock: the words are in the HTML for anyone who
   views source. Open a page by removing it here and following the note in
   its own <head>. */
const COMING_SOON = ["chickens.html", "crochet.html", "books.html", "tech.html"];

function hasSession() {
  try { const a = JSON.parse(localStorage.getItem("ja_auth") || "null"); return !!(a && a.access_token); }
  catch (e) { return false; }
}

const isSoonLink = (a) => COMING_SOON.includes((a.getAttribute("href") || "").split(/[?#]/)[0]);

/* GoatCounter's own opt-out: count.js skips any browser carrying skipgc=t.
   Set whenever a signed-in session is seen, so my visits stay out of the
   numbers. It persists after signing out, which is the point: this is my
   browser, not a reader's. */
if (hasSession()) {
  try { localStorage.setItem("skipgc", "t"); } catch (e) { /* storage blocked: counted, harmless */ }
}

/* Run again after the text overrides land, since those can bring links in. */
function markSoonLinks() {
  if (hasSession()) return;
  document.querySelectorAll("a[href]").forEach((a) => {
    if (!isSoonLink(a) || a.classList.contains("is-soon")) return;
    a.classList.add("is-soon");
    a.setAttribute("aria-disabled", "true");
    a.title = "Coming soon";
  });
}

(function () {
  const root = document.documentElement;
  if (hasSession()) {
    if (root.classList.contains("is-soon")) {
      root.classList.remove("is-soon");
      const head = document.querySelector(".masthead");
      if (head) head.insertAdjacentHTML("afterend",
        `<div class="preview-banner">Coming soon &middot; hidden from the public, only you can see this page</div>`);
    }
    return;
  }
  markSoonLinks();
  // Catches links injected after marking, too. A greyed link that still
  // navigated would be worse than no marking at all.
  document.addEventListener("click", (e) => {
    const a = e.target.closest && e.target.closest("a[href]");
    if (a && isSoonLink(a)) e.preventDefault();
  });
})();

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
    markSoonLinks();
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
