<?php
/**
 * Title: Latest from the journal
 * Slug: justamanda/journal-latest
 * Categories: justamanda
 * Description: Lavender band with the amethyst wash, the three newest posts, and a link to the rest.
 *
 * @package justamanda
 */
?>
<!-- wp:group {"tagName":"section","className":"band is-reading washed","layout":{"type":"default"}} -->
<section class="wp-block-group band is-reading washed"><!-- wp:group {"className":"wrap","layout":{"type":"default"}} -->
<div class="wp-block-group wrap"><!-- wp:group {"className":"sec-head","layout":{"type":"default"}} -->
<div class="wp-block-group sec-head"><!-- wp:heading -->
<h2 class="wp-block-heading">Latest from the journal</h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:query {"queryId":11,"query":{"perPage":3,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false}} -->
<div class="wp-block-query"><!-- wp:post-template {"className":"cards stacked"} -->
<!-- wp:group {"className":"card","layout":{"type":"default"}} -->
<div class="wp-block-group card"><!-- wp:post-date {"format":"F j, Y","className":"tagline"} /-->

<!-- wp:post-title {"level":3,"isLink":true} /-->

<!-- wp:post-excerpt {"excerptLength":40} /-->

<!-- wp:read-more {"content":"Read more →","className":"go"} /--></div>
<!-- /wp:group -->
<!-- /wp:post-template -->

<!-- wp:query-no-results -->
<!-- wp:paragraph {"className":"muted"} -->
<p class="muted">The journal is wired up and waiting for words.</p>
<!-- /wp:paragraph -->
<!-- /wp:query-no-results --></div>
<!-- /wp:query -->

<!-- wp:paragraph {"className":"home-more"} -->
<p class="home-more"><a href="/journal/">All journal entries →</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
