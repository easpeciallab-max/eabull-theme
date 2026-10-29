<?php
/**
 * EA BULL · โมดูลหน้าแรก (front-page.php) · ค่าเริ่มต้น หมวด Customizer และตัวช่วย
 *
 * หน้าตาตามต้นแบบที่เจ้าของอนุมัติ dev/mockup/_src/pages/home.html (ดีไซน์ Bull Black · docs/design.md)
 * ลำดับบนหน้า: hero → แถบแพลตฟอร์ม → สินค้า (products.php) → โหมด (แท็บ) → สิ่งที่ EA BULL ทำแทนคุณ
 * → 6 ขั้นตอน → ตารางเทียบ → เช็กลิสต์ความพร้อม → FAQ → ความเสี่ยง → บทความล่าสุด (เฉพาะเมื่อมีบทความเผยแพร่)
 * ตัวพิมพ์แต่ละส่วนอยู่ใน inc/modules/homeplus.php · แผงติดต่อท้ายหน้าอยู่ในโมดูล chrome (footer พิมพ์ให้)
 *
 * รูปแบบข้อความในช่อง Customizer (eab_home_inline):
 *   [ข้อความ](/slug/) = ลิงก์ในเว็บ (หน้าที่ยังไม่เผยแพร่จะเป็นข้อความธรรมดา)
 *   {wait} หรือ {wait:ข้อความ} = ป้าย "รอข้อมูลจากเจ้าของ" · {คำ} = ห้ามตัดบรรทัดกลางวลี
 *
 * กฎ: ไม่มีตัวเลขผลเทรด ไม่มีคำรับประกันกำไร ไม่ลดทอนคำเตือนความเสี่ยง · ข้อมูลที่ยังไม่มีใช้ค่าว่าง + eab_wait()
 *
 * @package eabull
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * จำนวนโหมด / จุดเด่น / ขั้นตอน / คำถาม บนหน้าแรก
 */
function eab_home_counts() {
	return array(
		'modes' => 3,
		'rows'  => 4,
		'feat'  => 4,
		'steps' => 6,
		'faq'   => 12,
	);
}

/* ==============================================================
 * ค่าเริ่มต้น (ข้อความจากต้นแบบที่เจ้าของอนุมัติ 29 ต.ค. 2026)
 * ============================================================== */

add_filter( 'eab_defaults', 'eab_home_defaults' );

