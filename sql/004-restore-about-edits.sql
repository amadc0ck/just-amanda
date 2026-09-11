-- Recovers six About-page edits that were lost to the save bug fixed in 375ecf5.
-- api() threw on PostgREST's empty 201 body AFTER writing, so the save loop wrote
-- the first row (about.p-1) and aborted before the rest. These are those rest,
-- transcribed from Amanda's screenshots.
--
-- Safe to run more than once: it upserts on key.

insert into public.site_text (key, page, value_md, value_html, default_text, updated_at) values
  ('about.p-11', 'about.html', 'I’ve had to learn about narcissistic abuse and codependency because those words gave me language for things I had lived through but didn’t fully understand at the time.', 'I’ve had to learn about narcissistic abuse and codependency because those words gave me language for things I had lived through but didn’t fully understand at the time.', 'I’ve been married, divorced, and married again. I’ve had to learn about narcissistic abuse and codependency because those words eventually gave me language for things I had lived through but didn’t fully understand at the time.', now()),
  ('about.p-12', 'about.html', 'Parenting has sent me searching for answers more times than I could count. I’ve learned more about baseball in the last year than I care to have ever known.', 'Parenting has sent me searching for answers more times than I could count. I’ve learned more about baseball in the last year than I care to have ever known.', 'Parenting has sent me searching for answers more times than I could count. Learning that I have ADHD changed the way I understood parts of myself I’d spent years trying to explain.', now()),
  ('about.p-13', 'about.html', 'But, when something matters to me, I want to understand it. I want to be the expert. Sometimes that means learning the taxonomy of a plant or embarking on a journey to become a master of Crochet.', 'But, when something matters to me, I want to understand it. I want to be the expert. Sometimes that means learning the taxonomy of a plant or embarking on a journey to become a master of Crochet.', 'When something matters to me, I want to understand it. Sometimes that means learning the taxonomy of a plant or figuring out why a 3D print keeps failing.', now()),
  ('about.p-14', 'about.html', 'Sometimes the thing I’ve set out to learn comes from just trying to solve an every day problem.', 'Sometimes the thing I’ve set out to learn comes from just trying to solve an every day problem.', 'Sometimes the thing I’m trying to understand is myself.', now()),
  ('about.p-25', 'about.html', 'That gap just got a whole lot smaller.', 'That gap just got a whole lot smaller.', 'That gap has gotten a whole lot smaller.', now()),
  ('about.p-28', 'about.html', 'Recently that has meant building an app for problems that exist because of my own hobbies: a full catalogue for the my succulent collection, and the site you are reading', 'Recently that has meant building an app for problems that exist because of my own hobbies: a full catalogue for the my succulent collection, and the site you are reading', 'Recently that has meant building software for problems that exist because of my own hobbies: a full catalogue for the plant collection, and the site you are reading.', now())
on conflict (key) do update set
  value_md     = excluded.value_md,
  value_html   = excluded.value_html,
  default_text = excluded.default_text,
  updated_at   = now();
