<?php
/**
 * Preview helpers for the HOME and PRODUCT modules (front-page.php, template-product.php) · not part of the theme
 * (WP Pusher deploys eabull/ only). Loaded by dev/preview/wp-stubs.php before functions.php.
 *
 * Try other Customizer values without WordPress by adding hm_mod[...] to the preview URL, e.g.
 *   /?hm_mod[line_url]=https://lin.ee/example                 (LINE buttons go to LINE instead of /go/)
 *   /?hm_mod[product2_version]=0.5                             (product order follows the version number)
 *   /product-1/?hm_mod[product1_stat1_value]=XAUUSD&hm_mod[product1_example]=0
 *   /?hm_mod[show_home_modes]=0&hm_mod[home_mode1_text]=ข้อความ
 * Only active when hm_mod is present in the query string. The WP_Query stub has no posts, so the
 * "latest articles" section stays hidden in the preview (it never prints placeholder posts).
 */

if ( ! empty( $_GET['hm_mod'] ) && is_array( $_GET['hm_mod'] ) && function_exists( 'add_filter' ) ) { // phpcs:ignore WordPress.Security.NonceVerification
	add_filter(
		'eab_defaults',
		function ( $d ) {
			foreach ( (array) $_GET['hm_mod'] as $fx_key => $fx_value ) { // phpcs:ignore WordPress.Security.NonceVerification
				$fx_key = preg_replace( '/[^a-z0-9_]/', '', strtolower( (string) $fx_key ) );
				if ( '' !== $fx_key && is_string( $fx_value ) ) {
					$d[ $fx_key ] = $fx_value;
				}
			}
			return $d;
		},
		99
	);
}