function eab_home_defaults( $d ) {
	$c = eab_home_counts();
	$h = array(
		/* ---------- Hero ---------- */
		'home_hero_img'        => 'assets/img/brand/eabull-emblem.webp',
		'home_hero_img_alt'    => 'หัววัวทองและแท่งกราฟ สัญลักษณ์ของ EA BULL',
		'home_hero_h1'         => 'ให้ EA BULL {เทรดตามแผน} บน {MetaTrader 5}',
		'home_hero_tag'        => 'แข็งแกร่งด้วยวินัย บุกตลาดอย่างมีแผน',
		'home_hero_lead'       => 'EA BULL คือ Expert Advisor สำหรับ MT5 ที่เปิดและปิดออเดอร์ตามกติกาในระบบ ติดตั้งตามคู่มือทีละขั้น ปล่อยรันบน VPS แล้วดูบัญชีจากแอป MT5 บนมือถือ ถามทุกเรื่องกับทีมงานทาง LINE',
		'home_hero_btn1_text'  => 'เลือก EA ที่เหมาะกับคุณ',
		'home_hero_btn1_url'   => '#products',
		'home_hero_btn2_text'  => 'เริ่มใช้งานใน 6 ขั้นตอน',
		'home_hero_btn2_url'   => '#steps',
		'home_hero_risk'       => '',
		'home_pillars'         => "วินัย | ทำตามกติกาเดิมทุกออเดอร์ | shield\nแผน | รู้ทุนและขนาดไม้ก่อนเริ่ม | target\nระบบ | ติดตั้ง ติดตาม ปรับ เป็นขั้นตอน | cpu",

		/* ---------- แถบแพลตฟอร์มสีเหลือง ---------- */
		'home_show_plat'       => true,
		'home_plat_label'      => 'ใช้งานร่วมกับ',
		'home_plat_items'      => "MetaTrader 5\nWindows\nAndroid\niPhone / iPad\nVPS",

		/* ---------- โหมด (แท็บ) · ข้อมูลโหมดยังไม่มี ---------- */
		'show_home_modes'      => true,
		'home_modes_title'     => 'เลือกโหมดให้ตรงกับทุน',
		'home_modes_sub'       => 'แต่ละโหมดใช้เงินในบัญชีและรับความเสี่ยงไม่เท่ากัน ดูให้ครบทุกแท็บก่อนตัดสินใจ',
		'home_modes_aria'      => 'โหมดของ EA',
		'home_modes_tag_empty' => 'ชื่อโหมดรอข้อมูล',
		'home_modes_text_hint' => 'คำอธิบายว่าโหมดนี้ทำงานอย่างไร เหมาะกับใคร',
		'home_modes_warn'      => '',
		'home_modes_row1'      => 'ระดับความเสี่ยง',
		'home_modes_row2'      => 'เงินในบัญชีที่แนะนำ',
		'home_modes_row3'      => 'ประเภทบัญชีที่ใช้ได้',
		'home_modes_row4'      => 'สิ่งที่ต้องรู้ก่อนเลือก',

		/* ---------- สิ่งที่ EA BULL ทำแทนคุณ (2x2) ---------- */
		'home_feat_title'      => 'สิ่งที่ EA BULL ทำแทนคุณ',
		'home_feat_sub'        => 'งานซ้ำ ๆ ที่กินเวลาหน้าจอ ให้ระบบทำ ส่วนคุณดูภาพรวมและตัดสินใจเรื่องทุน',
		'home_feat1_title'     => 'ส่งคำสั่งตามกติกาที่ตั้งไว้',
		'home_feat1_text'      => 'EA เปิดและปิดออเดอร์ตามเงื่อนไขในระบบ ไม่ต้องนั่งกดเองทุกไม้ และไม่เปลี่ยนแผนกลางทางเพราะอารมณ์',
		'home_feat1_link'      => 'วิธีติดตั้ง EA บน MT5',
		'home_feat1_url'       => '/how-to-install/',
		'home_feat2_title'     => 'ทำงานต่อเนื่องบน VPS',
		'home_feat2_text'      => 'รัน MT5 ไว้บนเครื่อง VPS ที่เปิดอยู่ตลอด EA ทำงานต่อได้แม้คอมที่บ้านปิดหรือเน็ตบ้านหลุด',
		'home_feat2_link'      => 'คู่มือใช้ VPS',
		'home_feat2_url'       => '/vps-windows/',
		'home_feat3_title'     => 'ดูบัญชีจากมือถือได้ทุกที่',
		'home_feat3_text'      => 'เปิดแอป MT5 บนมือถือด้วยเลขบัญชีเดียวกัน แล้วเช็กยอดเงิน ออเดอร์ที่ค้าง และรายการย้อนหลังได้ทุกที่',
		'home_feat3_link'      => 'ติดตั้งและล็อกอิน MT5',
		'home_feat3_url'       => '/mt5-login/',
		'home_feat4_title'     => 'คิดขนาดไม้ก่อนใช้เงินจริง',
		'home_feat4_text'      => 'ใส่ทุนและเงินที่ยอมเสียลงเครื่องคำนวณ ดูว่าขนาด Lot เท่าไรเหมาะกับแผน และถ้าพอร์ตลดลงต้องกำไรคืนแค่ไหน',
		'home_feat4_link'      => 'เครื่องคำนวณ Lot และ Drawdown',
		'home_feat4_url'       => '/tools/',

		/* ---------- 6 ขั้นตอน (รางขั้นตอน) ---------- */
		'home_steps_title'     => 'จากบัญชีแรกถึง EA ทำงาน: 6 ขั้นตอน',
		'home_steps_sub'       => 'ทุกขั้นมีคู่มือละเอียดพร้อมภาพหน้าจอ ติดตรงไหนทักทีมงานได้',
		'home_steps_more_text' => 'ดูทุกลิงก์ในหน้าเดียว',
		'home_steps_more_url'  => '/go/',
		'home_step1_title'     => 'เปิดบัญชีเทรด MT5',
		'home_step1_text'      => 'สมัครกับ Zaurix โบรกเกอร์พันธมิตรของ EA BULL แล้วยืนยันตัวตน',
		'home_step1_link'      => 'คู่มือเปิดบัญชี',
		'home_step1_url'       => '/open-mt5-account/',
		'home_step2_title'     => 'ติดตั้งและล็อกอิน MT5',
		'home_step2_text'      => 'ลง MT5 บนคอมหรือ VPS และในมือถือ ใช้บัญชีเดียวกัน',
		'home_step2_link'      => 'คู่มือ MT5',
		'home_step2_url'       => '/mt5-login/',
		'home_step3_title'     => 'ฝากเงินเข้าบัญชี',
		'home_step3_text'      => 'โอนเงินผ่าน Portal ของ Zaurix แล้วดูยอดในแอป MT5',
		'home_step3_link'      => 'คู่มือฝากเงิน',
		'home_step3_url'       => '/deposit/',
		'home_step4_title'     => 'รับไฟล์ EA',
		'home_step4_text'      => 'ทักทีมงานทาง LINE เพื่อรับไฟล์และขั้นตอนเปิดใช้งาน',
		'home_step4_link'      => 'ทัก LINE',
		'home_step4_url'       => 'line',
		'home_step5_title'     => 'ติดตั้ง EA บน MT5',
		'home_step5_text'      => 'วางไฟล์ในโฟลเดอร์ Experts เปิดสวิตช์ แล้วลากลงกราฟ',
		'home_step5_link'      => 'คู่มือติดตั้ง',
		'home_step5_url'       => '/how-to-install/',
		'home_step6_title'     => 'รันบน VPS',
		'home_step6_text'      => 'ย้าย MT5 ไปเครื่องที่เปิดตลอด ให้ EA ทำงานไม่สะดุด',
		'home_step6_link'      => 'คู่มือ VPS',
		'home_step6_url'       => '/vps-windows/',

		/* ---------- ตารางเทียบ (แถบขาว · คอลัมน์ EA BULL .us) ---------- */
		'show_home_compare'    => true,
		'home_compare_title'   => 'นั่งเฝ้าจอเอง หรือให้ EA ทำตามกติกา',
		'home_compare_sub'     => 'เทียบวิธีทำงานทีละเรื่อง เพื่อให้เห็นว่าอะไรเปลี่ยน และอะไรยังเป็นหน้าที่ของคุณ',
		'home_compare_rows'    => "หัวข้อ | เทรดเองทุกไม้ | ใช้ EA BULL\n"
			. "เวลาที่ต้องใช้ | - ต้องเฝ้าจอช่วงที่ตลาดเคลื่อนไหว | + ตั้งค่าครั้งแรก แล้วตรวจผลเป็นระยะ\n"
			. "การทำตามแผน | - เปลี่ยนไปตามอารมณ์ ความล้า และสิ่งรบกวนรอบตัว | + ใช้เงื่อนไขเดิมทุกครั้ง\n"
			. "ตอนที่คุณไม่ว่างหรือหลับ | - พลาดจังหวะ หรือปิดออเดอร์ไม่ทัน | + ทำงานต่อได้ถ้ารันบน VPS\n"
			. "การส่งคำสั่ง | - ต้องกดเองทุกครั้ง | + ส่งเมื่อเงื่อนไขครบ โดยไม่ต้องรอคนกด\n"
			. "โอกาสขาดทุน | ! มี | ! ยังมีเท่าเดิม ตลาดเสี่ยงอย่างไร ใช้ EA ก็เสี่ยงอย่างนั้น\n"
			. 'สิ่งที่คุณยังต้องทำ | ทุกอย่าง ตั้งแต่วิเคราะห์ถึงปิดออเดอร์ | เลือกโหมดให้ตรงทุน ดูแลเครื่องที่รัน และติดตามผล',
		'home_compare_note'    => '',

		/* ---------- เช็กลิสต์ความพร้อม ([data-ready] · main.js) ---------- */
		'show_home_ready'      => true,
		'home_ready_title'     => 'ก่อนเปิดใช้จริง เช็กความพร้อม 6 ข้อ',
		'home_ready_sub'       => 'ติ๊กข้อที่ทำแล้ว ข้อไหนยังไม่พร้อมมีลิงก์ไปคู่มือให้',
		'home_ready_items'     => "มีบัญชีเทรด MT5 แล้ว | ยังไม่มี ดู[วิธีเปิดบัญชี](/open-mt5-account/)\n"
			. "ติดตั้ง MT5 และล็อกอินได้ | ดู[คู่มือติดตั้งและล็อกอิน MT5](/mt5-login/)\n"
			. "มีเครื่องที่เปิด MT5 ไว้ได้ตลอด หรือมี VPS | ดู[คู่มือ VPS](/vps-windows/)\n"
			. "เลือกสินค้าและแพ็กเกจแล้ว | ดู[แพ็กเกจและราคา](/pricing/)\n"
			. "คิดขนาด Lot จากทุนของคุณแล้ว | ใช้[เครื่องคำนวณ Lot](/tools/)\n"
			. 'วางแผนเริ่มจากเดโมหรือเงินจำนวนน้อย | ดู[คู่มือ Forward Test](/forward-test/)',
		'home_ready_of'        => '/ {total} ข้อ',
		'home_ready_left'      => 'ยังเหลืออีก {n} ข้อ',
		'home_ready_full'      => 'พร้อมเริ่มแล้ว ทักทีมงานได้เลย',
		'home_ready_done'      => 'ครบแล้ว ทักทีมงานเพื่อเลือกสินค้าและโหมดที่เข้ากับทุนของคุณ',
		'home_ready_btn'       => 'ทัก LINE เริ่มใช้งาน',

		/* ---------- FAQ (ใช้ทำ FAQPage schema ใน inc/seo.php ด้วย) ---------- */
		'home_faq_title'       => 'คำถามที่พบบ่อย',
		'home_faq_sub'         => 'คำถามที่ทีมงานได้รับบ่อยก่อนลูกค้าเริ่มใช้ ไม่เจอคำตอบที่ต้องการ ถามทาง LINE ได้เลย',
		'home_faq_btn'         => 'ถามทีมงานทาง LINE',
		'home_faq1_q'          => 'EA BULL คืออะไร',
		'home_faq1_a'          => 'EA ย่อมาจาก Expert Advisor คือโปรแกรมที่ทำงานใน MetaTrader 5 และส่งคำสั่งซื้อขายตามเงื่อนไขที่เขียนไว้ EA BULL คือชื่อแบรนด์ของเรา รายละเอียดสินค้าแต่ละตัว {wait}',
		'home_faq2_q'          => 'ต้องเปิดบัญชีกับโบรกเกอร์ไหน',
		'home_faq2_a'          => 'เราแนะนำ Zaurix ซึ่งเป็นโบรกเกอร์พันธมิตรของ EA BULL และเราอาจได้รับค่าตอบแทนเมื่อคุณสมัครผ่านลิงก์ของเรา ใช้โบรกเกอร์อื่นได้หรือไม่ {wait}',
		'home_faq3_q'          => 'ต้องเปิดคอมทิ้งไว้ตลอดไหม',
		'home_faq3_a'          => 'EA ทำงานเมื่อ MT5 เปิดอยู่และต่ออินเทอร์เน็ต ถ้าไม่อยากเปิดคอมทิ้งไว้ ให้รัน MT5 บน VPS แทน ดู[คู่มือ VPS](/vps-windows/)',
		'home_faq4_q'          => 'ใช้แค่มือถืออย่างเดียวได้ไหม',
		'home_faq4_a'          => 'แอป MT5 บนมือถือรัน EA ไม่ได้ ใช้ได้แค่ดูบัญชีและออเดอร์ ตัว EA ต้องรันบน MT5 ของ Windows หรือบน VPS แล้วใช้มือถือไว้ติดตามผล',
		'home_faq5_q'          => 'ต้องใช้เงินทุนเท่าไร',
		'home_faq5_a'          => 'ขึ้นกับสินค้าและโหมดที่เลือก {wait} ลองใส่ทุนของคุณใน[เครื่องคำนวณ Lot](/tools/) หรือให้ทีมงานช่วยดูทาง LINE',
		'home_faq6_q'          => 'ราคาเท่าไร จ่ายอย่างไร',
		'home_faq6_a'          => 'ดูราคาและสิ่งที่รวมอยู่ในแต่ละแพ็กเกจได้ที่[หน้าแพ็กเกจ](/pricing/) จ่ายครั้งเดียวจบ ไม่มีรอบเก็บเงินรายเดือนหรือรายปี และชำระแล้วไม่คืนเงิน ทัก LINE ถามทีมงานให้ครบก่อนโอน',
		'home_faq7_q'          => 'ใช้แล้วได้กำไรแน่ไหม',
		'home_faq7_a'          => 'ไม่มี EA ตัวไหนรับรองกำไรได้ EA BULL ก็เช่นกัน ตลาดเปลี่ยนได้ตลอด และบัญชีอาจขาดทุนจนหมดได้ อ่าน[ประกาศความเสี่ยง](/risk-disclosure/)ก่อนเริ่ม',
		'home_faq8_q'          => 'ทดลองก่อนใช้เงินจริงได้ไหม',
		'home_faq8_a'          => 'ควรเริ่มจากบัญชีเดโมหรือบัญชีเงินน้อยก่อน ดู[คู่มือ Forward Test](/forward-test/) ส่วนการเปิดใช้งานบนบัญชีเดโม {wait}',

		'home_faq9_q'          => 'EA BULL ใช้กับ MT4 ได้ไหม',
		'home_faq9_a'          => 'EA BULL ทำมาสำหรับ MetaTrader 5 ถ้าตอนนี้ใช้ MT4 อยู่ เปิดบัญชี MT5 เพิ่มได้ตาม[คู่มือเปิดบัญชีเทรด](/open-mt5-account/)',
		'home_faq10_q'         => 'มีค่ารายเดือนหรือค่าต่ออายุไหม',
		'home_faq10_a'         => 'ไม่มี แพ็กเกจ Pro และ VIP จ่ายครั้งเดียว และต่ออายุสิทธิ์ไม่มีค่าใช้จ่ายเพิ่ม ดูรายละเอียดที่[หน้าแพ็กเกจ](/pricing/)',
		'home_faq11_q'         => 'มีรุ่นใหม่แล้วจะรู้ได้อย่างไร',
		'home_faq11_a'         => 'เมื่อมีรุ่นใหม่ ทีมงานจะแจ้งทาง LINE ส่วนเลขรุ่นล่าสุดของแต่ละสินค้าดูได้ที่หน้าสินค้าและหน้า[เริ่มต้นใช้งาน](/go/)',
		'home_faq12_q'         => 'Pro กับ VIP ต่างกันอย่างไร',
		'home_faq12_a'         => 'Pro เป็นสิทธิ์ใช้งานบนบัญชี MT5 ของคุณ 1 ถึง 2 บัญชี ส่วน VIP มีทีมงานช่วยติดตั้งให้ ทั้งสองแบบจ่ายครั้งเดียว เทียบทีละหัวข้อได้ที่[หน้าแพ็กเกจ](/pricing/)',

		/* ---------- เนื้อหาเพิ่มของหน้าแรก (เจ้าของขอ 29 ต.ค. 2026: บอกเรื่องเว็บให้ครบ และแข็งแรงด้าน SEO) ---------- */
		'show_home_intro'        => true,
		'home_intro_title'       => 'รู้จัก EA BULL ก่อนเริ่ม',
		'home_intro_text'        => "EA BULL คือแบรนด์ EA หรือ Expert Advisor สำหรับ MetaTrader 5 ระบบเทรดอัตโนมัติที่ทำงานอยู่ในโปรแกรม MT5 บนคอม Windows หรือ VPS อ่านราคาบนกราฟแล้วส่งคำสั่งซื้อขายตามกติกาที่เขียนไว้ คุณเป็นคนเลือกสินค้า โหมด และขนาดไม้ให้เข้ากับทุนของตัวเอง\n\n"
			. "เว็บนี้รวมทุกอย่างที่ต้องใช้ไว้ในที่เดียว ตั้งแต่[เปิดบัญชีเทรด](/open-mt5-account/) [ติดตั้ง MT5](/mt5-login/) [ติดตั้ง EA](/how-to-install/) ไปจนถึง[รัน EA บน VPS](/vps-windows/) และ[เครื่องคำนวณ Lot](/tools/) ทุกคู่มือแบ่งเป็นขั้นสั้น ๆ พร้อมภาพประกอบ ทำตามได้แม้ไม่เคยใช้ EA มาก่อน\n\n"
			. 'การซื้อและการดูแลหลังการขายทำผ่าน LINE กับทีมงานโดยตรง ทีมงานช่วยเลือกแพ็กเกจ ส่งไฟล์ EA เปิดสิทธิ์ใช้งาน และแจ้งเมื่อมีรุ่นใหม่ ส่วนเงินที่ใช้เทรดอยู่ในบัญชี MT5 ของคุณกับโบรกเกอร์',
		'home_intro_facts_title' => 'ข้อมูลสำคัญ',
		'home_intro_facts'       => "แพลตฟอร์ม | MetaTrader 5 บนคอม Windows หรือ VPS\n"
			. "ดูบัญชีจากมือถือ | แอป MT5 บน Android และ iPhone\n"
			. "ซื้อและดูแลหลังการขาย | LINE กับทีมงาน EA BULL\n"
			. "แพ็กเกจ | {plans}\n"
			. "การชำระเงิน | จ่ายครั้งเดียว ไม่มีรายเดือนหรือรายปี\n"
			. "ต่ออายุสิทธิ์ | ไม่มีค่าใช้จ่ายเพิ่ม\n"
			. "รุ่นใหม่ | ทีมงานแจ้งทาง LINE\n"
			. 'โบรกเกอร์ที่แนะนำ | Zaurix โบรกเกอร์พันธมิตรของเรา',

		'show_home_plans'        => true,
		'home_plans_title'       => 'แพ็กเกจและราคา',
		'home_plans_sub'         => 'เริ่มจากคุยกับทีมงานฟรี แล้วค่อยเลือกแพ็กเกจที่ตรงกับจำนวนบัญชีและความช่วยเหลือที่ต้องการ',
		'home_plans_more'        => 'เทียบแพ็กเกจทั้งหมด',
		'home_plans_btn'         => 'ดูรายละเอียด',

		'show_home_hub'          => true,
		'home_hub_title'         => 'คู่มือและเครื่องมือทั้งหมด',
		'home_hub_sub'           => 'แต่ละขั้นมีหน้าคู่มือของตัวเอง เปิดอ่านเฉพาะเรื่องที่กำลังทำอยู่ได้เลย',
		'home_hub_items'         => "user | /open-mt5-account/ | เปิดบัญชีเทรด MT5 | สมัคร ยืนยันตัวตน และสร้างบัญชีเทรด\n"
			. "wallet | /deposit/ | ฝากเงินเข้าบัญชีเทรด | ฝากผ่าน Portal แล้วเช็กยอดใน MT5\n"
			. "download | /mt5-login/ | ติดตั้งและล็อกอิน MT5 | ลงโปรแกรมบนคอมและแอปบนมือถือ\n"
			. "box | /how-to-install/ | ติดตั้ง EA บน MT5 | วางไฟล์ เปิด Algo Trading แล้วลากลงกราฟ\n"
			. "monitor | /vps-windows/ | VPS บน Windows | รัน MT5 ทั้งวันผ่าน Remote Desktop\n"
			. "phone | /vps-android/ | VPS จาก Android | เข้าเช็ก VPS ด้วยแอป Windows App\n"
			. "tablet | /vps-ios/ | VPS จาก iPhone และ iPad | ดู MT5 บน VPS ได้จากทุกที่\n"
			. "flask | /backtest/ | Backtest ด้วย Strategy Tester | ทดสอบกับราคาในอดีตและอ่านรายงาน\n"
			. "pulse | /forward-test/ | Forward Test | ลองกับตลาดจริงด้วยเงินน้อยก่อนขยาย\n"
			. 'calculator | /tools/ | เครื่องคำนวณ Lot และ Drawdown | คิดขนาดไม้จากทุนและระยะตัดขาดทุน',

		'show_home_learn'        => true,
		'home_learn_title'       => 'EA บน MT5 คืออะไร: สรุปสำหรับคนเริ่มต้น',
		'home_learn_sub'         => 'อ่านจบในไม่กี่นาที เข้าใจว่า EA ทำงานอย่างไร ต้องเตรียมอะไร และเลือกใช้อย่างไรให้เข้ากับทุน',
		'home_learn_body'        => "### EA หรือ Expert Advisor คืออะไร\n"
			. "EA คือโปรแกรมที่ติดตั้งอยู่ใน MetaTrader 5 ทำหน้าที่อ่านราคาบนกราฟ คำนวณตามเงื่อนไขที่ผู้พัฒนาเขียนไว้ แล้วส่งคำสั่งเปิดหรือปิดออเดอร์ให้เองโดยไม่ต้องมีคนกด หลายคนเรียกว่าบอทเทรดหรือระบบเทรดอัตโนมัติ จุดเด่นคือ EA ใช้กติกาเดิมทุกครั้ง ไม่เหนื่อย และไม่เปลี่ยนใจกลางทาง\n\n"
			. "### EA ทำงานอย่างไรใน MT5\n"
			. "เมื่อลากไฟล์ EA ลงบนกราฟและเปิดปุ่ม Algo Trading แล้ว EA จะทำงานทุกครั้งที่ราคาขยับ ตราบใดที่ MT5 ยังเปิดอยู่และต่ออินเทอร์เน็ต ถ้าปิดคอมหรือเน็ตหลุด EA ก็หยุดตาม คนใช้ EA ส่วนใหญ่จึงย้าย MT5 ไปไว้บน[VPS](/vps-windows/) ซึ่งเป็นคอมพิวเตอร์เสมือนที่เปิดอยู่ตลอดเวลา\n\n"
			. "### ต้องเตรียมอะไรบ้างก่อนใช้ EA\n"
			. "- บัญชีเทรด MT5 กับโบรกเกอร์ ดู[วิธีเปิดบัญชี](/open-mt5-account/)\n"
			. "- โปรแกรม MT5 บนคอม Windows หรือ VPS สำหรับรัน EA\n"
			. "- แอป MT5 บนมือถือไว้ติดตามบัญชี\n"
			. "- ไฟล์ EA และสิทธิ์ใช้งานจากทีมงาน\n"
			. "- ทุนที่เข้ากับโหมดที่เลือก คิดได้ด้วย[เครื่องคำนวณ Lot](/tools/)\n\n"
			. "### เลือก EA และโหมดให้เข้ากับทุน\n"
			. "EA ตัวเดียวกันตั้งค่าได้หลายแบบ โหมดที่เปิดไม้ใหญ่กว่าต้องมีเงินในบัญชีมากกว่า ก่อนเริ่มให้ดูทุนที่แนะนำของแต่ละสินค้า แล้วลองคำนวณขนาดไม้ล่วงหน้า ถ้ายังเลือกไม่ได้ ทักทีมงานทาง LINE ให้ช่วยดูจากทุนจริงของคุณ\n\n"
			. "### ทดสอบก่อนขยายทุน\n"
			. 'MT5 มี Strategy Tester ให้ทดสอบ EA กับราคาในอดีต ดู[วิธีทำ Backtest](/backtest/) และควรลองกับตลาดจริงด้วยบัญชีเดโมหรือเงินจำนวนน้อยก่อน ดู[คู่มือ Forward Test](/forward-test/) เมื่อเห็นการทำงานของระบบชัดแล้วค่อยตัดสินใจขยาย',

		/* ---------- กล่องความเสี่ยง ---------- */
		'home_risk_title'      => 'อ่านก่อนใช้เงินจริง: ความเสี่ยงของการใช้ EA',
		'home_risk_items'      => "อาจขาดทุนจนหมดเงินในบัญชี โดยเฉพาะเมื่อใช้เลเวอเรจสูงหรือมีเงินน้อยกว่าที่แนะนำ\n"
			. "ผล Backtest และผลในอดีตไม่ได้บอกว่าอนาคตจะออกมาแบบเดียวกัน\n"
			. "ช่วงข่าวแรง ราคาอาจกระโดด สเปรดกว้างขึ้น และออเดอร์อาจได้ราคาต่างจากที่ตั้งไว้\n"
			. "ไฟดับ เน็ตหลุด หรือเซิร์ฟเวอร์ของโบรกเกอร์และ VPS มีปัญหา ทำให้ EA หยุดทำงานได้\n"
			. "ความเสี่ยงเฉพาะของสินค้าแต่ละตัว {wait:รอเอกสารสินค้าจากเจ้าของ}\n"
			. 'ข้อมูลบนเว็บนี้มีไว้ให้ความรู้ ไม่ได้แนะนำให้ลงทุน คุณเป็นผู้ตัดสินใจและรับผลเอง',
		'home_risk_btn'        => 'อ่านประกาศความเสี่ยงฉบับเต็ม',

		/* คำเตือนหลักที่ใช้ทั้งเว็บ (ท้ายเว็บ บทความ หน้า /go) · หมวด "13) คำเตือนความเสี่ยง" */
		'risk_text'            => 'การเทรดผ่าน MT5 ด้วยเลเวอเรจมีความเสี่ยงสูง ราคาเปลี่ยนเร็ว และบัญชีอาจขาดทุนจนหมดเงิน โดยเฉพาะเมื่อมีเงินน้อยกว่าที่แนะนำ EA ทำงานตามเงื่อนไขที่ตั้งไว้ แต่ไม่ได้ทำให้ความเสี่ยงหายไป ผล Backtest และผลในอดีตไม่ได้บอกว่าอนาคตจะออกมาแบบเดียวกัน ไฟดับ เน็ตหลุด หรือเซิร์ฟเวอร์มีปัญหาก็ทำให้ EA หยุดทำงานได้ ข้อมูลบนเว็บนี้มีไว้ให้ความรู้ ไม่ได้แนะนำให้ลงทุน คุณเป็นผู้ตัดสินใจและรับผลเอง ใช้เฉพาะเงินที่เสียได้เท่านั้น',

		/* ---------- บทความล่าสุด (แสดงเฉพาะเมื่อมีบทความเผยแพร่) ---------- */
		'show_home_articles'   => true,
		'home_articles_title'  => 'บทความล่าสุด',
		'home_articles_sub'    => 'ความรู้เรื่อง MT5 VPS และการบริหารความเสี่ยง',
		'home_articles_more'   => 'ดูบทความทั้งหมด',
		'home_articles_count'  => 3,
	);

	/* โหมด 1 ถึง 3 · ทุกช่องข้อมูลว่างจนกว่าเจ้าของส่งเอกสารสินค้า */
	for ( $m = 1; $m <= $c['modes']; $m++ ) {
		$h[ 'home_mode' . $m . '_name' ] = 'โหมด ' . $m;
		$h[ 'home_mode' . $m . '_tag' ]  = '';
		$h[ 'home_mode' . $m . '_text' ] = '';
		for ( $r = 1; $r <= $c['rows']; $r++ ) {
			$h[ 'home_mode' . $m . '_row' . $r ] = '';
		}
	}

	return array_merge( $d, $h );
}

