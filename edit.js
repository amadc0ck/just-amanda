/* justamanda.net in-place editing.

   Loaded ONLY when a signed-in session is present, by the loader at the bottom
   of site.js. A reader never fetches this file, so the public pages stay as
   light as they were.

   The model: there is no separate admin view of the site's words. You are on
   the real page, in the real design, and signing in unlocks editing on it. The
   editor that used to live in an iframe inside admin.html is this, moved out to
   where the words actually are.

   Saving reuses the same session admin.html keeps in localStorage. */
(function () {
  "use strict";

  var KEY = "ja_auth";
  var pending = new Map();   // key -> markdown ("" means revert to the HTML)
  var authored = new Map();  // key -> words as written in the file
  var saved = new Map();     // key -> row currently in site_text
  var on = false;

  function auth() { try { return JSON.parse(localStorage.getItem(KEY) || "null"); } catch (e) { return null; } }
  function token() { var a = auth(); return a && a.access_token; }

  async function refresh() {
    var a = auth();
    if (!a || !a.refresh_token) return false;
    var res = await fetch(SB.url + "/auth/v1/token?grant_type=refresh_token", {
      method: "POST",
      headers: { apikey: SB.key, "Content-Type": "application/json" },
      body: JSON.stringify({ refresh_token: a.refresh_token }),
    });
    var d = await res.json();
    if (!res.ok || !d.access_token) return false;
    localStorage.setItem(KEY, JSON.stringify(d));
    return true;
  }

  /* Same shape as admin's api(), including the lesson that cost a save: never
     assume a response has a body. PostgREST answers a plain POST with 201 and
     nothing at all. */
  async function api(path, opts, retry) {
    opts = opts || {};
    var res = await fetch(SB.url + "/rest/v1/" + path, Object.assign({}, opts, {
      headers: Object.assign({
        apikey: SB.key, Authorization: "Bearer " + token(),
        "Content-Type": "application/json",
      }, opts.headers || {}),
    }));
    if (res.status === 401 && retry !== false && (await refresh())) return api(path, opts, false);
    if (!res.ok) throw new Error(res.status + ": " + (await res.text()));
    var body = await res.text();
    return body ? JSON.parse(body) : null;
  }

  function md(s) {
    /* markdown.js owns the renderer. Using a second copy here would let the two
       drift and render the same input differently in the editor and on save. */
    return typeof inlineMd === "function" ? inlineMd(s) : escapeHtml(s);
  }

  function css() {
    var el = document.createElement("style");
    el.textContent = [
      ".ja-bar{position:fixed;left:14px;bottom:14px;z-index:99998;display:flex;gap:7px;",
      "  align-items:center;background:#2E3D2F;color:#F7F3EB;border-radius:11px;padding:8px 10px;",
      "  box-shadow:0 10px 30px -12px rgba(0,0,0,.55);font:13px/1 Inter,system-ui,sans-serif;}",
      ".ja-bar button,.ja-bar a{font:inherit;font-size:11.5px;font-weight:600;letter-spacing:.7px;",
      "  text-transform:uppercase;padding:8px 12px;border-radius:7px;border:1px solid rgba(247,243,235,.28);",
      "  background:transparent;color:#F7F3EB;cursor:pointer;text-decoration:none;}",
      ".ja-bar button:hover,.ja-bar a:hover{background:rgba(247,243,235,.14);}",
      ".ja-bar button.on{background:#C8644A;border-color:#C8644A;}",
      ".ja-bar button.save{background:#F7F3EB;color:#2E3D2F;border-color:#F7F3EB;}",
      ".ja-bar button:disabled{opacity:.45;cursor:default;}",
      ".ja-bar .ja-sep{width:1px;height:20px;background:rgba(247,243,235,.25);}",
      "body.ja-edit [data-edit]{outline:1.5px dashed transparent;outline-offset:3px;cursor:text;",
      "  border-radius:3px;transition:outline-color .12s,background-color .12s;}",
      "body.ja-edit [data-edit]:hover{outline-color:#C8644A;background:rgba(200,100,74,.06);}",
      "body.ja-edit [data-edit].ja-on{outline:2px solid #C8644A;background:rgba(200,100,74,.10);}",
      "body.ja-edit [data-edit].ja-over{outline-color:rgba(141,121,166,.55);}",
      "body.ja-edit [data-edit].ja-drift{outline-color:#C0A15A;background:rgba(192,161,90,.12);}",
      ".ja-panel{position:fixed;left:0;right:0;bottom:0;z-index:99999;background:#fff;",
      "  border-top:2px solid #C8644A;box-shadow:0 -10px 30px -18px rgba(46,61,47,.5);",
      "  padding:14px 18px;font:14px/1.5 Inter,system-ui,sans-serif;}",
      ".ja-panel b{display:block;font-size:11px;letter-spacing:.7px;text-transform:uppercase;",
      "  color:#8B9382;margin-bottom:6px;}",
      ".ja-panel .ja-warn{background:rgba(200,100,74,.12);color:#B0543C;border-radius:7px;",
      "  padding:8px 11px;margin-bottom:9px;font-size:12.5px;}",
      ".ja-panel textarea{width:100%;min-height:74px;padding:10px 12px;border:1px solid rgba(46,61,47,.18);",
      "  border-radius:8px;font:inherit;resize:vertical;}",
      ".ja-row{display:flex;gap:9px;align-items:center;margin-top:9px;flex-wrap:wrap;}",
      ".ja-row button{font:inherit;font-size:12px;font-weight:600;letter-spacing:.8px;",
      "  text-transform:uppercase;padding:8px 15px;border-radius:8px;border:1.5px solid #C8644A;",
      "  background:#fff;color:#C8644A;cursor:pointer;}",
      ".ja-row button.pri{background:#2E3D2F;border-color:#2E3D2F;color:#fff;}",
      ".ja-row .ja-hint{font-size:12px;color:#8B9382;margin-left:auto;}",
      ".ja-toast{position:fixed;left:14px;bottom:70px;z-index:99999;background:#2E3D2F;color:#F7F3EB;",
      "  padding:10px 14px;border-radius:9px;font:13px Inter,system-ui,sans-serif;max-width:min(460px,86vw);}",
      ".ja-toast.bad{background:#B0543C;}",
    ].join("\n");
    document.head.appendChild(el);
  }

  /* The panel is a full-width band across the bottom, so anything else anchored
     there disappears behind it. Lift the bar and any toast clear of it. */
  function placeChrome() {
    var p = document.querySelector(".ja-panel");
    var lift = p ? p.offsetHeight + 12 : 0;
    var b = document.querySelector(".ja-bar");
    if (b) b.style.bottom = (14 + lift) + "px";
    var t = document.querySelector(".ja-toast");
    if (t) t.style.bottom = (70 + lift) + "px";
  }

  function toast(msg, bad) {
    var t = document.querySelector(".ja-toast");
    if (t) t.remove();
    t = document.createElement("div");
    t.className = "ja-toast" + (bad ? " bad" : "");
    t.textContent = msg;
    document.body.appendChild(t);
    placeChrome();
    setTimeout(function () { t.remove(); }, bad ? 9000 : 3500);
  }

  function bar() {
    var b = document.querySelector(".ja-bar");
    if (!b) {
      b = document.createElement("div");
      b.className = "ja-bar";
      document.body.appendChild(b);
    }
    var n = pending.size;
    b.innerHTML =
      '<button id="ja-toggle" class="' + (on ? "on" : "") + '">' + (on ? "Editing" : "Edit text") + "</button>" +
      (on ? '<button class="save" id="ja-save"' + (n ? "" : " disabled") + ">" +
            (n ? "Save " + n : "Saved") + "</button>" : "") +
      '<span class="ja-sep"></span>' +
      '<a href="admin.html">Admin</a>' +
      '<button id="ja-out">Sign out</button>';
    b.querySelector("#ja-toggle").onclick = toggle;
    if (b.querySelector("#ja-save")) b.querySelector("#ja-save").onclick = save;
    b.querySelector("#ja-out").onclick = function () {
      if (pending.size && !confirm(pending.size + " unsaved change(s). Sign out anyway?")) return;
      localStorage.removeItem(KEY);
      location.reload();
    };
  }

  async function toggle() {
    if (on) {
      if (pending.size && !confirm(pending.size + " unsaved change(s). Turn editing off anyway?")) return;
      pending.clear();
      document.body.classList.remove("ja-edit");
      var p = document.querySelector(".ja-panel"); if (p) p.remove();
      on = false; bar(); placeChrome();
      return;
    }
    on = true; document.body.classList.add("ja-edit"); bar();
    await load();
  }

  async function load() {
    var page = location.pathname.split("/").pop() || "index.html";
    /* The authored words come from the RAW file. This page has overrides applied
       already, so it cannot tell you what the HTML underneath actually says. */
    try {
      var html = await (await fetch(page + "?cb=" + Date.now())).text();
      var doc = new DOMParser().parseFromString(html, "text/html");
      doc.querySelectorAll("[data-edit]").forEach(function (el) {
        authored.set(el.dataset.edit, (el.textContent || "").replace(/\s+/g, " ").trim());
      });
    } catch (e) { /* falls back to no drift detection, which is survivable */ }

    try {
      (await api("site_text?select=key,value_md,default_text")).forEach(function (r) { saved.set(r.key, r); });
    } catch (e) {
      toast("Could not load your overrides: " + e.message, true);
    }

    document.querySelectorAll("[data-edit]").forEach(function (el) {
      if (el.dataset.wired) return;
      el.dataset.wired = "1";
      el.dataset.orig = el.innerHTML;
      var r = saved.get(el.dataset.edit);
      if (r) el.classList.add("ja-over");
      if (r && r.default_text && r.default_text !== authored.get(el.dataset.edit)) el.classList.add("ja-drift");
      el.addEventListener("click", function (ev) {
        if (!on) return;
        ev.preventDefault(); ev.stopPropagation();
        panel(el);
      });
    });

    /* While editing, a link would navigate away mid-sentence. */
    document.addEventListener("click", function (ev) {
      if (!on) return;
      var a = ev.target.closest("a");
      if (a && !a.closest(".ja-bar") && !a.closest(".ja-panel")) { ev.preventDefault(); }
    }, true);
  }

  function panel(el) {
    var key = el.dataset.edit;
    document.querySelectorAll(".ja-on").forEach(function (n) { n.classList.remove("ja-on"); });
    el.classList.add("ja-on");
    var old = document.querySelector(".ja-panel"); if (old) old.remove();

    var row = saved.get(key);
    var start = pending.has(key) ? pending.get(key) : (row ? row.value_md : "");
    var auth0 = authored.get(key) || "";
    var drift = row && row.default_text && row.default_text !== auth0;

    var p = document.createElement("div");
    p.className = "ja-panel";
    p.innerHTML =
      "<b>" + key + "</b>" +
      (drift ? '<div class="ja-warn"><strong>The HTML changed after this override was saved.</strong> ' +
        "It now reads: &ldquo;" + escapeHtml(auth0).slice(0, 240) + "&rdquo;, so this override may be " +
        "hiding something new. Use the original to drop it.</div>" : "") +
      '<textarea placeholder="' + escapeHtml(auth0).slice(0, 220) + '"></textarea>' +
      '<div class="ja-row">' +
        '<button class="pri" data-a="done">Done</button>' +
        '<button data-a="revert">Use the original</button>' +
        '<button data-a="close">Cancel</button>' +
        '<span class="ja-hint">**bold** &nbsp; *italic* &nbsp; [text](url) &nbsp; empty = original</span>' +
      "</div>";
    document.body.appendChild(p);

    var ta = p.querySelector("textarea");
    ta.value = start;
    ta.focus();
    function paint() {
      var v = ta.value.trim();
      el.innerHTML = v ? md(v) : el.dataset.orig;
    }
    ta.addEventListener("input", paint);
    paint();
    placeChrome();
    /* A taller textarea must not re-cover the bar. */
    if (window.ResizeObserver) new ResizeObserver(placeChrome).observe(p);

    p.querySelectorAll("button").forEach(function (b) {
      b.onclick = function () {
        var act = b.dataset.a;
        if (act === "close") {
          el.innerHTML = pending.has(key)
            ? (pending.get(key) ? md(pending.get(key)) : el.dataset.orig)
            : (row ? md(row.value_md) : el.dataset.orig);
        } else if (act === "revert") {
          if (row) pending.set(key, ""); else pending.delete(key);
          el.innerHTML = el.dataset.orig;
          el.classList.remove("ja-over");
        } else {
          var v = ta.value.trim();
          var current = row ? row.value_md : "";
          if (v === current) pending.delete(key); else pending.set(key, v);
          el.classList.toggle("ja-over", !!v);
        }
        el.classList.remove("ja-on");
        p.remove();
        bar();
        placeChrome();
      };
    });
  }

  async function save() {
    if (!pending.size) return;
    var page = location.pathname.split("/").pop() || "index.html";
    var items = Array.from(pending.entries());
    var okCount = 0, failed = null;
    for (var i = 0; i < items.length; i++) {
      var key = items[i][0], value = items[i][1];
      try {
        if (!value) {
          await api("site_text?key=eq." + encodeURIComponent(key), { method: "DELETE" });
          saved.delete(key);
        } else {
          await api("site_text", {
            method: "POST",
            headers: { Prefer: "resolution=merge-duplicates,return=representation" },
            body: JSON.stringify({
              key: key, page: page, value_md: value, value_html: md(value),
              default_text: authored.get(key) || "", updated_at: new Date().toISOString(),
            }),
          });
          saved.set(key, { key: key, value_md: value, default_text: authored.get(key) || "" });
        }
        pending.delete(key);
        okCount++;
      } catch (e) { failed = e; break; }   /* keep what is left in pending */
    }
    bar();
    if (failed) toast("Saved " + okCount + ", then failed: " + failed.message, true);
    else toast("Saved " + okCount + ". The live site is updated.");
  }

  /* Boot */
  if (!token()) return;
  css();
  bar();
  window.addEventListener("beforeunload", function (e) {
    if (pending.size) { e.preventDefault(); e.returnValue = ""; }
  });
})();
