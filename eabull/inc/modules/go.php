<?php
/**
 * EA BULL · /go page module (template-go.php)
 *
 * Standalone page (no header, footer, dock or floating button), mockup dev/mockup/_src/pages/go.html:
 * one dark card cut at the top-right corner · desktop two columns (sticky brand + help + info
 * buttons + socials on the left, 6 steps on the right) · phones one column. Styles in style.css.
 *
 * - Every text and link is a setting (sections "30)" and "31)" in the Customizer).
 * - Internal links point at pages by slug; a page that is not published yet shows a toast
 *   (data-wait) instead of a 404 link.
 * - LINE buttons use the chrome helpers: LINE OA when set, otherwise a toast.
 * - Step 4 download cards come from eab_go_product_downloads( false ) (products module, returns the
 *   .dlcards markup) when it exists.
 * - A step without a title is hidden and the numbers close up.
 *
 * @package eabull
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ==============================================================
 * Defaults
 * ============================================================== */

function eab_go_defaults( $d ) {
	return array_merge(
		$d,
		array(
			/* Side column */
			'go_logo'                => 'assets/img/brand/eabull-emblem.webp',
			'go_title'               => 'เริ่มต้นใช้ EA BULL',
			'go_sub'                 => 'แข็งแกร่งด้วยวินัย บุกตลาดอย่างมีแผน · ทุกลิงก์ที่ต้องใช้อยู่ในหน้านี้',
			'go_help_title'          => 'ติดขั้นตอนไหน ทักทีมงานได้',
			'go_line_label'          => 'ทัก LINE ปรึกษาทีมงาน',
			'go_openchat_label'      => 'เข้ากลุ่ม OpenChat EA BULL',
			'go_info_title'          => 'ข้อมูลก่อนตัดสินใจ',
			'go_btn1_label'          => 'แพ็กเกจและราคา',
			'go_btn1_url'            => '/pricing/',
			'go_btn2_label'          => 'คู่มือ Forward Test',
			'go_btn2_url'            => '/forward-test/',
			'go_btn3_label'          => 'รู้จัก EA BULL',
			'go_btn3_url'            => '/about/',
			'go_btn4_label'          => 'ประกาศความเสี่ยง',
			'go_btn4_url'            => '/risk-disclosure/',

			/* Steps: shared */
			'go_steps_title'         => 'เริ่มใช้งานใน {n} ขั้นตอน',
			'go_steps_sub'           => 'ทำตามลำดับจากบนลงล่าง ทุกขั้นมีปุ่มไปทำจริงและคู่มือละเอียด',
			'go_pending_label'       => 'หน้านี้กำลังเตรียม เปิดให้อ่านเร็ว ๆ นี้',

			/* Step 1 · trading account (signup link = broker_signup_url, partner link) */
			'go_step1_title'         => 'เปิดบัญชีเทรด MT5',
			'go_step1_desc'          => 'สมัครกับ Zaurix โบรกเกอร์พันธมิตรของ EA BULL แล้วยืนยันตัวตนให้เรียบร้อย',
			'go_step1_badge'         => '',
			'go_signup_label'        => 'สมัคร Zaurix',
			'go_account_guide_label' => 'คู่มือเปิดบัญชี',
			'go_account_guide_url'   => '/open-mt5-account/',

			/* Step 2 · MT5 app (store links: mt5_dl_* in functions.php) */
			'go_step2_title'         => 'ติดตั้งแอป MT5 และล็อกอิน',
			'go_step2_desc'          => 'ลงแอปจากร้านค้าทางการ แล้วล็อกอินด้วยเลขบัญชีที่ได้จากขั้นแรก',
			'go_step2_badge'         => '',
			'go_mt5_windows_label'   => 'Windows',
			'go_mt5_android_label'   => 'Android',
			'go_mt5_ios_label'       => 'iPhone / iPad',
			'go_mt5_login_label'     => 'คู่มือล็อกอิน',
			'go_mt5_login_url'       => '/mt5-login/',

			/* Step 3 · deposit (no portal link yet: toast) */
			'go_step3_title'         => 'ฝากเงินเข้าบัญชีเทรด',
			'go_step3_desc'          => 'ฝากผ่าน Portal ของโบรกเกอร์ แล้วเช็กยอด Balance ในแอป MT5',
			'go_step3_badge'         => '',
			'go_deposit_label'       => 'เข้า Portal Zaurix',
			'go_deposit_url'         => '',
			'go_deposit_wait'        => 'ลิงก์เข้า Portal รอยืนยันจากเจ้าของ',
			'go_deposit_guide_label' => 'คู่มือฝากเงิน',
			'go_deposit_guide_url'   => '/deposit/',

			/* Step 4 · EA files (cards from the products module) */
			'go_step4_title'         => 'ดาวน์โหลดไฟล์ EA',
			'go_step4_desc'          => 'เลือกสินค้าที่ตกลงกับทีมงานไว้ แล้วแจ้งทีมงานทาง LINE เพื่อเปิดใช้งาน',
			'go_step4_badge'         => '',
			'go_license_line_label'  => 'ขอเปิดใช้งานทาง LINE',

			/* Step 5 · install */
			'go_step5_title'         => 'ติดตั้ง EA บน MT5',
			'go_step5_desc'          => 'คัดลอกไฟล์ลงโฟลเดอร์ Experts กดเปิด Algo Trading แล้วลาก EA ไปวางบนกราฟ',
			'go_step5_badge'         => '',
			'go_install1_label'      => 'คู่มือติดตั้ง EA ทีละขั้น',
			'go_install1_url'        => '/how-to-install/',
			'go_install2_label'      => 'คำนวณขนาด Lot',
			'go_install2_url'        => '/tools/',

			/* Step 6 · VPS */
			'go_step6_title'         => 'รันบน VPS',
			'go_step6_desc'          => 'ย้าย MT5 ไปเครื่องที่เปิดตลอด EA ทำงานต่อแม้คอมที่บ้านปิด เลือกคู่มือตามเครื่องที่ใช้เชื่อมต่อ',
			'go_step6_badge'         => 'แนะนำ',
			'go_vps_windows_label'   => 'จากคอม Windows',
			'go_vps_windows_url'     => '/vps-windows/',
			'go_vps_android_label'   => 'จาก Android',
			'go_vps_android_url'     => '/vps-android/',
			'go_vps_ios_label'       => 'จาก iPhone / iPad',
			'go_vps_ios_url'         => '/vps-ios/',
			'go_vps_line_label'      => 'ถามสเปก VPS',

			/* Card foot */
			'go_risk_text'           => 'EA ช่วยเทรดตามแผน แต่เงินในบัญชีก็ยังหมดได้ ลงเฉพาะเงินที่ไม่กระทบค่าใช้จ่ายประจำ และอ่าน[ประกาศความเสี่ยง](/risk-disclosure/)ก่อนเริ่ม',
			'go_home_label'          => 'ไปหน้าแรกของ EA BULL',
		)
	);
}
add_filter( 'eab_defaults', 'eab_go_defaults' );

