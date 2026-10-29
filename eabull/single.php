<?php
/**
 * บทความเดี่ยว (คอนเทนต์ SEO)
 *
 * Glass Sky:
 * แถบความคืบหน้าการอ่าน (ทองไล่สี) → หัวบทความแผงกระจก (breadcrumb · ชิปหมวด · H1 · วันที่เผยแพร่/อัปเดต · เวลาอ่าน
 * · รูปหน้าปกในกรอบโค้ง) → สารบัญ (จอคอม: การ์ดกระจกติดข้าง · มือถือ: พับได้) + การ์ดขาวอ่านง่าย (เนื้อหา → แชร์
 * → ป้ายกำกับ → กล่องผู้เขียน → คำเตือนความเสี่ยง) → บทความในหมวดเดียวกัน (แถบเลื่อน) → แถบติดต่อ
 *
 * ข้อความที่ผู้เข้าชมเห็นมาจาก Customizer → "หน้ารวมบทความ & บทความ"
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
	$eab_author = trim( (string) eab_mod( 'article_author_name' ) );
	$eab_title  = wp_strip_all_tags( get_the_title() );

	/* เนื้อหาก่อน (สารบัญเก็บหัวข้อระหว่าง the_content) */
	eab_pages_enable_table_cards();
	$GLOBALS['eab_toc'] = array();
	$eab_content        = apply_filters( 'the_content', get_the_content() );
	$eab_content        = str_replace( ']]>', ']]&gt;', $eab_content );
	$eab_art_toc        = eab_pages_toc_items( isset( $GLOBALS['eab_toc'] ) ? $GLOBALS['eab_toc'] : array(), $eab_content, array( 2, 3 ) );
	$eab_has_toc        = count( $eab_art_toc ) >= 2;

	/* ชิปหมวด + บรรทัดวันที่/เวลาอ่าน + รูปปก สำหรับหัวบทความ */
	$eab_chip = '';
	if ( $eab_cat ) {
		$eab_chip = '<a class="article-cat" href="' . esc_url( get_category_link( $eab_cat->term_id ) ) . '">' . esc_html( $eab_cat->name ) . '</a>';
	}
	$eab_meta_html = '<div class="article-meta">';
	if ( '' !== $eab_meta['published'] ) {
		$eab_meta_html .= '<span class="article-meta-item">' . eab_icon( 'clock', 'icon icon-sm' ) . '<span>' . esc_html( eab_mod( 'article_published_label' ) ) . '</span> <time datetime="' . esc_attr( $eab_meta['published_iso'] ) . '">' . esc_html( $eab_meta['published'] ) . '</time></span>';
	}
	if ( '' !== $eab_meta['modified'] && eab_mod( 'article_show_updated' ) ) {
		$eab_meta_html .= '<span class="article-meta-item"><span>' . esc_html( eab_mod( 'article_updated_label' ) ) . '</span> <time datetime="' . esc_attr( $eab_meta['modified_iso'] ) . '">' . esc_html( $eab_meta['modified'] ) . '</time></span>';
	}
	$eab_meta_html .= '<span class="article-meta-item">' . eab_icon( 'book', 'icon icon-sm' ) . '<span>' . esc_html( eab_pages_count_text( 'article_reading_text', $eab_meta['minutes'] ) ) . '</span></span>';
	$eab_meta_html .= '</div>';

	$eab_media = '';
	if ( has_post_thumbnail() ) {
		$eab_media = '<div class="pg-hero-media pg-hero-media--cover"><figure class="pg-squircle article-thumb">'
			. eab_pages_capture( 'the_post_thumbnail', 'large', array( 'alt' => eab_pages_featured_alt( get_the_ID() ) ) )
			. '</figure></div>';
	}
	?>

	<div class="reading-progress" aria-hidden="true"><span></span></div>

	<main id="main" class="pg article-page">
		<article <?php post_class( 'single-article' ); ?>>

			<?php
			eab_pages_hero(
				array(
					'tag'   => 'header',
					'title' => $eab_title,
					'chip'  => $eab_chip,
					'meta'  => $eab_meta_html,
					'media' => $eab_media,
					'class' => 'pg-hero--article',
				)
			);
			?>

			<div class="pg-sec article-main">
				<div class="container">
					<div class="longform-layout<?php echo $eab_has_toc ? '' : ' longform-layout--solo'; ?>">
						<?php
						if ( $eab_has_toc ) {
							eab_pages_toc( $eab_art_toc, eab_mod( 'article_toc_label' ) );
						}
						?>

						<div class="lf-card article-card">
							<div class="entry-content guide-content article-content<?php echo eab_pages_self_numbered( $eab_art_toc ) ? ' lf-self-num' : ''; ?>">
								<?php echo $eab_content; // phpcs:ignore WordPress.Security.EscapeOutput ?>
							</div>

							<?php
							wp_link_pages(
								array(
									'before' => '<div class="page-links">',
									'after'  => '</div>',
								)
							);
							?>

							<?php
							if ( function_exists( 'eab_cluster_box' ) ) {
								eab_cluster_box();
							}
							?>

							<div class="article-share">
								<span class="article-share-label"><?php echo esc_html( eab_mod( 'article_share_label' ) ); ?></span>
								<div class="article-share-btns">
									<a class="share-btn share-line" href="<?php echo esc_url( 'https://social-plugins.line.me/lineit/share?url=' . rawurlencode( $eab_link ) ); ?>" target="_blank" rel="noopener"><?php echo eab_icon( 'line' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span>LINE</span></a>
									<a class="share-btn share-fb" href="<?php echo esc_url( 'https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode( $eab_link ) ); ?>" target="_blank" rel="noopener"><?php echo eab_icon( 'facebook' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span>Facebook</span></a>
									<button class="share-btn share-copy" type="button" data-url="<?php echo esc_url( $eab_link ); ?>"><?php echo eab_icon( 'link' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo eab_icon( 'check', 'icon share-done' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo esc_html( eab_mod( 'article_copy_label' ) ); ?></span></button>
								</div>
							</div>

							<?php
							$eab_tags = get_the_tag_list( '<ul class="article-tags"><li>', '</li><li>', '</li></ul>' );
							if ( $eab_tags ) {
								echo wp_kses_post( $eab_tags );
							}
							?>

							<?php if ( '' !== $eab_author ) : ?>
								<aside class="author-box">
									<span class="tile tile--navy" aria-hidden="true"><?php echo eab_icon( 'users' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
									<div class="author-box-body">
										<span class="pg-label"><?php echo esc_html( eab_mod( 'article_author_kicker' ) ); ?></span>
										<p class="author-box-name"><?php echo esc_html( $eab_author ); ?></p>
										<?php if ( eab_pages_has( eab_mod( 'article_author_bio' ) ) ) : ?>
											<p class="author-box-bio"><?php echo esc_html( eab_mod( 'article_author_bio' ) ); ?></p>
										<?php endif; ?>
										<?php if ( '' !== $eab_about && '' !== trim( (string) eab_mod( 'article_author_link' ) ) ) : ?>
											<a class="author-box-link" href="<?php echo esc_url( $eab_about ); ?>"><span><?php echo esc_html( eab_mod( 'article_author_link' ) ); ?></span> <?php echo eab_icon( 'arrow', 'icon icon-sm' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
										<?php endif; ?>
									</div>
								</aside>
							<?php endif; ?>

							<?php /* คำเตือนความเสี่ยง · ข้อความหลักเต็มเสมอ + คำเตือนเพิ่มของบทความ · ไม่ใช้ .reveal ให้เห็นทันทีแม้ JS ไม่ทำงาน */ ?>
							<div class="pg-warn article-disclaimer">
								<span class="tile tile--gold" aria-hidden="true"><?php echo eab_icon( 'warn' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
								<div>
									<p><?php echo esc_html( eab_mod( 'risk_text' ) ); ?></p>
									<?php if ( eab_pages_has( eab_mod( 'article_disclaimer_text' ) ) ) : ?>
										<p><?php echo esc_html( eab_mod( 'article_disclaimer_text' ) ); ?></p>
									<?php endif; ?>
									<?php
									$eab_risk_url = function_exists( 'eab_published_page_url' ) ? eab_published_page_url( 'risk-disclosure' ) : '';
									if ( '' !== $eab_risk_url && '' !== trim( (string) eab_mod( 'article_disclaimer_link' ) ) ) :
										?>
										<p><a href="<?php echo esc_url( $eab_risk_url ); ?>"><?php echo esc_html( eab_mod( 'article_disclaimer_link' ) ); ?></a></p>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		</article>

		<?php
		if ( $eab_cat ) :
			$eab_related = new WP_Query(
				array(
					'category__in'        => array( $eab_cat->term_id ),
					'post__not_in'        => array( get_the_ID() ),
					'posts_per_page'      => 9,
					'no_found_rows'       => true,
					'ignore_sticky_posts' => true,
				)
			);
			if ( $eab_related->have_posts() ) :
				?>
				<section class="pg-sec related-section" aria-labelledby="related-title">
					<div class="container">
						<div class="glass-panel pg-panel">
							<div class="related-head">
								<h2 id="related-title"><?php echo eab_text( eab_mod( 'article_related_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></h2>
								<div class="rail-nav">
									<button type="button" class="rail-btn rail-prev" data-dir="-1" aria-label="<?php echo esc_attr( eab_mod( 'articles_prev_label' ) ); ?>"><?php echo eab_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></button>
									<button type="button" class="rail-btn rail-next" data-dir="1" aria-label="<?php echo esc_attr( eab_mod( 'articles_next_label' ) ); ?>"><?php echo eab_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></button>
								</div>
							</div>
							<div class="related-rail">
								<?php
								while ( $eab_related->have_posts() ) :
									$eab_related->the_post();
									?>
									<article <?php post_class( 'post-card' ); ?>>
										<?php if ( has_post_thumbnail() ) : ?>
											<a class="post-card-thumb" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
												<?php the_post_thumbnail( 'medium_large', array( 'alt' => eab_pages_featured_alt( get_the_ID() ) ) ); ?>
											</a>
										<?php endif; ?>
										<div class="post-card-body">
											<span class="post-meta"><?php echo esc_html( eab_pages_thai_date( get_the_date( 'c' ) ) ); ?></span>
											<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
											<span class="post-card-more" aria-hidden="true"><?php echo eab_icon( 'arrow', 'icon icon-sm' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
										</div>
									</article>
									<?php
								endwhile;
								wp_reset_postdata();
								?>
							</div>
						</div>
					</div>
				</section>
				<?php
			endif;
		endif;
		?>

		<?php /* บล็อกติดต่อปิดท้าย · อยู่ติดส่วนท้ายเว็บ (หลังบทความในหมวดเดียวกัน) */ ?>
		<?php eab_line_cta(); ?>

	</main>

	<?php
	/* BlogPosting · ผู้เขียนเป็นทีมงาน (Organization) ตามกล่องผู้เขียน ไม่เปิดเผยชื่อผู้ใช้ WordPress */
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
		'headline'         => wp_strip_all_tags( get_the_title() ),
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
	if ( ! eab_has_seo_plugin() ) {
		$eab_schema['description'] = eab_meta_description();
		echo '<script type="application/ld+json">' . wp_json_encode( $eab_schema, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP ) . '</script>'; // phpcs:ignore WordPress.Security.EscapeOutput
	}
	?>

	<?php
endwhile;

get_footer();
