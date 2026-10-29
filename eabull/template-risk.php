<?php
/**
 * Template Name: EA BULL · หน้า Risk Disclosure
 *
 * โครง (Glass Sky): หัวเพจแผงกระจก + ภาพประกอบ → แผงความเสี่ยง (กล่องคำเตือนทองอ่อน + ภาพประกอบถ้ามี
 *       + การ์ดความเสี่ยง 01 ถึง 06 · id="risk-N") → เนื้อหายาวจาก editor (การ์ดขาว + สารบัญ)
 *       → วันที่ปรับปรุงล่าสุด (ท้ายเนื้อหาทั้งหมด) → แถบติดต่อ
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
$eab_title   = get_the_title();
$eab_sub     = eab_mod( 'riskpage_sub' );
$eab_image   = trim( (string) eab_mod( 'riskpage_image' ) );
$eab_caption = trim( (string) eab_mod( 'riskpage_image_caption' ) );
$eab_updated = trim( (string) eab_mod( 'riskpage_updated' ) );
?>

<main id="main" class="pg risk-page">

<?php
eab_pages_hero(
	array(
		'kicker' => eab_mod( 'riskpage_kicker' ),
		'title'  => $eab_title ? $eab_title : eab_mod( 'riskpage_kicker' ),
		'grad'   => eab_mod( 'riskpage_hero_grad' ),
		'lead'   => eab_pages_has( $eab_sub ) ? $eab_sub : '',
		'media'  => eab_pages_hero_media( 'riskpage', 'risk' ),
	)
);
?>

<section class="pg-sec riskdoc-section">
	<div class="container">
		<div class="glass-panel pg-panel riskdoc-panel">

			<?php /* คำเตือนเกริ่นนำ · ไม่ใช้ .reveal เพื่อให้เห็นทันทีแม้ JS ไม่ทำงาน */ ?>
			<div class="pg-warn riskdoc-intro">
				<span class="tile tile--gold" aria-hidden="true"><?php echo eab_icon( 'warn' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				<p><?php echo eab_text( eab_mod( 'riskpage_intro' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></p>
			</div>

			<?php if ( '' !== $eab_image ) : ?>
				<figure class="riskdoc-figure media-frame">
					<?php
					echo eab_media_open( // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside
						$eab_image,
						eab_media_picture( '', $eab_image, $eab_caption, 1280, 720 ),
						$eab_caption
					);
					?>
					<?php if ( '' !== $eab_caption ) : ?>
						<figcaption class="media-cap"><?php echo esc_html( $eab_caption ); ?></figcaption>
					<?php endif; ?>
				</figure>
			<?php endif; ?>

			<div class="riskdoc">
				<?php
				$eab_n = 0;
				for ( $i = 1; $i <= 6; $i++ ) :
					$b_title = trim( (string) eab_mod( 'rp_block' . $i . '_title' ) );
					$b_text  = trim( (string) eab_mod( 'rp_block' . $i . '_text' ) );
					if ( '' === $b_title && '' === $b_text ) {
						continue;
					}
					$eab_n++;
					?>
					<article class="riskdoc-block" id="<?php echo esc_attr( 'risk-' . $eab_n ); ?>">
						<div class="riskdoc-top">
							<span class="riskdoc-num" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $eab_n ) ); ?></span>
							<span class="riskdoc-ic" aria-hidden="true"><?php echo eab_icon( 'warn', 'icon icon-sm' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						</div>
						<?php if ( '' !== $b_title ) : ?>
							<h2><?php echo eab_text( $b_title ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></h2>
						<?php endif; ?>
						<p><?php echo nl2br( esc_html( $b_text ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
					</article>
				<?php endfor; ?>
			</div>

		</div>
	</div>
</section>

<?php
eab_pages_enable_table_cards();
eab_pages_longform();
?>

<?php if ( ! eab_pages_date_pending( $eab_updated ) ) : ?>
	<div class="pg-sec riskdoc-updated-wrap">
		<div class="container">
			<p class="pg-updated riskdoc-updated"><?php echo eab_icon( 'clock', 'icon icon-sm' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo esc_html( $eab_updated ); ?></span></p>
		</div>
	</div>
<?php endif; ?>

<?php eab_line_cta( eab_mod( 'riskpage_cta_title' ), eab_mod( 'riskpage_cta_text' ) ); ?>

</main>

<?php
get_footer();
