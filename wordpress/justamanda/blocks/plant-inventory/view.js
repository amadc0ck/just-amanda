/* Plant inventory block (render.php). Ported from the static site's
   inventory.html on 2026-11-08, logic unchanged; only the element lookups are
   scoped to the block instead of page ids. */
(function () {
  const escapeHtml = (s) => String(s == null ? "" : s).replace(/[&<>"']/g, (c) =>
    ({ "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" })[c]);

  /* Botanical convention: genus and epithet italic, the hybrid sign and the
     cultivar upright. Composed from parts when they exist, because only the parts
     can be marked up correctly; the free-text name is the fallback. */
  function taxonName(t) {
    const g = (t.genus || "").trim();
    const ep = (t.species_epithet || "").trim();
    const cv = (t.cultivar || "").trim();
    const infra = (t.infraspecific || "").trim();
    if (g && (ep || cv)) {
      let out = `<em>${escapeHtml(g)}</em>`;
      if (t.is_hybrid) out += " &times;";
      if (ep) out += ` <em>${escapeHtml(ep)}</em>`;
      if (infra) out += " " + infraHtml(infra);
      if (cv) out += ` &lsquo;${escapeHtml(cv)}&rsquo;`;
      return out;
    }
    const bn = (t.botanical_name || "").trim();
    if (bn) return `<em>${escapeHtml(bn)}</em>`;
    const wl = (t.working_label || "").trim();
    return wl ? escapeHtml(wl) : "<span class='muted'>Unidentified</span>";
  }

  /* Stored with the rank as written on the tag: "var. erinacea". The rank is
     upright and the epithet italic. A bare word with no rank reads as var.,
     which is overwhelmingly the common case. */
  function infraHtml(s) {
    const m = s.match(/^\s*(var\.|subsp\.|ssp\.|f\.)?\s*(.+)$/i);
    const rank = m && m[1] ? m[1] : "var.";
    const ep = m ? m[2] : s;
    return `${escapeHtml(rank)} <em>${escapeHtml(ep)}</em>`;
  }

  function humanize(v) {
    if (Array.isArray(v)) return v.map(humanize).join(", ");
    return String(v).replace(/_/g, " ").replace(/^./, (c) => c.toUpperCase());
  }

  function filled(v) {
    if (v == null) return false;
    if (Array.isArray(v)) return v.length > 0;
    return String(v).trim() !== "";
  }

  /* Only the fields this species actually carries. The records are still being
     filled in, so a blank field is an unfinished record, not a fact. */
  const FIELDS = [
    ["growth_habit", "Habit"], ["mature_size", "Mature size"], ["bloom_season", "Blooms"],
    ["native_range", "Native to"], ["hardy_to", "Hardy to"], ["water_needs", "Water"],
    ["light_conditions", "Light"], ["origin", "Origin"],
  ];

  /* The plain botanical name, for an outbound search: no markup, no quotes. */
  function searchTerm(t) {
    const g = (t.genus || "").trim(), ep = (t.species_epithet || "").trim();
    if (g && ep) return `${g} ${ep}`;
    if (g) return g;
    return (t.botanical_name || t.working_label || "").trim();
  }

  function card(t) {
    const rows = FIELDS.filter(([k]) => filled(t[k]))
      .map(([k, label]) => `<div class="inv-row"><dt>${label}</dt><dd>${escapeHtml(humanize(t[k]))}</dd></div>`)
      .join("");
    const meta = [
      filled(t.family) ? escapeHtml(t.family) : null,
      `${t.specimens} ${t.specimens === 1 ? "specimen" : "specimens"}`,
    ].filter(Boolean).join(" &middot; ");
    return `<article class="inv-card">
      <div class="inv-head">
        <h2>${taxonName(t)}</h2>
        ${filled(t.common_name) ? `<p class="inv-common">${escapeHtml(t.common_name)}</p>` : ""}
        <p class="inv-meta">${meta}${t.frost_tender ? ` &middot; <span class="inv-tender">frost tender</span>` : ""}</p>
      </div>
      ${filled(t.description) ? `<p class="inv-desc">${escapeHtml(t.description)}</p>` : ""}
      ${rows ? `<dl class="inv-fields">${rows}</dl>` : ""}
      <p class="inv-verify">
        <a href="https://powo.science.kew.org/results?q=${encodeURIComponent(searchTerm(t))}" rel="nofollow">Name &rarr;</a>
        <a href="https://www.smgrowers.com/search.asp?txtsearch=${encodeURIComponent(searchTerm(t))}" rel="nofollow">Growing &rarr;</a>
      </p>
    </article>`;
  }

  function haystack(t) {
    return [t.botanical_name, t.common_name, t.family, t.genus, t.species_epithet, t.cultivar, t.working_label]
      .filter(Boolean).join(" ").toLowerCase();
  }

  document.querySelectorAll(".ja-inventory").forEach(async (root) => {
    const search = root.querySelector(".search-input");
    const count = root.querySelector(".inv-count");
    const list = root.querySelector(".inv-list");
    let ALL = [];
    const draw = () => {
      const q = (search.value || "").trim().toLowerCase();
      const shown = q ? ALL.filter((t) => haystack(t).includes(q)) : ALL;
      const specimens = shown.reduce((n, t) => n + (t.specimens || 0), 0);
      count.innerHTML = shown.length
        ? `<strong>${shown.length}</strong> species &middot; <strong>${specimens}</strong> ${specimens === 1 ? "specimen" : "specimens"}${
           q ? ` matching &ldquo;${escapeHtml(q)}&rdquo;` : ""}`
        : `Nothing matches &ldquo;${escapeHtml(q)}&rdquo;.`;
      list.innerHTML = shown.map(card).join("");
    };
    try {
      const res = await fetch(`${root.dataset.sbUrl}/rest/v1/public_plant_inventory?select=*`,
        { headers: { apikey: root.dataset.sbKey, Accept: "application/json" } });
      if (!res.ok) throw new Error(`${res.status}: ${await res.text()}`);
      ALL = await res.json();
      // Sorted here, not in the query: the display name is composed from parts.
      // Unnamed taxa sort last; an empty card is a poor thing to open on.
      ALL.sort((a, b) => {
        const an = searchTerm(a), bn = searchTerm(b);
        if (!an !== !bn) return an ? -1 : 1;
        return an.localeCompare(bn);
      });
      draw();
      search.addEventListener("input", draw);
    } catch (e) {
      count.textContent = "";
      list.innerHTML = `<div class="panel"><p style="margin:0;">The catalogue could not be reached just now.
        <span class="muted">(${escapeHtml(e.message)})</span></p></div>`;
    }
  });
})();
