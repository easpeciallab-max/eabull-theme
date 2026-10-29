<?php
/**
 * EA BULL · โมดูลหน้าแรก (front-page.php)
 *
 * โครงหน้าแบบ Glass Sky (docs/design.md · ต้นแบบ dev/mockup/index.html) · แต่ละส่วนเป็นแผงกระจก ห่างกัน 14px
 * Hero → About EA BULL → ทำอะไรได้บ้าง (4 การ์ด) → อุปกรณ์ที่รองรับ → ข้อมูลก่อนตัดสินใจ (3 การ์ด)
 * → เริ่มใช้งาน 6 ขั้น → แพ็กเกจ (โหมดสอบถาม) → FAQ → คำเตือนความเสี่ยง
 * แถบติดต่อท้ายหน้า (LINE / OpenChat) อยู่ในโมดูล chrome ไม่ซ้ำบนหน้าแรก
 *
 * - ค่าเริ่มต้นของหน้าแรก: eab_home_defaults() (ฟิลเตอร์ eab_defaults)
 * - หมวดใน Customizer เรียงตามลำดับบนหน้า: eab_home_customizer_sections() (ฟิลเตอร์ eab_customizer_sections)
 * - ตัวช่วยที่ front-page.php ใช้: eab_home_head(), eab_home_btn(), eab_home_icon(), eab_home_pairs(),
 *   eab_home_link(), eab_home_tone(), eab_home_contact_link() ฯลฯ
 * - ส่วนเดิมที่ถอดออกจากหน้าแรก (จุดที่แผนมักหลุด, จุดเด่น 5-6, แกลเลอรี, ผลทดสอบ, รีวิว, การ์ดนำทาง ฯลฯ)
 *   ลบค่าเริ่มต้นออกแล้ว · ช่องเดิมใน customizer.php ที่ไม่มีค่าเริ่มต้นจะไม่ถูกสร้าง ช่องเดิมที่ยังมีค่าไปอยู่หมวด "99)"
 *
 * กฎ: ไม่มีตัวเลขผลเทรด ไม่มีคำรับประกันกำไร ไม่ลดทอนคำเตือนความเสี่ยง · ข้อความทุกคำมาจาก setting
 *
 * @package eabull
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ==============================================================
 * ค่าเริ่มต้น (ข้อความไทยของ EA BULL เอง · ไม่คัดลอกจากแบรนด์อื่น)
 * ============================================================== */

add_filter( 'eab_defaults', 'eab_home_defaults' );

