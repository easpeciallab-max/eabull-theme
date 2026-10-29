<?php
/**
 * EA BULL · PDPA cookie card + GA4 / Meta Pixel IDs
 *
 * Look: mockup dev/mockup/_src/partials/cookie.html (card bottom-left on desktop, full-width
 * bottom sheet at 900px and below · base styles in style.css, extras in assets/css/consent.css).
 * Behaviour: assets/js/consent.js (contract in docs/phase2.md section 4).
 *
 * - The card is printed once, hidden, at wp_body_open (wp_footer as a fallback). PHP never reads
 *   the visitor's cookie, so page caches can serve one HTML to everybody; consent.js decides.
 * - No GA4 or Pixel tag is in the HTML. consent.js adds a tag only after its category is allowed.
 * - Google Consent Mode v2: when a GA4 ID exists, <head> sets every signal to denied first.
 * - The card only names services whose ID is filled in. With no ID the text says the site uses
 *   necessary cookies only and the card offers a single OK button (show_cookie_consent shows it
 *   on the first visit, as in the approved mockup).
 * - Category rows: necessary (always on) · analytics (only with a GA4 ID) · marketing (only with a
 *   Pixel ID; without it Google ad signals stay denied).
 * - Cookie eab_consent = v<version>.a<0|1|->.m<0|1|->.t<unix> · 365 days · SameSite=Lax.
 * - Anything matching a[href$="#cookie-settings"], .cookie-reopen or [data-cookie-settings] opens
 *   the settings view (helper: eab_consent_link()).
 * - API for other scripts: window.eabConsent.has('analytics'|'marketing') and the
 *   'eab:consent' event on document.
 *
 * @package eabull
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ==============================================================
 * Defaults
 * ============================================================== */

function eab_consent_defaults( $d ) {
	return array_merge(
		$d,
		array(
			'show_cookie_consent'      => true,
			'ga_measurement_id'        => '',
			'fb_pixel_id'              => '',
			'consent_version'          => '1',

			'consent_title'            => 'เว็บนี้ใช้คุกกี้',
			'consent_none_text'        => 'ตอนนี้ EA BULL ใช้เฉพาะคุกกี้ที่จำเป็นให้เว็บทำงาน เช่น จำค่าที่คุณเลือกในการ์ดนี้ ไม่มีคุกกี้วัดผลหรือโฆษณา',
			'cookie_consent_text'      => 'เราใช้คุกกี้ที่จำเป็นให้เว็บทำงาน และขอใช้ {tools} ซึ่งจะเริ่มทำงานหลังคุณอนุญาตเท่านั้น เลือกได้ว่าจะอนุญาตหรือไม่',
			'consent_tool_ga_label'    => 'Google Analytics นับจำนวนผู้เข้าชม',
			'consent_tool_pixel_label' => 'Meta Pixel วัดผลโฆษณา',
			'consent_tools_join'       => 'และ',
			'consent_policy_label'     => 'อ่านนโยบายคุกกี้',

			'consent_ok_label'         => 'ตกลง',
			'consent_reject_label'     => 'ปฏิเสธ',
			'consent_accept_label'     => 'ยอมรับทั้งหมด',
			'consent_prefs_label'      => 'ตั้งค่า',
			'consent_back_label'       => 'ย้อนกลับ',
			'consent_save_label'       => 'บันทึก',
			'consent_close_label'      => 'ปิด',

			'consent_necessary_name'   => 'คุกกี้ที่จำเป็น',
			'consent_necessary_desc'   => 'ให้เว็บทำงานและจำค่าที่คุณเลือกในการ์ดนี้',
			'consent_always_label'     => 'เปิดอยู่เสมอ',
			'consent_analytics_name'   => 'วิเคราะห์การใช้งาน',
			'consent_analytics_desc'   => 'Google Analytics นับผู้เข้าชมและหน้าที่เปิด ใช้รหัสสุ่มแทนชื่อของคุณ',
			'consent_marketing_name'   => 'การตลาด',
			'consent_marketing_desc'   => 'Meta Pixel ใช้นับว่ามีคนมาจากโฆษณาบน Facebook หรือ Instagram กี่คน',
			'consent_prefs_note'       => 'เปลี่ยนใจได้ทุกเมื่อจากลิงก์ "ตั้งค่าคุกกี้" ท้ายเว็บ',
			'consent_link_label'       => 'ตั้งค่าคุกกี้',
		)
	);
}
add_filter( 'eab_defaults', 'eab_consent_defaults' );