/* ==============================================================
 * Customizer · replaces the central /go section in place, steps section right after it
 * ============================================================== */

function eab_go_sections( $sections, $d ) {
	unset( $d );
	$link = 'ใส่ slug เช่น /pricing/ หรือ URL เต็ม · หน้าในเว็บที่ยังไม่เผยแพร่ กดแล้วขึ้นข้อความ "หน้านี้กำลังเตรียม" แทน';

	$head = array(
		'title'       => '30) หน้า /go · ฝั่งแบรนด์และติดต่อ',
		'description' => 'หน้ารวมลิงก์สำหรับโปรไฟล์ LINE OA, Facebook และปลายทางโฆษณา · หน้าเดี่ยว ไม่มีเมนูและท้ายเว็บ · เพจต้องใช้เทมเพลต "EA BULL · หน้ารวมลิงก์ (/go)" · ปุ่ม LINE ใช้ลิงก์จากหมวด 1 (ยังไม่มี = กดแล้วขึ้นข้อความแจ้ง) · ไอคอนโซเชียลใช้ลิงก์ LINE OpenChat และ Facebook จากหมวด 1',
		'fields'      => array(
			'go_logo'           => array( 'รูปหัววัวบนสุด', 'image', 'ว่าง = ใช้รูปหัววัวของธีม · โลโก้ตัวอักษรใช้รูปจากหมวด 1' ),
			'go_title'          => array( 'หัวข้อหน้า (H1 · ว่าง = ใช้ชื่อเพจ)', 'text' ),
			'go_sub'            => array( 'บรรทัดใต้หัวข้อ', 'textarea' ),
			'go_help_title'     => array( 'กลุ่มติดต่อ · หัวข้อ', 'text' ),
			'go_line_label'     => array( 'กลุ่มติดต่อ · ปุ่ม LINE', 'text' ),
			'go_openchat_label' => array( 'กลุ่มติดต่อ · ปุ่ม OpenChat (ว่าง = ซ่อน)', 'text' ),
			'go_info_title'     => array( 'กลุ่มข้อมูลก่อนตัดสินใจ · หัวข้อ', 'text' ),
			'go_btn1_label'     => array( 'ปุ่มข้อมูล 1 · ข้อความ (ว่าง = ซ่อน)', 'text' ),
			'go_btn1_url'       => array( 'ปุ่มข้อมูล 1 · ลิงก์', 'path', $link ),
			'go_btn2_label'     => array( 'ปุ่มข้อมูล 2 · ข้อความ', 'text' ),
			'go_btn2_url'       => array( 'ปุ่มข้อมูล 2 · ลิงก์', 'path' ),
			'go_btn3_label'     => array( 'ปุ่มข้อมูล 3 · ข้อความ', 'text' ),
			'go_btn3_url'       => array( 'ปุ่มข้อมูล 3 · ลิงก์', 'path' ),
			'go_btn4_label'     => array( 'ปุ่มข้อมูล 4 · ข้อความ', 'text' ),
			'go_btn4_url'       => array( 'ปุ่มข้อมูล 4 · ลิงก์', 'path' ),
			'go_pending_label'  => array( 'ข้อความแจ้งเมื่อกดลิงก์ไปหน้าที่ยังไม่เผยแพร่', 'text' ),
			'go_risk_text'      => array( 'คำเตือนความเสี่ยงท้ายขั้นตอน', 'textarea', 'ทำลิงก์ได้ด้วย [ข้อความ](/slug/) · ต้องบอกว่าขาดทุนได้และลิงก์ไปหน้าประกาศความเสี่ยง' ),
			'go_home_label'     => array( 'ท้ายการ์ด · ลิงก์กลับหน้าแรก (ว่าง = ซ่อน)', 'text', 'ลิงก์นโยบายถัดไปใช้รายการเดียวกับแถวลิขสิทธิ์ของ Footer' ),
		),
	);

	$steps = array(
		'title'       => '31) หน้า /go · 6 ขั้นตอนเริ่มใช้งาน',
		'description' => 'ทุกขั้นมีหัวข้อ คำอธิบาย และป้ายเล็กหลังหัวข้อ · หัวข้อว่าง = ซ่อนขั้นนั้นแล้วเลขขั้นเรียงใหม่ · การ์ดดาวน์โหลดในขั้น 4 มาจากหมวดสินค้า · ห้ามใส่ตัวเลขผลเทรดหรือคำรับประกันกำไร',
		'fields'      => array(
			'go_steps_title'         => array( 'หัวข้อรวม ({n} = จำนวนขั้นที่แสดง)', 'text' ),
			'go_steps_sub'           => array( 'บรรทัดใต้หัวข้อรวม', 'text' ),

			'go_step1_title'         => array( 'ขั้น 1 · หัวข้อ', 'text' ),
			'go_step1_desc'          => array( 'ขั้น 1 · คำอธิบาย', 'textarea', 'ต้องบอกว่า Zaurix เป็นโบรกเกอร์พันธมิตรของ EA BULL ตรงนี้ เพราะเป็นจุดแรกที่พูดถึง' ),
			'go_step1_badge'         => array( 'ขั้น 1 · ป้ายเล็ก', 'text' ),
			'go_signup_label'        => array( 'ขั้น 1 · ปุ่มสมัคร (ลิงก์พันธมิตรตั้งที่หมวดโบรกเกอร์ · ว่าง = ซ่อน)', 'text' ),
			'go_account_guide_label' => array( 'ขั้น 1 · ปุ่มคู่มือ', 'text' ),
			'go_account_guide_url'   => array( 'ขั้น 1 · ลิงก์ปุ่มคู่มือ', 'path', $link ),

			'go_step2_title'         => array( 'ขั้น 2 · หัวข้อ', 'text' ),
			'go_step2_desc'          => array( 'ขั้น 2 · คำอธิบาย', 'textarea' ),
			'go_step2_badge'         => array( 'ขั้น 2 · ป้ายเล็ก', 'text' ),
			'go_mt5_windows_label'   => array( 'ขั้น 2 · ช่อง Windows', 'text' ),
			'mt5_dl_windows'         => array( 'ขั้น 2 · ลิงก์ดาวน์โหลด MT5 สำหรับ Windows', 'url' ),
			'go_mt5_android_label'   => array( 'ขั้น 2 · ช่อง Android', 'text' ),
			'mt5_dl_android'         => array( 'ขั้น 2 · ลิงก์ Google Play', 'url' ),
			'go_mt5_ios_label'       => array( 'ขั้น 2 · ช่อง iPhone / iPad', 'text' ),
			'mt5_dl_ios'             => array( 'ขั้น 2 · ลิงก์ App Store', 'url' ),
			'go_mt5_login_label'     => array( 'ขั้น 2 · ช่องคู่มือล็อกอิน', 'text' ),
			'go_mt5_login_url'       => array( 'ขั้น 2 · ลิงก์คู่มือล็อกอิน', 'path', $link ),

			'go_step3_title'         => array( 'ขั้น 3 · หัวข้อ', 'text' ),
			'go_step3_desc'          => array( 'ขั้น 3 · คำอธิบาย', 'textarea' ),
			'go_step3_badge'         => array( 'ขั้น 3 · ป้ายเล็ก', 'text' ),
			'go_deposit_label'       => array( 'ขั้น 3 · ปุ่มเข้า Portal', 'text' ),
			'go_deposit_url'         => array( 'ขั้น 3 · ลิงก์เข้า Portal ของโบรกเกอร์', 'path', 'ว่าง = ปุ่มยังแสดง กดแล้วขึ้นข้อความจากช่องถัดไป' ),
			'go_deposit_wait'        => array( 'ขั้น 3 · ข้อความแจ้งเมื่อยังไม่มีลิงก์ Portal', 'text' ),
			'go_deposit_guide_label' => array( 'ขั้น 3 · ปุ่มคู่มือ', 'text' ),
			'go_deposit_guide_url'   => array( 'ขั้น 3 · ลิงก์ปุ่มคู่มือ', 'path', $link ),

			'go_step4_title'         => array( 'ขั้น 4 · หัวข้อ', 'text' ),
			'go_step4_desc'          => array( 'ขั้น 4 · คำอธิบาย', 'textarea' ),
			'go_step4_badge'         => array( 'ขั้น 4 · ป้ายเล็ก', 'text' ),
			'go_license_line_label'  => array( 'ขั้น 4 · ปุ่ม LINE ใต้การ์ดดาวน์โหลด (ว่าง = ซ่อน)', 'text' ),

			'go_step5_title'         => array( 'ขั้น 5 · หัวข้อ', 'text' ),
			'go_step5_desc'          => array( 'ขั้น 5 · คำอธิบาย', 'textarea' ),
			'go_step5_badge'         => array( 'ขั้น 5 · ป้ายเล็ก', 'text' ),
			'go_install1_label'      => array( 'ขั้น 5 · ปุ่ม 1', 'text' ),
			'go_install1_url'        => array( 'ขั้น 5 · ลิงก์ปุ่ม 1', 'path', $link ),
			'go_install2_label'      => array( 'ขั้น 5 · ปุ่ม 2 (ว่าง = ซ่อน)', 'text' ),
			'go_install2_url'        => array( 'ขั้น 5 · ลิงก์ปุ่ม 2', 'path' ),

			'go_step6_title'         => array( 'ขั้น 6 · หัวข้อ', 'text' ),
			'go_step6_desc'          => array( 'ขั้น 6 · คำอธิบาย', 'textarea' ),
			'go_step6_badge'         => array( 'ขั้น 6 · ป้ายเล็ก', 'text' ),
			'go_vps_windows_label'   => array( 'ขั้น 6 · ช่อง Windows', 'text' ),
			'go_vps_windows_url'     => array( 'ขั้น 6 · ลิงก์ช่อง Windows', 'path', $link ),
			'go_vps_android_label'   => array( 'ขั้น 6 · ช่อง Android', 'text' ),
			'go_vps_android_url'     => array( 'ขั้น 6 · ลิงก์ช่อง Android', 'path' ),
			'go_vps_ios_label'       => array( 'ขั้น 6 · ช่อง iPhone / iPad', 'text' ),
			'go_vps_ios_url'         => array( 'ขั้น 6 · ลิงก์ช่อง iPhone / iPad', 'path' ),
			'go_vps_line_label'      => array( 'ขั้น 6 · ช่องถามทีมงานทาง LINE (ว่าง = ซ่อน)', 'text' ),
		),
	);

	$out   = array();
	$added = false;
	foreach ( $sections as $section_id => $section ) {
		if ( 'eab_go' === $section_id || 'eab_go_steps' === $section_id ) {
			if ( ! $added ) {
				$out['eab_go']       = $head;
				$out['eab_go_steps'] = $steps;
				$added               = true;
			}
			continue;
		}
		$out[ $section_id ] = $section;
	}
	if ( ! $added ) {
		$out['eab_go']       = $head;
		$out['eab_go_steps'] = $steps;
	}

	/* Keys of this page are edited here only. */
	$own = array_merge( array_keys( $head['fields'] ), array_keys( $steps['fields'] ) );
	foreach ( $out as $section_id => $section ) {
		if ( 'eab_go' === $section_id || 'eab_go_steps' === $section_id || empty( $section['fields'] ) || ! is_array( $section['fields'] ) ) {
			continue;
		}
		foreach ( $own as $key ) {
			unset( $out[ $section_id ]['fields'][ $key ] );
		}
	}
	return $out;
}
add_filter( 'eab_customizer_sections', 'eab_go_sections', 20, 2 );

