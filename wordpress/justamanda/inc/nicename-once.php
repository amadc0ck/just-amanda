<?php
/**
 * ONE-OFF, Phase 1 staging setup. Delete this file and its require once it has run.
 *
 * Author URLs use user_nicename, which defaults to the login name, so /author/<login>/
 * would publish the login. wp-admin has no field for it and WP-CLI is unavailable on
 * this host, so the theme sets it once. The login is deliberately not named here:
 * this repo is public. It acts only when there is exactly one administrator and that
 * account's nicename is still its login.
 *
 * @package justamanda
 */

add_action(
	'init',
	function () {
		if ( get_option( 'justamanda_nicename_done' ) ) {
			return;
		}
		$admins = get_users( array( 'role' => 'administrator' ) );
		if ( 1 === count( $admins ) && $admins[0]->user_nicename === sanitize_title( $admins[0]->user_login ) ) {
			wp_update_user( array( 'ID' => $admins[0]->ID, 'user_nicename' => 'amanda' ) );
		}
		update_option( 'justamanda_nicename_done', 1, false );
	}
);
