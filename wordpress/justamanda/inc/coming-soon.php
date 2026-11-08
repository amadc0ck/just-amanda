<?php
/**
 * Pages not open yet.
 *
 * A page is "coming soon" when its Coming soon box is ticked (Page settings in the
 * editor; the meta key below), or, for pages set up before 2026-11-08, when it is
 * on the old "Coming soon" template. A coming-soon page shows readers only its
 * .soon-panel group: the rest is never sent, which is stronger than the static
 * site's curtain, where the words stayed in the source. Signed-in users see the
 * whole page under a banner. Links to it are marked for everyone and go quiet (no
 * destination) for readers only. Pages nested under it (albums under Photography)
 * are curtained with it.
 *
 * Releasing a page = untick the box and Update. Its panel stays in the page, hidden,
 * so the box can be ticked again later.
 *
 * @package justamanda
 */

const JUSTAMANDA_SOON_META = '_justamanda_coming_soon';

/** True when this visitor should get the curtain rather than the page. */
function justamanda_is_reader() {
	return ! is_user_logged_in();
}

// The box's storage: a page meta flag the editor can read and write.
add_action(
	'init',
	function () {
		register_post_meta(
			'page',
			JUSTAMANDA_SOON_META,
			array(
				'type'          => 'boolean',
				'single'        => true,
				'default'       => false,
				'show_in_rest'  => true,
				'auth_callback' => function ( $allowed, $meta_key, $post_id ) {
					return current_user_can( 'edit_post', $post_id );
				},
			)
		);
	}
);

// The box itself, in the page settings sidebar.
add_action(
	'enqueue_block_editor_assets',
	function () {
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
		if ( $screen && 'page' !== $screen->post_type ) {
			return;
		}
		wp_enqueue_script(
			'justamanda-soon-panel',
			get_theme_file_uri( 'assets/js/editor-soon.js' ),
			array( 'wp-plugins', 'wp-editor', 'wp-components', 'wp-data', 'wp-core-data', 'wp-element', 'wp-i18n' ),
			wp_get_theme()->get( 'Version' ),
			true
		);
	}
);

/** Is this page itself marked coming soon (box ticked, or the old template)? */
function justamanda_page_flagged_soon( $id ) {
	return (bool) get_post_meta( $id, JUSTAMANDA_SOON_META, true ) || 'coming-soon' === get_page_template_slug( $id );
}

/**
 * Is this page unopened: marked itself, or nested under a marked page?
 *
 * @param WP_Post|int|null $page Page, ID, or null for the current one.
 */
function justamanda_page_is_soon( $page = null ) {
	$page = get_post( $page );
	if ( ! $page || 'page' !== $page->post_type ) {
		return false;
	}
	foreach ( array_merge( array( $page->ID ), get_post_ancestors( $page ) ) as $id ) {
		if ( justamanda_page_flagged_soon( $id ) ) {
			return true;
		}
	}
	return false;
}

/** Is this site-relative or absolute URL a coming-soon page on this site? */
function justamanda_url_is_soon( $url ) {
	static $seen = array();
	if ( isset( $seen[ $url ] ) ) {
		return $seen[ $url ];
	}
	$path = wp_parse_url( $url, PHP_URL_PATH );
	$host = wp_parse_url( $url, PHP_URL_HOST );
	$soon = false;
	if ( $path && trim( $path, '/' ) && ( ! $host || wp_parse_url( home_url(), PHP_URL_HOST ) === $host ) ) {
		$page = get_page_by_path( trim( $path, '/' ) );
		$soon = $page && justamanda_page_is_soon( $page );
	}
	$seen[ $url ] = $soon;
	return $soon;
}

/** The page's top-level blocks, kept or dropped by whether they are the soon panel. */
function justamanda_filter_soon_panel( $content, $keep_panel ) {
	$blocks = array_filter(
		parse_blocks( $content ),
		function ( $block ) use ( $keep_panel ) {
			$is_panel = false !== strpos( ' ' . ( $block['attrs']['className'] ?? '' ) . ' ', ' soon-panel ' );
			return $keep_panel ? $is_panel : ! $is_panel;
		}
	);
	return serialize_blocks( $blocks );
}

// Readers of a coming-soon page get the panel alone; an open page never shows its
// leftover panel to anyone. Runs before do_blocks, while the content is still blocks.
add_filter(
	'the_content',
	function ( $content ) {
		if ( ! is_page() || ! in_the_loop() || false === strpos( $content, 'soon-panel' ) ) {
			return $content;
		}
		if ( ! justamanda_page_is_soon() ) {
			return justamanda_filter_soon_panel( $content, false );
		}
		if ( justamanda_is_reader() && justamanda_page_flagged_soon( get_the_ID() ) ) {
			return justamanda_filter_soon_panel( $content, true );
		}
		return $content;
	},
	1
);

// A signed-in view says plainly that it is not what the public sees.
add_filter(
	'the_content',
	function ( $content ) {
		if ( ! is_page() || ! justamanda_page_is_soon() || justamanda_is_reader() || ! in_the_loop() ) {
			return $content;
		}
		return '<div class="preview-banner">' . esc_html__( 'Coming soon · hidden from the public, only you can see this page', 'justamanda' ) . '</div>' . $content;
	},
	20
);

