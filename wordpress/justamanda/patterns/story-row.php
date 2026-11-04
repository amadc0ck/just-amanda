<?php
/**
 * Title: Story row
 * Slug: justamanda/story-row
 * Categories: justamanda
 * Description: Text beside an illustration: a section heading, prose and a handwritten beat, with the picture on the right. Stack rows inside one section; alternate with the flipped row.
 *
 * @package justamanda
 */
?>
<!-- wp:group {"className":"story","layout":{"type":"default"}} -->
<div class="wp-block-group story"><!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group"><!-- wp:group {"className":"sec-head","layout":{"type":"default"}} -->
<div class="wp-block-group sec-head"><!-- wp:heading -->
<h2 class="wp-block-heading">Section heading</h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"prose","layout":{"type":"default"}} -->
<div class="wp-block-group prose"><!-- wp:paragraph -->
<p>A paragraph of the story.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Another paragraph, as long as it needs to be.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"beat"} -->
<p class="beat">A handwritten beat, used sparingly.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"story-art"} -->
<figure class="wp-block-image size-full story-art"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/ornament-succulent.png' ) ); ?>" alt=""/></figure>
<!-- /wp:image --></div>
<!-- /wp:group -->
