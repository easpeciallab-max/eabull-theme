<?php
/**
 * Preview stubs for the consent module (inc/modules/consent.php) · dev only, not deployed.
 *
 * Simulate settings in the preview with a query string:
 *   ?fx_consent=ga        → valid GA4 ID (analytics row, accept / reject / settings buttons)
 *   ?fx_consent=pixel     → valid Meta Pixel ID (marketing row)
 *   ?fx_consent=ga,pixel  → both
 *   ?fx_consent=off       → show_cookie_consent off, no IDs (card only opens from "ตั้งค่าคุกกี้")
 *   ?fx_consent=bad       → malformed IDs (must be ignored: "necessary only" card, no tags)
 * Default (no switch) = the live state: no IDs, card shows the "necessary only" text with one OK button.
 */

if ( ! function_exists( 'wp_script_is' ) ) {
	function wp_script_is( $handle, $list = 'enqueued' ) {
		return isset( $GLOBALS['fx_scripts'][ $handle ] );
	}
}

if ( ! function_exists( 'wp_print_inline_script_tag' ) ) {
	function wp_print_inline_script_tag( $js, $attributes = array() ) {
		echo '<script>' . $js . "</script>\n"; // phpcs:ignore WordPress.Security.EscapeOutput
	}
}

if ( isset( $_GET['fx_consent'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
	$GLOBALS['fx_consent_sim'] = preg_match_all( '/ga|pixel|off|bad/', (string) $_GET['fx_consent'], $fx_m ) ? $fx_m[0] : array(); // phpcs:ignore
	add_filter(
		'eab_defaults',
		function ( $d ) {
			$sim = $GLOBALS['fx_consent_sim'];
			if ( in_array( 'ga', $sim, true ) ) {
				$d['ga_measurement_id'] = 'G-EABULL0TEST';
			}
			if ( in_array( 'pixel', $sim, true ) ) {
				$d['fb_pixel_id'] = '123456789012345';
			}
			if ( in_array( 'off', $sim, true ) ) {
				$d['show_cookie_consent'] = false;
			}
			if ( in_array( 'bad', $sim, true ) ) {
				$d['ga_measurement_id'] = 'UA-12345-1';
				$d['fb_pixel_id']       = 'abc123';
			}
			return $d;
		},
		99
	);
}
