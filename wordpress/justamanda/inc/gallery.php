<?php
/**
 * A gallery set to "Enlarge on click" enlarges every photo in it.
 *
 * Core stores that choice twice: as the gallery's linkTo, and as a lightbox flag on
 * each image inside. The editor copies it to the images only when the setting is
 * changed, so photos added afterwards, galleries built in code, and galleries a
 * Lightroom sync plugin writes can end up with images that do not enlarge (seen
 * 2026-11-08 on a test gallery). This reads the gallery's choice at render time
 * and applies it to any image that has not been given its own.
 *
 * @package justamanda
 */

add_filter(
	'render_block_data',
	function ( $block ) {
		if ( 'core/gallery' !== ( $block['blockName'] ?? '' ) || 'lightbox' !== ( $block['attrs']['linkTo'] ?? '' ) ) {
			return $block;
		}
		foreach ( $block['innerBlocks'] as $i => $inner ) {
			if ( 'core/image' === ( $inner['blockName'] ?? '' ) && ! isset( $inner['attrs']['lightbox'] ) ) {
				$block['innerBlocks'][ $i ]['attrs']['lightbox'] = array( 'enabled' => true );
			}
		}
		return $block;
	}
);
