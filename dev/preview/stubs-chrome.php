<?php
/**
 * Preview stubs for the site chrome module (header, contact band, footer, dock, floating button).
 * Loaded by dev/preview/wp-stubs.php · not part of the theme · every function is wrapped so the
 * stubs of other modules can define the same helpers.
 *
 * Preview-only switches (query string):
 *   ?fx_chrome_demo=1  sample LINE OA, QR image and dashboard links (example URLs, not real ones)
 *                      to check the "LINE is set" state: QR in the contact card, LINE links that
 *                      open line.me instead of /go/, dashboard button with a real href
 */

if ( ! function_exists( 'wp_parse_url' ) ) {
	function wp_parse_url( $url, $component = -1 ) {
		return parse_url( (string) $url, $component );
	}
}

if ( ! function_exists( 'wp_date' ) ) {
	function wp_date( $format, $timestamp = null, $timezone = null ) {
		$date = new DateTime( '@' . ( null === $timestamp ? time() : (int) $timestamp ) );
		$date->setTimezone( $timezone instanceof DateTimeZone ? $timezone : new DateTimeZone( 'Asia/Bangkok' ) );
		return $date->format( $format );
	}
}

// phpcs:disable WordPress.Security.NonceVerification -- local preview only
if ( isset( $_GET['fx_chrome_demo'] ) ) {
	add_filter(
		'eab_defaults',
		function ( $d ) {
			return array_merge(
				$d,
				array(
					'line_url'      => 'https://lin.ee/example',
					'line_qr_image' => 'assets/img/brand/eabull-icon-512.png',
					'dashboard_url' => 'https://example.com/dashboard/',
				)
			);
		},
		99
	);
}

// phpcs:enable

/* $wp->request: path of the current page (dock and menu use it to mark the current item) */
if ( ! isset( $GLOBALS['wp'] ) ) {
	$fx_chrome_path = parse_url( isset( $_SERVER['REQUEST_URI'] ) ? (string) $_SERVER['REQUEST_URI'] : '/', PHP_URL_PATH );
	$GLOBALS['wp']  = (object) array( 'request' => trim( is_string( $fx_chrome_path ) ? $fx_chrome_path : '', '/' ) );
}
