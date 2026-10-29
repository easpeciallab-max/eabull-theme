<?php
/**
 * EA BULL · PDPA consent card + GA4 / Meta Pixel IDs
 *
 * วิธีทำงาน
 * - eab_consent_card() พิมพ์ต้น <body> ทาง wp_body_open (เป็นจุดแรกในลำดับ Tab เมื่อการ์ดเปิด) · เทมเพลตที่ไม่เรียก wp_body_open
 *   ได้การ์ดทาง wp_footer (priority 5) แทน · หน้า /go/ แบบไม่มีกรอบธีมก็ได้การ์ดด้วย
 * - หน้าตา Glass Sky (assets/css/consent.css): เดสก์ท็อปเป็นการ์ดกระจกลอยซ้ายล่าง · มือถือเป็น bottom sheet ที่อยู่เหนือแถบเมนูล่าง
 *   ปุ่มยอมรับ = กรมท่า (.btn-dark) · ปฏิเสธ = กระจก (.btn-ghost) · เลือกทีละหมวด = ปุ่มแบบลิงก์ · สวิตช์หมวดเป็นทองเมื่อเปิด
 * - ฝั่ง PHP ไม่แตะคุกกี้ของผู้เข้าชม เพื่อให้ปลั๊กอินแคชเก็บหน้าเดียวใช้ได้กับทุกคน · การแสดง/ซ่อนการ์ดเป็นงานของ assets/js/consent.js
 * - HTML ไม่มีสคริปต์ GA4 หรือ Pixel ติดมา · consent.js ค่อยใส่สคริปต์ของหมวดที่ผู้เข้าชมกดอนุญาต
 * - Consent Mode v2: <head> ประกาศทุกสัญญาณเป็น denied ไว้ก่อน แล้ว consent.js ส่ง update เมื่อได้รับอนุญาต
 * - เงื่อนไขเปิดการ์ดอัตโนมัติ: มีรหัส GA4/Pixel ถูกรูปแบบ หรือเจ้าของเว็บติ๊ก show_cookie_consent
 * - การ์ดพูดถึงเฉพาะบริการที่ใส่รหัสไว้จริง: {tools} ใน cookie_consent_text = ชื่อบริการที่มีรหัส · ไม่มีรหัสใดเลย = consent_none_text
 *   หมวดวิเคราะห์/การตลาดพิมพ์เมื่อมีรหัส GA4/Pixel ของหมวดนั้น · ไม่มี Pixel = ไม่มีหมวดการตลาด สัญญาณโฆษณาของ Google จึง denied ตลอด
 * - รูปแบบคุกกี้ eab_consent: v<รุ่น>.a<0|1|->.m<0|1|->.t<unix> ("-" คือหมวดที่ตอนตอบยังไม่มีบริการ) · 365 วัน · SameSite=Lax
 * - คำบนการ์ดแก้ได้ทั้งหมดที่ ปรับแต่ง → หมวด 18 (eab_cookie)
 * - เปิดการ์ดซ้ำจากที่ไหนก็ได้ด้วย <a href="#cookie-settings"> หรือคลาส .cookie-reopen (ตัวช่วย: eab_consent_link())
 * - API สำหรับสคริปต์อื่น: window.eabConsent.has('analytics'|'marketing') และเหตุการณ์ 'eab:consent' บน document
 *
 * @package eabull
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ==============================================================
 * ค่าเริ่มต้น (ข้อความของ EA BULL เอง)
 * ============================================================== */

