<?php
/**
 * EA BULL · โมดูล Site Chrome (header · แผงติดต่อ · footer · แถบล่างมือถือ · ปุ่ม LINE ลอย)
 *
 * หน้าตา "Glass Sky" (docs/design.md · ต้นแบบ dev/mockup/index.html)
 * - กรอบเว็บ .site-frame เปิดใน header.php ปิดใน footer.php
 * - header: แคปซูลกระจกลอย (โลโก้ · เมนู · ปุ่มทอง "ทัก LINE") · จอแคบเป็นปุ่มกลมเปิดแผ่นเมนูกระจก
 * - แผงติดต่อ "Let's talk" (eab_chrome_contact_panel): ข้อความ + รายการช่องทางซ้าย · การ์ดปุ่ม LINE / OpenChat + สิ่งที่ทีมงานจะถามขวา
 *   หน้าย่อยเรียกผ่าน eab_line_cta() · หน้าที่ไม่ได้เรียก (เช่นหน้าแรก) footer.php พิมพ์ให้ 1 ครั้งก่อน footer
 * - footer กระจกสว่าง: แบรนด์ · คู่มือ · หน้าในเว็บ · เอกสาร · คำเตือนความเสี่ยง · แถวลิขสิทธิ์ + ลิงก์นโยบาย + ตั้งค่าคุกกี้
 * - ค่าเริ่มต้น + ส่วน Customizer อยู่ในไฟล์นี้ (ฟิลเตอร์ eab_defaults / eab_customizer_sections)
 * - ปุ่มติดต่อทุกปุ่มใช้ eab_contact_target() · ไม่มีปุ่มใดชี้ไปที่ '#'
 *
 * @package eabull
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ==============================================================
 * ค่าเริ่มต้น
 * ============================================================== */
add_filter(
	'eab_defaults',
	function ( $d ) {
		return array_merge(
			$d,
			array(
				/* ช่องทาง (ต่อจาก facebook_url ใน "1) ช่องทางติดต่อ") */
				'instagram_url'            => '',
				'tiktok_url'               => '',
				'youtube_url'              => '',
				'float_line_text'          => 'ถามทีมงานทาง LINE',

				/* แผงติดต่อท้ายหน้าย่อย (eab_line_cta) */
				'chrome_cta_label'         => 'Talk to the team',
				'contact_title'            => 'อยากรู้ว่า EA BULL เข้ากับแผนของคุณไหม?',
				'contact_text'             => 'บอกทุนที่ตั้งใจใช้และบัญชี MT5 ที่มีอยู่ ทีมงานจะอธิบายขั้นตอนรับไฟล์และการติดตั้งให้ทาง LINE ค่อย ๆ ตัดสินใจได้ ไม่ต้องรีบ',

				/* แผงติดต่อ · ใช้กับหน้าที่ไม่มีแผงติดต่อของตัวเอง (เช่น หน้าแรก) */
				'footer_console_label'     => "Let's talk",
				'footer_headline'          => 'มีคำถามก่อนเริ่ม คุยกับทีมงานได้เลย',
				'footer_sub'               => 'ถามเรื่องแพ็กเกจ ทุนที่ตั้งใจใช้ หรือขั้นตอนติดตั้ง ทีมงานคนไทยตอบทาง LINE',

				/* แผงติดต่อ · การ์ดขวา */
				'chrome_card_title'        => 'ส่งข้อความหาทีมงาน',
				'chrome_card_text'         => 'แตะปุ่มด้านล่าง แล้วพิมพ์คำถามแรกได้ทันที',
				'footer_line_text'         => 'ทัก LINE คุยกับทีมงาน',
				'footer_qr_toggle_text'    => 'สแกนเพิ่มเพื่อน LINE',
				'footer_qr_text'           => 'เปิดกล้องมือถือหรือแอป LINE แล้วสแกน เพื่อเพิ่มเพื่อน @eabull ได้ทันที',
				'footer_line_qr_alt'       => 'QR Code สำหรับเพิ่ม LINE ของ EA BULL',
				'footer_prep_title'        => 'สิ่งที่ทีมงานจะถาม',
				'footer_prep_text'         => 'เตรียมคำตอบไว้ จะคุยกันได้เร็วขึ้น',
				'footer_prep_items'        => "เงินทุนที่จะใช้กับ EA\nมีบัญชี MT5 แล้วหรือยัง และกับโบรกเกอร์ไหน\nใช้คอมพิวเตอร์ มือถือ หรือมี VPS อยู่แล้ว\nคำถามที่อยากรู้ก่อนเริ่ม",
				'footer_hours_title'       => 'เวลาตอบแชท',
				'footer_hours_text'        => '',

				/* Footer · คอลัมน์ลิงก์ */
				'footer_status_text'       => 'EA BULL ผู้ช่วยเทรดอัตโนมัติสำหรับ MetaTrader 5 ทำงานตามแผนและขีดจำกัดความเสี่ยงที่คุณตั้งเอง มีคู่มือภาษาไทยครบตั้งแต่เปิดบัญชีจนรันบน VPS และทีมงานคนไทยคอยดูแลทาง LINE',
				'footer_about_points'      => "ใช้กับ MT5 บน Windows และ VPS\nดูพอร์ตผ่านแอป MT5 บนมือถือได้ทุกที่\nทีมงานคนไทยตอบทาง LINE OA",
				'footer_index_title'       => 'หน้าในเว็บ',
				'footer_docs_title'        => 'ข้อมูลและนโยบาย',
				'footer_docs_items'        => "about|เกี่ยวกับเรา\nprivacy-policy|นโยบายความเป็นส่วนตัว\nterms-of-use|เงื่อนไขการใช้บริการ\ndata-deletion|ขอลบข้อมูลส่วนบุคคล",
				'footer_risk_link'         => 'ความเสี่ยงของการใช้ EA',
				'footer_risk_short'        => 'การเทรด Forex และ CFD มีความเสี่ยงสูง อาจขาดทุนจนหมดเงินทุน ผลในอดีตไม่รับประกันอนาคต EA BULL ไม่ใช่คำแนะนำการลงทุนและไม่รับประกันกำไร',
				'footer_risk_more'         => 'อ่านคำเตือนฉบับเต็ม',
				'footer_facebook_text'     => 'เพจ Facebook',
				'footer_instagram_text'    => 'Instagram',
				'footer_tiktok_text'       => 'TikTok',
				'footer_youtube_text'      => 'YouTube',
				'footer_email_text'        => 'ส่งอีเมลถึงทีมงาน',

				/* Footer · แถวลิขสิทธิ์ */
				'footer_copyright_text'    => 'สงวนลิขสิทธิ์',
				'footer_legal_items'       => "privacy-policy|นโยบายความเป็นส่วนตัว\nterms-of-use|เงื่อนไขการใช้บริการ",
				'footer_backtop_text'      => 'กลับขึ้นด้านบน',

				/* แถบล่างมือถือ (ช่องท้ายแถบ = ติดต่อ) · ช่อง 2 = แดชบอร์ด (ลิงก์ dashboard_url) */
				'mobile_nav_dash_label'    => 'แดชบอร์ด',
				'mobile_nav_contact_label' => 'ติดต่อ',

				/* เมนูหลัก (ใช้เมื่อยังไม่ได้สร้างเมนูใน WordPress) + ข้อความระบบ */
				/* ปุ่มไปแดชบอร์ดลูกค้า (Header + ช่อง 2 ของแถบล่างมือถือ) */
				'dashboard_url'            => '',
				'header_dash_label'        => 'แดชบอร์ด',
				'nav_home_label'           => 'หน้าแรก',
				'nav_products_label'       => 'สินค้า',
				'nav_test_label'           => 'การทดสอบ',
				'nav_guide_label'          => 'คู่มือการใช้งาน',
				'nav_pricing_label'        => 'แพ็กเกจ',
				'nav_articles_label'       => 'บทความ',
				'nav_contact_label'        => 'ติดต่อ',
				'chrome_skip_text'         => 'ข้ามไปที่เนื้อหาหลัก',
				'chrome_loading_text'      => 'กำลังโหลดบทความ…',
			)
		);
	}
);

