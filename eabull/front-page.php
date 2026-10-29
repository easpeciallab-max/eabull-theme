<?php
/**
 * Front page · EA BULL · ดีไซน์ Glass Sky (docs/design.md · ต้นแบบ dev/mockup/index.html)
 *
 * hero → about → story → features (4 การ์ด) → modes (Lite/Advance/Full) → devices → license (การ์ดล็อกอิน) → tests (3 การ์ด)
 * → compare → how (6 ขั้น) → ready (เช็กลิสต์) → pricing → articles → faq (14 ข้อ) → risk
 * - ส่วนเสริม story/modes/license/compare/ready/articles อยู่ใน inc/modules/homeplus.php
 * - แต่ละส่วนเป็นแผงกระจก (.glass-panel) ห่างกัน 14px อยู่ในกรอบหน้าเว็บที่ header เปิดไว้ (โมดูล chrome)
 * - แถบติดต่อท้ายหน้า (LINE / OpenChat) อยู่ใน footer (โมดูล chrome) ไม่ซ้ำที่นี่
 * - ข้อความทุกคำมาจาก setting (ค่าเริ่มต้นใน inc/modules/home.php) · escape ทุก output
 * - ไม่มีตัวเลขผลเทรด · ภาพในการ์ดมีคำบรรยายว่าเป็นภาพประกอบ · คำเตือนความเสี่ยงแสดงเต็ม ไม่ตัด
 * - ไม่มี JS ก็ใช้ได้ครบ (FAQ ใช้ details · home.js ช่วยเฉพาะเบราว์เซอร์ที่ยังไม่รองรับ details[name])
 *
 * @package eabull
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

if ( eab_has_elementor_content() && eab_uses_elementor_page_template() ) :
	?>
	<main id="main" class="elementor-page-shell elementor-page-shell--front">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article <?php post_class( 'elementor-entry' ); ?>>
				<?php the_content(); ?>
			</article>
		<?php endwhile; ?>
	</main>
	<?php
	get_footer();
	return;
endif;
?>

<main id="main" class="home-v3">

<?php /* ============ HERO · ซ้าย: kicker + H1 + คำอธิบาย + ปุ่ม + คำเตือน + ใช้งานร่วมกับ · ขวา: แผง EA ในกรอบกระจก + การ์ดลอย ============ */ ?>
<?php if ( eab_mod( 'show_hero' ) ) : ?>
	<?php
	$eab_hero_brand = trim( (string) eab_mod( 'hero_title' ) );
	$eab_hero_line  = trim( (string) eab_mod( 'hero_subtitle' ) );
	$eab_hero_em    = trim( (string) eab_mod( 'hero_subtitle_em' ) );
	$eab_hero_desc  = trim( (string) eab_mod( 'hero_desc' ) );
	$eab_hero_note  = trim( (string) eab_mod( 'hero_note' ) );
	$eab_hero_img   = trim( (string) eab_mod( 'hero_image' ) );
	$eab_hero_btn1  = eab_home_link( eab_mod( 'hero_btn1_url' ) );
	$eab_hero_btn2  = eab_home_link( eab_mod( 'hero_btn2_url' ) );
	$eab_works      = eab_home_pairs( eab_mod( 'home_hero_works_items' ) );
	$eab_works_lbl  = trim( (string) eab_mod( 'home_hero_works_label' ) );
	$eab_chips      = eab_home_pairs( eab_mod( 'home_hero_chips' ), false, 2 );
	$eab_chip_icons = array( array( 'clock', 'gold' ), array( 'shield', 'sky' ) );
	$eab_panel_cap  = trim( (string) eab_mod( 'hero_panel_caption' ) );
	?>
