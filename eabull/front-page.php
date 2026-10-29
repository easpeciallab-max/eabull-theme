<?php
/**
 * Front page · EA BULL · ดีไซน์ Bull Black (docs/design.md · ต้นแบบ dev/mockup/_src/pages/home.html)
 *
 * hero → แถบแพลตฟอร์ม → สินค้า (เรียงตามเลขรุ่น) → โหมด (แท็บ) → สิ่งที่ EA BULL ทำแทนคุณ → 6 ขั้นตอน
 * → ตารางเทียบ → เช็กลิสต์ความพร้อม → FAQ → ความเสี่ยง → บทความล่าสุด (เฉพาะเมื่อมีบทความเผยแพร่)
 * - ตัวพิมพ์แต่ละส่วน: inc/modules/homeplus.php (สินค้า: inc/modules/products.php)
 * - ค่าเริ่มต้นและหมวด Customizer: inc/modules/home.php · ทุกข้อความมาจาก setting · escape ทุก output
 * - แถบติดต่อก่อน footer: โมดูล chrome พิมพ์เอง
 *
 * @package eabull
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

if ( eab_has_elementor_content() && eab_uses_elementor_page_template() ) :
	?>
	<main id="main" class="elementor-page-shell elementor-page-shell--front">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article <?php post_class( 'elementor-entry' ); ?>>
				<?php the_content(); ?>
			</article>
		<?php endwhile; ?>
	</main>
	<?php
	get_footer();
	return;
endif;
?>

<main id="main">
<?php
foreach ( array( 'hero', 'plat', 'products', 'modes', 'features', 'steps', 'compare', 'ready', 'faq', 'risk', 'articles' ) as $eab_section ) {
	eab_homeplus_section( $eab_section );
}
?>
</main>

<?php
get_footer();