/* ==============================================================
 * Customizer
 * ============================================================== */

/**
 * แทรกรายการต่อจาก key ที่กำหนด (ไม่มี key นั้น = ต่อท้าย)
 */
function eab_chrome_insert_after( $array, $after, $insert ) {
	if ( ! is_array( $array ) ) {
		$array = array();
	}
	if ( ! isset( $array[ $after ] ) ) {
		return array_merge( $array, $insert );
	}
	$out = array();
	foreach ( $array as $key => $value ) {
		$out[ $key ] = $value;
		if ( $key === $after ) {
			foreach ( $insert as $new_key => $new_value ) {
				$out[ $new_key ] = $new_value;
			}
		}
	}
	return $out;
}

add_filter(
	'eab_customizer_sections',
	function ( $sections, $d ) {
		/* 1) ช่องทางติดต่อ · โซเชียลเพิ่ม + ป้ายแผงติดต่อท้ายหน้าย่อย */
		if ( isset( $sections['eab_general']['fields'] ) ) {
			$sections['eab_general']['fields'] = eab_chrome_insert_after(
				$sections['eab_general']['fields'],
				'line_url',
				array(
					'contact_fallback_text' => array( 'ข้อความปุ่มติดต่อเมื่อยังไม่ใส่ลิงก์ LINE', 'text', 'ปุ่มจะพาไปหน้า /go/ (เมื่อเผยแพร่แล้ว) · ไม่มีทั้ง LINE และหน้า /go/ = ซ่อนปุ่มติดต่อทั้งเว็บ' ),
				)
			);
			$sections['eab_general']['fields'] = eab_chrome_insert_after(
				$sections['eab_general']['fields'],
				'facebook_url',
				array(
					'instagram_url' => array( 'ลิงก์ Instagram (ถ้ามี)', 'url' ),
					'tiktok_url'    => array( 'ลิงก์ TikTok (ถ้ามี)', 'url' ),
					'youtube_url'   => array( 'ลิงก์ YouTube (ถ้ามี)', 'url' ),
				)
			);
			$sections['eab_general']['fields'] = eab_chrome_insert_after(
				$sections['eab_general']['fields'],
				'contact_title',
				array(
					'chrome_cta_label' => array( 'แผงติดต่อท้ายหน้าย่อย · ป้ายเล็กเหนือหัวข้อ', 'text', 'ข้อความภาษาไทยแสดงแบบไม่เว้นระยะตัวอักษร' ),
				)
			);
		}

		$sections['eab_footer'] = array(
			'title'       => '15) แผงติดต่อ + Footer ท้ายเว็บ',
			'description' => 'แผงติดต่อ (กระจก 2 คอลัมน์) แสดงท้ายหน้าย่อยทุกหน้า · หน้าที่ไม่มีแผงติดต่อของตัวเอง (เช่น หน้าแรก) ใช้หัวข้อชุด "หน้าอื่น ๆ" ด้านล่าง · ปุ่มติดต่อใช้ลิงก์ LINE จาก "1) ช่องทางติดต่อ" (ไม่มี LINE = ไปหน้า /go/ ถ้าเผยแพร่แล้ว ไม่มีทั้งคู่ = ซ่อนแผง) · รายการช่องทางซ้ายมาจากลิงก์ที่กรอกในหมวด 1 · Footer กระจกสว่าง: แบรนด์ · คู่มือ · หน้าในเว็บ · เอกสาร · คำเตือนความเสี่ยง · แถวลิขสิทธิ์ · ช่องที่เว้นว่างจะถูกซ่อน',
			'fields'      => array(
				'footer_console_label'  => array( 'แผงติดต่อ (หน้าอื่น ๆ) · ป้ายเล็กเหนือหัวข้อ', 'text' ),
				'footer_headline'       => array( 'แผงติดต่อ (หน้าอื่น ๆ) · หัวข้อ (ว่าง = ใช้หัวข้อแผงติดต่อท้ายหน้าย่อย)', 'text' ),
				'footer_sub'            => array( 'แผงติดต่อ (หน้าอื่น ๆ) · คำอธิบาย (ว่าง = ใช้คำอธิบายแผงติดต่อท้ายหน้าย่อย)', 'textarea' ),
				'chrome_card_title'     => array( 'การ์ดติดต่อ · หัวข้อ', 'text' ),
				'chrome_card_text'      => array( 'การ์ดติดต่อ · คำอธิบายสั้น (ว่าง = ไม่แสดง)', 'text' ),
				'footer_line_text'      => array( 'ข้อความปุ่ม LINE (ใช้กับปุ่มติดต่อทั่วทั้งเว็บ)', 'text' ),
				'footer_qr_toggle_text' => array( 'QR · หัวข้อข้างรูป', 'text' ),
				'footer_qr_text'        => array( 'QR · คำอธิบายข้างรูป', 'text' ),
				'footer_line_qr_alt'    => array( 'คำอธิบายรูป QR (alt)', 'text' ),
				'footer_prep_title'     => array( 'สิ่งที่ทีมงานจะถาม · หัวข้อ', 'text' ),
				'footer_prep_text'      => array( 'สิ่งที่ทีมงานจะถาม · คำอธิบาย', 'textarea' ),
				'footer_prep_items'     => array( 'สิ่งที่ทีมงานจะถาม · รายการ (บรรทัดละ 1 ข้อ · ว่าง = ซ่อน)', 'textarea' ),
				'footer_hours_title'    => array( 'เวลาตอบแชท · หัวข้อ', 'text' ),
				'footer_hours_text'     => array( 'เวลาตอบแชท · รายละเอียด (ว่าง = ไม่แสดง)', 'textarea', 'กรอกตามเวลาที่ทีมตอบได้จริงเท่านั้น บรรทัดละ 1 ช่วง' ),
				'footer_facebook_text'  => array( 'ชื่อลิงก์ Facebook', 'text' ),
				'footer_instagram_text' => array( 'ชื่อลิงก์ Instagram', 'text' ),
				'footer_tiktok_text'    => array( 'ชื่อลิงก์ TikTok', 'text' ),
				'footer_youtube_text'   => array( 'ชื่อลิงก์ YouTube', 'text' ),
				'footer_email_text'     => array( 'ชื่อลิงก์อีเมล', 'text' ),
				'footer_status_text'    => array( 'Footer · คำอธิบายใต้โลโก้ (ว่าง = ไม่แสดง)', 'textarea' ),
				'footer_about_points'   => array( 'Footer · จุดเด่นใต้คำอธิบาย (บรรทัดละ 1 ข้อ · ว่าง = ไม่แสดง)', 'textarea' ),
				'footer_index_title'    => array( 'Footer · หัวคอลัมน์หน้าในเว็บไซต์', 'text', 'รายการหน้ามาจากเมนูหลัก (เฉพาะระดับบน) · ยังไม่มีเมนู = ใช้หน้ามาตรฐานที่เผยแพร่แล้ว · คอลัมน์คู่มือใช้ชื่อ "เมนู · กลุ่มคู่มือ" ในหมวด 16.1' ),
				'footer_docs_title'     => array( 'Footer · หัวคอลัมน์เอกสาร', 'text' ),
				'footer_docs_items'     => array( 'Footer · รายการเอกสาร (slug|ชื่อลิงก์ บรรทัดละ 1 หน้า)', 'textarea', 'แสดงเฉพาะหน้าที่เผยแพร่แล้ว · หน้าที่อยู่ในแถวลิขสิทธิ์แล้วจะไม่แสดงซ้ำ · ลิงก์ประกาศความเสี่ยงแสดงเมื่อหน้า risk-disclosure เผยแพร่แล้ว (ข้อความเตือนฉบับเต็มแสดงใน footer เสมอ)' ),
				'footer_risk_link'      => array( 'Footer · ชื่อลิงก์ประกาศความเสี่ยง', 'text' ),
				'footer_risk_short'     => array( 'Footer · คำเตือนความเสี่ยงแบบสั้น (ว่าง = ใช้ฉบับเต็มจากหมวด 13)', 'textarea', 'ต้องบอกว่าขาดทุนได้ ผลในอดีตไม่รับประกันอนาคต และไม่ใช่คำแนะนำการลงทุน · ฉบับเต็มยังแสดงในหน้าแรก /go บทความ และหน้าประกาศความเสี่ยง' ),
				'footer_risk_more'      => array( 'Footer · ข้อความลิงก์ไปคำเตือนฉบับเต็ม', 'text' ),
				'footer_copyright_text' => array( 'แถวลิขสิทธิ์ · ข้อความต่อท้าย ©', 'text' ),
				'footer_legal_items'    => array( 'แถวลิขสิทธิ์ · ลิงก์นโยบาย (slug|ชื่อลิงก์ บรรทัดละ 1 หน้า)', 'textarea', 'แสดงเฉพาะหน้าที่เผยแพร่แล้ว · ลิงก์ "ตั้งค่าคุกกี้" ต่อท้ายให้อัตโนมัติ (แก้ชื่อได้ที่หมวดคุกกี้)' ),
				'footer_backtop_text'   => array( 'แถวลิขสิทธิ์ · ข้อความลิงก์กลับขึ้นด้านบน (ว่าง = ไม่แสดง)', 'text' ),
			),
		);

		$sections['eab_mobile_nav'] = array(
			'title'       => '16) เมนูลัดมือถือด้านล่าง',
			'description' => 'แถบกระจกลอยด้านล่างบนจอมือถือ (กว้างไม่เกิน 820px) · ช่อง 1 ถึง 4 เป็นลิงก์ (ช่อง 2 = แดชบอร์ด) · ช่องท้ายแถบคือช่องติดต่อ: มีลิงก์ LINE = ทัก LINE · ไม่มี LINE = ไปหน้า /go/ (เมื่อเผยแพร่แล้ว) · ไม่มีทั้งคู่ = ไม่แสดง · ช่องที่ชื่อว่างจะถูกซ่อน · ช่องของหน้าปัจจุบันเป็นแคปซูลขาวให้อัตโนมัติ',
			'fields'      => array(
				'show_mobile_nav'          => array( 'แสดงเมนูลัดด้านล่างบนมือถือ', 'checkbox' ),
				'mobile_nav_home_label'    => array( 'ช่อง 1 · ชื่อ', 'text' ),
				'mobile_nav_home_url'      => array( 'ช่อง 1 · ลิงก์หรือ slug', 'text' ),
				'mobile_nav_dash_label'    => array( 'ช่อง 2 · ชื่อปุ่มแดชบอร์ด (ลิงก์ตั้งที่หมวด 16.1 · ว่าง = ไม่แสดงช่องนี้)', 'text' ),
				'mobile_nav_price_label'   => array( 'ช่อง 3 · ชื่อ', 'text' ),
				'mobile_nav_price_url'     => array( 'ช่อง 3 · ลิงก์หรือ slug', 'text' ),
				'mobile_nav_install_label' => array( 'ช่อง 4 · ชื่อ', 'text' ),
				'mobile_nav_install_url'   => array( 'ช่อง 4 · ลิงก์หรือ slug', 'text' ),
				'mobile_nav_line_label'    => array( 'ช่องท้ายแถบ · ชื่อเมื่อมีลิงก์ LINE', 'text' ),
				'mobile_nav_contact_label' => array( 'ช่องท้ายแถบ · ชื่อเมื่อยังไม่มี LINE (ไปหน้า /go/)', 'text' ),
			),
		);

		$sections = eab_chrome_insert_after(
			$sections,
			'eab_mobile_nav',
			array(
				'eab_chrome_nav' => array(
					'title'       => '16.1) เมนูหลัก (Header) · ข้อความระบบ',
					'description' => 'ชื่อเมนูที่ใช้เมื่อยังไม่ได้ตั้งเมนูใน รูปแบบ → เมนู (แสดงเฉพาะหน้าที่เผยแพร่แล้ว) · "ติดต่อ" จะต่อท้ายเมนูหลักให้อัตโนมัติเมื่อหน้า /go/ เผยแพร่แล้วและเมนูยังไม่มีลิงก์ติดต่อ · ชื่อเว็บและคำโปรยข้างโลโก้มาจาก ตั้งค่า → ทั่วไป (ชื่อเว็บไซต์ / คำโปรย)',
					'fields'      => array(
						'dashboard_url'       => array( 'ลิงก์แดชบอร์ดลูกค้า (ปุ่มบน Header และช่อง 2 ของแถบล่างมือถือ · ว่าง = ไม่แสดงทั้งสองที่)', 'text' ),
						'header_dash_label'   => array( 'ปุ่มแดชบอร์ดบน Header · ชื่อ (ว่าง = ไม่แสดง)', 'text' ),
						'nav_home_label'      => array( 'เมนู · หน้าแรก', 'text' ),
						'nav_products_label'  => array( 'เมนู · กลุ่มสินค้า (เรียงตามเลขรุ่นเอง · ว่าง = ไม่แสดง)', 'text' ),
						'nav_test_label'      => array( 'เมนู · กลุ่มการทดสอบ', 'text' ),
						'nav_guide_label'     => array( 'เมนู · กลุ่มคู่มือ (ใช้เป็นหัวคอลัมน์คู่มือใน footer ด้วย)', 'text' ),
						'nav_pricing_label'   => array( 'เมนู · แพ็กเกจ', 'text' ),
						'nav_articles_label'  => array( 'เมนู · บทความ', 'text' ),
						'nav_contact_label'   => array( 'เมนู · ติดต่อ (ว่าง = ไม่ต่อท้ายอัตโนมัติ)', 'text' ),
						'chrome_skip_text'    => array( 'ลิงก์ข้ามไปเนื้อหา (เห็นเมื่อกด Tab)', 'text' ),
						'chrome_loading_text' => array( 'ข้อความระหว่างโหลดบทความเพิ่ม', 'text' ),
					),
				),
			)
		);

		return $sections;
	},
	10,
	2
);

