/* The phone's own share sheet where there is one; otherwise copy the link and
   say so on the button, rather than an alert that blocks the page. */
const share = document.getElementById("lt-share");
if (share) share.addEventListener("click", async (e) => {
  const btn = e.currentTarget;
  const url = location.origin + location.pathname;
  if (navigator.share) {
    try { await navigator.share({ title: document.title, url }); } catch (err) { /* dismissed */ }
    return;
  }
  try {
    await navigator.clipboard.writeText(url);
    btn.classList.add("is-copied");
    btn.setAttribute("aria-label", "Link copied");
    setTimeout(() => { btn.classList.remove("is-copied"); btn.setAttribute("aria-label", "Share this page"); }, 1800);
  } catch (err) { /* clipboard blocked: nothing useful left to do */ }
});
