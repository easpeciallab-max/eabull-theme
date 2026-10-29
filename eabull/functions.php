<?php
/**
 * EA BULL · Theme functions
 *
 * @package eabull
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EAB_VERSION', '1.0.73' );

/* --------------------------------------------------------------
 * Theme setup
 * -------------------------------------------------------------- */
function eab_setup() {
	load_theme_textdomain( 'eabull', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'elementor' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 120,
			'width'       => 120,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	register_nav_menus(
		array(
			'primary' => 'เมนูหลัก (Header)',
			'footer'  => 'เมนูท้ายเว็บ (Footer)',
		)
	);
}
add_action( 'after_setup_theme', 'eab_setup' );

/* --------------------------------------------------------------
 * Styles & scripts
 * -------------------------------------------------------------- */
function eab_assets() {
	$style_path    = get_stylesheet_directory() . '/style.css';
	$script_path   = get_template_directory() . '/assets/js/main.js';
	$style_version = file_exists( $style_path ) ? filemtime( $style_path ) : EAB_VERSION;
	$script_version = file_exists( $script_path ) ? filemtime( $script_path ) : EAB_VERSION;

	wp_enqueue_style(
		'eab-fonts',
		get_template_directory_uri() . '/assets/css/fonts.css', // ฟอนต์ Noto Sans Thai แบบ self-host (ไม่โหลดจาก Google ก่อนยินยอมคุกกี้)
		array(),
		file_exists( get_template_directory() . '/assets/css/fonts.css' ) ? filemtime( get_template_directory() . '/assets/css/fonts.css' ) : EAB_VERSION
	);
	wp_enqueue_style( 'eab-style', get_stylesheet_uri(), array( 'eab-fonts' ), $style_version );
	wp_enqueue_script( 'eab-main', get_template_directory_uri() . '/assets/js/main.js', array(), $script_version, true );

	/* CSS/JS ของแต่ละโมดูล (assets/css/*.css, assets/js/*.js ยกเว้น main.js) · ไฟล์ที่ไม่มีจะถูกข้าม */
	foreach ( eab_asset_modules() as $eab_module ) {
		if ( ! eab_asset_module_needed( $eab_module ) ) {
			continue;
		}
		$css = '/assets/css/' . $eab_module . '.css';
		if ( file_exists( get_template_directory() . $css ) ) {
			wp_enqueue_style( 'eab-' . $eab_module, get_template_directory_uri() . $css, array( 'eab-style' ), filemtime( get_template_directory() . $css ) );
		}
		$js = '/assets/js/' . $eab_module . '.js';
		if ( file_exists( get_template_directory() . $js ) ) {
			wp_enqueue_script( 'eab-' . $eab_module . '-js', get_template_directory_uri() . $js, array( 'eab-main' ), filemtime( get_template_directory() . $js ), true );
		}
	}
	wp_localize_script(
		'eab-main',
		'eabLoadMore',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'eab_load_more' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'eab_assets' );

/**
 * ลำดับโมดูล CSS/JS (โหลดหลัง style.css ตามลำดับนี้)
 */
function eab_asset_modules() {
	return apply_filters( 'eab_asset_modules', array( 'components', 'chrome', 'home', 'guides', 'pages', 'go', 'consent' ) );
}

/**
 * โหลด CSS/JS ของโมดูลเฉพาะหน้าที่ใช้ (ลด CSS ที่ไม่ได้ใช้ต่อหน้า) · แก้ได้ด้วยฟิลเตอร์ eab_asset_module_needed
 */
function eab_asset_module_needed( $module ) {
	$is_go = is_page_template( 'template-go.php' );
	switch ( $module ) {
		case 'home':
			$needed = is_front_page();
			break;
		case 'go':
			$needed = $is_go;
			break;
		case 'guides':
			$needed = is_page_template( 'template-guide.php' ) || is_page_template( 'template-install.php' );
			break;
		case 'chrome':
			$needed = ! $is_go;
			break;
		default:
			$needed = true;
	}
	return (bool) apply_filters( 'eab_asset_module_needed', $needed, $module );
}

/* --------------------------------------------------------------
 * Builder compatibility
 * -------------------------------------------------------------- */
function eab_is_elementor_page( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_queried_object_id();
	}

	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	return $post_id && 'builder' === get_post_meta( $post_id, '_elementor_edit_mode', true );
}

function eab_elementor_data_has_widgets( $elements ) {
	if ( ! is_array( $elements ) ) {
		return false;
	}

	foreach ( $elements as $element ) {
		if ( ! empty( $element['widgetType'] ) ) {
			return true;
		}

		if ( ! empty( $element['elements'] ) && eab_elementor_data_has_widgets( $element['elements'] ) ) {
			return true;
		}
	}

	return false;
}

function eab_has_elementor_content( $post_id = null ) {
	if ( ! eab_is_elementor_page( $post_id ) ) {
		return false;
	}

	if ( ! $post_id ) {
		$post_id = get_queried_object_id();
	}

	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	$elementor_data = get_post_meta( $post_id, '_elementor_data', true );
	if ( empty( $elementor_data ) ) {
		return false;
	}

	$elements = json_decode( $elementor_data, true );

	return eab_elementor_data_has_widgets( $elements );
}

function eab_uses_elementor_page_template( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_queried_object_id();
	}

	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	$template = $post_id ? get_page_template_slug( $post_id ) : '';

	return in_array(
		$template,
		array(
			'elementor_canvas',
			'elementor_header_footer',
			'template-elementor-canvas.php',
			'template-elementor-full-width.php',
		),
		true
	);
}

function eab_body_classes( $classes ) {
	if ( is_page() && eab_is_elementor_page( get_queried_object_id() ) ) {
		$classes[] = 'eab-has-elementor';
	}
	if ( eab_is_dark_mode() ) {
		$classes[] = 'theme-dark';
	}

	return $classes;
}
add_filter( 'body_class', 'eab_body_classes' );

/**
 * EA BULL ใช้ดีไซน์ Glass Sky โทนสว่างอย่างเดียว (ไม่มีโหมดมืด)
 * เก็บฟังก์ชันไว้เพราะ template หลายไฟล์เรียกผ่าน eab_section_tone()
 */
function eab_is_dark_mode() {
	return false;
}

/**
 * คลาสโทนพื้นของ section · ในโหมดเข้ม section ตามรายการด้านล่างเป็นพื้นเข้ม (.is-dark)
 * $alt = true → ใช้เฉดเข้มกว่า (สลับจังหวะ section ที่อยู่ติดกัน)
 */
function eab_section_tone( $key, $alt = false ) {
	if ( ! eab_is_dark_mode() ) {
		return '';
	}
	$light = array( 'gallery', 'install', 'faq', 'risk', 'assurance', 'longform', 'posts' );
	if ( in_array( $key, $light, true ) ) {
		return '';
	}
	return $alt ? ' is-dark is-dark-2' : ' is-dark';
}

