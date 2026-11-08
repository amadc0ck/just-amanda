<?php
/**
 * The static site's addresses, sent to their WordPress pages with a 301, so links
 * already out in the world keep working after the domain moves (2026-11-08).
 *
 * A fixed list rather than a redirect plugin: there are only these, and they never
 * change. Runs at template_redirect priority 1, ahead of core's redirect_canonical
 * (10), whose "guess the nearest post" would otherwise send /ig somewhere random.
 *
 *   /ig  /fb  /threads   the bio links on Instagram, Facebook and Threads. The
 *                        static site sent them to /links?ref=<network> so visits
 *                        could be told apart; the ref is kept.
 *   /<page>.html         every page of the static site.
 *   /journal/<slug>/     static post pages; WordPress posts live at /<slug>/.
 *
 * @package justamanda
 */

/** Static path (no trailing slash, lower case) => WordPress path. */
function justamanda_redirect_map() {
	$map = array(
		'/ig'              => '/links/?ref=instagram',
		'/ig.html'         => '/links/?ref=instagram',
		'/fb'              => '/links/?ref=facebook',
		'/fb.html'         => '/links/?ref=facebook',
		'/threads'         => '/links/?ref=threads',
		'/threads.html'    => '/links/?ref=threads',
		'/index.html'      => '/',
		'/post.html'       => '/journal/',
		'/admin.html'      => '/wp-admin/',
		'/styleguide.html' => '/',
	);
	foreach ( array( 'about', 'plants', 'inventory', 'journal', 'links', 'chickens', 'crochet', 'books', 'tech' ) as $page ) {
		$map[ '/' . $page . '.html' ] = '/' . $page . '/';
	}
	return $map;
}

add_action(
	'template_redirect',
	function () {
		$path = strtolower( (string) wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ) );
		$path = '/' === $path ? $path : untrailingslashit( $path );
		$map  = justamanda_redirect_map();

		if ( isset( $map[ $path ] ) ) {
			$to = $map[ $path ];
		} elseif ( preg_match( '#^/journal/([a-z0-9-]+)$#', $path, $m ) && get_page_by_path( $m[1], OBJECT, 'post' ) ) {
			$to = '/' . $m[1] . '/';
		} else {
			return;
		}
		wp_safe_redirect( home_url( $to ), 301, 'justamanda' );
		exit;
	},
	1
);
