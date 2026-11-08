<?php
/**
 * Tabbed sections (2026-11-08). Any group with the class "is-tabbed" turns the
 * story rows inside it into tabs (assets/js/tabs.js); the script loads only on a
 * page that has one. The Books eras use it. In the editor the rows stay ordinary
 * blocks, and without JavaScript they show one after another.
 *
 * @package justamanda
 */

add_action(
	'init',
	function () {
		wp_register_script( 'justamanda-tabs', get_theme_file_uri( 'assets/js/tabs.js' ), array(), wp_get_theme()->get( 'Version' ), array( 'strategy' => 'defer', 'in_footer' => true ) );
	}
);

add_filter(
	'render_block_core/group',
	function ( $html, $block ) {
		if ( false !== strpos( ' ' . ( $block['attrs']['className'] ?? '' ) . ' ', ' is-tabbed ' ) ) {
			wp_enqueue_script( 'justamanda-tabs' );
		}
		return $html;
	},
	10,
	2
);