/* ==============================================================
 * Customizer · replaces the central cookie section (same place in the list)
 * ============================================================== */

function eab_consent_sections( $sections, $d ) {
	unset( $d );
	$own = array( 'show_cookie_consent', 'cookie_consent_text', 'ga_measurement_id', 'fb_pixel_id' );

	/* Tracking keys live in this section only. */
	foreach ( $sections as $section_id => $section ) {
		if ( 'eab_cookie' === $section_id || empty( $section['fields'] ) || ! is_array( $section['fields'] ) ) {
			continue;
		}
		foreach ( $own as $key ) {
			unset( $sections[ $section_id ]['fields'][ $key ] );
		}
	}

	$sections['eab_cookie'] = array(
		'title'       => '18) คุกกี้และความยินยอม · PDPA',
		'description' => 'การ์ดคุกกี้มุมซ้ายล่าง (มือถือเป็นแผ่นเต็มกว้างด้านล่าง) · ยังไม่ใส่รหัส GA4 หรือ Meta Pixel = การ์ดบอกว่าใช้เฉพาะคุกกี้ที่จำเป็นและมีปุ่มตกลงปุ่มเดียว · ใส่รหัสแล้ว การ์ดจะมีปุ่มยอมรับ ปฏิเสธ และตั้งค่าทีละหมวดให้เอง สคริปต์ของบริการโหลดหลังผู้เข้าชมอนุญาตเท่านั้น · อย่าติดตั้งปลั๊กอินที่ฝังแท็ก GA4 หรือ Pixel ซ้ำ เพราะจะโหลดก่อนได้รับอนุญาต · เพิ่มบริการหรือเปลี่ยนการใช้คุกกี้เมื่อไร ให้เพิ่มเลขรุ่นอีก 1 ทุกคนจะถูกถามใหม่',
		'fields'      => array(
			'ga_measurement_id'        => array( 'รหัส GA4 Measurement ID (G-XXXXXXXXXX)', 'text', 'ดูได้ที่ GA4 → Admin → Data streams · รูปแบบไม่ถูกต้องจะบันทึกไม่ได้' ),
			'fb_pixel_id'              => array( 'รหัส Meta Pixel ID (ตัวเลข 10 ถึง 20 หลัก)', 'text', 'ดูได้ที่ Meta Events Manager → Data sources · รูปแบบไม่ถูกต้องจะบันทึกไม่ได้' ),
			'show_cookie_consent'      => array( 'แสดงการ์ดตอนเข้าเว็บครั้งแรก แม้ยังไม่มีรหัสติดตาม', 'checkbox' ),
			'consent_version'          => array( 'เลขรุ่นของความยินยอม', 'text', 'เพิ่มเลขเมื่อเปลี่ยนการใช้คุกกี้ ระบบจะถามผู้เข้าชมทุกคนอีกครั้ง' ),
			'consent_title'            => array( 'หัวข้อการ์ด', 'text' ),
			'consent_none_text'        => array( 'ข้อความตอนที่ยังไม่ได้ใส่รหัส GA4 และ Pixel', 'textarea' ),
			'cookie_consent_text'      => array( 'ข้อความเมื่อมีรหัสติดตาม', 'textarea', '{tools} จะถูกแทนด้วยชื่อบริการที่ใส่รหัสไว้จริงจากสองช่องถัดไป' ),
			'consent_tool_ga_label'    => array( 'ชื่อบริการ GA4 ที่ใช้แทน {tools}', 'text' ),
			'consent_tool_pixel_label' => array( 'ชื่อบริการ Meta Pixel ที่ใช้แทน {tools}', 'text' ),
			'consent_tools_join'       => array( 'คำเชื่อมเมื่อมีทั้งสองบริการ', 'text' ),
			'consent_policy_label'     => array( 'ลิงก์ไปหัวข้อคุกกี้ในนโยบายความเป็นส่วนตัว', 'text', 'แสดงเมื่อหน้า privacy-policy เผยแพร่แล้ว' ),
			'consent_ok_label'         => array( 'ปุ่ม · ตกลง (เมื่อยังไม่มีรหัสติดตาม)', 'text' ),
			'consent_reject_label'     => array( 'ปุ่ม · ปฏิเสธ', 'text' ),
			'consent_accept_label'     => array( 'ปุ่ม · ยอมรับทั้งหมด', 'text' ),
			'consent_prefs_label'      => array( 'ปุ่ม · ไปหน้าตั้งค่าทีละหมวด', 'text' ),
			'consent_back_label'       => array( 'ปุ่ม · ย้อนกลับจากหน้าตั้งค่า', 'text' ),
			'consent_save_label'       => array( 'ปุ่ม · บันทึกตัวเลือก', 'text' ),
			'consent_close_label'      => array( 'ปุ่ม X · คำอ่านสำหรับโปรแกรมอ่านจอ', 'text' ),
			'consent_necessary_name'   => array( 'หมวดจำเป็น · ชื่อ', 'text' ),
			'consent_necessary_desc'   => array( 'หมวดจำเป็น · คำอธิบาย', 'textarea' ),
			'consent_always_label'     => array( 'หมวดจำเป็น · ป้ายแทนสวิตช์', 'text' ),
			'consent_analytics_name'   => array( 'หมวดวิเคราะห์ (GA4) · ชื่อ', 'text', 'หมวดนี้แสดงเมื่อมีรหัส GA4 เท่านั้น' ),
			'consent_analytics_desc'   => array( 'หมวดวิเคราะห์ (GA4) · คำอธิบาย', 'textarea' ),
			'consent_marketing_name'   => array( 'หมวดการตลาด (Meta Pixel) · ชื่อ', 'text', 'หมวดนี้แสดงเมื่อมีรหัส Meta Pixel เท่านั้น' ),
			'consent_marketing_desc'   => array( 'หมวดการตลาด (Meta Pixel) · คำอธิบาย', 'textarea' ),
			'consent_prefs_note'       => array( 'หมายเหตุใต้รายการหมวด', 'text' ),
			'consent_link_label'       => array( 'ชื่อปุ่ม "ตั้งค่าคุกกี้" (ท้ายเว็บ หน้า /go)', 'text' ),
		),
	);

	return $sections;
}
add_filter( 'eab_customizer_sections', 'eab_consent_sections', 30, 2 );