/* ==============================================================
 * ตัวช่วย
 * ============================================================== */

/**
 * ลิงก์ใช้งานได้จริง (ไม่ว่าง ไม่ใช่ '#')
 */
function eab_chrome_url_ok( $url ) {
	$url = trim( (string) $url );
	return '' !== $url && '#' !== $url;
}

/**
 * คำที่ห้ามตัดกลางบรรทัดในหัวข้อ (eab_text) · คำทับศัพท์ที่ตัวตัดคำของเบราว์เซอร์มักแยกผิด
 */
add_filter(
	'eab_keep_words',
	function ( $words ) {
		return array_merge( (array) $words, array( 'เด|โม' ) );
	}
);

/**
 * body class: มีแถบล่างมือถือจริง (มีอย่างน้อย 1 ช่อง) → CSS เว้นที่ท้ายหน้า + ซ่อนปุ่มซ้ำบนมือถือ
 */
add_filter(
	'body_class',
	function ( $classes ) {
		if ( eab_mod( 'show_mobile_nav' ) ) {
			$dock = eab_chrome_dock();
			if ( ! empty( $dock['items'] ) ) {
				$classes[] = 'has-dock';
			}
		}
		return $classes;
	}
);

/**
 * ข้อความของ JS (main.js) · ต่อจาก eab_assets() ที่ลงทะเบียน eab-main แล้ว
 */
