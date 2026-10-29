<?php
/**
 * Template Name: EA BULL · หน้ารวมลิงก์ (/go)
 *
 * หน้าลิงก์รวม (link-in-bio / ปลายทางโฆษณา) แบบหน้าเดี่ยว: ไม่มีเมนู ท้ายเว็บ แถบล่างมือถือ หรือปุ่มลอย · noindex (inc/seo.php)
 * การ์ดคุกกี้ยังแสดงตามปกติเพราะพิมพ์ผ่าน wp_footer()
 * หน้าตา Glass Sky: การ์ดกระจกกลางจอ (กว้างไม่เกิน 440px) บนพื้นฟ้า + ลูกแก้ว + เส้นโค้งปีกจาง ๆ · CSS ใน assets/css/go.css
 * ลำดับ: ส่วนหัว → ติดต่อทีม (LINE ทอง · OpenChat กรมท่า) → ขั้นตอนเริ่มใช้งาน (เลขทอง 01.. เรียงใหม่เมื่อซ่อนขั้น)
 *        → ข้อมูลก่อนเริ่ม → โซเชียล → คำเตือนความเสี่ยง → เอกสาร
 * ข้อความ/ปุ่ม/ลิงก์ทั้งหมดแก้ได้ที่ ปรับแต่ง → หมวด "30) หน้า /go" และ "31) หน้า /go · ขั้นตอนเริ่มใช้งาน" (ตัวช่วยอยู่ใน inc/modules/go.php)
 *
 * @package eabull
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( have_posts() ) {
	the_post();
}

/* คำไทยที่ไม่ควรถูกตัดกลางคำในการ์ดแคบ (มีผลเฉพาะหน้านี้) */
add_filter( 'eab_keep_words', 'eab_go_keep_words' );

$eab_go_title = eab_go_mod( 'go_title' );
if ( '' === $eab_go_title ) {
	$eab_go_title = get_the_title();
}
$eab_go_tagline = eab_go_mod( 'go_sub' );
$eab_go_badges  = eab_lines( eab_mod( 'go_badges' ) );

/* กลุ่มติดต่อทีม · LINE ผ่าน eab_contact_button() (ไม่มีลิงก์ = ไม่มีปุ่ม, แอดมินเห็นคำแนะนำ) */
$eab_go_line_label = eab_go_mod( 'go_line_label' );
if ( '' === $eab_go_line_label ) {
	$eab_go_line_label = eab_go_mod( 'footer_line_text' );
}
$eab_go_line_html = '';
if ( '' !== $eab_go_line_label ) {
	ob_start();
	eab_contact_button(
		array(
			'text'  => $eab_go_line_label,
			'class' => 'lh-btn lh-btn-line',
			'pos'   => 'go-top',
			'arrow' => false,
			'icon'  => true,
		)
	);
	$eab_go_line_html = trim( (string) ob_get_clean() );
}

$eab_go_openchat_url   = eab_go_mod( 'line_openchat_url' );
$eab_go_openchat_label = eab_go_mod( 'go_openchat_label' );
if ( '' === $eab_go_openchat_label ) {
	$eab_go_openchat_label = eab_go_mod( 'line_openchat_text' );
}
$eab_go_show_openchat = '' !== $eab_go_openchat_url && '#' !== $eab_go_openchat_url && '' !== $eab_go_openchat_label;
$eab_go_help_title    = eab_go_mod( 'go_help_title' );

/* ขั้นตอน · ขั้นที่ว่างถูกตัดออกแล้ว เลขที่แสดง = ลำดับที่เหลือ */
$eab_go_steps       = eab_go_steps();
$eab_go_steps_title = str_replace( '{n}', (string) count( $eab_go_steps ), eab_go_mod( 'go_steps_title' ) );
$eab_go_step_word   = eab_go_mod( 'go_step_word' );

$eab_go_info_title = eab_go_mod( 'go_info_title' );
$eab_go_info_html  = eab_go_info_buttons();
$eab_go_socials    = eab_go_socials();

/* เนื้อหาเพจจากตัวแก้ไข (ปิดไว้เป็นค่าเริ่มต้น) */
$eab_go_doc = '';
if ( eab_mod( 'go_show_doc' ) ) {
	$eab_go_doc = trim( (string) apply_filters( 'the_content', get_the_content() ) );
}

