/* A deliberately small Markdown subset — enough for a journal and no more, so
   the site keeps its no-dependency character.

   This file loads in admin.html ONLY. Posts are converted once when saved and
   the rendered HTML is stored in posts.body_html, so readers never download a
   parser. That is the whole reason the public pages stay this light.

   Supported: # headings, **bold**, *italic*, `code`, [links](url),
   ![images](url), > quotes, - and 1. lists, --- rules, ``` fenced code, and
   raw HTML blocks (any block whose first line starts with "<" passes through
   untouched). Everything else is escaped. */
function mdToHtml(src) {
  const L = String(src || "").replace(/\r\n?/g, "\n").split("\n");
  const out = [];
  const blank = (s) => !s.trim();
  const LIST_UL = /^\s*[-*+]\s+/;
  const LIST_OL = /^\s*\d+\.\s+/;
  const RULE = /^\s*(-{3,}|\*{3,}|_{3,})\s*$/;
  let i = 0;

  while (i < L.length) {
    const line = L[i];
    if (blank(line)) { i++; continue; }

    if (/^```/.test(line)) {
      const buf = []; i++;
      while (i < L.length && !/^```/.test(L[i])) buf.push(L[i++]);
      i++;
      out.push(`<pre><code>${escapeHtml(buf.join("\n"))}</code></pre>`);
      continue;
    }

    if (/^</.test(line)) {                       // raw HTML, until a blank line
      const buf = [];
      while (i < L.length && !blank(L[i])) buf.push(L[i++]);
      out.push(buf.join("\n"));
      continue;
    }

    if (RULE.test(line)) { out.push("<hr>"); i++; continue; }

    const h = line.match(/^(#{1,6})\s+(.*)$/);
    if (h) { out.push(`<h${h[1].length}>${inlineMd(h[2])}</h${h[1].length}>`); i++; continue; }

    if (/^>\s?/.test(line)) {
      const buf = [];
      while (i < L.length && /^>\s?/.test(L[i])) buf.push(L[i++].replace(/^>\s?/, ""));
      out.push(`<blockquote>${mdToHtml(buf.join("\n"))}</blockquote>`);
      continue;
    }

    if (LIST_UL.test(line) || LIST_OL.test(line)) {
      const ordered = LIST_OL.test(line);
      const re = ordered ? LIST_OL : LIST_UL;
      const items = [];
      while (i < L.length && re.test(L[i])) items.push(L[i++].replace(re, ""));
      const tag = ordered ? "ol" : "ul";
      out.push(`<${tag}>${items.map((t) => `<li>${inlineMd(t)}</li>`).join("")}</${tag}>`);
      continue;
    }

    const buf = [];
    while (i < L.length && !blank(L[i]) && !/^(```|>|<)/.test(L[i]) &&
           !/^#{1,6}\s/.test(L[i]) && !LIST_UL.test(L[i]) && !LIST_OL.test(L[i]) && !RULE.test(L[i])) {
      buf.push(L[i++]);
    }
    if (buf.length) out.push(`<p>${inlineMd(buf.join(" "))}</p>`);
    else i++;                                    // belt and braces: never spin
  }
  return out.join("\n");
}

function inlineMd(s) {
  let t = escapeHtml(s);
  t = t.replace(/!\[([^\]]*)\]\(([^)\s]+)\)/g, '<img src="$2" alt="$1" loading="lazy">');
  t = t.replace(/\[([^\]]+)\]\(([^)\s]+)\)/g, '<a href="$2">$1</a>');
  t = t.replace(/`([^`]+)`/g, "<code>$1</code>");
  t = t.replace(/\*\*([^*]+)\*\*/g, "<strong>$1</strong>");
  t = t.replace(/(^|[^*])\*([^*]+)\*/g, "$1<em>$2</em>");
  return t;
}

function slugify(s) {
  return String(s || "").toLowerCase().trim()
    .replace(/['’]/g, "").replace(/[^a-z0-9]+/g, "-")
    .replace(/^-+|-+$/g, "").slice(0, 70);
}