add_action(
	'wp_enqueue_scripts',
	function () {
		wp_localize_script(
			'eab-main',
			'eabChrome',
			array(
				'loadingText' => (string) eab_mod( 'chrome_loading_text' ),
			)
		);
	},
	20
);

/**
 * ตัวสลับภาษา · ไม่ใช้ GTranslate (แปลฝั่งเบราว์เซอร์อย่างเดียว ไม่มี URL ให้ Google เก็บ และ widget ใส่ inline style ทับธีม)
 * ใช้ eab_language_switcher() เดิม โดยซ่อน shortcode [gtranslate] ระหว่างเรียก → ได้ TranslatePress / Polylang / WPML หรือรายการสำรอง
 */
function eab_chrome_language_switcher() {
	if ( ! eab_mod( 'show_language_switcher' ) || ! function_exists( 'eab_language_switcher' ) ) {
		return;
	}
	global $shortcode_tags;
	$gtranslate = null;
	if ( is_array( $shortcode_tags ) && isset( $shortcode_tags['gtranslate'] ) ) {
		$gtranslate = $shortcode_tags['gtranslate'];
		unset( $shortcode_tags['gtranslate'] );
	}
	eab_language_switcher();
	if ( null !== $gtranslate ) {
		$shortcode_tags['gtranslate'] = $gtranslate;
	}
}

/**
 * URL ของหน้าตาม slug เมื่อเผยแพร่แล้ว ('' = ยังไม่เผยแพร่/ไม่มี)
 */
function eab_chrome_page_url( $slug ) {
	return function_exists( 'eab_published_page_url' ) ? (string) eab_published_page_url( $slug ) : '';
}

/**
 * URL หน้ารวมบทความ (หน้า Posts ของ WordPress หรือเพจ slug articles) · '' = ยังไม่เผยแพร่
 */
function eab_chrome_articles_url() {
	$posts_page = (int) get_option( 'page_for_posts' );
	if ( $posts_page > 0 && 'publish' === get_post_status( $posts_page ) ) {
		return (string) get_permalink( $posts_page );
	}
	return eab_chrome_page_url( 'articles' );
}

/**
 * โลโก้บนพื้นสว่าง (header/footer แสดงราว 52px): โลโก้ตัวอักษรที่อัปโหลดในหมวด 1 → ไฟล์เล็กในธีม (160px webp ~9 KB)
 * ไม่ใช้ Custom Logo ของ WordPress ตรงนี้ เพราะไฟล์ต้นฉบับใหญ่หลายร้อย KB (PageSpeed มือถือ 7 ต.ค. 2026)
 */
function eab_chrome_logo_url() {
	$custom = trim( (string) eab_mod( 'wordmark_dark' ) );
	if ( '' !== $custom ) {
		return $custom;
	}
	return get_template_directory_uri() . '/assets/img/brand/eabull-logo-160.webp';
}

/**
 * โลโก้ + ชื่อเว็บ + คำโปรย (ตั้งค่า → ทั่วไป) · ใช้ทั้ง header และ footer
 */
function eab_chrome_brand( $class = '' ) {
	$name    = trim( (string) get_bloginfo( 'name' ) );
	$tagline = trim( (string) get_bloginfo( 'description' ) );
	$home    = trim( (string) eab_mod( 'nav_home_label' ) );
	/* ชื่อลิงก์ = ข้อความที่ตาเห็น (WCAG 2.5.3) + "หน้าแรก" ที่อ่านได้เฉพาะโปรแกรมอ่านจอ · ไม่ใช้ aria-label เมื่อมีชื่อเว็บ */
	?>
	<a class="<?php echo esc_attr( trim( 'brand ' . $class ) ); ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>"<?php echo '' === $name ? ' aria-label="' . esc_attr( '' !== $home ? $home : home_url( '/' ) ) . '"' : ''; ?>>
		<img class="brand-logo-img" src="<?php echo esc_url( eab_chrome_logo_url() ); ?>" alt="" width="160" height="115" decoding="async">
		<?php if ( '' !== $name ) : ?>
			<span class="brand-text"><strong><?php echo esc_html( $name ); ?></strong> <?php if ( '' !== $tagline ) : ?><span><?php echo esc_html( $tagline ); ?></span><?php endif; ?><?php if ( '' !== $home ) : ?><span class="screen-reader-text"> · <?php echo esc_html( $home ); ?></span><?php endif; ?></span>
		<?php endif; ?>
	</a>
	<?php
}

/**
 * ปุ่ม "แดชบอร์ด" ท้าย header (น้ำเงินทึบ) · ไม่มีลิงก์หรือชื่อ = ไม่พิมพ์ · จอที่มีแถบล่างซ่อนไว้ (แถบล่างมีช่องแดชบอร์ดแล้ว)
 */
function eab_chrome_header_dash() {
	$url   = trim( (string) eab_mod( 'dashboard_url' ) );
	$label = trim( (string) eab_mod( 'header_dash_label' ) );
	if ( '' === $url || '' === $label ) {
		return;
	}
	printf(
		'<a class="btn chrome-btn chrome-btn--sm header-dash" href="%1$s"><span>%2$s</span><span class="btn-ic" aria-hidden="true">%3$s</span></a>',
		esc_url( eab_link_url( $url ) ),
		esc_html( $label ),
		eab_icon( 'gauge' ) // phpcs:ignore WordPress.Security.EscapeOutput
	);
}

/**
 * ป้ายเล็กเหนือหัวข้อ · ข้อความไทยไม่เว้นระยะตัวอักษร (kicker--th)
 */
function eab_chrome_kicker( $text ) {
	$text = trim( (string) $text );
	if ( '' === $text || ! eab_show_kickers() ) {
		return;
	}
	$thai = preg_match( '/\p{Thai}/u', $text );
	echo '<p class="kicker' . ( $thai ? ' kicker--th' : '' ) . '">' . esc_html( $text ) . '</p>';
}

/**
 * โครงเมนูมาตรฐานของ EA BULL (ใช้เมื่อยังไม่มีเมนูใน WP) · เฉพาะหน้าที่เผยแพร่แล้ว
 *
 * @return array[] { label, url, slug, current, children[] }
 */