/* ท้ายการ์ด: กลับหน้าแรก · เอกสารที่เผยแพร่แล้ว · ตั้งค่าคุกกี้ */
$eab_go_home_label = eab_go_mod( 'go_home_label' );
$eab_go_legal      = eab_go_legal_links();
$eab_go_cookie     = '';
$eab_go_cookie_lbl = eab_go_mod( 'consent_link_label' );
if ( '' !== $eab_go_cookie_lbl ) {
	if ( function_exists( 'eab_consent_link' ) ) {
		$eab_go_cookie = eab_consent_link(
			array(
				'class' => 'lh-legal-cookie',
				'echo'  => false,
			)
		);
	} else {
		/* สำรองเมื่อไม่มีโมดูลคุกกี้: ลิงก์เดิมที่ consent.js ดักคลิกเพื่อเปิดการ์ด */
		$eab_go_cookie = '<a class="cookie-reopen lh-legal-cookie" href="#cookie-settings">' . esc_html( $eab_go_cookie_lbl ) . '</a>';
	}
}

add_filter( 'body_class', 'eab_go_body_class', 99 );
?><!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<script>document.documentElement.className = document.documentElement.className.replace( /\bno-js\b/, 'js' );</script>
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<?php wp_head(); ?>
</head>
<body <?php body_class( 'link-hub-page' ); ?>>
<?php wp_body_open(); ?>

