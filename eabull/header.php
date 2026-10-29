<?php
/**
 * Header · black full-width bar (mockup dev/mockup/_src/partials/header.html)
 * header.hd#top > brand · nav.nav#nav (menu + mobile sheet buttons) · dashboard button · menu toggle.
 * Pieces and settings: inc/modules/chrome.php · behaviour: assets/js/main.js.
 *
 * @package eabull
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<script>document.documentElement.className = document.documentElement.className.replace( /\bno-js\b/, 'js' );</script>
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php $eab_skip = function_exists( 'eab_chrome_mod' ) ? eab_chrome_mod( 'chrome_skip_text' ) : ''; ?>
<?php if ( '' !== $eab_skip ) : ?>
<a class="skip" href="#main"><?php echo esc_html( $eab_skip ); ?></a>
<?php endif; ?>
<?php if ( function_exists( 'eab_chrome_brand' ) && ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) ) : ?>
<header class="hd" id="top">
	<div class="wrap hd__in">
		<?php eab_chrome_brand(); ?>
		<nav class="nav" id="nav" aria-label="<?php echo esc_attr( eab_chrome_mod( 'chrome_nav_aria' ) ); ?>">
			<?php eab_chrome_menu(); ?>
			<?php eab_chrome_nav_foot(); ?>
		</nav>
		<?php eab_chrome_header_dash(); ?>
		<?php eab_chrome_toggle(); ?>
	</div>
</header>
<?php endif; ?>
