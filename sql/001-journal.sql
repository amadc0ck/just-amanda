-- justamanda.net journal. Runs in the adcock-plant-inventory Supabase project.
-- Plain ASCII, no dollar-quoting, safe to run more than once.
-- published_at and updated_at are set by admin.html, not by a trigger.

-- STEP 1 ------------------------------------------------------------------
create table if not exists public.posts (
  id           uuid primary key default gen_random_uuid(),
  slug         text unique not null,
  title        text not null,
  excerpt      text,
  body_md      text not null default '',
  body_html    text not null default '',
  cover_url    text,
  tags         text[] not null default '{}',
  status       text not null default 'draft' check (status in ('draft','published')),
  published_at timestamptz,
  created_at   timestamptz not null default now(),
  updated_at   timestamptz not null default now()
);

create index if not exists posts_published_idx
  on public.posts (status, published_at desc);

-- STEP 2 ------------------------------------------------------------------
alter table public.posts enable row level security;

drop policy if exists posts_public_read on public.posts;
create policy posts_public_read on public.posts
  for select to anon, authenticated
  using (status = 'published' and published_at is not null and published_at <= now());

-- Write access is scoped to one address, NOT to any authenticated user.
-- Public signup is open on this project, so "to authenticated using (true)"
-- would let anyone who registers read drafts and delete posts.
drop policy if exists posts_owner_all on public.posts;
create policy posts_owner_all on public.posts
  for all to authenticated
  using      ((auth.jwt() ->> 'email') in ('me@justamanda.net'))
  with check ((auth.jwt() ->> 'email') in ('me@justamanda.net'));

-- STEP 3 ------------------------------------------------------------------
insert into storage.buckets (id, name, public)
  values ('journal', 'journal', true)
  on conflict (id) do update set public = true;

drop policy if exists journal_read   on storage.objects;
drop policy if exists journal_insert on storage.objects;
drop policy if exists journal_update on storage.objects;
drop policy if exists journal_delete on storage.objects;

create policy journal_read on storage.objects
  for select to anon, authenticated using (bucket_id = 'journal');
create policy journal_insert on storage.objects
  for insert to authenticated
  with check (bucket_id = 'journal' and (auth.jwt() ->> 'email') in ('me@justamanda.net'));
create policy journal_update on storage.objects
  for update to authenticated
  using      (bucket_id = 'journal' and (auth.jwt() ->> 'email') in ('me@justamanda.net'))
  with check (bucket_id = 'journal' and (auth.jwt() ->> 'email') in ('me@justamanda.net'));
create policy journal_delete on storage.objects
  for delete to authenticated
  using (bucket_id = 'journal' and (auth.jwt() ->> 'email') in ('me@justamanda.net'));