<main class="link-hub" id="main">
	<?php /* พื้นหลังตกแต่ง: เส้นโค้งปีกจาง ๆ + ลูกแก้ว (ไม่มีความหมาย ซ่อนจากโปรแกรมอ่านจอ) */ ?>
	<svg class="lh-wing-lines" viewBox="0 0 1440 900" preserveAspectRatio="xMidYMid slice" aria-hidden="true" focusable="false">
		<defs>
			<linearGradient id="lh-wl" x1="0" x2="1">
				<stop offset="0" stop-color="#FFFFFF" stop-opacity="0"/>
				<stop offset=".5" stop-color="#FFFFFF" stop-opacity=".9"/>
				<stop offset="1" stop-color="#FDD023" stop-opacity=".35"/>
			</linearGradient>
		</defs>
		<path d="M-40 640C260 520 520 610 760 520S1180 300 1480 340" fill="none" stroke="url(#lh-wl)" stroke-width="2"/>
		<path d="M-40 690C280 580 560 660 800 570S1200 360 1480 400" fill="none" stroke="url(#lh-wl)" stroke-width="1.4"/>
		<path d="M-40 740C300 640 600 710 840 620S1220 420 1480 460" fill="none" stroke="url(#lh-wl)" stroke-width="1"/>
	</svg>
	<span class="orb lh-orb lh-orb--1" aria-hidden="true"></span>
	<span class="orb lh-orb lh-orb--2" aria-hidden="true"></span>
	<span class="orb lh-orb lh-orb--3" aria-hidden="true"></span>

	<div class="lh-card">

		<header class="lh-brand">
			<div class="lh-logo">
				<img src="<?php echo esc_url( eab_go_logo_url() ); ?>" alt="<?php echo esc_attr( $eab_go_title ); ?>" width="88" height="88" decoding="async" fetchpriority="high">
			</div>

			<h1 class="lh-title"><?php echo eab_text( $eab_go_title ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in eab_text ?></h1>

			<?php if ( '' !== $eab_go_tagline ) : ?>
				<p class="lh-tagline"><?php echo nl2br( eab_text( $eab_go_tagline ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in eab_text ?></p>
			<?php endif; ?>

			<?php if ( $eab_go_badges ) : ?>
				<ul class="lh-badges">
					<?php foreach ( $eab_go_badges as $eab_go_badge ) : ?>
						<li><?php echo esc_html( $eab_go_badge ); ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</header>

		<?php if ( '' !== $eab_go_line_html || $eab_go_show_openchat ) : ?>
			<section class="lh-group lh-group--help"<?php echo '' !== $eab_go_help_title ? ' aria-labelledby="lh-help-title"' : ''; ?>>
				<?php if ( '' !== $eab_go_help_title ) : ?>
					<h2 class="lh-section-title" id="lh-help-title"><?php echo eab_text( $eab_go_help_title ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in eab_text ?></h2>
				<?php endif; ?>

				<?php echo $eab_go_line_html; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in eab_contact_button ?>

				<?php if ( $eab_go_show_openchat ) : ?>
					<a class="lh-btn lh-btn-openchat" href="<?php echo esc_url( $eab_go_openchat_url ); ?>" target="_blank" rel="noopener" data-line-pos="go-openchat" data-contact="openchat">
						<span class="lh-ic"><?php echo eab_icon( 'users' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<span class="lh-lbl"><?php echo eab_text( $eab_go_openchat_label ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in eab_text ?></span>
					</a>
				<?php endif; ?>
			</section>
		<?php endif; ?>

		<?php if ( $eab_go_steps ) : ?>
			<section class="lh-group lh-journey"<?php echo '' !== $eab_go_steps_title ? ' aria-labelledby="lh-steps-title"' : ''; ?>>
				<?php if ( '' !== $eab_go_steps_title ) : ?>
					<h2 class="lh-steps-title" id="lh-steps-title"><?php echo eab_text( $eab_go_steps_title ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in eab_text ?></h2>
				<?php endif; ?>
				<ol class="lh-steps">
					<?php foreach ( $eab_go_steps as $eab_go_idx => $eab_go_step ) : ?>
						<?php
						$eab_go_n     = (int) $eab_go_step['n'];
						$eab_go_num   = $eab_go_idx + 1;
						$eab_go_badge = eab_go_mod( 'go_step' . $eab_go_n . '_badge' );
						$eab_go_desc  = eab_go_mod( 'go_step' . $eab_go_n . '_desc' );
						$eab_go_note  = eab_go_mod( 'go_step' . $eab_go_n . '_note' );
						?>
						<li class="lh-step lh-step--<?php echo esc_attr( $eab_go_step['key'] ); ?>">
							<span class="lh-step-num" aria-hidden="true"><?php echo esc_html( (string) $eab_go_num ); ?></span>
							<div class="lh-step-head">
								<h3 class="lh-step-title">
									<span class="lh-step-name"><?php if ( '' !== $eab_go_step_word ) : ?><span class="screen-reader-text"><?php echo esc_html( $eab_go_step_word . ' ' . $eab_go_num . ' ' ); ?></span><?php endif; ?><?php echo eab_text( eab_go_mod( 'go_step' . $eab_go_n . '_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in eab_text ?></span>
									<?php if ( '' !== $eab_go_badge ) : ?>
										<span class="lh-step-badge"><?php echo esc_html( $eab_go_badge ); ?></span>
									<?php endif; ?>
								</h3>
								<?php if ( '' !== $eab_go_desc ) : ?>
									<p class="lh-step-desc"><?php echo eab_text( $eab_go_desc ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in eab_text ?></p>
								<?php endif; ?>
								<?php if ( '' !== $eab_go_note ) : ?>
									<p class="lh-step-note"><?php echo eab_text( $eab_go_note ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in eab_text ?></p>
								<?php endif; ?>
							</div>
							<div class="lh-step-body">
								<?php echo $eab_go_step['body']; // phpcs:ignore WordPress.Security.EscapeOutput -- built with escaping in eab_go_steps() ?>
							</div>
						</li>
					<?php endforeach; ?>
				</ol>
			</section>
		<?php endif; ?>

		<?php if ( '' !== trim( $eab_go_info_html ) ) : ?>
			<section class="lh-group lh-group--info"<?php echo '' !== $eab_go_info_title ? ' aria-labelledby="lh-info-title"' : ''; ?>>
				<?php if ( '' !== $eab_go_info_title ) : ?>
					<h2 class="lh-section-title" id="lh-info-title"><?php echo eab_text( $eab_go_info_title ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in eab_text ?></h2>
				<?php endif; ?>
				<?php echo $eab_go_info_html; // phpcs:ignore WordPress.Security.EscapeOutput -- built with escaping in eab_go_info_buttons() ?>
			</section>
		<?php endif; ?>

		<?php if ( $eab_go_socials ) : ?>
			<div class="lh-socials">
				<?php foreach ( $eab_go_socials as $eab_go_social ) : ?>
					<a class="lh-soc lh-soc--<?php echo esc_attr( $eab_go_social['name'] ); ?>" href="<?php echo esc_url( $eab_go_social['url'] ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( $eab_go_social['label'] ); ?>">
						<?php echo eab_icon( $eab_go_social['name'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if ( '' !== $eab_go_doc ) : ?>
			<section class="lh-doc entry-content">
				<?php echo $eab_go_doc; // phpcs:ignore WordPress.Security.EscapeOutput -- the_content filters ?>
			</section>
		<?php endif; ?>

		<?php if ( '' !== trim( (string) eab_mod( 'risk_text' ) ) ) : ?>
			<p class="lh-note"><?php echo eab_icon( 'warn', 'icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo eab_text( eab_mod( 'risk_text' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in eab_text ?></span></p>
		<?php endif; ?>

		<?php if ( '' !== $eab_go_home_label || $eab_go_legal || '' !== $eab_go_cookie ) : ?>
			<p class="lh-legal">
				<?php if ( '' !== $eab_go_home_label ) : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( $eab_go_home_label ); ?></a>
				<?php endif; ?>
				<?php foreach ( $eab_go_legal as $eab_go_doc_link ) : ?>
					<a href="<?php echo esc_url( $eab_go_doc_link['url'] ); ?>"><?php echo esc_html( $eab_go_doc_link['label'] ); ?></a>
				<?php endforeach; ?>
				<?php echo $eab_go_cookie; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in eab_consent_link ?>
			</p>
		<?php endif; ?>

	</div>
</main>

<?php wp_footer(); ?>
</body>
</html>
