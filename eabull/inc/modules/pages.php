<?php
/**
 * EA BULL · โมดูลหน้าเนื้อหา
 * (Backtest / Forward Test / Pricing / Risk Disclosure / เพจเอกสาร / หน้ารวมบทความ / บทความ / ค้นหา / 404)
 *
 * - ค่าเริ่มต้นใหม่ + เขียนข้อความเดิมใหม่ให้เป็นของ EA BULL เอง ผ่านฟิลเตอร์ 'eab_defaults'
 * - ฟิลด์ Customizer ของหน้ากลุ่มนี้ ผ่านฟิลเตอร์ 'eab_customizer_sections'
 * - ตัวช่วยที่เทมเพลตของโมดูลนี้ใช้ (prefix eab_pages_)
 * - หน้าตา Glass Sky (docs/design.md): หัวเพจแผงกระจก eab_pages_hero() · เนื้อหายาวการ์ดขาว + สารบัญ eab_pages_longform()
 *   สไตล์อยู่ใน assets/css/pages.css (คลาสเนื้อหายาวที่ใช้ร่วมกับหน้าคู่มือก็อยู่ในไฟล์นั้น)
 *
 * กฎเนื้อหา: ห้ามใส่ตัวเลขผลการเทรดสมมติ ห้ามรับประกันกำไร ห้ามลดทอนคำเตือนความเสี่ยง
 *
 * @package eabull
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ==============================================================
 * ค่าเริ่มต้น
 * ============================================================== */

add_filter( 'eab_defaults', 'eab_pages_defaults' );

