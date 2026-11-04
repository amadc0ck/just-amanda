<?php
/**
 * Title: Hero, with portrait
 * Slug: justamanda/hero-portrait
 * Categories: justamanda
 * Description: Heading with an italic accent line and a paragraph, beside a portrait capped at 400px wide. The About page.
 *
 * @package justamanda
 */
?>
<!-- wp:group {"className":"hero","layout":{"type":"default"}} -->
<div class="wp-block-group hero"><!-- wp:group {"className":"wrap","layout":{"type":"default"}} -->
<div class="wp-block-group wrap"><!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group"><!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">Hi,<em>an accent line.</em></h1>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>A sentence or two of introduction.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"hero-art is-portrait"} -->
<figure class="wp-block-image size-full hero-art is-portrait"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/ornament-succulent.png' ) ); ?>" alt=""/></figure>
<!-- /wp:image --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
