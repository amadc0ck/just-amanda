<?php
/**
 * Title: Hero, with a handwritten eyebrow
 * Slug: justamanda/hero-eyebrow
 * Categories: justamanda
 * Description: A Caveat line above the heading, one button, and an illustration. The chickens and tech pages.
 *
 * @package justamanda
 */
?>
<!-- wp:group {"className":"hero","layout":{"type":"default"}} -->
<div class="wp-block-group hero"><!-- wp:group {"className":"wrap","layout":{"type":"default"}} -->
<div class="wp-block-group wrap"><!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"className":"eyebrow-script"} -->
<p class="eyebrow-script">a handwritten aside</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">A heading,<em>with an accent line.</em></h1>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>A sentence or two about what this page is for.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"className":"is-solo"} -->
<div class="wp-block-buttons is-solo"><!-- wp:button {"className":"is-style-primary"} -->
<div class="wp-block-button is-style-primary"><a class="wp-block-button__link wp-element-button" href="#story">Read the story →</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->

<!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"hero-art"} -->
<figure class="wp-block-image size-full hero-art"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/ornament-lavender.png' ) ); ?>" alt=""/></figure>
<!-- /wp:image --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