/* ==============================================================
 * Helpers
 * ============================================================== */

/**
 * Trimmed setting value.
 */
function eab_go_mod( $key ) {
	return trim( (string) eab_mod( $key ) );
}

/**
 * Attribute string (chrome helper, local copy when the chrome module is missing).
 */
function eab_go_attrs( $attrs ) {
	if ( function_exists( 'eab_chrome_attrs' ) ) {
		return eab_chrome_attrs( $attrs );
	}
	$out = '';
	foreach ( (array) $attrs as $name => $value ) {
		if ( true === $value ) {
			$out .= ' ' . esc_attr( $name );
		} elseif ( null !== $value && false !== $value && '' !== $value ) {
			$out .= ' ' . esc_attr( $name ) . '="' . ( 'href' === $name ? esc_url( $value ) : esc_attr( $value ) ) . '"';
		}
	}
	return $out;
}

/**
 * Escaped label with iPhone / iPad kept in their case.
 */
function eab_go_text( $text ) {
	return function_exists( 'eab_chrome_nocaps' ) ? eab_chrome_nocaps( $text ) : esc_html( (string) $text );
}

/**
 * Link attributes for a configured URL or slug.
 * Unpublished page of this site or empty URL → href="#" + data-wait ($wait, default go_pending_label).
 * Other hosts open in a new tab.
 */