function eab_consent_defaults( $d ) {
	return array_merge(
		$d,
		array(
			/* คีย์เดิมที่ใช้ต่อ · show_cookie_consent = แสดงการ์ดแม้ยังไม่มีรหัสติดตาม · cookie_consent_text = ข้อความชั้นแรก ({tools} = บริการที่ใส่รหัสไว้) */
			'show_cookie_consent'      => false,
			'cookie_consent_text'      => 'EA BULL เปิดเฉพาะคุกกี้ที่จำเป็นต่อการทำงานของเว็บไว้ก่อน ส่วน {tools} จะเริ่มทำงานหลังคุณกดยอมรับเท่านั้น เปลี่ยนตัวเลือกภายหลังได้จากลิงก์ "ตั้งค่าคุกกี้"',
			'ga_measurement_id'        => '',
			'fb_pixel_id'              => '',
			'consent_tool_ga_label'    => 'Google Analytics (วิเคราะห์การใช้งาน)',
			'consent_tool_pixel_label' => 'Meta Pixel (การตลาด)',
			'consent_tools_join'       => 'และ',

			'consent_version'          => '1',
			'consent_kicker'           => 'คุกกี้และ PDPA',
			'consent_title'            => 'เลือกคุกกี้ที่คุณยินยอมให้ใช้',
			'consent_policy_label'     => 'อ่านนโยบายคุกกี้',
			'consent_accept_label'     => 'ยอมรับทั้งหมด',
			'consent_reject_label'     => 'ปฏิเสธ',
			'consent_prefs_label'      => 'ตั้งค่า',
			'consent_save_label'       => 'บันทึก',
			'consent_ok_label'         => 'ตกลง',
			'consent_close_label'      => 'ปิดหน้าต่างคุกกี้',
			'consent_always_label'     => 'เปิดอยู่เสมอ',
			'consent_detail_label'     => 'คุกกี้ในหมวดนี้',
			'consent_necessary_name'   => 'คุกกี้ที่จำเป็น',
			'consent_necessary_desc'   => 'ทำให้เว็บไซต์ทำงานได้และจดจำตัวเลือกคุกกี้ของคุณ หมวดนี้เปิดตลอด ปิดไม่ได้ และไม่ใช้ระบุตัวตนของผู้เข้าชม',
			'consent_necessary_detail' => "eab_consent · เก็บตัวเลือกของแต่ละหมวด เลขรุ่นความยินยอม และเวลาที่บันทึก · อายุ 365 วัน\neabConsentDismissed · จำไว้ในแท็บนี้ว่าคุณปิดหน้าต่างคุกกี้แล้ว · หายไปเมื่อปิดแท็บ",
			'consent_analytics_name'   => 'วิเคราะห์การใช้งาน',
			'consent_analytics_desc'   => 'Google Analytics ช่วยนับจำนวนผู้เข้าชม หน้าที่ถูกเปิด และปุ่มที่ถูกกด เพื่อปรับเนื้อหาให้ใช้งานง่ายขึ้น เริ่มทำงานหลังคุณยินยอมเท่านั้น',
			'consent_analytics_detail' => "ผู้ให้บริการ · Google (Google Analytics 4)\n_ga · รหัสสุ่มที่แยกเบราว์เซอร์หนึ่งออกจากอีกเบราว์เซอร์ โดยไม่รู้ว่าผู้ใช้เป็นใคร · ไม่เกินประมาณ 2 ปี\n_ga_* · บันทึกสถานะของการเข้าชมรอบปัจจุบัน · ไม่เกินประมาณ 2 ปี",
			/* ต่อท้ายรายละเอียดหมวดวิเคราะห์ เมื่อมี GA4 แต่ไม่มี Meta Pixel (ไม่มีหมวดการตลาดให้เปิด สัญญาณโฆษณาจึง denied ตลอด) */
			'consent_ads_off_detail'   => 'สัญญาณโฆษณา (Google Consent Mode) · ปิดอยู่เสมอ เพราะเว็บนี้ไม่มีหมวดการตลาดให้เปิด Google จึงไม่ได้รับอนุญาตให้ใช้ข้อมูลการเข้าชมเพื่องานโฆษณา',
			'consent_marketing_name'   => 'การตลาด',
			'consent_marketing_desc'   => 'Meta Pixel วัดว่าโฆษณาบน Facebook และ Instagram พาคนมาที่เว็บไซต์ได้แค่ไหน และใช้กำหนดกลุ่มผู้ชมโฆษณา เริ่มทำงานหลังคุณยินยอมเท่านั้น',
			'consent_marketing_detail' => "ผู้ให้บริการ · Meta Platforms (Meta Pixel)\n_fbp · ระบุเบราว์เซอร์เพื่อวัดผลโฆษณา · ประมาณ 90 วัน\n_fbc · จดรหัสคลิกของโฆษณาที่พาคุณมายังเว็บ · ประมาณ 90 วัน",
			/* ต่อท้ายรายละเอียดหมวดการตลาด เมื่อมีทั้ง Meta Pixel และ GA4 */
			'consent_marketing_google_detail' => "ผู้ให้บริการ · Google (Google Analytics 4)\n_gcl_au / _gcl_* · ผูกการเข้าชมเข้ากับแคมเปญโฆษณาบนเครือข่าย Google · ประมาณ 90 วัน\nสัญญาณโฆษณา (Google Consent Mode) · อนุญาตให้ Google ใช้ข้อมูลการเข้าชมเพื่องานโฆษณา · มีผลเมื่อเปิดหมวดนี้เท่านั้น",
			'consent_none_text'        => 'เว็บนี้ใช้แค่คุกกี้ที่จำเป็นในขณะนี้ เพราะยังไม่ได้เชื่อม Google Analytics หรือ Meta Pixel',
			'consent_prefs_note'       => 'เปลี่ยนตัวเลือกได้ทุกเมื่อจากลิงก์ "ตั้งค่าคุกกี้" ท้ายหน้าเว็บ การปฏิเสธไม่มีผลกับการอ่านเนื้อหาหรือการติดต่อทีมงานทาง LINE',
			'consent_link_label'       => 'ตั้งค่าคุกกี้',
		)
	);
}
add_filter( 'eab_defaults', 'eab_consent_defaults' );

