<?php
/**
 * เพจทั่วไป / เพจเอกสาร (เกี่ยวกับเรา · นโยบายความเป็นส่วนตัว · ข้อกำหนดการใช้บริการ · คำขอลบข้อมูลส่วนบุคคล)
 *
 * ต้นแบบ dev/mockup/_src/pages/{about,privacy-policy,terms-of-use,data-deletion}.html:
 * หัวเพจ (.phead + กล่องสรุป · วันที่ปรับปรุงจากวันที่แก้ไขเพจ · จำนวนหัวข้อนับจากเนื้อหา)
 * → .doclay: สารบัญ details.toc + เอกสารการ์ดขาว article.doc.paper (เนื้อหาของเพจ + วันที่ปรับปรุงท้ายเอกสาร)
 * → (ขอลบข้อมูล) เอกสารที่เกี่ยวข้อง → แถบติดต่อเฉพาะหน้าที่ตั้งหัวข้อไว้ (เกี่ยวกับเรา) · เพจกฎหมายไม่มีแถบติดต่อตามต้นแบบ
 * หัวเพจแก้ได้ที่ ปรับแต่ง → 25) เพจเอกสาร · เพจอื่นที่ไม่อยู่ในรายการ: หัวเพจ (ชื่อเพจ) + เอกสาร
 * เพจที่ทำด้วย Elementor: พิมพ์เนื้อหาของ Elementor อย่างเดียว
 *
 * @package eabull
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$eab_is_elementor = eab_is_elementor_page();
$eab_keys         = eab_pages_keys();
$eab_slug         = (string) get_post_field( 'post_name', get_queried_object_id() );

/* เพจ about ที่สร้างก่อนมีเทมเพลตของตัวเอง (ยังไม่ได้เลือกเทมเพลต) → ใช้ template-about.php ตามต้นแบบ */
if ( 'about' === $eab_slug && ! $eab_is_elementor && is_readable( get_template_directory() . '/template-about.php' ) ) {
	require get_template_directory() . '/template-about.php';
	return;
}

get_header();
$eab_k            = isset( $eab_keys[ $eab_slug ] ) ? $eab_keys[ $eab_slug ] : '';
$eab_has_cta      = '' !== $eab_k && '' !== trim( (string) eab_mod( $eab_k . '_cta_title' ) );

/* เพจที่ไม่มีแถบติดต่อ (เพจกฎหมาย): ไม่ให้ส่วนท้ายเว็บพิมพ์แถบกลางแทน */
if ( ! $eab_is_elementor && '' !== $eab_k && ! $eab_has_cta ) {
	add_filter( 'eab_chrome_footer_contact', '__return_false' );
}
?>

<main id="main"<?php echo $eab_is_elementor ? ' class="elementor-page-shell elementor-page-shell--auto"' : ''; ?>>
	<?php if ( $eab_is_elementor ) : ?>

		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article <?php post_class( 'elementor-entry' ); ?>>
				<?php the_content(); ?>
			</article>
		<?php endwhile; ?>

	<?php else : ?>

		<?php
		while ( have_posts() ) :
			the_post();

			$eab_vars = array(
				'updated'  => eab_pages_updated(),
				'sections' => eab_pages_section_count(),
			);
			if ( '' !== $eab_k ) {
				eab_pages_head( $eab_k, $eab_vars );
			} else {
				eab_pages_phead( array( 'title' => eab_pages_title() ) );
			}

			$eab_before = '';
			if ( has_post_thumbnail() ) {
				$eab_before = '<figure class="doc-thumb">' . eab_pages_capture( 'the_post_thumbnail', 'large', array( 'alt' => eab_pages_featured_alt( get_the_ID() ) ) ) . '</figure>';
			}
			$eab_after = eab_pages_capture(
				'wp_link_pages',
				array(
					'before' => '<div class="page-links">',
					'after'  => '</div>',
				)
			);

			eab_pages_doclay(
				array(
					'label'  => '' !== $eab_k ? (string) eab_mod( $eab_k . '_doc_label' ) : '',
					'before' => $eab_before,
					'after'  => $eab_after,
					'date'   => '' !== $eab_k && 'ab' !== $eab_k,
				)
			);

			if ( 'dd' === $eab_k ) {
				eab_pages_related_band( eab_mod( 'dd_rel_title' ), eab_mod( 'dd_rel_items' ), 'band', 'docs-title' );
			}
		endwhile;
		?>

	<?php endif; ?>
</main>

<?php
if ( $eab_has_cta && ! $eab_is_elementor ) {
	eab_pages_cta( $eab_k );
}

get_footer();
