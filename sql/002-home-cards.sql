-- justamanda.net home page cards. Runs in the adcock-plant-inventory Supabase
-- project, same as the journal. Plain ASCII, safe to run more than once.
--
-- The five cards on the home page keep their text in index.html as authored.
-- This table OVERRIDES that text when a row exists, so the page still renders
-- correctly with no network, no JavaScript, and no database. Editing here is
-- an override, never the only copy.

-- STEP 1 ------------------------------------------------------------------
create table if not exists public.home_cards (
  slug       text primary key,
  title      text not null,
  blurb      text not null,
  sort_order int  not null default 0,
  updated_at timestamptz not null default now()
);

-- STEP 2 ------------------------------------------------------------------
alter table public.home_cards enable row level security;

drop policy if exists home_cards_public_read on public.home_cards;
create policy home_cards_public_read on public.home_cards
  for select to anon, authenticated
  using (true);

-- Write access is scoped to one address, NOT to any authenticated user.
-- Public signup is open on this project, so "to authenticated using (true)"
-- would let anyone who registers rewrite the front page.
drop policy if exists home_cards_owner_all on public.home_cards;
create policy home_cards_owner_all on public.home_cards
  for all to authenticated
  using      ((auth.jwt() ->> 'email') in ('me@justamanda.net'))
  with check ((auth.jwt() ->> 'email') in ('me@justamanda.net'));

-- STEP 3 ------------------------------------------------------------------
-- Seeded with what index.html says today. ON CONFLICT DO NOTHING, so running
-- this again after an edit will not stomp the edit.
insert into public.home_cards (slug, title, blurb, sort_order) values
  ('succulents', 'Succulents', 'all cacti are succulents, but not all succulents are cacti', 1),
  ('chickens',   'Chickens',   'chickens are better pets than most people assume',            2),
  ('crochet',    'Crochet',    'the perfect fidget spinner for long meetings and audio books', 3),
  ('books',      'Books',      'faeries, vampires, dragons, smut. sign. me. up.',              4),
  ('tech',       'Tech',       'no way around it, i am a nerd.',                               5)
on conflict (slug) do nothing;