function eab_pages_defaults( $d ) {
	return array_merge(
		$d,
		array(
			/* ---------- หน้า Backtest ---------- */
			'backtest_kicker'      => 'Backtest',
			'backtest_sub'         => 'ขั้นวางแผนเริ่มที่นี่: ปล่อยให้ EA เจอราคาในอดีตใน Strategy Tester ของ MT5 แล้วอ่านรายงานให้รู้ว่าตัวเลขแต่ละค่าเชื่อได้แค่ไหน',
			'backtest_intro'       => 'Backtest ไม่ได้ตอบว่า EA จะทำเงินได้เท่าไร สิ่งที่ได้คือภาพนิสัยของระบบในตลาดที่ผ่านมาแล้ว ทั้งความถี่ในการเข้าเทรด ระยะเวลาที่ถือออเดอร์ และช่วงที่บัญชีติดลบหนักที่สุด ข้อมูลชุดนี้ใช้วางแผนทุนและเพดานขาดทุน ก่อนพาระบบไปเจอราคาสด',
			'backtest_img_mobile'  => '',
			'backtest_img_alt'     => 'ส่วน Results ของรายงาน Strategy Tester ของ EA BULL 5.0 โหมด Lite ช่วง 1 ม.ค. ถึง 30 ก.ย. 2026: Initial Deposit 10,000 Total Net Profit 58,915.21 Profit Factor 2.62 Equity Drawdown Maximal 8,248.36 Total Trades 24,493 และกราฟ Balance',
			'backtest_img_caption' => 'ส่วน Results ของรายงาน Strategy Tester ของ EA BULL 5.0 โหมด Lite · XAUUSD.c M1 · 1 ม.ค. ถึง 30 ก.ย. 2026 · ไม่มีการตัดขาดทุนอัตโนมัติ · เป็นผลทดสอบย้อนหลัง ไม่ใช่ผลการเทรดจริง และไม่รับประกันผลในอนาคต',
			'backtest_img_note'    => 'ใช้ภาพรายงาน Strategy Tester ของจริงเท่านั้น (ชุดเดียวกับตัวเลขในช่องสถิติ · ครอปเฉพาะส่วน Results กับกราฟ Balance ได้) ห้ามตัดต่อหรือทำภาพขึ้นใหม่ และต้องมีคำบรรยายว่าเป็นผลทดสอบย้อนหลัง',
			'backtest_note'        => 'เงื่อนไขของตัวเลขชุดนี้: Strategy Tester ของ MT5 แบบ Every tick based on real ticks (History Quality 99%) EA BULL รุ่น 5.0 โหมด Lite ทุนตั้งต้น 10,000 USD Leverage 1:2000 · ค่าตั้งของทุกโหมดคัดจากการทดสอบบนข้อมูลช่วงเดียวกับที่ใช้รายงาน ผลช่วงนี้จึงมีแนวโน้มดีกว่าที่ควรคาดหวังในช่วงอื่น · ช่วง 15 ม.ค. ถึง 3 มิ.ย. 2026 MT5 ไม่มีราคา tick จริง จึงจำลองราคาจากแท่ง 1 นาที · ผลนี้รวมการเพิ่มขนาดออเดอร์ตามกำไรสะสมทุกต้นเดือน กำไรและช่วงติดลบเป็นเงินจึงใหญ่ขึ้นในเดือนหลัง · ระหว่างทดสอบ Equity เคยติดลบลอยสูงสุด 8,248.36 USD เท่ากับ 82.5% ของทุนตั้งต้น และตัวเลขนี้ไม่ใช่เพดานที่รับประกัน · ระบบทบไม้ ไม่มีการตัดขาดทุนอัตโนมัติ · ตัวทดสอบไม่มีปฏิทินข่าว ผลนี้จึงไม่มีการหลบข่าวแดง · โบรกเกอร์ Spread และ Leverage ต่างกัน ผลก็ต่างกัน · ช่องสถิติและภาพด้านบนเป็นของโหมด Lite ผลของโหมด Advance และ Full อยู่ในตารางด้านล่างของหน้านี้',
			'backtest_disclaimer'  => 'ตัวเลขทดสอบย้อนหลังคำนวณจากราคาในอดีต ใช้ศึกษาว่าระบบทำงานอย่างไร แต่ไม่ได้บอกว่าผลแบบเดิมจะเกิดซ้ำ และไม่ใช่คำแนะนำให้ลงทุน EA BULL เป็นระบบทบไม้ที่เติมไม้แก้เมื่อราคาสวน และโหมดสำเร็จรูปไม่มีการตัดขาดทุนอัตโนมัติ ถ้าราคาวิ่งทางเดียวไกลกว่าที่ระบบรับได้ บัญชีเสียหายหนักได้ถึงเสียเงินทั้งหมด การเทรด Forex และ CFD เสี่ยงสูง คุณอาจขาดทุนจนเงินในบัญชีลดลงมากหรือหมดลง',
			'backtest_cta_title'   => 'อยากให้ทีมงานช่วยดูค่าที่ตั้งไว้?',
			'backtest_cta_text'    => 'ส่งภาพหน้าต่าง Settings มาทาง LINE แล้วถามได้ว่าช่องไหนยังไม่ตรงกับบัญชีที่คุณตั้งใจใช้',

			/* การ์ดสถานะเมื่อยังไม่มีตัวเลขจริง (ใช้ทั้งหน้า Backtest และ Forward) */
			'results_pending_label' => 'สถานะการเผยแพร่ผล',
			'results_pending_title' => 'ตัวเลขจะขึ้นที่นี่เมื่อมีหลักฐานให้ตรวจ',
			'results_pending_text'  => 'หน้านี้ลงผลทดสอบเฉพาะชุดที่บอกแหล่งที่มาได้ และแจ้งเงื่อนไขครบพอให้คนอื่นทำซ้ำ ตอนนี้ยังไม่มีชุดที่ผ่านเกณฑ์นั้น ช่องสถิติจึงว่างไว้โดยตั้งใจ ระหว่างนี้ลองทำตามคู่มือในหน้านี้กับบัญชีทดลองของคุณเองได้',
			'results_pending_value' => 'ยังไม่เผยแพร่',

			/* ---------- หัวเพจหน้าย่อย: คำเน้นสีใน H1 + รูปด้านขวา (ไม่มีรูป = ภาพประกอบของธีม) ----------
			 * คำเน้นต้องอยู่ในชื่อเพจจริง (title ในไฟล์ inc/content/pages/*.html = H1 หลังกด Setup · docs/plan.md ข้อ 3)
			 * Backtest: ทดสอบ EA ย้อนหลังใน MT5 ด้วย Strategy Tester · Forward: ทดสอบ EA กับตลาดจริงก่อนใช้เงินจริง
			 * Pricing: แพ็กเกจ EA BULL · Risk: ความเสี่ยงของการใช้ EA เทรดบน MT5 · Articles: บทความ EA BULL */
			'pages_hero_illus'   => true,
			'backtest_hero_grad' => 'ย้อนหลัง',
			'backtest_hero_img'  => '',
			'forward_hero_grad'  => 'ตลาดจริง',
			'forward_hero_img'   => '',
			'pricing_hero_grad'  => 'แพ็กเกจ',
			'pricing_hero_img'   => '',
			'riskpage_hero_grad' => 'ความเสี่ยง',
			'riskpage_hero_img'  => '',
			'articles_hero_grad' => 'บทความ',

			/* ---------- หน้า Forward Test ---------- */
			'forward_kicker'       => 'Forward Test',
			'forward_sub'          => 'ขั้นติดตามผลก่อนขยายทุน: ปล่อย EA เทรดกับราคาสดบน Demo หรือเงินก้อนที่เสียได้ แล้วดูว่าผลจริงห่างจาก Strategy Tester แค่ไหน',
			'forward_intro'        => 'ในช่วง Forward Test ระบบรับมือกับราคาสดทีละวัน จะใช้บัญชี Demo บัญชี Cent หรือบัญชีจริงที่ใส่เงินไว้น้อยก็ได้ ตัวเลขที่ได้รวมผลของ Spread ที่ขยับจริง ราคาที่คลาด และเครื่องที่ต้องออนไลน์ทุกวัน จึงเป็นข้อมูลที่ควรมีในมือก่อนตัดสินใจเพิ่มเงิน',
			'forward_note'         => 'ผลจากช่วงหนึ่งใช้แทนช่วงอื่นไม่ได้ ทุกครั้งที่อ่านตัวเลขชุดใด ให้ดูประกอบว่ารันมานานเท่าไร ใช้บัญชีประเภทไหน และมีการฝากหรือถอนเงินระหว่างทางหรือไม่',
			'forward_disclaimer'   => 'ผล Forward Test เป็นภาพของช่วงที่รันเท่านั้น จะรันบน Demo หรือเงินจริงก็ตาม ตัวเลขนี้ไม่ได้รับรองผลในอนาคต และไม่ใช่คำแนะนำให้ลงทุน EA BULL เป็นระบบทบไม้ที่เติมไม้แก้เมื่อราคาสวน และโหมดสำเร็จรูปไม่มีการตัดขาดทุนอัตโนมัติ ถ้าราคาวิ่งทางเดียวไกลกว่าที่ระบบรับได้ บัญชีเสียหายหนักได้ถึงเสียเงินทั้งหมด การเทรด Forex และ CFD เสี่ยงสูง คุณอาจขาดทุนจนเงินในบัญชีลดลงมากหรือหมดลง',
			'forward_cta_title'    => 'พร้อมลองกับราคาสดแล้วหรือยัง?',
			'forward_cta_text'     => 'ถามทีมงานทาง LINE ได้ว่าควรเตรียมบัญชีและเครื่องที่ใช้รันอย่างไร และค่าไหนบ้างที่ควรบันทึกไว้ทุกสัปดาห์',

			/* ---------- หน้า Pricing ---------- */
			'pricing_page_kicker'     => 'Pricing',
			'pricing_sub'             => 'เริ่มจากปรึกษาฟรีทาง LINE แล้วเลือกแพ็กเกจที่ตรงกับวิธีใช้งานของคุณ แพ็กเกจเดียวใช้ได้ทั้ง WING NITRO, EA BULL และ WING TWIN ต่างกันที่จำนวนบัญชีและการดูแล',
			'pricing_kicker'          => 'Packages',
			'pricing_title'           => 'เลือกแพ็กเกจที่เข้ากับการใช้งาน',
			'pricing_subtitle'        => 'ยังไม่แน่ใจ เริ่มที่ Starter ได้เลยโดยไม่มีค่าใช้จ่าย ทีมงานยืนยันยอดเป็นข้อความใน LINE ก่อนชำระเงินทุกครั้ง',
			'pricing_note'            => 'ค่าแพ็กเกจคือค่าสิทธิ์ใช้งานและการดูแล ไม่ใช่ค่าผลตอบแทน แพ็กเกจไหนก็ขาดทุนได้ EA BULL เป็นระบบทบไม้ที่เติมไม้แก้เมื่อราคาสวน และโหมดสำเร็จรูปไม่มีการตัดขาดทุนอัตโนมัติ ถ้าราคาวิ่งทางเดียวไกลกว่าที่ระบบรับได้ บัญชีเสียหายหนักได้ถึงเสียเงินทั้งหมด WING NITRO เป็นระบบทบไม้ (มาร์ติงเกล) ไม่มีจุดตัดขาดทุน และ WING TWIN เป็นกริดสองทางที่ทบ lot ของไม้สวนทางและไม่มีการตัดขาดทุนเช่นกัน ชำระครั้งเดียวและไม่มีการคืนเงิน จึงควรปรึกษาแบบ Starter ให้ครบก่อน ยอดที่ใช้จริงคือยอดที่ทีมงานยืนยันใน LINE วันที่คุณชำระเงิน',
			'pricing_flag_label'      => 'แนะนำ',
			'pricing_contact_label'   => 'สอบถามราคา',
			'pricing_contact_via'     => 'ทางแชต LINE',
			'pricing_hero_jump_label' => 'ดูตารางสรุป',

			/* แพ็กเกจ (หมวด 10 ใช้ร่วมกับหน้าแรก) · ราคาและสิ่งที่รวมยืนยันโดยเจ้าของ 6 ต.ค. 2026 (โครงเดียวกับแพ็กเกจในเครือ)
			 * จ่ายครั้งเดียว ไม่มีค่ารายเดือนหรือรายปี · ไม่มีการคืนเงิน (เจ้าของยืนยัน 6 ต.ค. 2026) */
			'pkg1_period'             => '',
			'pkg2_period'             => 'บาท · จ่ายครั้งเดียว',
			'pkg3_period'             => 'บาท · จ่ายครั้งเดียว',
			'pkg1_tag'                => 'คุยกับทีมงานก่อน ดูว่า EA BULL เข้ากับบัญชีและแผนของคุณหรือไม่',
			'pkg1_features'           => "ปรึกษาทีมงานทาง LINE โดยไม่มีค่าใช้จ่าย\nดูภาพรวมการทำงาน และความต่างของโหมด Lite, Advance และ Full\nถามเรื่องบัญชี MT5 โบรกเกอร์ และการเตรียมทุนก่อนเริ่ม\nดาวน์โหลดไฟล์กับคู่มือมาอ่านก่อนตัดสินใจได้",
			'pkg2_tag'                => 'สำหรับคนที่พร้อมใช้งานจริง และติดตั้งเองตามคู่มือได้',
			'pkg2_features'           => "เปิดสิทธิ์ใช้งาน EA BULL ได้ 1 ถึง 2 บัญชี MT5\nโหมด Lite, Advance และ Full อยู่ในไฟล์เดียว ไม่ต้องตั้งค่าไฟล์ .set\nคู่มือติดตั้งและคู่มือแดชบอร์ดภาษาไทย\nรับไฟล์รุ่นใหม่ตามรอบอัปเดต\nทีมงานดูแลส่วนตัวทาง LINE OA",
			'pkg3_tag'                => 'สำหรับคนที่อยากให้ทีมงานช่วยตั้งแต่ติดตั้งจนเริ่มรัน',
			'pkg3_features'           => "สิทธิ์ใช้งาน EA BULL ตามจำนวนบัญชีที่ตกลงกัน\nทีมงานช่วยติดตั้งบนคอมพิวเตอร์หรือ VPS ของคุณ\nช่วยตรวจการตั้งค่าก่อนเริ่มรันจริง\nคุยแนวทางคุมความเสี่ยงให้สอดคล้องกับทุนของคุณ\nทีมงานดูแลใกล้ชิดทาง LINE OA",

			'compare_kicker'          => 'At a Glance',
			'compare_title'           => 'เทียบแพ็กเกจทีละหัวข้อ',
			'compare_yes_label'       => 'มี',
			'compare_no_label'        => 'ไม่มี',
			'compare_rows'            => "เรื่อง | Starter | Pro | VIP\nราคา | ฟรี | 6,990 บาท | 9,990 บาท\nรูปแบบชำระ | ไม่มีค่าใช้จ่าย | จ่ายครั้งเดียว | จ่ายครั้งเดียว\nบัญชี MT5 ที่เปิดสิทธิ์ | ✗ | 1 ถึง 2 บัญชี | ตามที่ตกลง\nโหมด Lite, Advance และ Full ในไฟล์เดียว | ✗ | ✓ | ✓\nทีมงานช่วยติดตั้งบนเครื่องหรือ VPS | ✗ | ✗ | ✓\nไฟล์รุ่นใหม่ตามรอบอัปเดต | ✓ | ✓ | ✓\nการดูแลทาง LINE | พื้นฐาน | ส่วนตัว | ใกล้ชิด\nการรับประกันกำไร | ✗ | ✗ | ✗",
			'pricing_license_kicker'  => 'Before You Pay',
			'pricing_license_title'   => 'เงินทุน บัญชี และไฟล์ เรื่องที่ควรรู้ก่อนตกลง',
			'pricing_license_text'    => 'ตารางนี้รวมเรื่องที่ใช้กับทุกแพ็กเกจ เรื่องไหนยังไม่ชัด ขอให้ทีมงานตอบเป็นข้อความในแชตก่อนชำระเงิน จะย้อนกลับมาอ่านได้ทุกเมื่อ',
			'pricing_license_rows'    => "เรื่อง | สิ่งที่รู้แน่ | คำถามที่ควรถามต่อ\nเงินทุนเทรด | อยู่ในบัญชี MT5 ของคุณกับโบรกเกอร์ · ทุนแนะนำ: บัญชีเซนต์ราว 1,000 USD (100,000 cent) ขึ้นไป หรือบัญชีมาตรฐานราว 100,000 USD ขึ้นไป · เงินในบัญชีตามโหมด: Lite 1 เท่า Advance 2 เท่า Full 3 เท่าของทุนคิดไม้ | ควรเริ่มกับบัญชีเดโมก่อนหรือไม่\nไฟล์ EA | ใช้กับ MetaTrader 5 เท่านั้น ไม่รองรับ MT4 · โหมด Lite, Advance และ Full อยู่ในไฟล์เดียว | โหมดไหนเข้ากับบัญชีของคุณ\nสิทธิ์ใช้งาน | ผูกกับเลขบัญชี MT5 ทีมงานเปิดให้หลังชำระเงิน · จ่ายครั้งเดียว ไม่มีค่ารายเดือนหรือรายปี · ต่ออายุสิทธิ์ไม่มีค่าใช้จ่ายเพิ่ม | เปลี่ยนบัญชีภายหลังได้ไหม\nเครื่องที่ใช้รัน | คอมพิวเตอร์ Windows ที่เปิดตลอด หรือ Windows VPS ซึ่งเราแนะนำ | ค่าเช่า VPS รวมอยู่หรือแยกจ่าย\nการดูผล | แอป MT5 บนมือถือดูพอร์ตได้ แต่รัน EA ไม่ได้ | ควรเช็กอะไรบ้างในแต่ละวัน",
			'pricing_license_points'  => "เงินทุนเทรดไม่ต้องโอนให้ใคร EA ทำงานกับยอดเงินในบัญชีที่คุณเปิดไว้กับโบรกเกอร์\nMaster password ของบัญชี MT5 ให้เก็บไว้กับคุณคนเดียว อย่าส่งให้ใครทางแชต\nถามให้ชัดว่าค่าแพ็กเกจรวมอะไร และอะไรเป็นค่าใช้จ่ายแยก อย่างค่าเช่า VPS\nอ่านเรื่องสิทธิ์ใช้ไฟล์และข้อห้ามแจกจ่ายต่อได้ใน[เงื่อนไขการใช้บริการ](/terms-of-use/)",
			'pricing_order_kicker'    => 'How to Start',
			'pricing_order_title'     => 'จากแชตแรกถึงวันที่เปิดระบบ',
			'pricing_order_sub'       => 'คุยและชำระเงินกับ EA BULL ผ่านช่องทางที่ลิงก์จากเว็บนี้เท่านั้น ถ้ามีบัญชีแปลกหน้าทักมาในนามของเรา ตรวจกับทีมงานก่อนทำอะไรต่อ',
			'pricing_order_steps'     => "เปิดแชต LINE | เล่าแผนการใช้งานของคุณ เช่น มีบัญชี MT5 แล้วหรือยัง และอยากเริ่มกับบัญชีแบบไหน\nถามให้ครบ แล้วเก็บคำตอบไว้ | สิ่งที่รวมอยู่และจำนวนบัญชีที่ใช้ได้ ควรได้คำตอบเป็นตัวหนังสือในแชตก่อนตัดสินใจ เพราะค่าแพ็กเกจชำระครั้งเดียวและไม่มีการคืนเงิน\nตรวจผู้รับเงินก่อนกดโอน | ชื่อบัญชีต้องตรงกับที่ทีมงานพิมพ์ไว้ในแชต ถ้ามีใครมาขอให้โอนเข้าบัญชีอื่น อย่าเพิ่งโอน\nส่งเลขบัญชีขอเปิดสิทธิ์ | ดาวน์โหลดไฟล์ได้จาก[หน้าลิงก์รวม](/go/) แล้วส่งเลขบัญชี MT5 กับชื่อเซิร์ฟเวอร์ให้ทีมงาน เปิดสิทธิ์แล้ว EA เริ่มทำงานเองภายใน 5 นาที\nติดตั้งแล้วเริ่มจากบัญชีเล็ก | ทำตาม[คู่มือติดตั้งบนเว็บ](/how-to-install/) ดูว่าการ์ดล็อกอินเป็นสีเขียว แล้วเริ่มกับ Demo ก่อนขยายทุน",
			'pricing_order_btn_text'  => 'คุยกับทีมงาน',
			'pricing_cta_title'       => 'ยังไม่แน่ใจว่าแพ็กเกจไหนเข้ากับคุณ?',
			'pricing_cta_text'        => 'เล่าวิธีที่คุณอยากใช้ EA ให้ทีมงานฟัง แล้วถามจนได้ข้อมูลครบ ไม่ต้องรีบตัดสินใจตั้งแต่แชตแรก',

			/* ---------- หน้า Risk Disclosure ---------- */
			'riskpage_kicker'        => 'Risk Disclosure',
			'riskpage_sub'           => 'สิ่งที่ตลาดทำได้ สิ่งที่ EA ทำไม่ได้ และสิ่งที่คุณต้องดูแลเองในฐานะเจ้าของบัญชี อ่านให้จบก่อนใส่เงินจริง',
			'riskpage_intro'         => 'ทุกออเดอร์ที่ EA BULL เปิดอยู่ในบัญชีเทรดของคุณ กำไรหรือขาดทุนจึงเป็นเงินของคุณเอง โปรดอ่านทุกหัวข้อด้านล่างก่อนเริ่ม และถ้ายังมีข้อไหนค้างใจ ถามทีมงานทาง LINE จนชัดแล้วค่อยตัดสินใจ',
			'riskpage_image_caption' => 'ตัวอย่างแนวคิดการกำหนดขนาด Lot จากทุนและความเสี่ยงต่อออเดอร์ ใช้เพื่อการเรียนรู้ ไม่ใช่ผลการเทรด',
			'rp_block1_title'        => 'คุณอาจขาดทุนจนเงินทุนหมดบัญชี',
			'rp_block1_text'         => 'Forex, CFD และตราสารที่ใช้ Leverage มีโอกาสขาดทุนสูงกว่าเงินฝากหรือการลงทุนแบบดั้งเดิมหลายเท่า ราคาเปลี่ยนเร็วจนขาดทุนเกินแผนได้ภายในไม่กี่นาที และความเสียหายอาจลามถึงเงินทั้งบัญชี ใช้เฉพาะเงินที่ถ้าหายไปแล้วชีวิตยังเดินต่อได้ปกติ ห้ามนำเงินยืม เงินกู้ หรือเงินเก็บไว้ยามจำเป็นมาเทรด',
			'rp_block2_title'        => 'EA BULL ไม่รับประกันกำไร',
			'rp_block2_text'         => 'สิ่งที่ EA BULL ทำคือเปิดและปิดออเดอร์เมื่อครบเงื่อนไขที่กำหนด ระบบที่ทำตามกฎช่วยให้คุณไม่ต้องตัดสินใจตอนใจร้อน แต่ไม่มีกฎชุดไหนบังคับให้ตลาดจ่ายผลตอบแทนแน่นอนได้ ตัวเลขในบัญชีแปรไปตามจังหวะราคา ชุดค่าที่คุณใช้ ขนาดเงินทุน และกติกาของโบรกเกอร์ ใครสัญญากำไรแน่นอนโดยอ้างชื่อเรา ข้อความนั้นไม่ได้มาจาก EA BULL',
			'rp_block3_title'        => 'ผลทดสอบและผลในอดีตไม่ได้รับรองอนาคต',
			'rp_block3_text'         => 'Backtest จำลองการเทรดบนราคาในอดีต ส่วน Forward Test สะท้อนเฉพาะช่วงที่รันจริง ทั้งสองแบบไม่รู้ว่าตลาดข้างหน้าจะผันผวนแค่ไหน สภาพคล่องจะบางลงเมื่อไร หรือต้นทุนจะเปลี่ยนอย่างไร ผลที่เคยเกิดขึ้นจึงใช้เป็นหลักประกันผลในอนาคตไม่ได้',
			'rp_block4_title'        => 'คุณเป็นผู้ตัดสินใจ และรับผิดชอบบัญชีของตัวเอง',
			'rp_block4_text'         => 'คุณเป็นคนเลือกว่าจะเปิดบัญชีกับใคร ใช้ Lot เท่าไร รับการติดลบได้ลึกแค่ไหน และจะเริ่มหรือหยุด EA เมื่อไร ผลการเทรดที่ตามมา การดูแลเงินทุน และการเก็บรหัสผ่านบัญชีให้ปลอดภัย จึงเป็นความรับผิดชอบของผู้ใช้เอง',
			'rp_block5_title'        => 'ข้อมูลบนเว็บนี้ไม่ใช่คำแนะนำการลงทุน',
			'rp_block5_text'         => 'ทุกหน้าบนเว็บและทุกคำตอบทางแชต มีไว้อธิบายวิธีใช้เครื่องมือกับหลักการทั่วไป เราไม่ทราบรายได้ ภาระหนี้ หรือเป้าหมายทางการเงินของคุณ ข้อมูลเหล่านี้จึงไม่ใช่คำแนะนำด้านการลงทุน การเงิน หรือภาษี ถ้าต้องการคำแนะนำที่ทำมาเพื่อคุณโดยเฉพาะ ควรปรึกษาผู้ประกอบวิชาชีพที่ได้รับอนุญาต',
			'rp_block6_title'        => 'ปัญหาเครื่อง เน็ต และฝั่งโบรกเกอร์ ทำให้ผลคลาดได้',
			'rp_block6_text'         => 'EA จะทำงานได้ก็ต่อเมื่อ MT5 ยังเปิดอยู่ และคำสั่งเดินทางไปถึงเซิร์ฟเวอร์ของโบรกเกอร์ทัน เครื่อง VPS บูตใหม่ อินเทอร์เน็ตขาด ไฟฟ้าดับ หรือ MT5 ปิดตัวหลังอัปเดต ล้วนทำให้ระบบหยุดเงียบโดยไม่มีใครเตือน ส่วนโบรกเกอร์อาจไม่รับคำสั่ง ตอบกลับด้วย Requote หรือจับคู่ที่ราคาห่างจากที่ขอเมื่อราคาวิ่งแรง ควรเปิดดูสถานะ VPS และบัญชีในแอป MT5 เป็นกิจวัตร',
			'riskpage_cta_title'     => 'มีคำถามเรื่องความเสี่ยงก่อนเริ่ม?',
			'riskpage_cta_text'      => 'ถามทีมงานทาง LINE ได้ทุกเรื่องที่ยังค้างใจ เช่น ควรเช็กอะไรทุกวัน หรือจะรู้ได้อย่างไรว่า EA หยุดทำงาน',

			/* ---------- เพจเอกสาร (page.php: เกี่ยวกับเรา / นโยบาย / เงื่อนไข / ลบข้อมูล) ---------- */
			'doc_updated_label'   => 'ปรับปรุงล่าสุด',
			'doc_toc_label'       => 'สารบัญ',
			'aboutpage_cta_title' => 'คุยกับทีมงาน EA BULL ทาง LINE',
			'aboutpage_cta_text'  => 'ทีมงานคนไทยตอบได้ตั้งแต่เรื่อง MT5 ที่คุณใช้อยู่ ไปจนถึงขั้นตอนรับไฟล์ และคำถามเรื่องความเสี่ยงก่อนเริ่ม',

			/* ---------- หน้ารวมบทความ (index.php) ---------- */
			'articles_kicker'           => 'Articles',
			'articles_title'            => 'บทความ EA BULL',
			/* blog_subtitle: คำโปรยใต้หัวข้อหน้า /articles/ (คีย์เดิมของธีม ย้ายมาอยู่หมวดหน้าบทความ) */
			'blog_subtitle'             => 'เรื่องที่ช่วยให้ใช้ EA อย่างมีแบบแผน แบ่งเป็นสี่หมวด: พื้นฐาน EA และ MT5 วางแผนการเทรด ติดตามผล และบริหารความเสี่ยง เขียนให้อ่านง่ายและนำไปใช้กับบัญชี MT5 ของคุณได้จริง',
			'articles_count_text'       => '{n} บทความ',
			'articles_all_label'        => 'ทั้งหมด',
			'articles_load_more'        => 'ดูบทความเพิ่ม',
			'articles_prev_label'       => 'ก่อนหน้า',
			'articles_next_label'       => 'ถัดไป',
			'articles_empty_text'       => 'บทความชุดแรกกำลังทยอยเผยแพร่ ระหว่างนี้เริ่มจากคู่มือด้านล่าง ซึ่งพาตั้งแต่เตรียมบัญชีไปจนถึงรัน EA บน VPS',
			'articles_pillars_title'    => 'เลือกอ่านตามเรื่องที่อยากรู้',
			'articles_pillars_sub'      => 'บทความทั้งหมดเขียนสำหรับคนใช้ EA บน MetaTrader 5 ตั้งแต่ยังไม่เคยติดตั้ง ไปจนถึงดูแลพอร์ตที่รันอยู่ทุกวัน ถ้าเพิ่งเริ่มให้อ่านหมวด EA และบอทเทรดก่อน ถ้ากำลังติดปัญหาข้ามไปหมวดที่ตรงกับอาการได้เลย',
			'articles_pillars_order'    => "ea-basics
gold-trading
mt5
risk-management
vps
broker-account
monitoring
trading-plan",
			'articles_guides_kicker'    => 'Guides',
			'articles_guides_title'     => 'คู่มือใช้งานทีละขั้น',
			'articles_guides_sub'       => 'ไม่ต้องอ่านเรียงก็ได้ เลือกคู่มือที่ตรงกับงานตรงหน้า ทุกหน้าอธิบายเป็นภาษาไทยพร้อมจุดที่มักพลาด',
			'articles_guides_items'     => "how-to-install | ลงไฟล์ในโฟลเดอร์ Experts เปิด Algo Trading และไล่แก้อาการที่เจอบ่อย\nopen-mt5-account | สมัครจากลิงก์ Zaurix เลือกประเภทบัญชี และผ่านขั้นยืนยันตัวตน\ndeposit | ผูกบัญชีธนาคาร ฝากเงินบาทผ่าน QR Payment ใน Portal แล้วเช็กยอดใน MT5\nmt5-login | ใส่เลขบัญชี รหัสผ่าน และเซิร์ฟเวอร์ให้ถูก แล้วดูพอร์ตได้ทั้งบนคอมและมือถือ\nbacktest | ตั้งค่า Strategy Tester ทีละช่อง และอ่านรายงานโดยไม่หลงตัวเลขสวย\nforward-test | เลือกบัญชีทดสอบ วางเกณฑ์ล่วงหน้า แล้วติดตามผลกับราคาสด\nvps-windows | เชื่อม Remote Desktop เข้า Windows VPS ให้ MT5 ทำงานต่อแม้ปิดคอมที่บ้าน\nvps-android | เปิดหน้าจอ VPS จากมือถือ Android ผ่าน Windows App\nvps-ios | ดู MT5 บน VPS จาก iPhone และ iPad ด้วย Windows App\ntools | หาขนาด Lot จากความเสี่ยงต่อออเดอร์ และดูว่าต้องกำไรเท่าไรจึงกลับมาที่เดิมหลังติดลบ\nrisk-disclosure | สิ่งที่ต้องรู้ก่อนลงเงินจริง ทั้งฝั่งตลาด โบรกเกอร์ เครื่องที่รัน และตัวคุณเอง",

			/* ---------- บทความเดี่ยว (single.php) ---------- */
			'article_published_label' => 'เผยแพร่',
			'article_show_updated'    => false,
			'article_updated_label'   => 'อัปเดตล่าสุด',
			'article_reading_text'    => 'ใช้เวลาอ่านราว {n} นาที',
			'article_toc_label'       => 'ในบทความนี้',
			'article_share_label'     => 'แชร์บทความ',
			'article_copy_label'      => 'คัดลอกลิงก์',
			'article_author_kicker'   => 'ผู้เขียน',
			'article_author_name'     => 'ทีมงาน EA BULL',
			'article_author_bio'      => 'บทความชุดนี้เขียนโดยทีมงานคนไทยของ EA BULL เพื่อเล่าวิธีใช้ EA บน MT5 อย่างมีแบบแผน ทุกเรื่องมีไว้เพื่อการเรียนรู้ ไม่ได้ชี้นำให้ลงทุน เห็นจุดไหนคลาดเคลื่อน บอกเราได้ทาง LINE',
			'article_author_link'     => 'อ่านเกี่ยวกับ EA BULL',
			'article_disclaimer_text' => 'ตัวเลขทุกตัวในบทความนี้สมมติขึ้นเพื่ออธิบายวิธีคิด ไม่ได้มาจากบัญชีที่ใช้ EA BULL และไม่ใช่ผลการเทรดของระบบ',
			'article_disclaimer_link' => 'อ่านประกาศความเสี่ยงฉบับเต็ม',
			'article_related_title'   => 'บทความอื่นในหมวดนี้',

			/* ---------- ค้นหา + 404 ---------- */
			'search_title'              => 'ผลการค้นหา',
			'search_count_text'         => 'พบ {n} รายการ',
			'search_placeholder'        => 'ค้นหาบทความและคู่มือ',
			'search_empty_text'         => 'ยังไม่เจอเนื้อหาที่ตรงกับคำนี้ ลองใช้คำที่สั้นลง หรือคำภาษาอังกฤษอย่าง VPS, Lot, Drawdown หรือเลือกคู่มือด้านล่าง เรื่องที่ยังไม่มีบนเว็บ ถามทีมงานทาง LINE ได้',
			'err404_title'              => 'ไม่พบหน้าที่คุณเปิด',
			'err404_text'               => 'URL อาจพิมพ์ตกไปบางตัว หรือหน้าเดิมถูกย้ายแล้ว ใช้ช่องค้นหาด้านล่าง หรือไปต่อที่คู่มือ การทดสอบ แพ็กเกจ และประกาศความเสี่ยงได้เลย',
			'err404_search_placeholder' => 'ค้นหาในเว็บ EA BULL',
			'err404_home_label'         => 'กลับหน้าแรก',
			'err404_contact_text'       => 'ถามทีมงานทาง LINE',
			'err404_links'              => "ทดสอบ EA ย้อนหลัง | /backtest/\nแพ็กเกจและวิธีสอบถาม | /pricing/\nคู่มือติดตั้ง EA บน MT5 | /how-to-install/\nความเสี่ยงที่ต้องรู้ก่อนเริ่ม | /risk-disclosure/",
		)
	);
}