function eab_go_link( $url, $wait = '' ) {
	$url  = trim( (string) $url );
	$wait = '' !== $wait ? $wait : eab_go_mod( 'go_pending_label' );
	if ( '' === $url || '#' === $url ) {
		return array(
			'href'      => '#',
			'data-wait' => $wait,
		);
	}
	if ( preg_match( '#^/?([a-z0-9][a-z0-9\-]*)/?$#i', $url, $m ) && function_exists( 'eab_site_pages' ) ) {
		$pages = eab_site_pages();
		$slug  = strtolower( $m[1] );
		if ( isset( $pages[ $slug ] ) && empty( $pages[ $slug ]['front'] ) && function_exists( 'eab_published_page_url' ) && '' === eab_published_page_url( $slug ) ) {
			return array(
				'href'      => '#',
				'data-wait' => $wait,
			);
		}
	}
	$href  = eab_link_url( $url );
	$attrs = array( 'href' => $href );
	$ext   = function_exists( 'eab_chrome_is_external' ) ? eab_chrome_is_external( $href ) : (bool) preg_match( '#^(https?:)?//#i', $href );
	if ( $ext ) {
		$attrs['target'] = '_blank';
		$attrs['rel']    = 'noopener';
	}
	return $attrs;
}

/**
 * LINE link attributes on /go: LINE OA, otherwise a toast (this page is the fallback itself).
 */
