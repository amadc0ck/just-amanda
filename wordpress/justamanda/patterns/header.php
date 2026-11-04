<?php
/**
 * Title: Header
 * Slug: justamanda/header
 * Categories: header
 * Block Types: core/template-part/header
 * Inserter: no
 * Description: Logotype and the main navigation. Sticky above 700px, stacked below.
 *
 * @package justamanda
 */
?>
<!-- wp:group {"className":"wrap","layout":{"type":"default"}} -->
<div class="wp-block-group wrap"><!-- wp:image {"sizeSlug":"full","linkDestination":"custom","className":"logo"} -->
<figure class="wp-block-image size-full logo"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/logo.png' ) ); ?>" alt="just amanda"/></a></figure>
<!-- /wp:image -->

<!-- wp:navigation {"overlayMenu":"never","layout":{"type":"flex","flexWrap":"wrap"},"ariaLabel":"Main"} -->
<!-- wp:navigation-link {"label":"Home","url":"/","kind":"custom"} /-->
<!-- wp:navigation-link {"label":"About","url":"/about/","kind":"custom"} /-->
<!-- wp:navigation-link {"label":"Succulents","url":"/plants/","kind":"custom"} /-->
<!-- wp:navigation-link {"label":"Chickens","url":"/chickens/","kind":"custom"} /-->
<!-- wp:navigation-link {"label":"Crochet","url":"/crochet/","kind":"custom"} /-->
<!-- wp:navigation-link {"label":"Books","url":"/books/","kind":"custom"} /-->
<!-- wp:navigation-link {"label":"Tech","url":"/tech/","kind":"custom"} /-->
<!-- wp:navigation-link {"label":"Journal","url":"/journal/","kind":"custom"} /-->
<!-- /wp:navigation --></div>
<!-- /wp:group -->
