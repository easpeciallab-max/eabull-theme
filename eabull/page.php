<?php
/**
 * เพจทั่วไป / เพจเอกสาร (เกี่ยวกับเรา, นโยบายความเป็นส่วนตัว, เงื่อนไขการใช้บริการ, คำขอลบข้อมูล)
 *
 * Glass Sky: หัวเพจกระจกขนาดเล็ก (breadcrumb · ป้ายเล็ก · H1 · วันที่ปรับปรุงล่าสุด)
 * → การ์ดขาวอ่านง่ายคอลัมน์เดียว (สารบัญติดข้างบนจอคอม / พับได้บนมือถือ เมื่อมี H2 ตั้งแต่ 4 หัวข้อ)
 * ไม่มีบล็อกติดต่อในหน้า ยกเว้นเพจ about · เพจที่สร้างด้วย Elementor แสดงผลของ Elementor ตามเดิม
 *
 * @package eabull
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$eab_is_elementor = eab_is_elementor_page();
?>

<main id="main"<?php echo $eab_is_elementor ? ' class="elementor-page-shell elementor-page-shell--auto"' : ' class="pg doc-page"'; ?>>
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

			$eab_slug = (string) get_post_field( 'post_name', get_the_ID() );

			eab_pages_enable_table_cards();
			$GLOBALS['eab_toc'] = array();
			$eab_content        = apply_filters( 'the_content', get_the_content() );
			$eab_content        = str_replace( ']]>', ']]&gt;', $eab_content );
			$eab_toc            = isset( $GLOBALS['eab_toc'] ) ? $GLOBALS['eab_toc'] : array();
			$eab_updated        = eab_pages_thai_date( get_the_modified_date( 'c' ) );

			$eab_meta = '';
			if ( '' !== $eab_updated ) {
				$eab_meta = '<p class="pg-updated doc-updated">' . eab_icon( 'clock', 'icon icon-sm' )
					. '<span>' . esc_html( eab_mod( 'doc_updated_label' ) ) . ':</span> '
					. '<time datetime="' . esc_attr( get_the_modified_date( 'c' ) ) . '">' . esc_html( $eab_updated ) . '</time></p>';
			}

			eab_pages_hero(
				array(
					'kicker' => function_exists( 'eab_page_kicker' ) ? eab_page_kicker( $eab_slug ) : '',
					'title'  => wp_strip_all_tags( get_the_title() ),
					'meta'   => $eab_meta,
					'size'   => 'compact',
					'class'  => 'doc-hero',
				)
			);

			$eab_before = '';
			if ( has_post_thumbnail() ) {
				$eab_before = '<figure class="article-thumb doc-thumb">' . eab_pages_capture( 'the_post_thumbnail', 'large', array( 'alt' => eab_pages_featured_alt( get_the_ID() ) ) ) . '</figure>';
			}
			$eab_after = eab_pages_capture(
				'wp_link_pages',
				array(
					'before' => '<div class="page-links">',
					'after'  => '</div>',
				)
			);

			eab_pages_longform(
				array(
					'content'    => $eab_content,
					'toc'        => $eab_toc,
					'min'        => 4,
					'class'      => 'doc-section-wrap',
					'body_class' => 'doc-body',
					'before'     => $eab_before,
					'after'      => $eab_after,
				)
			);

			if ( 'about' === $eab_slug ) {
				eab_line_cta( eab_mod( 'aboutpage_cta_title' ), eab_mod( 'aboutpage_cta_text' ) );
			}
			?>

		<?php endwhile; ?>

	<?php endif; ?>
</main>

<?php
get_footer();