function eab_home_defaults( $d ) {
	return array_merge(
		$d,
		array(

			/* ---------- Hero ---------- */
			'hero_badge'            => 'Trade Smarter · Fly Higher',
			'hero_subtitle'         => 'ผู้ช่วยเทรดอัตโนมัติ',
			'hero_subtitle_em'      => 'สำหรับ MT5',
			'hero_desc'             => 'EA BULL คือ EA บน MetaTrader 5 ที่ส่งคำสั่งซื้อขายทองคำ XAUUSD ให้เป็นไปตามแผนอย่างสม่ำเสมอ เลือกระดับความเสี่ยงได้สามโหมดจากแดชบอร์ด เปิดแอป MT5 บนมือถือเมื่อไรก็เห็นสถานะบัญชี และมีทีมงานคนไทยคอยตอบทาง LINE',
			'hero_btn1_text'        => 'ดูแพ็กเกจ',
			'hero_btn1_url'         => '/pricing/',
			'hero_btn2_text'        => 'วิธีติดตั้ง',
			'hero_btn2_url'         => '/how-to-install/',
			'hero_note'             => 'Forex และ CFD เสี่ยงสูง เงินในบัญชีอาจลดลงบางส่วนหรือจนหมด สิ่งที่เกิดขึ้นในอดีตไม่ใช่ตัวบอกอนาคต และ EA BULL ไม่รับประกันกำไร',
			'home_hero_works_label' => 'ใช้งานร่วมกับ',
			'home_hero_works_items' => "MetaTrader 5\nWindows\niPhone\nAndroid\nVPS",
			'home_hero_img_alt'     => 'EA BULL บนหน้าจอ MetaTrader 5 (ภาพประกอบ)',
			'home_hero_chips'       => "ทำงาน 24 ชม.|เมื่อรันบน VPS\nคุมความเสี่ยงเอง|เลือกโหมด Lite, Advance หรือ Full บนแดชบอร์ด",

			/* Hero · แผงควบคุมจำลอง (แสดงเมื่อไม่ได้ใส่ภาพ Hero) · กราฟเป็นรูปทรงตกแต่ง ไม่มีราคาหรือผลเทรด */
			'hero_panel_status'     => 'Connected to MT5',
			'hero_panel_fields'     => "Auto Trading\nRisk Management\nStrategy Settings",

			/* ---------- About EA BULL ---------- */
			'home_what_kicker'      => 'About EA BULL',
			'about_title'           => 'เทรดอย่างมีแบบแผน ไปได้ไกลกว่าเดิม',
			'about_text'            => "EA BULL เป็น Expert Advisor สำหรับ MetaTrader 5 วางไว้บนกราฟแล้วระบบจะส่งคำสั่งตามเงื่อนไขของตัวโปรแกรม ระดับความเสี่ยงคุณเลือกเองจากโหมด Lite, Advance หรือ Full และปรับทุนคิดไม้ได้บนแดชบอร์ด\n\nเรายึดสามหลัก วางแผนก่อนเปิดใช้ ติดตามบัญชีเป็นประจำ และคุมความเสี่ยงให้อยู่ในกรอบ EA BULL ช่วยให้คุณทำตามแผนได้สม่ำเสมอ แต่ไม่ได้รับรองว่าบัญชีจะมีกำไร",
			'home_about_stats'      => "MT5|รองรับ MetaTrader 5 เท่านั้น\n6 ขั้น|จากเปิดบัญชีถึงรันบน VPS\nLINE|ทีมงานคนไทยตอบคำถาม",
			'home_about_btn_text'   => 'รู้จัก EA BULL เพิ่มเติม',
			'home_about_btn_url'    => '/about/',

			/* ---------- ทำอะไรได้บ้าง (4 การ์ด) ---------- */
			'home_features_kicker'  => 'What it does',
			'features_title'        => 'EA BULL ช่วยอะไรคุณได้บ้าง',
			'features_subtitle'     => 'ระบบลงมือตามแผน ส่วนเรื่องเงินทุนและความเสี่ยง คุณยังเป็นคนกำหนด',
			'feat1_title'           => 'เทรดอัตโนมัติ',
			'feat1_desc'            => 'เปิดออเดอร์ทองคำ XAUUSD ตามเงื่อนไขของระบบ และทำงานได้ตลอดเมื่อรันบน VPS',
			'home_feat1_url'        => '/backtest/',
			'feat2_title'           => 'ตั้งเป้ากำไรรายวัน',
			'feat2_desc'            => 'ตั้งเป้าเป็นเปอร์เซ็นต์ แดชบอร์ดบอกเป็นเงินให้เห็น ถึงเป้าแล้ว EA หยุดเปิดออเดอร์รอบใหม่จนหมดวัน',
			'home_feat2_url'        => '/how-to-install/',
			'feat3_title'           => 'ติดตามจากมือถือ',
			'feat3_desc'            => 'เปิดแอป MT5 ดูออเดอร์และยอดบัญชีได้ทุกที่',
			'home_feat3_url'        => '/mt5-login/',
			'feat4_title'           => 'บริหารความเสี่ยง',
			'feat4_desc'            => 'เลือกโหมด ตั้งทุนคิดไม้กับเงินกันชน หลบข่าวแดงของ USD และกดหยุดเปิดออเดอร์ใหม่ได้จากแดชบอร์ด',
			'home_feat4_url'        => '/tools/',

			/* ---------- อุปกรณ์ที่รองรับ ---------- */
			'home_show_devices'     => true,
			'home_devices_kicker'   => 'Works on',
			'home_devices_title'    => 'ใช้ได้กับอุปกรณ์ที่คุณมีอยู่แล้ว',
			'home_devices_items'    => "Windows|/mt5-login/\niPhone · iPad|/mt5-login/\nAndroid|/mt5-login/\nmacOS|/mt5-login/\nWindows VPS|/vps-windows/\nMetaTrader 5|/how-to-install/",
			'install_home_note'     => 'แอป MT5 บนมือถือมีไว้ติดตามออเดอร์และยอดบัญชี ตัว EA ต้องรันใน MT5 บนคอมที่เปิดไว้ หรือบน VPS',
			/* ---------- ข้อมูลก่อนตัดสินใจ (Backtest · Forward Test · ติดตั้ง) ---------- */
			'tests_kicker'          => 'Before you decide',
			'tests_title'           => 'ข้อมูลที่ควรรู้ก่อนตัดสินใจ',
			'tests_subtitle'        => 'สามเรื่องนี้ช่วยให้เห็นภาพก่อนเริ่มใช้งาน',
			'home_info_more_text'   => 'บทความทั้งหมด',
			'home_info_more_url'    => '/articles/',
			'tests_bt_title'        => 'Backtest ใน Strategy Tester',
			'tests_bt_text'         => 'ลองรัน EA กับราคาย้อนหลังบน MT5 แล้วอ่านรายงานอย่างระวัง',
			'home_tests_bt_btn'     => 'อ่านคู่มือ Backtest',
			'tests_bt_img'          => '',
			'tests_bt_img_mobile'   => '',
			'tests_bt_img_alt'      => 'ภาพประกอบหน้าต่าง Strategy Tester ใน MT5',
			'tests_bt_img_caption'  => 'ภาพประกอบเท่านั้น ไม่ได้แสดงผลทดสอบของ EA BULL',
			'tests_bt_img_note'     => 'ไม่ใส่ = ใช้ภาพวาดของธีม · ภาพที่เหมาะ: หน้าตั้งค่า Strategy Tester ก่อนกดเริ่ม ไม่มีตัวเลขผล · ขนาดประมาณ 1280x720 px',
			'tests_fw_title'        => 'Forward Test ตลาดจริง',
			'tests_fw_text'         => 'ให้ EA ทำงานบนบัญชีเดโมไปพร้อมตลาดจริง เพื่อเห็น Spread และการส่งคำสั่งจริง',
			'home_tests_fw_btn'     => 'อ่านคู่มือ Forward Test',
			'tests_fw_img'          => '',
			'tests_fw_img_mobile'   => '',
			'tests_fw_img_alt'      => 'ภาพประกอบบัญชีทดสอบใน MT5',
			'tests_fw_img_caption'  => 'ภาพประกอบเท่านั้น ไม่ได้แสดงผลการเทรดของ EA BULL',
			'tests_fw_img_note'     => 'ไม่ใส่ = ใช้ภาพวาดของธีม · ภาพที่เหมาะ: หน้าบัญชีทดสอบใน MT5 ที่ปิดเลขบัญชีและยอดเงินแล้ว · ขนาดประมาณ 1280x720 px',
			'install_home_title'    => 'ติดตั้ง EA ใน MT5',
			'install_home_sub'      => 'ย้ายไฟล์เข้าโฟลเดอร์ Experts เปิดปุ่ม Algo Trading แล้วดูว่า EA เริ่มทำงาน',
			'home_install_btn'      => 'อ่านคู่มือติดตั้ง',
			'home_install_btn_url'  => '/how-to-install/',
			'ih_step1_img'          => 'assets/img/install/eabull-dashboard-home-v2.webp',
			'ih_step1_img_mobile'   => '',
			'ih_step1_img_alt'      => 'แดชบอร์ด EA BULL บนกราฟ XAUUSD ที่ขึ้น AutoTrading เปิดอยู่ และการ์ดล็อกอินสำเร็จ',
			'ih_step1_img_caption'  => 'แดชบอร์ด EA BULL จริง · เลขบัญชีในภาพเป็นตัวอย่าง',
			'ih_step1_img_note'     => 'ไม่ใส่ = ใช้ภาพวาดของธีม · ภาพที่เหมาะ: โฟลเดอร์ MQL5 → Experts หรือกราฟที่มี EA ติดอยู่ ไม่มีเลขบัญชี · ขนาดประมาณ 1280x720 px',
			'home_shots_note'       => 'ภาพบนการ์ดเป็นภาพประกอบ ไม่ได้แสดงผลการเทรดจริง',
			'tests_note'            => 'ผล Backtest ที่เผยแพร่มีเงื่อนไขการทดสอบและข้อจำกัดบอกไว้ครบในหน้า Backtest ตัวเลขในอดีตไม่ได้รับประกันผลในอนาคต',
			'tests_pending_note'    => 'ตอนนี้ยังไม่มีผลทดสอบของ EA BULL ที่เผยแพร่',

			/* ---------- เริ่มใช้งาน 6 ขั้น ---------- */
			'steps_kicker'          => 'How to start',
			'steps_title'           => 'เริ่มใช้งานใน 6 ขั้นตอน',
			'steps_subtitle'        => '',
			'home_steps_link_text'  => 'ดูคู่มือ',
			'home_steps_line_text'  => 'ทัก LINE',
			'step1_title'           => 'เปิดบัญชี MT5',
			'step1_desc'            => 'สมัครกับโบรกเกอร์และยืนยันตัวตนให้เรียบร้อย',
			'step1_url'             => '/open-mt5-account/',
			'step2_title'           => 'ติดตั้ง MT5 และล็อกอิน',
			'step2_desc'            => 'เลือกแอปให้ตรงกับอุปกรณ์ แล้วเข้าบัญชีเทรด',
			'step2_url'             => '/mt5-login/',
			'step3_title'           => 'ฝากเงินเข้าบัญชี',
			'step3_desc'            => 'ทำรายการผ่านหน้าสมาชิกของโบรกเกอร์',
			'step3_url'             => '/how-to-install/',
			'step4_title'           => 'รับไฟล์และเปิดสิทธิ์',
			'step4_desc'            => 'ดาวน์โหลดไฟล์ แล้วส่งเลขบัญชีขอสิทธิ์ทาง LINE',
			'step4_url'             => '/go/',
			'step5_title'           => 'ติดตั้ง EA บน MT5',
			'step5_desc'            => 'วางไฟล์ใน Experts เปิด DLL และ Algo Trading',
			'step5_url'             => '/how-to-install/',
			'step6_title'           => 'รันบน VPS',
			'step6_desc'            => 'EA ทำงานได้ทั้งวันแม้คอมที่บ้านปิดอยู่',
			'step6_url'             => '/vps-windows/',

			/* ---------- แพ็กเกจ (โหมดสอบถาม) ---------- */
			'home_pricing_kicker'       => 'Packages',
			'pricing_home_title'        => 'แพ็กเกจ EA BULL',
			'pricing_home_sub'          => 'เริ่มปรึกษาฟรีทาง LINE แล้วเลือกแพ็กเกจที่ตรงกับวิธีใช้งานของคุณ ทีมงานยืนยันยอดในแชตก่อนชำระเงินทุกครั้ง',
			'home_pricing_rec_label'    => 'แนะนำ',
			'home_pricing_contact_text' => 'สอบถามราคาทาง LINE',
			'home_pricing_margin_note'  => 'ไม่ว่าเลือกแพ็กเกจไหน ความเสี่ยงของการเทรดยังเท่าเดิม บัญชีขาดทุนได้เสมอ ศึกษาหน้าคำเตือนความเสี่ยงให้ครบก่อนชำระเงิน',
			'home_pricing_more_text'    => 'ดูหน้าแพ็กเกจ',

			/* ---------- FAQ ---------- */
			'home_faq_kicker' => 'FAQ',
			'faq_title'       => 'สิ่งที่คนมักถามก่อนเริ่มใช้ EA BULL',
			'faq_subtitle'    => 'กรณีของคุณไม่อยู่ในนี้ พิมพ์ถามทีมงานทาง LINE ได้เลย',
			'faq1_q'          => 'EA BULL ใช้กับ MT4 ได้ไหม?',
			'faq1_a'          => 'ไม่ได้ EA BULL ทำมาเพื่อ MetaTrader 5 อย่างเดียว ไฟล์จึงใช้กับ MT4 ไม่ได้ ถ้าตอนนี้คุณเทรดบน MT4 ให้ขอเปิดบัญชีประเภท MT5 กับโบรกเกอร์ก่อน แล้วลงโปรแกรม MT5 เพื่อเข้าบัญชีนั้น ทำตามคู่มือเปิดบัญชีและคู่มือล็อกอินบนเว็บนี้ได้ทีละขั้น',
			'faq2_q'          => 'ต้องเปิดคอมค้างไว้ทั้งวันไหม?',
			'faq2_a'          => 'EA จะทำงานเฉพาะช่วงที่ MT5 เปิดอยู่และออนไลน์ ถ้าคอมดับ EA ก็หยุดไปด้วย หากต้องการให้ทำงานต่อเนื่อง เราแนะนำ Windows VPS ซึ่งเปิดอยู่ตลอดโดยไม่พึ่งคอมที่บ้าน ส่วนบนมือถือ แอป MT5 รัน EA เองไม่ได้ แต่เปิดดูออเดอร์และยอดบัญชีได้ทุกเมื่อ',
			'faq3_q'          => 'VPS คืออะไร จำเป็นต้องมีไหม?',
			'faq3_a'          => 'VPS คือคอมพิวเตอร์ Windows ที่เช่าไว้บนอินเทอร์เน็ตและไม่เคยปิด คุณลง MT5 กับ EA BULL ไว้บนเครื่องนั้น แล้วเข้าไปดูผ่านแอป Remote Desktop ได้ทั้งจากคอมและมือถือ ไม่ได้บังคับ แต่เราแนะนำ เพราะไฟดับ เน็ตหลุด หรือคอมดับ จะไม่ทำให้ EA หยุดกลางทาง วิธีตั้งค่าอยู่ในคู่มือ VPS',
			'faq4_q'          => 'ควรมีเงินทุนเท่าไรถึงจะเริ่มได้?',
			'faq4_a'          => 'ทีมงานแนะนำบัญชีเซนต์ราว 1,000 USD (100,000 cent) ขึ้นไป หรือบัญชีมาตรฐานราว 100,000 USD ขึ้นไป ทุนน้อยกว่านี้ใช้ได้ แต่ขนาดออเดอร์ขั้นต่ำของโบรกเกอร์ (0.01 lot) ทำให้ความเสี่ยงต่อทุนสูงกว่าที่ออกแบบ และแดชบอร์ดจะเตือนเป็นแถว "ทุนน้อยกว่าที่ออกแบบ" เงินในบัญชีที่แนะนำยังขึ้นกับโหมดด้วย คือ Lite 1 เท่า Advance 2 เท่า และ Full 3 เท่าของทุนคิดไม้ หลักที่ควรยึดคือเอาเฉพาะเงินที่เสียไปแล้วไม่กระทบค่ากินอยู่และภาระจำเป็นมาเทรด และเริ่มกับบัญชีเดโมก่อน',
			'faq5_q'          => 'ใช้ EA BULL แล้วจะได้กำไรแน่นอนไหม?',
			'faq5_a'          => 'ไม่แน่นอน และเราไม่รับประกันกำไร Forex กับ CFD เป็นตลาดที่เสี่ยงสูง บัญชีติดลบได้ และตัวเลขในอดีตไม่ใช่คำมั่นของผลข้างหน้า EA BULL ทำงานตามเงื่อนไขกับค่าที่คุณตั้งไว้ ไม่ใช่คำแนะนำด้านการลงทุน และคุณเป็นคนตัดสินใจเรื่องเงินของตัวเอง ถ้ามีใครสัญญาว่าบอทเทรดทำเงินได้แน่ ๆ ให้ระวังให้มาก',
			'faq6_q'          => 'ต้องใช้โบรกเกอร์ไหน?',
			'faq6_a'          => 'EA BULL ใช้กับ MT5 จึงต้องมีบัญชี MT5 ที่อนุญาตให้รัน EA ทีมงานแนะนำ Zaurix ซึ่งเป็นโบรกเกอร์พาร์ตเนอร์ของเรา ขอแจ้งให้ทราบว่า EA BULL ได้รับค่าตอบแทนเมื่อมีคนเปิดบัญชีผ่านลิงก์ของเรา สมัครได้จากปุ่มในหน้าเปิดบัญชี MT5 ถ้าคุณใช้โบรกเกอร์อื่นอยู่แล้ว แจ้งทีมงานก่อนเพื่อสอบถามเรื่องการใช้งาน',
			'faq7_q'          => 'รับไฟล์ EA BULL ได้อย่างไร?',
			'faq7_a'          => 'ดาวน์โหลดไฟล์ EA กับคู่มือได้จากหน้าลิงก์รวม แล้วส่งเลขบัญชี MT5 กับชื่อเซิร์ฟเวอร์ให้ทีมงานทาง LINE เพื่อเปิดสิทธิ์ใช้งานตามแพ็กเกจ เมื่อเปิดสิทธิ์แล้ว EA เริ่มทำงานเองภายใน 5 นาที ขั้นตอนติดตั้งอยู่ในคู่มือ ติดขั้นไหนส่งภาพหน้าจอมาถามได้',
			'faq8_q'          => 'หยุด EA ได้ตลอดเวลาไหม?',
			'faq8_a'          => 'ได้ วิธีที่แนะนำคือกดปุ่มใหญ่ "หยุดเปิดไม้ใหม่" บนแดชบอร์ด EA จะไม่เปิดรอบใหม่ แต่ยังดูแลออเดอร์ที่ถืออยู่จนปิดตามปกติ ถ้ากดปุ่ม Algo Trading ใน MT5 ให้ปิด หรือถอด EA ออกจากกราฟ EA จะหยุดทำงานทั้งหมด ออเดอร์ที่ค้างอยู่ยังอยู่ในบัญชีแต่ไม่มีใครดูแล ให้เปิดแท็บ Trade แล้วตัดสินใจเองว่าจะถือต่อหรือปิด ถ้าไม่แน่ใจว่าควรหยุดแบบไหน ทักถามทีมงานก่อนได้',
			'faq9_q'          => 'ขอลองกับบัญชีเดโมก่อนได้หรือเปล่า?',
			'faq9_a'          => 'บัญชีเดโมช่วยให้เห็นการทำงานของระบบโดยไม่เสี่ยงเงินจริง EA BULL ใช้กับบัญชีเดโมได้เมื่อบัญชีนั้นได้รับสิทธิ์แล้ว ส่งเลขบัญชีเดโมให้ทีมงานได้แบบเดียวกับบัญชีจริง ระหว่างลองให้สังเกต Spread ความไวในการส่งคำสั่ง และขนาด Lot ว่าเข้ากับแผนของคุณหรือไม่ และจำไว้ว่าผลบนบัญชีเดโมอาจต่างจากบัญชีจริงได้',
			'faq10_q'         => 'ความเสี่ยงของการใช้ EA มีอะไรบ้าง?',
			'faq10_a'         => 'อย่างแรกคือความเสี่ยงของตัวระบบ EA BULL เป็นระบบทบไม้ที่เติมไม้แก้เมื่อราคาสวน และโหมดสำเร็จรูปไม่มีการตัดขาดทุนอัตโนมัติ ถ้าราคาวิ่งทางเดียวไกลกว่าที่ระบบรับได้ บัญชีเสียหายหนักได้ถึงเสียเงินทั้งหมด ที่เหลือแบ่งได้เป็นสองฝั่ง ฝั่งตลาด เช่น ราคาวิ่งแรงตอนมีข่าว Spread กว้างขึ้น หรือได้ราคาไม่ตรงที่สั่ง ฝั่งการใช้งาน เช่น ตั้ง Lot เกินกำลังของทุน เน็ตหลุดหรือ VPS มีปัญหา หรือเผลอเปิด EA ซ้ำหลายกราฟ ผลคือคุณอาจเสียเงินบางส่วนหรือทั้งหมด ก่อนเริ่มจึงควรอ่านหน้าคำเตือนความเสี่ยงให้ครบทุกข้อ',
			/* ---------- คำเตือนความเสี่ยง ---------- */
			'home_risk_label'     => 'คำเตือน',
			'risk_title'          => 'ก่อนเริ่ม โปรดเข้าใจความเสี่ยงเหล่านี้',
			'risk_text'           => 'Forex, CFD และสินทรัพย์ที่ซื้อขายด้วย Leverage เป็นการลงทุนที่เสี่ยงสูง ราคาขยับได้รวดเร็วจนเงินทุนของคุณลดลงบางส่วนหรือหมดทั้งก้อน WING NITRO เป็นระบบทบไม้ (มาร์ติงเกล) ที่ไม่มีจุดตัดขาดทุน EA BULL เป็นระบบทบไม้ที่เติมไม้แก้เมื่อราคาสวน และโหมดสำเร็จรูปไม่มีการตัดขาดทุนอัตโนมัติ WING TWIN เป็นระบบกริดสองทางที่ทบ lot ของไม้สวนทาง เพิ่มไม้กู้ขนาดใหญ่เมื่อตะกร้าค้าง และไม่มีการตัดขาดทุน ถ้าราคาวิ่งทางเดียวไกลกว่าที่ระบบรับได้ บัญชีเสียหายหนักได้ถึงเสียเงินทั้งหมด ผลทดสอบและผลงานในอดีตไม่ได้รับรองว่าอนาคตจะเป็นแบบเดียวกัน EA BULL เป็นโปรแกรมที่ทำงานตามเงื่อนไขและค่าตั้งของผู้ใช้ ไม่ได้ให้คำแนะนำการลงทุน และไม่รับประกันกำไรในกรณีใด ผู้ใช้เป็นผู้รับผิดชอบการตัดสินใจและการตั้งค่าของตนเอง ควรศึกษาให้เข้าใจ ทดลองก่อนใช้เงินจริง และใช้เฉพาะเงินที่รับการสูญเสียได้',
			'home_risk_more_text' => 'เปิดหน้าคำเตือนความเสี่ยงฉบับเต็ม',
		)
	);
}

