<?php
/**
 * Template Name: EA BULL · หน้ารวมลิงก์ (/go)
 *
 * Standalone link page (mockup dev/mockup/_src/pages/go.html): no header, footer, dock or
 * floating button · noindex via inc/seo.php · the cookie card still prints through wp_body_open.
 * One narrow card on every screen, stacked like EA WING's /go (owner 29 Oct 2026): brand, contact,
 * 6 steps, info links, socials, policy links. Texts and links: Customizer sections 30) and 31) (inc/modules/go.php).
 *
 * @package eabull
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( have_posts() ) {
	the_post();
}

$eab_go_title = eab_go_mod( 'go_title' );
if ( '' === $eab_go_title ) {
	$eab_go_title = get_the_title();
}
$eab_go_sub      = eab_go_mod( 'go_sub' );
$eab_go_brand    = function_exists( 'eab_chrome_mod' ) ? eab_chrome_mod( 'chrome_brand_alt' ) : 'EA BULL';
$eab_go_help     = eab_go_mod( 'go_help_title' );
$eab_go_line     = eab_go_line_button( eab_go_mod( 'go_line_label' ), 'go-top', 'btn--block' );
$eab_go_openchat = '';
if ( function_exists( 'eab_chrome_openchat_link' ) ) {
	$eab_go_openchat = eab_go_btn( 'btn btn--openchat btn--block', eab_go_mod( 'go_openchat_label' ), eab_chrome_openchat_link( 'go-openchat' ), 'users' );
}
$eab_go_info_title = eab_go_mod( 'go_info_title' );
$eab_go_info       = eab_go_info_buttons();
$eab_go_socials    = function_exists( 'eab_chrome_social_icons' ) ? eab_chrome_social_icons( 'go-social' ) : '';

$eab_go_steps       = eab_go_steps();
$eab_go_steps_title = str_replace( '{n}', (string) count( $eab_go_steps ), eab_go_mod( 'go_steps_title' ) );
$eab_go_steps_sub   = eab_go_mod( 'go_steps_sub' );
$eab_go_risk        = eab_go_mod( 'go_risk_text' );

$eab_go_home   = eab_go_mod( 'go_home_label' );
$eab_go_legal  = eab_go_legal_links();
$eab_go_cookie = function_exists( 'eab_consent_link' ) ? eab_consent_link(
	array(
		'tag'   => 'button',
		'class' => 'linkish',
		'echo'  => false,
	)
) : '';
$eab_go_skip   = function_exists( 'eab_chrome_mod' ) ? eab_chrome_mod( 'chrome_skip_text' ) : '';

add_filter( 'body_class', 'eab_go_body_class', 99 );
?><!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<script>document.documentElement.className = document.documentElement.className.replace( /\bno-js\b/, 'js' );</script>
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php if ( '' !== $eab_go_skip ) : ?>
<a class="skip" href="#main"><?php echo esc_html( $eab_go_skip ); ?></a>
<?php endif; ?>
<main id="main">
<div class="go">
	<div class="gocard cut">
		<header class="gocard__brand">
			<img src="<?php echo esc_url( eab_go_logo_url() ); ?>" width="640" height="571" alt="" fetchpriority="high" decoding="async">
			<img class="go-word" src="<?php echo esc_url( eab_wordmark_url( 'dark' ) ); ?>" width="720" height="100" alt="<?php echo esc_attr( $eab_go_brand ); ?>" decoding="async">
			<h1><?php echo esc_html( $eab_go_title ); ?></h1>
			<?php if ( '' !== $eab_go_sub ) : ?>
				<p><?php echo nl2br( esc_html( $eab_go_sub ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped ?></p>
			<?php endif; ?>
		</header>

		<?php if ( '' !== $eab_go_line || '' !== $eab_go_openchat ) : ?>
			<section class="gocard__sec">
				<?php if ( '' !== $eab_go_help ) : ?>
					<h2 class="gocard__h"><?php echo esc_html( $eab_go_help ); ?></h2>
				<?php endif; ?>
				<div class="gocard__stack"><?php echo $eab_go_line . $eab_go_openchat; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in eab_go_btn ?></div>
			</section>
		<?php endif; ?>

		<?php if ( $eab_go_steps ) : ?>
			<section class="gocard__sec">
				<?php if ( '' !== $eab_go_steps_title ) : ?>
					<h2 class="gocard__h"><?php echo esc_html( $eab_go_steps_title ); ?></h2>
				<?php endif; ?>
				<?php if ( '' !== $eab_go_steps_sub ) : ?>
					<p class="gocard__sub"><?php echo esc_html( $eab_go_steps_sub ); ?></p>
				<?php endif; ?>
				<ol class="gosteps">
					<?php foreach ( $eab_go_steps as $eab_go_i => $eab_go_step ) : ?>
						<li class="gostep">
							<span class="gostep__n"><?php echo esc_html( (string) ( $eab_go_i + 1 ) ); ?></span>
							<div class="gostep__body">
								<h3><?php echo esc_html( $eab_go_step['title'] ); ?><?php if ( '' !== $eab_go_step['badge'] ) : ?> <span class="badge badge--acc"><?php echo esc_html( $eab_go_step['badge'] ); ?></span><?php endif; ?></h3>
								<?php if ( '' !== $eab_go_step['desc'] ) : ?>
									<p><?php echo esc_html( $eab_go_step['desc'] ); ?></p>
								<?php endif; ?>
								<?php echo $eab_go_step['body']; // phpcs:ignore WordPress.Security.EscapeOutput -- built with escaping in eab_go_steps() ?>
							</div>
						</li>
					<?php endforeach; ?>
				</ol>
			</section>
		<?php endif; ?>

		<?php if ( '' !== $eab_go_info ) : ?>
			<section class="gocard__sec">
				<?php if ( '' !== $eab_go_info_title ) : ?>
					<h2 class="gocard__h"><?php echo esc_html( $eab_go_info_title ); ?></h2>
				<?php endif; ?>
				<div class="golinks"><?php echo $eab_go_info; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in eab_go_info_buttons ?></div>
			</section>
		<?php endif; ?>

		<?php if ( '' !== $eab_go_socials ) : ?>
			<div class="gocard__social"><?php echo $eab_go_socials; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in eab_chrome_social_icons ?></div>
		<?php endif; ?>

		<?php if ( '' !== $eab_go_risk ) : ?>
			<p class="note note--warn mt-16"><?php echo eab_icon( 'alert' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo eab_go_rich( $eab_go_risk ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in eab_go_rich ?></span></p>
		<?php endif; ?>
		<?php if ( '' !== $eab_go_home || $eab_go_legal || '' !== $eab_go_cookie ) : ?>
			<div class="go__legal">
				<?php if ( '' !== $eab_go_home ) : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( $eab_go_home ); ?></a>
				<?php endif; ?>
				<?php foreach ( $eab_go_legal as $eab_go_link ) : ?>
					<a href="<?php echo esc_url( $eab_go_link['url'] ); ?>"><?php echo esc_html( $eab_go_link['title'] ); ?></a>
				<?php endforeach; ?>
				<?php echo $eab_go_cookie; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in eab_consent_link ?>
			</div>
		<?php endif; ?>
	</div>
</div>
</main>
<div class="toast" id="toast" role="status" aria-live="polite"></div>
<?php wp_footer(); ?>
</body>
</html>
