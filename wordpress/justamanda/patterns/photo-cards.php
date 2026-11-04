<?php
/**
 * Title: Photo cards
 * Slug: justamanda/photo-cards
 * Categories: justamanda
 * Description: Favourites with a square photograph on top and the story underneath. These cards are not links.
 *
 * @package justamanda
 */
?>
<!-- wp:group {"className":"cards photos","layout":{"type":"default"}} -->
<div class="wp-block-group cards photos"><!-- wp:group {"tagName":"article","className":"card","layout":{"type":"default"}} -->
<article class="wp-block-group card"><!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/ornament-succulent.png' ) ); ?>" alt="Describe the photograph"/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"card-body","layout":{"type":"default"}} -->
<div class="wp-block-group card-body"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Plant name</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>A few lines about this one.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></article>
<!-- /wp:group -->

<!-- wp:group {"tagName":"article","className":"card","layout":{"type":"default"}} -->
<article class="wp-block-group card"><!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/ornament-lavender.png' ) ); ?>" alt="Describe the photograph"/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"card-body","layout":{"type":"default"}} -->
<div class="wp-block-group card-body"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Plant name</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"nickname"} -->
<p class="nickname">A nickname</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>A few lines about this one.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></article>
<!-- /wp:group -->

<!-- wp:group {"tagName":"article","className":"card","layout":{"type":"default"}} -->
<article class="wp-block-group card"><!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/ornament-violets.png' ) ); ?>" alt="Describe the photograph"/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"card-body","layout":{"type":"default"}} -->
<div class="wp-block-group card-body"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Plant name</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>A few lines about this one.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></article>
<!-- /wp:group --></div>
<!-- /wp:group -->
