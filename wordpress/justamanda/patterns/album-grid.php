<?php
/**
 * Title: Album grid
 * Slug: justamanda/album-grid
 * Categories: justamanda
 * Keywords: albums, photography, gallery, grid
 * Description: Every album page nested under Photography, as cover cards in page order. New albums appear on their own.
 *
 * @package justamanda
 */

$justamanda_photography = get_page_by_path( 'photography' );
$justamanda_parent      = $justamanda_photography ? (int) $justamanda_photography->ID : 0;
?>
<!-- wp:query {"queryId":21,"query":{"perPage":100,"pages":0,"offset":0,"postType":"page","order":"asc","orderBy":"title","author":"","search":"","exclude":[],"sticky":"","inherit":false,"parents":[<?php echo (int) $justamanda_parent; ?>]},"className":"album-grid"} -->
<div class="wp-block-query album-grid"><!-- wp:post-template -->
<!-- wp:group {"className":"album-card","layout":{"type":"default"}} -->
<div class="wp-block-group album-card"><!-- wp:post-featured-image {"isLink":true,"aspectRatio":"4/3","sizeSlug":"medium_large"} /-->

<!-- wp:group {"className":"album-card-body","layout":{"type":"default"}} -->
<div class="wp-block-group album-card-body"><!-- wp:post-title {"level":3,"isLink":true} /-->

<!-- wp:post-excerpt {"moreText":"","excerptLength":24} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
<!-- /wp:post-template -->

<!-- wp:query-no-results -->
<!-- wp:paragraph {"className":"muted"} -->
<p class="muted">Albums are on their way.</p>
<!-- /wp:paragraph -->
<!-- /wp:query-no-results --></div>
<!-- /wp:query -->