function eab_chrome_nav_tree() {
	static $tree = null;
	if ( null !== $tree ) {
		return $tree;
	}
	$tree = array(
		array(
			'label'    => (string) eab_mod( 'nav_home_label' ),
			'url'      => home_url( '/' ),
			'current'  => is_front_page(),
			'children' => array(),
		),
	);

	/* สินค้าทั้งหมดเรียงตามเลขรุ่น (products.php) · ทุกตัวมีเพจของตัวเอง */
	$label = trim( (string) eab_mod( 'nav_products_label' ) );
	if ( '' !== $label && function_exists( 'eab_products' ) ) {
		$children = array();
		$active   = false;
		foreach ( eab_products() as $item ) {
			$url = '' !== $item['slug'] ? eab_chrome_page_url( $item['slug'] ) : home_url( '/#modes' );
			if ( '' === $url ) {
				continue;
			}
			$here       = '' !== $item['slug'] && is_page( $item['slug'] );
			$active     = $active || $here;
			$children[] = array(
				'label'   => $item['label'],
				'url'     => $url,
				'current' => $here,
			);
		}
		if ( $children ) {
			$tree[] = array(
				'label'    => $label,
				'url'      => $children[0]['url'],
				'current'  => false,
				'ancestor' => $active,
				'children' => $children,
			);
		}
	}

	$groups = array(
		'test'  => array( 'nav_test_label', 'backtest' ),
		'guide' => array( 'nav_guide_label', 'how-to-install' ),
	);
	foreach ( $groups as $group => $conf ) {
		$children = array();
		$active   = false;
		if ( function_exists( 'eab_guide_links' ) ) {
			foreach ( eab_guide_links( array( $group ) ) as $slug => $link ) {
				$url = eab_chrome_page_url( $slug );
				if ( '' === $url ) {
					continue;
				}
				$here       = is_page( $slug );
				$active     = $active || $here;
				$children[] = array(
					'label'   => $link['label'],
					'url'     => $url,
					'current' => $here,
				);
			}
		}
		if ( empty( $children ) ) {
			continue;
		}
		$landing = eab_chrome_page_url( $conf[1] );
		$tree[]  = array(
			'label'    => (string) eab_mod( $conf[0] ),
			'url'      => '' !== $landing ? $landing : $children[0]['url'],
			'current'  => false,
			'ancestor' => $active,
			'children' => $children,
		);
	}

	$singles = array(
		array( 'nav_pricing_label', eab_chrome_page_url( 'pricing' ), is_page( 'pricing' ) ),
		array( 'nav_articles_label', eab_chrome_articles_url(), is_home() ),
		array( 'nav_contact_label', eab_chrome_page_url( 'go' ), is_page( 'go' ) ),
	);
	foreach ( $singles as $single ) {
		$label = trim( (string) eab_mod( $single[0] ) );
		if ( '' === $single[1] || '' === $label ) {
			continue;
		}
		$tree[] = array(
			'label'    => $label,
			'url'      => $single[1],
			'current'  => (bool) $single[2],
			'children' => array(),
		);
	}
	return $tree;
}

/**
 * เมนูสำรอง (ยังไม่ได้ตั้งเมนูหลักใน WP) · แทน eab_fallback_menu() ที่ลิงก์ /go/ และ /articles/ แม้ยังไม่เผยแพร่
 */
function eab_chrome_fallback_menu() {
	echo '<ul class="nav-list">';
	foreach ( eab_chrome_nav_tree() as $item ) {
		$classes = array( 'menu-item' );
		if ( ! empty( $item['children'] ) ) {
			$classes[] = 'menu-item-has-children';
		}
		if ( ! empty( $item['current'] ) ) {
			$classes[] = 'current-menu-item';
		}
		if ( ! empty( $item['ancestor'] ) ) {
			$classes[] = 'current-menu-ancestor';
		}
		echo '<li class="' . esc_attr( implode( ' ', $classes ) ) . '"><a href="' . esc_url( $item['url'] ) . '"' . ( ! empty( $item['current'] ) ? ' aria-current="page"' : '' ) . '>' . esc_html( $item['label'] ) . '</a>';
		if ( ! empty( $item['children'] ) ) {
			echo '<ul class="sub-menu">';
			foreach ( $item['children'] as $child ) {
				echo '<li class="menu-item' . ( $child['current'] ? ' current-menu-item' : '' ) . '"><a href="' . esc_url( $child['url'] ) . '"' . ( $child['current'] ? ' aria-current="page"' : '' ) . '>' . esc_html( $child['label'] ) . '</a></li>';
			}
			echo '</ul>';
		}
		echo '</li>';
	}
	echo '</ul>';
}

/**
 * ลิงก์ไปส่วนหนึ่งในหน้า (มี # เช่น EA BULL 5.0 → /#modes) ไม่นับเป็นหน้าปัจจุบัน
 * WordPress ตัด # ทิ้งก่อนเทียบ URL ลิงก์นี้จึงถูกไฮไลต์ทุกครั้งที่อยู่หน้าแรก และลากหัวเมนู "สินค้า" ให้ไฮไลต์ตามไปด้วย
 * เอาสถานะปัจจุบันออกจากลิงก์แบบนี้ แล้วเอาสถานะหัวเมนูออกจากหัวเมนูที่ไม่มีลูกเป็นหน้าปัจจุบันจริงเหลืออยู่
 */