<section class="hm-hero<?php echo esc_attr( eab_home_tone( 'hero' ) ); ?>" id="hero" aria-labelledby="hm-hero-title">
	<div class="hm-hero-copy">
		<?php eab_home_kicker( eab_mod( 'hero_badge' ) ); ?>
		<h1 class="hm-hero-title" id="hm-hero-title">
			<?php if ( '' !== $eab_hero_brand ) : ?>
				<span class="hm-hero-brand"><?php echo esc_html( $eab_hero_brand ); ?></span>
			<?php endif; ?>
			<?php if ( '' !== $eab_hero_line ) : ?>
				<span class="hm-hero-line"><?php echo eab_text( $eab_hero_line ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></span>
			<?php endif; ?>
			<?php if ( '' !== $eab_hero_em ) : ?>
				<span class="hm-hero-em"><span class="grad-word"><?php echo eab_text( $eab_hero_em ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></span></span>
			<?php endif; ?>
		</h1>
		<?php if ( '' !== $eab_hero_desc ) : ?>
			<p class="hm-hero-text"><?php echo eab_text( $eab_hero_desc ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></p>
		<?php endif; ?>
		<?php if ( '' !== $eab_hero_btn1 || '' !== $eab_hero_btn2 ) : ?>
			<div class="hm-hero-cta">
				<?php eab_home_btn( eab_mod( 'hero_btn1_text' ), $eab_hero_btn1, 'btn btn-dark hm-btn', 'arrow-ur' ); ?>
				<?php eab_home_btn( eab_mod( 'hero_btn2_text' ), $eab_hero_btn2, 'btn btn-ghost hm-btn', 'download' ); ?>
			</div>
		<?php endif; ?>
		<?php if ( '' !== $eab_hero_note ) : ?>
			<p class="hm-hero-note"><?php echo eab_icon( 'warn', 'icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo eab_text( $eab_hero_note ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></span></p>
		<?php endif; ?>
		<?php if ( ! empty( $eab_works ) ) : ?>
			<div class="hm-works">
				<?php if ( '' !== $eab_works_lbl ) : ?>
					<p class="hm-works-label"><?php echo esc_html( $eab_works_lbl ); ?></p>
				<?php endif; ?>
				<ul class="hm-works-list">
					<?php foreach ( $eab_works as $eab_item ) : ?>
						<li><?php echo eab_home_icon( eab_home_platform_icon( $eab_item[0] ), 'icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( $eab_item[0] ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>
	</div>

	<div class="hm-hero-visual">
		<div class="hm-stage">
			<span class="orb hm-orb hm-orb--1" aria-hidden="true"></span>
			<div class="hm-squircle">
				<?php if ( '' !== $eab_hero_img ) : ?>
					<img class="hm-hero-img" src="<?php echo esc_url( $eab_hero_img ); ?>" alt="<?php echo esc_attr( eab_mod( 'home_hero_img_alt' ) ); ?>" width="1000" height="1000" loading="eager" decoding="async">
				<?php else : ?>
					<?php get_template_part( 'inc/parts/ea-panel' ); ?>
				<?php endif; ?>
			</div>
			<?php foreach ( $eab_chips as $eab_ci => $eab_chip ) : ?>
				<div class="hm-chip hm-chip--<?php echo 0 === $eab_ci ? 'a' : 'b'; ?>">
					<span class="tile tile--<?php echo esc_attr( $eab_chip_icons[ $eab_ci ][1] ); ?>"><?php echo eab_icon( $eab_chip_icons[ $eab_ci ][0], 'icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<span class="hm-chip-text"><strong><?php echo esc_html( $eab_chip[0] ); ?></strong><?php if ( '' !== $eab_chip[1] ) : ?> <span><?php echo esc_html( $eab_chip[1] ); ?></span><?php endif; ?></span>
				</div>
			<?php endforeach; ?>
			<span class="orb hm-orb hm-orb--2" aria-hidden="true"></span>
		</div>
		<?php if ( '' === $eab_hero_img && '' !== $eab_panel_cap ) : ?>
			<p class="hm-visual-cap"><?php echo esc_html( $eab_panel_cap ); ?></p>
		<?php endif; ?>
	</div>
</section>
<?php else : /* ให้มี h1 เสมอ แม้ปิด hero (SEO / โปรแกรมอ่านหน้าจอ) */ ?>
	<?php $eab_h1 = trim( eab_mod( 'hero_title' ) . ' ' . eab_mod( 'hero_subtitle' ) . ' ' . eab_mod( 'hero_subtitle_em' ) ); ?>
<h1 class="sr-only"><?php echo esc_html( '' !== $eab_h1 ? $eab_h1 : get_bloginfo( 'name' ) ); ?></h1>
<?php endif; ?>

<?php /* ============ ABOUT · หัวข้อ + การ์ดข้อมูล (ไม่ใช่ผลเทรด) ซ้าย · ย่อหน้า + ปุ่มกระจก ขวา ============ */ ?>
<?php if ( eab_mod( 'show_about' ) ) : ?>
	<?php
	$eab_stats      = eab_home_pairs( eab_mod( 'home_about_stats' ), false, 3 );
	$eab_stat_icons = array( 'chart', 'plan', 'group' );
	?>
<section class="glass-panel hm-panel hm-about<?php echo esc_attr( eab_home_tone( 'what' ) ); ?>" id="what" aria-labelledby="hm-about-title">
	<span id="about" class="hm-anchor" aria-hidden="true"></span>
	<div class="hm-about-grid">
		<div class="hm-about-main">
			<?php eab_home_kicker( eab_mod( 'home_what_kicker' ) ); ?>
			<h2 class="hm-h2" id="hm-about-title"><?php echo eab_text( eab_mod( 'about_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></h2>
			<?php if ( ! empty( $eab_stats ) ) : ?>
				<ul class="hm-stats" style="--n:<?php echo (int) count( $eab_stats ); ?>">
					<?php foreach ( $eab_stats as $eab_si => $eab_stat ) : ?>
						<li class="hm-stat"><?php echo eab_home_icon( $eab_stat_icons[ $eab_si ], 'icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><b><?php echo esc_html( $eab_stat[0] ); ?></b><?php if ( '' !== $eab_stat[1] ) : ?><span><?php echo esc_html( $eab_stat[1] ); ?></span><?php endif; ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
		<div class="hm-about-copy">
			<?php eab_home_paragraphs( eab_mod( 'about_text' ) ); ?>
			<?php eab_home_btn( eab_mod( 'home_about_btn_text' ), eab_home_link( eab_mod( 'home_about_btn_url' ) ), 'btn btn-ghost hm-btn hm-btn--sm', 'arrow-ur' ); ?>
		</div>
	</div>
</section>
<?php endif; ?>

<?php eab_homeplus_section( 'story' ); ?>

<?php /* ============ WHAT IT DOES · 4 การ์ดกระจก กระเบื้องไอคอน + ลูกศร (จุดเด่น 1-4) ============ */ ?>
<?php if ( eab_mod( 'show_features' ) ) : ?>
	<?php
	$eab_feats     = array();
	$eab_feat_look = array( array( 'gear', 'sky' ), array( 'plan', 'gold' ), array( 'chart', 'blue' ), array( 'shield', 'navy' ) );
	for ( $i = 1; $i <= 4; $i++ ) {
		$eab_f_title = trim( (string) eab_mod( 'feat' . $i . '_title' ) );
		$eab_f_desc  = trim( (string) eab_mod( 'feat' . $i . '_desc' ) );
		if ( '' === $eab_f_title && '' === $eab_f_desc ) {
			continue;
		}
		$eab_feats[] = array(
			'title' => $eab_f_title,
			'desc'  => $eab_f_desc,
			'url'   => eab_home_link( eab_mod( 'home_feat' . $i . '_url' ) ),
			'look'  => $eab_feat_look[ $i - 1 ],
		);
	}
	?>
<section class="glass-panel hm-panel hm-features<?php echo esc_attr( eab_home_tone( 'features' ) ); ?>" id="features" aria-labelledby="hm-features-title">
	<?php eab_home_head( 'hm-features-title', eab_mod( 'home_features_kicker' ), eab_mod( 'features_title' ), eab_mod( 'features_subtitle' ) ); ?>
	<?php if ( ! empty( $eab_feats ) ) : ?>
		<ul class="hm-grid hm-grid--4" style="--n:<?php echo (int) count( $eab_feats ); ?>">
			<?php foreach ( $eab_feats as $eab_feat ) : ?>
				<?php $eab_tag = '' !== $eab_feat['url'] ? 'a' : 'div'; ?>
				<li>
					<<?php echo $eab_tag; // phpcs:ignore WordPress.Security.EscapeOutput -- a|div ?> class="glass-card hm-card"<?php echo '' !== $eab_feat['url'] ? ' href="' . esc_url( $eab_feat['url'] ) . '"' : ''; ?>>
						<span class="tile tile--<?php echo esc_attr( $eab_feat['look'][1] ); ?>"><?php echo eab_home_icon( $eab_feat['look'][0], 'icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<?php if ( '' !== $eab_feat['title'] ) : ?>
							<h3 class="hm-card-title"><?php echo eab_text( $eab_feat['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></h3>
						<?php endif; ?>
						<?php if ( '' !== $eab_feat['desc'] ) : ?>
							<p class="hm-card-text"><?php echo eab_text( $eab_feat['desc'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></p>
						<?php endif; ?>
						<?php if ( '' !== $eab_feat['url'] ) : ?>
							<span class="hm-arrow" aria-hidden="true"><?php echo eab_home_icon( 'arrow-ur', 'icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<?php endif; ?>
					</<?php echo $eab_tag; // phpcs:ignore WordPress.Security.EscapeOutput -- a|div ?>>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</section>
<?php endif; ?>

<?php /* ============ สินค้าทั้งหมด เรียงตามเลขรุ่น (inc/modules/products.php) ============ */ ?>
<?php eab_homeplus_section( 'products' ); ?>

<?php eab_homeplus_section( 'modes' ); ?>

<?php /* ============ WORKS ON · กระเบื้องอุปกรณ์ ลิงก์ไปคู่มือ + หมายเหตุเรื่องมือถือ ============ */ ?>
<?php if ( eab_mod( 'home_show_devices' ) ) : ?>
	<?php
	$eab_devices     = eab_home_pairs( eab_mod( 'home_devices_items' ) );
	$eab_device_note = trim( (string) eab_mod( 'install_home_note' ) );
	?>
	<?php if ( ! empty( $eab_devices ) ) : ?>
<section class="glass-panel hm-panel hm-devices<?php echo esc_attr( eab_home_tone( 'devices' ) ); ?>" id="devices" aria-labelledby="hm-devices-title">
	<?php eab_home_head( 'hm-devices-title', eab_mod( 'home_devices_kicker' ), eab_mod( 'home_devices_title' ) ); ?>
	<ul class="hm-device-grid" style="--n:<?php echo (int) min( 6, count( $eab_devices ) ); ?>">
		<?php foreach ( $eab_devices as $eab_device ) : ?>
			<?php $eab_dev_url = eab_home_link( $eab_device[1] ); ?>
			<li>
				<?php if ( '' !== $eab_dev_url ) : ?>
					<a class="hm-device" href="<?php echo esc_url( $eab_dev_url ); ?>"><?php echo eab_home_icon( eab_home_platform_icon( $eab_device[0] ), 'icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo esc_html( $eab_device[0] ); ?></span></a>
				<?php else : ?>
					<span class="hm-device"><?php echo eab_home_icon( eab_home_platform_icon( $eab_device[0] ), 'icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo esc_html( $eab_device[0] ); ?></span></span>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ul>
		<?php if ( '' !== $eab_device_note ) : ?>
	<p class="hm-note"><?php echo eab_icon( 'phone', 'icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo eab_text( $eab_device_note ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></span></p>
		<?php endif; ?>
</section>
	<?php endif; ?>
<?php endif; ?>

<?php eab_homeplus_section( 'license' ); ?>

<?php /* ============ BEFORE YOU DECIDE · การ์ดภาพ Backtest / Forward Test / ติดตั้ง · ภาพประกอบเท่านั้น ไม่มีตัวเลขผล ============ */ ?>
<?php
$eab_shots = array();
if ( eab_mod( 'show_tests' ) ) {
	$eab_shots[] = array(
		'key'   => 'tests_bt',
		'art'   => 'bt',
		'title' => eab_mod( 'tests_bt_title' ),
		'text'  => eab_mod( 'tests_bt_text' ),
		'more'  => eab_mod( 'home_tests_bt_btn' ),
		'url'   => eab_home_link( '/backtest/' ),
	);
	$eab_shots[] = array(
		'key'   => 'tests_fw',
		'art'   => 'fw',
		'title' => eab_mod( 'tests_fw_title' ),
		'text'  => eab_mod( 'tests_fw_text' ),
		'more'  => eab_mod( 'home_tests_fw_btn' ),
		'url'   => eab_home_link( '/forward-test/' ),
	);
}
if ( eab_mod( 'show_install_home' ) ) {
	$eab_shots[] = array(
		'key'   => 'ih_step1',
		'art'   => 'install',
		'title' => eab_mod( 'install_home_title' ),
		'text'  => eab_mod( 'install_home_sub' ),
		'more'  => eab_mod( 'home_install_btn' ),
		'url'   => eab_home_link( eab_mod( 'home_install_btn_url' ) ),
	);
}
?>
<?php if ( ! empty( $eab_shots ) ) : ?>
	<?php
	$eab_shots_note = trim( (string) eab_mod( 'home_shots_note' ) );
	$eab_tests_note = trim( (string) eab_mod( 'tests_note' ) );
	// ยังไม่มีตัวเลขจริงทั้ง Backtest และ Forward → เติมประโยค "ยังไม่มีผลทดสอบ" ข้างหน้า
	$eab_no_stats = true;
	foreach ( array( array( 'bt_stat', 8 ) ) as $eab_sp ) {
		for ( $eab_si = 1; $eab_si <= $eab_sp[1]; $eab_si++ ) {
			if ( ! eab_is_placeholder( eab_mod( $eab_sp[0] . $eab_si . '_value' ) ) ) {
				$eab_no_stats = false;
			}
		}
	}
	if ( eab_mod( 'show_tests' ) && $eab_no_stats && trim( (string) eab_mod( 'tests_pending_note' ) ) ) {
		$eab_tests_note = trim( trim( (string) eab_mod( 'tests_pending_note' ) ) . ' · ' . $eab_tests_note, ' ·' );
	}
	?>
<section class="glass-panel hm-panel hm-info<?php echo esc_attr( eab_home_tone( 'tests' ) ); ?>" id="tests" aria-labelledby="hm-info-title">
	<span id="install" class="hm-anchor" aria-hidden="true"></span>
	<?php eab_home_head( 'hm-info-title', eab_mod( 'tests_kicker' ), eab_mod( 'tests_title' ), eab_mod( 'tests_subtitle' ), eab_mod( 'home_info_more_text' ), eab_home_link( eab_mod( 'home_info_more_url' ) ) ); ?>
	<ul class="hm-grid hm-grid--3" style="--n:<?php echo (int) count( $eab_shots ); ?>">
		<?php foreach ( $eab_shots as $eab_shot ) : ?>
			<?php
			$eab_tag  = '' !== $eab_shot['url'] ? 'a' : 'div';
			$eab_more = trim( (string) $eab_shot['more'] );
			?>
			<li>
				<<?php echo $eab_tag; // phpcs:ignore WordPress.Security.EscapeOutput -- a|div ?> class="glass-card hm-card hm-shot"<?php echo '' !== $eab_shot['url'] ? ' href="' . esc_url( $eab_shot['url'] ) . '"' : ''; ?>>
					<?php eab_home_shot_media( $eab_shot['key'], $eab_shot['art'] ); ?>
					<?php if ( '' !== trim( (string) $eab_shot['title'] ) ) : ?>
						<h3 class="hm-card-title"><?php echo eab_text( $eab_shot['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></h3>
					<?php endif; ?>
					<?php if ( '' !== trim( (string) $eab_shot['text'] ) ) : ?>
						<p class="hm-card-text hm-clamp"><?php echo eab_text( $eab_shot['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></p>
					<?php endif; ?>
					<?php if ( '' !== $eab_shot['url'] ) : ?>
						<?php if ( '' !== $eab_more ) : ?>
							<span class="hm-more"><?php echo esc_html( $eab_more ); ?></span>
						<?php endif; ?>
						<span class="hm-arrow" aria-hidden="true"><?php echo eab_home_icon( 'arrow-ur', 'icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<?php endif; ?>
				</<?php echo $eab_tag; // phpcs:ignore WordPress.Security.EscapeOutput -- a|div ?>>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php if ( '' !== $eab_shots_note ) : ?>
		<p class="hm-illus-note"><?php echo esc_html( $eab_shots_note ); ?></p>
	<?php endif; ?>
	<?php if ( '' !== $eab_tests_note ) : ?>
		<p class="hm-note"><?php echo eab_icon( 'warn', 'icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo eab_text( $eab_tests_note ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></span></p>
	<?php endif; ?>
</section>
<?php endif; ?>

<?php eab_homeplus_section( 'compare' ); ?>

<?php /* ============ HOW TO START · การ์ดขั้น 01-06 ต่อด้วยเส้นประ แต่ละขั้นลิงก์ไปคู่มือ (ขั้น line = ทัก LINE) ============ */ ?>
<?php if ( eab_mod( 'show_steps' ) ) : ?>
	<?php
	$eab_steps     = array();
	$eab_step_look = array( array( 'user', 'sky' ), array( 'download', 'blue' ), array( 'wallet', 'gold' ), array( 'line', 'sky' ), array( 'plug', 'blue' ), array( 'server', 'navy' ) );
	for ( $i = 1; $i <= 6; $i++ ) {
		$eab_s_title = trim( (string) eab_mod( 'step' . $i . '_title' ) );
		if ( '' === $eab_s_title ) {
			continue;
		}
		$eab_steps[] = array(
			'n'     => sprintf( '%02d', count( $eab_steps ) + 1 ),
			'title' => $eab_s_title,
			'desc'  => trim( (string) eab_mod( 'step' . $i . '_desc' ) ),
			'url'   => trim( (string) eab_mod( 'step' . $i . '_url' ) ),
			'look'  => $eab_step_look[ $i - 1 ],
		);
	}
	$eab_step_link = trim( (string) eab_mod( 'home_steps_link_text' ) );
	?>
	<?php if ( ! empty( $eab_steps ) ) : ?>
<section class="glass-panel hm-panel hm-steps<?php echo esc_attr( eab_home_tone( 'how' ) ); ?>" id="how" aria-labelledby="hm-steps-title">
	<span id="how-it-works" class="hm-anchor" aria-hidden="true"></span>
	<?php eab_home_head( 'hm-steps-title', eab_mod( 'steps_kicker' ), eab_mod( 'steps_title' ), eab_mod( 'steps_subtitle' ) ); ?>
	<ol class="hm-step-list" style="--n:<?php echo (int) count( $eab_steps ); ?>">
		<?php foreach ( $eab_steps as $eab_step ) : ?>
			<li class="hm-step">
				<div class="hm-step-top">
					<span class="hm-step-no"><?php echo esc_html( $eab_step['n'] ); ?></span>
					<span class="tile tile--<?php echo esc_attr( $eab_step['look'][1] ); ?>"><?php echo eab_home_icon( $eab_step['look'][0], 'icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				</div>
				<div class="hm-step-body">
					<h3 class="hm-step-title"><?php echo eab_text( $eab_step['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></h3>
					<?php if ( '' !== $eab_step['desc'] ) : ?>
						<p class="hm-step-text"><?php echo eab_text( $eab_step['desc'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></p>
					<?php endif; ?>
					<?php
					if ( 'line' === strtolower( $eab_step['url'] ) ) {
						eab_home_contact_link( eab_mod( 'home_steps_line_text' ), 'hm-step-link', 'home-steps', '', false );
					} else {
						$eab_step_url = eab_home_link( $eab_step['url'] );
						if ( '' !== $eab_step_url && '' !== $eab_step_link ) {
							printf( '<a class="hm-step-link" href="%s">%s</a>', esc_url( $eab_step_url ), esc_html( $eab_step_link ) );
						}
					}
					?>
				</div>
			</li>
		<?php endforeach; ?>
	</ol>
</section>
	<?php endif; ?>
<?php endif; ?>

<?php eab_homeplus_section( 'ready' ); ?>

<?php /* ============ PRICING · การ์ดกระจกโหมดสอบถาม (ไม่มีราคาจนกว่าจะตั้งโหมดแสดงราคา + ราคาจริง) ============ */ ?>
<?php if ( eab_mod( 'show_pricing_home' ) ) : ?>
	<?php
	$eab_pmode = eab_mod( 'pricing_mode' );
	$eab_tiers = array();
	for ( $i = 1; $i <= 3; $i++ ) {
		$eab_pk_name = trim( (string) eab_mod( 'pkg' . $i . '_name' ) );
		if ( '' === $eab_pk_name ) {
			continue;
		}
		$eab_pk_price = trim( (string) eab_mod( 'pkg' . $i . '_price' ) );
		$eab_tiers[]  = array(
			'name'     => $eab_pk_name,
			'tag'      => trim( (string) eab_mod( 'pkg' . $i . '_tag' ) ),
			'price'    => 'price' === $eab_pmode && eab_home_price_ready( $eab_pk_price ) ? $eab_pk_price : '',
			'period'   => trim( (string) eab_mod( 'pkg' . $i . '_period' ) ),
			'features' => eab_lines( eab_mod( 'pkg' . $i . '_features' ) ),
			'featured' => (bool) eab_mod( 'pkg' . $i . '_featured' ),
		);
	}
	$eab_rec_label    = trim( (string) eab_mod( 'home_pricing_rec_label' ) );
	$eab_contact_text = trim( (string) eab_mod( 'home_pricing_contact_text' ) );
	$eab_margin_note  = trim( (string) eab_mod( 'home_pricing_margin_note' ) );
	$eab_pricing_note = trim( (string) eab_mod( 'pricing_note' ) );
	?>
<section class="glass-panel hm-panel hm-pricing<?php echo esc_attr( eab_home_tone( 'pricing' ) ); ?>" id="pricing" aria-labelledby="hm-pricing-title">
	<?php eab_home_head( 'hm-pricing-title', eab_mod( 'home_pricing_kicker' ), eab_mod( 'pricing_home_title' ), eab_mod( 'pricing_home_sub' ), eab_mod( 'home_pricing_more_text' ), eab_home_link( '/pricing/' ) ); ?>
	<?php if ( ! empty( $eab_tiers ) ) : ?>
		<ul class="hm-grid hm-grid--3 hm-tiers" style="--n:<?php echo (int) count( $eab_tiers ); ?>">
			<?php foreach ( $eab_tiers as $eab_tier ) : ?>
				<li class="glass-card hm-tier<?php echo $eab_tier['featured'] ? ' is-featured' : ''; ?>">
					<div class="hm-tier-head">
						<h3 class="hm-tier-name"><?php echo esc_html( $eab_tier['name'] ); ?></h3>
						<?php if ( $eab_tier['featured'] && '' !== $eab_rec_label ) : ?>
							<span class="hm-tier-rec"><?php echo esc_html( $eab_rec_label ); ?></span>
						<?php endif; ?>
					</div>
					<?php if ( '' !== $eab_tier['tag'] ) : ?>
						<p class="hm-tier-tag"><?php echo eab_text( $eab_tier['tag'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></p>
					<?php endif; ?>
					<p class="hm-tier-price">
						<?php if ( '' !== $eab_tier['price'] ) : ?>
							<span class="hm-tier-amt"><?php echo esc_html( $eab_tier['price'] ); ?></span><?php if ( '' !== $eab_tier['period'] ) : ?> <span class="hm-tier-period"><?php echo esc_html( $eab_tier['period'] ); ?></span><?php endif; ?>
						<?php else : ?>
							<span class="hm-tier-amt hm-tier-amt--contact"><?php echo esc_html( $eab_contact_text ); ?></span>
						<?php endif; ?>
					</p>
					<?php if ( ! empty( $eab_tier['features'] ) ) : ?>
						<ul class="hm-checks">
							<?php foreach ( $eab_tier['features'] as $eab_item ) : ?>
								<li><?php echo eab_icon( 'check', 'icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo eab_text( $eab_item ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></span></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
					<?php eab_home_contact_link( eab_mod( 'pricing_btn_text' ), $eab_tier['featured'] ? 'btn btn-fire hm-tier-btn' : 'btn btn-ghost hm-tier-btn', 'home-pricing', $eab_tier['name'] ); ?>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
	<?php if ( '' !== $eab_margin_note ) : ?>
		<p class="hm-note hm-note--warn"><?php echo eab_icon( 'warn', 'icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo eab_text( $eab_margin_note ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></span></p>
	<?php endif; ?>
	<?php if ( '' !== $eab_pricing_note ) : ?>
		<p class="hm-illus-note"><?php echo eab_text( $eab_pricing_note ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></p>
	<?php endif; ?>
</section>
<?php endif; ?>

<?php eab_homeplus_section( 'articles' ); ?>

<?php /* ============ FAQ · details.faq-item 2 คอลัมน์ (schema อ่านจาก key faqN_q / faqN_a เดียวกัน) · เปิดได้ทีละข้อ ============ */ ?>
<?php if ( eab_mod( 'show_faq' ) ) : ?>
	<?php
	$eab_faqs = array();
	for ( $i = 1; $i <= ( function_exists( 'eab_home_faq_count' ) ? eab_home_faq_count() : 10 ); $i++ ) {
		$eab_q = trim( (string) eab_mod( 'faq' . $i . '_q' ) );
		$eab_a = trim( (string) eab_mod( 'faq' . $i . '_a' ) );
		if ( '' !== $eab_q && '' !== $eab_a ) {
			$eab_faqs[] = array( $eab_q, $eab_a );
		}
	}
	$eab_faq_cols = array_chunk( $eab_faqs, max( 1, (int) ceil( count( $eab_faqs ) / 2 ) ) );
	?>
	<?php if ( ! empty( $eab_faqs ) ) : ?>
<section class="glass-panel hm-panel hm-faq<?php echo esc_attr( eab_home_tone( 'faq' ) ); ?>" id="faq" aria-labelledby="hm-faq-title">
	<?php eab_home_head( 'hm-faq-title', eab_mod( 'home_faq_kicker' ), eab_mod( 'faq_title' ), eab_mod( 'faq_subtitle' ) ); ?>
	<div class="hm-faq-grid">
		<?php foreach ( $eab_faq_cols as $eab_col ) : ?>
			<div class="hm-faq-col">
				<?php foreach ( $eab_col as $eab_faq ) : ?>
					<details class="faq-item hm-q" name="eabull-faq">
						<summary class="hm-q-sum"><span class="hm-q-text"><?php echo eab_text( $eab_faq[0] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></span><i class="hm-q-mark" aria-hidden="true"></i></summary>
						<div class="hm-q-ans"><?php eab_home_paragraphs( $eab_faq[1] ); ?></div>
					</details>
				<?php endforeach; ?>
			</div>
		<?php endforeach; ?>
	</div>
</section>
	<?php endif; ?>
<?php endif; ?>

<?php /* ============ RISK · กล่องทองอ่อน ข้อความเต็ม ไม่พับ ไม่ตัด ============ */ ?>
<?php if ( eab_mod( 'show_risk' ) ) : ?>
	<?php
	$eab_risk_label = trim( (string) eab_mod( 'home_risk_label' ) );
	$eab_risk_title = trim( (string) eab_mod( 'risk_title' ) );
	$eab_risk_text  = trim( (string) eab_mod( 'risk_text' ) );
	?>
<section class="hm-risk<?php echo esc_attr( eab_home_tone( 'risk' ) ); ?>" id="risk"<?php echo '' !== $eab_risk_title ? ' aria-labelledby="hm-risk-title"' : ''; ?>>
	<?php if ( '' !== $eab_risk_label ) : ?>
		<p class="hm-risk-label"><?php echo eab_icon( 'warn', 'icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( $eab_risk_label ); ?></p>
	<?php endif; ?>
	<?php if ( '' !== $eab_risk_title ) : ?>
		<h2 class="hm-risk-title" id="hm-risk-title"><?php echo eab_text( $eab_risk_title ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></h2>
	<?php endif; ?>
	<?php if ( '' !== $eab_risk_text ) : ?>
		<p class="hm-risk-text"><?php echo nl2br( esc_html( $eab_risk_text ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></p>
	<?php endif; ?>
	<?php eab_home_btn( eab_mod( 'home_risk_more_text' ), eab_home_link( '/risk-disclosure/' ), 'btn btn-ghost hm-btn hm-btn--sm', 'shield' ); ?>
</section>
<?php endif; ?>

</main>

<?php
get_footer();
