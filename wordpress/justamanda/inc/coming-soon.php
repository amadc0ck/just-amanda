<?php
/**
 * Pages not open yet. A page on the "Coming soon" template shows readers only its
 * .soon-panel group: the rest is never sent, which is stronger than the static
 * site's curtain, where the words stayed in the source. Signed-in users see the
 * whole page under a banner. Links to these pages are marked for everyone and go
 * quiet (no destination) for readers only.
 *
 * Opening a page means switching it to the default template. Nothing else.
 *
 * @package justamanda
 */

/** True when this visitor should get the curtain rather than the page. */
function justamanda_is_reader() {
	return ! is_user_logged_in();
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
		$soon = $page && 'coming-soon' === get_page_template_slug( $page );
	}
	$seen[ $url ] = $soon;
	return $soon;
}

// Readers get the panel alone. Runs before do_blocks, while the content is still block markup.
add_filter(
	'the_content',
	function ( $content ) {
		if ( ! is_page_template( 'coming-soon' ) || ! justamanda_is_reader() || ! in_the_loop() ) {
			return $content;
		}
		$panel = array_filter(
			parse_blocks( $content ),
			function ( $block ) {
				return false !== strpos( ' ' . ( $block['attrs']['className'] ?? '' ) . ' ', ' soon-panel ' );
			}
		);
		return serialize_blocks( $panel );
	},
	1
);

// A signed-in view says plainly that it is not what the public sees.
add_filter(
	'the_content',
	function ( $content ) {
		if ( ! is_page_template( 'coming-soon' ) || justamanda_is_reader() || ! in_the_loop() ) {
			return $content;
		}
		return '<div class="preview-banner">' . esc_html__( 'Coming soon · hidden from the public, only you can see this page', 'justamanda' ) . '</div>' . $content;
	},
	20
);

add_filter(
	'body_class',
	function ( $classes ) {
		if ( is_page_template( 'coming-soon' ) && ! justamanda_is_reader() ) {
			$classes[] = 'is-soon-preview';
		}
		return $classes;
	}
);

add_filter(
	'wp_robots',
	function ( $robots ) {
		if ( is_page_template( 'coming-soon' ) ) {
			$robots['noindex'] = true;
			unset( $robots['max-image-preview'] );
		}
		return $robots;
	}
);

// Out of the core sitemap too.
add_filter(
	'wp_sitemaps_posts_query_args',
	function ( $args, $post_type ) {
		if ( 'page' === $post_type ) {
			$args['meta_query'] = array(
				'relation' => 'OR',
				array( 'key' => '_wp_page_template', 'compare' => 'NOT EXISTS' ),
				array( 'key' => '_wp_page_template', 'value' => 'coming-soon', 'compare' => '!=' ),
			);
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
		$request = wp_parse_url( $_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH );
		if ( trailingslashit( (string) wp_parse_url( $url, PHP_URL_PATH ) ) === trailingslashit( (string) $request ) ) {
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
