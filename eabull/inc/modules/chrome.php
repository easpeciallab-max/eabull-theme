<?php
/**
 * EA BULL · Site chrome: header + menu, yellow contact band, footer, mobile dock,
 * floating contact button + quick sheet.
 *
 * Markup follows the approved mockup (dev/mockup/_src/partials/header|contact|footer|dock|fab.html)
 * and the JS hooks in docs/phase2.md section 4 (main.js). Styles live in style.css; the
 * few extra rules for WordPress menus and the QR image are in assets/css/chrome.css.
 *
 * - Every text, link and image is a Customizer setting (defaults + controls below).
 * - LINE buttons use eab_contact_target(): LINE OA when set, otherwise the /go/ page,
 *   otherwise href="#" + data-wait (toast). Each LINE link keeps its data-line-pos.
 * - The dashboard link is empty until the owner's dashboard is ready: the button still
 *   shows and answers with a toast (data-wait).
 * - header.php and footer.php only arrange the pieces built here.
 *
 * @package eabull
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ==============================================================
 * Defaults
 * ============================================================== */

function eab_chrome_defaults( $d ) {
	$d = array_merge(
		$d,
		array(
			/* Header */
			'chrome_emblem'          => 'assets/img/brand/eabull-emblem.webp',
			'chrome_brand_alt'       => 'EA BULL',
			'chrome_home_aria'       => 'EA BULL หน้าแรก',
			'chrome_skip_text'       => 'ข้ามไปเนื้อหา',
			'chrome_nav_aria'        => 'เมนูหลัก',
			'chrome_menu_open'       => 'เปิดเมนู',
			'chrome_menu_close'      => 'ปิดเมนู',
			'chrome_loading_text'    => 'กำลังโหลดบทความ…',

			/* Main menu (used until a menu is assigned in Appearance → Menus) */
			'nav_home_label'         => 'หน้าแรก',
			'nav_products_label'     => 'สินค้า',
			'nav_test_label'         => 'การทดสอบ',
			'nav_test_items'         => "backtest | Backtest | ทดสอบย้อนหลังด้วย Strategy Tester\nforward-test | Forward Test | ทดสอบกับตลาดจริงด้วยบัญชีเล็ก",
			'nav_guide_label'        => 'คู่มือการใช้งาน',
			'nav_guide_items'        => "how-to-install | วิธีติดตั้ง EA บน MT5 | วางไฟล์ เปิดสวิตช์ ลากลงกราฟ\nopen-mt5-account | เปิดบัญชีเทรด MT5 | สมัคร Zaurix และยืนยันตัวตน\ndeposit | ฝากเงินเข้าบัญชีเทรด | ฝากผ่าน Portal ของโบรกเกอร์\nmt5-login | ติดตั้งและล็อกอิน MT5 | คอม มือถือ และแท็บเล็ต\nvps-windows | VPS บน Windows | เชื่อมต่อด้วย Remote Desktop\nvps-android | VPS บน Android | ใช้แอป Windows App\nvps-ios | VPS บน iPhone / iPad | ใช้แอป Windows App\ntools | เครื่องคำนวณ Lot / Drawdown | คิดขนาดไม้จากเงินที่ยอมเสีย",
			'nav_pricing_label'      => 'แพ็กเกจ',
			'nav_articles_label'     => 'บทความ',
			'nav_contact_label'      => 'ติดต่อ',

			/* Customer dashboard (owner: not finished yet, keep the button ready) */
			'dashboard_url'          => '',
			'header_dash_label'      => 'แดชบอร์ด',
			'chrome_dash_wait'       => 'ลิงก์แดชบอร์ดลูกค้า ยังไม่เสร็จ',

			/* Channels shared by every chrome part */
			'footer_line_text'       => 'ทัก LINE คุยกับทีมงาน',
			'chrome_line_wait'       => 'ลิงก์ LINE OA ของ EA BULL รอข้อมูลจากเจ้าของ',
			'chrome_line_name'       => 'LINE',
			'chrome_openchat_name'   => 'OpenChat',
			'chrome_fb_name'         => 'Facebook',

			/* Yellow contact band before the footer */
			'contact_title'          => 'สงสัยตรงไหน ถามทีมงานก่อนเริ่มได้เลย',
			'contact_text'           => 'ทักมาทาง LINE ได้ทุกเรื่อง ตั้งแต่เลือกแพ็กเกจ เปิดบัญชี ไปจนถึงติดตั้ง EA บน MT5',
			'contact_hide_on'        => "privacy-policy\nterms-of-use\ndata-deletion",
			'contact_line_title'     => 'LINE Official EA BULL',
			'contact_line_sub'       => 'ทักแชตกับทีมงาน',
			'contact_line_pending'   => 'ลิงก์รอข้อมูลจากเจ้าของ',
			'contact_openchat_title' => 'OpenChat EA BULL',
			'contact_openchat_sub'   => 'ห้องพูดคุยของผู้ใช้ EA BULL',
			'contact_fb_title'       => 'Facebook EA BULL',
			'contact_card_title'     => 'ส่งข้อความหาทีมงาน',
			'contact_card_text'      => 'บอกเราว่าตอนนี้อยู่ขั้นไหน ทีมงานจะแนะนำขั้นต่อไปให้',
			'contact_qr_title'       => 'สแกนเพิ่มเพื่อน LINE',
			'contact_qr_text'        => 'สแกนด้วยกล้องโทรศัพท์ หรือเมนูสแกนในแอป LINE',
			'contact_qr_wait'        => "QR LINE\nรอข้อมูล",
			'contact_qr_alt'         => 'QR Code เพิ่มเพื่อน LINE ของ EA BULL',
			'contact_ask_title'      => 'สิ่งที่ทีมงานจะถาม',
			'contact_ask_items'      => "เงินทุนที่ตั้งใจใช้ และรับความเสี่ยงได้แค่ไหน\nมีบัญชี MT5 แล้วหรือยัง ใช้โบรกเกอร์ไหน\nจะรัน EA บนคอม มือถือ หรือ VPS\nข้อสงสัยที่อยากถามก่อนเริ่ม",

			/* Footer */
			'footer_about'           => 'EA BULL ผู้ช่วยเทรดอัตโนมัติบน MetaTrader 5 · แข็งแกร่งด้วยวินัย บุกตลาดอย่างมีแผน',
			'footer_index_title'     => 'หน้าในเว็บ',
			'footer_index_items'     => "home | หน้าแรก\nproducts | สินค้า\nbacktest | Backtest\nforward-test | Forward Test\npricing | แพ็กเกจ\narticles | บทความ\ngo | ติดต่อ",
			'footer_docs_title'      => 'ข้อมูลและนโยบาย',
			'footer_docs_items'      => "about | เกี่ยวกับเรา\nrisk-disclosure | ประกาศความเสี่ยง\nprivacy-policy | นโยบายความเป็นส่วนตัว\nterms-of-use | ข้อกำหนดการใช้บริการ\ndata-deletion | ขอลบข้อมูลส่วนบุคคล",
			'footer_risk_short'      => 'การเทรดด้วย EA มีความเสี่ยงสูง อาจขาดทุนจนหมดเงินในบัญชี ผลทดสอบในอดีตไม่ได้บอกผลในอนาคต',
			'footer_risk_more'       => 'อ่านประกาศความเสี่ยงฉบับเต็ม',
			'footer_copyright_text'  => 'สงวนลิขสิทธิ์',
			'footer_legal_items'     => "privacy-policy | นโยบายความเป็นส่วนตัว\nterms-of-use | ข้อกำหนดการใช้บริการ",
			'footer_backtop_text'    => 'กลับขึ้นด้านบน',

			/* Mobile dock (slot 1, 3, 4 keys and show_mobile_nav are defined in functions.php) */
			'chrome_dock_aria'       => 'เมนูลัด',
			'mobile_nav_dash_label'  => 'แดชบอร์ด',
			'mobile_nav_line_label'  => 'LINE',

			/* Floating contact button + quick sheet */
			'show_float_line'        => true,
			'float_line_text'        => 'ติดต่อทีมงาน',
			'chrome_quick_title'     => 'คุยกับทีมงาน EA BULL',
			'chrome_quick_sub'       => 'เลือกช่องทางที่สะดวก',
			'chrome_quick_line'      => 'ทัก LINE',
			'chrome_quick_fb'        => 'Facebook EA BULL',
			'chrome_quick_go'        => 'หน้ารวมลิงก์เริ่มต้น',
			'chrome_close_label'     => 'ปิด',
		)
	);
	return $d;
}
add_filter( 'eab_defaults', 'eab_chrome_defaults' );

