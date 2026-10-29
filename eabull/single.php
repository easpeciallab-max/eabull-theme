<?php
/**
 * บทความเดี่ยว
 *
 * ต้นแบบ dev/mockup/_src/pages/article.html:
 * แถบความคืบหน้าการอ่าน (.readbar) → หัวบทความ (breadcrumb จบที่หมวด · ชิปหมวด · H1 · วันที่ เวลาอ่าน ผู้เขียน · รูปปก)
 * → .artlay: สารบัญ + เอกสารการ์ดขาว (เนื้อหา → บทความในชุดนี้ → แชร์ → ผู้เขียน → คำเตือน)
 * → บทความอื่นในหมวดนี้ (.posts) → แถบติดต่อกลาง (ส่วนท้ายเว็บพิมพ์ให้)
 * ข้อความแก้ได้ที่ ปรับแต่ง → 26) หน้ารวมบทความ & บทความ
 *
 * @package eabull
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	$eab_cats   = get_the_category();
	$eab_cat    = ! empty( $eab_cats ) ? $eab_cats[0] : null;
	$eab_thumb  = get_the_post_thumbnail_url( get_the_ID(), 'full' );
	$eab_meta   = eab_pages_article_meta();
	$eab_about  = eab_pages_about_url();
	$eab_link   = get_permalink();
	$eab_author = trim( (string) eab_mod( 'art_author_name' ) );
	$eab_title  = eab_pages_title();

	/* ประมวลผลเนื้อหาก่อนพิมพ์หัวเพจ: eab_collect_toc เก็บ H2 ไว้ทำสารบัญ */
	eab_pages_enable_table_cards();
	$GLOBALS['eab_toc'] = array();
	$eab_content        = str_replace( ']]>', ']]&gt;', (string) apply_filters( 'the_content', get_the_content() ) );
	$eab_toc            = eab_pages_toc_items( isset( $GLOBALS['eab_toc'] ) ? $GLOBALS['eab_toc'] : array(), $eab_content );
	$eab_toc_html       = count( $eab_toc ) >= 2 ? eab_pages_toc_html( $eab_toc, eab_mod( 'art_toc_label' ) ) : '';

	/* บรรทัดข้อมูลใต้ H1 */
	$eab_meta_html = '<div class="phead__meta">';
	if ( '' !== $eab_meta['published'] ) {
		$eab_meta_html .= '<span>' . eab_icon( 'calendar' ) . '<time datetime="' . esc_attr( $eab_meta['published_iso'] ) . '">' . esc_html( $eab_meta['published'] ) . '</time></span>';
	}
	if ( '' !== $eab_meta['modified'] && eab_mod( 'art_show_updated' ) ) {
		$eab_meta_html .= '<span>' . eab_icon( 'refresh' ) . esc_html( eab_mod( 'art_updated_label' ) ) . ' <time datetime="' . esc_attr( $eab_meta['modified_iso'] ) . '">' . esc_html( $eab_meta['modified'] ) . '</time></span>';
	}
	$eab_meta_html .= '<span>' . eab_icon( 'clock' ) . esc_html( eab_pages_count_text( 'art_reading_text', $eab_meta['minutes'] ) ) . '</span>';
	if ( '' !== $eab_author ) {
		$eab_meta_html .= '<span>' . eab_icon( 'user' ) . esc_html( $eab_author ) . '</span>';
	}
	$eab_meta_html .= '</div>';

	$eab_cover = '';
	if ( has_post_thumbnail() ) {
		$eab_cover = '<div class="art-cover">' . eab_pages_capture( 'the_post_thumbnail', 'large', array( 'alt' => eab_pages_featured_alt( get_the_ID() ) ) ) . '</div>';
	}
	?>
	<div class="readbar" aria-hidden="true"><i></i></div>
	<main id="main">
		<article <?php post_class(); ?>>
			<?php
			eab_pages_phead(
				array(
					'title'     => $eab_title,
					'crumb_cut' => true,
					'pre'       => $eab_cat ? '<a class="chip" href="' . esc_url( get_category_link( $eab_cat->term_id ) ) . '">' . esc_html( $eab_cat->name ) . '</a>' : '',
					'h1_class'  => $eab_cat ? 'mt-16' : '',
					'meta'      => $eab_meta_html,
					'after'     => $eab_cover,
				)
			);
			?>
			<section class="band">
				<div class="wrap<?php echo '' !== $eab_toc_html ? ' artlay' : ''; ?>">
					<?php echo $eab_toc_html; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?>
					<div class="doc paper<?php echo '' === $eab_toc_html ? ' doc--solo' : ''; ?>">
						<?php echo $eab_content; // phpcs:ignore WordPress.Security.EscapeOutput -- the_content ?>
						<?php
						wp_link_pages(
							array(
								'before' => '<div class="page-links">',
								'after'  => '</div>',
							)
						);
						if ( function_exists( 'eab_cluster_box' ) ) {
							eab_cluster_box();
						}
						?>
						<div class="share mt-32">
							<b><?php echo esc_html( eab_mod( 'art_share_label' ) ); ?></b>
							<a class="btn btn--line btn--sm" href="<?php echo esc_url( 'https://social-plugins.line.me/lineit/share?url=' . rawurlencode( $eab_link ) ); ?>" target="_blank" rel="noopener" data-line-pos="article-share"><?php echo eab_icon( 'line' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>LINE</a>
							<a class="btn btn--fb btn--sm" href="<?php echo esc_url( 'https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode( $eab_link ) ); ?>" target="_blank" rel="noopener"><?php echo eab_icon( 'facebook' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>Facebook</a>
							<button class="btn btn--outline btn--sm share-copy" type="button" data-url="<?php echo esc_url( $eab_link ); ?>" data-done="<?php echo esc_attr( eab_mod( 'art_copy_done' ) ); ?>"><?php echo eab_icon( 'link' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( eab_mod( 'art_copy_label' ) ); ?></button>
						</div>
						<?php if ( '' !== $eab_author ) : ?>
							<div class="author mt-24">
								<span class="tile tile--ink"><?php echo eab_icon( 'user' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
								<div>
									<span class="small muted"><?php echo esc_html( eab_mod( 'art_author_kicker' ) ); ?></span>
									<b><?php echo esc_html( $eab_author ); ?></b>
									<?php if ( '' !== trim( (string) eab_mod( 'art_author_bio' ) ) ) : ?>
										<p class="small"><?php echo esc_html( eab_mod( 'art_author_bio' ) ); ?></p>
									<?php endif; ?>
								</div>
							</div>
						<?php endif; ?>
						<?php echo eab_pages_note( 'info', eab_mod( 'art_risk_note' ), 'mt-24' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?>
					</div>
				</div>
			</section>
		</article>

		<?php
		if ( $eab_cat ) :
			$eab_related = new WP_Query(
				array(
					'category__in'        => array( $eab_cat->term_id ),
					'post__not_in'        => array( get_the_ID() ),
					'posts_per_page'      => 3,
					'no_found_rows'       => true,
					'ignore_sticky_posts' => true,
				)
			);
			if ( $eab_related->have_posts() ) :
				?>
				<section class="band band--alt" aria-labelledby="more-title">
					<div class="wrap">
						<?php echo eab_pages_shead( 'more-title', eab_mod( 'art_related_title' ), '', '<a class="tlink" href="' . esc_url( get_category_link( $eab_cat->term_id ) ) . '">' . esc_html( eab_mod( 'art_related_more' ) ) . ' ' . eab_icon( 'arrow-right' ) . '</a>' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?>
						<div class="posts">
							<?php
							while ( $eab_related->have_posts() ) :
								$eab_related->the_post();
								eab_post_card();
							endwhile;
							wp_reset_postdata();
							?>
						</div>
					</div>
				</section>
				<?php
			endif;
		endif;
		?>
	</main>

	<?php
	/* schema BlogPosting: author = ชื่อในกล่องผู้เขียน (Organization) ไม่ใช้ชื่อบัญชี WordPress */
	$eab_author_node = array(
		'@type' => 'Organization',
		'name'  => '' !== $eab_author ? $eab_author : get_bloginfo( 'name' ),
	);
	if ( '' !== $eab_about ) {
		$eab_author_node['url'] = $eab_about;
	}
	$eab_schema = array(
		'@context'         => 'https://schema.org',
		'@type'            => 'BlogPosting',
		'headline'         => $eab_title,
		'datePublished'    => get_the_date( 'c' ),
		'dateModified'     => get_the_modified_date( 'c' ),
		'author'           => $eab_author_node,
		'publisher'        => array(
			'@type' => 'Organization',
			'name'  => get_bloginfo( 'name' ),
			'logo'  => array(
				'@type' => 'ImageObject',
				'url'   => eab_logo_url(),
			),
		),
		'mainEntityOfPage' => $eab_link,
	);
	if ( $eab_thumb ) {
		$eab_schema['image'] = $eab_thumb;
	}
	if ( function_exists( 'eab_has_seo_plugin' ) && ! eab_has_seo_plugin() ) {
		if ( function_exists( 'eab_meta_description' ) ) {
			$eab_schema['description'] = eab_meta_description();
		}
		echo '<script type="application/ld+json">' . wp_json_encode( $eab_schema, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP ) . '</script>'; // phpcs:ignore WordPress.Security.EscapeOutput
	}
endwhile;

get_footer();
