<?php
/**
 * Title: Journal carousel
 * Slug: justamanda/post-carousel
 * Categories: justamanda
 * Description: Posts with chosen tags in a row that scrolls sideways; the arrows are added by script. The section hides itself when no post matches.
 *
 * @package justamanda
 */
?>
<!-- wp:group {"tagName":"section","className":"pt-0","layout":{"type":"default"}} -->
<section class="wp-block-group pt-0"><!-- wp:group {"className":"wrap","layout":{"type":"default"}} -->
<div class="wp-block-group wrap"><!-- wp:group {"className":"sec-head","layout":{"type":"default"}} -->
<div class="wp-block-group sec-head"><!-- wp:heading -->
<h2 class="wp-block-heading">From the journal</h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"carousel","layout":{"type":"default"}} -->
<div class="wp-block-group carousel"><!-- wp:query {"queryId":21,"query":{"perPage":12,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false}} -->
<div class="wp-block-query"><!-- wp:post-template {"className":"carousel-track"} -->
<!-- wp:group {"className":"post-card","layout":{"type":"default"}} -->
<div class="wp-block-group post-card"><!-- wp:post-featured-image {"sizeSlug":"ja-column"} /-->

<!-- wp:group {"className":"pc-body","layout":{"type":"default"}} -->
<div class="wp-block-group pc-body"><!-- wp:post-date {"format":"F j, Y","className":"eyebrow"} /-->

<!-- wp:post-title {"level":3,"isLink":true} /-->

<!-- wp:post-excerpt {"excerptLength":40} /-->

<!-- wp:read-more {"content":"Read more →","className":"go"} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
<!-- /wp:post-template --></div>
<!-- /wp:query --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