/* ==============================================================
 * Customizer · หมวดของหน้าแรกเรียงตามลำดับบนหน้า
 * ============================================================== */

add_filter( 'eab_customizer_sections', 'eab_home_customizer_sections', 10, 2 );

function eab_home_customizer_sections( $sections, $d ) {
	$rule  = 'ห้ามใส่ตัวเลขผลเทรด เปอร์เซ็นต์กำไร หรือคำสัญญาว่าได้กำไร';
	$lines = 'บรรทัดละ 1 รายการ';
	$link  = 'slug ในเว็บ (เช่น /pricing/) หรือ URL เต็ม · ลิงก์ในเว็บแสดงเมื่อหน้านั้นเผยแพร่แล้วเท่านั้น';
	$kick  = 'ป้ายเล็กเหนือหัวข้อ (ภาษาอังกฤษจะแสดงตัวพิมพ์ใหญ่เว้นระยะ)';

	$home = array(
		'eab_home_boot'     => array(
			'title'       => '2) หน้าแรก · Hero',
			'description' => 'ส่วนบนสุดของหน้าแรก ซ้าย: ป้ายเล็ก H1 คำอธิบาย ปุ่มสองปุ่ม คำเตือน และแถว "ใช้งานร่วมกับ" · ขวา: แผงควบคุม EA จำลองในกรอบกระจก (หรือภาพ Hero) พร้อมการ์ดลอยสองใบ · ปุ่มทัก LINE อยู่ที่แถบเมนูและแถบติดต่อท้ายหน้า',
			'fields'      => array(
				'show_hero'             => array( 'แสดงส่วน Hero', 'checkbox' ),
				'hero_badge'            => array( $kick, 'text' ),
				'hero_title'            => array( 'ชื่อแบรนด์ (บรรทัดเล็กบนสุดใน H1 · เว้นว่าง = ซ่อน)', 'text' ),
				'hero_subtitle'         => array( 'หัวข้อหลัก H1 · บรรทัดแรก', 'text' ),
				'hero_subtitle_em'      => array( 'หัวข้อหลัก H1 · บรรทัดที่สอง (ไล่สีน้ำเงินถึงทอง)', 'text' ),
				'hero_desc'             => array( 'คำอธิบายสั้น', 'textarea', $rule ),
				'hero_btn1_text'        => array( 'ปุ่มหลัก (กรมท่า) · ข้อความ (เว้นว่าง = ซ่อน)', 'text' ),
				'hero_btn1_url'         => array( 'ปุ่มหลัก · ลิงก์', 'path', $link ),
				'hero_btn2_text'        => array( 'ปุ่มรอง (กระจก) · ข้อความ (เว้นว่าง = ซ่อน)', 'text' ),
				'hero_btn2_url'         => array( 'ปุ่มรอง · ลิงก์', 'path', $link ),
				'hero_note'             => array( 'ข้อความเตือนความเสี่ยงใต้ปุ่ม (ห้ามลบ)', 'textarea' ),
				'home_hero_works_label' => array( 'แถว "ใช้งานร่วมกับ" · หัวข้อเล็ก', 'text' ),
				'home_hero_works_items' => array( 'แถว "ใช้งานร่วมกับ" · รายการ (' . $lines . ')', 'textarea', 'แสดงเป็นตัวอักษรพร้อมไอคอน (ไม่ใช้โลโก้การค้า) · ไอคอนเลือกตามคำ: MetaTrader/MT5, Windows, iPhone/iPad, Android, macOS, VPS' ),
				'hero_image'            => array( 'ภาพ Hero (ไม่ใส่ = แสดงแผงควบคุม EA จำลอง)', 'image', 'แนะนำภาพสี่เหลี่ยมจัตุรัส 1000px ขึ้นไป · ถ้าภาพมีตัวเลข ต้องไม่อ่านแล้วเหมือนผลการเทรด' ),
				'home_hero_img_alt'     => array( 'คำอธิบายภาพ Hero (alt)', 'text' ),
				'hero_panel_title'      => array( 'แผงจำลอง · ชื่อ', 'text' ),
				'hero_panel_status'     => array( 'แผงจำลอง · สถานะใต้ชื่อ', 'text', 'หลีกเลี่ยงคำที่ทำให้เข้าใจว่ากำลังทำกำไร' ),
				'hero_panel_fields'     => array( 'แผงจำลอง · แถวเมนู (' . $lines . ' · แถวแรกมีสวิตช์เปิด)', 'textarea', 'ใส่ "ชื่อ|ค่า" เพื่อแสดงค่าแทนลูกศร · ' . $rule ),
				'hero_panel_caption'    => array( 'แผงจำลอง · คำบรรยายใต้ภาพ (จำเป็น)', 'text' ),
				'home_hero_chips'       => array( 'การ์ดลอยรอบภาพ (' . $lines . ': หัวข้อ|คำอธิบาย · สูงสุด 2 ใบ)', 'textarea', $rule ),
			),
		),

		'eab_home_what'     => array(
			'title'  => '2.1) หน้าแรก · About EA BULL',
			'fields' => array(
				'show_about'          => array( 'แสดงส่วนนี้', 'checkbox' ),
				'home_what_kicker'    => array( $kick, 'text' ),
				'about_title'         => array( 'หัวข้อ (H2)', 'text' ),
				'about_text'          => array( 'เนื้อหา (เว้นบรรทัดว่าง = ย่อหน้าใหม่)', 'textarea', $rule ),
				'home_about_stats'    => array( 'การ์ดข้อมูล (' . $lines . ': ค่าตัวหนา|คำอธิบาย · สูงสุด 3 ช่อง)', 'textarea', 'ข้อเท็จจริงที่ไม่ใช่ผลเทรด เช่น MT5 · 6 ขั้น · LINE · ' . $rule ),
				'home_about_btn_text' => array( 'ปุ่มกระจกใต้เนื้อหา · ข้อความ (เว้นว่าง = ซ่อน)', 'text' ),
				'home_about_btn_url'  => array( 'ปุ่มกระจกใต้เนื้อหา · ลิงก์', 'path', $link ),
			),
		),

		'eab_home_features' => array(
			'title'  => '2.2) หน้าแรก · ทำอะไรได้บ้าง (4 การ์ด)',
			'fields' => array(
				'show_features'        => array( 'แสดงส่วนนี้', 'checkbox' ),
				'home_features_kicker' => array( $kick, 'text' ),
				'features_title'       => array( 'หัวข้อ (H2)', 'text' ),
				'features_subtitle'    => array( 'คำอธิบายใต้หัวข้อ (เว้นว่าง = ซ่อน)', 'textarea' ),
				'feat1_title'          => array( 'การ์ด 1 (กระเบื้องฟ้า) · หัวข้อ', 'text' ),
				'feat1_desc'           => array( 'การ์ด 1 · รายละเอียด', 'textarea', $rule ),
				'home_feat1_url'       => array( 'การ์ด 1 · ลิงก์ (เว้นว่าง = การ์ดไม่มีลิงก์)', 'path', $link ),
				'feat2_title'          => array( 'การ์ด 2 (กระเบื้องทอง) · หัวข้อ', 'text' ),
				'feat2_desc'           => array( 'การ์ด 2 · รายละเอียด', 'textarea' ),
				'home_feat2_url'       => array( 'การ์ด 2 · ลิงก์', 'path' ),
				'feat3_title'          => array( 'การ์ด 3 (กระเบื้องน้ำเงิน) · หัวข้อ', 'text' ),
				'feat3_desc'           => array( 'การ์ด 3 · รายละเอียด', 'textarea' ),
				'home_feat3_url'       => array( 'การ์ด 3 · ลิงก์', 'path' ),
				'feat4_title'          => array( 'การ์ด 4 (กระเบื้องกรมท่า) · หัวข้อ', 'text' ),
				'feat4_desc'           => array( 'การ์ด 4 · รายละเอียด', 'textarea' ),
				'home_feat4_url'       => array( 'การ์ด 4 · ลิงก์', 'path' ),
			),
		),

		'eab_home_devices'  => array(
			'title'  => '2.3) หน้าแรก · อุปกรณ์ที่รองรับ',
			'fields' => array(
				'home_show_devices'   => array( 'แสดงส่วนนี้', 'checkbox' ),
				'home_devices_kicker' => array( $kick, 'text' ),
				'home_devices_title'  => array( 'หัวข้อ (H2)', 'text' ),
				'home_devices_items'  => array( 'รายการอุปกรณ์ (' . $lines . ': ชื่อ|ลิงก์คู่มือ)', 'textarea', 'ไอคอนเลือกตามคำ: Windows, iPhone/iPad, Android, macOS, VPS, MetaTrader/MT5 · ไม่ใส่ลิงก์ = ไม่เป็นลิงก์' ),
				'install_home_note'   => array( 'หมายเหตุใต้รายการ (เว้นว่าง = ซ่อน)', 'textarea' ),
			),
		),

		'eab_home_tests'    => array(
			'title'       => '2.4) หน้าแรก · ข้อมูลก่อนตัดสินใจ (3 การ์ด)',
			'description' => 'การ์ด Backtest · Forward Test · ติดตั้ง ลิงก์ไปหน้าคู่มือแต่ละหน้า (แสดงเป็นลิงก์เมื่อหน้านั้นเผยแพร่แล้ว) · ไม่ใส่รูป = ใช้ภาพวาดประกอบของธีม · รูปที่มีตัวเลขต้องมีคำว่าเป็นภาพประกอบ ไม่ใช่ผลการเทรดจริง · ไม่แสดงตัวเลขผลทดสอบ',
			'fields'      => array_merge(
				array(
					'show_tests'          => array( 'แสดงการ์ด Backtest และ Forward Test', 'checkbox' ),
					'tests_kicker'        => array( $kick, 'text' ),
					'tests_title'         => array( 'หัวข้อ (H2)', 'text' ),
					'tests_subtitle'      => array( 'คำอธิบายใต้หัวข้อ (เว้นว่าง = ซ่อน)', 'textarea' ),
					'home_info_more_text' => array( 'ปุ่มมุมขวาบน · ข้อความ (เว้นว่าง = ซ่อน)', 'text' ),
					'home_info_more_url'  => array( 'ปุ่มมุมขวาบน · ลิงก์', 'path', $link ),
					'tests_bt_title'      => array( 'การ์ด Backtest · หัวข้อ', 'text' ),
					'tests_bt_text'       => array( 'การ์ด Backtest · คำอธิบาย (การ์ดแสดง 4 บรรทัด)', 'textarea', $rule ),
					'home_tests_bt_btn'   => array( 'การ์ด Backtest · ข้อความลิงก์ไปหน้า /backtest/', 'text' ),
				),
				eab_media_fields( 'tests_bt', 'การ์ด Backtest' ),
				array(
					'tests_fw_title'    => array( 'การ์ด Forward Test · หัวข้อ', 'text' ),
					'tests_fw_text'     => array( 'การ์ด Forward Test · คำอธิบาย (การ์ดแสดง 4 บรรทัด)', 'textarea', $rule ),
					'home_tests_fw_btn' => array( 'การ์ด Forward Test · ข้อความลิงก์ไปหน้า /forward-test/', 'text' ),
				),
				eab_media_fields( 'tests_fw', 'การ์ด Forward Test' ),
				array(
					'show_install_home'    => array( 'แสดงการ์ดติดตั้ง', 'checkbox' ),
					'install_home_title'   => array( 'การ์ดติดตั้ง · หัวข้อ', 'text' ),
					'install_home_sub'     => array( 'การ์ดติดตั้ง · คำอธิบาย', 'textarea' ),
					'home_install_btn'     => array( 'การ์ดติดตั้ง · ข้อความลิงก์', 'text' ),
					'home_install_btn_url' => array( 'การ์ดติดตั้ง · ลิงก์', 'path', $link ),
				),
				eab_media_fields( 'ih_step1', 'การ์ดติดตั้ง' ),
				array(
					'home_shots_note'    => array( 'คำบรรยายภาพใต้การ์ด (จำเป็นเมื่อภาพมีตัวเลข)', 'text' ),
					'tests_note'         => array( 'หมายเหตุท้ายส่วน (แสดงเสมอ · ห้ามลบคำเตือน)', 'textarea' ),
					'tests_pending_note' => array( 'ข้อความ "ยังไม่มีผลทดสอบ" (แสดงเฉพาะตอนที่หน้า Backtest/Forward ยังไม่มีตัวเลขจริง)', 'textarea' ),
				)
			),
		),

		'eab_home_how'      => array(
			'title'       => '2.5) หน้าแรก · เริ่มใช้งาน 6 ขั้น',
			'description' => 'การ์ดเลข 01 ถึง 06 ต่อกันด้วยเส้นประ แต่ละขั้นลิงก์ไปคู่มือ · ใส่ line ในช่องลิงก์ = ลิงก์ทัก LINE · ขั้นที่หัวข้อว่างจะไม่แสดง',
			'fields'      => array(
				'show_steps'           => array( 'แสดงส่วนนี้', 'checkbox' ),
				'steps_kicker'         => array( $kick, 'text' ),
				'steps_title'          => array( 'หัวข้อ (H2)', 'text' ),
				'steps_subtitle'       => array( 'คำอธิบายใต้หัวข้อ (เว้นว่าง = ซ่อน)', 'textarea' ),
				'home_steps_link_text' => array( 'ข้อความลิงก์คู่มือในการ์ด', 'text' ),
				'home_steps_line_text' => array( 'ข้อความลิงก์ทัก LINE ในการ์ด', 'text' ),
			),
		),

		'eab_home_pricing'  => array(
			'title'       => '2.6) หน้าแรก · แพ็กเกจ',
			'description' => 'ชื่อ ราคา สิ่งที่ได้ ป้ายแนะนำ ข้อความปุ่ม และรูปแบบการแสดงราคา ตั้งที่หมวด "10) แพ็กเกจราคา" · โหมด "ไม่แสดงราคา" หรือราคาที่ยังไม่มีตัวเลขจริง (เช่น X,XXX) จะแสดงข้อความสอบถามราคาแทน',
			'fields'      => array(
				'show_pricing_home'         => array( 'แสดงส่วนนี้', 'checkbox' ),
				'home_pricing_kicker'       => array( $kick, 'text' ),
				'pricing_home_title'        => array( 'หัวข้อ (H2)', 'text' ),
				'pricing_home_sub'          => array( 'คำอธิบายใต้หัวข้อ', 'textarea' ),
				'home_pricing_rec_label'    => array( 'ป้ายแพ็กเกจแนะนำ', 'text' ),
				'home_pricing_contact_text' => array( 'ข้อความแทนราคา (เมื่อไม่แสดงราคา)', 'text' ),
				'home_pricing_margin_note'  => array( 'คำเตือนใต้การ์ดแพ็กเกจ (ห้ามลบ)', 'textarea' ),
				'home_pricing_more_text'    => array( 'ปุ่มมุมขวาบนไปหน้าแพ็กเกจ · ข้อความ (เว้นว่าง = ซ่อน)', 'text' ),
			),
		),

		'eab_home_faq'      => array(
			'title'       => '2.7) หน้าแรก · FAQ',
			'description' => 'มีช่อง 10 ข้อ ข้อที่คำถามหรือคำตอบว่างจะไม่แสดง · จัดเป็น 2 คอลัมน์บนจอกว้าง · คำถามชุดนี้ใช้ทำ FAQ schema ของหน้าแรกด้วย',
			'fields'      => array(
				'show_faq'        => array( 'แสดงส่วนนี้', 'checkbox' ),
				'home_faq_kicker' => array( $kick, 'text' ),
				'faq_title'       => array( 'หัวข้อ (H2)', 'text' ),
				'faq_subtitle'    => array( 'คำอธิบายใต้หัวข้อ', 'textarea' ),
			),
		),

		'eab_home_risk'     => array(
			'title'       => '2.8) หน้าแรก · คำเตือนความเสี่ยง',
			'description' => 'สำคัญต่อความน่าเชื่อถือ ไม่แนะนำให้ปิด · ตัวข้อความคำเตือนฉบับเต็มแก้ที่หมวด "13) คำเตือนความเสี่ยง" (ใช้ร่วมกันทั้งเว็บ)',
			'fields'      => array(
				'show_risk'           => array( 'แสดงส่วนนี้', 'checkbox' ),
				'home_risk_label'     => array( 'ป้ายเตือนเล็กเหนือหัวข้อ', 'text' ),
				'risk_title'          => array( 'หัวข้อ (H2)', 'text' ),
				'home_risk_more_text' => array( 'ข้อความปุ่มไปหน้าประกาศความเสี่ยง (เว้นว่าง = ซ่อน · แสดงเมื่อหน้านั้นเผยแพร่แล้ว)', 'text' ),
			),
		),
	);

	/* 6 ขั้น · หัวข้อ รายละเอียด ลิงก์ */
	for ( $i = 1; $i <= 6; $i++ ) {
		$home['eab_home_how']['fields'][ 'step' . $i . '_title' ] = array( 'ขั้น ' . $i . ' · หัวข้อ', 'text' );
		$home['eab_home_how']['fields'][ 'step' . $i . '_desc' ]  = array( 'ขั้น ' . $i . ' · รายละเอียดสั้น', 'textarea' );
		$home['eab_home_how']['fields'][ 'step' . $i . '_url' ]   = array( 'ขั้น ' . $i . ' · ลิงก์คู่มือ (line = ทัก LINE · เว้นว่าง = ไม่มีลิงก์)', 'path' );
	}

	/* ข้อความคำเตือนหลักใช้ทั้งเว็บ (หน้าแรก · ท้ายเว็บทุกหน้า · บทความ · หน้า /go/) จึงแยกเป็นหมวดของตัวเอง ต่อจากหมวดแพ็กเกจ */
	$sitewide = array(
		'eab_risk' => array(
			'title'       => '13) คำเตือนความเสี่ยง',
			'description' => 'ข้อความเดียวนี้แสดงที่หน้าแรก (กล่องคำเตือนท้ายหน้า) ท้ายเว็บทุกหน้า ท้ายบทความ และหน้า /go/ · สำคัญต่อความถูกต้องและความน่าเชื่อถือ ห้ามลบ ตัด หรือลดทอน',
			'fields'      => array(
				'risk_text' => array( 'ข้อความคำเตือนฉบับเต็ม', 'textarea', 'ต้องบอกว่าขาดทุนได้ ผลในอดีตไม่รับประกันอนาคต และไม่ใช่คำแนะนำการลงทุน' ),
			),
		),
	);

	for ( $i = 1; $i <= 10; $i++ ) {
		$home['eab_home_faq']['fields'][ 'faq' . $i . '_q' ] = array( 'คำถามข้อ ' . $i, 'text' );
		$home['eab_home_faq']['fields'][ 'faq' . $i . '_a' ] = array( 'คำตอบข้อ ' . $i, 'textarea', $rule );
	}

	/* หมวดเดิมของหน้าแรก · ฟิลด์ที่หมวดใหม่ไม่ได้ใช้ย้ายไปหมวด "ค่าเดิม" ท้ายสุด · ฟิลด์ที่ไม่มีค่าเริ่มต้นแล้ว (ลบออกเพราะไม่ได้แสดงที่ไหน) ไม่สร้างช่อง */
	$origin_full = array( 'eab_hero', 'eab_pain', 'eab_about', 'eab_features', 'eab_gallery', 'eab_steps', 'eab_perf', 'eab_fit', 'eab_reviews', 'eab_faq', 'eab_risk', 'eab_home', 'eab_team', 'eab_assurance', 'eab_home_extra', 'eab_cta', 'eab_blog' );
	$origin_part = array( 'eab_pricing' );

	/* คีย์ที่โมดูลอื่นย้ายไปหมวดของตัวเองแล้ว ไม่ลงทะเบียนซ้ำ */
	$claimed = array();
	foreach ( $sections as $sid => $section ) {
		if ( in_array( $sid, $origin_full, true ) || in_array( $sid, $origin_part, true ) || empty( $section['fields'] ) ) {
			continue;
		}
		foreach ( array_keys( $section['fields'] ) as $key ) {
			$claimed[ $key ] = true;
		}
	}

	/* ถอดคีย์ที่โมดูลอื่นถือไว้ออกจากหมวดของเรา แล้วจดคีย์ที่เราถือ */
	$owned = array();
	$strip = function ( $group ) use ( $claimed, &$owned ) {
		foreach ( $group as $sid => $section ) {
			foreach ( array_keys( $section['fields'] ) as $key ) {
				if ( isset( $claimed[ $key ] ) ) {
					unset( $group[ $sid ]['fields'][ $key ] );
					continue;
				}
				$owned[ $key ] = true;
			}
		}
		return $group;
	};
	$home     = $strip( $home );
	$sitewide = array_filter(
		$strip( $sitewide ),
		function ( $section ) {
			return ! empty( $section['fields'] );
		}
	);

	$legacy = array();
	foreach ( $origin_full as $sid ) {
		if ( empty( $sections[ $sid ]['fields'] ) ) {
			unset( $sections[ $sid ] );
			continue;
		}
		foreach ( $sections[ $sid ]['fields'] as $key => $field ) {
			if ( ! isset( $owned[ $key ] ) && ! isset( $claimed[ $key ] ) && array_key_exists( $key, (array) $d ) ) {
				$legacy[ $key ] = $field;
			}
		}
		unset( $sections[ $sid ] );
	}
	foreach ( $origin_part as $sid ) {
		if ( empty( $sections[ $sid ]['fields'] ) ) {
			continue;
		}
		foreach ( array_keys( $sections[ $sid ]['fields'] ) as $key ) {
			if ( isset( $owned[ $key ] ) ) {
				unset( $sections[ $sid ]['fields'][ $key ] );
			}
		}
		if ( empty( $sections[ $sid ]['fields'] ) ) {
			unset( $sections[ $sid ] );
		}
	}

	/* แทรกหมวดหน้าแรกต่อจากหมวด 1 (ช่องทางติดต่อ) · หมวดคำเตือนหลัก (13) ต่อจากหมวด 10) แพ็กเกจราคา */
	$out      = array();
	$inserted = false;
	$risk_in  = false;
	foreach ( $sections as $sid => $section ) {
		$out[ $sid ] = $section;
		if ( 'eab_general' === $sid ) {
			$out      = array_merge( $out, $home );
			$inserted = true;
		}
		if ( 'eab_pricing' === $sid && $inserted ) {
			$out     = array_merge( $out, $sitewide );
			$risk_in = true;
		}
	}
	if ( ! $inserted ) {
		$out = array_merge( $home, $out );
	}
	if ( ! $risk_in && $sitewide ) {
		/* ไม่มีหมวดแพ็กเกจ = วางต่อจากหมวดคำเตือนของหน้าแรก (ไม่พบ = ต่อท้าย) */
		$out = eab_home_insert_after( $out, 'eab_home_risk', $sitewide );
	}

	if ( $legacy ) {
		$out['eab_home_legacy'] = array(
			'title'       => '99) หน้าแรก · ค่าเดิมที่ไม่ได้แสดงแล้ว',
			'description' => 'ช่องจากโครงหน้าแรกเดิมที่ยังมีค่าเริ่มต้นอยู่ แต่หน้าแรกแบบ Glass Sky ไม่ได้แสดงแล้ว · การแก้ค่าที่นี่ไม่เปลี่ยนหน้าแรก',
			'fields'      => $legacy,
		);
	}

	return $out;
}