/* ==============================================================
 * Tracking ID formats
 * ============================================================== */

/**
 * GA4 Measurement ID (G-XXXX) or ''.
 */
function eab_consent_sanitize_ga_id( $value ) {
	$value = strtoupper( preg_replace( '/\s+/', '', (string) $value ) );
	return preg_match( '/^G-[A-Z0-9]{4,20}$/', $value ) ? $value : '';
}

/**
 * Meta Pixel ID (10 to 20 digits) or ''.
 */
function eab_consent_sanitize_pixel_id( $value ) {
	$value = preg_replace( '/\s+/', '', (string) $value );
	return preg_match( '/^\d{10,20}$/', $value ) ? $value : '';
}

/**
 * Consent version: whole number from 1.
 */
function eab_consent_sanitize_version( $value ) {
	return (string) max( 1, absint( $value ) );
}

/**
 * A malformed ID is refused in the Customizer with a message next to the field.
 */
function eab_consent_validate_id( $validity, $value, $setting = null ) {
	$value = trim( (string) $value );
	if ( '' === $value || ! is_object( $setting ) || ! isset( $setting->id ) || ! is_object( $validity ) ) {
		return $validity;
	}
	if ( 'ga_measurement_id' === $setting->id && '' === eab_consent_sanitize_ga_id( $value ) ) {
		$validity->add( 'eab_bad_ga_id', 'รหัส GA4 ต้องขึ้นต้นด้วย G- ตามด้วยตัวอักษรหรือตัวเลข เช่น G-AB12CD34EF (คัดลอกจาก GA4 → Admin → Data streams)' );
	}
	if ( 'fb_pixel_id' === $setting->id && '' === eab_consent_sanitize_pixel_id( $value ) ) {
		$validity->add( 'eab_bad_pixel_id', 'รหัส Meta Pixel ต้องเป็นตัวเลขล้วน 10 ถึง 20 หลัก (คัดลอกจาก Meta Events Manager → Data sources)' );
	}
	return $validity;
}

