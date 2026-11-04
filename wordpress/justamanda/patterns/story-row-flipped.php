<?php
/**
 * Title: Story row, flipped
 * Slug: justamanda/story-row-flipped
 * Categories: justamanda
 * Description: The story row with the illustration on the left. Stacks text first below 900px.
 *
 * @package justamanda
 */
?>
<!-- wp:group {"className":"story is-flipped","layout":{"type":"default"}} -->
<div class="wp-block-group story is-flipped"><!-- wp:group {"layout":{"type":"default"}} -->
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