/* ==============================================================
 * Customizer
 * ============================================================== */

add_filter( 'eab_customizer_sections', 'eab_pages_customizer_sections', 10, 2 );

function eab_pages_customizer_sections( $sections, $d ) {
	$stats_bt = array();
	for ( $i = 1; $i <= 8; $i++ ) {
		$stats_bt[ 'bt_stat' . $i . '_label' ] = array( 'สถิติ ' . $i . ' · หัวข้อ', 'text' );
		$stats_bt[ 'bt_stat' . $i . '_value' ] = array( 'สถิติ ' . $i . ' · ค่า (ขึ้นต้นด้วย "ระบุ" = ยังไม่แสดง)', 'text' );
	}

	$sections['eab_backtest'] = array(
		'title'       => '20) หน้า Backtest',
		'description' => 'กรอกผลการทดสอบย้อนหลังจริงเท่านั้น ห้ามใส่ตัวเลขสมมติ และห้ามลบ Disclaimer · ข้อความที่ขึ้นต้นด้วย "ระบุ" หรือ "เช่น" จะถูกซ่อนจนกว่าจะกรอกจริง',
		'fields'      => array_merge(
			array(
				'backtest_kicker' => array( 'ป้ายเล็กเหนือชื่อหน้า (อังกฤษสั้น ๆ)', 'text' ),
				'backtest_sub'    => array( 'คำโปรยใต้ชื่อหน้า', 'text' ),
				'backtest_intro'  => array( 'ย่อหน้าเกริ่นนำ', 'textarea' ),
			),
			$stats_bt,
			array(
				'results_pending_label' => array( 'การ์ดยังไม่มีผลทดสอบ · ป้ายเล็ก (หน้า Backtest)', 'text' ),
				'results_pending_title' => array( 'การ์ดยังไม่มีผลทดสอบ · หัวข้อ (แสดงเมื่อยังไม่กรอกตัวเลขจริง)', 'text' ),
				'results_pending_text'  => array( 'การ์ดยังไม่มีผลทดสอบ · รายละเอียด', 'textarea' ),
				'results_pending_value' => array( 'การ์ดยังไม่มีผลทดสอบ · ป้ายแทนค่าในช่องสถิติที่ยังไม่กรอก', 'text', 'แสดงใต้หัวข้อสถิติแต่ละช่องจนกว่าจะกรอกตัวเลขจริง (ใช้ทั้งหน้า Backtest และ Forward)' ),
			),
			eab_media_fields( 'backtest', 'ภาพรายงาน Strategy Tester (ของจริง ชุดเดียวกับช่องสถิติ)' ),
			array(
				'backtest_note'       => array( 'หมายเหตุเงื่อนไขการทดสอบ', 'textarea' ),
				'backtest_disclaimer' => array( 'Disclaimer (จำเป็น ห้ามลบ)', 'textarea' ),
				'backtest_cta_title'  => array( 'บล็อกติดต่อท้ายหน้า · หัวข้อ', 'text' ),
				'backtest_cta_text'   => array( 'บล็อกติดต่อท้ายหน้า · คำอธิบาย', 'textarea' ),
			)
		),
	);

	$sections['eab_forward'] = array(
		'title'       => '21) หน้า Forward Test',
		'description' => 'หน้านี้เป็นคู่มือวิธีทำ Forward Test ด้วยบัญชีของผู้ใช้เอง ไม่มีช่องผลทดสอบ ลิงก์ผล หรือรูปผล (เจ้าของตัดออก 12 ต.ค. 2026 เพราะไม่มีผล Forward Test ของ EA BULL เอง · ห้ามใส่ผลของแบรนด์อื่น)',
		'fields'      => array_merge(
			array(
				'forward_kicker' => array( 'ป้ายเล็กเหนือชื่อหน้า (อังกฤษสั้น ๆ)', 'text' ),
				'forward_sub'    => array( 'คำโปรยใต้ชื่อหน้า', 'text' ),
				'forward_intro'  => array( 'ย่อหน้าเกริ่นนำ', 'textarea' ),
			),
			array(
				'forward_note'       => array( 'หมายเหตุ', 'textarea' ),
				'forward_disclaimer' => array( 'Disclaimer (จำเป็น ห้ามลบ)', 'textarea' ),
				'forward_cta_title'  => array( 'บล็อกติดต่อท้ายหน้า · หัวข้อ', 'text' ),
				'forward_cta_text'   => array( 'บล็อกติดต่อท้ายหน้า · คำอธิบาย', 'textarea' ),
			)
		),
	);

	$sections['eab_pricing_extra'] = array(
		'title'       => '23) หน้า Pricing (เพิ่มเติม)',
		'description' => 'หน้านี้ใช้แพ็กเกจจากหมวด "10) แพ็กเกจราคา" ร่วมกัน · ถ้าราคายังไม่มีตัวเลข (เช่น X,XXX) การ์ดจะแสดงข้อความสอบถามราคาแทน · การ์ด ตารางสรุป และตารางก่อนชำระเงิน ห้ามระบุสิ่งที่แพ็กเกจรวม (จำนวนบัญชี การติดตั้งให้ VPS อัปเดต การคืนเงิน) จนกว่าเจ้าของยืนยันเงื่อนไขจริง',
		'fields'      => array(
			'pricing_page_kicker'    => array( 'ป้ายเล็กเหนือชื่อหน้า (อังกฤษสั้น ๆ)', 'text' ),
			'pricing_sub'            => array( 'คำโปรยใต้ชื่อหน้า', 'text' ),
			'pricing_kicker'         => array( 'ส่วนแพ็กเกจ · ป้ายเล็ก', 'text' ),
			'pricing_flag_label'     => array( 'ป้ายบนการ์ดที่ติ๊ก "แนะนำ"', 'text' ),
			'pricing_contact_label'  => array( 'ข้อความแทนราคา (โหมดสอบถามราคา)', 'text' ),
			'pricing_contact_via'    => array( 'บรรทัดเล็กใต้ข้อความแทนราคา (แสดงเฉพาะเมื่อมีลิงก์ LINE)', 'text' ),
			'pricing_hero_jump_label' => array( 'หัวเพจ · ปุ่มเลื่อนไปตารางเปรียบเทียบ (เว้นว่าง = ซ่อน)', 'text' ),
			'compare_kicker'         => array( 'ตารางเปรียบเทียบ · ป้ายเล็ก', 'text' ),
			'compare_title'          => array( 'ตารางเปรียบเทียบ · หัวข้อ', 'text' ),
			'compare_rows'           => array( 'ตารางเปรียบเทียบ (บรรทัดละ 1 แถว คั่นช่องด้วย | บรรทัดแรกคือหัวตาราง ใช้ ✓ และ ✗ ได้)', 'textarea' ),
			'compare_yes_label'      => array( 'ตารางเปรียบเทียบ · คำอ่านของ ✓ (สำหรับโปรแกรมอ่านหน้าจอ)', 'text' ),
			'compare_no_label'       => array( 'ตารางเปรียบเทียบ · คำอ่านของ ✗ (สำหรับโปรแกรมอ่านหน้าจอ)', 'text' ),
			'pricing_license_kicker' => array( 'สิทธิ์ใช้งาน · ป้ายเล็ก', 'text' ),
			'pricing_license_title'  => array( 'สิทธิ์ใช้งาน · หัวข้อ', 'text' ),
			'pricing_license_text'   => array( 'สิทธิ์ใช้งาน · คำอธิบาย', 'textarea' ),
			'pricing_license_rows'   => array( 'สิทธิ์ใช้งาน · ตาราง (บรรทัดละ 1 แถว คั่นช่องด้วย " | " บรรทัดแรกคือหัวตาราง)', 'textarea', 'ต้องตรงกับการ์ดแพ็กเกจและตารางเปรียบเทียบ · ใส่เฉพาะเงื่อนไขที่ยืนยันแล้ว' ),
			'pricing_license_points' => array( 'สิทธิ์ใช้งาน · ข้อควรรู้ (บรรทัดละ 1 ข้อ · [ข้อความ](/slug/) = ลิงก์ภายใน)', 'textarea' ),
			'pricing_order_kicker'   => array( 'ขั้นตอนสั่งซื้อ · ป้ายเล็ก', 'text' ),
			'pricing_order_title'    => array( 'ขั้นตอนสั่งซื้อ · หัวข้อ', 'text' ),
			'pricing_order_sub'      => array( 'ขั้นตอนสั่งซื้อ · คำอธิบาย', 'textarea' ),
			'pricing_order_steps'    => array( 'ขั้นตอนสั่งซื้อ (บรรทัดละ 1 ขั้น รูปแบบ: หัวข้อ | รายละเอียด)', 'textarea' ),
			'pricing_order_btn_text' => array( 'ขั้นตอนสั่งซื้อ · ข้อความปุ่มติดต่อ', 'text' ),
			'pricing_cta_title'      => array( 'บล็อกติดต่อท้ายหน้า · หัวข้อ', 'text' ),
			'pricing_cta_text'       => array( 'บล็อกติดต่อท้ายหน้า · คำอธิบาย', 'textarea' ),
		),
	);

	$risk_blocks = array();
	for ( $i = 1; $i <= 6; $i++ ) {
		$risk_blocks[ 'rp_block' . $i . '_title' ] = array( 'หัวข้อ ' . $i, 'text' );
		$risk_blocks[ 'rp_block' . $i . '_text' ]  = array( 'เนื้อหา ' . $i, 'textarea' );
	}
	$sections['eab_riskpage'] = array(
		'title'       => '24) หน้า Risk Disclosure',
		'description' => 'หน้าประกาศความเสี่ยงฉบับเต็ม มี 6 หัวข้อ ห้ามลบหรือลดทอนคำเตือน · วันที่ปรับปรุงที่ยังเป็น "ระบุ..." จะไม่แสดง',
		'fields'      => array_merge(
			array(
				'riskpage_kicker'        => array( 'ป้ายเล็กเหนือชื่อหน้า (อังกฤษสั้น ๆ)', 'text' ),
				'riskpage_sub'           => array( 'คำโปรยใต้ชื่อหน้า', 'text' ),
				'riskpage_intro'         => array( 'กล่องคำเตือนเกริ่นนำ', 'textarea' ),
				'riskpage_image'         => array( 'ภาพประกอบความเสี่ยง (ไม่บังคับ · ห้ามมีตัวเลขผลการเทรด)', 'image' ),
				'riskpage_image_caption' => array( 'คำบรรยายภาพประกอบความเสี่ยง', 'text' ),
			),
			$risk_blocks,
			array(
				'riskpage_updated'   => array( 'วันที่ปรับปรุงล่าสุด (เช่น ปรับปรุงล่าสุด: 1 ตุลาคม 2569)', 'text' ),
				'riskpage_cta_title' => array( 'บล็อกติดต่อท้ายหน้า · หัวข้อ', 'text' ),
				'riskpage_cta_text'  => array( 'บล็อกติดต่อท้ายหน้า · คำอธิบาย', 'textarea' ),
			)
		),
	);

	$sections['eab_pages_docs'] = array(
		'title'       => '27) เพจเอกสาร · เกี่ยวกับเรา / นโยบาย / เงื่อนไข',
		'description' => 'เพจทั่วไปที่ไม่ได้เลือกเทมเพลต (เช่น about, privacy-policy, terms-of-use, data-deletion) · วันที่ปรับปรุงดึงจากวันที่แก้ไขเพจล่าสุดอัตโนมัติ · บล็อกติดต่อแสดงเฉพาะเพจ about',
		'fields'      => array(
			'doc_updated_label'   => array( 'ข้อความหน้าวันที่ปรับปรุง', 'text' ),
			'doc_toc_label'       => array( 'หัวกล่องสารบัญ', 'text' ),
			'aboutpage_cta_title' => array( 'เพจเกี่ยวกับเรา · หัวข้อบล็อกติดต่อ', 'text' ),
			'aboutpage_cta_text'  => array( 'เพจเกี่ยวกับเรา · คำอธิบายบล็อกติดต่อ', 'textarea' ),
		),
	);

	$sections['eab_pages_articles'] = array(
		'title'       => '28) หน้ารวมบทความ & บทความ',
		'description' => 'ข้อความบนหน้า /articles/ หน้าหมวดหมู่ และหน้าบทความเดี่ยว · กล่องผู้เขียนใช้ข้อเท็จจริงเท่านั้น ห้ามอ้างประสบการณ์หรือคุณวุฒิที่ไม่มีจริง',
		'fields'      => array(
			'articles_kicker'         => array( 'หน้ารวม · ป้ายเล็กเหนือหัวข้อ', 'text' ),
			'articles_title'          => array( 'หน้ารวม · หัวข้อ (ใช้เมื่อยังไม่ได้ตั้งเพจบทความ)', 'text' ),
			'blog_subtitle'           => array( 'หน้ารวม · คำโปรยใต้หัวข้อ', 'textarea' ),
			'articles_count_text'     => array( 'หน้ารวม · บรรทัดจำนวนบทความ ({n} = จำนวน)', 'text' ),
			'articles_all_label'      => array( 'หน้ารวม · ป้ายหมวด "ทั้งหมด"', 'text' ),
			'articles_load_more'      => array( 'หน้ารวม · ปุ่มโหลดเพิ่ม', 'text' ),
			'articles_prev_label'     => array( 'หน้ารวม · ลิงก์หน้าก่อนหน้า (เมื่อปิด JavaScript)', 'text' ),
			'articles_next_label'     => array( 'หน้ารวม · ลิงก์หน้าถัดไป (เมื่อปิด JavaScript)', 'text' ),
			'articles_empty_text'     => array( 'หน้ารวม · ข้อความเมื่อยังไม่มีบทความ', 'textarea' ),
			'articles_pillars_title'  => array( 'หมวดบทความ · หัวข้อ (เว้นว่าง = ซ่อนทั้งส่วน)', 'text' ),
			'articles_pillars_sub'    => array( 'หมวดบทความ · คำอธิบาย', 'textarea' ),
			'articles_pillars_order'  => array( 'หมวดบทความ · ลำดับหมวด (slug หมวด บรรทัดละ 1 · คำอธิบายการ์ดมาจาก "คำอธิบาย" ของหมวดในเมนูเรื่อง → หมวดหมู่)', 'textarea' ),
			'articles_guides_kicker'  => array( 'รายการคู่มือ · ป้ายเล็ก', 'text' ),
			'articles_guides_title'   => array( 'รายการคู่มือ · หัวข้อ', 'text' ),
			'articles_guides_sub'     => array( 'รายการคู่มือ · คำอธิบาย', 'textarea' ),
			'articles_guides_items'   => array( 'รายการคู่มือ (บรรทัดละ 1 เพจ รูปแบบ: slug | คำอธิบายหนึ่งบรรทัด · แสดงเฉพาะเพจที่เผยแพร่แล้ว)', 'textarea' ),
			'article_published_label' => array( 'บทความ · ข้อความหน้าวันที่เผยแพร่', 'text' ),
			'article_show_updated'    => array( 'บทความ · แสดงวันที่อัปเดตล่าสุดใต้หัวข้อ', 'checkbox', 'ปิดไว้ (8 ต.ค. 2026) · วันที่อัปเดตยังอยู่ใน schema ให้ Google อ่านเหมือนเดิม' ),
			'article_updated_label'   => array( 'บทความ · ข้อความหน้าวันที่อัปเดต', 'text' ),
			'article_reading_text'    => array( 'บทความ · เวลาอ่านโดยประมาณ ({n} = นาที)', 'text' ),
			'article_toc_label'       => array( 'บทความ · หัวกล่องสารบัญ', 'text' ),
			'article_share_label'     => array( 'บทความ · ข้อความหน้าปุ่มแชร์', 'text' ),
			'article_copy_label'      => array( 'บทความ · ปุ่มคัดลอกลิงก์', 'text' ),
			'article_author_kicker'   => array( 'กล่องผู้เขียน · ป้ายเล็ก', 'text' ),
			'article_author_name'     => array( 'กล่องผู้เขียน · ชื่อ', 'text' ),
			'article_author_bio'      => array( 'กล่องผู้เขียน · คำอธิบาย (ข้อเท็จจริงเท่านั้น)', 'textarea' ),
			'article_author_link'     => array( 'กล่องผู้เขียน · ข้อความลิงก์ไปเพจเกี่ยวกับเรา', 'text' ),
			'article_disclaimer_text' => array( 'บทความ · คำเตือนเพิ่มเติมต่อท้ายคำเตือนความเสี่ยงหลัก', 'textarea', 'แสดงต่อจากข้อความในหมวด "13) คำเตือนความเสี่ยง" เสมอ (ข้อความหลักไม่ถูกตัด)' ),
			'article_disclaimer_link' => array( 'บทความ · ข้อความลิงก์ไปหน้าประกาศความเสี่ยง', 'text' ),
			'article_related_title'   => array( 'บทความ · หัวข้อบทความที่เกี่ยวข้อง', 'text' ),
		),
	);

	$sections['eab_pages_misc'] = array(
		'title'       => '32) หน้าค้นหา & หน้า 404',
		'description' => 'ข้อความบนหน้าผลการค้นหาและหน้าไม่พบหน้า (404) · ลิงก์ด่วน 404 ใช้รูปแบบ: ชื่อ | /slug/',
		'fields'      => array(
			'search_title'              => array( 'ค้นหา · หัวข้อ (ต่อท้ายด้วยคำค้น)', 'text' ),
			'search_count_text'         => array( 'ค้นหา · บรรทัดจำนวนผล ({n} = จำนวน)', 'text' ),
			'search_placeholder'        => array( 'ค้นหา · ข้อความในช่องค้นหา', 'text' ),
			'search_empty_text'         => array( 'ค้นหา · ข้อความเมื่อไม่พบผล', 'textarea' ),
			'err404_title'              => array( '404 · หัวข้อ', 'text' ),
			'err404_text'               => array( '404 · คำอธิบาย', 'textarea' ),
			'err404_search_placeholder' => array( '404 · ข้อความในช่องค้นหา', 'text' ),
			'err404_home_label'         => array( '404 · ปุ่มกลับหน้าแรก', 'text' ),
			'err404_contact_text'       => array( '404 · ปุ่มติดต่อ (แสดงเมื่อมีลิงก์ LINE หรือหน้า /go/)', 'text' ),
			'err404_links'              => array( '404 · ลิงก์ด่วน (บรรทัดละ 1 ลิงก์ รูปแบบ: ชื่อ | /slug/)', 'textarea' ),
		),
	);

	$grad_note = 'คำหรือวลีที่มีอยู่ในชื่อหน้า จะแสดงเป็นสีไล่น้ำเงินถึงทอง (ไม่พบในชื่อหน้า = ไม่เน้น · เว้นว่าง = ไม่เน้น)';
	$img_note  = 'ภาพตกแต่งในกรอบกระจกด้านขวาของหัวเพจ (ซ่อนบนจอมือถือ) · ห้ามมีตัวเลขผลการเทรด · เว้นว่าง = ใช้ภาพประกอบของธีม';
	$sections['eab_pages_hero'] = array(
		'title'       => '34) หัวเพจหน้าย่อย · คำเน้นสีและภาพ',
		'description' => 'ใช้กับหน้า Backtest, Forward Test, แพ็กเกจ, ประกาศความเสี่ยง และหน้ารวมบทความ · ป้ายเล็กและคำโปรยของแต่ละหน้าแก้ในหมวดของหน้านั้น',
		'fields'      => array(
			'pages_hero_illus'   => array( 'แสดงภาพประกอบของธีมในหัวเพจ เมื่อยังไม่ได้ใส่รูป', 'checkbox' ),
			'backtest_hero_grad' => array( 'Backtest · คำเน้นสีในชื่อหน้า', 'text', $grad_note ),
			'backtest_hero_img'  => array( 'Backtest · รูปในหัวเพจ', 'image', $img_note ),
			'forward_hero_grad'  => array( 'Forward Test · คำเน้นสีในชื่อหน้า', 'text', $grad_note ),
			'forward_hero_img'   => array( 'Forward Test · รูปในหัวเพจ', 'image', $img_note ),
			'pricing_hero_grad'  => array( 'แพ็กเกจ · คำเน้นสีในชื่อหน้า', 'text', $grad_note ),
			'pricing_hero_img'   => array( 'แพ็กเกจ · รูปในหัวเพจ', 'image', $img_note ),
			'riskpage_hero_grad' => array( 'ประกาศความเสี่ยง · คำเน้นสีในชื่อหน้า', 'text', $grad_note ),
			'riskpage_hero_img'  => array( 'ประกาศความเสี่ยง · รูปในหัวเพจ', 'image', $img_note ),
			'articles_hero_grad' => array( 'หน้ารวมบทความ · คำเน้นสีในชื่อหน้า', 'text', $grad_note ),
		),
	);

	/* คีย์ที่ย้ายมาอยู่หมวดของโมดูลนี้: ถอดออกจากหมวดอื่นให้เหลือช่องเดียว (หมวดที่ว่างแล้วถูกลบทิ้ง) */
	$own = array(
		'blog_subtitle'         => 'eab_pages_articles',
		'results_pending_title' => 'eab_backtest',
		'results_pending_text'  => 'eab_backtest',
	);
	foreach ( $sections as $sid => $section ) {
		if ( empty( $section['fields'] ) || ! is_array( $section['fields'] ) ) {
			continue;
		}
		foreach ( $own as $key => $home_sid ) {
			if ( $sid !== $home_sid && isset( $section['fields'][ $key ] ) ) {
				unset( $sections[ $sid ]['fields'][ $key ] );
			}
		}
		if ( empty( $sections[ $sid ]['fields'] ) && ! in_array( $sid, $own, true ) ) {
			unset( $sections[ $sid ] );
		}
	}

	return $sections;
}

