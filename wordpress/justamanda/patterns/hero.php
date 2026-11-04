<?php
/**
 * Title: Hero, with illustration
 * Slug: justamanda/hero
 * Categories: justamanda
 * Description: Large heading with an italic accent line, a paragraph, two buttons, and an illustration beside it. Stacks below 900px.
 *
 * @package justamanda
 */
?>
<!-- wp:group {"className":"hero","layout":{"type":"default"}} -->
<div class="wp-block-group hero"><!-- wp:group {"className":"wrap","layout":{"type":"default"}} -->
<div class="wp-block-group wrap"><!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group"><!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">A heading,<em>with an accent line.</em></h1>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>A sentence or two about what this page is for.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"className":"btn-row"} -->
<div class="wp-block-buttons btn-row"><!-- wp:button {"className":"is-style-primary"} -->
<div class="wp-block-button is-style-primary"><a class="wp-block-button__link wp-element-button" href="/about/">More about me →</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-read"} -->
<div class="wp-block-button is-style-read"><a class="wp-block-button__link wp-element-button" href="/journal/">Read the journal →</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->

<!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"hero-art"} -->
<figure class="wp-block-image size-full hero-art"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/ornament-succulent.png' ) ); ?>" alt=""/></figure>
<!-- /wp:image --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