/**
 * แทรกรายการต่อจาก key ที่กำหนด (ไม่มี key นั้น = ต่อท้าย)
 */
function eab_home_insert_after( $array, $after, $insert ) {
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

/* ==============================================================
 * ตัวช่วยของ front-page.php
 * ============================================================== */

/**
 * โทนพื้นของแต่ละส่วน · Glass Sky มีโทนสว่างอย่างเดียว (eab_section_tone() คืนค่าว่าง) เก็บไว้ให้ธีมลูกเปลี่ยนได้
 */
function eab_home_tone( $key ) {
	return eab_section_tone( $key );
}

/**
 * ไอคอนเส้นของหน้าแรก · ชื่อที่ eab_icon() ไม่มี (จากต้นแบบ dev/mockup) อยู่ที่นี่ ที่เหลือส่งต่อ eab_icon()
 */
function eab_home_icon( $name, $class = 'icon' ) {
	$extra = array(
		'plan'     => '<rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 4V3h6v1M8.5 10h7M8.5 14h7M8.5 18h4"/>',
		'sliders'  => '<path d="M4 7h10M18 7h2M4 17h4M12 17h8"/><circle cx="16" cy="7" r="2"/><circle cx="10" cy="17" r="2"/>',
		'wallet'   => '<path d="M4 7.5A2.5 2.5 0 0 1 6.5 5H18v3"/><rect x="4" y="8" width="16" height="11" rx="2.2"/><path d="M16 13.5h.01"/>',
		'plug'     => '<path d="M9 3v5M15 3v5M6.5 8h11v3a5.5 5.5 0 0 1-11 0zM12 16.5V21"/>',
		'group'    => '<path d="M16.5 19.5c0-2.1-1.8-3.8-4.5-3.8s-4.5 1.7-4.5 3.8"/><circle cx="12" cy="9" r="3.2"/><path d="M20.5 18.5c0-1.7-1.2-3-3-3.4M16.8 6.3a2.6 2.6 0 0 1 0 5.1M3.5 18.5c0-1.7 1.2-3 3-3.4M7.2 6.3a2.6 2.6 0 0 0 0 5.1"/>',
		'arrow-ur' => '<path d="M7 17 17 7M8 7h9v9"/>',
		'chev'     => '<path d="m9.5 6 6 6-6 6"/>',
	);
	if ( ! isset( $extra[ $name ] ) ) {
		return eab_icon( $name, $class );
	}
	$stroke = 'arrow-ur' === $name || 'chev' === $name ? '2' : '1.7';
	return '<svg class="' . esc_attr( $class ) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="' . $stroke . '" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $extra[ $name ] . '</svg>';
}

/**
 * ไอคอนตามชื่อแพลตฟอร์ม/อุปกรณ์ (ตัวอักษรเท่านั้น ไม่ใช้โลโก้การค้า)
 */
function eab_home_platform_icon( $label ) {
	$label = strtolower( (string) $label );
	$map   = array(
		'vps'        => 'server',
		'metatrader' => 'chart',
		'mt5'        => 'chart',
		'windows'    => 'windows',
		'iphone'     => 'apple',
		'ipad'       => 'apple',
		'ios'        => 'apple',
		'android'    => 'android',
		'mac'        => 'macos',
	);
	foreach ( $map as $needle => $icon ) {
		if ( false !== strpos( $label, $needle ) ) {
			return $icon;
		}
	}
	return 'check';
}

/**
 * ป้ายเล็กเหนือหัวข้อ · ข้อความไทยไม่เว้นระยะตัวอักษรและไม่แปลงตัวพิมพ์ (docs/design.md)
 */
function eab_home_kicker( $text, $class = '' ) {
	$text = trim( (string) $text );
	if ( '' === $text || ! eab_show_kickers() ) {
		return;
	}
	$thai = preg_match( '/\p{Thai}/u', $text ) ? ' is-th' : '';
	printf( '<p class="%s">%s</p>', esc_attr( trim( 'kicker hm-kicker' . $thai . ' ' . $class ) ), esc_html( $text ) );
}

/**
 * หัวแผง · kicker + H2 + คำอธิบาย (ซ้าย) และปุ่มกระจกเล็ก (ขวา)
 */
function eab_home_head( $id, $kicker, $title, $sub = '', $more_text = '', $more_url = '' ) {
	$sub       = trim( (string) $sub );
	$more_text = trim( (string) $more_text );
	echo '<header class="hm-head"><div class="hm-head-text">';
	eab_home_kicker( $kicker );
	echo '<h2 class="hm-h2" id="' . esc_attr( $id ) . '">' . eab_text( $title ) . '</h2>'; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside eab_text
	if ( '' !== $sub ) {
		echo '<p class="hm-lead">' . eab_text( $sub ) . '</p>'; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside eab_text
	}
	echo '</div>';
	if ( '' !== $more_text && '' !== $more_url ) {
		eab_home_btn( $more_text, $more_url, 'btn btn-ghost hm-btn hm-btn--sm', 'arrow-ur' );
	}
	echo '</header>';
}

/**
 * ปุ่ม pill แบบต้นแบบ: ข้อความ + ไอคอนในวงกลมด้านขวา
 */
function eab_home_btn( $text, $url, $class, $icon = 'arrow-ur' ) {
	$text = trim( (string) $text );
	if ( '' === $text || '' === (string) $url ) {
		return;
	}
	printf(
		'<a class="%1$s" href="%2$s"><span>%3$s</span><span class="hm-btn-ic">%4$s</span></a>',
		esc_attr( $class ),
		esc_url( $url ),
		esc_html( $text ),
		eab_home_icon( $icon ) // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG
	);
}

/**
 * แยก textarea เป็นคู่ "ซ้าย|ขวา" · ข้ามบรรทัดที่อ่านแล้วเหมือนผลเทรด
 *
 * @param string $text     ค่าจาก textarea
 * @param bool   $need_two true = ข้ามบรรทัดที่ไม่มีส่วนขวา
 * @param int    $max      จำนวนสูงสุด (0 = ไม่จำกัด)
 * @return array [ [ ซ้าย, ขวา ], ... ]
 */
function eab_home_pairs( $text, $need_two = false, $max = 0 ) {
	$out = array();
	foreach ( eab_lines( $text ) as $line ) {
		if ( eab_home_is_result_text( $line ) ) {
			continue;
		}
		$parts = array_map( 'trim', explode( '|', $line, 2 ) );
		$left  = $parts[0];
		$right = isset( $parts[1] ) ? $parts[1] : '';
		if ( '' === $left || ( $need_two && '' === $right ) ) {
			continue;
		}
		$out[] = array( $left, $right );
		if ( $max && count( $out ) >= $max ) {
			break;
		}
	}
	return $out;
}

/**
 * ข้อความที่อ่านแล้วเหมือนผลเทรด: ตัวเลขตามด้วย % หรือมีคำว่า กำไร
 */
function eab_home_is_result_text( $text ) {
	$text = (string) $text;
	return (bool) preg_match( '/[+-]?\d[\d.,]*\s*%/u', $text ) || false !== mb_strpos( $text, 'กำไร' );
}

/**
 * ลิงก์ภายในที่แสดงได้ · slug ภายในเว็บจะคืนค่าเมื่อหน้านั้นเผยแพร่แล้ว (กันลิงก์ไปหน้า 404 ก่อนรัน EA BULL Setup)
 * URL ภายนอก / anchor คืนค่าตามเดิม · ว่าง หรือ '#' = ''
 */
function eab_home_link( $path ) {
	$path = trim( (string) $path );
	if ( '' === $path || '#' === $path ) {
		return '';
	}
	if ( preg_match( '#^/?([a-z0-9-]+)/?$#', $path, $m ) && function_exists( 'eab_published_page_url' ) ) {
		return '' !== eab_published_page_url( $m[1] ) ? home_url( '/' . $m[1] . '/' ) : '';
	}
	return eab_link_url( $path );
}

/**
 * พิมพ์ย่อหน้าจาก textarea (เว้นบรรทัดว่าง = ย่อหน้าใหม่)
 */
function eab_home_paragraphs( $text, $class = '' ) {
	foreach ( preg_split( '/\n\s*\n/', (string) $text ) as $para ) {
		$para = trim( $para );
		if ( '' === $para ) {
			continue;
		}
		echo '<p' . ( $class ? ' class="' . esc_attr( $class ) . '"' : '' ) . '>' . nl2br( eab_text( $para ) ) . '</p>'; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside eab_text
	}
}

/**
 * อาร์กิวเมนต์ขนาดรูปบนหน้าแรก · รูปที่มากับธีมใช้ขนาดจริงของไฟล์ (กรอบจองพื้นที่ตรงกับรูป ไม่มี layout shift)
 * รูปในคลังสื่อใช้ขนาดจาก metadata ใน eab_media_info() อยู่แล้ว
 */
function eab_home_media_args( $key, $width, $height, $args = array() ) {
	static $sizes = array();
	$src  = trim( (string) eab_mod( $key . '_img' ) );
	$base = trailingslashit( get_template_directory_uri() );
	if ( '' !== $src && 0 === strpos( $src, $base ) ) {
		if ( ! isset( $sizes[ $src ] ) ) {
			$file          = get_template_directory() . '/' . ltrim( substr( $src, strlen( $base ) ), '/' );
			$info          = ( false === strpos( $file, '..' ) && is_file( $file ) ) ? @getimagesize( $file ) : false; // phpcs:ignore WordPress.PHP.NoSilencedErrors
			$sizes[ $src ] = ( is_array( $info ) && ! empty( $info[0] ) && ! empty( $info[1] ) ) ? array( (int) $info[0], (int) $info[1] ) : array( (int) $width, (int) $height );
		}
		list( $width, $height ) = $sizes[ $src ];
	}
	return array_merge(
		array(
			'width'  => (int) $width,
			'height' => (int) $height,
		),
		$args
	);
}

/**
 * ภาพประกอบในการ์ด "ข้อมูลก่อนตัดสินใจ" · มีรูปใน Customizer = ใช้รูป · ไม่มี = ภาพวาด SVG ของธีม (ไม่มีตัวเลข)
 *
 * @param string $key  prefix ของช่องรูป (tests_bt / tests_fw / ih_step1)
 * @param string $art  ภาพวาดสำรอง: bt | fw | install
 */
function eab_home_shot_media( $key, $art ) {
	$src = eab_theme_asset_url( eab_mod( $key . '_img' ) ); // assets/... = ภาพที่มากับธีม
	if ( '' !== $src ) {
		$alt  = trim( (string) eab_mod( $key . '_img_alt' ) );
		$cap  = trim( (string) eab_mod( $key . '_img_caption' ) );
		$size = eab_home_media_args( $key, 1280, 720 );
		echo '<span class="hm-shot-img">' . eab_media_picture( $key, $src, '' !== $alt ? $alt : $cap, $size['width'], $size['height'] ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside eab_media_picture
		if ( '' !== $cap ) {
			echo '<span class="hm-shot-cap">' . esc_html( $cap ) . '</span>';
		}
		return;
	}
	$svg = array(
		'bt'      => '<rect x="18" y="20" width="264" height="128" rx="12" fill="#fff"/><path d="M34 120 C80 110 110 96 140 90 S210 60 266 44" fill="none" stroke="#0B5CAD" stroke-width="3"/><path d="M34 120 C80 110 110 96 140 90 S210 60 266 44 V132 H34z" fill="#CFE3FB" opacity=".5"/><rect x="34" y="34" width="80" height="8" rx="4" fill="#E2EDFB"/>',
		'fw'      => '<rect x="18" y="20" width="264" height="128" rx="12" fill="#fff"/><g fill="#FDD023"><rect x="44" y="96" width="18" height="36" rx="4"/><rect x="74" y="84" width="18" height="48" rx="4"/><rect x="104" y="100" width="18" height="32" rx="4"/><rect x="134" y="74" width="18" height="58" rx="4"/><rect x="164" y="80" width="18" height="52" rx="4"/><rect x="194" y="62" width="18" height="70" rx="4"/><rect x="224" y="56" width="18" height="76" rx="4"/></g><rect x="34" y="34" width="90" height="8" rx="4" fill="#FAEAA8"/>',
		'install' => '<rect x="18" y="20" width="264" height="128" rx="12" fill="#fff"/><rect x="34" y="36" width="70" height="96" rx="8" fill="#E9F3FF"/><g fill="#0B5CAD"><rect x="44" y="48" width="50" height="6" rx="3"/><rect x="44" y="62" width="40" height="6" rx="3" opacity=".6"/><rect x="44" y="76" width="44" height="6" rx="3" opacity=".6"/></g><rect x="116" y="36" width="150" height="96" rx="8" fill="#0A2E66"/><path d="M130 110 L160 92 L180 100 L210 74 L250 60" fill="none" stroke="#FDD023" stroke-width="3"/>',
	);
	echo '<span class="hm-shot-img"><svg viewBox="0 0 300 168" aria-hidden="true" focusable="false">' . ( isset( $svg[ $art ] ) ? $svg[ $art ] : '' ) . '</svg></span>'; // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG
}

/**
 * ราคาที่แสดงได้จริง (มีตัวเลข และไม่ใช่ placeholder อย่าง "X,XXX" / "ระบุ...")
 */
function eab_home_price_ready( $price ) {
	$price = trim( (string) $price );
	return '' !== $price && ( (bool) preg_match( '/\d/', $price ) || eab_is_free_price( $price ) ) && ! eab_is_placeholder( $price );
}

/**
 * ปุ่ม/ลิงก์ติดต่อของหน้าแรก · ปลายทางเดียวกับ eab_contact_button() (LINE → /go/ → ไม่แสดง) ไม่มีปุ่มใดชี้ '#'
 * data-line-pos ให้ main.js นับคลิก · data-line-pkg ส่งชื่อแพ็กเกจไปกับ event line_click
 *
 * @param bool $icons false = ไม่มีไอคอน (ลิงก์ข้อความเล็กในการ์ดขั้นตอน)
 */
function eab_home_contact_link( $text, $class, $pos, $pkg = '', $icons = true ) {
	static $hinted = false;
	$target = eab_contact_target();
	if ( '' === $target['url'] ) {
		if ( ! $hinted && current_user_can( 'edit_theme_options' ) ) {
			$hinted = true;
			echo '<span class="admin-hint">ปุ่มติดต่อถูกซ่อน: ใส่ลิงก์ LINE ที่ ปรับแต่ง → 1) ช่องทางติดต่อ</span>';
		}
		return;
	}
	$fallback = trim( (string) eab_mod( 'contact_fallback_text' ) );
	$label    = $target['is_line'] ? trim( (string) $text ) : $fallback;
	if ( '' === $label ) {
		$label = '' !== $fallback ? $fallback : trim( (string) eab_mod( 'footer_line_text' ) );
	}
	printf(
		'<a class="%1$s" href="%2$s"%3$s data-line-pos="%4$s"%5$s>%6$s<span>%7$s</span>%8$s</a>',
		esc_attr( $class ),
		esc_url( $target['url'] ),
		$target['is_line'] ? ' target="_blank" rel="noopener"' : '',
		esc_attr( $pos ),
		'' !== $pkg ? ' data-line-pkg="' . esc_attr( $pkg ) . '"' : '',
		$icons ? eab_icon( $target['is_line'] ? 'line' : 'chat' ) : '', // phpcs:ignore WordPress.Security.EscapeOutput
		esc_html( $label ),
		$icons ? eab_icon( 'arrow' ) : '' // phpcs:ignore WordPress.Security.EscapeOutput
	);
}

/**
 * คำประสมที่เบราว์เซอร์มักตัดกลางคำในหัวข้อของหน้าแรก (เช่น "ต่าง|กันอย่างไร", "ระบบตัว|เดียวกัน")
 * ใช้เฉพาะหน้าแรก · "|" = รอยต่อที่ห้ามตัดบรรทัด (eab_keep_words ใส่ word joiner ให้)
 */
function eab_home_keep_words( $words ) {
	if ( ! is_front_page() ) {
		return $words;
	}
	return array_merge( (array) $words, array( 'ต่าง|กัน', 'ตัว|เดียว' ) );
}
add_filter( 'eab_keep_words', 'eab_home_keep_words' );

/**
 * โหลดภาพ Hero ล่วงหน้าบนจอกว้าง (เฉพาะหน้าแรกที่ใส่ภาพ Hero เอง)
 */
function eab_home_preload_hero() {
	if ( ! is_front_page() || ! eab_mod( 'show_hero' ) ) {
		return;
	}
	if ( function_exists( 'eab_has_elementor_content' ) && eab_has_elementor_content() && eab_uses_elementor_page_template() ) {
		return;
	}
	$src = trim( (string) eab_mod( 'hero_image' ) );
	if ( '' === $src ) {
		return;
	}
	printf( '<link rel="preload" as="image" href="%s" media="(min-width: 961px)" fetchpriority="high">' . "\n", esc_url( $src ) );
}
add_action( 'wp_head', 'eab_home_preload_hero', 1 );
