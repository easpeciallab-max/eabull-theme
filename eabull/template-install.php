<?php
/**
 * Template Name: EA BULL · หน้า How to Install
 *
 * หน้าตาเดียวกับหน้าคู่มือ (Glass Sky · ส่วนประกอบจาก inc/modules/guides.php · CSS ใน guides.css + pages.css):
 * หัวเพจแผงกระจก (breadcrumb + kicker + H1 + คำโปรย + ภาพรวมขั้นตอน) → เกริ่นนำ + สิ่งที่ต้องเตรียม
 * → 6 ขั้นตอนติดตั้ง (การ์ดเลขทอง | ภาพ) → เช็กว่า EA พร้อมทำงาน → เนื้อหายาวจากหน้าแก้ไขเพจบนการ์ดขาว
 * (สารบัญ + ตารางแก้ปัญหา + FAQ) → คู่มือที่ควรอ่านต่อ → ติดต่อทีมงาน
 * ข้อความและช่องรูปของแต่ละขั้นแก้ที่ ปรับแต่ง → 22) หน้า How to Install
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
$eab_title  = get_the_title();
$eab_kicker = eab_mod( 'inst_kicker' );
$eab_sub    = eab_mod( 'install_sub' );
$eab_hero   = function_exists( 'eab_guide_hero' );

if ( $eab_hero ) {
	/* ภาพรวมขั้นตอนอยู่ใน hero · .gd-top ด้านล่างจึงไม่พิมพ์ซ้ำ */
	eab_guide_hero(
		$eab_kicker,
		$eab_title ? $eab_title : $eab_kicker,
		eab_is_placeholder( $eab_sub ) ? '' : $eab_sub,
		eab_mod( 'inst_quick_title' ),
		eab_mod( 'inst_quick' )
	);
} else {
	eab_page_hero( $eab_kicker, $eab_title ? $eab_title : $eab_kicker, eab_is_placeholder( $eab_sub ) ? '' : $eab_sub );
}
?>

<main id="main" class="guide-page gd-page gd-page--install">

<?php
eab_guide_top(
	array(
		'intro'       => eab_mod( 'install_intro' ),
		'quick_title' => $eab_hero ? '' : eab_mod( 'inst_quick_title' ),
		'quick'       => $eab_hero ? '' : eab_mod( 'inst_quick' ),
		'prep_title'  => eab_mod( 'inst_req_title' ),
		'prep'        => eab_mod( 'install_req' ),
		'prep_note'   => eab_mod( 'inst_req_note' ),
	)
);

eab_guide_steps( 'inst_step', '', eab_mod( 'install_note' ) );

eab_guide_check( eab_mod( 'inst_check_title' ), eab_mod( 'inst_check' ), eab_mod( 'inst_check_note' ) );

eab_guide_longform();

eab_guide_related( 'how-to-install' );

eab_line_cta( eab_mod( 'inst_cta_title' ), eab_mod( 'inst_cta_text' ) );
?>

</main>

<?php
get_footer();