/* ==============================================================
 * Customizer · หมวด 2 ถึง 2.10 ตามลำดับส่วนบนหน้า
 * ============================================================== */

add_filter( 'eab_customizer_sections', 'eab_home_customizer_sections', 10, 2 );

function eab_home_customizer_sections( $sections, $d ) {
	$c     = eab_home_counts();
	$rule  = 'ห้ามใส่ผลกำไร ตัวเลขเปอร์เซ็นต์ หรือคำที่ฟังเหมือนสัญญาผลตอบแทน';
	$fmt   = 'ลิงก์ในเว็บเขียนแบบ [ข้อความ](/slug/) · {wait} = ป้ายรอข้อมูลจากเจ้าของ';
	$link  = 'ใส่ /slug/ หรือ #ส่วนในหน้า หรือ URL เต็ม · เพจในเว็บที่ยังไม่ออนไลน์จะไม่แสดงเป็นลิงก์';
	$wait  = 'เว้นว่าง = แสดงป้าย "รอข้อมูลจากเจ้าของ" · ห้ามแต่งข้อมูล';

	$home = array(
		'eab_home_hero'     => array(
			'title'       => '2) หน้าแรก · ส่วนบนสุด',
			'description' => 'หัววัว H1 คำขวัญ คำอธิบาย ปุ่มสองปุ่ม คำเตือน และ 3 เสาหลัก (วินัย แผน ระบบ) · ตามด้วยแถบแพลตฟอร์มสีเหลือง',
			'fields'      => array(
				'show_hero'           => array( 'แสดงส่วนบนสุด (ปิด = ยังมี H1 ให้โปรแกรมอ่านหน้าจอ)', 'checkbox' ),
				'hero_title'          => array( 'ชื่อแบรนด์ (ใช้ใน [eabull_brand] และคู่มือ)', 'text' ),
				'home_hero_img'       => array( 'ภาพหัววัว', 'image', 'ค่าเริ่มต้น: assets/img/brand/eabull-emblem.webp (640×571)' ),
				'home_hero_img_alt'   => array( 'คำอธิบายภาพ (alt)', 'text' ),
				'home_hero_h1'        => array( 'หัวข้อหลัก (H1)', 'text', '{คำ} = ห้ามตัดบรรทัดกลางวลี' ),
				'home_hero_tag'       => array( 'คำขวัญใต้หัวข้อ (สีเหลือง)', 'text' ),
				'home_hero_lead'      => array( 'คำอธิบายสั้น', 'textarea', $rule ),
				'home_hero_btn1_text' => array( 'ปุ่มหลัก (เหลือง) · ข้อความ (ว่าง = ซ่อน)', 'text' ),
				'home_hero_btn1_url'  => array( 'ปุ่มหลัก · ลิงก์', 'path', $link ),
				'home_hero_btn2_text' => array( 'ปุ่มรอง (เส้น) · ข้อความ (ว่าง = ซ่อน)', 'text' ),
				'home_hero_btn2_url'  => array( 'ปุ่มรอง · ลิงก์', 'path', $link ),
				'home_hero_risk'      => array( 'คำเตือนใต้ปุ่ม (ห้ามลบ)', 'textarea', $fmt ),
				'home_pillars'        => array( '3 เสาหลัก (บรรทัดละ 1: คำ | คำอธิบาย | ชื่อไอคอน)', 'textarea' ),
				'home_show_plat'      => array( 'แสดงแถบแพลตฟอร์ม', 'checkbox' ),
				'home_plat_label'     => array( 'แถบแพลตฟอร์ม · หัวข้อ', 'text' ),
				'home_plat_items'     => array( 'แถบแพลตฟอร์ม · รายการ (บรรทัดละ 1)', 'textarea', 'ไอคอนเลือกตามคำ: MetaTrader/MT5, Windows, Android, iPhone/iPad, VPS · ใช้ชื่อเป็นตัวอักษร ไม่ใช้โลโก้การค้า' ),
			),
		),

		'eab_home_modes'    => array(
			'title'       => '2.3) หน้าแรก · โหมด (แท็บ)',
			'description' => 'แท็บ 3 โหมด แต่ละแท็บมีคำอธิบาย คำเตือน และตารางสเปก · ' . $wait,
			'fields'      => array(
				'show_home_modes'      => array( 'แสดงส่วนนี้', 'checkbox' ),
				'home_modes_title'     => array( 'หัวข้อ (H2)', 'text' ),
				'home_modes_sub'       => array( 'คำอธิบายใต้หัวข้อ', 'textarea' ),
				'home_modes_aria'      => array( 'ชื่อกลุ่มแท็บ (โปรแกรมอ่านหน้าจอ)', 'text' ),
				'home_modes_tag_empty' => array( 'ข้อความใต้ชื่อแท็บเมื่อยังไม่มีชื่อโหมด', 'text' ),
				'home_modes_text_hint' => array( 'ข้อความหน้าป้ายรอข้อมูล เมื่อแท็บยังไม่มีคำอธิบาย', 'text' ),
				'home_modes_warn'      => array( 'คำเตือนในทุกแท็บ (ห้ามลบ)', 'textarea' ),
			),
		),

		'eab_home_features' => array(
			'title'  => '2.4) หน้าแรก · สิ่งที่ EA BULL ทำแทนคุณ (2x2)',
			'fields' => array(
				'show_features'   => array( 'แสดงส่วนนี้', 'checkbox' ),
				'home_feat_title' => array( 'หัวข้อ (H2)', 'text' ),
				'home_feat_sub'   => array( 'คำอธิบายใต้หัวข้อ', 'textarea' ),
			),
		),

		'eab_home_steps'    => array(
			'title'       => '2.5) หน้าแรก · 6 ขั้นตอน',
			'description' => 'รางขั้นตอน เลขในบล็อกตัดมุม · ใส่ line ในช่องลิงก์ = ปุ่มทัก LINE · ขั้นที่หัวข้อว่างจะไม่แสดง',
			'fields'      => array(
				'show_steps'           => array( 'แสดงส่วนนี้', 'checkbox' ),
				'home_steps_title'     => array( 'หัวข้อ (H2)', 'text' ),
				'home_steps_sub'       => array( 'คำอธิบายใต้หัวข้อ', 'textarea' ),
				'home_steps_more_text' => array( 'ลิงก์มุมขวา · ข้อความ (ว่าง = ซ่อน)', 'text' ),
				'home_steps_more_url'  => array( 'ลิงก์มุมขวา · ปลายทาง', 'path', $link ),
			),
		),

		'eab_home_compare'  => array(
			'title'  => '2.6) หน้าแรก · ตารางเทียบ',
			'fields' => array(
				'show_home_compare'  => array( 'แสดงส่วนนี้', 'checkbox' ),
				'home_compare_title' => array( 'หัวข้อ (H2)', 'text' ),
				'home_compare_sub'   => array( 'คำอธิบายใต้หัวข้อ', 'textarea' ),
				'home_compare_rows'  => array( 'ตาราง (บรรทัดแรก = หัวตาราง · คั่นช่องด้วย | · คอลัมน์สุดท้ายเป็นสีเด่น)', 'textarea', 'ขึ้นต้นช่องด้วย + = เครื่องหมายถูก · - = กากบาท · ! = เตือน · ' . $rule ),
				'home_compare_note'  => array( 'หมายเหตุใต้ตาราง (ห้ามลบ)', 'textarea' ),
			),
		),

		'eab_home_ready'    => array(
			'title'       => '2.7) หน้าแรก · เช็กลิสต์ความพร้อม',
			'description' => 'ติ๊กแล้วแท่งความคืบหน้าขยับ (main.js) · ติ๊กครบ = แสดงปุ่ม LINE',
			'fields'      => array(
				'show_home_ready'  => array( 'แสดงส่วนนี้', 'checkbox' ),
				'home_ready_title' => array( 'หัวข้อ (H2)', 'text' ),
				'home_ready_sub'   => array( 'คำอธิบายใต้หัวข้อ', 'textarea' ),
				'home_ready_items' => array( 'รายการ (บรรทัดละ 1: หัวข้อ | บรรทัดเล็ก)', 'textarea', $fmt ),
				'home_ready_of'    => array( 'ข้อความต่อท้ายตัวนับ ({total} = จำนวนข้อ)', 'text' ),
				'home_ready_left'  => array( 'สถานะเมื่อยังไม่ครบ ({n} = ข้อที่เหลือ)', 'text' ),
				'home_ready_full'  => array( 'สถานะเมื่อครบ', 'text' ),
				'home_ready_done'  => array( 'ข้อความเหนือปุ่มเมื่อครบ', 'textarea' ),
				'home_ready_btn'   => array( 'ปุ่ม LINE เมื่อครบ', 'text' ),
			),
		),

		'eab_home_more'     => array(
			'title'       => '2.9) หน้าแรก · รู้จัก EA BULL · แพ็กเกจ · คู่มือทั้งหมด · EA คืออะไร',
			'description' => 'เนื้อหาเพิ่มของหน้าแรก · ' . $fmt,
			'fields'      => array(
				'show_home_intro'        => array( 'แสดง "รู้จัก EA BULL"', 'checkbox' ),
				'home_intro_title'       => array( 'รู้จัก EA BULL · หัวข้อ (H2)', 'text' ),
				'home_intro_text'        => array( 'รู้จัก EA BULL · เนื้อหา (เว้นบรรทัด = ย่อหน้าใหม่)', 'textarea', $rule ),
				'home_intro_facts_title' => array( 'รู้จัก EA BULL · หัวข้อกล่องข้อมูล', 'text' ),
				'home_intro_facts'       => array( 'รู้จัก EA BULL · ข้อมูลสำคัญ (บรรทัดละ 1: หัวข้อ | ข้อมูล)', 'textarea', '{plans} = ชื่อและราคาแพ็กเกจจากหมวดแพ็กเกจ' ),
				'show_home_plans'        => array( 'แสดง "แพ็กเกจและราคา"', 'checkbox' ),
				'home_plans_title'       => array( 'แพ็กเกจ · หัวข้อ (H2)', 'text' ),
				'home_plans_sub'         => array( 'แพ็กเกจ · คำอธิบาย', 'textarea' ),
				'home_plans_more'        => array( 'แพ็กเกจ · ลิงก์ไปหน้าแพ็กเกจ', 'text' ),
				'home_plans_btn'         => array( 'แพ็กเกจ · ปุ่มในการ์ด', 'text' ),
				'show_home_hub'          => array( 'แสดง "คู่มือและเครื่องมือทั้งหมด"', 'checkbox' ),
				'home_hub_title'         => array( 'คู่มือทั้งหมด · หัวข้อ (H2)', 'text' ),
				'home_hub_sub'           => array( 'คู่มือทั้งหมด · คำอธิบาย', 'textarea' ),
				'home_hub_items'         => array( 'คู่มือทั้งหมด · รายการ (บรรทัดละ 1: ไอคอน | /slug/ | ชื่อ | คำอธิบาย)', 'textarea' ),
				'show_home_learn'        => array( 'แสดง "EA บน MT5 คืออะไร"', 'checkbox' ),
				'home_learn_title'       => array( 'EA คืออะไร · หัวข้อ (H2)', 'text' ),
				'home_learn_sub'         => array( 'EA คืออะไร · คำอธิบาย', 'textarea' ),
				'home_learn_body'        => array( 'EA คืออะไร · เนื้อหา (### = หัวข้อย่อย · - = รายการ · เว้นบรรทัด = ย่อหน้าใหม่)', 'textarea', $rule ),
			),
		),

		'eab_home_faq'      => array(
			'title'       => '2.8) หน้าแรก · FAQ',
			'description' => 'ช่องว่างถูกข้าม · ชุดเดียวกันนี้ส่งไปเป็น FAQ schema ด้วย ยกเว้นข้อที่ยังมี {wait}',
			'fields'      => array(
				'show_faq'       => array( 'แสดงส่วนนี้', 'checkbox' ),
				'home_faq_title' => array( 'หัวข้อ (H2)', 'text' ),
				'home_faq_sub'   => array( 'คำอธิบายใต้หัวข้อ', 'textarea' ),
				'home_faq_btn'   => array( 'ปุ่ม LINE ใต้หัวข้อ', 'text' ),
			),
		),

		'eab_home_risk'     => array(
			'title'       => '2.9) หน้าแรก · กล่องความเสี่ยง',
			'description' => 'สำคัญต่อความน่าเชื่อถือ ไม่แนะนำให้ปิด · ข้อความคำเตือนที่ใช้ทั้งเว็บแก้ที่หมวด "13) คำเตือนความเสี่ยง"',
			'fields'      => array(
				'show_risk'       => array( 'แสดงส่วนนี้', 'checkbox' ),
				'home_risk_title' => array( 'หัวข้อ (H2)', 'text' ),
				'home_risk_items' => array( 'รายการคำเตือน (บรรทัดละ 1 ข้อ · แบ่ง 2 คอลัมน์)', 'textarea', 'ห้ามลบหรือลดทอน · ' . $fmt ),
				'home_risk_btn'   => array( 'ปุ่มเปิดประกาศความเสี่ยงฉบับเต็ม (ซ่อนถ้าเพจยังไม่ออนไลน์)', 'text' ),
			),
		),

		'eab_home_articles' => array(
			'title'       => '2.10) หน้าแรก · บทความล่าสุด',
			'description' => 'แสดงเฉพาะเมื่อมีบทความที่เผยแพร่แล้ว (ไม่มี = ซ่อนทั้งส่วน)',
			'fields'      => array(
				'show_home_articles'  => array( 'แสดงส่วนนี้', 'checkbox' ),
				'home_articles_title' => array( 'หัวข้อ (H2)', 'text' ),
				'home_articles_sub'   => array( 'คำอธิบายใต้หัวข้อ', 'textarea' ),
				'home_articles_more'  => array( 'ลิงก์ไปหน้าบทความทั้งหมด (ว่าง = ซ่อน)', 'text' ),
				'home_articles_count' => array( 'จำนวนบทความ (3 หรือ 6)', 'number' ),
			),
		),
	);

	for ( $r = 1; $r <= $c['rows']; $r++ ) {
		$home['eab_home_modes']['fields'][ 'home_modes_row' . $r ] = array( 'ตารางสเปกแถวที่ ' . $r . ' · ชื่อ (ใช้กับทุกโหมด)', 'text' );
	}
	for ( $m = 1; $m <= $c['modes']; $m++ ) {
		$home['eab_home_modes']['fields'][ 'home_mode' . $m . '_name' ] = array( 'แท็บ ' . $m . ' · ชื่อแท็บ (ว่าง = ซ่อนแท็บนี้)', 'text' );
		$home['eab_home_modes']['fields'][ 'home_mode' . $m . '_tag' ]  = array( 'แท็บ ' . $m . ' · ชื่อโหมด (บรรทัดเล็กใต้ชื่อแท็บ)', 'text' );
		$home['eab_home_modes']['fields'][ 'home_mode' . $m . '_text' ] = array( 'แท็บ ' . $m . ' · คำอธิบายว่าโหมดทำงานอย่างไร เหมาะกับใคร', 'textarea', $wait );
		for ( $r = 1; $r <= $c['rows']; $r++ ) {
			$home['eab_home_modes']['fields'][ 'home_mode' . $m . '_row' . $r ] = array( 'แท็บ ' . $m . ' · ตารางแถวที่ ' . $r . ' · ค่า', 'text', 1 === $r ? $wait : '' );
		}
	}
	for ( $i = 1; $i <= $c['feat']; $i++ ) {
		$home['eab_home_features']['fields'][ 'home_feat' . $i . '_title' ] = array( 'ช่อง ' . $i . ' · หัวข้อ (ว่าง = ซ่อน)', 'text' );
		$home['eab_home_features']['fields'][ 'home_feat' . $i . '_text' ]  = array( 'ช่อง ' . $i . ' · รายละเอียด', 'textarea', $rule );
		$home['eab_home_features']['fields'][ 'home_feat' . $i . '_link' ]  = array( 'ช่อง ' . $i . ' · ข้อความลิงก์', 'text' );
		$home['eab_home_features']['fields'][ 'home_feat' . $i . '_url' ]   = array( 'ช่อง ' . $i . ' · ลิงก์', 'path', $link );
	}
	for ( $i = 1; $i <= $c['steps']; $i++ ) {
		$home['eab_home_steps']['fields'][ 'home_step' . $i . '_title' ] = array( 'ขั้น ' . $i . ' · หัวข้อ', 'text' );
		$home['eab_home_steps']['fields'][ 'home_step' . $i . '_text' ]  = array( 'ขั้น ' . $i . ' · รายละเอียดสั้น', 'textarea' );
		$home['eab_home_steps']['fields'][ 'home_step' . $i . '_link' ]  = array( 'ขั้น ' . $i . ' · ข้อความลิงก์', 'text' );
		$home['eab_home_steps']['fields'][ 'home_step' . $i . '_url' ]   = array( 'ขั้น ' . $i . ' · ลิงก์ (line = ทัก LINE)', 'path' );
	}
	for ( $i = 1; $i <= $c['faq']; $i++ ) {
		$home['eab_home_faq']['fields'][ 'home_faq' . $i . '_q' ] = array( 'คำถามข้อ ' . $i, 'text' );
		$home['eab_home_faq']['fields'][ 'home_faq' . $i . '_a' ] = array( 'คำตอบข้อ ' . $i, 'textarea', $fmt );
	}

	/* risk_text ใช้ทั้งเว็บ จึงมีหมวดของตัวเอง */
	$sitewide = array(
		'eab_risk' => array(
			'title'       => '13) คำเตือนความเสี่ยง',
			'description' => 'แสดงที่ท้ายทุกหน้า ใต้บทความ และบนหน้า /go/ · ห้ามตัดให้สั้นลงหรือลบประโยคเรื่องการขาดทุน',
			'fields'      => array(
				'risk_text' => array( 'ข้อความคำเตือนฉบับเต็ม', 'textarea', 'ต้องมีครบสามเรื่อง: เสียเงินได้จนหมด อดีตไม่ใช่ตัวบอกอนาคต และเว็บนี้ไม่ได้แนะนำการลงทุน' ),
			),
		),
	);

	/* หมวดเดิมของธีมต้นทาง (customizer.php) · ลบทั้งหมวด ช่องที่ยังมีค่าเริ่มต้นใน functions.php แต่ไม่มีใครใช้ย้ายไปหมวด "99)" */
	$origin_full = array( 'eab_hero', 'eab_pain', 'eab_about', 'eab_features', 'eab_gallery', 'eab_steps', 'eab_perf', 'eab_fit', 'eab_reviews', 'eab_faq', 'eab_risk', 'eab_home', 'eab_team', 'eab_assurance', 'eab_home_extra', 'eab_cta', 'eab_blog' );
	$origin_part = array( 'eab_pricing' );

	/* คีย์ที่หมวดของโมดูลอื่นถืออยู่ */
	$claimed = array();
	foreach ( $sections as $sid => $section ) {
		if ( in_array( $sid, $origin_full, true ) || in_array( $sid, $origin_part, true ) || empty( $section['fields'] ) ) {
			continue;
		}
		foreach ( array_keys( $section['fields'] ) as $key ) {
			$claimed[ $key ] = true;
		}
	}

	/* ตัดคีย์ซ้ำออก แล้วจดคีย์ของเรา */
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
			/* ช่องที่หน้าแรกถือเอง หรือไม่มีค่าเริ่มต้นแล้ว (เช่น หัวข้อแพ็กเกจบนหน้าแรกแบบเดิม) */
			if ( isset( $owned[ $key ] ) || ! array_key_exists( $key, (array) $d ) ) {
				unset( $sections[ $sid ]['fields'][ $key ] );
			}
		}
		if ( empty( $sections[ $sid ]['fields'] ) ) {
			unset( $sections[ $sid ] );
		}
	}

	/* ลำดับ: หมวดหน้าแรกต่อจาก eab_general · eab_risk ต่อจาก eab_pricing */
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
		$out = eab_home_insert_after( $out, 'eab_home_articles', $sitewide );
	}

	if ( $legacy ) {
		$out['eab_home_legacy'] = array(
			'title'       => '99) ค่าเดิมของธีมต้นทางที่ไม่ได้แสดงแล้ว',
			'description' => 'ช่องจากธีมต้นทางที่ยังมีค่าเริ่มต้นใน functions.php แต่หน้าเว็บไม่ได้ใช้แล้ว · การแก้ค่าที่นี่ไม่เปลี่ยนหน้าเว็บ',
			'fields'      => $legacy,
		);
	}

	return $out;
}