function eab_go_line_link( $pos ) {
	if ( function_exists( 'eab_chrome_line_link' ) ) {
		return eab_chrome_line_link( $pos );
	}
	return array(
		'href'          => '#',
		'data-line-pos' => $pos,
	);
}

/**
 * Button with an optional leading icon · '' when the label is empty.
 */
function eab_go_btn( $class, $label, $attrs, $icon = '' ) {
	$label = trim( (string) $label );
	if ( '' === $label ) {
		return '';
	}
	return '<a' . eab_go_attrs( array_merge( array( 'class' => $class ), $attrs ) ) . '>' . ( '' !== $icon ? eab_icon( $icon ) : '' ) . eab_go_text( $label ) . '</a>';
}

/**
 * Green LINE button used in the steps (kept name for older callers, docs/phase2.md section 5).
 */
function eab_go_line_button( $label, $pos, $class = '' ) {
	return eab_go_btn( trim( 'btn btn--line ' . $class ), $label, eab_go_line_link( $pos ), 'line' );
}

/**
 * Tile grid (MT5 apps, VPS guides) · items: array( label, attrs, icon ).
 */
function eab_go_tiles( $items ) {
	$html = '';
	foreach ( $items as $item ) {
		$label = trim( (string) $item[0] );
		if ( '' === $label ) {
			continue;
		}
		$html .= '<a' . eab_go_attrs( array_merge( array( 'class' => 'gotile' ), $item[1] ) ) . '>' . eab_icon( $item[2] ) . eab_go_text( $label ) . '</a>';
	}
	return '' !== $html ? '<div class="gotiles">' . $html . '</div>' : '';
}

