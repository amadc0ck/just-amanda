<?php
/**
 * Title: Author tier
 * Slug: justamanda/author-tier
 * Categories: justamanda
 * Description: Author cards in a row, each with a blurb, series names and a shelf. A noted card spans the shelf and carries a handwritten line; keep noted cards first in a row. Use is-pair or is-trio for the column count.
 *
 * @package justamanda
 */
?>
<!-- wp:paragraph {"className":"sec-label"} -->
<p class="sec-label">Tier label</p>
<!-- /wp:paragraph -->

<!-- wp:group {"className":"author-tier is-pair","layout":{"type":"default"}} -->
<div class="wp-block-group author-tier is-pair"><!-- wp:group {"className":"author-card","layout":{"type":"default"}} -->
<div class="wp-block-group author-card"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Author name</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Why this author.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"series-name"} -->
<p class="series-name">Series name</p>
<!-- /wp:paragraph -->

<!-- wp:group {"className":"bookrow","layout":{"type":"default"}} -->
<div class="wp-block-group bookrow"><!-- wp:group {"className":"bookcard is-1 has-cover is-noted","layout":{"type":"default"}} -->
<div class="wp-block-group bookcard is-1 has-cover is-noted"><!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/ornament-violets.png' ) ); ?>" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"bc-txt","layout":{"type":"default"}} -->
<div class="wp-block-group bc-txt"><!-- wp:paragraph -->
<p>Book title</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"bc-note script"} -->
<p class="bc-note script">A handwritten line about this book.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"bookcard is-1 has-cover","layout":{"type":"default"}} -->
<div class="wp-block-group bookcard is-1 has-cover"><!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/ornament-violets.png' ) ); ?>" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"className":"bc-txt"} -->
<p class="bc-txt">Book title</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"author-card","layout":{"type":"default"}} -->
<div class="wp-block-group author-card"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Author name</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Why this author.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"series-name"} -->
<p class="series-name">Series name</p>
<!-- /wp:paragraph -->

<!-- wp:group {"className":"bookrow","layout":{"type":"default"}} -->
<div class="wp-block-group bookrow"><!-- wp:group {"className":"bookcard is-1 has-cover is-noted","layout":{"type":"default"}} -->
<div class="wp-block-group bookcard is-1 has-cover is-noted"><!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/ornament-violets.png' ) ); ?>" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"bc-txt","layout":{"type":"default"}} -->
<div class="wp-block-group bc-txt"><!-- wp:paragraph -->
<p>Book title</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"bc-note script"} -->
<p class="bc-note script">A handwritten line about this book.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"bookcard is-1 has-cover","layout":{"type":"default"}} -->
<div class="wp-block-group bookcard is-1 has-cover"><!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/ornament-violets.png' ) ); ?>" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"className":"bc-txt"} -->
<p class="bc-txt">Book title</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