/**
 * ใส่ $insert ต่อหลัง $after (ไม่พบ = ท้ายสุด)
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
 * ตัวช่วยของหน้าแรก (หน้าสินค้าใช้ร่วม)
 * ============================================================== */

/**
 * ลิงก์ที่แสดงได้ · /slug/ คืน URL เฉพาะเพจที่ออนไลน์แล้ว (ไม่พาไปหน้า 404)
 * URL ภายนอก / #anchor คืนค่าตามเดิม · ว่าง หรือ '#' = ''
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
 * ข้อความในบรรทัดจาก Customizer → HTML ที่ escape แล้ว
 * [ข้อความ](/slug/) = ลิงก์ในเว็บ · {wait} / {wait:ข้อความ} = ป้ายรอข้อมูล · {คำ} = span.nw
 */
function eab_home_inline( $text ) {
	$text = (string) $text;
	$re   = '/\[([^\[\]\n]+)\]\(((?:\/(?!\/)|#)[^\s()<>"\'\\\\]*)\)|\{wait(?::([^{}]*))?\}|\{([^{}]+)\}/u';
	if ( ! preg_match_all( $re, $text, $all, PREG_SET_ORDER | PREG_OFFSET_CAPTURE ) ) {
		return eab_home_keep( esc_html( $text ) );
	}
	$out = '';
	$pos = 0;
	foreach ( $all as $m ) {
		$out .= esc_html( substr( $text, $pos, $m[0][1] - $pos ) );
		$pos  = $m[0][1] + strlen( $m[0][0] );
		if ( isset( $m[2] ) && -1 !== $m[2][1] && '' !== $m[2][0] ) {
			$url  = eab_home_link( $m[2][0] );
			$out .= '' !== $url ? '<a href="' . esc_url( $url ) . '">' . esc_html( $m[1][0] ) . '</a>' : esc_html( $m[1][0] );
		} elseif ( isset( $m[4] ) && -1 !== $m[4][1] && '' !== $m[4][0] ) {
			$out .= '<span class="nw">' . esc_html( $m[4][0] ) . '</span>';
		} else {
			$out .= eab_wait( '', isset( $m[3] ) && -1 !== $m[3][1] ? trim( $m[3][0] ) : '' );
		}
	}
	$out .= esc_html( substr( $text, $pos ) );
	return eab_home_keep( $out );
}

