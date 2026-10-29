<?php
/**
 * Template Name: Forward Test
 *
 * ต้นแบบ dev/mockup/_src/pages/forward-test.html (คู่มือล้วน ไม่มีผลเทรดของ EA BULL):
 * หัวเพจ + สรุป 5 ข้อ → ต่างจาก Backtest อย่างไร (ตาราง) → เลือกบัญชีที่ใช้ทดสอบ (ตาราง) → วางแผนการทดสอบ (รางซ้าย + 5 ขั้น)
 * → ติดตามและอ่านผล (เนื้อหาของเพจ + เช็กลิสต์ก่อนเพิ่มเงิน) → ทดสอบด้วยบัญชีของคุณเอง → คำถามที่พบบ่อย → อ่านต่อ → แถบติดต่อ
 * ข้อความแก้ได้ที่ ปรับแต่ง → 22) หน้า Forward Test
 *
 * @package eabull
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	$eab_steps = eab_pages_step_items( 'fw', 5 );
	$eab_ready = eab_pages_pairs( eab_mod( 'fw_ready_items' ) );
	$eab_faq   = eab_pages_qa( eab_mod( 'fw_faq_items' ) );
	$eab_own   = '' !== trim( (string) eab_mod( 'fw_own_title' ) );
	?>
<main id="main">
	<?php eab_pages_head( 'fw', array( 'steps' => count( $eab_steps ) ) ); ?>

	<?php $eab_vs = eab_pages_table( eab_mod( 'fw_vs_rows' ) ); ?>
	<?php if ( '' !== $eab_vs ) : ?>
		<section class="band" id="vs" aria-labelledby="vs-title">
			<div class="wrap">
				<?php
				echo eab_pages_shead( 'vs-title', eab_mod( 'fw_vs_title' ), eab_mod( 'fw_vs_sub' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside
				echo $eab_vs; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside
				echo eab_pages_note( 'info', eab_mod( 'fw_vs_note' ), 'mt-16' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside
				?>
			</div>
		</section>
	<?php endif; ?>

	<?php $eab_where = eab_pages_table( eab_mod( 'fw_where_rows' ) ); ?>
	<?php if ( '' !== $eab_where ) : ?>
		<section class="band band--alt" id="where" aria-labelledby="where-title">
			<div class="wrap">
				<?php
				echo eab_pages_shead( 'where-title', eab_mod( 'fw_where_title' ), eab_mod( 'fw_where_sub' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside
				echo $eab_where; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside
				echo eab_pages_note( 'info', eab_mod( 'fw_where_note' ), 'mt-16' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside
				?>
			</div>
		</section>
	<?php endif; ?>

	<?php
	eab_pages_guide_band(
		array(
			'id'    => 'plan',
			'band'  => 'band',
			'rail'  => eab_mod( 'fw_plan_rail' ),
			'title' => eab_mod( 'fw_plan_title' ),
			'sub'   => eab_mod( 'fw_plan_sub' ),
			'items' => $eab_steps,
			'step'  => 'plan',
		)
	);

	/* เช็กลิสต์ก่อนเพิ่มเงิน (main.js: [data-ready] นับข้อที่ติ๊ก) ต่อท้ายเนื้อหาของเพจในการ์ดเอกสารเดียวกัน */
	$eab_after = '';
	$eab_extra = array();
	if ( $eab_ready && '' !== trim( (string) eab_mod( 'fw_ready_title' ) ) ) {
		$eab_total = count( $eab_ready );
		$eab_after = '<h2 id="check">' . eab_text( eab_mod( 'fw_ready_title' ) ) . '</h2>';
		if ( '' !== trim( (string) eab_mod( 'fw_ready_sub' ) ) ) {
			$eab_after .= '<p>' . eab_pages_fmt( eab_mod( 'fw_ready_sub' ) ) . '</p>';
		}
		$eab_after .= '<div class="ready" data-ready data-left="' . esc_attr( eab_mod( 'fw_ready_left' ) ) . '" data-full="' . esc_attr( eab_mod( 'fw_ready_full' ) ) . '"><div class="ready__list" role="list">';
		foreach ( $eab_ready as $eab_item ) {
			$eab_after .= '<div role="listitem"><label class="ready__item"><input class="box" type="checkbox"><span><b>' . eab_pages_fmt( $eab_item[0] ) . '</b>' . ( '' !== $eab_item[1] ? '<small>' . eab_pages_fmt( $eab_item[1] ) . '</small>' : '' ) . '</span></label></div>';
		}
		$eab_after .= '</div><div class="ready__meter" aria-live="polite"><div class="meter" aria-hidden="true">' . str_repeat( '<i></i>', $eab_total ) . '</div>';
		$eab_after .= '<p class="ready__count"><span data-ready-count>0</span><small> / ' . esc_html( $eab_total . ' ' . eab_mod( 'fw_ready_unit' ) ) . '</small></p>';
		$eab_after .= '<p class="ready__status" data-ready-status>' . esc_html( str_replace( '{n}', (string) $eab_total, (string) eab_mod( 'fw_ready_left' ) ) ) . '</p>';
		$eab_after .= '<div class="ready__done" data-ready-done hidden>' . ( '' !== trim( (string) eab_mod( 'fw_ready_done' ) ) ? '<p>' . eab_pages_fmt( eab_mod( 'fw_ready_done' ) ) . '</p>' : '' ) . eab_pages_line_btn( eab_mod( 'fw_ready_btn' ), 'forward-ready', 'btn--block' ) . '</div>';
		$eab_after .= '</div></div>';
		$eab_extra[] = array(
			'id'   => 'check',
			'text' => (string) eab_mod( 'fw_ready_title' ),
		);
	}
	if ( $eab_own ) {
		$eab_extra[] = array(
			'id'   => 'own',
			'text' => (string) eab_mod( 'fw_own_title' ),
		);
	}
	if ( '' !== $eab_faq ) {
		$eab_extra[] = array(
			'id'   => 'faq',
			'text' => (string) eab_mod( 'fw_faq_title' ),
		);
	}
	eab_pages_doclay(
		array(
			'label' => eab_mod( 'fw_doc_label' ),
			'after' => $eab_after,
			'extra' => $eab_extra,
		)
	);
	?>

	<?php if ( $eab_own ) : ?>
		<section class="band" id="own" aria-labelledby="own-title">
			<div class="wrap grid g2">
				<div class="stack">
					<h2 id="own-title"><?php echo eab_pages_bars() . eab_text( eab_mod( 'fw_own_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></h2>
					<?php if ( '' !== trim( (string) eab_mod( 'fw_own_lead' ) ) ) : ?>
						<p class="lead"><?php echo eab_pages_fmt( eab_mod( 'fw_own_lead' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></p>
					<?php endif; ?>
					<?php if ( '' !== trim( (string) eab_mod( 'fw_own_muted' ) ) ) : ?>
						<p class="muted"><?php echo eab_pages_fmt( eab_mod( 'fw_own_muted' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></p>
					<?php endif; ?>
					<?php $eab_own_btns = eab_pages_btns( eab_mod( 'fw_own_btns' ), 'forward-own' ); ?>
					<?php if ( '' !== $eab_own_btns ) : ?>
						<div class="btns"><?php echo $eab_own_btns; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></div>
					<?php endif; ?>
				</div>
				<div class="card">
					<?php if ( '' !== trim( (string) eab_mod( 'fw_own_card_title' ) ) ) : ?>
						<h3><?php echo eab_text( eab_mod( 'fw_own_card_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></h3>
					<?php endif; ?>
					<?php
					echo eab_pages_blocks( implode( "\n", array_map( function ( $l ) { return '+ ' . $l; }, eab_lines( eab_mod( 'fw_own_card_items' ) ) ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside
					if ( '' !== trim( (string) eab_mod( 'fw_own_card_text' ) ) ) {
						echo '<p>' . eab_pages_fmt( eab_mod( 'fw_own_card_text' ) ) . '</p>'; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside
					}
					echo eab_pages_line_btn( eab_mod( 'fw_own_card_btn' ), 'forward-own', 'btn--block' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside
					?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( '' !== $eab_faq ) : ?>
		<section class="band paper" id="faq" aria-labelledby="faq-title">
			<div class="wrap faq">
				<div class="faq__head">
					<h2 id="faq-title"><?php echo eab_pages_bars() . eab_text( eab_mod( 'fw_faq_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></h2>
					<?php if ( '' !== trim( (string) eab_mod( 'fw_faq_sub' ) ) ) : ?>
						<p><?php echo eab_pages_fmt( eab_mod( 'fw_faq_sub' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></p>
					<?php endif; ?>
					<div class="btns"><?php echo eab_pages_line_btn( eab_mod( 'fw_faq_btn' ), 'forward-faq' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></div>
				</div>
				<div><?php echo $eab_faq; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></div>
			</div>
		</section>
	<?php endif; ?>

	<?php eab_pages_related_band( eab_mod( 'fw_rel_title' ), eab_mod( 'fw_rel_items' ) ); ?>
</main>
	<?php
	eab_pages_cta( 'fw' );
endwhile;

get_footer();
