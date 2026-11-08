<?php
/**
 * Keep the Two Factor plugin's code screens usable on GoDaddy Managed WordPress.
 *
 * GoDaddy's system plugin (gd-system-plugin, "wpaas" SSO) adds the body class
 * wpaas-show-sso-login to every wp-login.php screen, and its sso-login.css hides
 * `#loginform > p` so its "Log in with GoDaddy" button can stand in for the
 * username and password fields. On the screens Two Factor draws inside that same
 * #loginform (the code prompt after the password, "revalidate" before changing
 * two-factor settings, recovery codes), GoDaddy adds no button, so the page shows
 * an empty box and the code cannot be typed. Found 2026-11-08 when revalidation
 * failed; the code prompt at sign-in is built the same way, which would have been
 * a lockout at the next login.
 *
 * Two layers, because the sign-in prompt is posted to wp-login.php without a
 * _2fa action: drop the class on Two Factor's own actions, and un-hide the
 * paragraphs of any login form that carries Two Factor's wp-auth-id field.
 * The normal password screen and GoDaddy's button there are untouched.
 *
 * @package justamanda
 */

add_filter(
	'login_body_class',
	function ( $classes ) {
		$action = isset( $_REQUEST['action'] ) ? sanitize_key( wp_unslash( $_REQUEST['action'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
		if ( '_2fa' === substr( $action, -4 ) || isset( $_POST['wp-auth-id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			$classes = array_values( array_diff( $classes, array( 'wpaas-show-sso-login' ) ) );
		}
		return $classes;
	},
	999
);

add_action(
	'login_enqueue_scripts',
	function () {
		// Outranks GoDaddy's `.wpaas-show-sso-login #loginform > p` (1,1,1) at (1,2,2).
		wp_register_style( 'justamanda-login', false, array(), wp_get_theme()->get( 'Version' ) );
		wp_enqueue_style( 'justamanda-login' );
		wp_add_inline_style( 'justamanda-login', '.wpaas-show-sso-login #loginform:has(input[name="wp-auth-id"]) > p{display:block;}' );
	},
	999
);