/**
 * ข้อความล้วน (schema / alt / aria) · ลิงก์เหลือแค่ข้อความ · {คำ} เหลือคำ · {wait} หายไป
 */
function eab_home_plain( $text ) {
	$text = preg_replace( '/\[([^\[\]\n]+)\]\([^)\s]*\)/u', '$1', (string) $text );
	$text = preg_replace( '/\{wait(?::[^{}]*)?\}/u', '', $text );
	$text = preg_replace( '/\{([^{}]+)\}/u', '$1', $text );
	return trim( preg_replace( '/\s+/u', ' ', $text ) );
}

/**
 * กันตัดบรรทัดกลางคำ (eab_keep_words ใน inc/components.php) ถ้ามี
 */
function eab_home_keep( $html ) {
	return function_exists( 'eab_keep_words' ) ? eab_keep_words( $html ) : $html;
}

/**
 * ช่องข้อมูลที่อาจยังว่าง: มีค่า = ข้อความ · ว่าง = ข้อความบอกว่าช่องนี้คืออะไร + ป้ายรอข้อมูล (แบบต้นแบบ)
 */
function eab_home_pending( $value, $hint = '' ) {
	$value = trim( (string) $value );
	if ( '' !== $value ) {
		return eab_home_keep( esc_html( $value ) );
	}
	$hint = trim( (string) $hint );
	return ( '' !== $hint ? esc_html( $hint ) . ' ' : '' ) . eab_wait( '' );
}

