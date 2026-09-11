-- Recovers the "One thing leads to the next" rewrite, from plants to chickens.
-- Lost with the same save bug as sql/004; recovered from a screenshot.
-- about.p-8 is deliberately absent: it is word for word what the file says.
-- Safe to run more than once.

insert into public.site_text (key, page, value_md, value_html, default_text, updated_at) values
  ('about.p-5', 'about.html', 'Chickens are the clearest example.', 'Chickens are the clearest example.', 'Plants are the clearest example.', now()),
  ('about.p-6', 'about.html', 'I have a flock of chickens after rescuing one years ago. You can’t just feed and water a chicken, you have to learn about broodiness and molting. Reading a Polutry Science textbook led to anatomy, that led to breeds, that led to more chickens. Now, I have a predator-proof coop with a full walk in run.', 'I have a flock of chickens after rescuing one years ago. You can’t just feed and water a chicken, you have to learn about broodiness and molting. Reading a Polutry Science textbook led to anatomy, that led to breeds, that led to more chickens. Now, I have a predator-proof coop with a full walk in run.', 'I started collecting succulents because I liked how they looked. Then I wanted to know what they were called. Names led to taxonomy, taxonomy led to documenting the collection, and documenting it led to digging through years of old photographs to work out what I used to have.', now()),
  ('about.p-7', 'about.html', 'After having only one, I have five hens and a rooster. They each have names and personalities... and each one is unique.', 'After having only one, I have five hens and a rooster. They each have names and personalities... and each one is unique.', 'Now every plant in the garden has an accession number and a photographic record, the way a botanical garden keeps them.', now()),
  ('about.p-9', 'about.html', 'Crochet came from wanting to make a basket for collecting chicken eggs, and my son is the reason for 3D Printing.', 'Crochet came from wanting to make a basket for collecting chicken eggs, and my son is the reason for 3D Printing.', 'Crochet came back after years away. So did photography.', now())
on conflict (key) do update set
  value_md     = excluded.value_md,
  value_html   = excluded.value_html,
  default_text = excluded.default_text,
  updated_at   = now();
