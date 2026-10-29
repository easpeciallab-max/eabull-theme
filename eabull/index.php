<?php
/**
 * หน้ารวมบทความ (/articles/) + หน้าหมวดหมู่/ป้ายกำกับ
 *
 * Glass Sky: หัวเพจแผงกระจก (H1 · คำโปรย · จำนวนบทความ · ชิปหมวด) → การ์ดบทความกระจก + ปุ่มโหลดเพิ่ม (แคปซูลกระจก)
 *            ยังไม่มีบทความ: แผง "กำลังเตรียมบทความ" + การ์ดคู่มือ (เฉพาะเพจที่เผยแพร่แล้ว)
 *            มีบทความ (หน้าแรกของรายการ): แผงรายการคู่มือท้ายหน้า
 *
 * @package eabull
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$eab_desc   = get_the_archive_description();
$eab_found  = (int) $GLOBALS['wp_query']->found_posts;
$eab_guides = eab_pages_guide_items();
$eab_has    = have_posts();

if ( is_home() && ! is_front_page() ) {
	$eab_posts_page = (int) get_option( 'page_for_posts' );
	$eab_h1         = $eab_posts_page ? get_the_title( $eab_posts_page ) : eab_mod( 'articles_title' );
} elseif ( is_search() ) {
	$eab_h1 = eab_mod( 'search_title' ) . ': ' . get_search_query();
} elseif ( is_archive() ) {
	$eab_h1 = wp_strip_all_tags( get_the_archive_title() );
} else {
	$eab_h1 = eab_mod( 'articles_title' );
}
$eab_sub = $eab_desc ? wp_strip_all_tags( $eab_desc ) : ( is_home() ? eab_mod( 'blog_subtitle' ) : '' );

/* จำนวนบทความ + ชิปหมวด (อยู่ในหัวเพจ) */
$eab_cats      = is_search() ? array() : get_categories( array( 'hide_empty' => true ) );
$eab_posts_url = (int) get_option( 'page_for_posts' ) ? get_permalink( (int) get_option( 'page_for_posts' ) ) : home_url( '/articles/' );
$eab_bar       = '';
if ( $eab_found || count( $eab_cats ) > 1 ) {
	$eab_bar .= '<div class="archive-bar">';
	if ( $eab_found ) {
		$eab_bar .= '<p class="archive-count">' . esc_html( eab_pages_count_text( 'articles_count_text', $eab_found ) ) . '</p>';
	}
	if ( count( $eab_cats ) > 1 ) {
		$eab_bar .= '<nav class="cat-chips" aria-label="' . esc_attr( eab_mod( 'articles_kicker' ) ) . '">';
		$eab_bar .= '<a class="chip-link' . ( is_home() ? ' is-active' : '' ) . '" href="' . esc_url( $eab_posts_url ) . '"' . ( is_home() ? ' aria-current="page"' : '' ) . '>' . esc_html( eab_mod( 'articles_all_label' ) ) . '</a>';
		foreach ( $eab_cats as $eab_cat ) {
			$eab_on   = is_category( $eab_cat->term_id );
			$eab_bar .= '<a class="chip-link' . ( $eab_on ? ' is-active' : '' ) . '" href="' . esc_url( get_category_link( $eab_cat ) ) . '"' . ( $eab_on ? ' aria-current="page"' : '' ) . '>' . esc_html( $eab_cat->name ) . '</a>';
		}
		$eab_bar .= '</nav>';
	}
	$eab_bar .= '</div>';
}
?>

<main id="main" class="pg articles-page">

	<?php
	eab_pages_hero(
		array(
			'kicker' => eab_mod( 'articles_kicker' ),
			'title'  => $eab_h1,
			'grad'   => is_home() ? eab_mod( 'articles_hero_grad' ) : '',
			'lead'   => eab_pages_has( $eab_sub ) ? $eab_sub : '',
			'after'  => $eab_bar,
			'media'  => is_home() ? eab_pages_hero_media( '', 'articles' ) : '',
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

				<div class="glass-panel pg-panel articles-empty">
					<p class="pg-empty no-posts"><span class="tile tile--gold" aria-hidden="true"><?php echo eab_icon( 'book' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span><span><?php echo esc_html( eab_mod( is_search() ? 'search_empty_text' : 'articles_empty_text' ) ); ?></span></p>
					<?php if ( $eab_guides ) : ?>
						<?php eab_pages_sec_head( eab_mod( 'articles_guides_kicker' ), eab_mod( 'articles_guides_title' ), eab_mod( 'articles_guides_sub' ) ); ?>
						<?php eab_pages_guide_grid( $eab_guides ); ?>
					<?php endif; ?>
				</div>

			<?php endif; ?>

		</div>
	</section>

	<?php
	$eab_pillars = ( is_home() && ! is_paged() && $eab_has && eab_pages_has( eab_mod( 'articles_pillars_title' ) ) ) ? eab_pages_pillar_items() : array();
	if ( count( $eab_pillars ) > 1 ) :
		?>
		<section class="pg-sec pillar-hub">
			<div class="container">
				<div class="glass-panel pg-panel">
					<?php eab_pages_sec_head( '', eab_mod( 'articles_pillars_title' ), eab_mod( 'articles_pillars_sub' ) ); ?>
					<?php eab_pages_guide_grid( $eab_pillars ); ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( is_home() && ! is_paged() && $eab_has && $eab_guides ) : ?>
		<section class="pg-sec guide-hub">
			<div class="container">
				<div class="glass-panel pg-panel">
					<?php eab_pages_sec_head( eab_mod( 'articles_guides_kicker' ), eab_mod( 'articles_guides_title' ), eab_mod( 'articles_guides_sub' ) ); ?>
					<?php eab_pages_guide_grid( $eab_guides ); ?>
				</div>
			</div>
		</section>
	<?php endif; ?>
</main>

<?php
get_footer();
