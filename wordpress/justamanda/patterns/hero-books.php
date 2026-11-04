<?php
/**
 * Title: Hero, books
 * Slug: justamanda/hero-books
 * Categories: justamanda
 * Description: The books page hero: heading with an accent line, a paragraph, and a wide illustration on the right.
 *
 * @package justamanda
 */
?>
<!-- wp:group {"className":"hero bk-hero","layout":{"type":"default"}} -->
<div class="wp-block-group hero bk-hero"><!-- wp:group {"className":"wrap","layout":{"type":"default"}} -->
<div class="wp-block-group wrap"><!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group"><!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">A heading,<em>with an accent line.</em></h1>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>A few sentences about what this page is for.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"hero-art"} -->
<figure class="wp-block-image size-full hero-art"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/ornament-lavender.png' ) ); ?>" alt=""/></figure>
<!-- /wp:image --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
