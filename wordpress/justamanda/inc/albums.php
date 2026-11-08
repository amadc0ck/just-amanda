<?php
/**
 * Photography albums (2026-11-08). An album is a page nested under Photography, on
 * the "Album" template (templates/page-album.html). Photography lists them with a
 * Query Loop of its child pages (patterns/album-grid.php): the cover is the album's
 * featured image and the line under the title is its excerpt.
 *
 * The grid follows the page order Amanda sets (Page Attributes > Order, lowest
 * first; ties by title). Core's Query Loop only offers date or title ordering, so a
 * Query Loop that lists child pages is switched to page order here. Nothing else on
 * the site lists child pages.
 *
 * @package justamanda
 */

add_filter(
	'query_loop_block_query_vars',
	function ( $query ) {
		if ( 'page' === ( $query['post_type'] ?? '' ) && ! empty( $query['post_parent__in'] ) ) {
			$query['orderby'] = array(
				'menu_order' => 'ASC',
				'title'      => 'ASC',
			);
			unset( $query['order'] );
		}
		return $query;
	}
);