/* ==============================================================
 * Customizer
 * ============================================================== */

function eab_chrome_sections( $sections, $d ) {
	unset( $d );

	/* These central keys now belong to the sections below. */
	if ( isset( $sections['eab_general']['fields'] ) ) {
		foreach ( array( 'contact_title', 'contact_text', 'show_float_line', 'float_line_text' ) as $key ) {
			unset( $sections['eab_general']['fields'][ $key ] );
		}
	}

	$items_hint = 'บรรทัดละ 1 หน้า รูปแบบ slug | ชื่อ | บรรทัดรอง · แสดงเฉพาะหน้าที่เผยแพร่แล้ว · ถ้าตั้งเมนูเองที่ รูปแบบ → เมนู แล้ว เมนูบนจะใช้เมนูนั้นแทน';
	$list_hint  = 'บรรทัดละ 1 ลิงก์ รูปแบบ slug | ชื่อลิงก์ · ใช้ home (หน้าแรก) products (สินค้าตัวแรก) articles (หน้ารวมบทความ) ได้ · หน้าที่ยังไม่เผยแพร่จะถูกซ่อน';

	$mine = array(
		'eab_chrome_nav'     => array(
			'title'       => '15) Header · เมนูหลัก · ปุ่มแดชบอร์ด',
			'description' => 'แถบดำด้านบนทุกหน้า (ยกเว้น /go) · โลโก้ตัวอักษรใช้รูปจากหมวด 1 · เมนูในหมวดนี้ใช้จนกว่าจะตั้งเมนู "เมนูหลัก (Header)" ที่ รูปแบบ → เมนู',
			'fields'      => array(
				'chrome_emblem'        => array( 'รูปหัววัวหน้าโลโก้ตัวอักษร', 'image', 'ว่าง = ใช้รูปหัววัวของธีม' ),
				'chrome_brand_alt'     => array( 'ชื่อแบรนด์ (alt ของโลโก้ และชื่อในแถวลิขสิทธิ์)', 'text' ),
				'chrome_home_aria'     => array( 'ชื่อลิงก์โลโก้สำหรับโปรแกรมอ่านจอ', 'text' ),
				'chrome_skip_text'     => array( 'ลิงก์ข้ามไปเนื้อหา (เห็นเมื่อกด Tab)', 'text' ),
				'chrome_nav_aria'      => array( 'ชื่อเมนูหลักสำหรับโปรแกรมอ่านจอ', 'text' ),
				'chrome_menu_open'     => array( 'ปุ่มเมนูบนมือถือ · คำอ่านตอนเมนูปิด', 'text' ),
				'chrome_menu_close'    => array( 'ปุ่มเมนูบนมือถือ · คำอ่านตอนเมนูเปิด', 'text' ),
				'nav_home_label'       => array( 'เมนู · หน้าแรก', 'text' ),
				'nav_products_label'   => array( 'เมนู · หัวกลุ่มสินค้า (รายการมาจากหมวดสินค้า เรียงตามเลขรุ่น · ว่าง = ซ่อน)', 'text' ),
				'nav_test_label'       => array( 'เมนู · หัวกลุ่มการทดสอบ', 'text' ),
				'nav_test_items'       => array( 'เมนู · รายการในกลุ่มการทดสอบ', 'textarea', $items_hint ),
				'nav_guide_label'      => array( 'เมนู · หัวกลุ่มคู่มือ (ใช้เป็นหัวคอลัมน์คู่มือใน Footer ด้วย)', 'text' ),
				'nav_guide_items'      => array( 'เมนู · รายการในกลุ่มคู่มือ (คอลัมน์คู่มือใน Footer ใช้ชื่อชุดนี้)', 'textarea', $items_hint ),
				'nav_pricing_label'    => array( 'เมนู · แพ็กเกจ', 'text' ),
				'nav_articles_label'   => array( 'เมนู · บทความ', 'text' ),
				'nav_contact_label'    => array( 'เมนู · ติดต่อ (ไปหน้า /go · ว่าง = ซ่อน)', 'text' ),
				'dashboard_url'        => array( 'ลิงก์หน้าแดชบอร์ดของลูกค้า · ใช้กับปุ่มบนแถบด้านบนและช่องที่ 2 ของแถบล่าง', 'path', 'ว่าง = ปุ่มยังแสดงอยู่ กดแล้วขึ้นข้อความแจ้งจากช่องถัดไป' ),
				'header_dash_label'    => array( 'ปุ่มแดชบอร์ดบน Header · ข้อความ', 'text' ),
				'chrome_dash_wait'     => array( 'ข้อความแจ้งเมื่อกดปุ่มแดชบอร์ดที่ยังไม่มีลิงก์', 'text' ),
				'footer_line_text'     => array( 'ข้อความปุ่ม LINE หลัก (ใช้ทั้งเว็บ)', 'text', 'ลิงก์มาจาก "ลิงก์ LINE OA" ในหมวด 1 · ยังไม่มีลิงก์ = ปุ่มพาไปหน้า /go' ),
				'chrome_line_wait'     => array( 'ข้อความแจ้งเมื่อกดปุ่ม LINE ที่ยังไม่มีปลายทาง', 'text' ),
				'chrome_line_name'     => array( 'ชื่อช่องทาง LINE (ไอคอนท้ายเว็บ)', 'text' ),
				'chrome_openchat_name' => array( 'ชื่อช่องทาง OpenChat (ปุ่มเล็กในเมนูมือถือ ไอคอนท้ายเว็บ)', 'text' ),
				'chrome_fb_name'       => array( 'ชื่อช่องทาง Facebook (ปุ่มเล็กในเมนูมือถือ ไอคอนท้ายเว็บ)', 'text' ),
				'chrome_loading_text'  => array( 'ข้อความบนปุ่มระหว่างโหลดบทความเพิ่ม', 'text' ),
			),
		),
		'eab_chrome_contact' => array(
			'title'       => '15.1) แถบติดต่อสีเหลืองท้ายหน้า',
			'description' => 'แสดงก่อน Footer ทุกหน้า · หน้าย่อยตั้งหัวข้อของตัวเองได้ ถ้าไม่ตั้งจะใช้หัวข้อในหมวดนี้ · ปุ่ม LINE ใช้ลิงก์จากหมวด 1',
			'fields'      => array(
				'contact_title'          => array( 'หัวข้อกลาง', 'text' ),
				'contact_text'           => array( 'บรรทัดรองกลาง', 'textarea' ),
				'contact_hide_on'        => array( 'หน้าที่ไม่แสดงแถบนี้ (slug บรรทัดละ 1 หน้า)', 'textarea' ),
				'contact_line_title'     => array( 'รายการช่องทาง · LINE · ชื่อ', 'text' ),
				'contact_line_sub'       => array( 'รายการช่องทาง · LINE · บรรทัดรอง', 'text' ),
				'contact_line_pending'   => array( 'รายการช่องทาง · LINE · ต่อท้ายเมื่อยังไม่มีลิงก์ LINE OA', 'text' ),
				'contact_openchat_title' => array( 'รายการช่องทาง · OpenChat · ชื่อ', 'text' ),
				'contact_openchat_sub'   => array( 'รายการช่องทาง · OpenChat · บรรทัดรอง', 'text' ),
				'contact_fb_title'       => array( 'รายการช่องทาง · Facebook · ชื่อ (บรรทัดรองแสดงที่อยู่เพจจากหมวด 1)', 'text' ),
				'contact_card_title'     => array( 'การ์ดดำ · หัวข้อ', 'text' ),
				'contact_card_text'      => array( 'การ์ดดำ · คำอธิบาย', 'text' ),
				'contact_qr_title'       => array( 'การ์ดดำ · QR · หัวข้อ', 'text' ),
				'contact_qr_text'        => array( 'การ์ดดำ · QR · คำอธิบาย', 'text' ),
				'contact_qr_wait'        => array( 'การ์ดดำ · ข้อความในกรอบ QR ระหว่างรอรูป', 'textarea', 'รูป QR อัปโหลดที่หมวด 1 · แสดงเมื่อมีทั้งรูปและลิงก์ LINE OA' ),
				'contact_qr_alt'         => array( 'การ์ดดำ · คำอธิบายรูป QR (alt)', 'text' ),
				'contact_ask_title'      => array( 'การ์ดดำ · หัวข้อรายการคำถาม', 'text' ),
				'contact_ask_items'      => array( 'การ์ดดำ · รายการคำถาม (บรรทัดละ 1 ข้อ · ว่าง = ซ่อน)', 'textarea' ),
			),
		),
		'eab_footer'         => array(
			'title'       => '15.2) Footer ท้ายเว็บ',
			'description' => 'แบรนด์ · 3 คอลัมน์ลิงก์ (คอลัมน์แรกคือคู่มือจากหมวด 15) · กล่องเตือนความเสี่ยงแบบสั้นที่ลิงก์ไปหน้าประกาศความเสี่ยง · แถวลิขสิทธิ์',
			'fields'      => array(
				'footer_about'          => array( 'ข้อความใต้โลโก้', 'textarea' ),
				'footer_index_title'    => array( 'คอลัมน์ 2 · หัวข้อ', 'text' ),
				'footer_index_items'    => array( 'คอลัมน์ 2 · รายการลิงก์', 'textarea', $list_hint ),
				'footer_docs_title'     => array( 'คอลัมน์ 3 · หัวข้อ', 'text' ),
				'footer_docs_items'     => array( 'คอลัมน์ 3 · รายการลิงก์', 'textarea', $list_hint ),
				'footer_risk_short'     => array( 'คำเตือนความเสี่ยงแบบสั้น (1 ถึง 2 บรรทัด)', 'textarea', 'ต้องบอกว่าขาดทุนได้และผลในอดีตไม่ได้บอกผลในอนาคต · ลิงก์ไปหน้า risk-disclosure ต่อท้ายให้เอง' ),
				'footer_risk_more'      => array( 'คำบนลิงก์ต่อท้ายคำเตือน (ไปหน้า risk-disclosure)', 'text' ),
				'footer_copyright_text' => array( 'แถวลิขสิทธิ์ · ข้อความหลังชื่อแบรนด์', 'text' ),
				'footer_legal_items'    => array( 'แถวลิขสิทธิ์ · ลิงก์นโยบาย', 'textarea', $list_hint . ' · ปุ่ม "ตั้งค่าคุกกี้" ต่อท้ายให้เอง (ชื่อปุ่มตั้งที่หมวดคุกกี้)' ),
				'footer_backtop_text'   => array( 'แถวล่างสุด · คำบนลิงก์เลื่อนกลับไปบนสุด (ว่าง = ซ่อน)', 'text' ),
			),
		),
		'eab_mobile_nav'     => array(
			'title'       => '16) แถบล่างมือถือ และปุ่มติดต่อลอย',
			'description' => 'แถบดำ 5 ช่องบนจอกว้างไม่เกิน 900px · ช่องของหน้าที่เปิดอยู่เป็นสีเหลืองให้เอง · ช่อง 2 ใช้ลิงก์แดชบอร์ดจากหมวด 15 · ช่อง 5 ใช้ลิงก์ LINE จากหมวด 1 · ปุ่มเขียวมุมขวาล่างเปิดแผงติดต่อด่วน',
			'fields'      => array(
				'show_mobile_nav'          => array( 'แสดงแถบล่างบนมือถือ', 'checkbox' ),
				'chrome_dock_aria'         => array( 'ชื่อแถบล่างสำหรับโปรแกรมอ่านจอ', 'text' ),
				'mobile_nav_home_label'    => array( 'ช่อง 1 · ชื่อ', 'text' ),
				'mobile_nav_home_url'      => array( 'ช่อง 1 · ลิงก์หรือ slug', 'text' ),
				'mobile_nav_dash_label'    => array( 'ช่อง 2 · ชื่อ (แดชบอร์ด)', 'text' ),
				'mobile_nav_price_label'   => array( 'ช่อง 3 · ชื่อ', 'text' ),
				'mobile_nav_price_url'     => array( 'ช่อง 3 · ลิงก์หรือ slug', 'text' ),
				'mobile_nav_install_label' => array( 'ช่อง 4 · ชื่อ (เป็นสีเหลืองในทุกหน้าคู่มือ)', 'text' ),
				'mobile_nav_install_url'   => array( 'ช่อง 4 · ลิงก์หรือ slug', 'text' ),
				'mobile_nav_line_label'    => array( 'ช่อง 5 · ชื่อ (LINE)', 'text' ),
				'show_float_line'          => array( 'แสดงปุ่มติดต่อลอยมุมขวาล่าง', 'checkbox' ),
				'float_line_text'          => array( 'ปุ่มติดต่อลอย · คำอ่านสำหรับโปรแกรมอ่านจอ', 'text' ),
				'chrome_quick_title'       => array( 'แผงติดต่อด่วน · หัวข้อ', 'text' ),
				'chrome_quick_sub'         => array( 'แผงติดต่อด่วน · บรรทัดรอง', 'text' ),
				'chrome_quick_line'        => array( 'แผงติดต่อด่วน · ปุ่ม LINE', 'text' ),
				'chrome_quick_fb'          => array( 'แผงติดต่อด่วน · ปุ่ม Facebook', 'text' ),
				'chrome_quick_go'          => array( 'แผงติดต่อด่วน · ลิงก์ไปหน้า /go (ว่าง = ซ่อน)', 'text' ),
				'chrome_close_label'       => array( 'ปุ่มปิด · คำอ่านสำหรับโปรแกรมอ่านจอ', 'text' ),
			),
		),
	);

	/* Put the chrome sections where the old footer section sat, once. */
	$out    = array();
	$placed = false;
	foreach ( $sections as $section_id => $section ) {
		if ( isset( $mine[ $section_id ] ) ) {
			if ( ! $placed ) {
				$out   += $mine;
				$placed = true;
			}
			continue;
		}
		$out[ $section_id ] = $section;
	}
	if ( ! $placed ) {
		$out += $mine;
	}
	return $out;
}
add_filter( 'eab_customizer_sections', 'eab_chrome_sections', 10, 2 );

