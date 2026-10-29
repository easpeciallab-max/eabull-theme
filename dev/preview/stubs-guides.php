<?php
/**
 * Preview stubs for the guides module (eabull/inc/modules/guides.php).
 * Loaded by dev/preview/wp-stubs.php. Not part of the theme.
 *
 * - H1 of the guide pages: WordPress takes the page title from the "title" in the seed content file on import
 *   (inc/setup.php), while the preview router uses the short manifest title. eab_guide_title is filtered here
 *   so the preview shows the same H1 as a freshly imported site.
 *
 * Preview-only switches (query string, e.g. /vps-windows/?gdshot=1):
 *   gdshot=1   drop the theme screenshots so every step shows the .shot placeholder of the mockup
 *   gdimg=1    put a sample image in every step slot (checks the real-image layout on steps without a screenshot)
 *   gdline=1   set a LINE URL so contact buttons point to LINE instead of /go/
 *   gdfact=1   fill every {fact:...} so the page reads as it will once the owner sends the EA facts
 */

add_filter(
	'eab_guide_title',
	function ( $title ) {
		$slug = isset( $GLOBALS['fx_post']['slug'] ) ? (string) $GLOBALS['fx_post']['slug'] : '';
		if ( '' !== $slug && function_exists( 'eab_seed_meta' ) ) {
			$meta = eab_seed_meta( 'pages/' . $slug );
			if ( ! empty( $meta['title'] ) ) {
				return $meta['title'];
			}
		}
		return $title;
	}
);

if ( isset( $_GET['gdshot'] ) || isset( $_GET['gdimg'] ) || isset( $_GET['gdline'] ) || isset( $_GET['gdfact'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification -- local preview only
	add_filter(
		'eab_defaults',
		function ( $d ) {
			if ( isset( $_GET['gdline'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				$d['line_url'] = 'https://lin.ee/preview-only';
			}
			if ( isset( $_GET['gdfact'] ) && function_exists( 'eab_guide_facts' ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				foreach ( array_keys( eab_guide_facts() ) as $fact ) {
					$d[ 'guide_fact_' . $fact ] = 'ค่าตัวอย่างสำหรับพรีวิว';
				}
			}
			if ( ( isset( $_GET['gdshot'] ) || isset( $_GET['gdimg'] ) ) && function_exists( 'eab_guide_map' ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				$prefixes = array_merge( array_values( eab_guide_map() ), array( 'inst' ) );
				foreach ( $prefixes as $p ) {
					for ( $i = 1; $i <= eab_guide_step_count(); $i++ ) {
						$d[ $p . '_step' . $i . '_img' ] = isset( $_GET['gdimg'] ) ? 'assets/img/brand/eabull-share-1200x630.jpg' : ''; // phpcs:ignore WordPress.Security.NonceVerification
					}
				}
			}
			return $d;
		},
		99
	);
}
