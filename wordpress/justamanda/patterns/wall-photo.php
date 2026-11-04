<?php
/**
 * Title: Wall photo
 * Slug: justamanda/wall-photo
 * Categories: justamanda
 * Description: A full-width photograph with a caption underneath, on its own band.
 *
 * @package justamanda
 */
?>
<!-- wp:group {"tagName":"section","className":"pb-0","layout":{"type":"default"}} -->
<section class="wp-block-group pb-0"><!-- wp:group {"className":"wrap","layout":{"type":"default"}} -->
<div class="wp-block-group wrap"><!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"wall-photo"} -->
<figure class="wp-block-image size-full wall-photo"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/ornament-succulent.png' ) ); ?>" alt="Describe the photograph"/><figcaption class="wp-element-caption">A caption for the photograph.</figcaption></figure>
<!-- /wp:image --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
