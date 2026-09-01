-- justamanda.net journal — runs in the adcock-plant-inventory Supabase project.
-- Safe to run more than once.

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

-- Keeps updated_at honest, and stamps published_at the first time a post goes
-- live so a published row can never be invisible for want of a date.
create or replace function public.posts_touch() returns trigger
language plpgsql as $fn$
begin
  new.updated_at := now();
  if new.status = 'published' and new.published_at is null then
    new.published_at := now();
  end if;
  return new;
end
$fn$;

drop trigger if exists posts_touch on public.posts;
create trigger posts_touch before insert or update on public.posts
  for each row execute function public.posts_touch();

alter table public.posts enable row level security;

-- The public sees published posts only, and only once their date has arrived —
-- which makes a future published_at act as scheduling.
drop policy if exists posts_public_read on public.posts;
create policy posts_public_read on public.posts
  for select to anon, authenticated
  using (status = 'published' and published_at is not null and published_at <= now());

-- Write access is scoped to one address, NOT to "any authenticated user".
-- Public signup is enabled on this project, so `to authenticated using (true)`
-- would let anyone who registers read the drafts and delete the posts.
-- To write from a different account, add its address to the list below.
drop policy if exists posts_owner_all on public.posts;
create policy posts_owner_all on public.posts
  for all to authenticated
  using      ((auth.jwt() ->> 'email') in ('me@justamanda.net'))
  with check ((auth.jwt() ->> 'email') in ('me@justamanda.net'));

-- Images for posts. Public bucket: reads go through the public URL.
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