/* ==============================================================
 * ตัวช่วยทั่วไป
 * ============================================================== */

/**
 * ค่ามีเนื้อหาจริง (ไม่ว่าง ไม่ใช่ "ระบุ..." / "เช่น...")
 */
function eab_pages_has( $value ) {
	return ! eab_is_placeholder( $value );
}

/**
 * ข้อความวันที่ที่ยังเป็น placeholder (เช่น "ปรับปรุงล่าสุด: ระบุวันที่" หรือมี <!-- -->)
 */
function eab_pages_date_pending( $value ) {
	$value = trim( (string) $value );
	return eab_is_placeholder( $value ) || false !== mb_strpos( $value, 'ระบุ' ) || false !== strpos( $value, '<!--' ) || ! preg_match( '/[0-9\x{0E50}-\x{0E59}]/u', $value );
}

/**
 * แทน {n} ในข้อความด้วยตัวเลข
 */
function eab_pages_count_text( $key, $n ) {
	return str_replace( '{n}', number_format_i18n( (int) $n ), (string) eab_mod( $key ) );
}

/**
 * วันที่แบบไทย (พ.ศ.) จากสตริง ISO 8601 ของ WordPress (ใช้วันที่ตามเขตเวลาของเว็บ)
 */
function eab_pages_thai_date( $iso ) {
	if ( ! preg_match( '/^(\d{4})-(\d{2})-(\d{2})/', (string) $iso, $m ) ) {
		return '';
	}
	$months = array( 1 => 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม' );
	$month  = (int) $m[2];
	if ( ! isset( $months[ $month ] ) ) {
		return '';
	}
	return (int) $m[3] . ' ' . $months[ $month ] . ' ' . ( (int) $m[1] + 543 );
}

/**
 * เวลาอ่านโดยประมาณ (นาที) · ภาษาไทยไม่มีการเว้นวรรคระหว่างคำ จึงนับจากจำนวนตัวอักษร
 */
function eab_pages_reading_minutes( $html ) {
	$text  = preg_replace( '/\s+/u', '', wp_strip_all_tags( (string) $html ) );
	$chars = function_exists( 'mb_strlen' ) ? mb_strlen( (string) $text, 'UTF-8' ) : strlen( (string) $text ) / 3;
	return max( 1, (int) ceil( $chars / 850 ) );
}

/**
 * alt ของรูปหน้าปก: ใช้ข้อความ alt จากคลังสื่อ · ถ้าว่าง หรือเป็นข้อความกลางที่ Setup ใส่ให้ทุกรูป ใช้ชื่อบทความแทน
 */
function eab_pages_featured_alt( $post_id = 0 ) {
	$post_id = $post_id ? (int) $post_id : (int) get_the_ID();
	$title   = wp_strip_all_tags( get_the_title( $post_id ) );
	$thumb   = function_exists( 'get_post_thumbnail_id' ) ? (int) get_post_thumbnail_id( $post_id ) : 0;
	$alt     = $thumb ? trim( (string) get_post_meta( $thumb, '_wp_attachment_image_alt', true ) ) : '';
	$generic = (array) apply_filters( 'eab_pages_generic_alts', array( 'EA BULL ผู้ช่วยเทรดอัตโนมัติสำหรับ MT5 (ภาพประกอบ)' ) );
	if ( '' === $alt || in_array( $alt, $generic, true ) ) {
		return $title;
	}
	return $alt;
}

/**
 * แตกบรรทัด "ซ้าย | ขวา" เป็น array( ซ้าย, ขวา )
 */
function eab_pages_pairs( $text ) {
	$out = array();
	foreach ( eab_lines( $text ) as $line ) {
		$parts = array_map( 'trim', explode( '|', $line, 2 ) );
		if ( '' === $parts[0] ) {
			continue;
		}
		$out[] = array( $parts[0], isset( $parts[1] ) ? $parts[1] : '' );
	}
	return $out;
}

/* ==============================================================
 * ตาราง: จอแคบ (≤ 640px) เรียงแต่ละแถวเป็นการ์ดพร้อมป้ายหัวคอลัมน์
 * ============================================================== */

/**
 * ใส่ data-label ให้ทุก <td> ของ table.data-table จากหัวตาราง และติดคลาส table-wrap--stack table-wrap--cards
 * เทมเพลตของโมดูลนี้เปิดใช้ด้วย eab_pages_enable_table_cards() ก่อนพิมพ์เนื้อหา
 */
function eab_pages_stack_tables( $html ) {
	if ( false === stripos( (string) $html, '<table' ) ) {
		return $html;
	}
	$html = preg_replace_callback(
		'#<table\b[^>]*\bdata-table\b[^>]*>.*?</table>#is',
		'eab_pages_stack_table_cb',
		(string) $html
	);
	return preg_replace( '#<div class="table-wrap">#', '<div class="table-wrap table-wrap--stack table-wrap--cards">', $html );
}

function eab_pages_stack_table_cb( $match ) {
	$table = $match[0];
	if ( ! preg_match( '#<thead\b[^>]*>(.*?)</thead>#is', $table, $head ) ) {
		return $table;
	}
	preg_match_all( '#<th\b[^>]*>(.*?)</th>#is', $head[1], $cells );
	$labels = array();
	foreach ( $cells[1] as $cell ) {
		$labels[] = trim( wp_strip_all_tags( $cell ) );
	}
	if ( ! $labels ) {
		return $table;
	}
	return preg_replace_callback(
		'#<tr\b([^>]*)>(.*?)</tr>#is',
		function ( $row ) use ( $labels ) {
			$col   = 0;
			$inner = preg_replace_callback(
				'#<td\b([^>]*)>#i',
				function ( $td ) use ( &$col, $labels ) {
					$label = isset( $labels[ $col ] ) ? $labels[ $col ] : '';
					$col++;
					if ( '' === $label || false !== stripos( $td[1], 'data-label' ) ) {
						return $td[0];
					}
					return '<td' . $td[1] . ' data-label="' . esc_attr( $label ) . '">';
				},
				$row[2]
			);
			return '<tr' . $row[1] . '>' . $inner . '</tr>';
		},
		$table
	);
}

function eab_pages_enable_table_cards() {
	static $done = false;
	if ( ! $done ) {
		add_filter( 'the_content', 'eab_pages_stack_tables', 30 );
		$done = true;
	}
}

/* ==============================================================
 * หัวเพจหน้าย่อย (Glass Sky)
 * แผงกระจก: breadcrumb · kicker อังกฤษ · H1 กรมท่า (คำเน้นสีไล่) · คำโปรย · ปุ่ม · ภาพในกรอบ squircle ด้านขวา
 * ============================================================== */

/**
 * H1 ที่ escape แล้ว · ห่อวลี $phrase (ครั้งแรกที่พบในชื่อ) ด้วย span.grad-word · ไม่พบ = ชื่อธรรมดา
 */
function eab_pages_grad_title( $title, $phrase = '' ) {
	$title  = (string) $title;
	$phrase = trim( (string) $phrase );
	$pos    = '' !== $phrase ? mb_strpos( $title, $phrase ) : false;
	if ( false === $pos ) {
		return eab_text( $title );
	}
	$before = mb_substr( $title, 0, $pos );
	$after  = mb_substr( $title, $pos + mb_strlen( $phrase ) );
	return eab_text( $before ) . '<span class="grad-word">' . eab_text( $phrase ) . '</span>' . eab_text( $after );
}

/**
 * ป้ายเล็กเหนือหัวข้อ: อังกฤษ = .kicker (ตัวพิมพ์ใหญ่ เว้นระยะ) · มีอักษรไทย = .pg-label (ไม่เว้นระยะตัวอักษร)
 */
function eab_pages_kicker( $text, $class = '' ) {
	$text = trim( (string) $text );
	if ( '' === $text || ! eab_show_kickers() ) {
		return '';
	}
	$base = preg_match( '/[\x{0E00}-\x{0E7F}]/u', $text ) ? 'pg-label' : 'kicker';
	return '<span class="' . esc_attr( trim( $base . ' ' . $class ) ) . '">' . esc_html( $text ) . '</span>';
}

/**
 * ภาพประกอบของธีม (SVG ตกแต่ง ไม่มีตัวเลขหรือข้อความ) สำหรับกรอบด้านขวาของหัวเพจ
 *
 * @param string $type backtest | forward | pricing | risk | articles
 */
function eab_pages_illus( $type ) {
	$svg = array(
		'backtest' => '<defs><linearGradient id="pgi-bt" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#1A6DC2" stop-opacity=".28"/><stop offset="1" stop-color="#1A6DC2" stop-opacity="0"/></linearGradient></defs>'
			. '<rect x="14" y="14" width="332" height="252" rx="26" fill="#FFFFFF" stroke="#DCE8F7"/>'
			. '<circle cx="42" cy="42" r="5" fill="#CFE3FB"/><circle cx="58" cy="42" r="5" fill="#CFE3FB"/><circle cx="74" cy="42" r="5" fill="#FFEA99"/>'
			. '<rect x="246" y="36" width="76" height="12" rx="6" fill="#EEF4FC"/>'
			. '<path d="M36 96H324M36 136H324M36 176H324" stroke="#EEF4FC" stroke-width="1.5"/>'
			. '<path d="M36 196C64 190 80 172 104 174S140 160 164 150 204 152 226 132 268 112 290 100 314 82 324 76V206H36Z" fill="url(#pgi-bt)"/>'
			. '<path d="M36 196C64 190 80 172 104 174S140 160 164 150 204 152 226 132 268 112 290 100 314 82 324 76" fill="none" stroke="#0B5CAD" stroke-width="3" stroke-linecap="round"/>'
			. '<g fill="#FDD023" stroke="#FFFFFF" stroke-width="3"><circle cx="104" cy="174" r="6"/><circle cx="164" cy="150" r="6"/><circle cx="226" cy="132" r="6"/><circle cx="290" cy="100" r="6"/></g>'
			. '<circle cx="46" cy="234" r="13" fill="#0A2E66"/><path d="M42 228v12l10-6z" fill="#FFE27A"/>'
			. '<rect x="70" y="229" width="252" height="10" rx="5" fill="#EEF4FC"/><rect x="70" y="229" width="158" height="10" rx="5" fill="#CFE3FB"/>'
			. '<circle cx="228" cy="234" r="9" fill="#FDD023" stroke="#FFFFFF" stroke-width="3"/>',
		'forward'  => '<defs><linearGradient id="pgi-fw" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#123B7E"/><stop offset="1" stop-color="#011D3B"/></linearGradient><linearGradient id="pgi-fw2" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#FDD023" stop-opacity=".34"/><stop offset="1" stop-color="#FDD023" stop-opacity="0"/></linearGradient></defs>'
			. '<rect x="14" y="14" width="332" height="252" rx="26" fill="url(#pgi-fw)"/>'
			. '<circle cx="42" cy="42" r="11" fill="#34D399" opacity=".22"/><circle cx="42" cy="42" r="5" fill="#34D399"/>'
			. '<rect x="62" y="36" width="70" height="12" rx="6" fill="#FFFFFF" opacity=".16"/><rect x="258" y="34" width="64" height="16" rx="8" fill="#FFFFFF" opacity=".1"/>'
			. '<path d="M36 96H324M36 136H324M36 176H324" stroke="#FFFFFF" stroke-opacity=".07" stroke-width="1.5"/>'
			. '<path d="M36 190C60 184 76 166 98 170S132 150 156 154 192 132 214 128 234 118 240 112V204H36Z" fill="url(#pgi-fw2)"/>'
			. '<path d="M36 190C60 184 76 166 98 170S132 150 156 154 192 132 214 128 234 118 240 112" fill="none" stroke="#FDD023" stroke-width="3" stroke-linecap="round"/>'
			. '<path d="M240 112C262 104 280 112 300 96S318 86 324 82" fill="none" stroke="#FFFFFF" stroke-opacity=".5" stroke-width="2.5" stroke-dasharray="6 7" stroke-linecap="round"/>'
			. '<path d="M240 70V204" stroke="#FFFFFF" stroke-opacity=".25" stroke-dasharray="4 6"/>'
			. '<circle cx="240" cy="112" r="8" fill="#FFFFFF" stroke="#FDD023" stroke-width="3"/>'
			. '<g fill="#FFFFFF" fill-opacity=".08"><rect x="36" y="220" width="88" height="28" rx="10"/><rect x="136" y="220" width="88" height="28" rx="10"/><rect x="236" y="220" width="88" height="28" rx="10"/></g>'
			. '<g fill="#A9C3E6" fill-opacity=".55"><rect x="48" y="231" width="44" height="6" rx="3"/><rect x="148" y="231" width="56" height="6" rx="3"/><rect x="248" y="231" width="36" height="6" rx="3"/></g>',
		'pricing'  => '<defs><linearGradient id="pgi-pr" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#FFE27A"/><stop offset=".55" stop-color="#FDD023"/><stop offset="1" stop-color="#C29A00"/></linearGradient></defs>'
			. '<g fill="#FFFFFF" stroke="#DCE8F7"><rect x="18" y="80" width="104" height="168" rx="20"/><rect x="238" y="80" width="104" height="168" rx="20"/></g>'
			. '<rect x="124" y="44" width="112" height="216" rx="22" fill="#FFFFFF" stroke="url(#pgi-pr)" stroke-width="2.5"/>'
			. '<rect x="150" y="34" width="60" height="20" rx="10" fill="url(#pgi-pr)"/>'
			. '<g fill="#0A2E66"><rect x="34" y="100" width="44" height="9" rx="4.5"/><rect x="142" y="72" width="54" height="10" rx="5"/><rect x="254" y="100" width="44" height="9" rx="4.5"/></g>'
			. '<g fill="#E2EDFB"><rect x="34" y="118" width="70" height="7" rx="3.5"/><rect x="142" y="92" width="76" height="7" rx="3.5"/><rect x="254" y="118" width="70" height="7" rx="3.5"/></g>'
			. '<g fill="#FFEA99"><circle cx="40" cy="152" r="5"/><circle cx="40" cy="172" r="5"/><circle cx="40" cy="192" r="5"/><circle cx="148" cy="132" r="5.5"/><circle cx="148" cy="154" r="5.5"/><circle cx="148" cy="176" r="5.5"/><circle cx="148" cy="198" r="5.5"/><circle cx="260" cy="152" r="5"/><circle cx="260" cy="172" r="5"/><circle cx="260" cy="192" r="5"/></g>'
			. '<g fill="#EEF4FC"><rect x="52" y="149" width="52" height="6" rx="3"/><rect x="52" y="169" width="44" height="6" rx="3"/><rect x="52" y="189" width="50" height="6" rx="3"/><rect x="161" y="129" width="56" height="6" rx="3"/><rect x="161" y="151" width="48" height="6" rx="3"/><rect x="161" y="173" width="54" height="6" rx="3"/><rect x="161" y="195" width="44" height="6" rx="3"/><rect x="272" y="149" width="52" height="6" rx="3"/><rect x="272" y="169" width="44" height="6" rx="3"/><rect x="272" y="189" width="50" height="6" rx="3"/></g>'
			. '<rect x="34" y="216" width="72" height="18" rx="9" fill="#0A2E66"/><rect x="142" y="226" width="76" height="20" rx="10" fill="url(#pgi-pr)"/><rect x="254" y="216" width="72" height="18" rx="9" fill="#0A2E66"/>',
		'risk'     => '<defs><linearGradient id="pgi-rk" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#123B7E"/><stop offset="1" stop-color="#011D3B"/></linearGradient></defs>'
			. '<rect x="14" y="14" width="332" height="252" rx="26" fill="#FFFFFF" stroke="#DCE8F7"/>'
			. '<g fill="none" stroke-width="20"><path d="M66 190A114 114 0 0 1 92 118" stroke="#CFE3FB"/><path d="M100 108A114 114 0 0 1 172 76" stroke="#8DB8E8"/><path d="M188 76A114 114 0 0 1 260 108" stroke="#FFE27A"/><path d="M268 118A114 114 0 0 1 294 190" stroke="#E6B800"/></g>'
			. '<path d="M180 190 238 128" stroke="#0A2E66" stroke-width="5" stroke-linecap="round"/><circle cx="180" cy="190" r="11" fill="#0A2E66"/>'
			. '<path d="M180 148 150 160v24c0 22 13 39 30 44 17-5 30-22 30-44v-24z" fill="url(#pgi-rk)" transform="translate(0 36)"/>'
			. '<path d="m167 216 9 9 18-19" fill="none" stroke="#FFE27A" stroke-width="4.5" stroke-linecap="round" stroke-linejoin="round"/>'
			. '<rect x="36" y="36" width="64" height="12" rx="6" fill="#EEF4FC"/>',
		'articles' => '<defs><linearGradient id="pgi-ar" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#E9F3FF"/><stop offset="1" stop-color="#CFE3FB"/></linearGradient></defs>'
			. '<rect x="64" y="22" width="232" height="226" rx="22" fill="#FFFFFF" stroke="#DCE8F7" transform="rotate(-6 180 135)"/>'
			. '<rect x="44" y="34" width="248" height="232" rx="22" fill="#FFFFFF" stroke="#DCE8F7"/>'
			. '<rect x="58" y="48" width="220" height="104" rx="14" fill="url(#pgi-ar)"/>'
			. '<path d="M58 132c42-26 86-10 120-28s62-26 100-22v52a14 14 0 0 1-14 14H72a14 14 0 0 1-14-14z" fill="#FFFFFF" fill-opacity=".7"/>'
			. '<path d="M84 120c34-22 70-14 100-28s50-18 76-16" fill="none" stroke="#FDD023" stroke-width="3" stroke-linecap="round"/>'
			. '<rect x="58" y="166" width="62" height="12" rx="6" fill="#FFEA99"/>'
			. '<rect x="58" y="188" width="196" height="11" rx="5.5" fill="#0A2E66"/>'
			. '<g fill="#E2EDFB"><rect x="58" y="210" width="214" height="7" rx="3.5"/><rect x="58" y="226" width="160" height="7" rx="3.5"/></g>'
			. '<circle cx="262" cy="236" r="14" fill="#EEF4FC"/><path d="m257 241 10-10m-7 0h7v7" fill="none" stroke="#0A2E66" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>',
	);
	if ( ! isset( $svg[ $type ] ) ) {
		return '';
	}
	return '<svg class="pg-illus pg-illus--' . esc_attr( $type ) . '" viewBox="0 0 360 280" aria-hidden="true" focusable="false">' . $svg[ $type ] . '</svg>';
}

/**
 * ภาพด้านขวาของหัวเพจ: รูปจาก Customizer ({key}_hero_img) → ภาพประกอบของธีม → ไม่มี
 *
 * @param string $key   prefix ของคีย์รูป (เช่น 'backtest') · '' = ไม่มีช่องรูป
 * @param string $illus ชนิดภาพประกอบ (ดู eab_pages_illus)
 */
function eab_pages_hero_media( $key, $illus = '' ) {
	$src = '' !== $key ? eab_theme_asset_url( eab_mod( $key . '_hero_img' ) ) : '';
	if ( '' !== $src ) {
		$info = eab_media_info( $src, 960, 720 );
		return sprintf(
			'<div class="pg-hero-media pg-hero-media--img"><div class="pg-squircle"><img src="%1$s" alt="" width="%2$s" height="%3$s" decoding="async"></div></div>',
			esc_url( $src ),
			esc_attr( (string) $info['width'] ),
			esc_attr( (string) $info['height'] )
		);
	}
	if ( '' === $illus || ! eab_mod( 'pages_hero_illus' ) ) {
		return '';
	}
	return '<div class="pg-hero-media pg-hero-media--illus" aria-hidden="true"><div class="pg-squircle">' . eab_pages_illus( $illus ) . '</div></div>';
}

/**
 * หัวเพจ · ทุกช่อง HTML (chip / meta / actions / after / media) ต้อง escape มาแล้ว
 *
 * @param array $args {
 *     @type string      $kicker  ป้ายเล็กเหนือ H1
 *     @type string      $title   ข้อความ H1 (ข้อความล้วน)
 *     @type string|null $crumb   ชื่อหน้าใน breadcrumb (null = ใช้ $title)
 *     @type string      $grad    วลีใน $title ที่เน้นสีไล่
 *     @type string      $lead    คำโปรยใต้ H1 (ข้อความล้วน)
 *     @type string      $chip    HTML เหนือ H1 (เช่น หมวดบทความ)
 *     @type string      $meta    HTML ใต้คำโปรย (วันที่ เวลาอ่าน)
 *     @type string      $actions HTML ปุ่ม
 *     @type string      $after   HTML ท้ายคอลัมน์ข้อความ (หมวด / ช่องค้นหา)
 *     @type string      $media   HTML ภาพด้านขวา (eab_pages_hero_media หรือรูปปกบทความ)
 *     @type string      $size    '' | 'compact'
 *     @type string      $tag     'section' | 'header'
 *     @type string      $class   คลาสเพิ่ม
 * }
 */
function eab_pages_hero( $args ) {
	$a = wp_parse_args(
		$args,
		array(
			'kicker'  => '',
			'title'   => '',
			'crumb'   => null,
			'grad'    => '',
			'lead'    => '',
			'chip'    => '',
			'meta'    => '',
			'actions' => '',
			'after'   => '',
			'media'   => '',
			'size'    => '',
			'tag'     => 'section',
			'class'   => '',
		)
	);
	$tag   = 'header' === $a['tag'] ? 'header' : 'section';
	$class = 'pg-hero';
	$class .= 'compact' === $a['size'] ? ' pg-hero--compact' : '';
	$class .= '' === $a['media'] ? ' pg-hero--solo' : '';
	$class .= '' !== $a['class'] ? ' ' . $a['class'] : '';
	$title = wp_strip_all_tags( (string) $a['title'] );
	?>
	<<?php echo esc_attr( $tag ); ?> class="<?php echo esc_attr( $class ); ?>">
		<div class="container">
			<div class="pg-hero-panel">
				<span class="orb pg-hero-orb" aria-hidden="true"></span>
				<div class="pg-hero-text">
					<?php eab_pages_crumbs( null === $a['crumb'] ? $title : (string) $a['crumb'] ); ?>
					<?php echo $a['chip']; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped by caller ?>
					<?php echo eab_pages_kicker( $a['kicker'], 'pg-hero-kicker' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?>
					<h1 class="pg-hero-title"><?php echo eab_pages_grad_title( $title, $a['grad'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></h1>
					<?php if ( '' !== trim( (string) $a['lead'] ) ) : ?>
						<p class="pg-hero-lead"><?php echo eab_text( $a['lead'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></p>
					<?php endif; ?>
					<?php if ( '' !== trim( (string) $a['meta'] ) ) : ?>
						<div class="pg-hero-meta"><?php echo $a['meta']; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped by caller ?></div>
					<?php endif; ?>
					<?php if ( '' !== trim( (string) $a['actions'] ) ) : ?>
						<div class="pg-hero-actions"><?php echo $a['actions']; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped by caller ?></div>
					<?php endif; ?>
					<?php echo $a['after']; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped by caller ?>
				</div>
				<?php echo $a['media']; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped by caller ?>
			</div>
		</div>
	</<?php echo esc_attr( $tag ); ?>>
	<?php
}

/**
 * เก็บผลของฟังก์ชันที่พิมพ์ HTML เป็นสตริง (ใช้ประกอบปุ่ม/ฟอร์มให้หัวเพจ)
 */
function eab_pages_capture( $callback, ...$args ) {
	ob_start();
	call_user_func_array( $callback, $args );
	return (string) ob_get_clean();
}

/* ==============================================================
 * เนื้อหายาว · การ์ดขาวอ่านง่าย + สารบัญ (จอคอม: การ์ดกระจกติดข้าง · มือถือ: details พับได้)
 * ============================================================== */

/**
 * รายการสารบัญจาก $GLOBALS['eab_toc'] · เฉพาะระดับที่ต้องการ · ตัดหัวข้อของกล่อง .related-links (ไม่มีเลขในเนื้อหา)
 */
function eab_pages_toc_items( $toc, $content = '', $levels = array( 2 ) ) {
	$skip = array();
	if ( '' !== $content && preg_match_all( '#<div\b[^>]*\bclass="[^"]*\brelated-links\b[^"]*"[^>]*>\s*<h2\b[^>]*\bid="([^"]+)"#i', (string) $content, $m ) ) {
		$skip = $m[1];
	}
	$out = array();
	foreach ( (array) $toc as $item ) {
		if ( empty( $item['id'] ) || '' === trim( (string) $item['text'] ) ) {
			continue;
		}
		if ( ! in_array( (int) $item['level'], $levels, true ) || in_array( $item['id'], $skip, true ) ) {
			continue;
		}
		$out[] = $item;
	}
	return $out;
}

/**
 * หัวข้อ H2 มีเลขในข้อความอยู่แล้วหรือไม่ (เช่น "1. ใครเป็นผู้ดูแลข้อมูล" ในเอกสารกฎหมาย)
 * ถ้าครึ่งหนึ่งขึ้นไปมีเลข: ไม่ใส่เลขทองอัตโนมัติซ้ำ (ทั้งในเนื้อหาและสารบัญ)
 */
function eab_pages_self_numbered( $items ) {
	$h2  = 0;
	$num = 0;
	foreach ( (array) $items as $item ) {
		if ( 2 !== (int) $item['level'] ) {
			continue;
		}
		$h2++;
		if ( preg_match( '/^\s*[0-9\x{0E50}-\x{0E59}]+(?:\.[0-9]+)*[.)]?\s/u', (string) $item['text'] ) ) {
			$num++;
		}
	}
	return $h2 > 0 && $num * 2 >= $h2;
}

/**
 * สารบัญ · พิมพ์รายการสองชุด: details (มือถือ ปิดไว้ก่อน) แล้วตามด้วยการ์ดติดข้าง (จอคอม)
 * ชุดจอคอมอยู่หลังสุดใน DOM เพื่อให้ main.js ไฮไลต์หัวข้อที่กำลังอ่าน (.longform-toc a) ที่ชุดนี้
 */
function eab_pages_toc( $items, $label = '' ) {
	if ( ! $items ) {
		return;
	}
	$label = '' !== trim( (string) $label ) ? (string) $label : (string) eab_mod( 'doc_toc_label' );
	$count = 0;
	foreach ( $items as $item ) {
		if ( 2 === (int) $item['level'] ) {
			$count++;
		}
	}
	ob_start();
	?>
	<ol class="pg-toc-list">
		<?php foreach ( $items as $eab_item ) : ?>
			<li class="toc-l<?php echo esc_attr( (string) (int) $eab_item['level'] ); ?>"><a href="#<?php echo esc_attr( $eab_item['id'] ); ?>"><?php echo esc_html( $eab_item['text'] ); ?></a></li>
		<?php endforeach; ?>
	</ol>
	<?php
	$list = (string) ob_get_clean();
	?>
	<nav class="longform-toc pg-toc<?php echo eab_pages_self_numbered( $items ) ? ' pg-toc--plain' : ''; ?>" aria-label="<?php echo esc_attr( $label ); ?>">
		<details class="pg-toc-mob">
			<summary>
				<?php echo eab_icon( 'layout', 'icon icon-sm' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<span class="pg-toc-label"><?php echo esc_html( $label ); ?></span>
				<span class="pg-toc-count"><?php echo esc_html( number_format_i18n( $count ) ); ?></span>
			</summary>
			<?php echo $list; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above ?>
		</details>
		<div class="pg-toc-desk">
			<p class="pg-toc-title"><?php echo esc_html( $label ); ?></p>
			<?php echo $list; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above ?>
		</div>
	</nav>
	<?php
}

/**
 * เนื้อหายาวจาก editor (แทน eab_page_longform ในเทมเพลตของโมดูลนี้)
 *
 * @param array $args {
 *     @type string|null $content    HTML ที่ผ่าน the_content แล้ว (null = ประมวลผลเนื้อหาเพจปัจจุบัน)
 *     @type array       $toc        รายการหัวข้อ (ใช้คู่กับ $content)
 *     @type array       $levels     ระดับหัวข้อในสารบัญ
 *     @type int         $min        จำนวนหัวข้อขั้นต่ำที่แสดงสารบัญ
 *     @type string      $label      หัวกล่องสารบัญ
 *     @type string      $class      คลาสเพิ่มของ section
 *     @type string      $body_class คลาสเพิ่มของการ์ดเนื้อหา
 *     @type string      $before     HTML ก่อนเนื้อหา (escape แล้ว)
 *     @type string      $after      HTML ท้ายเนื้อหา (escape แล้ว)
 * }
 */
function eab_pages_longform( $args = array() ) {
	$a = wp_parse_args(
		$args,
		array(
			'content'    => null,
			'toc'        => array(),
			'levels'     => array( 2 ),
			'min'        => 3,
			'label'      => '',
			'class'      => '',
			'body_class' => '',
			'before'     => '',
			'after'      => '',
		)
	);
	$content = $a['content'];
	$toc     = $a['toc'];
	if ( null === $content ) {
		$content = str_replace( ']]>', ']]&gt;', (string) apply_filters( 'the_content', get_the_content() ) );
		$toc     = isset( $GLOBALS['eab_toc'] ) ? $GLOBALS['eab_toc'] : array();
	}
	if ( '' === trim( wp_strip_all_tags( (string) $content ) ) && '' === trim( (string) $a['before'] ) ) {
		return;
	}
	$items = eab_pages_toc_items( $toc, $content, (array) $a['levels'] );
	$show  = count( $items ) >= (int) $a['min'];
	if ( eab_pages_self_numbered( eab_pages_toc_items( $toc, $content, array( 2 ) ) ) ) {
		$a['body_class'] = trim( $a['body_class'] . ' lf-self-num' );
	}
	?>
	<section class="pg-sec longform pg-longform<?php echo '' !== $a['class'] ? esc_attr( ' ' . $a['class'] ) : ''; ?>">
		<div class="container">
			<div class="longform-layout<?php echo $show ? '' : ' longform-layout--solo'; ?>">
				<?php
				if ( $show ) {
					eab_pages_toc( $items, $a['label'] );
				}
				?>
				<div class="entry-content guide-content<?php echo '' !== $a['body_class'] ? esc_attr( ' ' . $a['body_class'] ) : ''; ?>">
					<?php echo $a['before']; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped by caller ?>
					<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput -- the_content ?>
					<?php echo $a['after']; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped by caller ?>
				</div>
			</div>
		</div>
	</section>
	<?php
}

/* ==============================================================
 * ส่วนประกอบของหน้าทดสอบ (Backtest / Forward)
 * ============================================================== */

/**
 * จำนวนคอลัมน์ของตารางสถิติ (4 เมื่อหารด้วย 4 ลงตัว ไม่งั้น 3)
 */
function eab_pages_stat_cols( $n ) {
	return $n >= 4 && 0 === $n % 4 ? 4 : 3;
}

/**
 * การ์ดสถิติกระจก (แสดงเฉพาะค่าที่กรอกจริง) หรือบล็อก "ยังไม่มีผลที่เผยแพร่"
 *
 * @param string $prefix 'bt' | 'fw'
 * @param int    $count  จำนวนช่อง
 * @param string $type   'backtest' | 'forward'
 */
function eab_pages_stats( $prefix, $count, $type ) {
	$rows   = array();
	$labels = array();
	for ( $i = 1; $i <= $count; $i++ ) {
		$label = trim( (string) eab_mod( $prefix . '_stat' . $i . '_label' ) );
		$value = trim( (string) eab_mod( $prefix . '_stat' . $i . '_value' ) );
		if ( '' === $label ) {
			continue;
		}
		$labels[] = $label;
		if ( eab_is_placeholder( $value ) ) {
			continue;
		}
		$rows[] = array( $label, $value );
	}
	if ( ! $rows ) {
		eab_pages_pending( $type, $labels );
		return;
	}
	echo '<dl class="stat-grid stat-grid--' . esc_attr( (string) eab_pages_stat_cols( count( $rows ) ) ) . '">';
	foreach ( $rows as $row ) {
		echo '<div class="stat-card reveal"><dt class="stat-card-label">' . esc_html( $row[0] ) . '</dt><dd class="stat-card-value">' . esc_html( $row[1] ) . '</dd></div>';
	}
	echo '</dl>';
}

/**
 * บล็อก "ยังไม่มีผลที่เผยแพร่" · ไม่มีตัวเลขสมมติ · ช่องสถิติแสดงเฉพาะหัวข้อ + ป้ายรอผลจริง
 *
 * @param string $type   'backtest' | 'forward'
 * @param array  $labels หัวข้อสถิติที่ตั้งไว้
 */
function eab_pages_pending( $type, $labels = array() ) {
	$label = trim( (string) eab_mod( 'results_pending_label' ) );
	$wait  = trim( (string) eab_mod( 'results_pending_value' ) );
	?>
	<div class="results-pending">
		<div class="results-pending-head">
			<span class="tile tile--gold" aria-hidden="true"><?php echo eab_icon( 'backtest' === $type ? 'candles' : 'pulse' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
			<div>
				<?php echo eab_pages_kicker( '' !== $label ? $label : eab_mod( 'backtest' === $type ? 'backtest_kicker' : 'forward_kicker' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?>
				<h2><?php echo eab_text( eab_mod( 'results_pending_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></h2>
				<p><?php echo esc_html( eab_mod( 'results_pending_text' ) ); ?></p>
			</div>
		</div>
		<?php if ( $labels ) : ?>
			<ul class="stat-grid stat-grid--pending stat-grid--<?php echo esc_attr( (string) eab_pages_stat_cols( count( $labels ) ) ); ?>">
				<?php foreach ( $labels as $eab_label ) : ?>
					<li class="stat-card is-pending">
						<span class="stat-card-label"><?php echo esc_html( $eab_label ); ?></span>
						<?php if ( '' !== $wait ) : ?>
							<span class="stat-card-wait"><?php echo esc_html( $wait ); ?></span>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * ส่วนบนของหน้าทดสอบ (แผงกระจก): เกริ่นนำ → สถิติ/สถานะ → ลิงก์ผลที่ตรวจสอบได้ → ภาพประกอบ → หมายเหตุ + คำเตือน
 */
function eab_pages_tests_top( $type ) {
	$is_bt = 'backtest' === $type;
	$key   = $is_bt ? 'backtest' : 'forward';
	$intro = eab_mod( $key . '_intro' );
	$note  = eab_mod( $key . '_note' );
	?>
	<section class="pg-sec tests-top">
		<div class="container">
			<div class="glass-panel pg-panel tests-panel">
				<?php if ( eab_pages_has( $intro ) ) : ?>
					<p class="tests-lead"><?php echo eab_text( $intro ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></p>
				<?php endif; ?>

				<?php
				/* Forward Test เป็นคู่มือล้วน: ไม่มีช่องผล ลิงก์ผล หรือรูปผล (EA BULL ไม่มีผล Forward Test ของตัวเอง · 12 ต.ค. 2026) */
				if ( $is_bt ) {
					eab_pages_stats( 'bt', 8, $type );
					eab_media_slot(
						$key,
						array(
							'width'  => 1280,
							'height' => 720,
							'class'  => 'tests-figure',
						)
					);
				}
				?>

				<div class="tests-notes<?php echo eab_pages_has( $note ) ? '' : ' tests-notes--solo'; ?>">
					<?php if ( eab_pages_has( $note ) ) : ?>
						<p class="pg-note tests-note"><span class="tile tile--sky" aria-hidden="true"><?php echo eab_icon( 'flask' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span><span><?php echo esc_html( $note ); ?></span></p>
					<?php endif; ?>
					<?php /* คำเตือน · ไม่ใช้ .reveal เพื่อให้เห็นทันทีแม้ JS ไม่ทำงาน */ ?>
					<div class="pg-warn tests-disclaimer">
						<span class="tile tile--gold" aria-hidden="true"><?php echo eab_icon( 'warn' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<p><?php echo esc_html( eab_mod( $key . '_disclaimer' ) ); ?></p>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php
}

/* ==============================================================
 * ส่วนประกอบของหน้า Pricing
 * ============================================================== */

/**
 * ราคาเป็นตัวเลขจริงหรือคำว่าฟรีหรือยัง (X,XXX / ระบุราคา = ยังไม่ใช่)
 */
function eab_pages_price_ready( $price ) {
	return eab_pages_has( $price ) && ( (bool) preg_match( '/\d/', (string) $price ) || eab_is_free_price( $price ) );
}

/**
 * ปุ่มบนการ์ดแพ็กเกจ: LINE → หน้า /go/ (ถ้าเผยแพร่แล้ว) → ไม่แสดงปุ่ม · data-line-pkg = ชื่อแพ็กเกจ (นับคลิกแยกแพ็กเกจ)
 */
function eab_pages_package_button( $name, $featured ) {
	$target = eab_contact_target();
	if ( '' === $target['url'] ) {
		if ( current_user_can( 'edit_theme_options' ) ) {
			echo '<span class="admin-hint">ปุ่มถูกซ่อน: ใส่ลิงก์ LINE ที่ ปรับแต่ง → 1) ช่องทางติดต่อ หรือเผยแพร่หน้า /go/</span>';
		}
		return;
	}
	printf(
		'<a class="btn %1$s btn-block btn-contact plan-btn price-btn" href="%2$s"%3$s data-line-pos="pricing" data-line-pkg="%4$s">%5$s<span>%6$s</span>%7$s</a>',
		$featured ? 'btn-fire' : 'btn-dark',
		esc_url( $target['url'] ),
		$target['is_line'] ? ' target="_blank" rel="noopener"' : '',
		esc_attr( $name ),
		eab_icon( $target['is_line'] ? 'line' : 'chat', 'icon btn-contact-ic' ), // phpcs:ignore WordPress.Security.EscapeOutput
		esc_html( eab_mod( 'pricing_btn_text' ) ),
		eab_icon( 'arrow', 'icon btn-contact-ar' ) // phpcs:ignore WordPress.Security.EscapeOutput
	);
}

/**
 * หัว section (kicker + H2 + คำอธิบาย)
 */
function eab_pages_sec_head( $kicker, $title, $sub = '', $class = '' ) {
	if ( '' === trim( (string) $title ) ) {
		return;
	}
	?>
	<div class="pg-head<?php echo '' !== $class ? esc_attr( ' ' . $class ) : ''; ?>">
		<?php echo eab_pages_kicker( $kicker ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?>
		<h2><?php echo eab_text( $title ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></h2>
		<?php if ( eab_pages_has( $sub ) ) : ?>
			<p><?php echo eab_text( $sub ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></p>
		<?php endif; ?>
	</div>
	<?php
}

/* ==============================================================
 * รายการคู่มือพร้อมคำอธิบายหนึ่งบรรทัด (เฉพาะเพจที่เผยแพร่แล้ว)
 * ============================================================== */

function eab_pages_guide_items() {
	$pages = function_exists( 'eab_site_pages' ) ? eab_site_pages() : array();
	$items = array();
	foreach ( eab_pages_pairs( eab_mod( 'articles_guides_items' ) ) as $pair ) {
		$slug = sanitize_title( $pair[0] );
		$url  = function_exists( 'eab_published_page_url' ) ? eab_published_page_url( $slug ) : '';
		if ( '' === $url ) {
			continue;
		}
		$label = $slug;
		if ( isset( $pages[ $slug ] ) ) {
			$label = ! empty( $pages[ $slug ]['menu'] ) ? $pages[ $slug ]['menu'] : $pages[ $slug ]['title'];
		}
		$items[] = array(
			'label' => $label,
			'url'   => $url,
			'desc'  => $pair[1],
		);
	}
	return $items;
}

/**
 * การ์ดหมวดบทความบนหน้า /articles/ (รูปแบบเดียวกับการ์ดคู่มือ) · หมวดที่ยังไม่มีบทความถูกข้าม
 * ชื่อการ์ด = ชื่อหมวด · จำนวนบทความ · คำอธิบาย = คำอธิบายของหมวดใน WP (สำรอง: ค่าตั้งต้นใน setup.php)
 */
function eab_pages_pillar_items() {
	$items    = array();
	$fallback = function_exists( 'eab_article_category_descriptions' ) ? eab_article_category_descriptions() : array();
	foreach ( eab_lines( eab_mod( 'articles_pillars_order' ) ) as $slug ) {
		$term = get_term_by( 'slug', sanitize_title( $slug ), 'category' );
		if ( ! $term || is_wp_error( $term ) || ! (int) $term->count ) {
			continue;
		}
		$desc = trim( wp_strip_all_tags( (string) $term->description ) );
		if ( '' === $desc && isset( $fallback[ $term->slug ] ) ) {
			$desc = $fallback[ $term->slug ];
		}
		$items[] = array(
			'label' => $term->name . ' · ' . eab_pages_count_text( 'articles_count_text', (int) $term->count ),
			'url'   => get_category_link( $term ),
			'desc'  => $desc,
		);
	}
	return $items;
}

/**
 * การ์ดกระจกของคู่มือ (หน้ารวมบทความ / ค้นหาไม่พบ / 404)
 */
function eab_pages_guide_grid( $items ) {
	if ( ! $items ) {
		return;
	}
	?>
	<ol class="guide-cards">
		<?php foreach ( $items as $eab_i => $eab_item ) : ?>
			<li class="reveal">
				<a class="guide-card" href="<?php echo esc_url( $eab_item['url'] ); ?>">
					<span class="guide-card-idx" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $eab_i + 1 ) ); ?></span>
					<span class="guide-card-name"><?php echo eab_text( $eab_item['label'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></span>
					<?php if ( '' !== $eab_item['desc'] ) : ?>
						<span class="guide-card-sub"><?php echo eab_text( $eab_item['desc'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></span>
					<?php endif; ?>
					<span class="guide-card-arrow" aria-hidden="true"><?php echo eab_icon( 'arrow', 'icon icon-sm' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				</a>
			</li>
		<?php endforeach; ?>
	</ol>
	<?php
}

/**
 * ปุ่มโหลดเพิ่ม (แคปซูลกระจก) + ลิงก์แบ่งหน้าเมื่อปิด JavaScript (ใช้ใน index.php / search.php)
 */
function eab_pages_load_more() {
	$query = $GLOBALS['wp_query'];
	if ( (int) $query->max_num_pages <= 1 ) {
		return;
	}
	?>
	<div class="load-more">
		<button type="button" class="btn btn-ghost load-more-btn"
			data-page="<?php echo esc_attr( (string) max( 1, (int) get_query_var( 'paged' ) ) ); ?>"
			data-max="<?php echo esc_attr( (string) (int) $query->max_num_pages ); ?>"
			data-query="<?php echo esc_attr( wp_json_encode( $query->query ) ); ?>">
			<?php echo esc_html( eab_mod( 'articles_load_more' ) ); ?>
		</button>
	</div>
	<noscript>
		<div class="pagination">
			<?php
			echo paginate_links( // phpcs:ignore WordPress.Security.EscapeOutput
				array(
					'prev_text' => '&larr; ' . esc_html( eab_mod( 'articles_prev_label' ) ),
					'next_text' => esc_html( eab_mod( 'articles_next_label' ) ) . ' &rarr;',
				)
			);
			?>
		</div>
	</noscript>
	<?php
}

/**
 * ฟอร์มค้นหาแคปซูลกระจก (404 / หน้าผลการค้นหา)
 */
function eab_pages_search_form( $placeholder, $class = '' ) {
	static $n = 0;
	$n++;
	$id = 'pg-search-' . $n;
	?>
	<form class="pg-search<?php echo '' !== $class ? esc_attr( ' ' . $class ) : ''; ?>" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<label class="sr-only" for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $placeholder ); ?></label>
		<input id="<?php echo esc_attr( $id ); ?>" type="search" name="s" placeholder="<?php echo esc_attr( $placeholder ); ?>" spellcheck="false" autocomplete="off" value="<?php echo esc_attr( get_search_query() ); ?>">
		<button type="submit" aria-label="<?php echo esc_attr( $placeholder ); ?>"><?php echo eab_icon( 'arrow', 'icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></button>
	</form>
	<?php
}

/* ==============================================================
 * บทความเดี่ยว (single.php)
 * ============================================================== */

/**
 * breadcrumb · ใช้ eab_breadcrumbs() ให้ตรงกับ BreadcrumbList schema
 */
function eab_pages_crumbs( $title = '' ) {
	$crumbs = eab_breadcrumbs( $title );
	if ( count( $crumbs ) < 2 ) {
		return;
	}
	$last = count( $crumbs ) - 1;
	?>
	<nav class="crumbs pg-crumbs" aria-label="เส้นทางนำทาง">
		<ol>
			<?php foreach ( $crumbs as $eab_i => $eab_crumb ) : ?>
				<li>
					<?php if ( $eab_crumb['url'] && $eab_i < $last ) : ?>
						<a href="<?php echo esc_url( $eab_crumb['url'] ); ?>"><?php echo esc_html( $eab_crumb['name'] ); ?></a>
					<?php else : ?>
						<span aria-current="page"><?php echo esc_html( $eab_crumb['name'] ); ?></span>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ol>
	</nav>
	<?php
}

/**
 * ลิงก์เพจเกี่ยวกับเรา (เฉพาะเมื่อเผยแพร่แล้ว)
 */
function eab_pages_about_url() {
	return function_exists( 'eab_published_page_url' ) ? (string) eab_published_page_url( 'about' ) : '';
}

/**
 * ข้อมูลวันที่ + เวลาอ่านของบทความ
 *
 * @return array { published, published_iso, modified, modified_iso, minutes }
 */
function eab_pages_article_meta( $post_id = 0 ) {
	$published_iso = (string) get_the_date( 'c', $post_id ? $post_id : null );
	$modified_iso  = (string) get_the_modified_date( 'c', $post_id ? $post_id : null );
	$published     = eab_pages_thai_date( $published_iso );
	$modified      = eab_pages_thai_date( $modified_iso );
	return array(
		'published'     => $published,
		'published_iso' => $published_iso,
		'modified'      => $modified !== $published ? $modified : '',
		'modified_iso'  => $modified_iso,
		'minutes'       => eab_pages_reading_minutes( get_the_content() ),
	);
}