/**
 * Action row under a step.
 */
function eab_go_actions( $html ) {
	return '' !== trim( $html ) ? '<div class="gostep__act">' . $html . '</div>' : '';
}

/**
 * Inline text with [label](/path/) links (internal paths only) · unpublished targets stay plain text.
 */
function eab_go_rich( $text ) {
	$parts = preg_split( '#\[([^\[\]\n]+)\]\((/(?!/)[^\s()<>"\'\\\\]*)\)#u', (string) $text, -1, PREG_SPLIT_DELIM_CAPTURE );
	if ( false === $parts ) {
		return esc_html( (string) $text );
	}
	$html  = '';
	$label = '';
	foreach ( $parts as $idx => $part ) {
		switch ( $idx % 3 ) {
			case 0:
				$html .= esc_html( $part );
				break;
			case 1:
				$label = $part;
				break;
			default:
				$attrs = eab_go_link( $part );
				$html .= isset( $attrs['data-wait'] ) ? esc_html( $label ) : '<a href="' . esc_url( $attrs['href'] ) . '">' . esc_html( $label ) . '</a>';
		}
	}
	return $html;
}

/**
 * Steps · steps without a title are dropped (array order = number shown).
 *
 * @return array[] { n (setting number), title, desc, badge, body (escaped HTML) }
 */
