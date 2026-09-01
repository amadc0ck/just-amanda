/* justamanda.net — shared front-end helpers.

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
   toISOString here — it would drag evening Pacific posts onto the next day. */
function fmtDate(v) {
  if (!v) return "";
  const d = new Date(v);
  return isNaN(d) ? "" : d.toLocaleDateString(undefined, { month: "long", day: "numeric", year: "numeric" });
}

async function sbGet(path, token) {
  const headers = { apikey: SB.key, Accept: "application/json" };
  if (token) headers.Authorization = `Bearer ${token}`;
  const res = await fetch(`${SB.url}/rest/v1/${path}`, { headers });
  if (!res.ok) throw new Error(`${res.status} — ${await res.text()}`);
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
