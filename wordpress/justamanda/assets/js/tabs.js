/* Tabbed sections (.is-tabbed, e.g. the Books eras, 2026-11-08). Each .story inside
   becomes a panel; a tab row is built from each panel's small label (.era-label)
   and heading. Without this script, or with JavaScript off, every panel simply
   shows in order, so nothing is lost. ARIA tabs pattern: arrow keys, Home and End
   move between tabs; each panel gets an address (#heading-slug) so a tab can be
   linked to directly. */
document.querySelectorAll(".is-tabbed").forEach((section, n) => {
  const panels = [...section.querySelectorAll(".story")];
  if (panels.length < 2) return;
  const slug = (s) => s.toLowerCase().normalize("NFKD").replace(/[^\w\s-]/g, "").trim().replace(/\s+/g, "-");
  const list = document.createElement("div");
  list.className = "tab-list";
  list.setAttribute("role", "tablist");
  const tabs = panels.map((panel, i) => {
    const heading = panel.querySelector("h2, h3");
    const label = panel.querySelector(".era-label");
    const id = slug(heading ? heading.textContent : "tab-" + (i + 1)) || "tab-" + n + "-" + i;
    panel.id = panel.id || id;
    panel.setAttribute("role", "tabpanel");
    panel.setAttribute("tabindex", "0");
    const tab = document.createElement("button");
    tab.type = "button";
    tab.className = "tab";
    tab.id = "tab-" + panel.id;
    tab.setAttribute("role", "tab");
    tab.setAttribute("aria-controls", panel.id);
    tab.innerHTML = (label ? '<span class="tab-label"></span>' : "") + '<span class="tab-title"></span>';
    if (label) tab.querySelector(".tab-label").textContent = label.textContent;
    tab.querySelector(".tab-title").textContent = heading ? heading.textContent : "Part " + (i + 1);
    panel.setAttribute("aria-labelledby", tab.id);
    list.appendChild(tab);
    return tab;
  });
  panels[0].parentNode.insertBefore(list, panels[0]);
  section.classList.add("tabs-ready");

  const show = (i, focus, updateHash) => {
    tabs.forEach((t, j) => {
      const on = i === j;
      t.setAttribute("aria-selected", on ? "true" : "false");
      t.tabIndex = on ? 0 : -1;
      panels[j].hidden = !on;
    });
    if (focus) tabs[i].focus();
    if (updateHash) history.replaceState(null, "", "#" + panels[i].id);
  };
  tabs.forEach((t, i) => {
    t.addEventListener("click", () => show(i, false, true));
    t.addEventListener("keydown", (e) => {
      const last = tabs.length - 1;
      const to = { ArrowRight: i === last ? 0 : i + 1, ArrowLeft: i === 0 ? last : i - 1, Home: 0, End: last }[e.key];
      if (to === undefined) return;
      e.preventDefault();
      show(to, true, true);
    });
  });
  const fromHash = panels.findIndex((p) => "#" + p.id === location.hash);
  show(fromHash >= 0 ? fromHash : 0, false, false);
  if (fromHash >= 0) section.scrollIntoView({ block: "start" });
});
