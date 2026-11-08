<?php
/**
 * Theme supports, assets, block styles and image handling.
 *
 * @package justamanda
 */

add_action(
	'after_setup_theme',
	function () {
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'editor-styles' );
		add_editor_style( array( 'assets/css/site.css', 'assets/css/editor.css' ) );
		add_post_type_support( 'page', 'excerpt' );

		// Named for the jobs images actually do here. No crop: the CSS crops,
		// with object-fit, so an author's picture is never cut twice.
		add_image_size( 'ja-card', 400, 400 );
		add_image_size( 'ja-column', 900, 900 );
		add_image_size( 'ja-wide', 1600, 1600 );
	}
);

add_action(
	'wp_enqueue_scripts',
	function () {
		$version = wp_get_theme()->get( 'Version' );
		wp_enqueue_style( 'justamanda', get_theme_file_uri( 'assets/css/site.css' ), array( 'wp-block-library' ), $version );
		wp_enqueue_script( 'justamanda-carousel', get_theme_file_uri( 'assets/js/carousel.js' ), array(), $version, array( 'strategy' => 'defer' ) );
		if ( is_page_template( 'page-links' ) ) {
			wp_enqueue_script( 'justamanda-links', get_theme_file_uri( 'assets/js/links.js' ), array(), $version, array( 'strategy' => 'defer' ) );
		}
		if ( is_singular( 'post' ) ) {
			wp_enqueue_script( 'justamanda-share', get_theme_file_uri( 'assets/js/share.js' ), array(), $version, array( 'strategy' => 'defer' ) );
		}
	}
);

add_action(
	'init',
	function () {
		register_block_type( get_theme_file_path( 'blocks/progress' ) );
		register_block_type( get_theme_file_path( 'blocks/share' ) );
		register_block_type( get_theme_file_path( 'blocks/plant-inventory' ) );

		foreach ( array(
			'primary' => __( 'Solid', 'justamanda' ),
			'ghost'   => __( 'Outline', 'justamanda' ),
			'read'    => __( 'Outline, journal', 'justamanda' ),
		) as $name => $label ) {
			register_block_style( 'core/button', array( 'name' => $name, 'label' => $label ) );
		}

		foreach ( array(
			'divider-default'   => __( 'Divider', 'justamanda' ),
			'divider-amethyst'  => __( 'Divider, amethyst', 'justamanda' ),
			'divider-book'      => __( 'Divider, book', 'justamanda' ),
			'divider-slim'      => __( 'Divider, slim', 'justamanda' ),
			'divider-succulent' => __( 'Divider, succulent', 'justamanda' ),
		) as $name => $label ) {
			register_block_style( 'core/separator', array( 'name' => $name, 'label' => $label ) );
		}

		register_block_pattern_category( 'justamanda', array( 'label' => __( 'just amanda', 'justamanda' ) ) );

		// One pattern per divider, so each is findable by name in the inserter. The
		// artwork is a background on the Separator's style, so a page stores no theme URL.
		foreach ( array(
			'default'   => __( 'Divider', 'justamanda' ),
			'amethyst'  => __( 'Divider, amethyst', 'justamanda' ),
			'book'      => __( 'Divider, book', 'justamanda' ),
			'slim'      => __( 'Divider, slim', 'justamanda' ),
			'succulent' => __( 'Divider, succulent', 'justamanda' ),
		) as $name => $label ) {
			register_block_pattern(
				'justamanda/divider-' . $name,
				array(
					'title'      => $label,
					'categories' => array( 'justamanda' ),
					'content'    => '<!-- wp:separator {"className":"is-style-divider-' . $name . '"} -->' . "\n"
						. '<hr class="wp-block-separator has-alpha-channel-opacity is-style-divider-' . $name . '"/>' . "\n"
						. '<!-- /wp:separator -->',
				)
			);
		}
	}
);

// Links page: its own ground, set on the body because it runs the full window.
add_filter(
	'body_class',
	function ( $classes ) {
		if ( is_page_template( 'page-links' ) ) {
			$classes[] = 'lt-page';
		}
		return $classes;
	}
);

// A front page template is only for a chosen static page. Without one, fall through to index.
add_filter(
	'frontpage_template_hierarchy',
	function ( $templates ) {
		return 'page' === get_option( 'show_on_front' ) ? $templates : array();
	}
);