function eab_chrome_menu_hash_items( $items ) {
	$parent  = array();
	$hashed  = array();
	foreach ( $items as $item ) {
		$parent[ (int) $item->db_id ] = (int) $item->menu_item_parent;
		/* หน้าแรกที่มี query (?utm=…) WordPress ใส่คลาส current-menu-item ให้ลิงก์หน้าแรกโดยไม่ตั้ง current จึงเช็กทั้งสองอย่าง */
		$is_current = ! empty( $item->current ) || in_array( 'current-menu-item', (array) $item->classes, true );
		if ( $is_current && false !== strpos( (string) $item->url, '#' ) ) {
			$item->current = false;
			$item->classes = array_values( array_diff( (array) $item->classes, array( 'current-menu-item', 'current_page_item' ) ) );
			$hashed[]      = (int) $item->db_id;
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
add_filter( 'wp_nav_menu_objects', 'eab_chrome_menu_hash_items', 20 );

/**
 * ต่อท้าย "ติดต่อ" (/go/) ในเมนูหลักที่สร้างเองใน WP
 * เฉพาะเมื่อหน้า go เผยแพร่แล้ว และเมนูยังไม่มีลิงก์ /go/ รายการชื่อเดียวกัน หรือลิงก์ LINE
 */
function eab_chrome_menu_contact( $items, $args ) {
	if ( ! is_object( $args ) || empty( $args->theme_location ) || 'primary' !== $args->theme_location ) {
		return $items;
	}
	$label = trim( (string) eab_mod( 'nav_contact_label' ) );
	$go    = eab_chrome_page_url( 'go' );
	if ( '' === $label || '' === $go ) {
		return $items;
	}
	/* มีลิงก์ /go/ อยู่แล้ว (รวมแบบไม่มี / ท้าย หรือมี ?query / #anchor) หรือมีรายการชื่อเดียวกัน */
	if ( false !== strpos( $items, esc_url( $go ) ) || preg_match( '#href="[^"]*/go/?(?:[?\#][^"]*)?"#i', $items ) || false !== strpos( $items, '>' . esc_html( $label ) . '<' ) ) {
		return $items;
	}
	/* มีลิงก์ LINE (OA / OpenChat / LIFF / line://) อยู่แล้ว */
	if ( preg_match( '#href="(?:(?:https?:)?//(?:www\.)?(?:lin\.ee|line\.me|liff\.line\.me|page\.line\.me)/|line:)#i', $items ) ) {
		return $items;
	}
	if ( eab_has_line_url() ) {
		$line = trim( (string) eab_mod( 'line_url' ) );
		if ( false !== strpos( $items, esc_url( $line ) ) ) {
			return $items;
		}
	}
	$here = is_page( 'go' );
	return $items . '<li class="menu-item menu-item-eab-contact' . ( $here ? ' current-menu-item' : '' ) . '"><a href="' . esc_url( $go ) . '"' . ( $here ? ' aria-current="page"' : '' ) . '>' . esc_html( $label ) . '</a></li>';
}
add_filter( 'wp_nav_menu_items', 'eab_chrome_menu_contact', 10, 2 );

/**
 * โซเชียล + อีเมล ที่กรอกแล้ว
 *
 * @return array[] { icon, url, label, mailto }
 */
function eab_chrome_socials() {
	$out = array();
	foreach ( array(
		'facebook'  => array( 'facebook_url', 'footer_facebook_text' ),
		'instagram' => array( 'instagram_url', 'footer_instagram_text' ),
		'tiktok'    => array( 'tiktok_url', 'footer_tiktok_text' ),
		'youtube'   => array( 'youtube_url', 'footer_youtube_text' ),
	) as $icon => $keys ) {
		$url   = trim( (string) eab_mod( $keys[0] ) );
		$label = trim( (string) eab_mod( $keys[1] ) );
		if ( ! eab_chrome_url_ok( $url ) || '' === $label ) {
			continue;
		}
		$out[] = array(
			'icon'  => $icon,
			'url'   => $url,
			'label' => $label,
		);
	}
	$email = trim( (string) eab_mod( 'contact_email' ) );
	$label = trim( (string) eab_mod( 'footer_email_text' ) );
	if ( '' !== $email && '' !== $label && is_email( $email ) ) {
		$out[] = array(
			'icon'   => 'mail',
			'mailto' => $email,
			'label'  => $label,
		);
	}
	return $out;
}

/**
 * href ของรายการโซเชียล (อีเมลเข้ารหัสด้วย antispambot)
 */
function eab_chrome_social_href( $item ) {
	if ( isset( $item['mailto'] ) ) {
		return 'mailto:' . esc_attr( antispambot( $item['mailto'] ) );
	}
	return esc_url( $item['url'] );
}

/**
 * แถวไอคอนโซเชียล (แผ่นเมนูมือถือ · คอลัมน์แบรนด์ใน footer) · ไม่มีช่องทางที่กรอก = ไม่พิมพ์อะไร
 */
function eab_chrome_social_row( $class = 'nav-social' ) {
	$items = eab_chrome_socials();
	if ( empty( $items ) ) {
		return;
	}
	echo '<div class="' . esc_attr( $class ) . '">';
	foreach ( $items as $item ) {
		printf(
			'<a class="social-link social-link--%5$s" href="%1$s"%2$s aria-label="%3$s">%4$s</a>',
			eab_chrome_social_href( $item ), // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in eab_chrome_social_href
			isset( $item['mailto'] ) ? '' : ' target="_blank" rel="noopener"',
			esc_attr( $item['label'] ),
			eab_icon( $item['icon'], 'icon' ), // phpcs:ignore WordPress.Security.EscapeOutput
			esc_attr( $item['icon'] )
		);
	}
	echo '</div>';
}

/**
 * Footer · รายการหน้า (เมนูหลักระดับบน → โครงเมนูมาตรฐาน)
 *
 * @return array[] { 0 => label, 1 => url }
 */
function eab_chrome_index_items() {
	$items     = array();
	$locations = function_exists( 'get_nav_menu_locations' ) ? get_nav_menu_locations() : array();
	if ( ! empty( $locations['primary'] ) ) {
		$menu_items = wp_get_nav_menu_items( (int) $locations['primary'] );
		if ( is_array( $menu_items ) ) {
			foreach ( $menu_items as $menu_item ) {
				if ( (int) $menu_item->menu_item_parent > 0 || ! eab_chrome_url_ok( $menu_item->url ) ) {
					continue;
				}
				$items[] = array( wp_strip_all_tags( (string) $menu_item->title ), (string) $menu_item->url );
			}
		}
	}
	if ( empty( $items ) ) {
		foreach ( eab_chrome_nav_tree() as $node ) {
			$items[] = array( $node['label'], $node['url'] );
		}
	}
	return $items;
}

/**
 * Footer · คู่มือ (กลุ่ม guide ใน eab_site_pages) เฉพาะหน้าที่เผยแพร่แล้ว
 */
function eab_chrome_guide_items() {
	$out = array();
	if ( ! function_exists( 'eab_guide_links' ) ) {
		return $out;
	}
	foreach ( eab_guide_links( array( 'guide' ) ) as $slug => $link ) {
		$url = eab_chrome_page_url( $slug );
		if ( '' !== $url ) {
			$out[] = array( $link['label'], $url );
		}
	}
	return $out;
}

/**
 * ช่องทาง (LINE OA, OpenChat, โซเชียล, อีเมล) เฉพาะที่กรอกแล้ว · รายการช่องทางในแผงติดต่อ
 */
function eab_chrome_channels() {
	$out = array();
	if ( eab_has_line_url() ) {
		$out[] = array(
			'icon'  => 'line',
			'url'   => trim( (string) eab_mod( 'line_url' ) ),
			'label' => (string) eab_mod( 'footer_line_text' ),
			'pos'   => 'footer-channels',
			'line'  => true,
		);
	}
	$openchat = trim( (string) eab_mod( 'line_openchat_url' ) );
	if ( eab_chrome_url_ok( $openchat ) ) {
		$out[] = array(
			'icon'  => 'users',
			'url'   => $openchat,
			'label' => (string) eab_mod( 'line_openchat_text' ),
			'pos'   => 'footer-channels-openchat',
		);
	}
	return array_merge( $out, eab_chrome_socials() );
}

/**
 * อ่านรายการ "slug|ชื่อลิงก์" จาก setting
 *
 * @return array slug => label (ไม่ตรวจสถานะการเผยแพร่)
 */
function eab_chrome_slug_lines( $key ) {
	$out = array();
	foreach ( eab_lines( eab_mod( $key ) ) as $line ) {
		if ( false === strpos( $line, '|' ) ) {
			continue;
		}
		list( $slug, $label ) = array_map( 'trim', explode( '|', $line, 2 ) );
		$slug = sanitize_title( $slug );
		if ( '' !== $slug && '' !== $label ) {
			$out[ $slug ] = $label;
		}
	}
	return $out;
}

/**
 * แถวลิขสิทธิ์ · ลิงก์นโยบาย (เฉพาะหน้าที่เผยแพร่แล้ว)
 *
 * @return array[] { 0 => label, 1 => url }
 */
function eab_chrome_legal_items() {
	$out = array();
	foreach ( eab_chrome_slug_lines( 'footer_legal_items' ) as $slug => $label ) {
		$url = eab_chrome_page_url( $slug );
		if ( '' !== $url ) {
			$out[] = array( $label, $url );
		}
	}
	return $out;
}

/**
 * Footer · เอกสาร (เฉพาะหน้าที่เผยแพร่แล้ว ไม่ซ้ำกับแถวลิขสิทธิ์) + ประกาศความเสี่ยง
 */
function eab_chrome_doc_items() {
	$out   = array();
	$legal = eab_chrome_slug_lines( 'footer_legal_items' );
	foreach ( eab_chrome_slug_lines( 'footer_docs_items' ) as $slug => $label ) {
		if ( 'risk-disclosure' === $slug || isset( $legal[ $slug ] ) ) {
			continue;
		}
		$url = eab_chrome_page_url( $slug );
		if ( '' !== $url ) {
			$out[] = array( $label, $url );
		}
	}
	/* ประกาศความเสี่ยง: แสดงทุกครั้งที่หน้าเผยแพร่แล้ว (ข้อความเตือนฉบับเต็มอยู่ใน footer เสมอ จึงไม่ลิงก์ไปหน้าที่ยังไม่มี) */
	$risk_label = trim( (string) eab_mod( 'footer_risk_link' ) );
	$risk_url   = eab_chrome_page_url( 'risk-disclosure' );
	if ( '' !== $risk_label && '' !== $risk_url && ! isset( $legal['risk-disclosure'] ) ) {
		$out[] = array( $risk_label, $risk_url );
	}
	return $out;
}

/**
 * แถบล่างมือถือ · ช่องลิงก์ 1 ถึง 4 + ปุ่มทองติดต่อท้ายแถบ · ช่อง active คิดฝั่ง PHP (ปิด JS ก็ถูก)
 *
 * @return array { items[], active (int, -1 = ไม่มี) }
 */
function eab_chrome_dock() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}
	$slots  = array(
		array(
			'label' => eab_mod( 'mobile_nav_home_label' ),
			'url'   => eab_link_url( eab_mod( 'mobile_nav_home_url' ) ),
			'icon'  => 'home',
		),
		array(
			'label' => '' !== trim( (string) eab_mod( 'dashboard_url' ) ) ? eab_mod( 'mobile_nav_dash_label' ) : '',
			'url'   => eab_link_url( eab_mod( 'dashboard_url' ) ),
			'icon'  => 'gauge',
		),
		array(
			'label' => eab_mod( 'mobile_nav_price_label' ),
			'url'   => eab_link_url( eab_mod( 'mobile_nav_price_url' ) ),
			'icon'  => 'tag',
		),
		array(
			'label' => eab_mod( 'mobile_nav_install_label' ),
			'url'   => eab_link_url( eab_mod( 'mobile_nav_install_url' ) ),
			'icon'  => 'book',
		),
	);
	$target = eab_contact_target();
	if ( '' !== $target['url'] ) {
		$slots[] = array(
			'label'  => eab_mod( $target['is_line'] ? 'mobile_nav_line_label' : 'mobile_nav_contact_label' ),
			'url'    => $target['url'],
			'icon'   => $target['is_line'] ? 'line' : 'chat',
			'action' => true,
			'line'   => $target['is_line'],
		);
	}

	$items = array();
	foreach ( $slots as $slot ) {
		if ( '' === trim( (string) $slot['label'] ) || ! eab_chrome_url_ok( $slot['url'] ) ) {
			continue;
		}
		$items[] = $slot;
	}

	/* หน้าปัจจุบัน: ใช้ $wp->request (ผ่าน routing ของ WP แล้ว ไม่มี query string) */
	$request = ( isset( $GLOBALS['wp'] ) && is_object( $GLOBALS['wp'] ) && isset( $GLOBALS['wp']->request ) ) ? (string) $GLOBALS['wp']->request : '';
	$home    = '/' . trim( (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH ), '/' );
	$here    = '/' . trim( (string) wp_parse_url( home_url( '/' . ltrim( $request, '/' ) ), PHP_URL_PATH ), '/' );
	$host    = wp_parse_url( home_url( '/' ), PHP_URL_HOST );
	$active  = -1;
	$score   = -1;

	foreach ( $items as $idx => $item ) {
		if ( ! empty( $item['action'] ) || 0 === strpos( $item['url'], '#' ) ) {
			continue;
		}
		$link_host = wp_parse_url( $item['url'], PHP_URL_HOST );
		if ( $link_host && $link_host !== $host ) {
			continue;
		}
		$path = '/' . trim( (string) wp_parse_url( $item['url'], PHP_URL_PATH ), '/' );
		$hit  = $path === $home ? $here === $home : ( $here === $path || 0 === strpos( $here, $path . '/' ) );
		if ( $hit && strlen( $path ) > $score ) {
			$active = (int) $idx;
			$score  = strlen( $path );
		}
	}

	$cache = array(
		'items'  => $items,
		'active' => $active,
	);
	return $cache;
}

/* ==============================================================
 * แผงติดต่อ "Let's talk" (ต้นแบบ .contact ใน dev/mockup)
 * ซ้าย: ป้ายเล็ก · H2 · คำอธิบาย · รายการช่องทาง (กระเบื้องไอคอน)
 * ขวา: การ์ด · ปุ่มทอง LINE · ปุ่มกรมท่า OpenChat · QR · สิ่งที่ทีมงานจะถาม
 * พิมพ์ได้ครั้งเดียวต่อหน้า ($GLOBALS['eab_chrome_cta_done'])
 * ============================================================== */

/**
 * ปุ่มติดต่อหลักแบบมีวงกลมไอคอนท้ายปุ่ม · ไม่มีปลายทาง = ไม่พิมพ์ (แอดมินเห็นคำแนะนำ)
 */
function eab_chrome_contact_button( $class, $pos ) {
	$target = eab_contact_target();
	if ( '' === $target['url'] ) {
		if ( current_user_can( 'edit_theme_options' ) ) {
			echo '<p class="admin-hint">ปุ่มติดต่อถูกซ่อน: ใส่ลิงก์ LINE ที่ ปรับแต่ง → 1) ช่องทางติดต่อ (ข้อความนี้เห็นเฉพาะแอดมิน)</p>';
		}
		return;
	}
	$text = (string) eab_mod( $target['is_line'] ? 'footer_line_text' : 'contact_fallback_text' );
	printf(
		'<a class="%1$s" href="%2$s"%3$s data-line-pos="%4$s"><span>%5$s</span><span class="btn-ic" aria-hidden="true">%6$s</span></a>',
		esc_attr( $class . ( $target['is_line'] ? ' is-line' : '' ) ),
		esc_url( $target['url'] ),
		$target['is_line'] ? ' target="_blank" rel="noopener"' : '',
		esc_attr( $pos ),
		esc_html( $text ),
		eab_icon( $target['is_line'] ? 'line' : 'chat' ) // phpcs:ignore WordPress.Security.EscapeOutput
	);
}

/**
 * แผงติดต่อ
 *
 * @param array $args { kicker, title, sub, pos }
 */
function eab_chrome_contact_panel( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'kicker' => '',
			'title'  => '',
			'sub'    => '',
			'pos'    => 'page-cta',
		)
	);
	if ( ! empty( $GLOBALS['eab_chrome_cta_done'] ) ) {
		return;
	}
	$GLOBALS['eab_chrome_cta_done'] = true;

	$target   = eab_contact_target();
	$is_admin = current_user_can( 'edit_theme_options' );
	if ( '' === $target['url'] && ! $is_admin ) {
		return;
	}

	$pos        = sanitize_key( (string) $args['pos'] );
	$openchat   = trim( (string) eab_mod( 'line_openchat_url' ) );
	$openchat   = eab_chrome_url_ok( $openchat ) ? $openchat : '';
	$qr         = $target['is_line'] ? eab_theme_asset_url( eab_mod( 'line_qr_image' ) ) : '';
	$channels   = eab_chrome_channels();
	$hours      = eab_lines( eab_mod( 'footer_hours_text' ) );
	$prep       = eab_lines( eab_mod( 'footer_prep_items' ) );
	$card_title = trim( (string) eab_mod( 'chrome_card_title' ) );
	$card_text  = trim( (string) eab_mod( 'chrome_card_text' ) );
	$tiles      = array(
		'line'      => 'line',
		'users'     => 'openchat',
		'facebook'  => 'facebook',
		'instagram' => 'blue',
		'tiktok'    => 'blue',
		'youtube'   => 'blue',
		'mail'      => 'mail',
	);
	?>
	<section class="chrome-cta" id="contact" aria-labelledby="chrome-cta-title">
		<div class="ctc-panel glass-panel">
			<span class="orb ctc-orb" aria-hidden="true"></span>

			<div class="ctc-copy">
				<?php eab_chrome_kicker( $args['kicker'] ); ?>
				<h2 class="ctc-title" id="chrome-cta-title"><?php echo eab_text( $args['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside eab_text ?></h2>
				<?php if ( '' !== trim( (string) $args['sub'] ) ) : ?>
					<p class="ctc-lead"><?php echo eab_text( $args['sub'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside eab_text ?></p>
				<?php endif; ?>

				<?php if ( $channels || $hours ) : ?>
					<ul class="ctc-list">
						<?php foreach ( $channels as $eab_channel ) : ?>
							<?php $eab_tone = isset( $tiles[ $eab_channel['icon'] ] ) ? $tiles[ $eab_channel['icon'] ] : 'blue'; ?>
							<li>
								<a href="<?php echo eab_chrome_social_href( $eab_channel ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?>"<?php echo isset( $eab_channel['mailto'] ) ? '' : ' target="_blank" rel="noopener"'; ?><?php echo ! empty( $eab_channel['pos'] ) ? ' data-line-pos="' . esc_attr( $eab_channel['pos'] ) . '"' : ''; ?>>
									<span class="tile tile--<?php echo esc_attr( $eab_tone ); ?>" aria-hidden="true"><?php echo eab_icon( $eab_channel['icon'], 'icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
									<span class="ctc-list-label"><?php echo esc_html( $eab_channel['label'] ); ?></span>
								</a>
							</li>
						<?php endforeach; ?>
						<?php if ( $hours ) : ?>
							<li class="ctc-hours">
								<span class="tile tile--navy" aria-hidden="true"><?php echo eab_icon( 'clock', 'icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
								<span class="ctc-hours-text">
									<strong><?php echo esc_html( eab_mod( 'footer_hours_title' ) ); ?></strong>
									<?php foreach ( $hours as $eab_line ) : ?>
										<span><?php echo esc_html( $eab_line ); ?></span>
									<?php endforeach; ?>
								</span>
							</li>
						<?php endif; ?>
					</ul>
				<?php endif; ?>
			</div>

			<div class="ctc-card">
				<?php if ( '' !== $card_title ) : ?>
					<h3 class="ctc-card-title"><?php echo esc_html( $card_title ); ?></h3>
				<?php endif; ?>
				<?php if ( '' !== $card_text ) : ?>
					<p class="ctc-card-text"><?php echo esc_html( $card_text ); ?></p>
				<?php endif; ?>

				<div class="ctc-actions">
					<?php eab_chrome_contact_button( 'btn btn-fire chrome-btn ctc-btn', $pos ); ?>
					<?php if ( '' !== $openchat ) : ?>
						<a class="btn chrome-btn ctc-btn ctc-btn--openchat" href="<?php echo esc_url( $openchat ); ?>" target="_blank" rel="noopener" data-line-pos="<?php echo esc_attr( $pos . '-openchat' ); ?>"><span><?php echo esc_html( eab_mod( 'line_openchat_text' ) ); ?></span><span class="btn-ic" aria-hidden="true"><?php echo eab_icon( 'users' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span></a>
					<?php endif; ?>
				</div>

				<?php if ( '' !== $qr ) : ?>
					<div class="ctc-qr">
						<img src="<?php echo esc_url( $qr ); ?>" alt="<?php echo esc_attr( eab_mod( 'footer_line_qr_alt' ) ); ?>" width="600" height="600" loading="lazy" decoding="async">
						<div class="ctc-qr-text">
							<p class="ctc-qr-title"><?php echo eab_icon( 'qr', 'icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo esc_html( eab_mod( 'footer_qr_toggle_text' ) ); ?></span></p>
							<?php if ( '' !== trim( (string) eab_mod( 'footer_qr_text' ) ) ) : ?>
								<p class="ctc-qr-sub"><?php echo esc_html( eab_mod( 'footer_qr_text' ) ); ?></p>
							<?php endif; ?>
						</div>
					</div>
				<?php elseif ( $target['is_line'] && $is_admin ) : ?>
					<p class="admin-hint">ยังไม่มีรูป QR · อัปโหลดที่ ปรับแต่ง → 1) ช่องทางติดต่อ → รูป QR Code LINE OA (ข้อความนี้เห็นเฉพาะแอดมิน)</p>
				<?php endif; ?>

				<?php if ( $prep ) : ?>
					<div class="ctc-ask">
						<?php if ( '' !== trim( (string) eab_mod( 'footer_prep_title' ) ) ) : ?>
							<p class="ctc-ask-title"><?php echo esc_html( eab_mod( 'footer_prep_title' ) ); ?></p>
						<?php endif; ?>
						<?php if ( '' !== trim( (string) eab_mod( 'footer_prep_text' ) ) ) : ?>
							<p class="ctc-ask-text"><?php echo esc_html( eab_mod( 'footer_prep_text' ) ); ?></p>
						<?php endif; ?>
						<ul class="ctc-ask-list">
							<?php foreach ( $prep as $eab_item ) : ?>
								<li><?php echo eab_icon( 'check', 'icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo esc_html( $eab_item ); ?></span></li>
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
 * แผงติดต่อท้ายหน้าย่อย · แทน eab_line_cta() เดิม (add_action 'eab_line_cta')
 */
function eab_chrome_line_cta( $title = '', $sub = '' ) {
	eab_chrome_contact_panel(
		array(
			'kicker' => eab_mod( 'chrome_cta_label' ),
			'title'  => '' !== trim( (string) $title ) ? $title : eab_mod( 'contact_title' ),
			'sub'    => '' !== trim( (string) $sub ) ? $sub : eab_mod( 'contact_text' ),
			'pos'    => 'page-cta',
		)
	);
}
add_action( 'eab_line_cta', 'eab_chrome_line_cta', 10, 2 );

/**
 * แผงติดต่อในส่วนท้าย สำหรับหน้าที่ยังไม่มีแผงติดต่อ (เช่น หน้าแรก บทความ 404) · ปิดได้ด้วยฟิลเตอร์ eab_chrome_footer_contact
 */
function eab_chrome_footer_contact() {
	if ( ! empty( $GLOBALS['eab_chrome_cta_done'] ) || ! apply_filters( 'eab_chrome_footer_contact', true ) ) {
		return;
	}
	$title = trim( (string) eab_mod( 'footer_headline' ) );
	$sub   = trim( (string) eab_mod( 'footer_sub' ) );
	eab_chrome_contact_panel(
		array(
			'kicker' => eab_mod( 'footer_console_label' ),
			'title'  => '' !== $title ? $title : eab_mod( 'contact_title' ),
			'sub'    => '' !== $sub ? $sub : eab_mod( 'contact_text' ),
			'pos'    => 'footer',
		)
	);
}
