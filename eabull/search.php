<?php
/**
 * ผลการค้นหา
 *
 * ต้นแบบ dev/mockup/_src/pages/search.html:
 * หัวเพจ (breadcrumb · H1 พร้อมคำค้น · ช่องค้นหา · จำนวนผล) → การ์ดผลลัพธ์ .posts (eab_post_card: บทความและเพจคู่มือ) + โหลดเพิ่ม
 * ไม่พบผล: การ์ดแจ้ง (ปุ่ม LINE + ลิงก์เริ่มต้น) + คู่มือที่ใช้บ่อย · ข้อความแก้ได้ที่ ปรับแต่ง → 27) หน้าค้นหา & หน้า 404
 *
 * @package eabull
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$eab_q     = trim( (string) get_search_query( false ) );
$eab_query = isset( $GLOBALS['wp_query'] ) ? $GLOBALS['wp_query'] : null;
$eab_found = $eab_query && isset( $eab_query->found_posts ) ? (int) $eab_query->found_posts : 0;
$eab_has   = have_posts();
$eab_title = '' !== $eab_q ? str_replace( '{q}', $eab_q, (string) eab_mod( 'se_title' ) ) : (string) eab_mod( 'se_title_empty' );
?>

<main id="main">
	<?php
	eab_pages_phead(
		array(
			'title' => $eab_title,
			'crumb' => (string) eab_mod( 'se_crumb' ),
			'mid'   => eab_pages_capture( 'eab_pages_search_form', '', 'mt-24' ),
			'meta'  => '' !== $eab_q ? eab_pages_meta( 'list | ' . eab_pages_count_text( 'se_count_text', $eab_has ? max( 1, $eab_found ) : 0 ) ) : '',
		)
	);
	?>

	<?php if ( $eab_has ) : ?>
		<section class="band" aria-label="<?php echo esc_attr( $eab_title ); ?>">
			<div class="wrap">
				<div class="posts">
					<?php
					while ( have_posts() ) :
						the_post();
						eab_post_card();
					endwhile;
					?>
				</div>
				<?php eab_pages_load_more(); ?>
			</div>
		</section>
	<?php else : ?>
		<section class="band band--alt" aria-labelledby="none-title">
			<div class="wrap">
				<div class="card">
					<span class="tile tile--lg"><?php echo eab_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<h2 id="none-title"><?php echo eab_text( str_replace( '{q}', '' !== $eab_q ? $eab_q : '…', (string) eab_mod( 'se_none_title' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></h2>
					<?php if ( '' !== trim( (string) eab_mod( 'se_none_text' ) ) ) : ?>
						<p><?php echo eab_pages_fmt( eab_mod( 'se_none_text' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></p>
					<?php endif; ?>
					<?php $eab_btns = eab_pages_btns( eab_mod( 'se_none_btns' ), 'search-empty' ); ?>
					<?php if ( '' !== $eab_btns ) : ?>
						<div class="btns"><?php echo $eab_btns; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></div>
					<?php endif; ?>
				</div>
				<?php $eab_rel = eab_pages_rel_items( eab_mod( 'se_rel_items' ) ); ?>
				<?php if ( '' !== $eab_rel ) : ?>
					<div class="related mt-24"><?php echo $eab_rel; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></div>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>
</main>

<?php
get_footer();
