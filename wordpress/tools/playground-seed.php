<?php
/**
 * Seeds a local WordPress Playground with the draft pages and the two live posts,
 * for checking the theme before anything reaches staging. Never deployed.
 *
 * Expects these mounts (see playground.sh):
 *   /ja/content   wordpress/content/          page markup with {{id:}} / {{url:}} tokens
 *   /ja/images    the static site's images/   source for those tokens
 *   /ja/seed      posts.json and covers/      fetched from Supabase by playground.sh
 */
require '/wordpress/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

wp_set_current_user( 1 );
update_option( 'permalink_structure', '/%postname%/' );
update_option( 'timezone_string', 'America/Los_Angeles' );
update_option( 'blogname', 'just amanda' );
update_option( 'blogdescription', 'a creative life, thoughtfully collected' );

// The install's samples would sit in the journal and the page list.
foreach ( array( 'hello-world' => 'post', 'sample-page' => 'page' ) as $sample => $type ) {
	$p = get_page_by_path( $sample, OBJECT, $type );
	if ( $p ) {
		wp_delete_post( $p->ID, true );
	}
}

/** Import a file once, keyed by its source path, and return the attachment ID. */
function ja_import( $path, $key ) {
	$found = get_posts( array( 'post_type' => 'attachment', 'meta_key' => '_ja_src', 'meta_value' => $key, 'fields' => 'ids', 'post_status' => 'any' ) );
	if ( $found ) {
		return $found[0];
	}
	$tmp = wp_tempnam( basename( $path ) );
	copy( $path, $tmp );
	$id = media_handle_sideload( array( 'name' => basename( $path ), 'tmp_name' => $tmp ), 0 );
	if ( is_wp_error( $id ) ) {
		fwrite( STDERR, "import failed: $key: " . $id->get_error_message() . "\n" );
		return 0;
	}
	update_post_meta( $id, '_ja_src', $key );
	return $id;
}

foreach ( json_decode( file_get_contents( '/ja/seed/posts.json' ), true ) as $p ) {
	$existing = get_page_by_path( $p['slug'], OBJECT, 'post' );
	$id       = wp_insert_post(
		array(
			'ID'           => $existing ? $existing->ID : 0,
			'post_type'    => 'post',
			'post_status'  => 'publish',
			'post_title'   => $p['title'],
			'post_name'    => $p['slug'],
			'post_excerpt' => $p['excerpt'],
			'post_content' => wp_slash( $p['body_html'] ),
			'post_date_gmt' => gmdate( 'Y-m-d H:i:s', strtotime( $p['published_at'] ) ),
			'post_date'    => get_date_from_gmt( gmdate( 'Y-m-d H:i:s', strtotime( $p['published_at'] ) ) ),
			'tags_input'   => $p['tags'],
		)
	);
	$cover = glob( '/ja/seed/covers/' . $p['slug'] . '.*' );
	if ( $cover ) {
		$att = ja_import( $cover[0], 'cover:' . $p['slug'] );
		set_post_thumbnail( $id, $att );
		update_post_meta( $att, '_wp_attachment_image_alt', $p['cover_alt'] );
	}
	echo "post {$p['slug']} => $id\n";
}
$pages = array(
	// slug => array( title, file, template )
	'home'      => array( 'Home', 'home.html', '' ),
	'about'     => array( 'About', 'about.html', '' ),
	'plants'    => array( 'Succulents', 'plants.html', '' ),
	'inventory' => array( 'Inventory', 'inventory.html', '' ),
	'journal'   => array( 'Journal', 'journal.html', '' ),
	'links'     => array( 'Links', 'links.html', 'page-links' ),
	'chickens'  => array( 'Chickens', 'chickens.html', 'coming-soon' ),
	'crochet'   => array( 'Crochet', 'crochet.html', 'coming-soon' ),
	'books'     => array( 'Books', 'books.html', 'coming-soon' ),
	'tech'      => array( 'Tech', 'tech.html', 'coming-soon' ),
);
foreach ( $pages as $slug => list( $title, $file, $template ) ) {
	$src = "/ja/content/$file";
	if ( ! file_exists( $src ) ) {
		continue;
	}
	$markup = file_get_contents( $src );
	$markup = preg_replace_callback(
		'/\{\{(id|url|tag):([^}]+)\}\}/',
		function ( $m ) {
			if ( 'tag' === $m[1] ) {
				// A tag the Query Loop filters on. Created when no post carries it yet.
				$term = get_term_by( 'slug', $m[2], 'post_tag' );
				$term = $term ? $term->term_id : wp_insert_term( $m[2], 'post_tag' )['term_id'];
				return (string) $term;
			}
			$id = ja_import( '/ja/images/' . $m[2], $m[2] );
			return 'id' === $m[1] ? (string) $id : wp_get_attachment_url( $id );
		},
		$markup
	);
	$existing = get_page_by_path( $slug );
	$id       = wp_insert_post(
		array(
			'ID'           => $existing ? $existing->ID : 0,
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => $title,
			'post_name'    => $slug,
			'post_content' => wp_slash( $markup ),
			'page_template' => $template,
		)
	);
	echo "page $slug => $id\n";
	if ( 'home' === $slug ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $id );
	}
}

flush_rewrite_rules();
