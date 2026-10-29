<?php
/**
 * Template Name: Backtest
 *
 * ต้นแบบ dev/mockup/_src/pages/backtest.html:
 * หัวเพจ + สรุปแบบลูกศร → ผล Backtest (สถิติ 8 ช่อง · รายงาน · คำเตือน · ปุ่มสินค้า) → ทดสอบเองทีละขั้น (รางซ้าย + 8 ขั้น)
 * → อ่านผลให้เป็น (เนื้อหาของเพจ: สารบัญ + เอกสาร) → หน้าที่ควรอ่านต่อ → แถบติดต่อ
 * ผลทดสอบทุกช่องรอรายงาน MT5 ของเจ้าของ (ค่าว่าง = ป้ายรอข้อมูล) · ข้อความแก้ได้ที่ ปรับแต่ง → 21) หน้า Backtest
 *
 * @package eabull
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	$eab_steps = eab_pages_step_items( 'bt', 8 );
	?>
<main id="main">
	<?php eab_pages_head( 'bt', array( 'steps' => count( $eab_steps ) ) ); ?>

	<section class="band" id="results" aria-labelledby="results-title">
		<div class="wrap">
			<?php echo eab_pages_shead( 'results-title', eab_mod( 'bt_results_title' ), eab_mod( 'bt_results_sub' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?>
			<?php eab_pages_stats( 'bt', 8 ); ?>
			<?php
			$eab_r1 = eab_pages_shot( 'bt_report1_img', eab_mod( 'bt_report1_shot' ), eab_mod( 'bt_report1_cap' ), 'bt-report' );
			$eab_r2 = eab_pages_shot( 'bt_report2_img', eab_mod( 'bt_report2_shot' ), eab_mod( 'bt_report2_cap' ), 'bt-report' );
			if ( '' !== $eab_r1 . $eab_r2 ) :
				?>
				<div class="grid g2 mt-24">
					<?php if ( '' !== $eab_r1 ) : ?>
						<div><?php echo $eab_r1; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></div>
					<?php endif; ?>
					<?php if ( '' !== $eab_r2 ) : ?>
						<div><?php echo $eab_r2; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<?php echo eab_pages_note( 'warn', eab_mod( 'bt_results_warn' ), 'mt-24' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?>
			<?php $eab_prod = eab_pages_btns( eab_mod( 'bt_products' ), 'backtest' ); ?>
			<?php if ( '' !== $eab_prod ) : ?>
				<div class="row mt-24"><?php echo $eab_prod; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></div>
			<?php endif; ?>
		</div>
	</section>

	<?php
	eab_pages_guide_band(
		array(
			'id'    => 'guide',
			'rail'  => eab_mod( 'bt_guide_rail' ),
			'title' => eab_mod( 'bt_guide_title' ),
			'sub'   => eab_mod( 'bt_guide_sub' ),
			'items' => $eab_steps,
			'step'  => 'step',
		)
	);

	eab_pages_doclay(
		array(
			'band'  => 'band',
			'label' => eab_mod( 'bt_doc_label' ),
		)
	);

	eab_pages_related_band( eab_mod( 'bt_rel_title' ), eab_mod( 'bt_rel_items' ) );
	?>
</main>
	<?php
	eab_pages_cta( 'bt' );
endwhile;

get_footer();
