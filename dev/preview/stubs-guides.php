<?php
/**
 * Preview stubs for the guides module (eabull/inc/modules/guides.php).
 * Loaded by dev/preview/wp-stubs.php. Not part of the theme.
 *
 * Preview-only switches (query string, e.g. /vps-windows/?gdimg=1):
 *   gdimg=1    fill every step image slot with a theme banner so the text | image layout can be checked
 *              (real sites start with empty slots · the owner uploads screenshots from dev/image-shot-list.md)
 *   gdimg=odd  fill only odd steps (mixed layout: image steps + full-width text steps)
 *   gdlight=1  render with color_mode 'balanced' (white / light-grey bands instead of dark bands)
 *   gdline=1   set a LINE URL so contact buttons point to LINE instead of /go/
 */

if ( isset( $_GET['gdimg'] ) || isset( $_GET['gdlight'] ) || isset( $_GET['gdline'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification -- local preview only
	add_filter(
		'eab_defaults',
		function ( $d ) {
			if ( isset( $_GET['gdlight'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				$d['color_mode'] = 'balanced';
			}
			if ( isset( $_GET['gdline'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				$d['line_url'] = 'https://lin.ee/preview-only';
			}
			if ( isset( $_GET['gdimg'] ) && function_exists( 'eab_guide_map' ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				$odd  = 'odd' === $_GET['gdimg']; // phpcs:ignore WordPress.Security.NonceVerification
				$base = get_template_directory_uri() . '/assets/img/banners/';
				$pool = array(
					'eabull-mt5-navigator-laptop.webp',
					'eabull-mt5-navigator-laptop.webp',
					'eabull-plan-feathers-night.webp',
					'eabull-plan-sky-chart.webp',
					'eabull-plan-track-risk-light.webp',
					'eabull-mt5-laptop-phone.webp',
				);
				$prefixes = array_merge( array_values( eab_guide_map() ), array( 'inst' ) );
				foreach ( $prefixes as $p ) {
					for ( $i = 1; $i <= eab_guide_step_count(); $i++ ) {
						if ( $odd && 0 === $i % 2 ) {
							continue;
						}
						$d[ $p . '_step' . $i . '_img' ]         = $base . $pool[ ( $i - 1 ) % count( $pool ) ];
						$d[ $p . '_step' . $i . '_img_caption' ] = 'ภาพตัวอย่างสำหรับพรีวิวเท่านั้น';
					}
				}
			}
			return $d;
		},
		99
	);
}