// Images: keep originals sensible, serve modern formats. AVIF only where the server can write it.
add_filter(
	'big_image_size_threshold',
	function () {
		return 2560;
	}
);

/**
 * Can this server really write a given format? wp_image_editor_supports() only
 * asks the library, and a library can say yes and then write nothing: on 2026-11-03
 * WordPress Playground's GD reported AVIF support and produced 0-byte .avif files
 * for every upload, so every image on the page was broken. So encode a small real
 * image, read it back, and believe only that. Cached for a day per format.
 */
function justamanda_can_write( $mime ) {
	$key    = 'justamanda_can_write_' . str_replace( '/', '_', $mime );
	$cached = get_transient( $key );
	if ( false !== $cached ) {
		return 'yes' === $cached;
	}
	$ok = false;
	if ( wp_image_editor_supports( array( 'mime_type' => $mime ) ) ) {
		// Full size, not a thumbnail: Playground wrote good AVIFs at 300 and 400px and
		// empty ones from 768px up. divider.png is 1400x466, larger than that limit.
		$editor = wp_get_image_editor( get_theme_file_path( 'assets/images/divider.png' ) );
		if ( ! is_wp_error( $editor ) ) {
			$saved = $editor->save( get_temp_dir() . 'justamanda-probe-' . wp_generate_password( 8, false ), $mime );
			if ( ! is_wp_error( $saved ) && ! empty( $saved['path'] ) && file_exists( $saved['path'] ) ) {
				$info = filesize( $saved['path'] ) > 0 ? wp_getimagesize( $saved['path'] ) : false;
				$ok   = $info && $mime === $info['mime'];
				wp_delete_file( $saved['path'] );
			}
		}
	}
	set_transient( $key, $ok ? 'yes' : 'no', $ok ? DAY_IN_SECONDS : 30 * DAY_IN_SECONDS );
	return $ok;
}

add_filter(
	'image_editor_output_format',
	function ( $formats ) {
		static $target = null;
		if ( get_transient( 'justamanda_avif_failed' ) && 'image/avif' === $target ) {
			$target = null;
		}
		if ( null === $target ) {
			$target = '';
			// Set first so the probe's own save, which runs through this filter, is not converted.
			$avif   = ! get_transient( 'justamanda_avif_failed' ) && justamanda_can_write( 'image/avif' );
			$target = $avif ? 'image/avif' : ( justamanda_can_write( 'image/webp' ) ? 'image/webp' : '' );
		}
		if ( $target ) {
			$formats['image/jpeg'] = $target;
			$formats['image/png']  = $target;
		}
		return $formats;
	}
);

/*
 * Safety net for a probe that passes and a real photo that still fails (a 2560px
 * original needs far more memory than the probe). After WordPress writes an upload's
 * files, any empty one means this server cannot be trusted with AVIF: stop using it
 * for 30 days and rebuild this attachment as WebP from the original, which WordPress
 * keeps beside a converted upload.
 */
add_filter(
	'wp_generate_attachment_metadata',
	function ( $metadata, $attachment_id ) {
		static $rebuilding = false;
		if ( $rebuilding || empty( $metadata['file'] ) ) {
			return $metadata;
		}
		$dir   = trailingslashit( dirname( get_attached_file( $attachment_id ) ) );
		$files = array( basename( $metadata['file'] ) );
		foreach ( (array) ( $metadata['sizes'] ?? array() ) as $size ) {
			$files[] = $size['file'];
		}
		$empty = array_filter(
			$files,
			function ( $f ) use ( $dir ) {
				return file_exists( $dir . $f ) && 0 === filesize( $dir . $f );
			}
		);
		$original = wp_get_original_image_path( $attachment_id );
		if ( ! $empty || ! $original || ! file_exists( $original ) ) {
			return $metadata;
		}
		error_log( 'justamanda: empty image output for attachment ' . $attachment_id . ', switching uploads to WebP' );
		set_transient( 'justamanda_avif_failed', 1, 30 * DAY_IN_SECONDS );
		foreach ( $files as $f ) {
			wp_delete_file( $dir . $f );
		}
		$rebuilding = true;
		$rebuilt    = wp_create_image_subsizes( $original, $attachment_id );
		$rebuilding = false;
		return is_array( $rebuilt ) ? $rebuilt : $metadata;
	},
	10,
	2
);
