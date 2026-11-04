<?php
/**
 * Title: Links footer badge
 * Slug: justamanda/links-foot
 * Categories: justamanda
 * Description: The small logo badge that closes the links page, linking home.
 *
 * @package justamanda
 */
?>
<!-- wp:group {"className":"lt-foot","layout":{"type":"default"}} -->
<div class="wp-block-group lt-foot"><!-- wp:image {"sizeSlug":"full","linkDestination":"custom"} -->
<figure class="wp-block-image size-full"><a href="/"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/logo-badge.png' ) ); ?>" alt="just amanda"/></a></figure>
<!-- /wp:image --></div>
<!-- /wp:group -->
