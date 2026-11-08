<?php
/**
 * Title: Album
 * Slug: justamanda/album-page
 * Categories: justamanda
 * Keywords: album, photography, gallery, lightroom
 * Block Types: core/post-content
 * Post Types: page
 * Description: Starts a new album page: a line about the album and a gallery that enlarges each photo on click. Nest the page under Photography, pick the Album template, set a featured image (the cover) and an excerpt (the date and place line).
 *
 * @package justamanda
 */
?>
<!-- wp:paragraph {"className":"album-note"} -->
<p class="album-note">A line about where and when these were taken.</p>
<!-- /wp:paragraph -->

<!-- wp:gallery {"linkTo":"lightbox","sizeSlug":"large"} -->
<figure class="wp-block-gallery has-nested-images columns-default is-cropped"></figure>
<!-- /wp:gallery -->