/* --------------------------------------------------------------
 * Default content (ทุกค่าแก้ได้ในหน้า "ปรับแต่ง / Customize")
 * -------------------------------------------------------------- */
function eab_defaults() {
	static $d = null;
	if ( null !== $d ) {
		return $d;
	}

	/*
	 * ค่ากลางที่ไม่มีโมดูลใดเป็นเจ้าของ · ค่าของหน้าแรก (home) แถบติดต่อ/footer (chrome) หน้าย่อย (pages, guides)
	 * หน้า /go (go) และคุกกี้ (consent) อยู่ในฟิลเตอร์ eab_defaults ของโมดูลนั้น ไม่ซ้ำที่นี่
	 */
	$d = array(
		/* ทั่วไป */
		'line_url'        => '',
		'contact_fallback_text' => 'ติดต่อทีมงาน',
		'wordmark_dark'   => '',
		'wordmark_light'  => '',
		'line_openchat_url'  => 'https://line.me/ti/g2/2JBjB5FQGk26L9d0Nm-geWaVr-CUFe2y8MONQw',
		'line_openchat_text' => 'เข้ากลุ่ม OpenChat',
		'line_qr_image'      => 'assets/img/brand/eabull-line-qr.png',

		/* หน้า /go (ลิงก์รวม) · ลิงก์ร้านแอป MT5 */
		'mt5_dl_windows' => 'https://www.metatrader5.com/en/download',
		'mt5_dl_android' => 'https://play.google.com/store/apps/details?id=net.metaquotes.metatrader5',
		'mt5_dl_ios'     => 'https://apps.apple.com/app/metatrader-5/id413251709',

		/* โบรกเกอร์ (ใช้ในคู่มือเปิดบัญชี / ล็อกอิน ผ่าน shortcode [eabull_broker]) */
		'broker_name'        => 'Zaurix',
		'broker_server'      => 'Zaurix-Server', // ใช้ตาม EA WING ไปก่อน (29 ต.ค. 2026) รอเจ้าของยืนยัน
		'broker_signup_url'  => 'https://portal.zaurix.com/sign-up?ref=211281', // ลิงก์พาร์ตเนอร์ EA BULL จากเจ้าของ 29 ต.ค. 2026
		'broker_signup_text' => 'สมัครบัญชี Zaurix',

		/* รุ่นของไฟล์ EA ที่แจกอยู่ · ใช้ผ่าน [eabull_ea] ในเนื้อหา และ {ea} {ea_file} {ea_version} {bt_range} ในข้อความคู่มือ
		 * เปลี่ยนรุ่น = แก้ 3 ค่านี้ + ไฟล์ zip ของหน้า /go (go_fast_*) + ภาพหน้าจอใน assets/img/install */
		'ea_version'         => '5.0',
		'ea_bt_from'         => '2026.01.01',
		'ea_bt_to'           => '2026.10.01',
		'facebook_url'    => 'https://www.facebook.com/eabull.trading/',
		'contact_email'   => 'easpeciallab@gmail.com',
		'show_float_line' => false,
		'show_language_switcher' => false,
		'language_fallback_items' => "th|🇹🇭|TH|ไทย\nen|🇬🇧|EN|English\nzh|🇨🇳|ZH|中文\nfr|🇫🇷|FR|Français\nde|🇩🇪|DE|Deutsch\nru|🇷🇺|RU|Русский\nja|🇯🇵|JA|日本語\nko|🇰🇷|KO|한국어",

		/* Mobile bottom bar */
		'show_mobile_nav'         => true,
		'mobile_nav_home_label'   => 'หน้าแรก',
		'mobile_nav_home_url'     => '/',
		'mobile_nav_price_label'   => 'แพ็กเกจ',
		'mobile_nav_price_url'     => '/pricing/',
		'mobile_nav_install_label' => 'คู่มือ',
		'mobile_nav_install_url'   => '/how-to-install/',
		'mobile_nav_line_label'    => 'ทัก LINE',

		/* SEO / แชร์ลิงก์ (Open Graph) */
		'og_default_image'       => '',
		'og_default_description' => 'EA BULL ผู้ช่วยเทรดอัตโนมัติบน MetaTrader 5 ส่งคำสั่งตามแผนที่คุณวางไว้ คุณกำหนดขนาด Lot และขีดจำกัดความเสี่ยงเอง มีทีมงานคนไทยคอยตอบทาง LINE',
		/* คุกกี้ / Consent + Tracking (โหลด tracking เฉพาะหลังกดยอมรับ) */
		'show_cookie_consent' => false,
		'ga_measurement_id'   => '',
		'fb_pixel_id'         => '',

		/* Hero */
		'show_hero'      => true,
		'hero_title'     => 'EA BULL',
		'show_kickers'   => false,
		'hero_image'     => '',
		/* Hero · แผงควบคุมจำลอง (แสดงเมื่อไม่ได้ใส่รูป Hero) */
		'hero_panel_title'   => 'EA BULL',
		'hero_panel_caption' => 'ภาพจำลองหน้าจอ EA ใช้ประกอบการอธิบาย ไม่ใช่ผลการเทรด',

		/* สวิตช์ส่วนต่าง ๆ ของหน้าแรก (ข้อความอยู่ในโมดูล home) */
		'show_about'    => true,
		'show_features' => true,
		'show_steps'    => true,

		/* แพ็กเกจ (ชื่อ ราคา ป้ายแนะนำ · คำโปรยและรายการอยู่ในโมดูล pages) */
		'show_pricing'     => true,
		'pricing_mode'     => 'price',
		'pricing_btn_text' => 'สอบถามแพ็กเกจนี้ทาง LINE',
		'pkg1_name'        => 'Starter',
		'pkg1_price'       => 'ฟรี',
		'pkg1_period'      => 'บาท / ปี',
		'pkg1_featured'    => false,
		'pkg2_name'        => 'Pro',
		'pkg2_price'       => '6,990',
		'pkg2_period'      => 'บาท / ปี',
		'pkg2_featured'    => true,
		'pkg3_name'        => 'VIP',
		'pkg3_price'       => '9,990',
		'pkg3_period'      => 'บาท / ปี',
		'pkg3_featured'    => false,

		/* FAQ · คำเตือนความเสี่ยง (ข้อความอยู่ในโมดูล home) */
		'show_faq'  => true,
		'show_risk' => true,
	);

	/* ===== หน้าย่อย (multipage) · ค่าที่ไม่มีโมดูลใดตั้งทับ ===== */
	$d = array_merge(
		$d,
		array(

			/* หน้าแรก · การ์ดข้อมูลก่อนตัดสินใจ (ข้อความอยู่ในโมดูล home) */
			'show_tests'        => true,
			'show_install_home' => true,
			'show_pricing_home' => true,

			/* หน้า Backtest · ช่องผลทดสอบ (ค่าที่ขึ้นต้นด้วย "ระบุ" = ยังไม่กรอก และจะไม่แสดง) */
			'bt_stat1_label'      => 'ช่วงข้อมูลที่ใช้ทดสอบ',
			'bt_stat1_value'      => '1 ม.ค. ถึง 30 ก.ย. 2026',
			'bt_stat2_label'      => 'สินทรัพย์และโบรกเกอร์',
			'bt_stat2_value'      => 'XAUUSD.c · Zaurix',
			'bt_stat3_label'      => 'Timeframe และโหมด',
			'bt_stat3_value'      => 'M1 · รุ่น 5.0 โหมด Lite',
			'bt_stat4_label'      => 'ทุนตั้งต้นในการทดสอบ',
			'bt_stat4_value'      => '10,000 USD',
			'bt_stat5_label'      => 'Net Profit ในรายงาน',
			'bt_stat5_value'      => '58,915.21 USD',
			'bt_stat6_label'      => 'Profit Factor',
			'bt_stat6_value'      => '2.62',
			'bt_stat7_label'      => 'ติดลบลอยสูงสุด (Equity Drawdown 8,248.36 USD)',
			'bt_stat7_value'      => '82.5% ของทุนตั้งต้น',
			'bt_stat8_label'      => 'จำนวนออเดอร์รวม',
			'bt_stat8_value'      => '24,493',
			'backtest_img'        => 'assets/img/results/eabull-backtest-lite-v5-2026-v1.webp',


			/* หน้า How to Install · รูปประกอบขั้นตอน */
			'inst_step1_img'    => '',
			'inst_step2_img'    => '',
			'inst_step3_img'    => '',
			'inst_step4_img'    => '',
			'inst_step5_img'    => '',
			'inst_step6_img'    => '',

			/* หน้า Risk Disclosure */
			'riskpage_image'   => '',
			'riskpage_updated' => 'ปรับปรุงล่าสุด: ระบุวันที่',
		)
	);

	/* โมดูล (inc/modules/*.php) เพิ่มค่าเริ่มต้นของตัวเองผ่านฟิลเตอร์นี้ */
	$d = apply_filters( 'eab_defaults', $d );

	return $d;
}

