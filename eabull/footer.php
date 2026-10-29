<?php
/**
 * Footer · กระจกสว่างในกรอบเว็บ (Glass Sky · docs/design.md)
 * 0) แผงติดต่อ (เฉพาะหน้าที่ยังไม่ได้พิมพ์ผ่าน eab_line_cta() เช่น หน้าแรก) · inc/modules/chrome.php
 * 1) คอลัมน์แบรนด์ (โลโก้ ข้อความสั้น โซเชียล) · คู่มือ · หน้าในเว็บ · เอกสาร
 * 2) คำเตือนความเสี่ยงแบบสั้นในกล่องทองอ่อน + ลิงก์ไปหน้าประกาศความเสี่ยง (เจ้าของขอให้สั้น 6 ต.ค. 2026 · ฉบับเต็มอยู่หน้าแรก /go บทความ และ /risk-disclosure/)
 * 3) แถวลิขสิทธิ์ · ลิงก์นโยบาย · ตั้งค่าคุกกี้ · กลับขึ้นด้านบน
 * ปิดกรอบเว็บ .site-frame (เปิดใน header.php) แล้วจึงพิมพ์แถบล่างมือถือและปุ่ม LINE ลอย (position: fixed)
 * การ์ดความยินยอมคุกกี้พิมพ์ผ่าน wp_footer (inc/modules/consent.php) ไม่ใช่ไฟล์นี้
 *
 * @package eabull
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'eab_chrome_dock' ) ) {
	echo "</div>\n";
	wp_footer();
	echo "</body>\n</html>\n";
	return;
}

$eab_guides = eab_chrome_guide_items();
$eab_index  = eab_chrome_index_items();
$eab_docs   = eab_chrome_doc_items();
$eab_legal  = eab_chrome_legal_items();
/* ลิงก์เปิดการ์ดคุกกี้ · ชื่อลิงก์ตั้งที่หมวดคุกกี้ (inc/modules/consent.php) */
$eab_cookie = ( function_exists( 'eab_consent_link' ) && '' !== trim( (string) eab_mod( 'consent_link_label' ) ) ) ? eab_consent_link( array( 'echo' => false ) ) : '';
$eab_about  = trim( (string) eab_mod( 'footer_status_text' ) );
$eab_points = eab_lines( eab_mod( 'footer_about_points' ) );
$eab_top    = trim( (string) eab_mod( 'footer_backtop_text' ) );
$eab_risk   = trim( (string) eab_mod( 'footer_risk_short' ) );
$eab_risk   = '' !== $eab_risk ? $eab_risk : trim( (string) eab_mod( 'risk_text' ) );
$eab_rlink  = function_exists( 'eab_published_page_url' ) ? eab_published_page_url( 'risk-disclosure' ) : '';
$eab_rmore  = trim( (string) eab_mod( 'footer_risk_more' ) );
$eab_copy   = trim( (string) eab_mod( 'footer_copyright_text' ) );
$eab_cols   = array(
	array( eab_mod( 'nav_guide_label' ), $eab_guides ),
	array( eab_mod( 'footer_index_title' ), $eab_index ),
	array( eab_mod( 'footer_docs_title' ), $eab_docs ),
);
?>