/* ==============================================================
 * Customizer · แทนหมวดคุกกี้เดิมทั้งหมวด (คงตำแหน่งเดิมในรายการ)
 * ============================================================== */

function eab_consent_sections( $sections, $d ) {
	$own = array( 'show_cookie_consent', 'cookie_consent_text', 'ga_measurement_id', 'fb_pixel_id' );

	/* ย้ายฟิลด์ติดตาม/คุกกี้ออกจากหมวดอื่น (เช่น eab_seo) ให้อยู่ที่นี่ที่เดียว */
	foreach ( $sections as $section_id => $section ) {
		if ( 'eab_cookie' === $section_id || empty( $section['fields'] ) || ! is_array( $section['fields'] ) ) {
			continue;
		}
		foreach ( $own as $key ) {
			unset( $sections[ $section_id ]['fields'][ $key ] );
		}
	}

	$detail_hint = 'บรรทัดละ 1 รายการ · รูปแบบ "ชื่อคุกกี้ · ใช้ทำอะไร · อายุ" · ชื่อคุกกี้แสดงตามตัวพิมพ์จริง';

	$sections['eab_cookie'] = array(
		'title'       => '18) คุกกี้ & ความยินยอม · PDPA',
		'description' => 'การ์ดนี้แสดงเองทันทีที่มี GA4 Measurement ID หรือ Meta Pixel ID ถูกรูปแบบอย่างน้อยหนึ่งตัว (ติ๊กช่องด้านล่างถ้าอยากให้แสดงตั้งแต่ยังไม่มีรหัส) · สคริปต์ของแต่ละบริการเริ่มทำงานหลังผู้เข้าชมกดอนุญาตหมวดของบริการนั้นแล้วเท่านั้น · ห้ามให้ปลั๊กอินอื่นฝังแท็กซ้ำ (เช่น Site Kit หรือปลั๊กอิน Pixel) เพราะจะทำงานก่อนได้รับอนุญาต · เมื่อเพิ่มบริการหรือเปลี่ยนวัตถุประสงค์ของคุกกี้ ให้บวกเลขรุ่นขึ้น 1 ระบบจะขอความยินยอมจากทุกคนอีกรอบ · ลิงก์นโยบายโผล่เมื่อหน้า privacy-policy เผยแพร่แล้ว (ชี้ไปหัวข้อ #cookies) · ลิงก์ใด ๆ ที่ชี้ไป #cookie-settings จะเปิดการ์ดนี้',
		'fields'      => array(
			'ga_measurement_id'        => array( 'GA4 Measurement ID (รูปแบบ G-XXXXXXXXXX)', 'text', 'หาได้ใน GA4 → Admin → Data streams · ถ้ารูปแบบผิด ระบบจะไม่ยอมบันทึก' ),
			'fb_pixel_id'              => array( 'Meta Pixel ID (ตัวเลข 10 ถึง 20 หลัก)', 'text', 'หาได้ใน Meta Events Manager → Data sources · ถ้ารูปแบบผิด ระบบจะไม่ยอมบันทึก' ),
			'show_cookie_consent'      => array( 'แสดงการ์ดคุกกี้แม้ยังไม่ได้ใส่รหัสติดตาม', 'checkbox' ),
			'consent_version'          => array( 'เลขรุ่นของความยินยอม', 'text', 'บวกเลขขึ้นเมื่อเปลี่ยนวิธีใช้คุกกี้ ระบบจะขอความยินยอมใหม่จากทุกคน' ),
			'consent_kicker'           => array( 'การ์ด · ข้อความเล็กเหนือหัวข้อ', 'text' ),
			'consent_title'            => array( 'การ์ด · หัวข้อ', 'text' ),
			'cookie_consent_text'      => array( 'การ์ด · ข้อความหลัก (ก่อนกดเลือกทีละหมวด)', 'textarea', '{tools} = ชื่อบริการที่ใส่รหัสไว้จริงเท่านั้น (สองช่องถัดไป) · ถ้ายังไม่มีรหัสใดเลย การ์ดใช้ "ข้อความเมื่อยังไม่ได้ใส่รหัสติดตามใด ๆ" แทน' ),
			'consent_tool_ga_label'    => array( 'การ์ด · ชื่อบริการ GA4 ที่ใช้แทน {tools}', 'text', 'แสดงเมื่อมี GA4 Measurement ID' ),
			'consent_tool_pixel_label' => array( 'การ์ด · ชื่อบริการ Meta Pixel ที่ใช้แทน {tools}', 'text', 'แสดงเมื่อมี Meta Pixel ID' ),
			'consent_tools_join'       => array( 'การ์ด · คำเชื่อมเมื่อมีทั้งสองบริการ', 'text' ),
			'consent_policy_label'     => array( 'การ์ด · คำบนลิงก์ไปนโยบายคุกกี้', 'text' ),
			'consent_accept_label'     => array( 'ข้อความปุ่ม · ยอมรับทุกหมวด', 'text' ),
			'consent_reject_label'     => array( 'ข้อความปุ่ม · ปฏิเสธทุกหมวดที่ไม่จำเป็น', 'text' ),
			'consent_prefs_label'      => array( 'ข้อความปุ่ม · ไปหน้าเลือกทีละหมวด', 'text' ),
			'consent_save_label'       => array( 'ข้อความปุ่ม · บันทึกตัวเลือกที่ติ๊กไว้', 'text' ),
			'consent_ok_label'         => array( 'ข้อความปุ่ม · ตกลง (ใช้เมื่อยังไม่มีรหัสติดตาม)', 'text' ),
			'consent_close_label'      => array( 'ปุ่ม X · คำอ่านสำหรับผู้ใช้โปรแกรมอ่านจอ', 'text' ),
			'consent_always_label'     => array( 'ป้ายบนหมวดจำเป็น (ปิดไม่ได้)', 'text' ),
			'consent_detail_label'     => array( 'คำบนปุ่มเปิดรายละเอียดคุกกี้ของแต่ละหมวด', 'text' ),
			'consent_necessary_name'   => array( 'หมวดจำเป็น · ชื่อหมวด', 'text' ),
			'consent_necessary_desc'   => array( 'หมวดจำเป็น · คำอธิบายสั้น', 'textarea' ),
			'consent_necessary_detail' => array( 'หมวดจำเป็น · รายละเอียดคุกกี้', 'textarea', $detail_hint ),
			'consent_analytics_name'   => array( 'หมวดวัดสถิติ (GA4) · ชื่อหมวด', 'text' ),
			'consent_analytics_desc'   => array( 'หมวดวัดสถิติ (GA4) · คำอธิบายสั้น', 'textarea' ),
			'consent_analytics_detail' => array( 'หมวดวัดสถิติ (GA4) · รายละเอียดคุกกี้', 'textarea', $detail_hint ),
			'consent_ads_off_detail'   => array( 'หมวดวัดสถิติ (GA4) · บรรทัดต่อท้ายเมื่อไม่มี Meta Pixel', 'textarea', 'ไม่มีหมวดการตลาด สัญญาณโฆษณาของ Google จึงปิดตลอด · บรรทัดนี้บอกผู้เข้าชมให้รู้' ),
			'consent_marketing_name'   => array( 'หมวดโฆษณา (Meta Pixel) · ชื่อหมวด', 'text', 'หมวดนี้แสดงเฉพาะเมื่อมี Meta Pixel ID' ),
			'consent_marketing_desc'   => array( 'หมวดโฆษณา (Meta Pixel) · คำอธิบายสั้น', 'textarea' ),
			'consent_marketing_detail' => array( 'หมวดโฆษณา (Meta Pixel) · รายละเอียดคุกกี้', 'textarea', $detail_hint ),
			'consent_marketing_google_detail' => array( 'หมวดโฆษณา · บรรทัดต่อท้ายเมื่อมี GA4 ด้วย (สัญญาณโฆษณาของ Google)', 'textarea', $detail_hint ),
			'consent_none_text'        => array( 'ข้อความเมื่อยังไม่ได้ใส่รหัสติดตามใด ๆ', 'textarea' ),
			'consent_prefs_note'       => array( 'ข้อความใต้รายการหมวด (หน้าเลือกทีละหมวด)', 'textarea' ),
			'consent_link_label'       => array( 'ข้อความลิงก์ "ตั้งค่าคุกกี้" (หน้า /go และจุดที่ใช้ eab_consent_link)', 'text' ),
		),
	);

	return $sections;
}
add_filter( 'eab_customizer_sections', 'eab_consent_sections', 30, 2 );

