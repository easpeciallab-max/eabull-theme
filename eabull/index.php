<?php
/**
 * หน้ารวมบทความ (/articles/) และหน้าหมวดบทความ
 *
 * ต้นแบบ dev/mockup/_src/pages/articles.html:
 * หัวเพจ (จำนวนบทความจริง · ชิปหมวดจริงที่มีบทความ) → การ์ดบทความ .posts (eab_post_card) + ปุ่มโหลดเพิ่ม
 * → เลือกอ่านตามหมวด (การ์ดหมวดที่มีบทความ) → คู่มือใช้งานทีละขั้น (.related)
 * ยังไม่มีบทความ: กล่องแจ้ง + ปุ่ม + คู่มือ (ไม่แสดงการ์ดตัวอย่าง) · ข้อความแก้ได้ที่ ปรับแต่ง → 26) หน้ารวมบทความ & บทความ
 *
 * @package eabull
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$eab_is_cat = is_category();
$eab_cats   = eab_pages_categories();
$eab_query  = isset( $GLOBALS['wp_query'] ) ? $GLOBALS['wp_query'] : null;
$eab_found  = $eab_query && isset( $eab_query->found_posts ) ? (int) $eab_query->found_posts : 0;
$eab_has    = have_posts();

/* หัวข้อ: หน้าหมวด = ชื่อหมวด · หน้ารวม = ชื่อเพจบทความ (ไม่มีเพจ = ค่าจาก Customizer) */
if ( $eab_is_cat ) {
	$eab_title = html_entity_decode( wp_strip_all_tags( (string) single_cat_title( '', false ) ), ENT_QUOTES, 'UTF-8' );
	$eab_lead  = trim( wp_strip_all_tags( (string) category_description() ) );
} else {
	$eab_posts_page = (int) get_option( 'page_for_posts' );
	$eab_title      = $eab_posts_page ? eab_pages_title( $eab_posts_page ) : (string) eab_mod( 'ar_title' );
	$eab_lead       = (string) eab_mod( 'ar_lead' );
}
if ( '' === trim( $eab_title ) ) {
	$eab_title = (string) eab_mod( 'ar_title' );
}

$eab_meta = '';
if ( $eab_found ) {
	$eab_meta = eab_pages_meta( 'news | ' . eab_pages_count_text( 'ar_count_text', $eab_found ) );
}
$eab_chips = '';
if ( $eab_cats ) {
	$eab_chips = '<nav class="chips" aria-label="หมวดบทความ"><a class="chip" href="' . esc_url( eab_pages_articles_url() ) . '"' . ( $eab_is_cat ? '' : ' aria-current="page"' ) . '>' . esc_html( eab_mod( 'ar_all_label' ) ) . '</a>';
	foreach ( $eab_cats as $eab_cat ) {
		$eab_chips .= '<a class="chip" href="' . esc_url( $eab_cat['url'] ) . '"' . ( $eab_cat['current'] ? ' aria-current="page"' : '' ) . '>' . esc_html( $eab_cat['name'] ) . '</a>';
	}
	$eab_chips .= '</nav>';
}
?>

<main id="main">
	<?php
	eab_pages_phead(
		array(
			'title' => $eab_title,
			'crumb' => $eab_is_cat ? $eab_title : (string) eab_mod( 'ar_crumb' ),
			'lead'  => $eab_lead,
			'meta'  => $eab_meta,
			'after' => $eab_chips,
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
		<section class="band" aria-labelledby="empty-title">
			<div class="wrap">
				<div class="card">
					<span class="tile tile--lg"><?php echo eab_icon( 'news' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<h2 id="empty-title"><?php echo eab_text( eab_mod( 'ar_empty_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></h2>
					<?php if ( '' !== trim( (string) eab_mod( 'ar_empty_text' ) ) ) : ?>
						<p><?php echo eab_pages_fmt( eab_mod( 'ar_empty_text' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></p>
					<?php endif; ?>
					<?php $eab_btns = eab_pages_btns( eab_mod( 'ar_empty_btns' ), 'articles-empty' ); ?>
					<?php if ( '' !== $eab_btns ) : ?>
						<div class="btns"><?php echo $eab_btns; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></div>
					<?php endif; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( $eab_cats && ! $eab_is_cat && '' !== trim( (string) eab_mod( 'ar_topics_title' ) ) ) : ?>
		<?php
		$eab_icons = array();
		foreach ( eab_pages_pairs( eab_mod( 'ar_topics_icons' ) ) as $eab_pair ) {
			$eab_icons[ $eab_pair[0] ] = $eab_pair[1];
		}
		?>
		<section class="band band--alt" aria-labelledby="topics-title">
			<div class="wrap">
				<?php echo eab_pages_shead( 'topics-title', eab_mod( 'ar_topics_title' ), eab_mod( 'ar_topics_sub' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?>
				<div class="grid g4">
					<?php foreach ( $eab_cats as $eab_cat ) : ?>
						<a class="card" href="<?php echo esc_url( $eab_cat['url'] ); ?>">
							<span class="tile tile--accent"><?php echo eab_icon( isset( $eab_icons[ $eab_cat['slug'] ] ) ? $eab_icons[ $eab_cat['slug'] ] : 'news' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
							<h3><?php echo esc_html( $eab_cat['name'] ); ?></h3>
							<p><?php echo esc_html( '' !== $eab_cat['desc'] ? wp_trim_words( $eab_cat['desc'], 18 ) : eab_pages_count_text( 'ar_count_text', $eab_cat['count'] ) ); ?></p>
							<span class="tlink"><?php echo esc_html( eab_mod( 'ar_topics_more' ) ); ?> <?php echo eab_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php
	$eab_guides = eab_pages_rel_items( eab_mod( 'ar_guides_items' ) );
	if ( '' !== $eab_guides ) :
		$eab_more = eab_pages_pairs( eab_mod( 'ar_guides_more' ) );
		$eab_more = $eab_more ? eab_pages_tlink( $eab_more[0][0], $eab_more[0][1] ) : '';
		?>
		<section class="band<?php echo $eab_has ? '' : ' band--alt'; ?>" aria-labelledby="guides-title">
			<div class="wrap">
				<?php echo eab_pages_shead( 'guides-title', eab_mod( 'ar_guides_title' ), '', $eab_more ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?>
				<div class="related"><?php echo $eab_guides; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></div>
			</div>
		</section>
	<?php endif; ?>
</main>

<?php
get_footer();