add_filter(
	'body_class',
	function ( $classes ) {
		if ( is_page() && justamanda_page_is_soon() && ! justamanda_is_reader() ) {
			$classes[] = 'is-soon-preview';
		}
		return $classes;
	}
);

add_filter(
	'wp_robots',
	function ( $robots ) {
		if ( is_page() && justamanda_page_is_soon() ) {
			$robots['noindex'] = true;
			unset( $robots['max-image-preview'] );
		}
		return $robots;
	}
);

// A reader who reaches a page nested under an unopened one (an album under Photography)
// is sent to the nearest marked page above it, which shows its panel. 302: it opens later.
add_action(
	'template_redirect',
	function () {
		if ( ! is_page() || ! justamanda_is_reader() ) {
			return;
		}
		$id = get_queried_object_id();
		if ( justamanda_page_flagged_soon( $id ) || ! justamanda_page_is_soon( $id ) ) {
			return;
		}
		foreach ( get_post_ancestors( $id ) as $ancestor ) {
			if ( justamanda_page_flagged_soon( $ancestor ) ) {
				wp_safe_redirect( get_permalink( $ancestor ), 302, 'justamanda' );
				exit;
			}
		}
	},
	5
);

// Out of the core sitemap too: marked pages and the pages nested under them.
add_filter(
	'wp_sitemaps_posts_query_args',
	function ( $args, $post_type ) {
		if ( 'page' !== $post_type ) {
			return $args;
		}
		$marked = get_posts(
			array(
				'post_type'   => 'page',
				'fields'      => 'ids',
				'numberposts' => -1,
				'meta_query'  => array( // phpcs:ignore WordPress.DB.SlowDBQuery
					'relation' => 'OR',
					array( 'key' => JUSTAMANDA_SOON_META, 'value' => '1' ),
					array( 'key' => '_wp_page_template', 'value' => 'coming-soon' ),
				),
			)
		);
		if ( $marked ) {
			$args['post__not_in']        = array_merge( $args['post__not_in'] ?? array(), $marked );
			$args['post_parent__not_in'] = $marked;
		}
		return $args;
	},
	10,
	2
);

/**
 * Mark every link in a chunk of HTML as leading to an unopened page.
 *
 * Readers: the destination comes off too. A greyed link that still navigated would
 * be worse than no marking. Signed-in users ($keep_href): same marking, link kept,
 * so the owner sees the site as readers will and can still click through to the
 * page preview. Before 2026-11-08 signed-in users got no marking at all, copied from
 * the static site, but there the owner is usually signed out and here always in,
 * so the badges were never visible to the person checking them.
 */
function justamanda_silence_links( $html, $keep_href = false ) {
	$tags = new WP_HTML_Tag_Processor( $html );
	while ( $tags->next_tag( 'a' ) ) {
		if ( ! $keep_href ) {
			$tags->remove_attribute( 'href' );
			$tags->set_attribute( 'aria-disabled', 'true' );
		}
		$tags->set_attribute( 'title', __( 'Coming soon', 'justamanda' ) );
		$tags->add_class( 'is-soon' );
	}
	return $tags->get_updated_html();
}

// Navigation: mark the current page (custom links get no aria-current from core) and quiet the unopened ones.
add_filter(
	'render_block_core/navigation-link',
	function ( $html, $block ) {
		$url  = $block['attrs']['url'] ?? '';
		$tags = new WP_HTML_Tag_Processor( $html );
		if ( ! $url || ! $tags->next_tag( 'a' ) ) {
			return $html;
		}
		$request = trailingslashit( (string) wp_parse_url( $_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH ) );
		$link    = trailingslashit( (string) wp_parse_url( $url, PHP_URL_PATH ) );
		// The section's link also lights up on pages inside it (an album under /photography/).
		if ( $link === $request || ( '/' !== $link && 0 === strpos( $request, $link ) ) ) {
			$tags->set_attribute( 'aria-current', 'page' );
		}
		$html = $tags->get_updated_html();
		return justamanda_url_is_soon( $url ) ? justamanda_silence_links( $html, ! justamanda_is_reader() ) : $html;
	},
	10,
	2
);

// Cards and tiles whose link leads to an unopened page.
add_filter(
	'render_block_core/group',
	function ( $html, $block ) {
		$classes = ' ' . ( $block['attrs']['className'] ?? '' ) . ' ';
		if ( false === strpos( $classes, ' card ' ) && false === strpos( $classes, ' lt-tile ' ) ) {
			return $html;
		}
		$tags = new WP_HTML_Tag_Processor( $html );
		if ( ! $tags->next_tag( 'a' ) || ! justamanda_url_is_soon( (string) $tags->get_attribute( 'href' ) ) ) {
			return $html;
		}
		$tags = new WP_HTML_Tag_Processor( justamanda_silence_links( $html, ! justamanda_is_reader() ) );
		$tags->next_tag();
		$tags->add_class( 'is-soon' );
		return $tags->get_updated_html();
	},
	10,
	2
);
