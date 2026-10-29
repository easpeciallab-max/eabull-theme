<?php
/**
 * Template Name: EA BULL · หน้า Forward Test
 *
 * โครง (Glass Sky): หัวเพจแผงกระจก + ภาพประกอบ → แผงสรุปผล/สถานะ + ลิงก์ผลที่ตรวจสอบได้ + ภาพประกอบวิธีอ่านผล
 *       + หมายเหตุ + คำเตือน → เนื้อหายาวจาก editor (การ์ดขาว + สารบัญ) → แถบติดต่อ
 *
 * @package eabull
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

if ( have_posts() ) {
	the_post();
}
$eab_title = get_the_title();
$eab_sub   = eab_mod( 'forward_sub' );
?>

<main id="main" class="pg tests-page tests-page--forward">

	<?php
	eab_pages_hero(
		array(
			'kicker' => eab_mod( 'forward_kicker' ),
			'title'  => $eab_title ? $eab_title : eab_mod( 'forward_kicker' ),
			'grad'   => eab_mod( 'forward_hero_grad' ),
			'lead'   => eab_pages_has( $eab_sub ) ? $eab_sub : '',
			'media'  => eab_pages_hero_media( 'forward', 'forward' ),
		)
	);
	?>

	<?php eab_pages_tests_top( 'forward' ); ?>

	<?php
	eab_pages_enable_table_cards();
	eab_pages_longform();
	?>

	<?php eab_line_cta( eab_mod( 'forward_cta_title' ), eab_mod( 'forward_cta_text' ) ); ?>

</main>

<?php
get_footer();