/**
 * สัญลักษณ์ 3 แท่งหน้าหัวข้อ H2
 */
function eab_home_bars() {
	return '<span class="bars" aria-hidden="true"><i></i><i></i><i></i></span>';
}

/**
 * หัวส่วน .shead: 3 แท่ง + H2 + บรรทัดรอง (ซ้าย) · ลิงก์ "ดูทั้งหมด →" (ขวา)
 */
function eab_home_shead( $id, $title, $sub = '', $more_text = '', $more_url = '' ) {
	$sub       = trim( (string) $sub );
	$more_text = trim( (string) $more_text );
	echo '<div class="shead"><div class="shead__text">';
	echo '<h2 id="' . esc_attr( $id ) . '">' . eab_home_bars() . eab_home_inline( $title ) . '</h2>'; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside
	if ( '' !== $sub ) {
		echo '<p>' . eab_home_inline( $sub ) . '</p>'; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside
	}
	echo '</div>';
	if ( '' !== $more_text && '' !== (string) $more_url ) {
		echo '<a class="tlink" href="' . esc_url( $more_url ) . '">' . esc_html( $more_text ) . ' ' . eab_icon( 'arrow-right' ) . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput
	}
	echo '</div>';
}

/**
 * แยก textarea เป็นแถว "ก | ข | ค" (ตัดช่องว่าง · ข้ามบรรทัดว่าง)
 */
