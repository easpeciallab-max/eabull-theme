<?php
/**
 * Template Name: EA BULL · หน้าคู่มือ / เนื้อหายาว
 *
 * ใช้กับ: เปิดบัญชีเทรด MT5, ฝากเงิน, ติดตั้งและล็อกอิน MT5, VPS (Windows / Android / iPhone) และหน้าเครื่องคำนวณ /tools/
 * markup ตามต้นแบบที่อนุมัติ (dev/mockup/_src/pages/<slug>.html) · ส่วนประกอบทั้งหมดอยู่ใน inc/modules/guides.php
 * - หน้าคู่มือ (slug อยู่ใน eab_guide_map()): หัวหน้า → ก่อนเริ่ม → ขั้นตอน #steps → เอกสารยาว (สารบัญ + เช็กลิสต์ + เนื้อหาเพจ) → คู่มือที่เกี่ยวข้อง
 * - /tools/: หัวหน้า → เครื่องคำนวณ Lot #lot → Drawdown #dd → เอกสารยาว → คู่มือที่เกี่ยวข้อง
 * - เพจอื่นที่เลือกเทมเพลตนี้: หัวหน้า → เนื้อหาเพจ
 * ปิดท้ายด้วยแถบติดต่อ (eab_line_cta ของโมดูล chrome) นอก <main> แบบต้นแบบ
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

$eab_slug = (string) get_post_field( 'post_name', get_the_ID() );
$eab_p    = function_exists( 'eab_guide_prefix' ) ? eab_guide_prefix() : '';
?>

<main id="main" class="guide-page">
	<?php
	if ( $eab_p ) {
		eab_guide_page( $eab_p );
	} elseif ( 'tools' === $eab_slug ) {
		eab_guide_tools_page();
	} else {
		eab_guide_plain_page();
	}
	?>
</main>

<?php
if ( $eab_p ) {
	eab_guide_cta( $eab_p );
} elseif ( 'tools' === $eab_slug ) {
	eab_guide_cta( 'gtools' );
} else {
	eab_line_cta();
}

get_footer();
