/* A row of post cards that scrolls sideways. Native scrolling does the work, so
   touch, trackpad and keyboard need no script; the arrows are only a convenience
   for a mouse, and only earn their place when the row actually overflows. */
document.querySelectorAll(".carousel-track").forEach((track) => {
  const nav = document.createElement("div");
  nav.className = "carousel-nav";
  nav.innerHTML =
    '<button type="button" class="carousel-btn" data-dir="-1" aria-label="Previous posts">&larr;</button>' +
    '<button type="button" class="carousel-btn" data-dir="1" aria-label="More posts">&rarr;</button>';
  track.after(nav);
  const btns = nav.querySelectorAll(".carousel-btn");
  const sync = () => {
    const max = track.scrollWidth - track.clientWidth;
    nav.hidden = max <= 2;
    btns[0].disabled = track.scrollLeft <= 2;
    btns[1].disabled = track.scrollLeft >= max - 2;
  };
  btns.forEach((b) => b.addEventListener("click", () => {
    const card = track.firstElementChild;
    const step = card ? card.getBoundingClientRect().width + 20 : track.clientWidth;
    track.scrollBy({ left: step * Number(b.dataset.dir), behavior: "smooth" });
  }));
  track.addEventListener("scroll", sync, { passive: true });
  window.addEventListener("resize", sync);
  sync();
});