/**
 * อ่านค่า theme mod พร้อม fallback เป็นค่าเริ่มต้น
 */
function eab_mod( $key ) {
	$defaults = eab_defaults();
	$default  = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';
	return get_theme_mod( $key, $default );
}

/**
 * แปลง textarea เป็น array รายการ (บรรทัดละ 1 รายการ)
 */
function eab_lines( $text ) {
	$lines = preg_split( '/\r\n|\r|\n/', (string) $text );
	$lines = array_map( 'trim', $lines );
	return array_values( array_filter( $lines, 'strlen' ) );
}

/**
 * แสดงคำเล็กเหนือหัวข้อ (kicker) หรือไม่ · ปิดทั้งเว็บเป็นค่าเริ่มต้น (เจ้าของขอ 6 ต.ค. 2026) · เปิดได้ที่ ปรับแต่ง → 2) Hero
 */
function eab_show_kickers() {
	return (bool) eab_mod( 'show_kickers' );
}

/**
 * รูปในธีม (assets/...) → URL เต็ม · ค่าอื่นคืนตามเดิม
 */
function eab_theme_asset_url( $value ) {
	$value = trim( (string) $value );
	if ( '' !== $value && 0 === strpos( $value, 'assets/' ) ) {
		return get_template_directory_uri() . '/' . $value;
	}
	return $value;
}

/**
 * ราคาแบบไม่มีค่าใช้จ่าย (ฟรี / free / 0) · แสดงในการ์ดแพ็กเกจได้แม้ไม่ใช่ตัวเลข
 */
function eab_is_free_price( $value ) {
	return in_array( strtolower( trim( (string) $value ) ), array( 'ฟรี', 'free', '0' ), true );
}

/**
 * ตรวจว่าค่ายังเป็น placeholder (ยังไม่กรอกจริง) หรือไม่
 * ใช้ซ่อนสถิติที่ยังขึ้นต้นด้วย "ระบุ" / "เช่น" ไม่ให้หน้าแรกดูเหมือนยังทำไม่เสร็จ
 */
function eab_is_placeholder( $value ) {
	$value = trim( (string) $value );
	if ( '' === $value ) {
		return true;
	}
	foreach ( array( 'ระบุ', 'เช่น' ) as $needle ) {
		if ( 0 === mb_strpos( $value, $needle ) ) {
			return true;
		}
	}
	return false;
}

/**
 * แปลงลิงก์ที่ตั้งค่าได้ ให้รองรับทั้ง URL เต็ม, anchor และ slug ภายในเว็บ
 */
function eab_link_url( $url ) {
	$url = trim( (string) $url );

	if ( '' === $url ) {
		return '#';
	}

	if ( '#' === $url || 0 === strpos( $url, '#' ) || preg_match( '#^(https?:)?//#i', $url ) || preg_match( '#^(mailto|tel):#i', $url ) ) {
		return $url;
	}

	return home_url( '/' . ltrim( $url, '/' ) );
}

/**
 * URL โลโก้ (ใช้โลโก้ที่อัปโหลดเอง ถ้าไม่มีใช้โลโก้ที่ฝังมากับธีม)
 */
function eab_logo_url() {
	$logo_id = get_theme_mod( 'custom_logo' );
	if ( $logo_id ) {
		$url = wp_get_attachment_image_url( $logo_id, 'full' );
		if ( $url ) {
			return $url;
		}
	}
	return get_template_directory_uri() . '/assets/img/logo.png';
}

/**
 * URL โลโก้ตัวอักษร (wordmark) · 'dark' = ตัวอักษรเข้มสำหรับพื้นขาว, 'light' = ตัวอักษรขาวสำหรับพื้นดำ
 * ตั้งรูปเองได้ที่ ปรับแต่ง → ช่องทางติดต่อ (wordmark_dark / wordmark_light)
 */
function eab_wordmark_url( $variant = 'dark' ) {
	$variant = 'light' === $variant ? 'light' : 'dark';
	$custom  = eab_mod( 'wordmark_' . $variant );
	if ( $custom ) {
		return $custom;
	}
	return get_template_directory_uri() . '/assets/img/brand/eabull-wordmark-' . $variant . '.webp';
}

/**
 * ไอคอนในวงกลม (ดำ/เขียว) ตามสไตล์แบนเนอร์ EA BULL
 */
