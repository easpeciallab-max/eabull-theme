<?php
/**
 * Template Name: EA BULL · หน้า Pricing
 *
 * โครง (Glass Sky):
 * หัวเพจแผงกระจก (ปุ่ม LINE + เลื่อนไปตารางเทียบ) → การ์ดแพ็กเกจกระจก 3 ใบ (ใบแนะนำมีเส้นทอง + ป้าย) + หมายเหตุ
 * → ตารางเปรียบเทียบ (หัวตารางกรมท่า · คอลัมน์แพ็กเกจแนะนำเน้นสีทองอ่อน) → สิทธิ์ใช้งาน/VPS → ขั้นตอนสั่งซื้อ
 * → เนื้อหายาวจาก editor (การ์ดขาว + สารบัญ) → แถบติดต่อ
 * โหมด "ติดต่อสอบถาม" (ค่าเริ่มต้น): การ์ดไม่แสดงราคา ปุ่มทุกใบเปิด LINE พร้อม data-line-pkg = ชื่อแพ็กเกจ
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
$eab_title    = get_the_title();
$eab_sub      = eab_mod( 'pricing_sub' );
$eab_mode     = eab_mod( 'pricing_mode' );
$eab_has_line = eab_has_line_url();
$eab_rows     = eab_lines( eab_mod( 'compare_rows' ) );
$eab_show_cmp = count( $eab_rows ) >= 2;

/* ปุ่มบนหัวเพจ: ติดต่อทีมงาน (ทอง) + เลื่อนไปตารางเปรียบเทียบ (กระจก) */
$eab_actions = eab_pages_capture(
	'eab_contact_button',
	array(
		'text'  => eab_mod( 'pricing_order_btn_text' ),
		'class' => 'btn btn-fire',
		'pos'   => 'pricing-hero',
	)
);
$eab_jump = trim( (string) eab_mod( 'pricing_hero_jump_label' ) );
if ( '' !== $eab_jump && $eab_show_cmp ) {
	$eab_actions .= '<a class="btn btn-ghost" href="#compare"><span>' . esc_html( $eab_jump ) . '</span>' . eab_icon( 'arrow', 'icon icon-sm pg-ic-down' ) . '</a>';
}

/* ชื่อแพ็กเกจที่ติ๊ก "แนะนำ" (ใช้เน้นคอลัมน์ในตารางเปรียบเทียบ) */
$eab_featured_names = array();
for ( $i = 1; $i <= 3; $i++ ) {
	if ( eab_mod( 'pkg' . $i . '_featured' ) && '' !== trim( (string) eab_mod( 'pkg' . $i . '_name' ) ) ) {
		$eab_featured_names[] = trim( (string) eab_mod( 'pkg' . $i . '_name' ) );
	}
}
?>

<main id="main" class="pg pricing-page">

<?php
eab_pages_hero(
	array(
		'kicker'  => eab_mod( 'pricing_page_kicker' ),
		'title'   => $eab_title ? $eab_title : eab_mod( 'pricing_title' ),
		'grad'    => eab_mod( 'pricing_hero_grad' ),
		'lead'    => eab_pages_has( $eab_sub ) ? $eab_sub : '',
		'actions' => $eab_actions,
		'media'   => eab_pages_hero_media( 'pricing', 'pricing' ),
	)
);
?>