/* ==============================================================
 * ตรวจรูปแบบรหัสติดตาม
 * ============================================================== */

/**
 * GA4 Measurement ID ที่ใช้ได้ (G-XXXX) หรือ '' ถ้ารูปแบบไม่ตรง
 */
function eab_consent_sanitize_ga_id( $value ) {
	$value = strtoupper( preg_replace( '/\s+/', '', (string) $value ) );
	return preg_match( '/^G-[A-Z0-9]{4,20}$/', $value ) ? $value : '';
}

/**
 * Meta Pixel ID ที่ใช้ได้ (ตัวเลข 10 ถึง 20 หลัก) หรือ '' ถ้ารูปแบบไม่ตรง
 */
function eab_consent_sanitize_pixel_id( $value ) {
	$value = preg_replace( '/\s+/', '', (string) $value );
	return preg_match( '/^\d{10,20}$/', $value ) ? $value : '';
}

/**
 * รุ่นความยินยอม · จำนวนเต็มตั้งแต่ 1 ขึ้นไป
 */
function eab_consent_sanitize_version( $value ) {
	return (string) max( 1, absint( $value ) );
}

/**
 * รหัสผิดรูปแบบ: แสดงข้อผิดพลาดตรงช่องกรอกและไม่ยอมบันทึก เจ้าของเว็บจะได้รู้ตัวทันที
 */
