<?php
/**
 * Title: Links explore tiles
 * Slug: justamanda/links-tiles
 * Categories: justamanda
 * Description: A labelled row of small illustrated tiles, each linking to a page. Tiles for unopened pages are greyed and tagged soon automatically.
 *
 * @package justamanda
 */
?>
<!-- wp:group {"tagName":"section","className":"lt-group","layout":{"type":"default"}} -->
<section class="wp-block-group lt-group"><!-- wp:heading {"className":"lt-label"} -->
<h2 class="wp-block-heading lt-label">Explore</h2>
<!-- /wp:heading -->

<!-- wp:group {"className":"lt-tiles","layout":{"type":"default"}} -->
<div class="wp-block-group lt-tiles"><!-- wp:group {"className":"lt-tile","layout":{"type":"default"}} -->
<div class="wp-block-group lt-tile"><!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/ornament-succulent.png' ) ); ?>" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:paragraph -->
<p><a href="/plants/">Succulents</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"lt-tile","layout":{"type":"default"}} -->
<div class="wp-block-group lt-tile"><!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/ornament-violets.png' ) ); ?>" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:paragraph -->
<p><a href="/books/">Books</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
