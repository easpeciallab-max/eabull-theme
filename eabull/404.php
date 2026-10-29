<?php
/**
 * ไม่พบหน้า (404)
 *
 * ต้นแบบ dev/mockup/_src/pages/404.html: เลข 404 ใหญ่ · H1 · คำอธิบาย · ช่องค้นหา · ปุ่มกลับหน้าแรกและ LINE · ลิงก์ที่ใช้บ่อย (.quicklinks)
 * ข้อความแก้ได้ที่ ปรับแต่ง → 27) หน้าค้นหา & หน้า 404
 *
 * @package eabull
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="main">
	<section class="e404">
		<div class="wrap">
			<p class="e404__n" aria-hidden="true">404</p>
			<h1><?php echo eab_text( eab_mod( 'e404_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></h1>
			<?php if ( '' !== trim( (string) eab_mod( 'e404_text' ) ) ) : ?>
				<p><?php echo eab_pages_fmt( eab_mod( 'e404_text' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></p>
			<?php endif; ?>
			<?php eab_pages_search_form(); ?>
			<?php $eab_btns = eab_pages_btns( eab_mod( 'e404_btns' ), '404' ); ?>
			<?php if ( '' !== $eab_btns ) : ?>
				<div class="btns"><?php echo $eab_btns; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></div>
			<?php endif; ?>
			<?php $eab_links = eab_pages_rel_items( eab_mod( 'e404_links' ) ); ?>
			<?php if ( '' !== $eab_links ) : ?>
				<div class="quicklinks"><?php echo $eab_links; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></div>
			<?php endif; ?>
		</div>
	</section>
</main>

<?php
get_footer();
