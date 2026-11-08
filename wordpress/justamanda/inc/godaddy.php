<?php
/**
 * Opt this theme out of GoDaddy's "WP Site Designer" palette layer.
 *
 * GoDaddy Managed WordPress bundles a must-use add-on
 * (mu-plugins/vendor/gdcorp-wordpress/wp-site-designer-mu-plugins) whose
 * Palette_Switcher serves GoDaddy's own site builder. On the front end it:
 *
 *  - prints <style id="wp-site-designer-contrast-fallback"> (about 70 KB) at wp_head 99,
 *    written against Twenty Twenty-Five's `base` / `contrast` colour slugs. This theme
 *    has neither, so its rules resolve to nothing and still win on specificity:
 *    `body .wp-site-blocks input:not(...)` wiped the border and background off the
 *    inventory search box and the comment form fields (found 2026-11-08);
 *  - rewrites group and column blocks at render_block to add text colours of its own.
 *
 * Every colour here comes from theme.json and site.css, so both hooks are removed.
 * Its palette swap (wp_theme_json_data_user) is left alone: it does nothing unless a
 * GoDaddy palette is picked, and the editor UI that picks one is GoDaddy's to keep.
 * Unhooking is a no-op if GoDaddy renames or drops the class.
 *
 * @package justamanda
 */

add_action(
	'init',
	function () {
		$class = 'GoDaddy\\WordPress\\Plugins\\SiteDesigner\\Dependencies\\WPExtensions\\Palette_Switcher';
		if ( ! class_exists( $class, false ) ) {
			return;
		}
		remove_action( 'wp_head', array( $class, 'print_contrast_fallback_css' ), 99 );
		remove_filter( 'render_block', array( $class, 'inject_inline_bg_contrast' ), 10 );
	},
	20
);
