<?php
/**
 * Template Name: EA BULL · หน้า Backtest
 *
 * โครง (Glass Sky): หัวเพจแผงกระจก + ภาพประกอบ → แผงสรุปผล/สถานะ + ภาพประกอบวิธีทดสอบ + หมายเหตุ + คำเตือน
 *       → เนื้อหายาวจาก editor (การ์ดขาว + สารบัญ) → แถบติดต่อ
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
$eab_sub   = eab_mod( 'backtest_sub' );
?>

<main id="main" class="pg tests-page tests-page--backtest">

	<?php
	eab_pages_hero(
		array(
			'kicker' => eab_mod( 'backtest_kicker' ),
			'title'  => $eab_title ? $eab_title : eab_mod( 'backtest_kicker' ),
			'grad'   => eab_mod( 'backtest_hero_grad' ),
			'lead'   => eab_pages_has( $eab_sub ) ? $eab_sub : '',
			'media'  => eab_pages_hero_media( 'backtest', 'backtest' ),
		)
	);
	?>

	<?php eab_pages_tests_top( 'backtest' ); ?>

	<?php
	eab_pages_enable_table_cards();
	eab_pages_longform();
	?>

	<?php eab_line_cta( eab_mod( 'backtest_cta_title' ), eab_mod( 'backtest_cta_text' ) ); ?>

</main>

<?php
get_footer();