add_filter( 'customize_sanitize_ga_measurement_id', 'eab_consent_sanitize_ga_id', 20 );
add_filter( 'customize_sanitize_fb_pixel_id', 'eab_consent_sanitize_pixel_id', 20 );
add_filter( 'customize_sanitize_consent_version', 'eab_consent_sanitize_version', 20 );
add_filter( 'customize_validate_ga_measurement_id', 'eab_consent_validate_id', 10, 3 );
add_filter( 'customize_validate_fb_pixel_id', 'eab_consent_validate_id', 10, 3 );

/* ==============================================================
 * Values in use
 * ============================================================== */

/**
 * Checked tracking IDs (values saved before validation existed are filtered here too).
 *
 * @return array { ga: string, pixel: string }
 */
function eab_consent_ids() {
	return array(
		'ga'    => eab_consent_sanitize_ga_id( eab_mod( 'ga_measurement_id' ) ),
		'pixel' => eab_consent_sanitize_pixel_id( eab_mod( 'fb_pixel_id' ) ),
	);
}

function eab_consent_version() {
	return max( 1, absint( eab_mod( 'consent_version' ) ) );
}

/**
 * Names of the services that have an ID (GA4 first) · '' when none.
 */
function eab_consent_tools_text() {
	$ids    = eab_consent_ids();
	$labels = array();
	if ( '' !== $ids['ga'] ) {
		$labels[] = trim( (string) eab_mod( 'consent_tool_ga_label' ) );
	}
	if ( '' !== $ids['pixel'] ) {
		$labels[] = trim( (string) eab_mod( 'consent_tool_pixel_label' ) );
	}
	$labels = array_values( array_filter( $labels, 'strlen' ) );
	if ( count( $labels ) < 2 ) {
		return $labels ? $labels[0] : '';
	}
	$last = array_pop( $labels );
	$join = trim( (string) eab_mod( 'consent_tools_join' ) );
	return implode( ' ', $labels ) . ' ' . ( '' !== $join ? $join . ' ' : '' ) . $last;
}

/**
 * Intro text: the "necessary only" wording without IDs, otherwise cookie_consent_text with {tools}.
 */
function eab_consent_intro_text() {
	$ids = eab_consent_ids();
	if ( '' === $ids['ga'] && '' === $ids['pixel'] ) {
		return trim( (string) eab_mod( 'consent_none_text' ) );
	}
	$text = str_replace( '{tools}', eab_consent_tools_text(), (string) eab_mod( 'cookie_consent_text' ) );
	return trim( preg_replace( '/[ \t]{2,}/', ' ', $text ) );
}

/**
 * Cookie section of the privacy policy · '' until the page is published.
 */
function eab_consent_policy_url() {
	$url = function_exists( 'eab_published_page_url' ) ? eab_published_page_url( 'privacy-policy' ) : '';
	return $url ? $url . '#cookies' : '';
}

/**
 * "ตั้งค่าคุกกี้" opener for the footer, /go and content.
 *
 * @param array $args {
 *     @type string $tag   'a' (link to #cookie-settings, default) or 'button'.
 *     @type string $class Extra classes ('linkish' for the footer row).
 *     @type string $label Text ('' = consent_link_label).
 *     @type bool   $echo  Print as well as return.
 * }
 * @return string
 */
