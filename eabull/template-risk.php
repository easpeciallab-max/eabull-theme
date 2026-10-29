<?php
/**
 * Template Name: ประกาศความเสี่ยง (Risk Disclosure)
 *
 * ต้นแบบ dev/mockup/_src/pages/risk-disclosure.html:
 * หัวเพจ (วันที่ปรับปรุงจากวันที่แก้ไขเพจ) + 3 บรรทัดสำคัญ → กล่องเตือน + ความเสี่ยงหลัก 6 ข้อ (.feat)
 * → ประกาศฉบับเต็ม (เนื้อหาของเพจ: สารบัญ + เอกสาร) → อ่านต่อ → แถบติดต่อ
 * ห้ามลบหรือลดทอนคำเตือน · ข้อความแก้ได้ที่ ปรับแต่ง → 24) หน้าประกาศความเสี่ยง
 *
 * @package eabull
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	?>
<main id="main">
	<?php
	eab_pages_head(
		'rk',
		array(
			'updated'  => eab_pages_updated(),
			'sections' => eab_pages_section_count(),
		)
	);
	?>

	<section class="band" id="core" aria-labelledby="core-title">
		<div class="wrap">
			<?php
			echo eab_pages_note( 'warn', eab_mod( 'rk_core_warn' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside
			$eab_feat = eab_pages_feat( eab_mod( 'rk_core_items' ) );
			if ( '' !== $eab_feat ) {
				echo str_replace( '<div class="shead">', '<div class="shead mt-48">', eab_pages_shead( 'core-title', eab_mod( 'rk_core_title' ), eab_mod( 'rk_core_sub' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside
				echo $eab_feat; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside
			}
			?>
		</div>
	</section>

	<?php
	eab_pages_doclay(
		array(
			'label' => eab_mod( 'rk_doc_label' ),
			'date'  => true,
		)
	);

	eab_pages_related_band( eab_mod( 'rk_rel_title' ), eab_mod( 'rk_rel_items' ), 'band' );
	?>
</main>
	<?php
	eab_pages_cta( 'rk' );
endwhile;

get_footer();
