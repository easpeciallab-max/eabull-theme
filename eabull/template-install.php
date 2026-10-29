<?php
/**
 * Template Name: EA BULL · หน้า How to Install
 *
 * หน้าติดตั้ง EA บน MT5 · markup ตามต้นแบบที่อนุมัติ (dev/mockup/_src/pages/how-to-install.html)
 * หัวหน้า → ก่อนเริ่ม → 6 ขั้นตอน #steps → เอกสารยาว (สารบัญ + เช็กลิสต์ + ตารางแก้ปัญหา การอัปเดต และคำถามจากหน้าแก้ไขเพจ)
 * → คู่มือที่เกี่ยวข้อง → แถบติดต่อ
 * ข้อความ ปุ่ม และภาพของแต่ละขั้นแก้ที่ ปรับแต่ง → 22) หน้า How to Install (setting inst_* / install_* ใน inc/modules/guides.php)
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
?>

<main id="main" class="guide-page">
	<?php eab_guide_page( 'inst' ); ?>
</main>

<?php
eab_guide_cta( 'inst' );

get_footer();
