<?php
/**
 * Title: Journal list, for archives
 * Slug: justamanda/journal-list-inherit
 * Categories: justamanda
 * Inserter: no
 * Description: The post list that follows the page's own query: tag, category and date archives.
 *
 * @package justamanda
 */
?>
<!-- wp:group {"tagName":"section","layout":{"type":"default"}} -->
<section class="wp-block-group"><!-- wp:group {"className":"wrap","layout":{"type":"default"}} -->
<div class="wp-block-group wrap"><!-- wp:query {"queryId":12,"query":{"perPage":20,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true}} -->
<div class="wp-block-query"><!-- wp:post-template {"className":"post-list"} -->
<!-- wp:group {"className":"post-card","layout":{"type":"default"}} -->
<div class="wp-block-group post-card"><!-- wp:post-featured-image {"sizeSlug":"ja-column"} /-->

<!-- wp:group {"className":"pc-body","layout":{"type":"default"}} -->
<div class="wp-block-group pc-body"><!-- wp:post-date {"format":"F j, Y","className":"eyebrow"} /-->

<!-- wp:post-title {"level":3,"isLink":true} /-->

<!-- wp:post-excerpt {"excerptLength":40} /-->

<!-- wp:read-more {"content":"Read more →","className":"go"} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
<!-- /wp:post-template -->

<!-- wp:query-no-results -->
<!-- wp:paragraph {"className":"muted"} -->
<p class="muted">Nothing published yet. The first post is coming.</p>
<!-- /wp:paragraph -->
<!-- /wp:query-no-results -->

<!-- wp:query-pagination {"layout":{"type":"flex","justifyContent":"center"}} -->
<!-- wp:query-pagination-previous /-->

<!-- wp:query-pagination-next /-->
<!-- /wp:query-pagination --></div>
<!-- /wp:query --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
