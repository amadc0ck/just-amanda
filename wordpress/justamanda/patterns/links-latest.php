<?php
/**
 * Title: Links latest posts
 * Slug: justamanda/links-latest
 * Categories: justamanda
 * Description: The newest post as a card, then the next two as pill rows with a round thumbnail. Hidden when there are no posts.
 *
 * @package justamanda
 */
?>
<!-- wp:group {"tagName":"section","className":"lt-group lt-latest","layout":{"type":"default"}} -->
<section class="wp-block-group lt-group lt-latest"><!-- wp:heading {"className":"lt-label"} -->
<h2 class="wp-block-heading lt-label">Latest posts</h2>
<!-- /wp:heading -->

<!-- wp:query {"queryId":31,"query":{"perPage":1,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false}} -->
<div class="wp-block-query"><!-- wp:post-template -->
<!-- wp:group {"className":"lt-feature","layout":{"type":"default"}} -->
<div class="wp-block-group lt-feature"><!-- wp:post-featured-image {"sizeSlug":"ja-column"} /-->

<!-- wp:group {"className":"lt-feature-body","layout":{"type":"default"}} -->
<div class="wp-block-group lt-feature-body"><!-- wp:post-date {"format":"\\N\\e\\w · F j, Y","className":"eyebrow"} /-->

<!-- wp:post-title {"level":3,"isLink":true,"className":"lt-title"} /-->

<!-- wp:post-excerpt {"excerptLength":55} /-->

<!-- wp:read-more {"content":"Read it →","className":"go"} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
<!-- /wp:post-template --></div>
<!-- /wp:query -->

<!-- wp:group {"className":"lt-rows","layout":{"type":"default"}} -->
<div class="wp-block-group lt-rows"><!-- wp:query {"queryId":32,"query":{"perPage":2,"pages":0,"offset":1,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false}} -->
<div class="wp-block-query"><!-- wp:post-template -->
<!-- wp:group {"className":"lt-row","layout":{"type":"default"}} -->
<div class="wp-block-group lt-row"><!-- wp:post-featured-image {"sizeSlug":"thumbnail"} /-->

<!-- wp:group {"className":"lt-text","layout":{"type":"default"}} -->
<div class="wp-block-group lt-text"><!-- wp:post-title {"level":3,"isLink":true} /-->

<!-- wp:post-date {"format":"F j, Y"} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
<!-- /wp:post-template --></div>
<!-- /wp:query --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
