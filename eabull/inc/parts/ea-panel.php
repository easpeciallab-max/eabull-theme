<?php
/**
 * แผงควบคุม EA จำลอง (ภาพหลักของ Hero เมื่อยังไม่ได้ใส่ภาพ Hero) · หน้าตาตามต้นแบบ dev/mockup (.ea-panel)
 * HTML ล้วน · โลโก้ + สถานะ · กราฟแท่งเทียนตกแต่ง (รูปทรงเท่านั้น ไม่มีราคา ไม่มีตัวเลข) · แถวเมนู
 * ไม่มีตัวเลขผลการเทรด ราคา หรือเปอร์เซ็นต์ใด ๆ
 * ข้อความแก้ได้ที่ ปรับแต่ง → 2) หน้าแรก · Hero (แผงจำลอง · ...) · คำบรรยายใต้ภาพพิมพ์ใน front-page.php
 *
 * @package eabull
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$eab_panel_title   = trim( (string) eab_mod( 'hero_panel_title' ) );
$eab_panel_status  = trim( (string) eab_mod( 'hero_panel_status' ) );
$eab_panel_caption = trim( (string) eab_mod( 'hero_panel_caption' ) );
$eab_panel_rows    = function_exists( 'eab_home_pairs' ) ? eab_home_pairs( eab_mod( 'hero_panel_fields' ), false, 4 ) : array();
$eab_row_icons     = array( 'chart', 'shield', 'sliders', 'gear' );
$eab_panel_label   = trim( $eab_panel_title . ' · ' . $eab_panel_caption, ' ·' );

/* แท่งเทียนตกแต่ง: [ x, บน, ล่าง, ขึ้น? ] · รูปทรงไล่ขึ้นแบบต้นแบบ ไม่ได้มาจากข้อมูลราคา */
$eab_candles = array(
	array( 14, 70, 82, 0 ), array( 30, 64, 80, 1 ), array( 46, 62, 72, 1 ), array( 62, 66, 74, 0 ),
	array( 78, 56, 70, 1 ), array( 94, 52, 64, 1 ), array( 110, 58, 66, 0 ), array( 126, 44, 60, 1 ),
	array( 142, 42, 52, 1 ), array( 158, 48, 56, 0 ), array( 174, 36, 50, 1 ), array( 190, 34, 44, 1 ),
	array( 206, 38, 46, 0 ), array( 222, 26, 40, 1 ), array( 238, 24, 34, 1 ), array( 254, 28, 36, 0 ),
	array( 270, 16, 30, 1 ), array( 286, 14, 24, 1 ), array( 302, 10, 20, 1 ),
);
?>
<div class="ea-panel" role="img"<?php echo '' !== $eab_panel_label ? ' aria-label="' . esc_attr( $eab_panel_label ) . '"' : ''; ?>>
	<div class="ea-head">
		<img class="ea-logo" src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/brand/eabull-icon-96.webp' ); ?>" alt="" width="48" height="48" decoding="async">
		<div class="ea-name">
			<?php if ( '' !== $eab_panel_title ) : ?>
				<strong><?php echo esc_html( $eab_panel_title ); ?></strong>
			<?php endif; ?>
			<?php if ( '' !== $eab_panel_status ) : ?>
				<span class="ea-status"><?php echo esc_html( $eab_panel_status ); ?></span>
			<?php endif; ?>
		</div>
	</div>

	<div class="ea-chart">
		<svg viewBox="0 0 320 100" aria-hidden="true" focusable="false">
			<path class="ea-grid" d="M0 82H320M0 54H320M0 26H320"/>
			<g class="ea-candles">
				<?php foreach ( $eab_candles as $eab_c ) : ?>
					<path class="<?php echo $eab_c[3] ? 'up' : 'down'; ?>" d="<?php echo esc_attr( 'M' . (int) $eab_c[0] . ' ' . (int) $eab_c[2] . 'V' . (int) $eab_c[1] ); ?>"/>
				<?php endforeach; ?>
			</g>
			<path class="ea-trend" d="M10 80C80 68 120 58 170 46S260 26 310 12"/>
		</svg>
	</div>

	<?php if ( ! empty( $eab_panel_rows ) ) : ?>
		<div class="ea-rows">
			<?php foreach ( $eab_panel_rows as $eab_ri => $eab_row ) : ?>
				<div class="ea-row">
					<?php echo eab_home_icon( $eab_row_icons[ $eab_ri % 4 ], 'icon ea-row-ic' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?>
					<span class="ea-row-label"><?php echo esc_html( $eab_row[0] ); ?></span>
					<?php if ( 0 === $eab_ri ) : ?>
						<i class="ea-toggle"></i>
					<?php elseif ( '' !== $eab_row[1] ) : ?>
						<span class="ea-row-value"><?php echo esc_html( $eab_row[1] ); ?></span>
					<?php else : ?>
						<?php echo eab_home_icon( 'chev', 'icon ea-chev' ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