function eab_consent_link( $args = array() ) {
	$args  = wp_parse_args(
		$args,
		array(
			'tag'   => 'a',
			'class' => '',
			'label' => '',
			'echo'  => true,
		)
	);
	$label = '' !== trim( (string) $args['label'] ) ? trim( (string) $args['label'] ) : trim( (string) eab_mod( 'consent_link_label' ) );
	if ( '' === $label ) {
		return '';
	}
	if ( 'button' === $args['tag'] ) {
		$html = '<button type="button" class="' . esc_attr( trim( (string) $args['class'] ) ) . '" data-cookie-settings>' . esc_html( $label ) . '</button>';
	} else {
		$html = '<a class="' . esc_attr( trim( 'cookie-reopen ' . $args['class'] ) ) . '" href="#cookie-settings">' . esc_html( $label ) . '</a>';
	}
	if ( $args['echo'] ) {
		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above
	}
	return $html;
}

/* ==============================================================
 * Settings for consent.js · default Consent Mode
 * ============================================================== */

/**
 * window.eabConsentConfig (the same for every visitor, so pages stay cacheable).
 */
function eab_consent_script_data() {
	if ( function_exists( 'wp_script_is' ) && ! wp_script_is( 'eab-consent-js', 'enqueued' ) ) {
		return;
	}
	$ids = eab_consent_ids();
	wp_localize_script(
		'eab-consent-js',
		'eabConsentConfig',
		array(
			'version' => (string) eab_consent_version(),
			'ga'      => $ids['ga'],
			'pixel'   => $ids['pixel'],
			'force'   => eab_mod( 'show_cookie_consent' ) ? '1' : '',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'eab_consent_script_data', 20 );

/**
 * Consent Mode v2 defaults (all denied), printed at the top of <head> before any Google tag.
 * Uses a local function instead of window.gtag so nothing can send events before consent.
 */
function eab_consent_mode_default() {
	if ( is_admin() ) {
		return;
	}
	$ids = eab_consent_ids();
	if ( '' === $ids['ga'] ) {
		return;
	}
	$js = "window.dataLayer=window.dataLayer||[];(function(){function g(){window.dataLayer.push(arguments);}g('consent','default',{ad_storage:'denied',ad_user_data:'denied',ad_personalization:'denied',analytics_storage:'denied'});g('set','ads_data_redaction',true);})();window.eabConsentDefault=true;";
	if ( function_exists( 'wp_print_inline_script_tag' ) ) {
		wp_print_inline_script_tag( $js );
		return;
	}
	echo '<script>' . $js . "</script>\n"; // phpcs:ignore WordPress.Security.EscapeOutput -- static string
}
add_action( 'wp_head', 'eab_consent_mode_default', 1 );

/* ==============================================================
 * The card
 * ============================================================== */

/**
 * One row of the settings view.
 */
function eab_consent_row( $key, $name, $desc, $locked ) {
	$id   = 'consent-' . $key;
	$html = '<div class="ckrow"' . ( $locked ? '' : ' data-cat="' . esc_attr( $key ) . '" hidden' ) . '>';
	$html .= '<div><b id="' . esc_attr( $id ) . '-name">' . esc_html( $name ) . '</b>';
	if ( '' !== trim( (string) $desc ) ) {
		$html .= '<p id="' . esc_attr( $id ) . '-desc">' . esc_html( $desc ) . '</p>';
	}
	$html .= '</div>';
	if ( $locked ) {
		$html .= '<span class="ckrow__lock">' . esc_html( eab_mod( 'consent_always_label' ) ) . '</span>';
	} else {
		$html .= '<label class="switch"><input type="checkbox" role="switch" name="' . esc_attr( $key ) . '" aria-labelledby="' . esc_attr( $id ) . '-name"' . ( '' !== trim( (string) $desc ) ? ' aria-describedby="' . esc_attr( $id ) . '-desc"' : '' ) . '><span></span></label>';
	}
	return $html . '</div>';
}

/**
 * Cookie card · always printed hidden: without JS nobody sees it and no tracking runs.
 * Printed once per page (wp_body_open first, wp_footer as a fallback).
 */
function eab_consent_card() {
	static $printed = false;
	if ( $printed || is_admin() ) {
		return;
	}
	$printed = true;

	$ids    = eab_consent_ids();
	$mode   = ( '' === $ids['ga'] && '' === $ids['pixel'] ) ? 'none' : 'optional';
	$policy = eab_consent_policy_url();
	$plabel = trim( (string) eab_mod( 'consent_policy_label' ) );
	$note   = trim( (string) eab_mod( 'consent_prefs_note' ) );

	$btn = function ( $action, $class, $label_key, $when = '', $icon = '', $after = false ) use ( $mode ) {
		$label = esc_html( eab_mod( $label_key ) );
		$svg   = '' !== $icon ? eab_icon( $icon ) : '';
		return '<button type="button" class="btn ' . esc_attr( $class ) . ' btn--sm" data-consent="' . esc_attr( $action ) . '"'
			. ( '' !== $when ? ' data-when="' . esc_attr( $when ) . '"' . ( $when !== $mode ? ' hidden' : '' ) : '' ) . '>'
			. ( $after ? $label . ' ' . $svg : $svg . $label ) . '</button>';
	};
	?>
<div class="cookie dark" id="cookie-settings" role="dialog" aria-labelledby="consent-title" data-view="intro" hidden>
	<div class="cookie__head">
		<span class="tile tile--accent" aria-hidden="true"><?php echo eab_icon( 'cookie' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
		<p class="cookie__title" id="consent-title" tabindex="-1"><?php echo esc_html( eab_mod( 'consent_title' ) ); ?></p>
		<button type="button" class="cookie__x" data-consent="close" aria-label="<?php echo esc_attr( eab_mod( 'consent_close_label' ) ); ?>"><?php echo eab_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></button>
	</div>

	<div class="cookie__view" data-show="intro">
		<p><?php echo esc_html( eab_consent_intro_text() ); ?></p>
		<?php if ( '' !== $policy && '' !== $plabel ) : ?>
			<a class="cookie__more" href="<?php echo esc_url( $policy ); ?>"><?php echo esc_html( $plabel ); ?></a>
		<?php endif; ?>
		<div class="cookie__btns" data-when="none"<?php echo 'none' !== $mode ? ' hidden' : ''; ?>>
			<?php echo $btn( 'ok', 'btn--primary', 'consent_ok_label' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
		<div class="cookie__btns" data-when="optional"<?php echo 'optional' !== $mode ? ' hidden' : ''; ?>>
			<?php echo $btn( 'reject', 'btn--outline', 'consent_reject_label' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<?php echo $btn( 'accept', 'btn--primary', 'consent_accept_label' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<?php echo $btn( 'prefs', 'btn--light', 'consent_prefs_label', '', 'arrow-right', true ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
	</div>

	<div class="cookie__view" data-show="prefs" hidden>
		<?php echo eab_consent_row( 'necessary', eab_mod( 'consent_necessary_name' ), eab_mod( 'consent_necessary_desc' ), true ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<?php if ( '' !== $ids['ga'] ) : ?>
			<?php echo eab_consent_row( 'analytics', eab_mod( 'consent_analytics_name' ), eab_mod( 'consent_analytics_desc' ), false ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<?php endif; ?>
		<?php if ( '' !== $ids['pixel'] ) : ?>
			<?php echo eab_consent_row( 'marketing', eab_mod( 'consent_marketing_name' ), eab_mod( 'consent_marketing_desc' ), false ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<?php endif; ?>
		<?php if ( '' !== $note ) : ?>
			<p class="cookie__note"><?php echo esc_html( $note ); ?></p>
		<?php endif; ?>
		<div class="cookie__btns">
			<?php echo $btn( 'back', 'btn--outline', 'consent_back_label', '', 'chevron-left' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<?php echo $btn( 'save', 'btn--primary', 'consent_save_label', 'optional' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<?php echo $btn( 'ok', 'btn--primary', 'consent_ok_label', 'none' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
	</div>
</div>
	<?php
}
add_action( 'wp_body_open', 'eab_consent_card', 5 );
add_action( 'wp_footer', 'eab_consent_card', 5 );
