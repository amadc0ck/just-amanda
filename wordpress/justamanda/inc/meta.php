<?php
/**
 * Share cards, icons and analytics, so no SEO plugin is needed for what the static
 * site did by hand. A page's meta description is its Excerpt field.
 *
 * @package justamanda
 */

add_action(
	'wp_head',
	function () {
		$title       = wp_get_document_title();
		$description = is_singular() && has_excerpt() ? get_the_excerpt() : get_bloginfo( 'description' );
		$url         = is_singular() ? get_permalink() : home_url( add_query_arg( array() ) );
		$image       = is_singular() ? get_the_post_thumbnail_url( null, 'full' ) : '';

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