function eab_home_rows( $text, $min = 1 ) {
	$out = array();
	foreach ( eab_lines( $text ) as $line ) {
		$cells = array_map( 'trim', explode( '|', $line ) );
		if ( count( array_filter( $cells, 'strlen' ) ) >= $min ) {
			$out[] = $cells;
		}
	}
	return $out;
}

/**
 * ปุ่ม/ลิงก์ติดต่อ: LINE → หน้า /go/ (ยังไม่มี LINE OA · เจ้าของสั่งให้เตรียมปุ่มไว้) → ไม่แสดง
 * ไม่มีปุ่มใดชี้ '#' · data-line-pos ให้ main.js นับคลิก
 *
 * @param string $icon  ไอคอนหน้าข้อความ ('' = ไม่มี)
 * @param string $after ไอคอนท้ายข้อความ ('' = ไม่มี)
 */
function eab_home_contact_link( $text, $class, $pos, $icon = 'line', $after = '' ) {
	static $hinted = false;
	if ( function_exists( 'eab_contact_target' ) ) {
		$target = eab_contact_target();
	} else {
		$target = array(
			'url'     => function_exists( 'eab_published_page_url' ) ? eab_published_page_url( 'go' ) : '',
			'is_line' => false,
		);
	}
	if ( '' === (string) $target['url'] ) {
		if ( ! $hinted && current_user_can( 'edit_theme_options' ) ) {
			$hinted = true;
			echo '<span class="admin-hint">ยังไม่มีปลายทางของปุ่มติดต่อ: ตั้งลิงก์ LINE ในหมวด 1)</span>';
		}
		return;
	}
	$fallback = trim( (string) eab_mod( 'contact_fallback_text' ) );
	$label    = $target['is_line'] ? trim( (string) $text ) : $fallback;
	if ( '' === $label ) {
		$label = '' !== $fallback ? $fallback : trim( (string) $text );
	}
	if ( '' === $label ) {
		return;
	}
	printf(
		'<a class="%1$s" href="%2$s"%3$s data-line-pos="%4$s">%5$s%6$s%7$s</a>',
		esc_attr( $class ),
		esc_url( $target['url'] ),
		$target['is_line'] ? ' target="_blank" rel="noopener"' : '',
		esc_attr( $pos ),
		'' !== $icon ? eab_icon( $icon ) : '', // phpcs:ignore WordPress.Security.EscapeOutput
		esc_html( $label ),
		'' !== $after ? ' ' . eab_icon( $after ) : '' // phpcs:ignore WordPress.Security.EscapeOutput
	);
}

