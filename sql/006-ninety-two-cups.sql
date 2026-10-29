-- justamanda.net: the "Ninety-Two Cups" journal post, as a DRAFT.
-- Plain ASCII, no dollar-quoting, safe to run more than once.
-- Generated from content/ninety-two-cups.md through markdown.js, so body_html
-- is exactly what admin.html would store. Once this has run, edit the post in
-- admin, not here: re-running will NOT overwrite it (on conflict do nothing).

-- STEP 1 ------------------------------------------------------------------
-- Alt text for the cover photo. post.html showed covers with alt="", which
-- is wrong for a cover that carries the story (this one is the finished wall).
alter table public.posts add column if not exists cover_alt text;

-- STEP 2 ------------------------------------------------------------------
-- published_at stays null: admin sets it on Publish, and the public read
-- policy hides any post without one.
insert into public.posts (slug, title, excerpt, cover_url, cover_alt, status, body_md, body_html)
values (
  'ninety-two-cups',
  'Ninety-Two Cups',
  'How a wall of $0.99 IKEA cups followed me from my parents'' fence to my carport, and finally got some color.',
  'images/cups-v3-hero-2026.jpg',
  'A gray house wall covered in three rows of hanging cups painted in teal, mint, cinnamon, and copper, each holding a succulent or cactus, with potted plants on a wooden bench below.',
  'draft',
'## It started in Vallejo

At the start of the pandemic, I brought every plant home from my office. That was a lot of plants and nowhere near enough space. Around the same time, I saw a wall at a woman''s house in Vallejo: hog fencing mounted to the house, covered in green hanging planters, every one packed with succulents and trailing plants spilling over the bottom row. I couldn''t stop thinking about it.

<figure>
<img src="images/cups-inspiration-vallejo.jpg" width="1400" height="1050" loading="lazy" alt="A stucco wall covered by a wire grid holding dozens of green hanging planters overflowing with colorful succulents, with trailing plants cascading from the bottom row.">
<figcaption>The wall in Vallejo that started it all.</figcaption>
</figure>

So I built my own on the back fence at my parents'' house. I kept her idea of hog fencing: galvanized utility panels from Tractor Supply with 4-inch squares. For the cups, I went with IKEA SUNNERSTA containers. They''re sold as kitchen rail accessories for $0.99 each, and they have a hook molded into the back that drops right onto the grid. I painted the panels pink, left the cups white, and drilled a drainage hole in every one.

<figure class="is-tall">
<img src="images/cups-v1-planting-day.jpg" width="1050" height="1400" loading="lazy" alt="A pink wire grid mounted on a wooden fence, holding rows of white plastic cups freshly planted with small cacti and succulents.">
<figcaption>Planting day at my parents'' house, 2020.</figcaption>
</figure>

By the summer of 2021, it was full.

<figure>
<img src="images/cups-v1-full-2021.jpg" width="1400" height="1050" loading="lazy" alt="A long wooden fence covered by a pink wire grid and dozens of white cups overflowing with succulents.">
<figcaption>July 2021. Pink grid, white cups, every spot taken.</figcaption>
</figure>

## What happened to version one

Here''s the honest part. My mom liked the wall so much she decided she wanted one of her own. Mine got left behind. By the time I could bring it home, most of the original plants were gone.

## Moving it home

In August 2025, the wall came with me to our house. It went on the gray siding under the carport, a wall that had held nothing at all. The few pots in this photo were the first things to arrive.

<figure>
<img src="images/cups-before-bare-wall.jpg" width="1400" height="1050" loading="lazy" alt="A bare gray-sided wall under a carport with a few pots just set down and a German Shepherd standing on the concrete.">
<figcaption>Day one at the new house. Koda, our German Shepherd, supervising.</figcaption>
</figure>

I repainted the grid black with Rust-Oleum 2X spray paint. Pink worked at my parents'' house, but here I wanted something cleaner and more modern. The cups stayed white. A lot of them were empty, so I replanted from the potted collection I already had.

Along the base, we built shelving for the bigger pots out of standard cinderblocks and pressure-treated 12-foot 2x12 planks, left raw. One plank had to be cut down, and the leftover piece became the small raised shelf in the middle, which turned into the focal point of the whole setup.

<figure class="is-tall">
<img src="images/cups-v2-moved-home-2025.jpg" width="1050" height="1400" loading="lazy" alt="A black wire grid on a gray house wall holding rows of white cups, many still empty, with large teal and white planters lined up below.">
<figcaption>August 2025. Black grid, white cups, a lot of empty spots.</figcaption>
</figure>

A year later, it was full again.

<figure>
<img src="images/cups-v2-full-white-2026.jpg" width="1400" height="1050" loading="lazy" alt="The full wall of white cups on the gray house, with a row of potted plants and a wooden bench along the base.">
<figcaption>September 2026. Full, and very white.</figcaption>
</figure>

## Why the cups changed color

Once the wall was full, the white started to bother me. Against the gray house, it looked stark. Some cups were stained or sun-worn, and after six years outdoors, a couple had cracked and broken. Painting them was partly about looks and partly about protecting the plastic.

I worked out the palette with ChatGPT, looking for colors that would suit the house as it is now and the deep charcoal we plan to paint it someday. We landed on seven: five teals running from pale mint to nearly black, plus cinnamon and copper as warm accents. Then we mapped all 92 cups by number so the warm colors would never line up into an obvious stripe. I followed the map loosely.

<figure>
<a href="images/cups-color-map.png"><img src="images/cups-color-map.png" width="2400" height="1600" loading="lazy" alt="A numbered color map of 92 cups in three rows, showing seven paint colors from pale mint to deep teal plus cinnamon and copper, with totals per row."></a>
<figcaption>The plan. Reality took some liberties.</figcaption>
</figure>

The primer was the step that mattered most. Polypropylene is slick, and regular spray paint peels right off it. So every cup got a coat of Rust-Oleum Automotive Adhesion Promoter first. It''s a clear spray made for priming plastic car parts like bumpers and trim, and it gives the paint something to grip. Then two coats of Rust-Oleum color on top.

<figure class="is-tall">
<img src="images/cups-painted-cups-detail.jpg" width="1050" height="1400" loading="lazy" alt="Close-up of painted cups in mint, terra cotta, and teal hanging on a black wire grid, holding a haworthia, a small round cactus, and a snake plant.">
<figcaption>Mint, cinnamon, and three shades of teal.</figcaption>
</figure>

[[STANDOUT PLANTS: optional short paragraph naming 2 or 3 plants on the wall. Amanda to provide.]]

## Ninety-two cups

The wall now holds 92 cups in three rows: 31 on top, 30 in the middle, 31 on the bottom. Every cup has a number, and every number is a location in [the app I built to track the collection](plants.html#app), so each plant on the wall has an exact address. The app now has a wall map too, with every cup shown in the color it actually is. Not the plan. The real thing.

<figure>
<a href="images/cups-app-wall-map.png"><img src="images/cups-app-wall-map.png" width="1281" height="311" loading="lazy" alt="A screenshot of a plant inventory app showing a wall map of 92 numbered cups in three rows, each colored to match its real paint, with a legend of seven colors and counts."></a>
<figcaption>The wall as it lives in the app: 92 numbered cups, colored to match.</figcaption>
</figure>

<figure class="is-tall">
<img src="images/cups-v3-trailing-detail.jpg" width="1050" height="1400" loading="lazy" alt="A string-of-bananas succulent trailing over the edge of a deep teal cup beside a copper cup holding a green rosette.">
<figcaption>Six years, two houses, three paint jobs.</figcaption>
</figure>

Six years, two houses, three paint jobs. Ninety-two cups at $0.99 each.

<aside class="materials">
<h3>What I used</h3>
<ul>
<li><strong>Cups:</strong> <a href="https://www.ikea.com/us/en/p/sunnersta-container-white-50303735/">IKEA SUNNERSTA container, white</a>. 4 3/4" x 4 3/8" x 5 1/8", polypropylene, $0.99 each. Drill a drainage hole in each.</li>
<li><strong>Grid:</strong> <a href="https://www.tractorsupply.com/tsc/product/ok-brand-4-ft-x-16-ft-utility-panel-6-ga-1022019">OKBRAND 4 ft x 16 ft, 6-gauge galvanized utility panel</a>, Tractor Supply. 4" x 4" grid openings.</li>
<li><strong>Grid paint:</strong> Rust-Oleum 2X Ultra Cover spray paint, black.</li>
<li><strong>Shelving:</strong> standard cinderblocks and pressure-treated 12 ft 2x12 planks, unfinished. One offcut forms the raised middle shelf.</li>
<li><strong>Cup primer:</strong> <a href="https://www.amazon.com/dp/B006ZLQ71Y">Rust-Oleum Automotive Adhesion Promoter, clear (251572)</a>. Made for plastic automotive parts. Don''t skip this on polypropylene.</li>
<li><strong>Cup paint:</strong> Rust-Oleum 2X Ultra Cover spray paint, two coats:
<ul>
<li>Ocean Mist, Gloss (15 cups)</li>
<li>Seaside, Gloss (15 cups)</li>
<li>Vintage Teal, Satin (13 cups)</li>
<li>Lagoon, Satin (16 cups)</li>
<li>Deep Teal, Ultra Matte (16 cups)</li>
<li>Cinnamon, Satin (9 cups)</li>
<li>Copper, Hammered (8 cups)</li>
</ul></li>
</ul>
<p class="note">Counts are the final wall as painted, 92 cups total.</p>
</aside>
',
'<h2>It started in Vallejo</h2>
<p>At the start of the pandemic, I brought every plant home from my office. That was a lot of plants and nowhere near enough space. Around the same time, I saw a wall at a woman&#39;s house in Vallejo: hog fencing mounted to the house, covered in green hanging planters, every one packed with succulents and trailing plants spilling over the bottom row. I couldn&#39;t stop thinking about it.</p>
<figure>
<img src="images/cups-inspiration-vallejo.jpg" width="1400" height="1050" loading="lazy" alt="A stucco wall covered by a wire grid holding dozens of green hanging planters overflowing with colorful succulents, with trailing plants cascading from the bottom row.">
<figcaption>The wall in Vallejo that started it all.</figcaption>
</figure>
<p>So I built my own on the back fence at my parents&#39; house. I kept her idea of hog fencing: galvanized utility panels from Tractor Supply with 4-inch squares. For the cups, I went with IKEA SUNNERSTA containers. They&#39;re sold as kitchen rail accessories for $0.99 each, and they have a hook molded into the back that drops right onto the grid. I painted the panels pink, left the cups white, and drilled a drainage hole in every one.</p>
<figure class="is-tall">
<img src="images/cups-v1-planting-day.jpg" width="1050" height="1400" loading="lazy" alt="A pink wire grid mounted on a wooden fence, holding rows of white plastic cups freshly planted with small cacti and succulents.">
<figcaption>Planting day at my parents'' house, 2020.</figcaption>
</figure>
<p>By the summer of 2021, it was full.</p>
<figure>
<img src="images/cups-v1-full-2021.jpg" width="1400" height="1050" loading="lazy" alt="A long wooden fence covered by a pink wire grid and dozens of white cups overflowing with succulents.">
<figcaption>July 2021. Pink grid, white cups, every spot taken.</figcaption>
</figure>
<h2>What happened to version one</h2>
<p>Here&#39;s the honest part. My mom liked the wall so much she decided she wanted one of her own. Mine got left behind. By the time I could bring it home, most of the original plants were gone.</p>
<h2>Moving it home</h2>
<p>In August 2025, the wall came with me to our house. It went on the gray siding under the carport, a wall that had held nothing at all. The few pots in this photo were the first things to arrive.</p>
<figure>
<img src="images/cups-before-bare-wall.jpg" width="1400" height="1050" loading="lazy" alt="A bare gray-sided wall under a carport with a few pots just set down and a German Shepherd standing on the concrete.">
<figcaption>Day one at the new house. Koda, our German Shepherd, supervising.</figcaption>
</figure>
<p>I repainted the grid black with Rust-Oleum 2X spray paint. Pink worked at my parents&#39; house, but here I wanted something cleaner and more modern. The cups stayed white. A lot of them were empty, so I replanted from the potted collection I already had.</p>
<p>Along the base, we built shelving for the bigger pots out of standard cinderblocks and pressure-treated 12-foot 2x12 planks, left raw. One plank had to be cut down, and the leftover piece became the small raised shelf in the middle, which turned into the focal point of the whole setup.</p>
<figure class="is-tall">
<img src="images/cups-v2-moved-home-2025.jpg" width="1050" height="1400" loading="lazy" alt="A black wire grid on a gray house wall holding rows of white cups, many still empty, with large teal and white planters lined up below.">
<figcaption>August 2025. Black grid, white cups, a lot of empty spots.</figcaption>
</figure>
<p>A year later, it was full again.</p>
<figure>
<img src="images/cups-v2-full-white-2026.jpg" width="1400" height="1050" loading="lazy" alt="The full wall of white cups on the gray house, with a row of potted plants and a wooden bench along the base.">
<figcaption>September 2026. Full, and very white.</figcaption>
</figure>
<h2>Why the cups changed color</h2>
<p>Once the wall was full, the white started to bother me. Against the gray house, it looked stark. Some cups were stained or sun-worn, and after six years outdoors, a couple had cracked and broken. Painting them was partly about looks and partly about protecting the plastic.</p>
<p>I worked out the palette with ChatGPT, looking for colors that would suit the house as it is now and the deep charcoal we plan to paint it someday. We landed on seven: five teals running from pale mint to nearly black, plus cinnamon and copper as warm accents. Then we mapped all 92 cups by number so the warm colors would never line up into an obvious stripe. I followed the map loosely.</p>
<figure>
<a href="images/cups-color-map.png"><img src="images/cups-color-map.png" width="2400" height="1600" loading="lazy" alt="A numbered color map of 92 cups in three rows, showing seven paint colors from pale mint to deep teal plus cinnamon and copper, with totals per row."></a>
<figcaption>The plan. Reality took some liberties.</figcaption>
</figure>
<p>The primer was the step that mattered most. Polypropylene is slick, and regular spray paint peels right off it. So every cup got a coat of Rust-Oleum Automotive Adhesion Promoter first. It&#39;s a clear spray made for priming plastic car parts like bumpers and trim, and it gives the paint something to grip. Then two coats of Rust-Oleum color on top.</p>
<figure class="is-tall">
<img src="images/cups-painted-cups-detail.jpg" width="1050" height="1400" loading="lazy" alt="Close-up of painted cups in mint, terra cotta, and teal hanging on a black wire grid, holding a haworthia, a small round cactus, and a snake plant.">
<figcaption>Mint, cinnamon, and three shades of teal.</figcaption>
</figure>
<p>[[STANDOUT PLANTS: optional short paragraph naming 2 or 3 plants on the wall. Amanda to provide.]]</p>
<h2>Ninety-two cups</h2>
<p>The wall now holds 92 cups in three rows: 31 on top, 30 in the middle, 31 on the bottom. Every cup has a number, and every number is a location in <a href="plants.html#app">the app I built to track the collection</a>, so each plant on the wall has an exact address. The app now has a wall map too, with every cup shown in the color it actually is. Not the plan. The real thing.</p>
<figure>
<a href="images/cups-app-wall-map.png"><img src="images/cups-app-wall-map.png" width="1281" height="311" loading="lazy" alt="A screenshot of a plant inventory app showing a wall map of 92 numbered cups in three rows, each colored to match its real paint, with a legend of seven colors and counts."></a>
<figcaption>The wall as it lives in the app: 92 numbered cups, colored to match.</figcaption>
</figure>
<figure class="is-tall">
<img src="images/cups-v3-trailing-detail.jpg" width="1050" height="1400" loading="lazy" alt="A string-of-bananas succulent trailing over the edge of a deep teal cup beside a copper cup holding a green rosette.">
<figcaption>Six years, two houses, three paint jobs.</figcaption>
</figure>
<p>Six years, two houses, three paint jobs. Ninety-two cups at $0.99 each.</p>
<aside class="materials">
<h3>What I used</h3>
<ul>
<li><strong>Cups:</strong> <a href="https://www.ikea.com/us/en/p/sunnersta-container-white-50303735/">IKEA SUNNERSTA container, white</a>. 4 3/4" x 4 3/8" x 5 1/8", polypropylene, $0.99 each. Drill a drainage hole in each.</li>
<li><strong>Grid:</strong> <a href="https://www.tractorsupply.com/tsc/product/ok-brand-4-ft-x-16-ft-utility-panel-6-ga-1022019">OKBRAND 4 ft x 16 ft, 6-gauge galvanized utility panel</a>, Tractor Supply. 4" x 4" grid openings.</li>
<li><strong>Grid paint:</strong> Rust-Oleum 2X Ultra Cover spray paint, black.</li>
<li><strong>Shelving:</strong> standard cinderblocks and pressure-treated 12 ft 2x12 planks, unfinished. One offcut forms the raised middle shelf.</li>
<li><strong>Cup primer:</strong> <a href="https://www.amazon.com/dp/B006ZLQ71Y">Rust-Oleum Automotive Adhesion Promoter, clear (251572)</a>. Made for plastic automotive parts. Don''t skip this on polypropylene.</li>
<li><strong>Cup paint:</strong> Rust-Oleum 2X Ultra Cover spray paint, two coats:
<ul>
<li>Ocean Mist, Gloss (15 cups)</li>
<li>Seaside, Gloss (15 cups)</li>
<li>Vintage Teal, Satin (13 cups)</li>
<li>Lagoon, Satin (16 cups)</li>
<li>Deep Teal, Ultra Matte (16 cups)</li>
<li>Cinnamon, Satin (9 cups)</li>
<li>Copper, Hammered (8 cups)</li>
</ul></li>
</ul>
<p class="note">Counts are the final wall as painted, 92 cups total.</p>
</aside>'
)
on conflict (slug) do nothing;
