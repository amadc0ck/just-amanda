-- justamanda.net: "I Built a Botanical Garden's Records System for My Succulents", as a DRAFT.
-- Generated from ~/Downloads/abg-blog-post-app.md through markdown.js, so body_html
-- is exactly what admin.html would store. Already run by Claude via the CLI on
-- 2026-10-30; kept as the record. Edit the post in admin from here on: re-running
-- will NOT overwrite it (on conflict do nothing).
insert into public.posts (slug, title, excerpt, cover_url, cover_alt, tags, status, body_md, body_html)
values (
  'adcock-botanical-garden-app',
  'I Built a Botanical Garden''s Records System for My Succulents',
  '263 plants, 178 locations, 4,035 photos, and every one of them accounted for. Here''s the app behind my collection, how it works, and what I learned building it.',
  'images/collection-wall.jpg',
  'A house wall hung with rows of white wall-mounted pots of succulents, with larger glazed planters and potted cacti along the ground beneath',
  array['adcock botanical garden', 'succulents', 'cacti', 'vibe coding', 'claude', 'supabase', 'plant inventory'],
  'draft',
'At the start of the pandemic, I brought my plants home from the office. That was the beginning of the end of my ability to keep track of them.

A few plants became a wall. The wall became a collection. Cuttings became plants, which made more cuttings. Tags faded. Names got lost. A dozen rosettes started to look exactly alike, and I could no longer answer three basic questions about my own plants: *What is it? Where is it? Where did it come from?*

So I did what any reasonable person would do. I built a botanical garden''s records system for my yard.

This is the **Adcock Botanical Garden**: a custom web app that catalogs every succulent and cactus I own. Today it tracks **170 kinds of plants, 263 individual specimens, 178 locations, and 4,035 photos.** It''s on version 2.72, and it''s been [[HOW LONG: the first commit was August 23, 2026, so about two months]] in the making. Here''s what it does, how I built it, and what I learned along the way.

---

## Why a "botanical garden"?

Real botanical gardens solved this problem a long time ago. Every specimen gets an **accession number**: a permanent ID that never changes, no matter what happens to the plant. Names change when botanists reclassify things. Plants get repotted, moved, divided, and occasionally die. The accession number stays.

I borrowed that idea wholesale, and the rest of the app grew around it.

---

## The tour

### Kinds and specimens

<figure>
<a href="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-01-plants-grid.webp"><img src="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-01-plants-grid.webp" width="1311" height="924" loading="lazy" alt="Screenshot: The Plants screen: 170 kinds, 263 specimens"></a>
<figcaption>The Plants screen: 170 kinds, 263 specimens</figcaption>
</figure>

The app separates two ideas that a spreadsheet mashes together: the **kind** of plant and the **individual plant**.

*Aeonium arboreum* is a kind. I have five of them, in five different places, and each one is its own **specimen** with its own accession number in the format **ABG-YYYY-NNNN**. The database generates the number automatically, and once it''s set, nothing can change it.

Care information that''s true of the whole kind (water needs, hardiness, type) lives once on the species record. Everything about one particular plant lives on the specimen.

### Every specimen has a full record

<figure>
<a href="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-02-plant-detail.webp"><img src="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-02-plant-detail.webp" width="1311" height="924" loading="lazy" alt="Screenshot: A specimen''s detail page: accession number, check-in reminder, and actions"></a>
<figcaption>A specimen''s detail page: accession number, check-in reminder, and actions</figcaption>
</figure>

Each specimen''s page answers everything I''d want to know at a glance:

- **Identity:** accession number, botanical and common names, family, and identification status
- **Status and health:** active, dormant, given away, or gone; healthy, watch, or recovery
- **Where it has lived:** every move, with dates, recorded automatically
- **Care:** when I last watered it, when it''s due for a check-in, and whether it''s blooming
- **Provenance:** when I got it and where from

The actions are the things I actually do to plants: water it, move it, send it to the **Plant Hospital**, propagate from it, merge in a duplicate, or mark that I no longer have it. Plants that die or get given away don''t vanish. They go to **In memoriam**, the same way a real garden keeps its history.

My favorite detail: if I haven''t photographed a plant in a while, it tells me. That one is "24 days since a photo," so it''s due for a check-in.

<figure>
<a href="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-03-photo-timeline.webp"><img src="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-03-photo-timeline.webp" width="1311" height="924" loading="lazy" alt="Screenshot: A specimen''s photo timeline, grouped by every place it has lived"></a>
<figcaption>A specimen''s photo timeline, grouped by every place it has lived</figcaption>
</figure>

The photo timeline is grouped by location, so I can see how a plant looked in every spot it''s lived. This aeonium has been through the propagation area, a small pot, the ground, and a succulent bed, and the photos tell the whole story.

### The To Do screen: the garden tells me what it needs

<figure>
<a href="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-12-todo.webp"><img src="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-12-todo.webp" width="1311" height="924" loading="lazy" alt="Screenshot: The To Do screen: what needs attention, photos to file, and records to finish"></a>
<figcaption>The To Do screen: what needs attention, photos to file, and records to finish</figcaption>
</figure>

This is the screen I open first. It''s everything waiting on me, sorted by urgency:

- **Needs you now:** plants needing attention, plants that need watering, plants due for a check-in, plants waiting to be planted, and blooms that might be over
- **Photos to file:** new photos that still need a plant, a location, or both
- **Records to finish:** plants missing photos or identification, locations I haven''t catalogued, and likely duplicates

Alongside that are my tasks (each linked to a specific plant), a **wish list** of plants I want, and today''s weather, because that decides whether I''m watering.

### Photos first

<figure>
<a href="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-04-inbox.webp"><img src="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-04-inbox.webp" width="1311" height="924" loading="lazy" alt="Screenshot: Photos waiting to be filed"></a>
<figcaption>Photos waiting to be filed</figcaption>
</figure>

The way I actually use this app is photo-first. I take pictures in the garden, upload them, and they land in a queue waiting to be filed.

<figure>
<a href="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-05-inbox-lightbox.webp"><img src="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-05-inbox-lightbox.webp" width="1311" height="924" loading="lazy" alt="Screenshot: One photo open, with every filing action along the bottom"></a>
<figcaption>One photo open, with every filing action along the bottom</figcaption>
</figure>

Then I file them one by one. Each photo opens full-size with everything I need along the bottom: identify it, ask Claude, assign it to a plant or a location, create a new plant from it, favorite it, edit it, or delete it. When I finish one, the next one is already waiting.

A few details I''m proud of:

- **Real photo dates.** The app reads the date the photo was actually taken, not the date I uploaded it. Upload a year of photos at once and every timeline still comes out right.
- **One photo, many plants.** A wide shot of a pot with three plants in it can be tagged to all three.

### "What is this?" Two AI opinions

<figure>
<a href="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-06-plantnet-candidates.webp"><img src="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-06-plantnet-candidates.webp" width="1311" height="924" loading="lazy" alt="Screenshot: Comparing Pl@ntNet candidates against my photo"></a>
<figcaption>Comparing Pl@ntNet candidates against my photo</figcaption>
</figure>

For anything I can''t name, the app gets a second opinion. Actually two.

**Pl@ntNet** returns up to five candidate species with a match score. My photo sits at the top, and each candidate below it has reference photos plus one-tap searches on Google Images, iNaturalist, and Wikipedia. If the top guess is wrong, I pick another with "Use this instead."

<figure>
<a href="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-07-claude-identification.webp"><img src="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-07-claude-identification.webp" width="1311" height="924" loading="lazy" alt="Screenshot: Claude''s identification, with its confidence and reasoning"></a>
<figcaption>Claude''s identification, with its confidence and reasoning</figcaption>
</figure>

**Claude** gives a different kind of answer: its best identification, a confidence level, and its reasoning. "Spherical, pea-like leaves trailing along thin stems" tells me what to look at to confirm it, which is more useful than a bare percentage.

The most important design rule here: **the AI never changes my data on its own.** Every suggestion stays a suggestion until I confirm it, and every attempt is kept on record.

### Every plant has an address

<figure>
<a href="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-08-wall-collection.webp"><img src="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-08-wall-collection.webp" width="1311" height="924" loading="lazy" alt="Screenshot: The Wall Collection with its wall map of 92 cups"></a>
<figcaption>The Wall Collection with its wall map of 92 cups</figcaption>
</figure>

Locations nest inside each other as deep as I need. The **Wall Collection** alone holds 99 plants in 92 cups across three rows, and the app draws it as a **wall map**: one tile per cup, in the same order they hang on the wall.

The map can color the cups by their actual paint color, by plant health, by when I last photographed them, by what''s in bloom, or by which cups are empty. When I water the wall, one tap records it for all 99 plants.

<figure>
<a href="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-09-location-detail.webp"><img src="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-09-location-detail.webp" width="1311" height="924" loading="lazy" alt="Screenshot: Bucket 34: one cup, its plant, and what used to live there"></a>
<figcaption>Bucket 34: one cup, its plant, and what used to live there</figcaption>
</figure>

Every cup is its own location with its own photo and history. Bucket 34 holds a *Cotyledon* ''Cub Paws'' today, and it remembers that a *Mammillaria* lived there before it moved to the Plant Hospital. "Show on the wall" jumps back to its spot on the map.

The location list also has a section called **From the Archives**: every place the collection has lived before this house, starting with my old office. Old photos of past collections live there, so nothing gets lost.

### The family tree

<figure>
<a href="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-10-lineage.webp"><img src="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-10-lineage.webp" width="1311" height="924" loading="lazy" alt="Screenshot: A kind with six specimens, three of them propagated from a parent"></a>
<figcaption>A kind with six specimens, three of them propagated from a parent</figcaption>
</figure>

Propagation is how a succulent collection really grows. When I take a cutting or pull a pup, the new plant gets its own accession number and a link back to its parent. Here, three new specimens sit nested under the plant they came from, each tagged "propagated." Three generations later, I can still trace a cutting back to the mother plant.

### The Gallery

<figure>
<a href="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-11-gallery.webp"><img src="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-11-gallery.webp" width="1311" height="924" loading="lazy" alt="Screenshot: The Gallery: recent photos, garden albums, and what''s in bloom"></a>
<figcaption>The Gallery: recent photos, garden albums, and what''s in bloom</figcaption>
</figure>

The Gallery is the fun part. It opens with the whole collection by the numbers, then recently added photos, **Garden Albums** for each part of the yard, and an **In Bloom** row for whatever''s flowering right now. Everything is searchable by notes, plant, or location.

### Backups, because I''ve been in IT too long

<figure>
<a href="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-13-settings.webp"><img src="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-13-settings.webp" width="1311" height="798" loading="lazy" alt="Screenshot: Settings: Drive connection, backup and restore, and photo date repair"></a>
<figcaption>Settings: Drive connection, backup and restore, and photo date repair</figcaption>
</figure>

One tap downloads a full backup of everything, and another exports my plant list as a spreadsheet. The backup restores straight back into the app, and the restore only adds and updates, so it can never wipe anything out. There''s even an error history, so when something fails, I can see what, when, and why. I''ve spent too many years in IT to trust any system that can''t do this.

### The public side

<figure>
<a href="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-14-justamanda-plants.webp"><img src="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-14-justamanda-plants.webp" width="1311" height="924" loading="lazy" alt="Screenshot: The Succulents page on justamanda.net"></a>
<figcaption>The Succulents page on justamanda.net</figcaption>
</figure>

The app is private. It''s my back office. The public face of the collection lives right here on the [Succulents page](plants.html), along with build stories like the Succulent Wall.

---

## How I built it

I''m not a professional developer. I lead technical support teams, and I think in systems, flows, and edge cases. That turned out to be the perfect background for building this with AI.

My process looks like this:

1. **Scope with Claude.** I describe what I want in plain language: the behavior, the outcome, the problem. Claude acts as a thinking partner. It asks questions, pokes holes, and helps me make the design decisions before any code exists.
2. **Build with Claude Code.** Once a feature is scoped, I hand it to Claude Code, which finds the right place in the code, implements it, and explains what it did.
3. **Test, adjust, ship.** I try it on real plants with real photos. Then I bundle related changes into one release.

I work requirements-first. I describe what I see and what I want, not which lines to change. But I''m not a passive passenger. I push back, suggest approaches, and make the architecture calls. The AI handles the typing; I own the system.

### Boring on purpose

I made one big decision early and have stuck to it: **keep it simple.**

The entire app is a single HTML file. No framework, no build step, no external JavaScript libraries. When I needed to read photo dates from image files, I didn''t install a library. Claude Code wrote a small reader by hand.

That sounds extreme, but it''s paid off constantly. There''s nothing to compile, nothing to update, and nothing that breaks because some dependency changed. Over two major versions, I can still open one file and see the whole app.

---

## Under the hood (for the builders)

If you''re not into the technical side, skip to "What I learned." If you are, here''s how it fits together.

[[ARCHITECTURE DIAGRAM: 15-architecture.png is coming separately. Caption: Architecture: single-file app, Supabase, Google Drive, Pl@ntNet, and Claude]]

- **Frontend:** one vanilla HTML/CSS/JS file, hosted free on GitHub Pages
- **Backend:** Supabase for the Postgres database, authentication, and server-side functions
- **Photos:** Google Drive
- **Identification:** Pl@ntNet and Claude, both called from server-side functions so no API keys live in the browser

### Accession numbers the database enforces

Accession numbers are generated by a database trigger that increments a per-year counter, and a second trigger rejects any attempt to change a number once it''s set. One gotcha: the counter table is locked down by row-level security, which blocked the trigger itself the first time I added a plant. The fix was letting those two functions run with elevated privileges rather than opening up the counter table.

### The Google Drive saga

Storing 4,000 photos in Google Drive took more tries than anything else in the app.

1. **Service account:** dead end. Service accounts have zero storage quota on personal Google accounts, so they can''t upload anything, even to a folder you''ve shared with them.
2. **Sign in with Google into a folder I created:** 404. The limited Drive permission I was using can only see files and folders the app itself created, even ones I own.
3. **Let the app create its own folder:** worked, with a catch. An unpublished Google app''s sign-in expires every week, so for months I reconnected Drive every Monday-ish.
4. **A Google Workspace account, and publishing the app:** the weekly reconnect is finally gone. The Settings screen now says it plainly: this connection does not expire.

### The CORS error that wasn''t

At one point, Claude identification failed with a CORS error. Classic misdirection: the real problem was a crash converting large photos before sending them, which happened before the function could attach its CORS headers. The browser reported the symptom, not the cause. Converting the image in small chunks fixed it. Lesson filed: **a CORS error plus a 500 on a server function usually means a crash, not a CORS problem.**

### Fast paths plus flexibility

Plants have one primary location stored directly on the record, plus optional extra locations in a separate table. Same for photos and plants. Almost everything has exactly one, so the common case stays fast and simple, and the rare plant that sprawls across two pots still works.

---

## What I learned

- **Borrow from the experts.** Botanical gardens have been solving inventory for centuries. Accession numbers, provenance, and deaccessioning were all there for the taking.
- **Simple scales further than you''d think.** One file and a few services got me to 263 plants, 4,035 photos, and version 2.72 with very little that can break.
- **Build for the habit, not the database.** The biggest leap wasn''t storing more data. It was the To Do screen telling me what the garden needs, so the app fits how I actually garden.
- **AI is a collaborator, not an autopilot.** The best results came from treating Claude like a sharp partner: clear requirements in, real reasoning out, and me making the final calls. That goes for the identification feature too.

---

## What''s next

[[WHAT''S NEXT: confirm these two are still on the roadmap and not shipped yet.]]

- **A smarter photo upload**, where one AI pass suggests the identification, health notes, blooms, and location all at once
- **An in-app idea capture**, so new feature ideas go straight into the backlog from my phone

I''ll write about each one as it ships.

---

*Have a collection that''s outgrown your memory? I''d love to hear how you keep track of yours. [[CALL TO ACTION: where should readers reply? Instagram, Threads, email?]]*
',
'<p>At the start of the pandemic, I brought my plants home from the office. That was the beginning of the end of my ability to keep track of them.</p>
<p>A few plants became a wall. The wall became a collection. Cuttings became plants, which made more cuttings. Tags faded. Names got lost. A dozen rosettes started to look exactly alike, and I could no longer answer three basic questions about my own plants: <em>What is it? Where is it? Where did it come from?</em></p>
<p>So I did what any reasonable person would do. I built a botanical garden&#39;s records system for my yard.</p>
<p>This is the <strong>Adcock Botanical Garden</strong>: a custom web app that catalogs every succulent and cactus I own. Today it tracks <strong>170 kinds of plants, 263 individual specimens, 178 locations, and 4,035 photos.</strong> It&#39;s on version 2.72, and it&#39;s been [[HOW LONG: the first commit was August 23, 2026, so about two months]] in the making. Here&#39;s what it does, how I built it, and what I learned along the way.</p>
<hr>
<h2>Why a &quot;botanical garden&quot;?</h2>
<p>Real botanical gardens solved this problem a long time ago. Every specimen gets an <strong>accession number</strong>: a permanent ID that never changes, no matter what happens to the plant. Names change when botanists reclassify things. Plants get repotted, moved, divided, and occasionally die. The accession number stays.</p>
<p>I borrowed that idea wholesale, and the rest of the app grew around it.</p>
<hr>
<h2>The tour</h2>
<h3>Kinds and specimens</h3>
<figure>
<a href="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-01-plants-grid.webp"><img src="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-01-plants-grid.webp" width="1311" height="924" loading="lazy" alt="Screenshot: The Plants screen: 170 kinds, 263 specimens"></a>
<figcaption>The Plants screen: 170 kinds, 263 specimens</figcaption>
</figure>
<p>The app separates two ideas that a spreadsheet mashes together: the <strong>kind</strong> of plant and the <strong>individual plant</strong>.</p>
<p><em>Aeonium arboreum</em> is a kind. I have five of them, in five different places, and each one is its own <strong>specimen</strong> with its own accession number in the format <strong>ABG-YYYY-NNNN</strong>. The database generates the number automatically, and once it&#39;s set, nothing can change it.</p>
<p>Care information that&#39;s true of the whole kind (water needs, hardiness, type) lives once on the species record. Everything about one particular plant lives on the specimen.</p>
<h3>Every specimen has a full record</h3>
<figure>
<a href="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-02-plant-detail.webp"><img src="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-02-plant-detail.webp" width="1311" height="924" loading="lazy" alt="Screenshot: A specimen''s detail page: accession number, check-in reminder, and actions"></a>
<figcaption>A specimen''s detail page: accession number, check-in reminder, and actions</figcaption>
</figure>
<p>Each specimen&#39;s page answers everything I&#39;d want to know at a glance:</p>
<ul><li><strong>Identity:</strong> accession number, botanical and common names, family, and identification status</li><li><strong>Status and health:</strong> active, dormant, given away, or gone; healthy, watch, or recovery</li><li><strong>Where it has lived:</strong> every move, with dates, recorded automatically</li><li><strong>Care:</strong> when I last watered it, when it&#39;s due for a check-in, and whether it&#39;s blooming</li><li><strong>Provenance:</strong> when I got it and where from</li></ul>
<p>The actions are the things I actually do to plants: water it, move it, send it to the <strong>Plant Hospital</strong>, propagate from it, merge in a duplicate, or mark that I no longer have it. Plants that die or get given away don&#39;t vanish. They go to <strong>In memoriam</strong>, the same way a real garden keeps its history.</p>
<p>My favorite detail: if I haven&#39;t photographed a plant in a while, it tells me. That one is &quot;24 days since a photo,&quot; so it&#39;s due for a check-in.</p>
<figure>
<a href="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-03-photo-timeline.webp"><img src="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-03-photo-timeline.webp" width="1311" height="924" loading="lazy" alt="Screenshot: A specimen''s photo timeline, grouped by every place it has lived"></a>
<figcaption>A specimen''s photo timeline, grouped by every place it has lived</figcaption>
</figure>
<p>The photo timeline is grouped by location, so I can see how a plant looked in every spot it&#39;s lived. This aeonium has been through the propagation area, a small pot, the ground, and a succulent bed, and the photos tell the whole story.</p>
<h3>The To Do screen: the garden tells me what it needs</h3>
<figure>
<a href="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-12-todo.webp"><img src="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-12-todo.webp" width="1311" height="924" loading="lazy" alt="Screenshot: The To Do screen: what needs attention, photos to file, and records to finish"></a>
<figcaption>The To Do screen: what needs attention, photos to file, and records to finish</figcaption>
</figure>
<p>This is the screen I open first. It&#39;s everything waiting on me, sorted by urgency:</p>
<ul><li><strong>Needs you now:</strong> plants needing attention, plants that need watering, plants due for a check-in, plants waiting to be planted, and blooms that might be over</li><li><strong>Photos to file:</strong> new photos that still need a plant, a location, or both</li><li><strong>Records to finish:</strong> plants missing photos or identification, locations I haven&#39;t catalogued, and likely duplicates</li></ul>
<p>Alongside that are my tasks (each linked to a specific plant), a <strong>wish list</strong> of plants I want, and today&#39;s weather, because that decides whether I&#39;m watering.</p>
<h3>Photos first</h3>
<figure>
<a href="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-04-inbox.webp"><img src="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-04-inbox.webp" width="1311" height="924" loading="lazy" alt="Screenshot: Photos waiting to be filed"></a>
<figcaption>Photos waiting to be filed</figcaption>
</figure>
<p>The way I actually use this app is photo-first. I take pictures in the garden, upload them, and they land in a queue waiting to be filed.</p>
<figure>
<a href="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-05-inbox-lightbox.webp"><img src="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-05-inbox-lightbox.webp" width="1311" height="924" loading="lazy" alt="Screenshot: One photo open, with every filing action along the bottom"></a>
<figcaption>One photo open, with every filing action along the bottom</figcaption>
</figure>
<p>Then I file them one by one. Each photo opens full-size with everything I need along the bottom: identify it, ask Claude, assign it to a plant or a location, create a new plant from it, favorite it, edit it, or delete it. When I finish one, the next one is already waiting.</p>
<p>A few details I&#39;m proud of:</p>
<ul><li><strong>Real photo dates.</strong> The app reads the date the photo was actually taken, not the date I uploaded it. Upload a year of photos at once and every timeline still comes out right.</li><li><strong>One photo, many plants.</strong> A wide shot of a pot with three plants in it can be tagged to all three.</li></ul>
<h3>&quot;What is this?&quot; Two AI opinions</h3>
<figure>
<a href="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-06-plantnet-candidates.webp"><img src="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-06-plantnet-candidates.webp" width="1311" height="924" loading="lazy" alt="Screenshot: Comparing Pl@ntNet candidates against my photo"></a>
<figcaption>Comparing Pl@ntNet candidates against my photo</figcaption>
</figure>
<p>For anything I can&#39;t name, the app gets a second opinion. Actually two.</p>
<p><strong>Pl@ntNet</strong> returns up to five candidate species with a match score. My photo sits at the top, and each candidate below it has reference photos plus one-tap searches on Google Images, iNaturalist, and Wikipedia. If the top guess is wrong, I pick another with &quot;Use this instead.&quot;</p>
<figure>
<a href="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-07-claude-identification.webp"><img src="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-07-claude-identification.webp" width="1311" height="924" loading="lazy" alt="Screenshot: Claude''s identification, with its confidence and reasoning"></a>
<figcaption>Claude''s identification, with its confidence and reasoning</figcaption>
</figure>
<p><strong>Claude</strong> gives a different kind of answer: its best identification, a confidence level, and its reasoning. &quot;Spherical, pea-like leaves trailing along thin stems&quot; tells me what to look at to confirm it, which is more useful than a bare percentage.</p>
<p>The most important design rule here: <strong>the AI never changes my data on its own.</strong> Every suggestion stays a suggestion until I confirm it, and every attempt is kept on record.</p>
<h3>Every plant has an address</h3>
<figure>
<a href="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-08-wall-collection.webp"><img src="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-08-wall-collection.webp" width="1311" height="924" loading="lazy" alt="Screenshot: The Wall Collection with its wall map of 92 cups"></a>
<figcaption>The Wall Collection with its wall map of 92 cups</figcaption>
</figure>
<p>Locations nest inside each other as deep as I need. The <strong>Wall Collection</strong> alone holds 99 plants in 92 cups across three rows, and the app draws it as a <strong>wall map</strong>: one tile per cup, in the same order they hang on the wall.</p>
<p>The map can color the cups by their actual paint color, by plant health, by when I last photographed them, by what&#39;s in bloom, or by which cups are empty. When I water the wall, one tap records it for all 99 plants.</p>
<figure>
<a href="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-09-location-detail.webp"><img src="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-09-location-detail.webp" width="1311" height="924" loading="lazy" alt="Screenshot: Bucket 34: one cup, its plant, and what used to live there"></a>
<figcaption>Bucket 34: one cup, its plant, and what used to live there</figcaption>
</figure>
<p>Every cup is its own location with its own photo and history. Bucket 34 holds a <em>Cotyledon</em> &#39;Cub Paws&#39; today, and it remembers that a <em>Mammillaria</em> lived there before it moved to the Plant Hospital. &quot;Show on the wall&quot; jumps back to its spot on the map.</p>
<p>The location list also has a section called <strong>From the Archives</strong>: every place the collection has lived before this house, starting with my old office. Old photos of past collections live there, so nothing gets lost.</p>
<h3>The family tree</h3>
<figure>
<a href="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-10-lineage.webp"><img src="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-10-lineage.webp" width="1311" height="924" loading="lazy" alt="Screenshot: A kind with six specimens, three of them propagated from a parent"></a>
<figcaption>A kind with six specimens, three of them propagated from a parent</figcaption>
</figure>
<p>Propagation is how a succulent collection really grows. When I take a cutting or pull a pup, the new plant gets its own accession number and a link back to its parent. Here, three new specimens sit nested under the plant they came from, each tagged &quot;propagated.&quot; Three generations later, I can still trace a cutting back to the mother plant.</p>
<h3>The Gallery</h3>
<figure>
<a href="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-11-gallery.webp"><img src="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-11-gallery.webp" width="1311" height="924" loading="lazy" alt="Screenshot: The Gallery: recent photos, garden albums, and what''s in bloom"></a>
<figcaption>The Gallery: recent photos, garden albums, and what''s in bloom</figcaption>
</figure>
<p>The Gallery is the fun part. It opens with the whole collection by the numbers, then recently added photos, <strong>Garden Albums</strong> for each part of the yard, and an <strong>In Bloom</strong> row for whatever&#39;s flowering right now. Everything is searchable by notes, plant, or location.</p>
<h3>Backups, because I&#39;ve been in IT too long</h3>
<figure>
<a href="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-13-settings.webp"><img src="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-13-settings.webp" width="1311" height="798" loading="lazy" alt="Screenshot: Settings: Drive connection, backup and restore, and photo date repair"></a>
<figcaption>Settings: Drive connection, backup and restore, and photo date repair</figcaption>
</figure>
<p>One tap downloads a full backup of everything, and another exports my plant list as a spreadsheet. The backup restores straight back into the app, and the restore only adds and updates, so it can never wipe anything out. There&#39;s even an error history, so when something fails, I can see what, when, and why. I&#39;ve spent too many years in IT to trust any system that can&#39;t do this.</p>
<h3>The public side</h3>
<figure>
<a href="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-14-justamanda-plants.webp"><img src="https://fsckwgicmvviefuivgza.supabase.co/storage/v1/object/public/journal/abg-app-14-justamanda-plants.webp" width="1311" height="924" loading="lazy" alt="Screenshot: The Succulents page on justamanda.net"></a>
<figcaption>The Succulents page on justamanda.net</figcaption>
</figure>
<p>The app is private. It&#39;s my back office. The public face of the collection lives right here on the <a href="plants.html">Succulents page</a>, along with build stories like the Succulent Wall.</p>
<hr>
<h2>How I built it</h2>
<p>I&#39;m not a professional developer. I lead technical support teams, and I think in systems, flows, and edge cases. That turned out to be the perfect background for building this with AI.</p>
<p>My process looks like this:</p>
<ol><li><strong>Scope with Claude.</strong> I describe what I want in plain language: the behavior, the outcome, the problem. Claude acts as a thinking partner. It asks questions, pokes holes, and helps me make the design decisions before any code exists.</li><li><strong>Build with Claude Code.</strong> Once a feature is scoped, I hand it to Claude Code, which finds the right place in the code, implements it, and explains what it did.</li><li><strong>Test, adjust, ship.</strong> I try it on real plants with real photos. Then I bundle related changes into one release.</li></ol>
<p>I work requirements-first. I describe what I see and what I want, not which lines to change. But I&#39;m not a passive passenger. I push back, suggest approaches, and make the architecture calls. The AI handles the typing; I own the system.</p>
<h3>Boring on purpose</h3>
<p>I made one big decision early and have stuck to it: <strong>keep it simple.</strong></p>
<p>The entire app is a single HTML file. No framework, no build step, no external JavaScript libraries. When I needed to read photo dates from image files, I didn&#39;t install a library. Claude Code wrote a small reader by hand.</p>
<p>That sounds extreme, but it&#39;s paid off constantly. There&#39;s nothing to compile, nothing to update, and nothing that breaks because some dependency changed. Over two major versions, I can still open one file and see the whole app.</p>
<hr>
<h2>Under the hood (for the builders)</h2>
<p>If you&#39;re not into the technical side, skip to &quot;What I learned.&quot; If you are, here&#39;s how it fits together.</p>
<p>[[ARCHITECTURE DIAGRAM: 15-architecture.png is coming separately. Caption: Architecture: single-file app, Supabase, Google Drive, Pl@ntNet, and Claude]]</p>
<ul><li><strong>Frontend:</strong> one vanilla HTML/CSS/JS file, hosted free on GitHub Pages</li><li><strong>Backend:</strong> Supabase for the Postgres database, authentication, and server-side functions</li><li><strong>Photos:</strong> Google Drive</li><li><strong>Identification:</strong> Pl@ntNet and Claude, both called from server-side functions so no API keys live in the browser</li></ul>
<h3>Accession numbers the database enforces</h3>
<p>Accession numbers are generated by a database trigger that increments a per-year counter, and a second trigger rejects any attempt to change a number once it&#39;s set. One gotcha: the counter table is locked down by row-level security, which blocked the trigger itself the first time I added a plant. The fix was letting those two functions run with elevated privileges rather than opening up the counter table.</p>
<h3>The Google Drive saga</h3>
<p>Storing 4,000 photos in Google Drive took more tries than anything else in the app.</p>
<ol><li><strong>Service account:</strong> dead end. Service accounts have zero storage quota on personal Google accounts, so they can&#39;t upload anything, even to a folder you&#39;ve shared with them.</li><li><strong>Sign in with Google into a folder I created:</strong> 404. The limited Drive permission I was using can only see files and folders the app itself created, even ones I own.</li><li><strong>Let the app create its own folder:</strong> worked, with a catch. An unpublished Google app&#39;s sign-in expires every week, so for months I reconnected Drive every Monday-ish.</li><li><strong>A Google Workspace account, and publishing the app:</strong> the weekly reconnect is finally gone. The Settings screen now says it plainly: this connection does not expire.</li></ol>
<h3>The CORS error that wasn&#39;t</h3>
<p>At one point, Claude identification failed with a CORS error. Classic misdirection: the real problem was a crash converting large photos before sending them, which happened before the function could attach its CORS headers. The browser reported the symptom, not the cause. Converting the image in small chunks fixed it. Lesson filed: <strong>a CORS error plus a 500 on a server function usually means a crash, not a CORS problem.</strong></p>
<h3>Fast paths plus flexibility</h3>
<p>Plants have one primary location stored directly on the record, plus optional extra locations in a separate table. Same for photos and plants. Almost everything has exactly one, so the common case stays fast and simple, and the rare plant that sprawls across two pots still works.</p>
<hr>
<h2>What I learned</h2>
<ul><li><strong>Borrow from the experts.</strong> Botanical gardens have been solving inventory for centuries. Accession numbers, provenance, and deaccessioning were all there for the taking.</li><li><strong>Simple scales further than you&#39;d think.</strong> One file and a few services got me to 263 plants, 4,035 photos, and version 2.72 with very little that can break.</li><li><strong>Build for the habit, not the database.</strong> The biggest leap wasn&#39;t storing more data. It was the To Do screen telling me what the garden needs, so the app fits how I actually garden.</li><li><strong>AI is a collaborator, not an autopilot.</strong> The best results came from treating Claude like a sharp partner: clear requirements in, real reasoning out, and me making the final calls. That goes for the identification feature too.</li></ul>
<hr>
<h2>What&#39;s next</h2>
<p>[[WHAT&#39;S NEXT: confirm these two are still on the roadmap and not shipped yet.]]</p>
<ul><li><strong>A smarter photo upload</strong>, where one AI pass suggests the identification, health notes, blooms, and location all at once</li><li><strong>An in-app idea capture</strong>, so new feature ideas go straight into the backlog from my phone</li></ul>
<p>I&#39;ll write about each one as it ships.</p>
<hr>
<p><em>Have a collection that&#39;s outgrown your memory? I&#39;d love to hear how you keep track of yours. [[CALL TO ACTION: where should readers reply? Instagram, Threads, email?]]</em></p>'
)
on conflict (slug) do nothing;
