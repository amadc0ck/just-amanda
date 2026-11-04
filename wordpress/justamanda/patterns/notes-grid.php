<?php
/**
 * Title: Book notes
 * Slug: justamanda/notes-grid
 * Categories: justamanda
 * Description: The newest posts tagged books as small cards, with a closing card that says more are coming. With no tagged posts the closing card sits alone, centred.
 *
 * @package justamanda
 */
?>
<!-- wp:group {"className":"notes-grid","layout":{"type":"default"}} -->
<div class="wp-block-group notes-grid"><!-- wp:query {"queryId":21,"query":{"perPage":5,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false}} -->
<div class="wp-block-query"><!-- wp:post-template -->
<!-- wp:group {"className":"note-card","layout":{"type":"default"}} -->
<div class="wp-block-group note-card"><!-- wp:post-featured-image {"sizeSlug":"ja-column"} /-->

<!-- wp:group {"className":"nc-body","layout":{"type":"default"}} -->
<div class="wp-block-group nc-body"><!-- wp:post-date {"format":"F j, Y"} /-->

<!-- wp:post-title {"level":3,"isLink":true} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
<!-- /wp:post-template --></div>
<!-- /wp:query -->

<!-- wp:group {"className":"note-card is-soon","layout":{"type":"default"}} -->
<div class="wp-block-group note-card is-soon"><!-- wp:paragraph -->
<p>More coming soon.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"script"} -->
<p class="script">A handwritten line.</p>
<!-- /wp:paragraph -->

<!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/ornament-amethyst.png' ) ); ?>" alt=""/></figure>
<!-- /wp:image --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