/* ==============================================================
 * Small helpers
 * ============================================================== */

/**
 * Trimmed setting value.
 */
function eab_chrome_mod( $key ) {
	return trim( (string) eab_mod( $key ) );
}

/**
 * Attribute string from an array (href goes through esc_url, the rest esc_attr).
 * true = bare attribute, null/false/'' = skipped.
 */
function eab_chrome_attrs( $attrs ) {
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
 * Escaped text with product names that must keep their case (iPhone, iPad, iOS, macOS)
 * wrapped in .nocaps, because the site upper-cases English with CSS.
 */
function eab_chrome_nocaps( $text ) {
	$html = esc_html( (string) $text );
	$word = '(?:iPhone|iPad|iOS|macOS)';
	$out  = preg_replace( '#(?<![A-Za-z])(' . $word . '(?:\s*/\s*' . $word . ')*)(?![A-Za-z])#', '<span class="nocaps">$1</span>', $html );
	return null === $out ? $html : $out;
}

/**
 * Words the browser tends to split badly in headings (eab_text in components.php).
 */
add_filter(
	'eab_keep_words',
	function ( $words ) {
		return array_merge( (array) $words, array( 'เด|โม' ) );
	}
);

/**
 * Strings for main.js.
 */
add_action(
	'wp_enqueue_scripts',
	function () {
		wp_localize_script(
			'eab-main',
			'eabChrome',
			array(
				'loadingText' => eab_chrome_mod( 'chrome_loading_text' ),
			)
		);
	},
	20
);

/**
 * URL of a page when it is published ('' otherwise).
 */
function eab_chrome_page_url( $slug ) {
	return function_exists( 'eab_published_page_url' ) ? (string) eab_published_page_url( $slug ) : '';
}

/**
 * Posts page (WordPress "Posts page" or a page with slug articles) · '' when not published.
 */
function eab_chrome_articles_url() {
	$posts_page = (int) get_option( 'page_for_posts' );
	if ( $posts_page > 0 && 'publish' === get_post_status( $posts_page ) ) {
		return (string) get_permalink( $posts_page );
	}
	return eab_chrome_page_url( 'articles' );
}

/**
 * Path of the current request ("/" on the front page), from $wp->request.
 */
function eab_chrome_here_path() {
	static $path = null;
	if ( null === $path ) {
		$req  = ( isset( $GLOBALS['wp'] ) && is_object( $GLOBALS['wp'] ) && isset( $GLOBALS['wp']->request ) ) ? (string) $GLOBALS['wp']->request : '';
		$path = '/' . trim( (string) wp_parse_url( home_url( '/' . ltrim( $req, '/' ) ), PHP_URL_PATH ), '/' );
	}
	return $path;
}

/**
 * Path of a link on this site · '' for other hosts and for #anchors.
 */
function eab_chrome_url_path( $url ) {
	$url = trim( (string) $url );
	if ( '' === $url || '#' === $url[0] ) {
		return '';
	}
	$host = wp_parse_url( $url, PHP_URL_HOST );
	if ( $host && strtolower( (string) $host ) !== strtolower( (string) wp_parse_url( home_url( '/' ), PHP_URL_HOST ) ) ) {
		return '';
	}
	return '/' . trim( (string) wp_parse_url( $url, PHP_URL_PATH ), '/' );
}

/**
 * The link points at the page being viewed.
 */
function eab_chrome_is_here( $url ) {
	$path = eab_chrome_url_path( $url );
	return '' !== $path && $path === eab_chrome_here_path();
}

/**
 * Link to another host (opens in a new tab).
 */
function eab_chrome_is_external( $url ) {
	$url = trim( (string) $url );
	if ( ! preg_match( '#^(https?:)?//#i', $url ) ) {
		return false;
	}
	$to = wp_parse_url( ( 0 === strpos( $url, '//' ) ? 'https:' : '' ) . $url, PHP_URL_HOST );
	return strtolower( (string) $to ) !== strtolower( (string) wp_parse_url( home_url( '/' ), PHP_URL_HOST ) );
}

/**
 * Products from the products module (set B: items with name, version, url, sub).
 * Missing function or no items = empty list, so the menu group is skipped.
 *
 * @return array[] { title, sub, url, current }
 */
function eab_chrome_products() {
	if ( ! function_exists( 'eab_products' ) ) {
		return array();
	}
	$items = eab_products();
	if ( ! is_array( $items ) ) {
		return array();
	}
	$out = array();
	foreach ( $items as $item ) {
		if ( ! is_array( $item ) ) {
			continue;
		}
		$name = isset( $item['name'] ) ? trim( (string) $item['name'] ) : '';
		$slug = isset( $item['slug'] ) ? trim( (string) $item['slug'] ) : '';
		$url  = ! empty( $item['url'] ) ? (string) $item['url'] : ( '' !== $slug ? eab_chrome_page_url( $slug ) : '' );
		if ( '' === $name || '' === $url ) {
			continue;
		}
		$ver = isset( $item['version'] ) ? trim( (string) $item['version'] ) : '';
		if ( '' !== $ver ) {
			$name .= ' · ' . ( preg_match( '/^v/i', $ver ) ? 'V' . substr( $ver, 1 ) : 'V' . $ver );
		}
		$out[] = array(
			'title'   => $name,
			'sub'     => isset( $item['sub'] ) ? trim( (string) $item['sub'] ) : '',
			'url'     => $url,
			'current' => eab_chrome_is_here( $url ) || ( '' !== $slug && is_page( $slug ) ),
		);
	}
	return $out;
}

/**
 * Turn a reference from a link list into a URL · '' when the page is not published.
 * home · products (first product) · articles (posts page) · /path/ or full URL · page slug.
 */
function eab_chrome_resolve( $ref ) {
	$ref = trim( (string) $ref );
	if ( '' === $ref ) {
		return '';
	}
	if ( 'home' === $ref || '/' === $ref ) {
		return home_url( '/' );
	}
	if ( 'articles' === $ref ) {
		return eab_chrome_articles_url();
	}
	if ( 'products' === $ref ) {
		$products = eab_chrome_products();
		return $products ? $products[0]['url'] : '';
	}
	if ( false !== strpos( $ref, '/' ) || false !== strpos( $ref, ':' ) || '#' === $ref[0] ) {
		return eab_link_url( $ref );
	}
	return eab_chrome_page_url( sanitize_title( $ref ) );
}

/**
 * The reference is the page being viewed.
 */
function eab_chrome_ref_current( $ref, $url ) {
	if ( 'home' === $ref || '/' === $ref ) {
		return is_front_page();
	}
	if ( 'articles' === $ref ) {
		return is_home() || is_singular( 'post' ) || is_category();
	}
	if ( 'products' === $ref ) {
		return false;
	}
	return ( false === strpos( $ref, '/' ) && is_page( sanitize_title( $ref ) ) ) || eab_chrome_is_here( $url );
}

/**
 * Link list setting ("ref | title | sub line", one per line) → items, unpublished pages dropped.
 *
 * @return array[] { ref, title, sub, url, current }
 */
function eab_chrome_links( $key ) {
	$out = array();
	foreach ( eab_lines( eab_mod( $key ) ) as $line ) {
		$parts = array_map( 'trim', explode( '|', $line ) );
		if ( count( $parts ) < 2 || '' === $parts[0] || '' === $parts[1] ) {
			continue;
		}
		$url = eab_chrome_resolve( $parts[0] );
		if ( '' === $url ) {
			continue;
		}
		$out[] = array(
			'ref'     => $parts[0],
			'title'   => $parts[1],
			'sub'     => isset( $parts[2] ) ? $parts[2] : '',
			'url'     => $url,
			'current' => eab_chrome_ref_current( $parts[0], $url ),
		);
	}
	return $out;
}

/* ==============================================================
 * Channel links (LINE · OpenChat · Facebook · dashboard)
 * ============================================================== */

/**
 * Attributes of every LINE link: LINE OA → /go/ page → toast. Keeps data-line-pos.
 */
function eab_chrome_line_link( $pos = '' ) {
	$target = function_exists( 'eab_contact_target' ) ? eab_contact_target() : array(
		'url'     => '',
		'is_line' => false,
	);
	$attrs  = array();
	if ( '' !== $target['url'] ) {
		$attrs['href'] = $target['url'];
		if ( $target['is_line'] ) {
			$attrs['target'] = '_blank';
			$attrs['rel']    = 'noopener';
		}
	} else {
		$attrs['href']      = '#';
		$attrs['data-wait'] = eab_chrome_mod( 'chrome_line_wait' );
	}
	$attrs['data-line-pos'] = $pos;
	return $attrs;
}

/**
 * The LINE link goes to a real LINE OA (not the /go/ fallback or a toast).
 */
function eab_chrome_has_line() {
	return function_exists( 'eab_has_line_url' ) && eab_has_line_url();
}

/**
 * OpenChat link attributes · empty array when no OpenChat URL is set.
 */
function eab_chrome_openchat_link( $pos = '' ) {
	$url = eab_chrome_mod( 'line_openchat_url' );
	if ( '' === $url || '#' === $url ) {
		return array();
	}
	return array(
		'href'          => $url,
		'target'        => '_blank',
		'rel'           => 'noopener',
		'data-line-pos' => $pos,
	);
}

/**
 * Facebook link attributes · empty array when no page URL is set.
 */
function eab_chrome_fb_link() {
	$url = eab_chrome_mod( 'facebook_url' );
	if ( '' === $url || '#' === $url ) {
		return array();
	}
	return array(
		'href'   => $url,
		'target' => '_blank',
		'rel'    => 'noopener',
	);
}

/**
 * Dashboard link attributes · no URL yet = toast with chrome_dash_wait.
 */
function eab_chrome_dash_link() {
	$url = eab_chrome_mod( 'dashboard_url' );
	if ( '' === $url || '#' === $url ) {
		return array(
			'href'      => '#',
			'data-wait' => eab_chrome_mod( 'chrome_dash_wait' ),
		);
	}
	$href  = eab_link_url( $url );
	$attrs = array( 'href' => $href );
	if ( eab_chrome_is_external( $href ) ) {
		$attrs['rel'] = 'noopener';
	}
	return $attrs;
}

/**
 * Green LINE button (btn btn--line) · kept for older callers (docs/phase2.md section 5).
 *
 * @param string $class Extra classes (e.g. btn--block).
 * @param string $pos   data-line-pos value.
 * @param string $text  Button text ('' = footer_line_text).
 * @param bool   $echo  Print (true) or return.
 */
function eab_chrome_contact_button( $class = '', $pos = 'content', $text = '', $echo = true ) {
	$extra = array_diff( preg_split( '/\s+/', trim( (string) $class ) ), array( '', 'btn', 'btn--line' ) );
	$label = '' !== trim( (string) $text ) ? trim( (string) $text ) : eab_chrome_mod( 'footer_line_text' );
	$attrs = array_merge( array( 'class' => trim( 'btn btn--line ' . implode( ' ', $extra ) ) ), eab_chrome_line_link( $pos ) );
	$html  = '<a' . eab_chrome_attrs( $attrs ) . '>' . eab_icon( 'chat' ) . esc_html( $label ) . '</a>';
	if ( $echo ) {
		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above
	}
	return $html;
}

/**
 * Square social icons (footer brand column and /go side column).
 */
function eab_chrome_social_icons( $pos = 'footer-social', $class = '' ) {
	$items = array(
		array( eab_chrome_line_link( $pos ), 'chat', eab_chrome_mod( 'chrome_line_name' ) ),
		array( eab_chrome_openchat_link( $pos . '-openchat' ), 'users', eab_chrome_mod( 'chrome_openchat_name' ) ),
		array( eab_chrome_fb_link(), 'facebook', eab_chrome_mod( 'chrome_fb_name' ) ),
	);
	$html = '';
	foreach ( $items as $item ) {
		if ( empty( $item[0] ) || '' === $item[2] ) {
			continue;
		}
		$html .= '<a' . eab_chrome_attrs( array_merge( array( 'class' => 'ft__soc' ), $item[0], array( 'aria-label' => $item[2] ) ) ) . '>' . eab_icon( $item[1] ) . '</a>';
	}
	if ( '' === $html ) {
		return '';
	}
	return '<div class="' . esc_attr( trim( 'ft__social ' . $class ) ) . '">' . $html . '</div>';
}

/* ==============================================================
 * Header: brand, menu, dashboard button, menu toggle
 * ============================================================== */

/**
 * Logo link (emblem + wordmark).
 */
function eab_chrome_brand() {
	$emblem = eab_theme_asset_url( eab_chrome_mod( 'chrome_emblem' ) );
	if ( '' === $emblem ) {
		$emblem = get_template_directory_uri() . '/assets/img/brand/eabull-emblem.webp';
	}
	?>
	<a class="hd__brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( eab_chrome_mod( 'chrome_home_aria' ) ); ?>">
		<img class="hd__emblem" src="<?php echo esc_url( $emblem ); ?>" width="640" height="571" alt="" decoding="async">
		<img class="hd__word" src="<?php echo esc_url( eab_wordmark_url( 'dark' ) ); ?>" width="720" height="100" alt="<?php echo esc_attr( eab_chrome_mod( 'chrome_brand_alt' ) ); ?>" decoding="async">
	</a>
	<?php
}

/**
 * Menu used until a WordPress menu is assigned (same tree as the mockup).
 *
 * @return array[] { label, url, current, children[] { title, sub, url, current }, wide }
 */
function eab_chrome_nav_tree() {
	static $tree = null;
	if ( null !== $tree ) {
		return $tree;
	}
	$tree = array();

	$home = eab_chrome_mod( 'nav_home_label' );
	if ( '' !== $home ) {
		$tree[] = array(
			'label'    => $home,
			'url'      => home_url( '/' ),
			'current'  => is_front_page(),
			'children' => array(),
		);
	}

	$groups = array(
		array( 'nav_products_label', eab_chrome_products(), false ),
		array( 'nav_test_label', eab_chrome_links( 'nav_test_items' ), false ),
		array( 'nav_guide_label', eab_chrome_links( 'nav_guide_items' ), true ),
	);
	foreach ( $groups as $group ) {
		$label = eab_chrome_mod( $group[0] );
		if ( '' === $label || empty( $group[1] ) ) {
			continue;
		}
		$tree[] = array(
			'label'    => $label,
			'url'      => $group[1][0]['url'],
			'current'  => false,
			'children' => $group[1],
			'wide'     => $group[2],
		);
	}

	foreach ( array(
		'nav_pricing_label'  => 'pricing',
		'nav_articles_label' => 'articles',
		'nav_contact_label'  => 'go',
	) as $key => $ref ) {
		$label = eab_chrome_mod( $key );
		$url   = eab_chrome_resolve( $ref );
		if ( '' === $label || '' === $url ) {
			continue;
		}
		$tree[] = array(
			'label'    => $label,
			'url'      => $url,
			'current'  => eab_chrome_ref_current( $ref, $url ),
			'children' => array(),
		);
	}
	return $tree;
}

/**
 * Fallback menu markup (fallback_cb of wp_nav_menu): li.has-sub > button.nav__parent + ul.sub.
 */
function eab_chrome_fallback_menu( $args = array() ) {
	unset( $args );
	$html = '<ul class="nav__list">';
	foreach ( eab_chrome_nav_tree() as $item ) {
		if ( empty( $item['children'] ) ) {
			$html .= '<li><a class="nav__link" href="' . esc_url( $item['url'] ) . '"' . ( $item['current'] ? ' aria-current="page"' : '' ) . '>' . esc_html( $item['label'] ) . '</a></li>';
			continue;
		}
		$html .= '<li class="has-sub"><button class="nav__link nav__parent" type="button" aria-expanded="false">' . esc_html( $item['label'] ) . ' ' . eab_icon( 'chevron-down' ) . '</button>';
		$html .= '<ul class="sub' . ( ! empty( $item['wide'] ) ? ' sub--wide' : '' ) . '">';
		foreach ( $item['children'] as $child ) {
			$html .= '<li><a href="' . esc_url( $child['url'] ) . '"' . ( $child['current'] ? ' aria-current="page"' : '' ) . '><b>' . eab_chrome_nocaps( $child['title'] ) . '</b>';
			if ( '' !== $child['sub'] ) {
				$html .= '<span>' . eab_chrome_nocaps( $child['sub'] ) . '</span>';
			}
			$html .= '</a></li>';
		}
		$html .= '</ul></li>';
	}
	$html .= '</ul>';
	echo $html; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above
}

/**
 * The menu inside nav#nav: the WordPress menu "primary" or the fallback tree.
 */
function eab_chrome_menu() {
	wp_nav_menu(
		array(
			'theme_location' => 'primary',
			'container'      => false,
			'menu_class'     => 'nav__list',
			'fallback_cb'    => 'eab_chrome_fallback_menu',
			'depth'          => 2,
		)
	);
}

/**
 * Buttons at the foot of the mobile menu sheet: LINE + OpenChat + Facebook.
 */
function eab_chrome_nav_foot() {
	$openchat = eab_chrome_openchat_link( 'nav-openchat' );
	$fb       = eab_chrome_fb_link();
	?>
	<div class="nav__foot">
		<?php eab_chrome_contact_button( 'btn--block', 'nav' ); ?>
		<?php if ( $openchat || $fb ) : ?>
			<div class="nav__social">
				<?php if ( $openchat ) : ?>
					<a<?php echo eab_chrome_attrs( array_merge( array( 'class' => 'btn btn--openchat btn--sm' ), $openchat ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>><?php echo eab_icon( 'users' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( eab_chrome_mod( 'chrome_openchat_name' ) ); ?></a>
				<?php endif; ?>
				<?php if ( $fb ) : ?>
					<a<?php echo eab_chrome_attrs( array_merge( array( 'class' => 'btn btn--fb btn--sm' ), $fb ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>><?php echo eab_icon( 'facebook' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( eab_chrome_mod( 'chrome_fb_name' ) ); ?></a>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Dashboard button at the end of the header (outlined yellow).
 */
function eab_chrome_header_dash() {
	$label = eab_chrome_mod( 'header_dash_label' );
	if ( '' === $label ) {
		return;
	}
	echo '<a' . eab_chrome_attrs( array_merge( array( 'class' => 'hd__dash' ), eab_chrome_dash_link() ) ) . '>' . eab_icon( 'gauge' ) . '<span>' . esc_html( $label ) . '</span></a>'; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in parts
}

/**
 * Menu toggle (main.js swaps aria-label with data-open / data-close).
 */
function eab_chrome_toggle() {
	$open  = eab_chrome_mod( 'chrome_menu_open' );
	$close = eab_chrome_mod( 'chrome_menu_close' );
	?>
	<button class="hd__toggle" type="button" aria-controls="nav" aria-expanded="false" aria-label="<?php echo esc_attr( $open ); ?>" data-open="<?php echo esc_attr( $open ); ?>" data-close="<?php echo esc_attr( $close ); ?>">
		<span class="hd__ico-open"><?php echo eab_icon( 'menu' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span><span class="hd__ico-close"><?php echo eab_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
	</button>
	<?php
}

/* ==============================================================
 * WordPress menus in the primary location: same classes as the fallback
 * (top links .nav__link, parents .has-sub with a chevron, submenus .sub,
 * child items <b>title</b><span>menu item description</span>).
 * ============================================================== */

function eab_chrome_is_primary( $args ) {
	return is_object( $args ) && ! empty( $args->theme_location ) && 'primary' === $args->theme_location;
}

function eab_chrome_menu_li_class( $classes, $item, $args, $depth = 0 ) {
	if ( eab_chrome_is_primary( $args ) && 0 === (int) $depth && in_array( 'menu-item-has-children', (array) $classes, true ) ) {
		$classes[] = 'has-sub';
		if ( ! empty( $GLOBALS['eab_chrome_wide'][ (int) $item->ID ] ) ) {
			$classes[] = 'has-sub--wide';
		}
	}
	return $classes;
}
add_filter( 'nav_menu_css_class', 'eab_chrome_menu_li_class', 10, 4 );

function eab_chrome_menu_link_attrs( $atts, $item, $args, $depth = 0 ) {
	if ( eab_chrome_is_primary( $args ) && 0 === (int) $depth ) {
		$atts['class'] = trim( ( isset( $atts['class'] ) ? $atts['class'] . ' ' : '' ) . 'nav__link' );
	}
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'eab_chrome_menu_link_attrs', 10, 4 );

function eab_chrome_menu_title( $title, $item, $args, $depth = 0 ) {
	if ( ! eab_chrome_is_primary( $args ) ) {
		return $title;
	}
	if ( 0 === (int) $depth ) {
		return in_array( 'menu-item-has-children', (array) $item->classes, true ) ? $title . ' ' . eab_icon( 'chevron-down' ) : $title;
	}
	$desc = isset( $item->description ) ? trim( wp_strip_all_tags( (string) $item->description ) ) : '';
	return '<b>' . $title . '</b>' . ( '' !== $desc ? '<span>' . esc_html( $desc ) . '</span>' : '' );
}
add_filter( 'nav_menu_item_title', 'eab_chrome_menu_title', 10, 4 );

function eab_chrome_menu_sub_class( $classes, $args, $depth = 0 ) {
	if ( eab_chrome_is_primary( $args ) ) {
		$classes[] = 'sub';
	}
	return $classes;
}
add_filter( 'nav_menu_submenu_css_class', 'eab_chrome_menu_sub_class', 10, 3 );

/**
 * Menu objects of the primary menu:
 * - parents with 6 or more children get the wide two-column submenu;
 * - a link with #anchor is never "current" (WordPress drops the hash when it compares URLs,
 *   so a /#section link would light up on every visit to the front page, and its parent too).
 */
function eab_chrome_menu_objects( $items, $args = null ) {
	if ( null !== $args && ! eab_chrome_is_primary( $args ) ) {
		return $items;
	}
	$count  = array();
	$parent = array();
	$hashed = array();
	foreach ( $items as $item ) {
		$pid                            = (int) $item->menu_item_parent;
		$parent[ (int) $item->db_id ] = $pid;
		if ( $pid ) {
			$count[ $pid ] = isset( $count[ $pid ] ) ? $count[ $pid ] + 1 : 1;
		}
		$is_current = ! empty( $item->current ) || in_array( 'current-menu-item', (array) $item->classes, true );
		if ( $is_current && false !== strpos( (string) $item->url, '#' ) ) {
			$item->current = false;
			$item->classes = array_values( array_diff( (array) $item->classes, array( 'current-menu-item', 'current_page_item' ) ) );
			$hashed[]      = (int) $item->db_id;
		}
	}
	$GLOBALS['eab_chrome_wide'] = array();
	foreach ( $count as $pid => $n ) {
		if ( $n >= 6 ) {
			$GLOBALS['eab_chrome_wide'][ $pid ] = true;
		}
	}
	if ( ! $hashed ) {
		return $items;
	}
	$ancestors = function ( $id ) use ( $parent ) {
		$out = array();
		$p   = isset( $parent[ $id ] ) ? $parent[ $id ] : 0;
		while ( $p && ! isset( $out[ $p ] ) ) {
			$out[ $p ] = true;
			$p         = isset( $parent[ $p ] ) ? $parent[ $p ] : 0;
		}
		return $out;
	};
	$keep = array();
	foreach ( $items as $item ) {
		if ( ! empty( $item->current ) ) {
			$keep += $ancestors( (int) $item->db_id );
		}
	}
	$drop = array();
	foreach ( $hashed as $id ) {
		$drop += $ancestors( $id );
	}
	foreach ( $items as $item ) {
		$id = (int) $item->db_id;
		if ( isset( $drop[ $id ] ) && ! isset( $keep[ $id ] ) ) {
			$item->current_item_ancestor = false;
			$item->current_item_parent   = false;
			$item->classes               = array_values( array_diff( (array) $item->classes, array( 'current-menu-ancestor', 'current-menu-parent', 'current_page_parent', 'current_page_ancestor' ) ) );
		}
	}
	return $items;
}
add_filter( 'wp_nav_menu_objects', 'eab_chrome_menu_objects', 20, 2 );

/**
 * Append "ติดต่อ" (/go/) to a hand-made primary menu that has no /go/ or LINE link yet.
 */
function eab_chrome_menu_contact( $items, $args ) {
	if ( ! eab_chrome_is_primary( $args ) ) {
		return $items;
	}
	$label = eab_chrome_mod( 'nav_contact_label' );
	$go    = eab_chrome_page_url( 'go' );
	if ( '' === $label || '' === $go ) {
		return $items;
	}
	if ( false !== strpos( $items, esc_url( $go ) ) || preg_match( '#href="[^"]*/go/?(?:[?\#][^"]*)?"#i', $items ) || false !== strpos( $items, '>' . esc_html( $label ) . '<' ) ) {
		return $items;
	}
	if ( preg_match( '#href="(?:(?:https?:)?//(?:www\.)?(?:lin\.ee|line\.me|liff\.line\.me|page\.line\.me)/|line:)#i', $items ) ) {
		return $items;
	}
	$here = is_page( 'go' );
	return $items . '<li class="menu-item menu-item-eab-contact' . ( $here ? ' current-menu-item' : '' ) . '"><a class="nav__link" href="' . esc_url( $go ) . '"' . ( $here ? ' aria-current="page"' : '' ) . '>' . esc_html( $label ) . '</a></li>';
}
add_filter( 'wp_nav_menu_items', 'eab_chrome_menu_contact', 10, 2 );

/* ==============================================================
 * Yellow contact band (mockup partials/contact.html)
 * Printed once per page: by eab_line_cta() where a template calls it, otherwise
 * by footer.php before the footer (skipped on the slugs in contact_hide_on).
 * ============================================================== */

/**
 * Contact band.
 *
 * @param array $args { title, sub, pos } (title/sub '' = the shared texts).
 */
function eab_chrome_contact_panel( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'title' => '',
			'sub'   => '',
			'pos'   => 'page-cta',
		)
	);
	if ( ! empty( $GLOBALS['eab_chrome_cta_done'] ) ) {
		return;
	}
	$GLOBALS['eab_chrome_cta_done'] = true;

	$title = '' !== trim( (string) $args['title'] ) ? trim( (string) $args['title'] ) : eab_chrome_mod( 'contact_title' );
	$sub   = '' !== trim( (string) $args['sub'] ) ? trim( (string) $args['sub'] ) : eab_chrome_mod( 'contact_text' );
	$pos   = sanitize_key( (string) $args['pos'] );

	$has_line = eab_chrome_has_line();
	$line_sub = eab_chrome_mod( 'contact_line_sub' );
	$pending  = eab_chrome_mod( 'contact_line_pending' );
	if ( ! $has_line && '' !== $pending ) {
		$line_sub = '' !== $line_sub ? $line_sub . ' · ' . $pending : $pending;
	}
	$fb      = eab_chrome_fb_link();
	$fb_host = $fb ? preg_replace( '#^(?:https?:)?//(?:www\.)?#i', '', rtrim( $fb['href'], '/' ) ) : '';

	$channels = array(
		array( eab_chrome_line_link( 'footer-channels' ), 'chat', eab_chrome_mod( 'contact_line_title' ), $line_sub, '' ),
		array( eab_chrome_openchat_link( 'footer-channels-openchat' ), 'users', eab_chrome_mod( 'contact_openchat_title' ), eab_chrome_mod( 'contact_openchat_sub' ), '' ),
		array( $fb, 'facebook', eab_chrome_mod( 'contact_fb_title' ), $fb_host, 'nocaps' ),
	);

	$openchat = eab_chrome_openchat_link( $pos . '-openchat' );
	$qr_img   = $has_line ? eab_theme_asset_url( eab_chrome_mod( 'line_qr_image' ) ) : '';
	$qr_wait  = eab_lines( eab_mod( 'contact_qr_wait' ) );
	$ask      = eab_lines( eab_mod( 'contact_ask_items' ) );
	?>
<section class="contact yband" id="contact" aria-labelledby="contact-title">
	<div class="wrap contact__grid">
		<div class="contact__text">
			<h2 id="contact-title"><?php echo esc_html( $title ); ?></h2>
			<?php if ( '' !== $sub ) : ?>
				<p class="contact__sub"><?php echo esc_html( $sub ); ?></p>
			<?php endif; ?>
			<ul class="chan">
				<?php
				foreach ( $channels as $chan ) :
					if ( empty( $chan[0] ) || '' === $chan[2] ) {
						continue;
					}
					$eab_out = ! empty( $chan[0]['target'] );
					?>
					<li><a<?php echo eab_chrome_attrs( $chan[0] ); // phpcs:ignore WordPress.Security.EscapeOutput ?>><span class="tile tile--ink"><?php echo eab_icon( $chan[1] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span><span><b><?php echo esc_html( $chan[2] ); ?></b><?php if ( '' !== $chan[3] ) : ?><small<?php echo '' !== $chan[4] ? ' class="' . esc_attr( $chan[4] ) . '"' : ''; ?>><?php echo esc_html( $chan[3] ); ?></small><?php endif; ?></span><?php echo eab_icon( $eab_out ? 'arrow-up-right' : 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<div class="contact__card cut dark">
			<?php if ( '' !== eab_chrome_mod( 'contact_card_title' ) ) : ?>
				<h3><?php echo esc_html( eab_chrome_mod( 'contact_card_title' ) ); ?></h3>
			<?php endif; ?>
			<?php if ( '' !== eab_chrome_mod( 'contact_card_text' ) ) : ?>
				<p><?php echo esc_html( eab_chrome_mod( 'contact_card_text' ) ); ?></p>
			<?php endif; ?>
			<div class="contact__btns">
				<?php eab_chrome_contact_button( 'btn--block', $pos ); ?>
				<?php if ( $openchat ) : ?>
					<a<?php echo eab_chrome_attrs( array_merge( array( 'class' => 'btn btn--openchat btn--block' ), $openchat ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>><?php echo eab_icon( 'users' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( eab_chrome_mod( 'line_openchat_text' ) ); ?></a>
				<?php endif; ?>
			</div>
			<?php if ( '' !== eab_chrome_mod( 'contact_qr_title' ) ) : ?>
				<div class="contact__qr">
					<?php if ( '' !== $qr_img ) : ?>
						<div class="qr qr--img"><img src="<?php echo esc_url( $qr_img ); ?>" width="600" height="600" alt="<?php echo esc_attr( eab_chrome_mod( 'contact_qr_alt' ) ); ?>" loading="lazy" decoding="async"></div>
					<?php else : ?>
						<div class="qr"><?php echo eab_icon( 'qr' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo implode( '<br>', array_map( 'esc_html', $qr_wait ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span></div>
					<?php endif; ?>
					<div>
						<b><?php echo esc_html( eab_chrome_mod( 'contact_qr_title' ) ); ?></b>
						<?php if ( '' !== eab_chrome_mod( 'contact_qr_text' ) ) : ?>
							<p><?php echo esc_html( eab_chrome_mod( 'contact_qr_text' ) ); ?></p>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
			<?php if ( $ask ) : ?>
				<div class="contact__ask">
					<?php if ( '' !== eab_chrome_mod( 'contact_ask_title' ) ) : ?>
						<b><?php echo esc_html( eab_chrome_mod( 'contact_ask_title' ) ); ?></b>
					<?php endif; ?>
					<ul class="checks">
						<?php foreach ( $ask as $eab_item ) : ?>
							<li><?php echo eab_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( $eab_item ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
	<?php
}

/**
 * eab_line_cta( $title, $sub ) from templates (functions.php forwards to this hook).
 */
function eab_chrome_line_cta( $title = '', $sub = '' ) {
	eab_chrome_contact_panel(
		array(
			'title' => $title,
			'sub'   => $sub,
			'pos'   => 'page-cta',
		)
	);
}
add_action( 'eab_line_cta', 'eab_chrome_line_cta', 10, 2 );

/**
 * Band before the footer on pages whose template did not print one.
 * Off on the slugs listed in contact_hide_on, or with the filter eab_chrome_footer_contact.
 */
function eab_chrome_footer_contact() {
	if ( ! empty( $GLOBALS['eab_chrome_cta_done'] ) ) {
		return;
	}
	$hidden = array_map( 'sanitize_title', eab_lines( eab_mod( 'contact_hide_on' ) ) );
	$show   = ! ( $hidden && is_page( $hidden ) );
	if ( ! apply_filters( 'eab_chrome_footer_contact', $show ) ) {
		return;
	}
	eab_chrome_contact_panel( array( 'pos' => 'footer' ) );
}

/* ==============================================================
 * Footer (footer.php prints the markup with these lists)
 * ============================================================== */

/**
 * The three link columns: guides (menu items) · site pages · documents.
 *
 * @return array[] { title, items[] { title, url } }
 */
function eab_chrome_footer_columns() {
	$cols = array(
		array( eab_chrome_mod( 'nav_guide_label' ), eab_chrome_links( 'nav_guide_items' ) ),
		array( eab_chrome_mod( 'footer_index_title' ), eab_chrome_links( 'footer_index_items' ) ),
		array( eab_chrome_mod( 'footer_docs_title' ), eab_chrome_links( 'footer_docs_items' ) ),
	);
	$out  = array();
	foreach ( $cols as $col ) {
		if ( ! empty( $col[1] ) ) {
			$out[] = array(
				'title' => $col[0],
				'items' => $col[1],
			);
		}
	}
	return $out;
}

/* ==============================================================
 * Mobile dock (5 equal slots) and floating contact button
 * ============================================================== */

/**
 * Dock slots · the slot of the page being viewed gets aria-current (CSS draws the hexagon).
 *
 * @return array[] { key, label, attrs, icon, current }
 */
function eab_chrome_dock_items() {
	$guides = array();
	foreach ( eab_chrome_links( 'nav_guide_items' ) as $link ) {
		$guides[] = sanitize_title( $link['ref'] );
	}
	$home  = eab_link_url( eab_mod( 'mobile_nav_home_url' ) );
	$price = eab_link_url( eab_mod( 'mobile_nav_price_url' ) );
	$guide = eab_link_url( eab_mod( 'mobile_nav_install_url' ) );
	$slots = array(
		array( 'home', 'mobile_nav_home_label', array( 'href' => $home ), 'home', is_front_page() || ( '/' !== eab_chrome_url_path( $home ) && eab_chrome_is_here( $home ) ) ),
		array( 'dash', 'mobile_nav_dash_label', eab_chrome_dash_link(), 'gauge', false ),
		array( 'pricing', 'mobile_nav_price_label', array( 'href' => $price ), 'tag', eab_chrome_is_here( $price ) ),
		array( 'guides', 'mobile_nav_install_label', array( 'href' => $guide ), 'book', eab_chrome_is_here( $guide ) || ( $guides && is_page( $guides ) ) ),
		array( 'line', 'mobile_nav_line_label', eab_chrome_line_link( 'dock' ), 'chat', false ),
	);
	$out   = array();
	foreach ( $slots as $slot ) {
		$label = eab_chrome_mod( $slot[1] );
		if ( '' === $label ) {
			continue;
		}
		$out[] = array(
			'key'     => $slot[0],
			'label'   => $label,
			'attrs'   => $slot[2],
			'icon'    => $slot[3],
			'current' => (bool) $slot[4],
		);
	}
	return $out;
}

/**
 * nav.dock (shown at 900px and below by style.css).
 */
function eab_chrome_dock() {
	if ( ! eab_mod( 'show_mobile_nav' ) ) {
		return;
	}
	$items = eab_chrome_dock_items();
	if ( ! $items ) {
		return;
	}
	echo '<nav class="dock" aria-label="' . esc_attr( eab_chrome_mod( 'chrome_dock_aria' ) ) . '">';
	foreach ( $items as $item ) {
		$attrs = array_merge( array( 'data-dock' => $item['key'] ), $item['attrs'], $item['current'] ? array( 'aria-current' => 'page' ) : array() );
		echo '<a' . eab_chrome_attrs( $attrs ) . '><span class="dock__ico">' . eab_icon( $item['icon'] ) . '</span><span>' . esc_html( $item['label'] ) . '</span></a>'; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in parts
	}
	echo '</nav>';
}

/**
 * Floating green button + quick contact sheet (#quick, opened by main.js).
 */
function eab_chrome_fab() {
	if ( ! eab_mod( 'show_float_line' ) ) {
		return;
	}
	$close    = eab_chrome_mod( 'chrome_close_label' );
	$title    = eab_chrome_mod( 'chrome_quick_title' );
	$openchat = eab_chrome_openchat_link( 'fab-openchat' );
	$fb       = eab_chrome_fb_link();
	$go       = eab_chrome_page_url( 'go' );
	$go_label = eab_chrome_mod( 'chrome_quick_go' );
	?>
<button class="fab" type="button" aria-controls="quick" aria-expanded="false" aria-label="<?php echo esc_attr( eab_chrome_mod( 'float_line_text' ) ); ?>"><?php echo eab_icon( 'chat' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></button>
<div class="quick dark" id="quick" role="dialog" aria-label="<?php echo esc_attr( '' !== $title ? $title : eab_chrome_mod( 'float_line_text' ) ); ?>" hidden>
	<div class="quick__head">
		<b><?php echo esc_html( $title ); ?></b>
		<button type="button" class="quick__x" data-quick-close aria-label="<?php echo esc_attr( $close ); ?>"><?php echo eab_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></button>
	</div>
	<?php if ( '' !== eab_chrome_mod( 'chrome_quick_sub' ) ) : ?>
		<p><?php echo esc_html( eab_chrome_mod( 'chrome_quick_sub' ) ); ?></p>
	<?php endif; ?>
	<div class="quick__btns">
		<?php eab_chrome_contact_button( 'btn--block', 'fab', eab_chrome_mod( 'chrome_quick_line' ) ); ?>
		<?php if ( $openchat ) : ?>
			<a<?php echo eab_chrome_attrs( array_merge( array( 'class' => 'btn btn--openchat btn--block' ), $openchat ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>><?php echo eab_icon( 'users' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( eab_chrome_mod( 'line_openchat_text' ) ); ?></a>
		<?php endif; ?>
		<?php if ( $fb ) : ?>
			<a<?php echo eab_chrome_attrs( array_merge( array( 'class' => 'btn btn--fb btn--block' ), $fb ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>><?php echo eab_icon( 'facebook' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( eab_chrome_mod( 'chrome_quick_fb' ) ); ?></a>
		<?php endif; ?>
	</div>
	<?php if ( '' !== $go && '' !== $go_label ) : ?>
		<a class="quick__go" href="<?php echo esc_url( $go ); ?>"><?php echo esc_html( $go_label ); ?> <?php echo eab_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
	<?php endif; ?>
</div>
	<?php
}
