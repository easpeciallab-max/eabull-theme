<?php
/**
 * Footer (mockup dev/mockup/_src/partials/footer.html, dock.html, fab.html)
 * 1) yellow contact band, when the template did not print one with eab_line_cta()
 * 2) footer.ft: brand + socials · three link columns · short risk box linking /risk-disclosure/ ·
 *    copyright row with policy links, cookie settings and back to top
 * 3) mobile dock, floating contact button + quick sheet, toast for data-wait links
 * The cookie card is printed by inc/modules/consent.php (wp_body_open).
 *
 * @package eabull
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'eab_chrome_footer_columns' ) ) {
	wp_footer();
	echo "</body>\n</html>\n";
	return;
}

eab_chrome_footer_contact();

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) :
	$eab_about  = eab_chrome_mod( 'footer_about' );
	$eab_cols   = eab_chrome_footer_columns();
	$eab_risk   = eab_chrome_mod( 'footer_risk_short' );
	$eab_rmore  = eab_chrome_mod( 'footer_risk_more' );
	$eab_rlink  = eab_chrome_page_url( 'risk-disclosure' );
	$eab_legal  = eab_chrome_links( 'footer_legal_items' );
	$eab_brand  = eab_chrome_mod( 'chrome_brand_alt' );
	$eab_copy   = eab_chrome_mod( 'footer_copyright_text' );
	$eab_top    = eab_chrome_mod( 'footer_backtop_text' );
	$eab_cookie = function_exists( 'eab_consent_link' ) ? eab_consent_link(
		array(
			'tag'   => 'button',
			'class' => 'linkish',
			'echo'  => false,
		)
	) : '';
	?>
<footer class="ft">
	<div class="wrap">
		<div class="ft__top">
			<div class="ft__brand">
				<img src="<?php echo esc_url( eab_wordmark_url( 'dark' ) ); ?>" width="720" height="100" alt="<?php echo esc_attr( $eab_brand ); ?>" loading="lazy" decoding="async">
				<?php if ( '' !== $eab_about ) : ?>
					<p><?php echo esc_html( $eab_about ); ?></p>
				<?php endif; ?>
				<?php echo eab_chrome_social_icons( 'footer-social' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?>
			</div>
			<?php if ( $eab_cols ) : ?>
				<div class="ft__cols">
					<?php foreach ( $eab_cols as $eab_col ) : ?>
						<div>
							<?php if ( '' !== $eab_col['title'] ) : ?>
								<h2><?php echo esc_html( $eab_col['title'] ); ?></h2>
							<?php endif; ?>
							<ul>
								<?php foreach ( $eab_col['items'] as $eab_link ) : ?>
									<li><a href="<?php echo esc_url( $eab_link['url'] ); ?>"><?php echo eab_chrome_nocaps( $eab_link['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></a></li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php if ( '' !== $eab_risk ) : ?>
			<div class="ft__risk">
				<?php echo eab_icon( 'alert' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<p><?php echo esc_html( $eab_risk ); ?><?php if ( '' !== $eab_rlink && '' !== $eab_rmore ) : ?> <a href="<?php echo esc_url( $eab_rlink ); ?>"><?php echo esc_html( $eab_rmore ); ?></a><?php endif; ?></p>
			</div>
		<?php endif; ?>
		<div class="ft__bottom">
			<span>&copy; <?php echo esc_html( wp_date( 'Y', null, new DateTimeZone( 'Asia/Bangkok' ) ) ); ?> <?php echo esc_html( $eab_brand ); ?><?php echo '' !== $eab_copy ? ' · ' . esc_html( $eab_copy ) : ''; ?></span>
			<?php foreach ( $eab_legal as $eab_link ) : ?>
				<a href="<?php echo esc_url( $eab_link['url'] ); ?>"><?php echo esc_html( $eab_link['title'] ); ?></a>
			<?php endforeach; ?>
			<?php echo $eab_cookie; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in eab_consent_link ?>
			<?php if ( '' !== $eab_top ) : ?>
				<a href="#top" class="ft__top-link"><?php echo esc_html( $eab_top ); ?> <?php echo eab_icon( 'arrow-up' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
			<?php endif; ?>
		</div>
	</div>
</footer>
<?php endif; ?>
<?php
eab_chrome_dock();
eab_chrome_fab();
?>
<div class="toast" id="toast" role="status" aria-live="polite"></div>
<?php wp_footer(); ?>
</body>
</html>
