<?php
/**
 * Share cards, icons and analytics, so no SEO plugin is needed for what the static
 * site did by hand. A page's meta description is its Excerpt field.
 *
 * @package justamanda
 */

/**
 * The featured image as the file that was uploaded, not the AVIF/WebP that
 * inc/setup.php has WordPress write for the page. Facebook, LinkedIn and most
 * link-preview crawlers do not read AVIF, so a share card needs the PNG/JPEG,
 * which WordPress keeps alongside as the attachment's original_image.
 */
function justamanda_share_image_url() {
	$id = get_post_thumbnail_id();
	if ( ! $id ) {
		return '';
	}
	return (string) ( wp_get_original_image_url( $id ) ?: wp_get_attachment_url( $id ) );
}

add_action(
	'wp_head',
	function () {
		$title       = wp_get_document_title();
		$description = is_singular() && has_excerpt() ? get_the_excerpt() : get_bloginfo( 'description' );
		$url         = is_singular() ? get_permalink() : home_url( add_query_arg( array() ) );
		$image       = is_singular() ? justamanda_share_image_url() : '';

		if ( $description ) {
			printf( '<meta name="description" content="%s">' . "\n", esc_attr( $description ) );
			printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $description ) );
		}
		printf( '<meta property="og:type" content="%s">' . "\n", is_singular( 'post' ) ? 'article' : 'website' );
		printf( '<meta property="og:site_name" content="%s">' . "\n", esc_attr( get_bloginfo( 'name' ) ) );
		printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $title ) );
		printf( '<meta property="og:url" content="%s">' . "\n", esc_url( $url ) );
		if ( $image ) {
			printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $image ) );
		}
		echo '<meta name="twitter:card" content="summary_large_image">' . "\n";

		$theme_color = wp_get_global_settings( array( 'color', 'palette', 'theme' ) );
		$ivory       = wp_list_filter( (array) $theme_color, array( 'slug' => 'ivory' ) );
		if ( $ivory ) {
			printf( '<meta name="theme-color" content="%s">' . "\n", esc_attr( reset( $ivory )['color'] ) );
		}
		if ( ! has_site_icon() ) {
			printf( '<link rel="icon" type="image/png" href="%s">' . "\n", esc_url( get_theme_file_uri( 'assets/images/favicon.png' ) ) );
			printf( '<link rel="apple-touch-icon" href="%s">' . "\n", esc_url( get_theme_file_uri( 'assets/images/apple-touch-icon.png' ) ) );
		}
	},
	1
);

// BlogPosting data on a post, field for field what the static site's generator wrote
// (tools/build-journal.mjs), so search results keep the same rich details.
add_action(
	'wp_head',
	function () {
		if ( ! is_singular( 'post' ) ) {
			return;
		}
		$about = get_page_by_path( 'about' );
		$data  = array(
			'@context'      => 'https://schema.org',
			'@type'         => 'BlogPosting',
			'headline'      => get_the_title(),
			'description'   => has_excerpt() ? get_the_excerpt() : '',
			'image'         => justamanda_share_image_url(),
			'url'           => get_permalink(),
			'datePublished' => get_the_date( 'c' ),
			'dateModified'  => get_the_modified_date( 'c' ),
			'author'        => array(
				'@type' => 'Person',
				'name'  => get_the_author_meta( 'display_name', (int) get_post_field( 'post_author' ) ),
				'url'   => $about ? get_permalink( $about ) : home_url( '/' ),
			),
			'keywords'      => implode( ', ', wp_list_pluck( (array) get_the_tags(), 'name' ) ),
		);
		printf( '<script type="application/ld+json">%s</script>' . "\n", wp_json_encode( array_filter( $data ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) );
	},
	2
);

// Counted only on the real site and never for a signed-in visit, so staging cannot pollute the numbers.
add_action(
	'wp_head',
	function () {
		if ( is_user_logged_in() || 'justamanda.net' !== wp_parse_url( home_url(), PHP_URL_HOST ) ) {
			return;
		}
		?>
<script data-goatcounter="https://justamanda.goatcounter.com/count" async src="https://gc.zgo.at/count.js"></script>
<script async src="https://www.googletagmanager.com/gtag/js?id=G-00ZH9LDQ5B"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', 'G-00ZH9LDQ5B');
</script>
		<?php
	}
);

// "About · just amanda", as the static site titled its pages.
add_filter(
	'document_title_separator',
	function () {
		return '·';
	}
);
