<?php
/**
 * Title: Resource cards
 * Slug: justamanda/trio-cards
 * Categories: justamanda
 * Description: Cards with a small illustration beside a heading and a paragraph, set three to a row.
 *
 * @package justamanda
 */
?>
<!-- wp:group {"className":"cards trio","layout":{"type":"default"}} -->
<div class="wp-block-group cards trio"><!-- wp:group {"tagName":"article","className":"card","layout":{"type":"default"}} -->
<article class="wp-block-group card"><!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/ornament-succulent.png' ) ); ?>" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"card-body","layout":{"type":"default"}} -->
<div class="wp-block-group card-body"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Books</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>A paragraph about it, with <a href="https://example.com/">a link</a> if needed.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></article>
<!-- /wp:group -->

<!-- wp:group {"tagName":"article","className":"card","layout":{"type":"default"}} -->
<article class="wp-block-group card"><!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/ornament-lavender.png' ) ); ?>" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"card-body","layout":{"type":"default"}} -->
<div class="wp-block-group card-body"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Nurseries</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>A paragraph about it.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></article>
<!-- /wp:group -->

<!-- wp:group {"tagName":"article","className":"card","layout":{"type":"default"}} -->
<article class="wp-block-group card"><!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/ornament-violets.png' ) ); ?>" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"card-body","layout":{"type":"default"}} -->
<div class="wp-block-group card-body"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Identification</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>A paragraph about it.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></article>
<!-- /wp:group --></div>
<!-- /wp:group -->