function eab_consent_validate_id( $validity, $value, $setting = null ) {
	$value = trim( (string) $value );
	if ( '' === $value || ! is_object( $setting ) || ! isset( $setting->id ) || ! is_object( $validity ) ) {
		return $validity;
	}
	if ( 'ga_measurement_id' === $setting->id && '' === eab_consent_sanitize_ga_id( $value ) ) {
		$validity->add( 'eab_bad_ga_id', 'รูปแบบรหัส GA4 ไม่ถูกต้อง · รหัสที่ใช้ได้หน้าตาแบบ G-AB12CD34EF (เปิด GA4 → Admin → Data streams แล้วคัดลอก Measurement ID)' );
	}
	if ( 'fb_pixel_id' === $setting->id && '' === eab_consent_sanitize_pixel_id( $value ) ) {
		$validity->add( 'eab_bad_pixel_id', 'รูปแบบรหัส Meta Pixel ไม่ถูกต้อง · ใส่เฉพาะตัวเลข 10 ถึง 20 หลัก ห้ามมีตัวอักษรหรือช่องว่าง (คัดลอกจาก Meta Events Manager → Data sources)' );
	}
	return $validity;
}

/* sanitize ต่อท้ายตัวกลางของ customizer.php (sanitize_text_field ที่ priority 10) · validate แยกต่อ setting */
add_filter( 'customize_sanitize_ga_measurement_id', 'eab_consent_sanitize_ga_id', 20 );
add_filter( 'customize_sanitize_fb_pixel_id', 'eab_consent_sanitize_pixel_id', 20 );
add_filter( 'customize_sanitize_consent_version', 'eab_consent_sanitize_version', 20 );
add_filter( 'customize_validate_ga_measurement_id', 'eab_consent_validate_id', 10, 3 );
add_filter( 'customize_validate_fb_pixel_id', 'eab_consent_validate_id', 10, 3 );

