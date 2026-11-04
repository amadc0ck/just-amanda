<?php
/**
 * Title: Hobby card
 * Slug: justamanda/card
 * Categories: justamanda
 * Description: One card for a cards row: illustration, heading link, a line, and an arrow.
 *
 * @package justamanda
 */
?>
<!-- wp:group {"className":"card is-succulents","layout":{"type":"default"}} -->
<div class="wp-block-group card is-succulents"><!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/ornament-succulent.png' ) ); ?>" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"card-body","layout":{"type":"default"}} -->
<div class="wp-block-group card-body"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><a href="/plants/">Succulents</a></h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>A line about it.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"go"} -->
<p class="go">→</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