/**
 * ไอคอนของแถบแพลตฟอร์มตามชื่อ (ตัวอักษรเท่านั้น ไม่ใช้โลโก้การค้า)
 */
function eab_home_plat_icon( $label ) {
	$label = strtolower( (string) $label );
	$map   = array(
		'metatrader' => 'candles',
		'mt5'        => 'candles',
		'windows'    => 'monitor',
		'android'    => 'phone',
		'iphone'     => 'tablet',
		'ipad'       => 'tablet',
		'ios'        => 'tablet',
		'mac'        => 'monitor',
		'vps'        => 'server',
	);
	foreach ( $map as $needle => $icon ) {
		if ( false !== strpos( $label, $needle ) ) {
			return $icon;
		}
	}
	return 'check';
}

/**
 * ขนาดรูป: รูปที่มากับธีม (assets/...) อ่านขนาดจริงของไฟล์ · อื่น ๆ ใช้ค่าที่ส่งมา
 *
 * @return int[] [ width, height ]
 */
function eab_home_img_size( $value, $width, $height ) {
	$value = trim( (string) $value );
	if ( 0 === strpos( $value, 'assets/' ) && false === strpos( $value, '..' ) ) {
		$file = get_template_directory() . '/' . $value;
		$info = is_file( $file ) ? @getimagesize( $file ) : false; // phpcs:ignore WordPress.PHP.NoSilencedErrors
		if ( is_array( $info ) && ! empty( $info[0] ) && ! empty( $info[1] ) ) {
			return array( (int) $info[0], (int) $info[1] );
		}
	}
	return array( (int) $width, (int) $height );
}

/**
 * จำนวนข้อ FAQ ของหน้าแรก
 */
function eab_home_faq_count() {
	$c = eab_home_counts();
	return $c['faq'];
}

/**
 * คำถามที่พบบ่อยของหน้าแรก (ข้อที่คำถามหรือคำตอบว่างถูกข้าม)
 * plain = คำตอบแบบข้อความล้วนสำหรับ FAQPage schema · '' เมื่อคำตอบยังมี {wait} (ข้อมูลยังไม่ครบ ไม่ใส่ใน schema)
 *
 * @return array[] { q, a, plain }
 */
function eab_home_faqs() {
	$out = array();
	for ( $i = 1; $i <= eab_home_faq_count(); $i++ ) {
		$q = trim( (string) eab_mod( 'home_faq' . $i . '_q' ) );
		$a = trim( (string) eab_mod( 'home_faq' . $i . '_a' ) );
		if ( '' === $q || '' === $a ) {
			continue;
		}
		$out[] = array(
			'q'     => $q,
			'a'     => $a,
			'plain' => preg_match( '/\{wait(?::[^{}]*)?\}/u', $a ) ? '' : eab_home_plain( $a ),
		);
	}
	return $out;
}

/**
 * คำที่ห้ามตัดกลางคำบนหน้าแรก ("|" = จุดต่อ · eab_keep_words)
 */
function eab_home_keep_words( $words ) {
	if ( ! is_front_page() ) {
		return $words;
	}
	return array_merge( (array) $words, array( 'ต่าง|กัน', 'ตัว|เดียว' ) );
}
add_filter( 'eab_keep_words', 'eab_home_keep_words' );