<?php /* การ์ดแพ็กเกจ (ปิดได้ที่ ปรับแต่ง → 10) แพ็กเกจราคา → แสดงส่วนนี้) */ ?>
<?php if ( eab_mod( 'show_pricing' ) ) : ?>
<section class="pg-sec pricing-packages" id="packages">
	<div class="container">
		<div class="glass-panel pg-panel">
			<?php eab_pages_sec_head( eab_mod( 'pricing_kicker' ), eab_mod( 'pricing_title' ), eab_mod( 'pricing_subtitle' ), 'pg-head--center' ); ?>

			<div class="plan-grid">
				<?php
				for ( $i = 1; $i <= 3; $i++ ) :
					$k_name = trim( (string) eab_mod( 'pkg' . $i . '_name' ) );
					if ( '' === $k_name ) {
						continue;
					}
					$k_featured = (bool) eab_mod( 'pkg' . $i . '_featured' );
					$k_price    = eab_mod( 'pkg' . $i . '_price' );
					$k_tag      = eab_mod( 'pkg' . $i . '_tag' );
					$k_flag     = $k_featured ? trim( (string) eab_mod( 'pricing_flag_label' ) ) : '';
					?>
					<article class="plan-card reveal<?php echo $k_featured ? ' is-featured' : ''; ?>">
						<?php if ( '' !== $k_flag ) : ?>
							<span class="plan-flag"><?php echo esc_html( $k_flag ); ?></span>
						<?php endif; ?>
						<h3 class="plan-name"><?php echo esc_html( $k_name ); ?></h3>
						<?php if ( eab_pages_has( $k_tag ) ) : ?>
							<p class="plan-tag"><?php echo eab_text( $k_tag ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></p>
						<?php endif; ?>
						<?php if ( 'price' === $eab_mode && eab_pages_price_ready( $k_price ) ) : ?>
							<div class="plan-price">
								<strong><?php echo esc_html( $k_price ); ?></strong>
								<span><?php echo esc_html( eab_mod( 'pkg' . $i . '_period' ) ); ?></span>
							</div>
						<?php else : ?>
							<div class="plan-price plan-price--contact">
								<strong><?php echo esc_html( eab_mod( 'pricing_contact_label' ) ); ?></strong>
								<?php if ( $eab_has_line && eab_mod( 'pricing_contact_via' ) ) : ?>
									<span><?php echo esc_html( eab_mod( 'pricing_contact_via' ) ); ?></span>
								<?php endif; ?>
							</div>
						<?php endif; ?>
						<ul class="plan-feats">
							<?php foreach ( eab_lines( eab_mod( 'pkg' . $i . '_features' ) ) as $eab_item ) : ?>
								<li><span class="plan-check" aria-hidden="true"><?php echo eab_icon( 'check', 'icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span><span><?php echo esc_html( $eab_item ); ?></span></li>
							<?php endforeach; ?>
						</ul>
						<?php eab_pages_package_button( $k_name, $k_featured ); ?>
					</article>
				<?php endfor; ?>
			</div>

			<?php if ( eab_pages_has( eab_mod( 'pricing_note' ) ) ) : ?>
				<p class="pg-note pricing-note"><span class="tile tile--sky" aria-hidden="true"><?php echo eab_icon( 'tag' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span><span><?php echo esc_html( eab_mod( 'pricing_note' ) ); ?></span></p>
			<?php endif; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<?php /* ตารางเปรียบเทียบ · จอแคบเรียงเป็นการ์ดทีละหัวข้อ */ ?>
<?php
if ( $eab_show_cmp ) :
	$eab_table = array();
	foreach ( $eab_rows as $eab_row ) {
		$eab_table[] = array_map( 'trim', explode( '|', $eab_row ) );
	}
	$eab_head     = array_shift( $eab_table );
	$eab_feat_col = array();
	foreach ( $eab_head as $idx => $eab_cell ) {
		if ( $idx > 0 && in_array( $eab_cell, $eab_featured_names, true ) ) {
			$eab_feat_col[] = $idx;
		}
	}
	?>
	<section class="pg-sec compare-section" id="compare">
		<div class="container">
			<div class="glass-panel pg-panel">
				<?php eab_pages_sec_head( eab_mod( 'compare_kicker' ), eab_mod( 'compare_title' ) ); ?>
				<div class="cmp-wrap reveal">
					<table class="cmp-table">
						<thead>
							<tr>
								<?php foreach ( $eab_head as $idx => $eab_cell ) : ?>
									<th scope="col"<?php echo 0 === $idx ? ' class="cmp-rowhead"' : ( in_array( $idx, $eab_feat_col, true ) ? ' class="is-featured"' : '' ); ?>><?php echo esc_html( $eab_cell ); ?></th>
								<?php endforeach; ?>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $eab_table as $eab_trow ) : ?>
								<tr>
									<?php
									foreach ( $eab_trow as $idx => $eab_cell ) :
										$eab_yes = '✓' === $eab_cell;
										$eab_no  = '✗' === $eab_cell || 'x' === strtolower( $eab_cell );
										$eab_lbl = isset( $eab_head[ $idx ] ) ? $eab_head[ $idx ] : '';
										if ( 0 === $idx ) :
											?>
											<th scope="row" class="cmp-rowhead"><?php echo esc_html( $eab_cell ); ?></th>
											<?php
											continue;
										endif;
										$eab_td = $eab_yes ? 'cell-yes' : ( $eab_no ? 'cell-no' : 'cell-text' );
										if ( in_array( $idx, $eab_feat_col, true ) ) {
											$eab_td .= ' is-featured';
										}
										?>
										<td class="<?php echo esc_attr( $eab_td ); ?>" data-label="<?php echo esc_attr( $eab_lbl ); ?>">
											<?php
											if ( $eab_yes ) {
												echo '<span class="cmp-mark cmp-mark--yes" aria-hidden="true">' . eab_icon( 'check', 'icon' ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput
												echo '<span class="sr-only">' . esc_html( eab_mod( 'compare_yes_label' ) ) . '</span>';
											} elseif ( $eab_no ) {
												echo '<span class="cmp-mark cmp-mark--no" aria-hidden="true">' . eab_icon( 'x', 'icon' ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput
												echo '<span class="sr-only">' . esc_html( eab_mod( 'compare_no_label' ) ) . '</span>';
											} else {
												echo esc_html( $eab_cell );
											}
											?>
										</td>
									<?php endforeach; ?>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php /* สิทธิ์ใช้งาน / VPS */ ?>
<?php
$eab_license = trim( (string) eab_mod( 'pricing_license_rows' ) );
$eab_points  = eab_lines( eab_mod( 'pricing_license_points' ) );
$eab_steps   = eab_pages_pairs( eab_mod( 'pricing_order_steps' ) );
if ( '' !== $eab_license || $eab_points ) :
	?>
	<section class="pg-sec pricing-terms" id="license">
		<div class="container">
			<div class="glass-panel pg-panel pricing-license">
				<?php eab_pages_sec_head( eab_mod( 'pricing_license_kicker' ), eab_mod( 'pricing_license_title' ), eab_mod( 'pricing_license_text' ) ); ?>
				<?php if ( '' !== $eab_license ) : ?>
					<div class="pricing-license-table reveal">
						<?php echo str_replace( 'table-wrap table-wrap--stack', 'table-wrap table-wrap--stack table-wrap--cards', eab_rich_text( $eab_license ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside eab_rich_text ?>
					</div>
				<?php endif; ?>
				<?php if ( $eab_points ) : ?>
					<ul class="pricing-points">
						<?php foreach ( $eab_points as $eab_point ) : ?>
							<li class="reveal"><span class="tile tile--gold" aria-hidden="true"><?php echo eab_icon( 'shield' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span><span><?php echo eab_rich_inline( $eab_point ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></span></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php /* ขั้นตอนสั่งซื้อ · การ์ดเลขทอง + ปุ่มติดต่อ */ ?>
<?php if ( $eab_steps ) : ?>
	<section class="pg-sec pricing-order-sec" id="order">
		<div class="container">
			<div class="glass-panel pg-panel pricing-order">
				<?php eab_pages_sec_head( eab_mod( 'pricing_order_kicker' ), eab_mod( 'pricing_order_title' ), eab_mod( 'pricing_order_sub' ) ); ?>
				<ol class="order-flow order-flow--<?php echo esc_attr( (string) min( 5, count( $eab_steps ) ) ); ?>">
					<?php foreach ( $eab_steps as $eab_n => $eab_step ) : ?>
						<li class="order-step reveal">
							<span class="order-num" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $eab_n + 1 ) ); ?></span>
							<h3><?php echo eab_text( $eab_step[0] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></h3>
							<?php if ( '' !== $eab_step[1] ) : ?>
								<p><?php echo eab_rich_inline( $eab_step[1] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></p>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ol>
				<div class="order-action">
					<?php
					eab_contact_button(
						array(
							'text'  => eab_mod( 'pricing_order_btn_text' ),
							'class' => 'btn btn-fire',
							'pos'   => 'pricing-order',
						)
					);
					?>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php
eab_pages_enable_table_cards();
eab_pages_longform();
?>

<?php eab_line_cta( eab_mod( 'pricing_cta_title' ), eab_mod( 'pricing_cta_text' ) ); ?>

</main>

<?php
get_footer();