/* ==============================================================
 * ค่าที่ใช้งานจริง
 * ============================================================== */

/**
 * รหัสติดตามที่ผ่านการตรวจแล้ว (ค่าเก่าที่เคยบันทึกแบบไม่ตรวจก็ถูกกรองที่นี่)
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
 * ชื่อบริการที่ใส่รหัสไว้จริง (GA4 ก่อน Meta Pixel) ต่อกันด้วยคำเชื่อม · '' เมื่อยังไม่มีรหัสใดเลย
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
 * ข้อความชั้นแรกของการ์ด: cookie_consent_text ที่แทน {tools} ด้วยบริการที่ใส่รหัสไว้
 * ยังไม่มีรหัสใดเลย (การ์ดเปิดเพราะติ๊ก show_cookie_consent) = consent_none_text ไม่พูดถึงบริการที่ไม่ได้ทำงาน
 */
function eab_consent_intro_text() {
	$ids = eab_consent_ids();
	if ( '' === $ids['ga'] && '' === $ids['pixel'] ) {
		return (string) eab_mod( 'consent_none_text' );
	}
	$text = str_replace( '{tools}', eab_consent_tools_text(), (string) eab_mod( 'cookie_consent_text' ) );
	return trim( preg_replace( '/[ \t]{2,}/', ' ', $text ) );
}

/**
 * รวมรายละเอียดคุกกี้หลายชุด (บรรทัดละ 1 รายการ) · ข้ามชุดที่ว่าง
 */
function eab_consent_join_details( ...$parts ) {
	return implode( "\n", array_filter( array_map( 'trim', array_map( 'strval', $parts ) ), 'strlen' ) );
}

/**
 * ลิงก์นโยบาย (หัวข้อคุกกี้) · เฉพาะเมื่อหน้า privacy-policy เผยแพร่แล้ว
 */
function eab_consent_policy_url() {
	$url = function_exists( 'eab_published_page_url' ) ? eab_published_page_url( 'privacy-policy' ) : '';
	return $url ? $url . '#cookies' : '';
}

/**
 * ลิงก์ "ตั้งค่าคุกกี้" สำหรับท้ายเว็บ / หน้า /go/ / ที่อื่น ๆ · consent.js ดักคลิกแล้วเปิดการ์ดหน้าเลือกทีละหมวด
 *
 * @param array $args { class, label, echo }
 * @return string
 */
function eab_consent_link( $args = array() ) {
	$args  = wp_parse_args(
		$args,
		array(
			'class' => '',
			'label' => '',
			'echo'  => true,
		)
	);
	$label = '' !== trim( (string) $args['label'] ) ? $args['label'] : eab_mod( 'consent_link_label' );
	$html  = sprintf(
		'<a class="%1$s" href="#cookie-settings">%2$s</a>',
		esc_attr( trim( 'cookie-reopen ' . $args['class'] ) ),
		esc_html( $label )
	);
	if ( $args['echo'] ) {
		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above
	}
	return $html;
}

/**
 * รายละเอียดคุกกี้ของหมวด: บรรทัดละ 1 รายการ · ชื่อคุกกี้ (มี _ หรือเป็น camelCase) ห่อด้วย <code class="keep-case">
 */
function eab_consent_detail_html( $text ) {
	$items = eab_lines( $text );
	if ( ! $items ) {
		return '';
	}
	$html = '';
	foreach ( $items as $item ) {
		$safe  = esc_html( $item );
		$coded = preg_replace(
			'/(?<![A-Za-z0-9_*])(_[A-Za-z0-9]+(?:_[A-Za-z0-9]+)*(?:_\*)?|[A-Za-z][A-Za-z0-9]*(?:_[A-Za-z0-9]+)+(?:_\*)?|[a-z]+(?:[A-Z][a-z0-9]+){2,})(?![A-Za-z0-9_])/',
			'<code class="consent-code keep-case">$1</code>',
			$safe
		);
		$html .= '<li>' . ( null === $coded ? $safe : $coded ) . '</li>';
	}
	return '<ul class="consent-detail-list">' . $html . '</ul>';
}