function eab_icon_badge( $name, $variant = 'dark' ) {
	return '<span class="ic-badge ic-badge--' . esc_attr( $variant ) . '">' . eab_icon( $name ) . '</span>';
}

/**
 * เมนูสำรอง กรณียังไม่ได้สร้างเมนูใน WordPress
 * ชี้ไปยังหน้าย่อยตาม slug ที่แนะนำ (ปรับเมนูจริงได้ที่ รูปแบบ → เมนู)
 */
function eab_fallback_menu() {
	if ( function_exists( 'eab_chrome_fallback_menu' ) ) {
		eab_chrome_fallback_menu();
	}
}

/**
 * Language switcher slot.
 *
 * This prefers multilingual plugins for real translated URLs, hreflang, SEO,
 * and Elementor compatibility. The manual fallback is only a visible starter
 * until a plugin such as TranslatePress, Polylang, or WPML owns translations.
 */
function eab_language_switcher() {
	if ( ! eab_mod( 'show_language_switcher' ) ) {
		return;
	}

	$plugin_markup = '';
	$plugin_class  = '';

	if ( shortcode_exists( 'language-switcher' ) ) {
		$plugin_markup = do_shortcode( '[language-switcher]' );
	} elseif ( function_exists( 'pll_the_languages' ) ) {
		$plugin_markup = pll_the_languages(
			array(
				'echo'          => 0,
				'show_flags'    => 0,
				'show_names'    => 1,
				'hide_if_empty' => 0,
			)
		);
	} elseif ( function_exists( 'icl_get_languages' ) ) {
		$wpml_languages = icl_get_languages( 'skip_missing=0&orderby=code' );

		if ( is_array( $wpml_languages ) && $wpml_languages ) {
			$plugin_markup = '<ul class="language-switcher-list">';
			foreach ( $wpml_languages as $language ) {
				if ( empty( $language['url'] ) || empty( $language['native_name'] ) ) {
					continue;
				}

				$plugin_markup .= sprintf(
					'<li><a class="%1$s" href="%2$s">%3$s</a></li>',
					! empty( $language['active'] ) ? 'is-active' : '',
					esc_url( $language['url'] ),
					esc_html( $language['native_name'] )
				);
			}
			$plugin_markup .= '</ul>';
		}
	}

	if ( $plugin_markup ) {
		echo '<div class="language-switcher language-switcher--plugin' . esc_attr( $plugin_class ) . '" aria-label="' . esc_attr__( 'Language switcher', 'eabull' ) . '">';
		echo wp_kses_post( $plugin_markup );
		echo '</div>';
		return;
	}

	$languages = array();
	foreach ( eab_lines( eab_mod( 'language_fallback_items' ) ) as $language_line ) {
		$parts = array_map( 'trim', explode( '|', $language_line ) );
		if ( count( $parts ) < 3 ) {
			continue;
		}

		$code = sanitize_key( $parts[0] );
		if ( ! $code ) {
			continue;
		}

		if ( count( $parts ) >= 4 ) {
			$flag  = $parts[1];
			$short = $parts[2];
			$label = $parts[3];
		} else {
			$flag  = '';
			$short = $parts[1];
			$label = $parts[2];
		}

		$languages[ $code ] = array(
			'flag'  => $flag,
			'short' => $short,
			'label' => $label,
		);
	}

	if ( ! $languages ) {
		$languages = array(
			'th' => array(
				'flag'  => '🇹🇭',
				'short' => 'TH',
				'label' => 'ไทย',
			),
			'en' => array(
				'flag'  => '🇬🇧',
				'short' => 'EN',
				'label' => 'English',
			),
		);
	}

	$current = substr( get_locale(), 0, 2 );
	if ( isset( $_GET['lang'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$current = sanitize_key( wp_unslash( $_GET['lang'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	}

	if ( ! isset( $languages[ $current ] ) ) {
		$current = 'th';
	}
	?>
	<details class="language-switcher language-switcher--fallback">
		<summary aria-label="<?php echo esc_attr__( 'Choose language', 'eabull' ); ?>">
			<span class="language-switcher-current">
				<?php if ( ! empty( $languages[ $current ]['flag'] ) ) : ?>
					<span class="language-switcher-flag" aria-hidden="true"><?php echo esc_html( $languages[ $current ]['flag'] ); ?></span>
				<?php endif; ?>
				<span class="language-switcher-code"><?php echo esc_html( $languages[ $current ]['short'] ); ?></span>
			</span>
		</summary>
		<div class="language-switcher-menu">
			<?php foreach ( $languages as $code => $language ) : ?>
				<a class="<?php echo esc_attr( $code === $current ? 'is-active' : '' ); ?>" href="<?php echo esc_url( add_query_arg( 'lang', $code ) ); ?>">
					<?php if ( ! empty( $language['flag'] ) ) : ?>
						<span class="language-switcher-flag" aria-hidden="true"><?php echo esc_html( $language['flag'] ); ?></span>
					<?php endif; ?>
					<span class="language-switcher-code"><?php echo esc_html( $language['short'] ); ?></span>
					<small><?php echo esc_html( $language['label'] ); ?></small>
				</a>
			<?php endforeach; ?>
		</div>
	</details>
	<?php
}

/**
 * หัวหน้าเพจ (page hero) ใช้ร่วมกันทุกหน้าย่อย · มี breadcrumb (+ BreadcrumbList schema ใน inc/seo.php)
 */
function eab_page_hero( $kicker, $title, $subtitle = '' ) {
	$crumbs = eab_breadcrumbs( $title );
	?>
	<section class="phero">
		<div class="container phero-inner reveal">
			<?php if ( count( $crumbs ) > 1 ) : ?>
				<nav class="crumbs" aria-label="เส้นทางนำทาง">
					<ol>
						<?php foreach ( $crumbs as $eab_i => $eab_crumb ) : ?>
							<li>
								<?php if ( $eab_crumb['url'] && $eab_i < count( $crumbs ) - 1 ) : ?>
									<a href="<?php echo esc_url( $eab_crumb['url'] ); ?>"><?php echo esc_html( $eab_crumb['name'] ); ?></a>
								<?php else : ?>
									<span aria-current="page"><?php echo esc_html( $eab_crumb['name'] ); ?></span>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ol>
				</nav>
			<?php endif; ?>
			<?php if ( $kicker && eab_show_kickers() ) : ?>
				<span class="kicker"><?php echo esc_html( $kicker ); ?></span>
			<?php endif; ?>
			<h1 class="phero-title"><?php echo esc_html( $title ); ?></h1>
			<?php if ( $subtitle ) : ?>
				<p class="phero-sub"><?php echo esc_html( $subtitle ); ?></p>
			<?php endif; ?>
		</div>
	</section>
	<?php
}

/**
 * การ์ดสถานะ "ยังไม่มีผลทดสอบที่เผยแพร่" (หน้า Backtest / Forward) · ไม่แสดงตัวเลขสมมติ
 */
function eab_results_pending( $type = 'backtest' ) {
	?>
	<div class="results-pending reveal">
		<?php echo eab_icon_badge( 'backtest' === $type ? 'candles' : 'pulse' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<div>
			<span class="card-label"><?php echo esc_html( eab_mod( 'results_pending_label' ) ? eab_mod( 'results_pending_label' ) : ( 'backtest' === $type ? 'Backtest' : 'Forward Test' ) ); ?></span>
			<h2><?php echo esc_html( eab_mod( 'results_pending_title' ) ); ?></h2>
			<p><?php echo esc_html( eab_mod( 'results_pending_text' ) ); ?></p>
		</div>
	</div>
	<?php
}

/**
 * เส้นทาง breadcrumb ของหน้าปัจจุบัน: หน้าแรก › (กลุ่ม) › หน้านี้
 */
function eab_breadcrumbs( $title = '' ) {
	$crumbs = array(
		array(
			'name' => eab_mod( 'nav_home_label' ) ? eab_mod( 'nav_home_label' ) : 'หน้าแรก',
			'url'  => home_url( '/' ),
		),
	);
	if ( is_front_page() ) {
		return $crumbs;
	}
	if ( is_singular( 'post' ) || is_home() || is_archive() || is_search() ) {
		$posts_page = (int) get_option( 'page_for_posts' );
		$crumbs[]   = array(
			'name' => 'บทความ',
			'url'  => $posts_page ? get_permalink( $posts_page ) : home_url( '/articles/' ),
		);
		if ( is_home() ) {
			array_pop( $crumbs );
			$crumbs[] = array(
				'name' => 'บทความ',
				'url'  => '',
			);
			return $crumbs;
		}
	} elseif ( is_page() && function_exists( 'eab_site_pages' ) ) {
		$slug   = (string) get_post_field( 'post_name', get_queried_object_id() );
		$pages  = eab_site_pages();
		$group  = isset( $pages[ $slug ]['group'] ) ? $pages[ $slug ]['group'] : '';
		$parent = array(
			'test'  => array( eab_mod( 'nav_test_label' ) ? eab_mod( 'nav_test_label' ) : 'การทดสอบ', '/backtest/' ),
			'guide' => array( eab_mod( 'nav_guide_label' ) ? eab_mod( 'nav_guide_label' ) : 'คู่มือการใช้งาน', '/how-to-install/' ),
		);
		// เมื่อมีปลั๊กอิน SEO: ให้ breadcrumb ที่มองเห็นตรงกับ BreadcrumbList ของปลั๊กอิน (ไม่ใส่ระดับกลุ่มที่ปลั๊กอินไม่รู้จัก)
		if ( isset( $parent[ $group ] ) && ! in_array( $slug, array( 'backtest', 'how-to-install' ), true ) && ! ( function_exists( 'eab_has_seo_plugin' ) && eab_has_seo_plugin() ) ) {
			$crumbs[] = array(
				'name' => $parent[ $group ][0],
				'url'  => home_url( $parent[ $group ][1] ),
			);
		}
	}
	$crumbs[] = array(
		'name' => $title ? $title : wp_strip_all_tags( get_the_title() ),
		'url'  => is_singular() ? get_permalink() : '',
	);
	return $crumbs;
}

/**
 * เนื้อหาแบบยาวของเพจ (จาก editor) + สารบัญอัตโนมัติ · ใช้ต่อท้ายเทมเพลตเพจที่มีส่วนออกแบบไว้ด้านบน
 */
function eab_page_longform( $section_class = 'section' ) {
	$content = apply_filters( 'the_content', get_the_content() );
	if ( '' === trim( wp_strip_all_tags( $content ) ) ) {
		return;
	}
	$toc = isset( $GLOBALS['eab_toc'] ) ? $GLOBALS['eab_toc'] : array();
	$toc = array_values(
		array_filter(
			$toc,
			function ( $item ) {
				return 2 === $item['level'];
			}
		)
	);
	?>
	<section class="<?php echo esc_attr( $section_class ); ?> longform">
		<div class="container">
			<div class="longform-layout<?php echo count( $toc ) > 2 ? '' : ' longform-layout--solo'; ?>">
				<?php if ( count( $toc ) > 2 ) : ?>
					<aside class="longform-toc" aria-label="<?php echo esc_attr( eab_mod( 'doc_toc_label' ) ? eab_mod( 'doc_toc_label' ) : 'สารบัญ' ); ?>">
						<details open>
							<summary><?php echo esc_html( eab_mod( 'doc_toc_label' ) ? eab_mod( 'doc_toc_label' ) : 'สารบัญ' ); ?></summary>
							<ol>
								<?php foreach ( $toc as $eab_item ) : ?>
									<li><a href="#<?php echo esc_attr( $eab_item['id'] ); ?>"><?php echo esc_html( $eab_item['text'] ); ?></a></li>
								<?php endforeach; ?>
							</ol>
						</details>
					</aside>
				<?php endif; ?>
				<div class="entry-content guide-content">
					<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</div>
			</div>
		</div>
	</section>
	<?php
}

/**
 * บล็อกติดต่อทีมงาน (LINE OA / OpenChat / QR + สิ่งที่ทีมจะถาม) · ใช้ปิดท้ายทุกหน้าย่อย
 */
function eab_line_cta( $title = '', $sub = '' ) {
	/* โมดูล chrome (inc/modules/chrome.php) เป็นผู้วาดแถบติดต่อท้ายหน้า · ที่นี่แค่ส่งต่อ */
	if ( has_action( 'eab_line_cta' ) ) {
		do_action( 'eab_line_cta', $title, $sub );
		return;
	}
	$title = $title ? $title : eab_mod( 'contact_title' );
	$sub   = $sub ? $sub : eab_mod( 'contact_text' );
	?>
	<section class="section contact-block<?php echo esc_attr( eab_section_tone( 'contact', true ) ); ?>" id="cta">
		<div class="container container-narrow">
			<div class="contact-console reveal">
				<div class="contact-main">
					<h2><?php echo esc_html( $title ); ?></h2>
					<p><?php echo esc_html( $sub ); ?></p>
					<div class="contact-actions"><?php eab_contact_button( array( 'class' => 'btn btn-fire btn-lg', 'pos' => 'page-cta' ) ); ?></div>
				</div>
			</div>
		</div>
	</section>
	<?php
}

/* --------------------------------------------------------------
 * Inline SVG icons
 * -------------------------------------------------------------- */
function eab_icon( $name, $class = 'icon' ) {
	$svg = array(
		'flame'    => '<path d="M12 2c1 4-3 5.5-3 9a3 3 0 0 0 6 0c0-1.2-.6-2.2-1.2-3.1C16.5 9.4 19 11.6 19 15a7 7 0 0 1-14 0c0-5 5-7.5 7-13z"/>',
		'pulse'    => '<path d="M3 12h4l2.5-6 4 12L16 12h5"/>',
		'clock'    => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3.5 2"/>',
		'gauge'    => '<path d="M4.5 19a9 9 0 1 1 15 0"/><path d="M12 13l4-4"/><circle cx="12" cy="14" r="1.6"/>',
		'flag'     => '<path d="M5 21V4"/><path d="M5 5h12l-2.5 3.5L17 12H5"/>',
		'home'     => '<path d="M4 11.5 12 5l8 6.5"/><path d="M6.5 10.5V20h11v-9.5"/><path d="M10 20v-5h4v5"/>',
		'chart'    => '<path d="M4 19V5"/><path d="M4 19h16"/><path d="M7 15l3-3 2.4 2.4L17.5 9"/><path d="M15 9h2.5v2.5"/>',
		'tag'      => '<path d="M20 12.5 12.5 20 4 11.5V4h7.5L20 12.5z"/><circle cx="8.2" cy="8.2" r="0.8"/>',
		'cpu'      => '<rect x="6" y="6" width="12" height="12" rx="2"/><rect x="10" y="10" width="4" height="4"/><path d="M9 2v3M15 2v3M9 19v3M15 19v3M2 9h3M2 15h3M19 9h3M19 15h3"/>',
		'candles'  => '<path d="M7 6v3M7 15v3M7 9h0a1.5 1.5 0 0 1 1.5 1.5v3A1.5 1.5 0 0 1 7 15h0a1.5 1.5 0 0 1-1.5-1.5v-3A1.5 1.5 0 0 1 7 9zM17 3v3M17 13v4M17 6h0a1.5 1.5 0 0 1 1.5 1.5v4A1.5 1.5 0 0 1 17 13h0a1.5 1.5 0 0 1-1.5-1.5v-4A1.5 1.5 0 0 1 17 6z"/><path d="M3 21h18"/>',
		'layout'   => '<rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 9h18M9 9v11"/>',
		'shield'   => '<path d="M12 3l7 3v5c0 4.6-3 8.4-7 10-4-1.6-7-5.4-7-10V6l7-3z"/><path d="M9 12l2 2 4-4"/>',
		'moon'     => '<path d="M20 14.5A8 8 0 1 1 9.5 4 6.5 6.5 0 0 0 20 14.5z"/>',
		'headset'  => '<path d="M4 13a8 8 0 0 1 16 0"/><rect x="3" y="13" width="4" height="6" rx="1.6"/><rect x="17" y="13" width="4" height="6" rx="1.6"/><path d="M19 19a3 3 0 0 1-3 3h-3"/>',
		'check'    => '<path d="M4 12.5l5 5L20 6.5"/>',
		'x'        => '<path d="M6 6l12 12M18 6L6 18"/>',
		'warn'     => '<path d="M12 3.5l9.5 16.5h-19L12 3.5z"/><path d="M12 10v4.2"/><circle cx="12" cy="17" r="0.4"/>',
		'chat'     => '<path d="M21 12a8 8 0 0 1-8 8c-1.2 0-2.4-.25-3.4-.7L4 21l1.4-4.2A8 8 0 1 1 21 12z"/><path d="M8.5 11h.01M12 11h.01M15.5 11h.01"/>',
		'arrow'    => '<path d="M5 12h14M13 6l6 6-6 6"/>',
		'quote'    => '<path d="M7.5 11c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3V9.5C10.5 7 9 5.5 7 5M17.5 11c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3V9.5C20.5 7 19 5.5 17 5"/>',
		'mail'     => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/>',
		'facebook' => '<path d="M14 8h2.5V4.5H14c-2.2 0-4 1.8-4 4V11H7.5v3.5H10v6h3.5v-6h2.6l.4-3.5h-3V8.7c0-.4.3-.7.5-.7z"/>',
		'download' => '<path d="M12 4v10M7.5 10.5L12 15l4.5-4.5"/><path d="M5 19h14"/>',
		'link'     => '<path d="M9.5 14.5l5-5"/><path d="M11.5 6.5l1-1a4 4 0 0 1 5.7 5.7l-2 2"/><path d="M12.5 17.5l-1 1a4 4 0 0 1-5.7-5.7l2-2"/>',
		'gear'     => '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.8l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-1.8-.3 1.7 1.7 0 0 0-1 1.5V21a2 2 0 1 1-4 0v-.1a1.7 1.7 0 0 0-1.1-1.5 1.7 1.7 0 0 0-1.8.3l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a1.7 1.7 0 0 0 .3-1.8 1.7 1.7 0 0 0-1.5-1H3a2 2 0 1 1 0-4h.1a1.7 1.7 0 0 0 1.5-1.1 1.7 1.7 0 0 0-.3-1.8l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a1.7 1.7 0 0 0 1.8.3H9a1.7 1.7 0 0 0 1-1.5V3a2 2 0 1 1 4 0v.1a1.7 1.7 0 0 0 1 1.5 1.7 1.7 0 0 0 1.8-.3l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a1.7 1.7 0 0 0-.3 1.8V9a1.7 1.7 0 0 0 1.5 1H21a2 2 0 1 1 0 4h-.1a1.7 1.7 0 0 0-1.5 1z"/>',
		'bars'     => '<path d="M6 20v-6M12 20V10M18 20V4"/>',
		'robot'    => '<rect x="4" y="8" width="16" height="12" rx="3"/><path d="M12 8V5"/><circle cx="12" cy="4" r="1"/><circle cx="9" cy="13.5" r="1"/><circle cx="15" cy="13.5" r="1"/><path d="M9.5 17h5"/>',
		'play'     => '<path d="M8 5.5v13l10.5-6.5L8 5.5z"/>',
		'bolt'     => '<path d="M13 2 4.5 13.5H11L10 22l8.5-11.5H12L13 2z"/>',
		'target'   => '<circle cx="12" cy="12" r="8.5"/><circle cx="12" cy="12" r="4.5"/><circle cx="12" cy="12" r="0.8"/>',
		'server'   => '<rect x="4" y="4" width="16" height="7" rx="1.6"/><rect x="4" y="13" width="16" height="7" rx="1.6"/><path d="M8 7.5h.01M8 16.5h.01"/>',
		'phone'    => '<rect x="7" y="2.5" width="10" height="19" rx="2.2"/><path d="M11 18.5h2"/>',
		'monitor'  => '<rect x="3" y="4" width="18" height="12" rx="1.8"/><path d="M8 20h8M12 16v4"/>',
		'calc'     => '<rect x="5" y="2.5" width="14" height="19" rx="2"/><path d="M8 6.5h8"/><path d="M8.5 11h.01M12 11h.01M15.5 11h.01M8.5 14.5h.01M12 14.5h.01M15.5 14.5h.01M8.5 18h.01M12 18h.01M15.5 18h.01"/>',
		'book'     => '<path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v15H6.5A2.5 2.5 0 0 0 4 20.5v-15z"/><path d="M4 20.5A2.5 2.5 0 0 1 6.5 18H20v3H6.5"/>',
		'lock'     => '<rect x="5" y="10.5" width="14" height="10" rx="2"/><path d="M8 10.5V7.5a4 4 0 0 1 8 0v3"/>',
		'user'     => '<circle cx="12" cy="8" r="4"/><path d="M4.5 20.5a7.5 7.5 0 0 1 15 0"/>',
		'trash'    => '<path d="M4 7h16M9.5 7V4.5h5V7M6.5 7l1 13h9l1-13"/>',
		'windows'  => '<path d="M3.5 5.5 10.5 4.5v7h-7zM12 4.3l8.5-1.3v8.5H12zM3.5 12.5h7v7l-7-1zM12 12.5h8.5V21L12 19.7z"/>',
		'apple'    => '<path d="M16.4 12.6c0-2.3 1.9-3.4 2-3.5-1.1-1.6-2.8-1.8-3.4-1.8-1.4-.1-2.8.9-3.5.9-.7 0-1.9-.9-3.1-.8-1.6 0-3 .9-3.8 2.3-1.6 2.8-.4 7 1.2 9.3.8 1.1 1.7 2.4 2.9 2.3 1.2 0 1.6-.7 3-.7s1.8.7 3.1.7c1.3 0 2.1-1.1 2.8-2.3.9-1.3 1.3-2.6 1.3-2.6s-2.5-1-2.5-3.8zM14.1 5.8c.6-.8 1.1-1.9 1-3-1 0-2.1.7-2.8 1.5-.6.7-1.1 1.8-1 2.9 1.1.1 2.1-.6 2.8-1.4z"/>',
		'android'  => '<path d="M6 10v6.5a1 1 0 0 0 1 1h1v3h2v-3h4v3h2v-3h1a1 1 0 0 0 1-1V10H6z"/><path d="M6.5 9a5.5 5.5 0 0 1 11 0z"/><path d="M8 4l1.5 2M16 4l-1.5 2"/>',
		'external' => '<path d="M14 4h6v6"/><path d="M20 4l-9 9"/><path d="M18 14v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h5"/>',
		'image'    => '<rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="8.5" cy="9.5" r="1.6"/><path d="M21 16l-5.5-5.5L6 20"/>',
		'flask'    => '<path d="M9 3h6"/><path d="M10 3v6L4.8 18.2A1.8 1.8 0 0 0 6.4 21h11.2a1.8 1.8 0 0 0 1.6-2.8L14 9V3"/><path d="M7.5 15h9"/>',
		'macos'    => '<rect x="3" y="4" width="18" height="12" rx="1.8"/><path d="M2 20h20"/>',
		'users'    => '<circle cx="9" cy="8" r="3.5"/><path d="M2.5 20a6.5 6.5 0 0 1 13 0"/><path d="M16 4.5a3.5 3.5 0 0 1 0 7M18.5 20a6.5 6.5 0 0 0-3-5.5"/>',
		'terminal' => '<rect x="3" y="4" width="18" height="16" rx="2"/><path d="M7 9l3 3-3 3M12.5 15H17"/>',
		'qr'       => '<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><path d="M14 14h3v3h-3zM20 14v.01M14 20h.01M17 20h4v-3"/>',
		'instagram' => '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.3" cy="6.7" r="0.6"/>',
		'tiktok'   => '<path d="M14 3v11.5a3.5 3.5 0 1 1-3.5-3.5"/><path d="M14 3c.5 2.6 2.3 4.3 5 4.5"/>',
		'youtube'  => '<rect x="2.5" y="5.5" width="19" height="13" rx="4"/><path d="M10 9.5v5l4.5-2.5z"/>',
		'dollar'   => '<path d="M12 2v20"/><path d="M17 6.5c-1-1.3-2.7-2-5-2-2.8 0-4.5 1.4-4.5 3.4 0 4.6 10 2.4 10 7.2 0 2-1.9 3.4-5 3.4-2.4 0-4.3-.8-5.3-2.3"/>',
	);

	// แบรนด์ไอคอน LINE (โลโก้จริง) · เป็น path แบบ fill ไม่ใช่ stroke จึง render แยก.
	if ( 'line' === $name ) {
		return '<svg class="' . esc_attr( $class ) . '" viewBox="0 0 24 24" fill="currentColor" stroke="none" aria-hidden="true" focusable="false"><path d="M19.365 9.863c.349 0 .63.285.63.631 0 .345-.281.63-.63.63H17.61v1.125h1.755c.348 0 .63.283.63.63 0 .344-.282.629-.63.629h-2.386c-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63h2.386c.346 0 .627.285.627.63 0 .349-.281.63-.63.63H17.61v1.125h1.755zm-3.855 3.016c0 .27-.174.51-.432.596-.064.021-.133.031-.199.031-.211 0-.391-.09-.51-.25l-2.443-3.317v2.94c0 .344-.279.629-.631.629-.346 0-.626-.285-.626-.629V8.108c0-.27.173-.51.43-.595.06-.023.136-.033.194-.033.195 0 .375.104.495.254l2.462 3.33V8.108c0-.345.282-.63.63-.63.345 0 .63.285.63.63v4.771zm-5.741 0c0 .344-.282.629-.631.629-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63.346 0 .628.285.628.63v4.771zm-2.466.629H4.917c-.345 0-.63-.285-.63-.629V8.108c0-.345.285-.63.63-.63.348 0 .63.285.63.63v4.141h1.756c.348 0 .629.283.629.63 0 .344-.282.629-.629.629M24 10.314C24 4.943 18.615.572 12 .572S0 4.943 0 10.314c0 4.811 4.27 8.842 10.035 9.608.391.082.923.258 1.058.59.12.301.079.766.038 1.08l-.164 1.02c-.045.301-.24 1.186 1.049.645 1.291-.539 6.916-4.078 9.436-6.975C23.176 14.393 24 12.458 24 10.314"/></svg>';
	}

	if ( ! isset( $svg[ $name ] ) ) {
		return '';
	}

	return '<svg class="' . esc_attr( $class ) . '" data-icon="' . esc_attr( $name ) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $svg[ $name ] . '</svg>';
}

/* --------------------------------------------------------------
 * Post views (ตัวนับยอดเข้าชมบทความ)
 * -------------------------------------------------------------- */
function eab_get_post_views( $post_id ) {
	return (int) get_post_meta( $post_id, 'eab_views', true );
}

function eab_increment_post_views( $post_id ) {
	if ( ! $post_id ) {
		return;
	}
	update_post_meta( $post_id, 'eab_views', eab_get_post_views( $post_id ) + 1 );
}

/* นับเฉพาะผู้เข้าชมหน้าบทความเดี่ยว (ข้ามแอดมิน เพื่อไม่ให้ตัวเลขเพี้ยน)
   หมายเหตุ: ถ้าใช้ปลั๊กแคชหน้า ตัวเลขอาจนับไม่ครบทุกครั้ง */
add_action(
	'wp_head',
	function () {
		if ( is_singular( 'post' ) && ! current_user_can( 'edit_posts' ) ) {
			eab_increment_post_views( get_queried_object_id() );
		}
	}
);



/* --------------------------------------------------------------
 * Table of Contents · เก็บหัวข้อ H2/H3 จากเนื้อหาบทความ + ใส่ id ให้ลิงก์
 * ($GLOBALS['eab_toc'] ถูกเติมตอน the_content ถูกประมวลผล)
 * -------------------------------------------------------------- */
function eab_collect_toc( $content ) {
	if ( ! ( is_singular( array( 'post', 'page' ) ) && is_main_query() && in_the_loop() ) ) {
		return $content;
	}

	$GLOBALS['eab_toc'] = array();
	$index                = 0;

	return preg_replace_callback(
		'/<(h[23])([^>]*)>(.*?)<\/\1>/is',
		function ( $matches ) use ( &$index ) {
			$index++;
			$tag   = strtolower( $matches[1] );
			$attrs = $matches[2];
			$inner = $matches[3];

			if ( preg_match( '/\bid=["\']([^"\']+)["\']/', $attrs, $id_match ) ) {
				$id = $id_match[1];
			} else {
				$id     = 'toc-' . $index;
				$attrs .= ' id="' . $id . '"';
			}

			$GLOBALS['eab_toc'][] = array(
				'level' => (int) substr( $tag, 1 ),
				'text'  => trim( wp_strip_all_tags( $inner ) ),
				'id'    => $id,
			);

			return '<' . $tag . $attrs . '>' . $inner . '</' . $tag . '>';
		},
		$content
	);
}
add_filter( 'the_content', 'eab_collect_toc', 20 );

/* ตัดคำนำหน้า "หมวดหมู่:" / "ป้ายกำกับ:" ออกจากหัวข้อหน้า archive */
add_filter( 'get_the_archive_title_prefix', '__return_empty_string' );

/* --------------------------------------------------------------
 * การ์ดบทความ (ใช้ร่วมกันที่ index และ AJAX โหลดเพิ่ม)
 * -------------------------------------------------------------- */
function eab_post_card() {
	$cats = get_the_category();
	$cat  = ! empty( $cats ) ? $cats[0] : null;
	?>
	<article <?php post_class( 'post-card' ); ?>>
		<?php if ( has_post_thumbnail() ) : ?>
			<a class="post-card-thumb" href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail( 'medium_large', array( 'alt' => function_exists( 'eab_pages_featured_alt' ) ? eab_pages_featured_alt( get_the_ID() ) : get_the_title() ) ); ?>
				<?php if ( $cat ) : ?>
					<span class="post-card-cat"><?php echo esc_html( $cat->name ); ?></span>
				<?php endif; ?>
			</a>
		<?php endif; ?>
		<div class="post-card-body">
			<span class="post-meta"><?php echo esc_html( get_the_date() ); ?></span>
			<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p>
			<span class="post-card-more">อ่านต่อ <?php echo eab_icon( 'arrow', 'icon icon-sm' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
		</div>
	</article>
	<?php
}

/* --------------------------------------------------------------
 * AJAX โหลดบทความเพิ่ม (ปุ่ม "โหลดเพิ่ม")
 * -------------------------------------------------------------- */
function eab_load_more() {
	check_ajax_referer( 'eab_load_more', 'nonce' );

	$page = isset( $_POST['page'] ) ? max( 1, (int) $_POST['page'] ) : 1;

	$incoming = array();
	if ( isset( $_POST['query'] ) ) {
		$decoded = json_decode( wp_unslash( $_POST['query'] ), true ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		if ( is_array( $decoded ) ) {
			$incoming = $decoded;
		}
	}

	// รับเฉพาะ query var ที่หน้าเว็บส่งมาได้จริง + sanitize ทีละค่า (กัน inject meta_query/tax_query หนัก ๆ)
	$query = array();
	if ( isset( $incoming['category_name'] ) ) {
		$query['category_name'] = sanitize_text_field( $incoming['category_name'] );
	}
	if ( isset( $incoming['cat'] ) ) {
		$query['cat'] = (int) $incoming['cat'];
	}
	if ( isset( $incoming['tag'] ) ) {
		$query['tag'] = sanitize_text_field( $incoming['tag'] );
	}
	if ( isset( $incoming['author'] ) ) {
		$query['author'] = (int) $incoming['author'];
	}
	if ( isset( $incoming['author_name'] ) ) {
		$query['author_name'] = sanitize_text_field( $incoming['author_name'] );
	}
	if ( isset( $incoming['s'] ) ) {
		$query['s'] = sanitize_text_field( $incoming['s'] );
	}

	// บังคับค่าที่ปลอดภัย ไม่ให้ฝั่ง client กำหนดเอง
	$query['paged']               = $page;
	$query['post_type']           = 'post';
	$query['post_status']         = 'publish';
	$query['posts_per_page']      = (int) get_option( 'posts_per_page' );
	$query['ignore_sticky_posts'] = true;

	$loop = new WP_Query( $query );
	if ( $loop->have_posts() ) {
		while ( $loop->have_posts() ) {
			$loop->the_post();
			eab_post_card();
		}
	}
	wp_reset_postdata();
	wp_die();
}
add_action( 'wp_ajax_eab_load_more', 'eab_load_more' );
add_action( 'wp_ajax_nopriv_eab_load_more', 'eab_load_more' );

/* --------------------------------------------------------------
 * Customizer
 * -------------------------------------------------------------- */
require get_template_directory() . '/inc/components.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/setup.php';
require get_template_directory() . '/inc/shortcodes.php';
require get_template_directory() . '/inc/seo.php';

/* โมดูลเพิ่มเติม (หน้าแรก, header/footer, คู่มือ, หน้าย่อย, /go, คุกกี้ ฯลฯ) · โหลดตามลำดับชื่อไฟล์ */
foreach ( (array) glob( get_template_directory() . '/inc/modules/*.php' ) as $eab_module_file ) {
	require $eab_module_file;
}
