<?php
/**
 * Template Name: EA BULL · หน้าคู่มือ / เนื้อหายาว
 *
 * ใช้กับ: เปิดบัญชี MT5, ติดตั้งและล็อกอิน MT5, VPS (Windows / Android / iOS) และหน้าเครื่องมือคำนวณ
 * หน้าตา Glass Sky (docs/design.md) · CSS ใน assets/css/guides.css
 * - หน้าคู่มือ (slug อยู่ใน eab_guide_map() ของ inc/modules/guides.php):
 *   hero แผงกระจก (breadcrumb + kicker + H1 + คำโปรย + ภาพรวมขั้นตอน) → เกริ่นนำ + สิ่งที่ต้องเตรียม
 *   → ขั้นตอน #steps (การ์ดเลขทอง) + การ์ดดาวน์โหลดแอป → เช็กลิสต์ → เนื้อหายาวจากหน้าแก้ไขเพจบนการ์ดขาว (สารบัญ + ตาราง + FAQ)
 *   → คู่มือที่ควรอ่านต่อ #guides → ติดต่อทีมงาน
 *   ขั้นตอน / เช็กลิสต์ / การ์ดดาวน์โหลด / ข้อความติดต่อ แก้ที่ ปรับแต่ง → "คู่มือ · …"
 *   คำอธิบายยาว ตารางแก้ปัญหา และ FAQ แก้ในหน้าแก้ไขเพจ
 * - เพจอื่นที่เลือกเทมเพลตนี้ (เช่น /tools/): hero → เนื้อหาเพจ + สารบัญ → คู่มือที่ควรอ่านต่อ → ติดต่อทีมงาน (ไม่มีหน้าว่าง)
 * - หน้าเอกสาร (about / privacy / terms / data-deletion) ใช้ page.php
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

$eab_slug  = (string) get_post_field( 'post_name', get_the_ID() );
$eab_pages = eab_site_pages();
$eab_group = isset( $eab_pages[ $eab_slug ]['group'] ) ? $eab_pages[ $eab_slug ]['group'] : '';
$eab_p     = function_exists( 'eab_guide_prefix' ) ? eab_guide_prefix() : '';

if ( $eab_p ) {
	$eab_kicker = eab_mod( $eab_p . '_kicker' );
	$eab_sub    = eab_mod( $eab_p . '_sub' );
} else {
	$eab_kicker = eab_page_kicker( $eab_slug );
	$eab_sub    = has_excerpt() ? get_the_excerpt() : (string) get_post_meta( get_the_ID(), 'eab_meta_description', true );
}

if ( function_exists( 'eab_guide_hero' ) ) {
	/* ภาพรวมขั้นตอน (คำตอบสั้น) อยู่ใน hero · .gd-top ด้านล่างจึงไม่พิมพ์ซ้ำ */
	eab_guide_hero(
		$eab_kicker,
		get_the_title(),
		eab_is_placeholder( $eab_sub ) ? '' : $eab_sub,
		$eab_p ? eab_mod( $eab_p . '_quick_title' ) : '',
		$eab_p ? eab_mod( $eab_p . '_quick' ) : ''
	);
} else {
	eab_page_hero( $eab_kicker, get_the_title(), eab_is_placeholder( $eab_sub ) ? '' : $eab_sub );
}
?>

<main id="main" class="guide-page gd-page<?php echo esc_attr( $eab_p ? ' gd-page--' . $eab_p : ' gd-page--' . sanitize_key( $eab_slug ) ); ?>">

<?php if ( $eab_p ) : ?>

	<?php
	eab_guide_top(
		array(
			'intro'       => eab_mod( $eab_p . '_intro' ),
			'quick_title' => '',
			'quick'       => '',
			'prep_title'  => eab_mod( $eab_p . '_prep_title' ),
			'prep'        => eab_mod( $eab_p . '_prep' ),
			'prep_note'   => eab_mod( $eab_p . '_prep_note' ),
		)
	);

	eab_guide_steps( $eab_p . '_step', in_array( $eab_p, eab_guide_dl_prefixes(), true ) ? $eab_p : '' );

	eab_guide_check( eab_mod( $eab_p . '_check_title' ), eab_mod( $eab_p . '_check' ), eab_mod( $eab_p . '_check_note' ) );

	eab_guide_longform();

	eab_guide_related( $eab_slug );

	eab_line_cta( eab_mod( $eab_p . '_cta_title' ), eab_mod( $eab_p . '_cta_text' ) );
	?>

<?php else : ?>

	<?php
	if ( function_exists( 'eab_guide_longform' ) ) {
		eab_guide_longform();
	} else {
		eab_page_longform( 'section section--flush-top' );
	}

	if ( 'guide' === $eab_group && function_exists( 'eab_guide_related' ) ) {
		eab_guide_related( $eab_slug );
	}

	if ( 'tools' === $eab_slug ) {
		eab_line_cta( eab_mod( 'gtools_cta_title' ), eab_mod( 'gtools_cta_text' ) );
	} else {
		eab_line_cta();
	}
	?>

<?php endif; ?>

</main>

<?php
get_footer();
