<?php
/**
 * Template Name: แพ็กเกจ (Pricing)
 *
 * ต้นแบบ dev/mockup/_src/pages/pricing.html:
 * หัวเพจ + กล่องสรุป → แพ็กเกจ 3 คอลัมน์ติดกัน (.plans) → ตารางเทียบ (พื้นขาว) → ก่อนชำระเงิน → สั่งซื้ออย่างไร (ขั้นตอน)
 * → คำถามที่พบบ่อย (เนื้อหาของเพจ: faq-block) → สิ่งที่ค่าแพ็กเกจซื้อไม่ได้ (.riskbox) → แถบติดต่อ
 * ข้อความทุกจุดแก้ได้ที่ ปรับแต่ง → 10) แพ็กเกจราคา และ 23) หน้าแพ็กเกจ
 *
 * @package eabull
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	$eab_pick = eab_pages_featured_pkg();
	$eab_vars = eab_pages_pkg_vars();
	$eab_faq  = eab_pages_content();
	?>
<main id="main">
	<?php eab_pages_head( 'pr' ); ?>

	<section class="band" id="plans" aria-labelledby="plans-title">
		<div class="wrap">
			<?php echo eab_pages_shead( 'plans-title', eab_mod( 'pricing_title' ), eab_mod( 'pricing_subtitle' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?>
			<div class="plans mt-24">
				<?php
				foreach ( array( 1, 2, 3 ) as $eab_n ) :
					$eab_name = trim( (string) eab_mod( 'pkg' . $eab_n . '_name' ) );
					if ( '' === $eab_name ) {
						continue;
					}
					$eab_is_pick = $eab_pick === $eab_n;
					?>
					<article class="plan<?php echo $eab_is_pick ? ' plan--pick' : ''; ?>">
						<div class="plan__head">
							<?php if ( $eab_is_pick && '' !== trim( (string) eab_mod( 'pr_flag_label' ) ) ) : ?>
								<span class="plan__flag"><?php echo esc_html( eab_mod( 'pr_flag_label' ) ); ?></span>
							<?php endif; ?>
							<h3><?php echo esc_html( $eab_name ); ?></h3>
							<p><?php echo '' !== trim( (string) eab_mod( 'pkg' . $eab_n . '_tag' ) ) ? eab_pages_fmt( eab_mod( 'pkg' . $eab_n . '_tag' ) ) : eab_wait( '' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></p>
							<div class="plan__price"><?php echo eab_pages_price_html( $eab_n ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></div>
						</div>
						<div class="plan__body">
							<?php $eab_feat = eab_lines( eab_mod( 'pkg' . $eab_n . '_features' ) ); ?>
							<?php if ( $eab_feat ) : ?>
								<ul class="checks">
									<?php foreach ( $eab_feat as $eab_line ) : ?>
										<li><?php echo eab_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo eab_pages_fmt( $eab_line ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></span></li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
							<?php eab_pages_package_button( $eab_name, $eab_is_pick ); ?>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
			<?php echo eab_pages_note( 'info', eab_mod( 'pricing_note' ), 'mt-24' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?>
		</div>
	</section>

	<?php $eab_compare = eab_pages_table( eab_mod( 'pr_compare_rows' ), array( 'us' => $eab_pick, 'vars' => $eab_vars ) ); ?>
	<?php if ( '' !== $eab_compare ) : ?>
		<section class="band paper" id="compare" aria-labelledby="compare-title">
			<div class="wrap">
				<?php
				echo eab_pages_shead( 'compare-title', eab_mod( 'pr_compare_title' ), eab_mod( 'pr_compare_sub' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside
				echo $eab_compare; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside
				echo eab_pages_note( 'info', eab_mod( 'pr_compare_note' ), 'mt-16' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside
				?>
			</div>
		</section>
	<?php endif; ?>

	<?php $eab_before = eab_pages_table( eab_mod( 'pr_before_rows' ), array( 'vars' => $eab_vars ) ); ?>
	<?php if ( '' !== $eab_before ) : ?>
		<section class="band" id="before-pay" aria-labelledby="before-title">
			<div class="wrap">
				<?php
				echo eab_pages_shead( 'before-title', eab_mod( 'pr_before_title' ), eab_mod( 'pr_before_sub' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside
				echo $eab_before; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside
				?>
			</div>
		</section>
	<?php endif; ?>

	<?php $eab_steps = eab_pages_step_items( 'pr', 5 ); ?>
	<?php if ( $eab_steps ) : ?>
		<section class="band band--alt" id="order" aria-labelledby="order-title">
			<div class="wrap faq">
				<div class="faq__head">
					<h2 id="order-title"><?php echo eab_pages_bars() . eab_text( eab_mod( 'pr_order_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></h2>
					<?php if ( '' !== trim( (string) eab_mod( 'pr_order_sub' ) ) ) : ?>
						<p><?php echo eab_pages_fmt( eab_mod( 'pr_order_sub' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></p>
					<?php endif; ?>
					<div class="btns"><?php echo eab_pages_line_btn( eab_mod( 'pr_order_btn' ), 'pricing-order' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></div>
				</div>
				<?php echo eab_pages_gsteps( $eab_steps, '', false ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( '' !== trim( wp_strip_all_tags( $eab_faq['html'] ) ) ) : ?>
		<section class="band paper" id="faq" aria-labelledby="faq-title">
			<div class="wrap faq">
				<div class="faq__head">
					<h2 id="faq-title"><?php echo eab_pages_bars() . eab_text( eab_mod( 'pr_faq_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></h2>
					<?php if ( '' !== trim( (string) eab_mod( 'pr_faq_sub' ) ) ) : ?>
						<p><?php echo eab_pages_fmt( eab_mod( 'pr_faq_sub' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></p>
					<?php endif; ?>
					<div class="btns"><?php echo eab_pages_line_btn( eab_mod( 'pr_faq_btn' ), 'pricing-faq' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></div>
				</div>
				<div class="doc-flow"><?php echo $eab_faq['html']; // phpcs:ignore WordPress.Security.EscapeOutput -- the_content ?></div>
			</div>
		</section>
	<?php endif; ?>

	<?php $eab_box = eab_pages_riskbox( 'cannot-title', eab_mod( 'pr_cannot_title' ), eab_mod( 'pr_cannot_items' ), eab_pages_btns( eab_mod( 'pr_cannot_btns' ) ) ); ?>
	<?php if ( '' !== $eab_box ) : ?>
		<section class="band" aria-labelledby="cannot-title">
			<div class="wrap"><?php echo $eab_box; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></div>
		</section>
	<?php endif; ?>
</main>
	<?php
	eab_pages_cta( 'pr' );
endwhile;

get_footer();