function eab_go_steps() {
	$bodies = array();

	// 1 · Partner signup (always rel="sponsored noopener") + account guide.
	$signup = eab_go_mod( 'broker_signup_url' );
	$act    = '';
	if ( '' !== $signup && '' !== eab_go_mod( 'go_signup_label' ) ) {
		$act .= eab_go_btn(
			'btn btn--primary',
			eab_go_mod( 'go_signup_label' ),
			array(
				'href'        => eab_link_url( $signup ),
				'target'      => '_blank',
				'rel'         => 'sponsored noopener',
				'data-go-pos' => 'go-signup',
			),
			'user'
		);
	}
	$act       .= eab_go_btn( 'btn btn--outline', eab_go_mod( 'go_account_guide_label' ), eab_go_link( eab_go_mod( 'go_account_guide_url' ) ) );
	$bodies[1]  = eab_go_actions( $act );

	// 2 · MT5 app per device + login guide.
	$bodies[2] = eab_go_tiles(
		array(
			array( eab_go_mod( 'go_mt5_windows_label' ), eab_go_link( eab_go_mod( 'mt5_dl_windows' ) ), 'monitor' ),
			array( eab_go_mod( 'go_mt5_android_label' ), eab_go_link( eab_go_mod( 'mt5_dl_android' ) ), 'phone' ),
			array( eab_go_mod( 'go_mt5_ios_label' ), eab_go_link( eab_go_mod( 'mt5_dl_ios' ) ), 'tablet' ),
			array( eab_go_mod( 'go_mt5_login_label' ), eab_go_link( eab_go_mod( 'go_mt5_login_url' ) ), 'book' ),
		)
	);

	// 3 · Broker portal (toast until the owner confirms the link) + deposit guide.
	$act       = eab_go_btn( 'btn btn--primary', eab_go_mod( 'go_deposit_label' ), eab_go_link( eab_go_mod( 'go_deposit_url' ), eab_go_mod( 'go_deposit_wait' ) ), 'wallet' );
	$act      .= eab_go_btn( 'btn btn--outline', eab_go_mod( 'go_deposit_guide_label' ), eab_go_link( eab_go_mod( 'go_deposit_guide_url' ) ) );
	$bodies[3] = eab_go_actions( $act );

	// 4 · Download cards from the products module + LINE activation.
	$cards     = function_exists( 'eab_go_product_downloads' ) ? (string) eab_go_product_downloads( false ) : '';
	$bodies[4] = $cards . eab_go_actions( eab_go_line_button( eab_go_mod( 'go_license_line_label' ), 'go-license' ) );

	// 5 · Install guide + lot calculator.
	$act       = eab_go_btn( 'btn btn--outline', eab_go_mod( 'go_install1_label' ), eab_go_link( eab_go_mod( 'go_install1_url' ) ), 'book' );
	$act      .= eab_go_btn( 'btn btn--outline', eab_go_mod( 'go_install2_label' ), eab_go_link( eab_go_mod( 'go_install2_url' ) ), 'calculator' );
	$bodies[5] = eab_go_actions( $act );

	// 6 · VPS guide per connecting device + ask on LINE.
	$bodies[6] = eab_go_tiles(
		array(
			array( eab_go_mod( 'go_vps_windows_label' ), eab_go_link( eab_go_mod( 'go_vps_windows_url' ) ), 'monitor' ),
			array( eab_go_mod( 'go_vps_android_label' ), eab_go_link( eab_go_mod( 'go_vps_android_url' ) ), 'phone' ),
			array( eab_go_mod( 'go_vps_ios_label' ), eab_go_link( eab_go_mod( 'go_vps_ios_url' ) ), 'tablet' ),
			array( eab_go_mod( 'go_vps_line_label' ), eab_go_line_link( 'go-vps' ), 'line' ),
		)
	);

	$steps = array();
	foreach ( $bodies as $n => $body ) {
		$title = eab_go_mod( 'go_step' . $n . '_title' );
		if ( '' === $title ) {
			continue;
		}
		$steps[] = array(
			'n'     => $n,
			'title' => $title,
			'desc'  => eab_go_mod( 'go_step' . $n . '_desc' ),
			'badge' => eab_go_mod( 'go_step' . $n . '_badge' ),
			'body'  => $body,
		);
	}
	return $steps;
}

/**
 * "ข้อมูลก่อนตัดสินใจ" buttons (outline, small).
 */
function eab_go_info_buttons() {
	$html = '';
	for ( $i = 1; $i <= 4; $i++ ) {
		$html .= eab_go_btn( 'btn btn--outline btn--sm', eab_go_mod( 'go_btn' . $i . '_label' ), eab_go_link( eab_go_mod( 'go_btn' . $i . '_url' ) ) );
	}
	return $html;
}

/**
 * Policy links of the card foot (same list as the footer copyright row, published pages only).
 *
 * @return array[] { title, url }
 */
function eab_go_legal_links() {
	return function_exists( 'eab_chrome_links' ) ? eab_chrome_links( 'footer_legal_items' ) : array();
}

/**
 * Emblem at the top of the side column.
 */
function eab_go_logo_url() {
	$logo = eab_theme_asset_url( eab_go_mod( 'go_logo' ) );
	return '' !== $logo ? $logo : get_template_directory_uri() . '/assets/img/brand/eabull-emblem.webp';
}

/**
 * <body> class of this page (template-go.php adds the filter before body_class()).
 */
function eab_go_body_class( $classes ) {
	$classes[] = 'go-page';
	return array_values( array_unique( $classes ) );
}
