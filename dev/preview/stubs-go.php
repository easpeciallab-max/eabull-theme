<?php
/**
 * Preview stubs for the /go page module (eabull/inc/modules/go.php).
 * Loaded by dev/preview/wp-stubs.php. Not part of the theme.
 *
 * Preview-only switches (query string, e.g. /go/?godemo=1):
 *   godemo=1     sample LINE OA and broker portal links (example URLs) so the LINE buttons and the
 *                portal button get a real href instead of the toast
 *   gohide=2,4   blank the titles of those steps: they drop out and the rest renumber
 *   gopending=1  clear the info buttons and the VPS links: they answer with the "page not ready" toast
 */

if ( ! function_exists( 'wp_parse_url' ) ) {
	function wp_parse_url( $url, $component = -1 ) {
		return parse_url( (string) $url, $component );
	}
}

// phpcs:disable WordPress.Security.NonceVerification, WordPress.Security.ValidatedSanitizedInput -- local preview only
if ( isset( $_GET['godemo'] ) || isset( $_GET['gohide'] ) || isset( $_GET['gopending'] ) ) {
	add_filter(
		'eab_defaults',
		function ( $d ) {
			if ( isset( $_GET['godemo'] ) ) {
				$d['line_url']       = 'https://lin.ee/preview-only';
				$d['go_deposit_url'] = 'https://example.com/portal';
			}
			if ( isset( $_GET['gohide'] ) ) {
				foreach ( explode( ',', (string) $_GET['gohide'] ) as $n ) {
					$n = (int) $n;
					if ( $n >= 1 && $n <= 6 ) {
						$d[ 'go_step' . $n . '_title' ] = '';
					}
				}
			}
			if ( isset( $_GET['gopending'] ) ) {
				for ( $i = 1; $i <= 4; $i++ ) {
					$d[ 'go_btn' . $i . '_url' ] = '';
				}
				foreach ( array( 'windows', 'android', 'ios' ) as $os ) {
					$d[ 'go_vps_' . $os . '_url' ] = '';
				}
			}
			return $d;
		},
		99
	);
}
// phpcs:enable
