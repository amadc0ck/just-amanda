<?php
/**
 * Title: Photo album
 * Slug: justamanda/photo-album
 * Categories: justamanda
 * Keywords: gallery, photos, lightroom, album
 * Description: A titled album: a heading, a line about it, and a gallery that enlarges each photo on click. Add the photos from the Media Library.
 *
 * @package justamanda
 */
?>
<!-- wp:group {"className":"photo-album","layout":{"type":"default"}} -->
<div class="wp-block-group photo-album"><!-- wp:heading -->
<h2 class="wp-block-heading">Album title</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>A line about where and when these were taken.</p>
<!-- /wp:paragraph -->

<!-- wp:gallery {"linkTo":"lightbox"} -->
<figure class="wp-block-gallery has-nested-images columns-default is-cropped"></figure>
<!-- /wp:gallery --></div>
<!-- /wp:group -->
