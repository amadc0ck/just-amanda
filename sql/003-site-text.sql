-- justamanda.net page text. Runs in the adcock-plant-inventory Supabase
-- project, same as the journal and the home cards. Plain ASCII, safe to run
-- more than once.
--
-- Every editable block on the site carries data-edit="page.tag-n" in the HTML.
-- The authored words stay in the HTML and are the default. A row here
-- OVERRIDES one block. No row means the page shows what was written into it,
-- so the site is correct with no JavaScript, no network and no database.
--
-- default_text records what the page said at the moment the override was
-- saved. If someone later edits the HTML, admin can compare and warn that the
-- underlying text moved, instead of silently masking a change nobody sees.

-- STEP 1 ------------------------------------------------------------------
create table if not exists public.site_text (
  key          text primary key,
  page         text not null,
  value_md     text not null default '',
  value_html   text not null default '',
  default_text text not null default '',
  updated_at   timestamptz not null default now()
);

create index if not exists site_text_page_idx on public.site_text (page);

-- STEP 2 ------------------------------------------------------------------
alter table public.site_text enable row level security;

drop policy if exists site_text_public_read on public.site_text;
create policy site_text_public_read on public.site_text
  for select to anon, authenticated
  using (true);

-- Write access is scoped to one address, NOT to any authenticated user.
-- Public signup is open on this project, so "to authenticated using (true)"
-- would let anyone who registers rewrite any sentence on the site.
drop policy if exists site_text_owner_all on public.site_text;
create policy site_text_owner_all on public.site_text
  for all to authenticated
  using      ((auth.jwt() ->> 'email') in ('me@justamanda.net'))
  with check ((auth.jwt() ->> 'email') in ('me@justamanda.net'));

-- No seed. An empty table means every page shows its authored text, which is
-- the correct starting state.