/* ==============================================================
 * ส่งค่าให้ consent.js และตั้ง Consent Mode เริ่มต้น
 * ============================================================== */

/**
 * ส่งการตั้งค่าให้ consent.js ผ่าน window.eabConsentConfig · ค่าไม่ขึ้นกับผู้เข้าชม หน้าเว็บจึงยังแคชได้
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
 * Consent Mode v2 · ค่าเริ่มต้น denied ทุกสัญญาณ พิมพ์ต้น <head> ก่อนแท็ก Google ใด ๆ
 * ใช้ฟังก์ชันภายใน (ไม่สร้าง window.gtag) สคริปต์อื่นจึงไม่ส่ง event ก่อนผู้ใช้อนุญาต
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
 * การ์ดความยินยอม
 * ============================================================== */

/**
 * การ์ดออกมาพร้อม hidden ทุกครั้ง · เบราว์เซอร์ที่ปิด JS จะไม่เห็นการ์ด และไม่มีสคริปต์ติดตามตัวไหนทำงาน
 * พิมพ์ครั้งเดียวต่อหน้า (wp_body_open ก่อน · wp_footer เป็นทางสำรอง)
 */
function eab_consent_card() {
	static $printed = false;
	if ( $printed || is_admin() ) {
		return;
	}
	$printed = true;

	$policy_url   = eab_consent_policy_url();
	$policy_label = trim( (string) eab_mod( 'consent_policy_label' ) );
	$policy_link  = ( $policy_url && '' !== $policy_label )
		? ' <a class="consent-policy" href="' . esc_url( $policy_url ) . '">' . esc_html( $policy_label ) . '</a>'
		: '';
	$detail_label = eab_mod( 'consent_detail_label' );
	$ids          = eab_consent_ids();

	/* พิมพ์เฉพาะหมวดที่มีบริการใส่รหัสไว้จริง · ไม่มี Pixel = ไม่มีหมวดการตลาด สัญญาณโฆษณาของ Google จึงคง denied (consent.js) */
	$cats = array(
		'necessary' => array(
			'name'   => eab_mod( 'consent_necessary_name' ),
			'desc'   => eab_mod( 'consent_necessary_desc' ),
			'detail' => eab_mod( 'consent_necessary_detail' ),
			'icon'   => 'lock',
		),
	);
	if ( '' !== $ids['ga'] ) {
		$cats['analytics'] = array(
			'name'   => eab_mod( 'consent_analytics_name' ),
			'desc'   => eab_mod( 'consent_analytics_desc' ),
			'detail' => eab_consent_join_details(
				eab_mod( 'consent_analytics_detail' ),
				'' === $ids['pixel'] ? eab_mod( 'consent_ads_off_detail' ) : ''
			),
			'icon'   => 'chart',
		);
	}
	if ( '' !== $ids['pixel'] ) {
		$cats['marketing'] = array(
			'name'   => eab_mod( 'consent_marketing_name' ),
			'desc'   => eab_mod( 'consent_marketing_desc' ),
			'detail' => eab_consent_join_details(
				eab_mod( 'consent_marketing_detail' ),
				'' !== $ids['ga'] ? eab_mod( 'consent_marketing_google_detail' ) : ''
			),
			'icon'   => 'target',
		);
	}
	?>
<section class="consent" id="cookie-settings" aria-labelledby="consent-title" data-view="intro" hidden>
	<div class="consent-head">
		<span class="consent-badge" aria-hidden="true"><?php echo eab_icon( 'shield' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
		<div class="consent-head-text">
			<?php if ( '' !== trim( (string) eab_mod( 'consent_kicker' ) ) && eab_show_kickers() ) : ?>
				<p class="consent-kicker"><?php echo esc_html( eab_mod( 'consent_kicker' ) ); ?></p>
			<?php endif; ?>
			<p class="consent-title" id="consent-title" tabindex="-1"><?php echo eab_text( eab_mod( 'consent_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in eab_text ?></p>
		</div>
	</div>

	<div class="consent-body">
		<p class="consent-text" data-show="intro"><?php echo esc_html( eab_consent_intro_text() ); ?><?php echo $policy_link; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above ?></p>
		<?php if ( '' === $ids['ga'] && '' === $ids['pixel'] ) : ?>
			<p class="consent-note consent-note--none" data-show="prefs" data-when="none" hidden><?php echo esc_html( eab_mod( 'consent_none_text' ) ); ?></p>
		<?php endif; ?>

		<div class="consent-prefs" data-show="prefs" hidden>
			<ul class="consent-cats">
				<?php foreach ( $cats as $eab_cat_key => $eab_cat ) : ?>
					<?php
					$eab_cat_id  = 'consent-' . $eab_cat_key;
					$eab_locked  = 'necessary' === $eab_cat_key;
					$eab_details = eab_consent_detail_html( $eab_cat['detail'] );
					?>
					<li class="consent-cat<?php echo $eab_locked ? ' is-locked' : ''; ?>"<?php echo $eab_locked ? '' : ' data-cat="' . esc_attr( $eab_cat_key ) . '" hidden'; ?>>
						<div class="consent-cat-row">
							<span class="consent-cat-icon" aria-hidden="true"><?php echo eab_icon( $eab_cat['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
							<div class="consent-cat-main">
								<p class="consent-cat-name" id="<?php echo esc_attr( $eab_cat_id ); ?>-name"><?php echo esc_html( $eab_cat['name'] ); ?></p>
								<p class="consent-cat-desc" id="<?php echo esc_attr( $eab_cat_id ); ?>-desc"><?php echo esc_html( $eab_cat['desc'] ); ?></p>
							</div>
							<?php if ( $eab_locked ) : ?>
								<span class="consent-always"><?php echo esc_html( eab_mod( 'consent_always_label' ) ); ?></span>
							<?php else : ?>
								<label class="consent-switch">
									<input type="checkbox" role="switch" name="<?php echo esc_attr( $eab_cat_key ); ?>" aria-labelledby="<?php echo esc_attr( $eab_cat_id ); ?>-name" aria-describedby="<?php echo esc_attr( $eab_cat_id ); ?>-desc">
									<span class="consent-track" aria-hidden="true"></span>
								</label>
							<?php endif; ?>
						</div>
						<?php if ( $eab_details && '' !== trim( (string) $detail_label ) ) : ?>
							<details class="consent-more">
								<summary><span><?php echo esc_html( $detail_label ); ?></span><?php echo eab_icon( 'arrow', 'icon consent-more-icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></summary>
								<?php echo $eab_details; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in eab_consent_detail_html ?>
							</details>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
			<p class="consent-note"><?php echo esc_html( eab_mod( 'consent_prefs_note' ) ); ?><?php echo $policy_link; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above ?></p>
		</div>
	</div>

	<div class="consent-actions">
		<button type="button" class="consent-btn consent-btn--save btn-dark" data-consent="save" data-show="prefs" data-when="optional" hidden><?php echo esc_html( eab_mod( 'consent_save_label' ) ); ?></button>
		<button type="button" class="consent-btn consent-btn--choice consent-btn--reject btn-ghost" data-consent="reject" data-when="optional" hidden><?php echo esc_html( eab_mod( 'consent_reject_label' ) ); ?></button>
		<button type="button" class="consent-btn consent-btn--choice consent-btn--accept btn-dark" data-consent="accept" data-when="optional" hidden><?php echo esc_html( eab_mod( 'consent_accept_label' ) ); ?></button>
		<button type="button" class="consent-btn consent-btn--ok btn-dark" data-consent="ok" data-when="none" hidden><?php echo esc_html( eab_mod( 'consent_ok_label' ) ); ?></button>
		<button type="button" class="consent-btn consent-btn--link" data-consent="prefs" data-show="intro" data-when="optional" hidden><?php echo esc_html( eab_mod( 'consent_prefs_label' ) ); ?><?php echo eab_icon( 'arrow', 'icon icon-sm' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></button>
	</div>

	<button type="button" class="consent-close" data-consent="close" aria-label="<?php echo esc_attr( eab_mod( 'consent_close_label' ) ); ?>"><?php echo eab_icon( 'x', 'icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></button>
</section>
	<?php
}
add_action( 'wp_body_open', 'eab_consent_card', 5 );
add_action( 'wp_footer', 'eab_consent_card', 5 );
