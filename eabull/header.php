<?php
/**
 * Header · แถบเมนูแคปซูลกระจกลอย (Glass Sky · docs/design.md · ต้นแบบ dev/mockup/index.html)
 * - เปิดกรอบเว็บ .site-frame ที่นี่ ปิดใน footer.php (เทมเพลตที่ไม่เรียก get_header() เช่น /go ไม่มีกรอบ)
 * - html.no-js → .js ด้วยสคริปต์บรรทัดเดียว (ใช้กับ .reveal / .watch)
 * - viewport-fit=cover ให้ env(safe-area-inset-bottom) ของแถบล่างมือถือทำงานบน iPhone
 * - โลโก้/ชื่อเว็บ/คำโปรย · ปุ่มทอง · เมนูสำรอง/ต่อท้าย "ติดต่อ" · แถวโซเชียลในแผ่นเมนู อยู่ใน inc/modules/chrome.php
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
<?php $eab_skip = trim( (string) eab_mod( 'chrome_skip_text' ) ); ?>
<?php if ( '' !== $eab_skip ) : ?>
<a class="skip-link" href="#main"><?php echo esc_html( $eab_skip ); ?></a>
<?php endif; ?>

<div class="site-frame">

<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) : ?>
<header class="site-header" id="top">
	<div class="header-bar">

		<?php
		if ( function_exists( 'eab_chrome_brand' ) ) {
			eab_chrome_brand();
		} else {
			?>
			<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
				<img class="brand-logo-img" src="<?php echo esc_url( eab_wordmark_url( 'dark' ) ); ?>" alt="" width="307" height="220">
			</a>
			<?php
		}
		?>

		<nav class="site-nav" id="site-nav" aria-label="เมนูหลัก">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'nav-list',
					'fallback_cb'    => function_exists( 'eab_chrome_fallback_menu' ) ? 'eab_chrome_fallback_menu' : 'eab_fallback_menu',
					'depth'          => 2,
				)
			);
			if ( function_exists( 'eab_chrome_social_row' ) ) {
				eab_chrome_social_row( 'nav-social' );
			}
			?>
		</nav>

		<?php
		if ( function_exists( 'eab_chrome_language_switcher' ) ) {
			eab_chrome_language_switcher();
		}
		if ( function_exists( 'eab_chrome_header_dash' ) ) {
			eab_chrome_header_dash();
		}
		?>

		<button class="nav-toggle" type="button" aria-expanded="false" aria-controls="site-nav" aria-label="เปิด/ปิดเมนู">
			<span></span><span></span><span></span>
		</button>

	</div>
</header>
<?php endif; ?>
