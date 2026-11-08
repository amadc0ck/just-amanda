/* Post share row (blocks/share). The device's own share sheet where there is one,
   and copy-the-link everywhere the clipboard works. Both start hidden in the HTML,
   so a browser without them never shows a button that does nothing. Feedback goes
   on the button itself, never an alert that blocks the page. */
document.querySelectorAll(".ja-share").forEach((row) => {
  const url = row.dataset.url || location.origin + location.pathname;
  const title = row.dataset.title || document.title;
  const native = row.querySelector(".ja-share-native");
  const copy = row.querySelector(".ja-share-copy");
  if (native && navigator.share) {
    native.hidden = false;
    native.addEventListener("click", async () => {
      try { await navigator.share({ title, url }); } catch (err) { /* dismissed */ }
    });
  }
  if (copy && navigator.clipboard && window.isSecureContext) {
    copy.hidden = false;
    copy.addEventListener("click", async () => {
      try {
        await navigator.clipboard.writeText(url);
        const was = copy.textContent;
        copy.textContent = "Link copied";
        copy.classList.add("is-copied");
        setTimeout(() => { copy.textContent = was; copy.classList.remove("is-copied"); }, 1800);
      } catch (err) { /* clipboard blocked: the service links still work */ }
    });
  }
});
