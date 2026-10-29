<?php
/**
 * Template Name: เกี่ยวกับเรา (About)
 *
 * ต้นแบบ dev/mockup/_src/pages/about.html:
 * หัวเพจ + กล่องสิ่งที่เรายึด → EA BULL โดยสรุป (+ กล่องข้อมูลสั้น) → หลักของเรา (การ์ด 3 ใบ) → เราทำงานอย่างไร (.feat)
 * → สิ่งที่เราไม่สัญญา (พื้นขาว) → สินค้าของเรา (eab_products) → ผู้ให้บริการและช่องทางติดต่อ
 * → คำถามที่พบบ่อย (เนื้อหาของเพจ: faq-block) → กล่องความเสี่ยง → อ่านต่อ → แถบติดต่อ
 * ข้อความทุกจุดแก้ได้ที่ ปรับแต่ง → 25) หน้าเกี่ยวกับเรา
 *
 * @package eabull
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	$eab_faq = eab_pages_content();
	?>
<main id="main">
	<?php eab_pages_head( 'ab' ); ?>

	<section class="band" id="brief" aria-labelledby="brief-title">
		<div class="wrap">
			<div class="grid g2">
				<div class="stack">
					<h2 id="brief-title"><?php echo eab_pages_bars() . eab_text( eab_mod( 'ab_brief_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></h2>
					<?php if ( '' !== trim( (string) eab_mod( 'ab_brief_lead' ) ) ) : ?>
						<p class="lead"><?php echo eab_pages_fmt( eab_mod( 'ab_brief_lead' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></p>
					<?php endif; ?>
					<?php echo eab_pages_blocks( eab_mod( 'ab_brief_text' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?>
				</div>
				<?php echo eab_pages_dl( eab_mod( 'ab_facts_title' ), eab_mod( 'ab_facts' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?>
			</div>
		</div>
	</section>

	<?php $eab_cards = eab_pages_cards( eab_mod( 'ab_prin_items' ) ); ?>
	<?php if ( '' !== $eab_cards ) : ?>
		<section class="band band--alt" id="principles" aria-labelledby="principles-title">
			<div class="wrap">
				<?php
				echo eab_pages_shead( 'principles-title', eab_mod( 'ab_prin_title' ), eab_mod( 'ab_prin_sub' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside
				echo $eab_cards; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside
				?>
			</div>
		</section>
	<?php endif; ?>

	<?php $eab_work = eab_pages_feat( eab_mod( 'ab_work_items' ) ); ?>
	<?php if ( '' !== $eab_work ) : ?>
		<section class="band" id="work" aria-labelledby="work-title">
			<div class="wrap">
				<?php
				echo eab_pages_shead( 'work-title', eab_mod( 'ab_work_title' ), eab_mod( 'ab_work_sub' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside
				echo $eab_work; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside
				?>
			</div>
		</section>
	<?php endif; ?>

	<?php $eab_np = eab_lines( eab_mod( 'ab_np_items' ) ); ?>
	<?php if ( $eab_np ) : ?>
		<section class="band paper" id="no-promise" aria-labelledby="no-promise-title">
			<div class="wrap">
				<div class="grid g2">
					<div class="stack">
						<h2 id="no-promise-title"><?php echo eab_pages_bars() . eab_text( eab_mod( 'ab_np_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></h2>
						<?php if ( '' !== trim( (string) eab_mod( 'ab_np_lead' ) ) ) : ?>
							<p class="lead"><?php echo eab_pages_fmt( eab_mod( 'ab_np_lead' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></p>
						<?php endif; ?>
						<?php if ( '' !== trim( (string) eab_mod( 'ab_np_muted' ) ) ) : ?>
							<p class="muted"><?php echo eab_pages_fmt( eab_mod( 'ab_np_muted' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></p>
						<?php endif; ?>
					</div>
					<div class="card">
						<?php echo eab_pages_blocks( implode( "\n", array_map( function ( $l ) { return '~ ' . $l; }, $eab_np ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?>
					</div>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php $eab_prods = eab_pages_product_rows(); ?>
	<?php if ( '' !== $eab_prods ) : ?>
		<section class="band" id="products" aria-labelledby="products-title">
			<div class="wrap">
				<?php
				echo eab_pages_shead( 'products-title', eab_mod( 'ab_prod_title' ), eab_mod( 'ab_prod_sub' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside
				echo $eab_prods; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside
				?>
			</div>
		</section>
	<?php endif; ?>

	<section class="band band--alt" id="provider" aria-labelledby="provider-title">
		<div class="wrap">
			<?php echo eab_pages_shead( 'provider-title', eab_mod( 'ab_prov_title' ), eab_mod( 'ab_prov_sub' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?>
			<div class="grid g2">
				<?php echo eab_pages_dl( eab_mod( 'ab_prov_box_title' ), eab_mod( 'ab_prov_rows' ), 'idcard', 'about-provider' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?>
				<div class="card">
					<?php if ( '' !== trim( (string) eab_mod( 'ab_prov_card_title' ) ) ) : ?>
						<h3><?php echo eab_text( eab_mod( 'ab_prov_card_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></h3>
					<?php endif; ?>
					<?php if ( '' !== trim( (string) eab_mod( 'ab_prov_card_text' ) ) ) : ?>
						<p><?php echo eab_pages_fmt( eab_mod( 'ab_prov_card_text' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></p>
					<?php endif; ?>
					<div class="stack"><?php echo eab_pages_btns( eab_mod( 'ab_prov_card_btns' ), 'about-contact' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></div>
				</div>
			</div>
		</div>
	</section>

	<?php if ( '' !== trim( wp_strip_all_tags( $eab_faq['html'] ) ) ) : ?>
		<section class="band paper" id="faq" aria-labelledby="faq-title">
			<div class="wrap faq">
				<div class="faq__head">
					<h2 id="faq-title"><?php echo eab_pages_bars() . eab_text( eab_mod( 'ab_faq_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></h2>
					<?php if ( '' !== trim( (string) eab_mod( 'ab_faq_sub' ) ) ) : ?>
						<p><?php echo eab_pages_fmt( eab_mod( 'ab_faq_sub' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></p>
					<?php endif; ?>
					<div class="btns"><?php echo eab_pages_line_btn( eab_mod( 'ab_faq_btn' ), 'about-faq' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></div>
				</div>
				<div class="doc-flow"><?php echo $eab_faq['html']; // phpcs:ignore WordPress.Security.EscapeOutput -- the_content ?></div>
			</div>
		</section>
	<?php endif; ?>

	<?php $eab_box = eab_pages_riskbox( 'risk-title', eab_mod( 'ab_risk_title' ), eab_mod( 'ab_risk_items' ), eab_pages_btns( eab_mod( 'ab_risk_btns' ) ) ); ?>
	<?php if ( '' !== $eab_box ) : ?>
		<section class="band" id="risk" aria-labelledby="risk-title">
			<div class="wrap"><?php echo $eab_box; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></div>
		</section>
	<?php endif; ?>

	<?php eab_pages_related_band( eab_mod( 'ab_rel_title' ), eab_mod( 'ab_rel_items' ) ); ?>
</main>
	<?php
	eab_pages_cta( 'ab' );
endwhile;

get_footer();