<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) : ?>
<footer class="site-footer site-foot" id="site-footer">

	<?php eab_chrome_footer_contact(); ?>

	<div class="foot glass-panel">
		<div class="foot-top">
			<div class="foot-brand">
				<?php eab_chrome_brand( 'brand--foot' ); ?>
				<?php if ( '' !== $eab_about ) : ?>
					<p class="foot-about"><?php echo esc_html( $eab_about ); ?></p>
				<?php endif; ?>
				<?php if ( $eab_points ) : ?>
					<ul class="foot-points">
						<?php foreach ( $eab_points as $eab_point ) : ?>
							<li><?php echo eab_icon( 'check', 'icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo esc_html( $eab_point ); ?></span></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
				<?php eab_chrome_social_row( 'foot-social' ); ?>
			</div>

			<nav class="foot-cols" aria-label="ลิงก์ท้ายเว็บ">
				<?php
				foreach ( $eab_cols as $eab_col ) :
					if ( empty( $eab_col[1] ) ) {
						continue;
					}
					?>
					<div class="foot-col">
						<?php if ( '' !== trim( (string) $eab_col[0] ) ) : ?>
							<h2 class="foot-col-title"><?php echo esc_html( $eab_col[0] ); ?></h2>
						<?php endif; ?>
						<ul class="foot-list">
							<?php foreach ( $eab_col[1] as $eab_entry ) : ?>
								<li><a href="<?php echo esc_url( $eab_entry[1] ); ?>"><?php echo esc_html( $eab_entry[0] ); ?></a></li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endforeach; ?>
			</nav>
		</div>

		<?php if ( '' !== $eab_risk ) : ?>
			<div class="foot-risk" role="note">
				<span class="tile tile--gold" aria-hidden="true"><?php echo eab_icon( 'warn', 'icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				<p><?php echo esc_html( $eab_risk ); ?><?php if ( $eab_rlink && '' !== $eab_rmore ) : ?> <a class="foot-risk-more" href="<?php echo esc_url( $eab_rlink ); ?>"><?php echo esc_html( $eab_rmore ); ?></a><?php endif; ?></p>
			</div>
		<?php endif; ?>

		<div class="foot-bar">
			<p class="foot-copy">&copy; <?php echo esc_html( wp_date( 'Y', null, new DateTimeZone( 'Asia/Bangkok' ) ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?><?php echo '' !== $eab_copy ? ' · ' . esc_html( $eab_copy ) : ''; ?></p>
			<?php if ( $eab_legal || '' !== $eab_cookie || '' !== $eab_top ) : ?>
				<ul class="foot-legal">
					<?php foreach ( $eab_legal as $eab_entry ) : ?>
						<li><a href="<?php echo esc_url( $eab_entry[1] ); ?>"><?php echo esc_html( $eab_entry[0] ); ?></a></li>
					<?php endforeach; ?>
					<?php if ( '' !== $eab_cookie ) : ?>
						<li><?php echo $eab_cookie; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside eab_consent_link ?></li>
					<?php endif; ?>
					<?php if ( '' !== $eab_top ) : ?>
						<li><a class="foot-top-link" href="#top"><span><?php echo esc_html( $eab_top ); ?></span><?php echo eab_icon( 'arrow', 'icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a></li>
					<?php endif; ?>
				</ul>
			<?php endif; ?>
		</div>
	</div>
</footer>
<?php endif; ?>

</div><!-- /.site-frame -->

<?php if ( eab_mod( 'show_mobile_nav' ) ) : ?>
	<?php $eab_dock = eab_chrome_dock(); ?>
	<?php if ( $eab_dock['items'] ) : ?>
<nav class="mobile-app-nav dock" aria-label="เมนูลัดมือถือ">
		<?php
		foreach ( $eab_dock['items'] as $eab_slot => $eab_item ) :
			$eab_is_action = ! empty( $eab_item['action'] );
			$eab_is_here   = ! $eab_is_action && (int) $eab_slot === $eab_dock['active'];
			$eab_cls       = 'dock-key';
			if ( $eab_is_action ) {
				$eab_cls .= ' is-action' . ( ! empty( $eab_item['line'] ) ? ' is-line' : '' );
			} elseif ( $eab_is_here ) {
				$eab_cls .= ' is-active';
			}
			?>
	<a class="<?php echo esc_attr( $eab_cls ); ?>" href="<?php echo esc_url( $eab_item['url'] ); ?>" data-slot="<?php echo esc_attr( (string) $eab_slot ); ?>"<?php echo $eab_is_here ? ' aria-current="page"' : ''; ?><?php echo ! empty( $eab_item['line'] ) ? ' target="_blank" rel="noopener"' : ''; ?><?php echo $eab_is_action ? ' data-line-pos="dock"' : ''; ?>>
		<span class="dock-cap"><?php echo eab_icon( $eab_item['icon'], 'icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
		<span class="dock-label"><?php echo esc_html( $eab_item['label'] ); ?></span>
	</a>
		<?php endforeach; ?>
</nav>
	<?php endif; ?>
<?php endif; ?>

<?php if ( eab_has_line_url() && eab_mod( 'show_float_line' ) ) : ?>
<a class="float-line" href="<?php echo esc_url( trim( (string) eab_mod( 'line_url' ) ) ); ?>" target="_blank" rel="noopener" data-line-pos="float">
	<span class="fab-core" aria-hidden="true"><?php echo eab_icon( 'line' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
	<span class="float-line-text"><?php echo esc_html( eab_mod( 'float_line_text' ) ); ?></span>
</a>
<?php endif; ?>

<?php if ( eab_has_line_url() && ! eab_mod( 'show_float_line' ) ) : ?>
<a class="line-fab" href="<?php echo esc_url( trim( (string) eab_mod( 'line_url' ) ) ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( eab_mod( 'float_line_text' ) ); ?>" data-line-pos="fab">
	<span class="fab-core" aria-hidden="true"><?php echo eab_icon( 'line' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
</a>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
