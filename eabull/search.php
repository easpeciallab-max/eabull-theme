<?php
/**
 * หน้าผลการค้นหา
 *
 * Glass Sky: หัวเพจกระจกขนาดเล็ก (คำค้น + ช่องค้นหาแคปซูลกระจก + จำนวนผล) → การ์ดบทความกระจก + โหลดเพิ่ม
 *            ไม่พบผล: แผงข้อความแนะนำ + การ์ดคู่มือ (เฉพาะเพจที่เผยแพร่แล้ว)
 *
 * @package eabull
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$eab_found = (int) $GLOBALS['wp_query']->found_posts;
$eab_has   = have_posts();
$eab_query = get_search_query();

$eab_after = '<div class="search-bar">' . eab_pages_capture( 'eab_pages_search_form', eab_mod( 'search_placeholder' ), 'search-bar-form' );
if ( $eab_found ) {
	$eab_after .= '<p class="archive-count">' . esc_html( eab_pages_count_text( 'search_count_text', $eab_found ) ) . '</p>';
}
$eab_after .= '</div>';
?>

<main id="main" class="pg search-page">

	<?php
	eab_pages_hero(
		array(
			'title' => eab_mod( 'search_title' ) . ( '' !== $eab_query ? ': “' . $eab_query . '”' : '' ),
			'after' => $eab_after,
			'size'  => 'compact',
		)
	);
	?>

	<section class="pg-sec posts-wrap">
		<div class="container">

			<?php if ( $eab_has ) : ?>

				<div class="posts-grid">
					<?php
					while ( have_posts() ) :
						the_post();
						eab_post_card();
					endwhile;
					?>
				</div>

				<?php eab_pages_load_more(); ?>

			<?php else : ?>

				<?php $eab_guides = eab_pages_guide_items(); ?>
				<div class="glass-panel pg-panel articles-empty">
					<p class="pg-empty no-posts"><span class="tile tile--gold" aria-hidden="true"><?php echo eab_icon( 'book' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span><span><?php echo esc_html( eab_mod( 'search_empty_text' ) ); ?></span></p>
					<?php if ( $eab_guides ) : ?>
						<?php eab_pages_sec_head( eab_mod( 'articles_guides_kicker' ), eab_mod( 'articles_guides_title' ), eab_mod( 'articles_guides_sub' ) ); ?>
						<?php eab_pages_guide_grid( $eab_guides ); ?>
					<?php endif; ?>
				</div>

			<?php endif; ?>

		</div>
	</section>

</main>

<?php
get_footer();
