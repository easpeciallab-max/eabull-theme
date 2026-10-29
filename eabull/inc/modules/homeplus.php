<?php
/**
 * EA BULL · ส่วนเสริมของหน้าแรก (เพิ่ม 6 ต.ค. 2026 · เจ้าของขอเนื้อหามากขึ้นและลูกเล่น)
 *
 * ลำดับบนหน้า (front-page.php เรียก eab_homeplus_section()):
 *   about → [story] EA BULL คืออะไร → features → [modes] Lite / Advance / Full (แท็บสลับ) → devices
 *   → [license] การ์ดล็อกอินจำลอง (แตะเปลี่ยนสี) → tests → [compare] เทรดเอง กับ EA BULL → steps
 *   → [ready] เช็กลิสต์พร้อมเริ่ม (ติ๊กแล้วแถบคืบหน้าขยับ) → pricing → [articles] บทความล่าสุด → faq → risk
 *
 * - ข้อความทุกคำเป็น setting (ค่าเริ่มต้น eab_homeplus_defaults · ช่องใน Customizer หมวด 2.9 ถึง 2.14)
 * - ไม่มี JS ก็อ่านได้ครบ (แท็บแสดงทุกแผง · การ์ดแสดงทุกสถานะ · เช็กลิสต์เป็นรายการธรรมดา) · home.js เพิ่มการโต้ตอบ
 * - ข้อเท็จจริงจากชุดส่งลูกค้าและคำแคมเปญ 5.0 ของเจ้าของเท่านั้น (XAUUSD M1 · Lite/Advance/Full · ระบบทบไม้ไม่มีตัดขาดทุนอัตโนมัติ · ทุนคิดไม้ · DLL · ระบบสิทธิ์) ไม่มีตัวเลขผลเทรด
 *
 * @package eabull
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'eab_defaults', 'eab_homeplus_defaults' );

function eab_homeplus_defaults( $d ) {
	return array_merge(
		$d,
		array(
			/* ---------- [story] EA BULL คืออะไร ---------- */
			'show_home_story'   => true,
			'home_story_title'  => 'EA BULL คืออะไร และทำงานบน MT5 อย่างไร',
			'home_story_lead'   => 'EA ย่อมาจาก Expert Advisor คือโปรแกรมเทรดอัตโนมัติที่ทำงานอยู่ใน MetaTrader 5 คอยอ่านราคาทุกช่วงเวลา แล้วส่งคำสั่งซื้อขายตามกติกาที่เขียนไว้ล่วงหน้า EA BULL คือ EA ที่ทีมงานคนไทยพัฒนาและดูแลเอง ออกแบบให้ทำตามแผนอย่างสม่ำเสมอ ไม่เหนื่อย ไม่หลุดวินัยตามอารมณ์ของตลาด และมีคู่มือภาษาไทยทุกขั้นตอนตั้งแต่เปิดบัญชีจนรันบน VPS',
			'home_story_blocks' => "ทำงานบนกราฟทองคำ XAUUSD M1 | EA BULL ออกแบบให้ทำงานบนกราฟ XAUUSD กรอบเวลา M1 ในโปรแกรม MetaTrader 5 บน Windows หรือ VPS เมื่อวางลงกราฟแล้ว EA จะดูราคาทุกแท่งและตัดสินใจตามเงื่อนไขของระบบ คุณจึงไม่ต้องนั่งเฝ้าจอทั้งวัน แค่เปิดแอป MT5 บนมือถือเช็กบัญชีเป็นช่วง ๆ\n"
				. "เปิดออเดอร์เป็นชุดและเติมไม้แก้ | EA BULL เปิดออเดอร์เป็นชุด ถ้าราคาสวนทางจะเติมไม้แก้เป็นขั้นบันไดสูงสุด 9 ขั้น แล้วปิดทั้งชุดพร้อมกันเมื่อกำไรรวมถึงเป้า วิธีนี้เรียกว่าระบบทบไม้ โหมดสำเร็จรูปไม่มีการตัดขาดทุนอัตโนมัติ ถ้าราคาวิ่งทางเดียวไกลเกินบันไดไม้แก้ บัญชีเสียหายหนักได้ถึงเสียเงินทั้งหมด\n"
				. "สามโหมดในไฟล์เดียว | ไฟล์ EA มีโหมด Lite ซึ่งเป็นค่าเริ่มต้น โหมด Advance และโหมด Full ระดับความเสี่ยงสูงขึ้นตามลำดับ เงินในบัญชีที่แนะนำคือ 1 เท่า 2 เท่า และ 3 เท่าของทุนคิดไม้ เปลี่ยนได้จากปุ่มบนแดชบอร์ดโดยไม่ต้องโหลดไฟล์ค่าตั้งเพิ่ม และ EA จำตัวเลือกไว้ให้แม้ปิดเปิด MT5 ใหม่\n"
				. "เป้ากำไรรายวันบอกเป็นเงิน | ตั้งเป้าเป็นเปอร์เซ็นต์ของเงินต้นวันหรือของทุนคิดไม้ แดชบอร์ดแปลงเป็นเงินให้เห็นทันที ถึงเป้าแล้ว EA หยุดเปิดออเดอร์รอบใหม่จนหมดวัน เป้านี้เป็นจุดหยุดของระบบ ไม่ใช่ผลตอบแทนที่รับประกัน\n"
				. "ทุนคิดไม้ เงินกันชน และไม้โตตามกำไร | ทุนคิดไม้คือตัวเลขที่ EA ใช้คิดขนาดออเดอร์ ไม่จำเป็นต้องเท่าเงินในบัญชี เงินส่วนที่เกินเป็นเงินกันชน และแดชบอร์ดเตือนเป็นตัวหนังสือสีแดงเมื่อเงินน้อยกว่าที่โหมดแนะนำ ขึ้นเดือนใหม่ EA นำกำไรสะสมส่วนหนึ่งมาเพิ่มทุนคิดไม้ให้เอง ออเดอร์จึงใหญ่ขึ้นทีละเดือน และช่วงติดลบเป็นเงินก็ใหญ่ขึ้นตาม\n"
				. "หลบข่าวแดงและปุ่มหยุด | EA อ่านปฏิทินข่าวในตัว MT5 แล้วงดเปิดออเดอร์รอบใหม่ช่วงก่อนและหลังข่าวสำคัญของ USD เปิดปิดได้ด้วยปุ่มเดียว และมีปุ่มใหญ่ \"หยุดเปิดไม้ใหม่\" สำหรับพักระบบ โดยออเดอร์ที่ถืออยู่ยังถูกดูแลจนปิด\n"
				. "เงินอยู่ในบัญชีของคุณเสมอ | EA BULL ไม่รับฝากเงินและไม่มีสิทธิ์ถอนเงิน เงินทุนอยู่ในบัญชี MT5 ที่คุณเปิดกับโบรกเกอร์ในชื่อของคุณเอง EA ทำได้เพียงส่งคำสั่งซื้อขายในบัญชีนั้น และคุณหยุดหรือถอด EA ออกจากกราฟได้ทุกเมื่อ\n"
				. "สิทธิ์ใช้งานผูกกับเลขบัญชี | ไฟล์ EA ดาวน์โหลดได้เปิดเผย แต่จะทำงานเฉพาะบัญชีที่ทีมงานเปิดสิทธิ์ให้ การ์ดล็อกอินบนแดชบอร์ดบอกสถานะสิทธิ์ตลอดเวลา และถ้าเน็ตหลุดชั่วคราว EA ยังทำงานต่อได้ในช่วงผ่อนผัน",
			'home_story_img'        => 'assets/img/illus/eabull-how-it-works-v2.webp',
			'home_story_img_mobile' => 'assets/img/illus/eabull-how-it-works-mobile-v2.webp',
			'home_story_img_alt'    => 'แผนภาพ EA BULL ทำงานอย่างไร 4 ขั้น: วางบนกราฟ XAUUSD M1 ใน MT5 ตรวจสิทธิ์เลขบัญชี ส่งคำสั่งเข้าบัญชีโบรกเกอร์ในชื่อของคุณ และติดตามผลจากแอป MT5 บนมือถือ',
			'home_story_btn'    => 'อ่านขั้นตอนติดตั้งแบบละเอียด',
			'home_story_url'    => '/how-to-install/',

			/* ---------- [modes] Lite / Advance / Full (รุ่น 5.0 · 12 ต.ค. 2026) ---------- */
			'show_home_modes'      => true,
			'home_modes_title'     => 'เลือกระดับการบิน: โหมด Lite, Advance หรือ Full',
			'home_modes_sub'       => 'EA BULL มีสามโหมดในไฟล์เดียว แตะเพื่อดูความต่าง แล้วเลือกให้เข้ากับเงินในบัญชีและความเสี่ยงที่คุณรับได้',
			'home_mode1_label'     => 'Lite',
			'home_mode1_tag'       => 'บินต่ำ · เริ่มต้น',
			'home_mode1_title'     => 'ระดับความเสี่ยงต่ำสุดในสามโหมด',
			'home_mode1_text'      => 'โหมดที่ EA ใช้ทันทีหลังติดตั้ง เหมาะกับคนที่เพิ่งเริ่มและอยากเรียนรู้การทำงานของระบบไปก่อน เงินในบัญชีที่แนะนำคือ 1 เท่าของทุนคิดไม้ เช่น เงิน 10,000 ใช้ทุนคิดไม้ 10,000',
			'home_mode1_points'    => "เป็นค่าเริ่มต้นหลังติดตั้ง\nเงินในบัญชีที่แนะนำ 1 เท่าของทุนคิดไม้\nแดชบอร์ดระบุความเสี่ยงระดับ 1 จาก 3",
			'home_mode1_img'       => 'assets/img/install/eabull-mode-lite-v2.webp',
			'home_mode1_img_alt'   => 'แดชบอร์ด EA BULL ที่เลือกโหมด Lite ไว้ ใต้ปุ่มเขียนว่า Lite บินต่ำ เงิน 1 เท่าของทุนคิดไม้ ความเสี่ยงระดับ 1 จาก 3',
			'home_mode2_label'     => 'Advance',
			'home_mode2_tag'       => 'บินกลาง',
			'home_mode2_title'     => 'ระดับกลาง เพิ่มมาในรุ่น 5.0',
			'home_mode2_text'      => 'โหมดที่อยู่ระหว่าง Lite กับ Full ขนาดออเดอร์เมื่อเทียบกับทุนคิดไม้ใหญ่กว่าโหมด Lite เงินในบัญชีที่แนะนำคือ 2 เท่าของทุนคิดไม้ เช่น เงิน 20,000 ใช้ทุนคิดไม้ 10,000 ส่วนที่เกินเป็นเงินกันชน ควรคุยกับทีมงานก่อนเปลี่ยนมาใช้',
			'home_mode2_points'    => "เงินในบัญชีที่แนะนำ 2 เท่าของทุนคิดไม้\nแดชบอร์ดระบุความเสี่ยงระดับ 2 จาก 3\nปรึกษาทีมงานก่อนเปลี่ยนโหมด",
			'home_mode2_img'       => 'assets/img/install/eabull-mode-advance-v1.webp',
			'home_mode2_img_alt'   => 'แดชบอร์ด EA BULL ที่เลือกโหมด Advance ไว้ ใต้ปุ่มเขียนว่า Advance บินกลาง เงิน 2 เท่าของทุนคิดไม้ ความเสี่ยงระดับ 2 จาก 3',
			'home_mode3_label'     => 'Full',
			'home_mode3_tag'       => 'บินสูง',
			'home_mode3_title'     => 'ระดับความเสี่ยงสูงสุด',
			'home_mode3_text'      => 'โหมดสำหรับคนที่เข้าใจระบบแล้วและรับความผันผวนของบัญชีได้มาก ขนาดออเดอร์เมื่อเทียบกับทุนคิดไม้ใหญ่ที่สุด เงินในบัญชีที่แนะนำคือ 3 เท่าของทุนคิดไม้ เช่น เงิน 30,000 ใช้ทุนคิดไม้ 10,000 ส่วนที่เกินเป็นเงินกันชน ควรคุยกับทีมงานก่อนเปลี่ยนมาใช้',
			'home_mode3_points'    => "เงินในบัญชีที่แนะนำ 3 เท่าของทุนคิดไม้\nแดชบอร์ดระบุความเสี่ยงระดับ 3 จาก 3\nปรึกษาทีมงานก่อนเปลี่ยนโหมด",
			'home_mode3_img'       => 'assets/img/install/eabull-mode-full-v2.webp',
			'home_mode3_img_alt'   => 'แดชบอร์ด EA BULL ที่เลือกโหมด Full ไว้ ใต้ปุ่มเขียนว่า Full บินสูง เงิน 3 เท่าของทุนคิดไม้ ความเสี่ยงระดับ 3 จาก 3',
			'home_modes_img_cap'   => 'ปุ่มเลือกโหมดบนแดชบอร์ด EA BULL จริง',
			'home_modes_note'      => 'เปลี่ยนโหมดได้จากปุ่มบนแดชบอร์ด ถ้ามีออเดอร์เปิดอยู่ EA จะรอให้ชุดนั้นปิดก่อนจึงเปลี่ยน ใส่เงินน้อยกว่าที่โหมดแนะนำเสี่ยงพอร์ตแตก ทุกโหมดเป็นระบบทบไม้ที่ไม่มีการตัดขาดทุนอัตโนมัติ และขาดทุนได้ถึงขั้นเสียเงินทั้งบัญชี',

			/* ---------- [license] การ์ดล็อกอิน ---------- */
			'show_home_license'    => true,
			'home_license_title'   => 'การ์ดล็อกอิน บอกสถานะสิทธิ์ทุกเวลา',
			'home_license_sub'     => 'หลังลาก EA ลงกราฟประมาณ 1 วินาที EA BULL จะตรวจเลขบัญชีกับระบบเอง ไม่ต้องใส่รหัสใน MT5 แตะแต่ละสีเพื่อดูว่าการ์ดบอกอะไร',
			'home_license_states'  => "green | ผ่าน | ใช้งานได้ตามปกติ EA พร้อมทำงานตามโหมดที่เลือก\n"
				. "yellow | กำลังตรวจ หรือผ่อนผัน | ระบบกำลังตรวจสิทธิ์ หรืออยู่ในช่วงผ่อนผันระหว่างที่เน็ตหลุดชั่วคราว EA ยังทำงานต่อได้\n"
				. 'red | ไม่ผ่าน | บัญชีนี้ยังไม่ได้รับสิทธิ์ EA จะไม่เปิดออเดอร์ใหม่ ส่งเลขบัญชีกับชื่อเซิร์ฟเวอร์ให้ทีมงานทาง LINE',
			'home_license_steps_title' => 'ขอสิทธิ์ใช้งานใน 3 ขั้น',
			'home_license_steps'   => "ส่งเลขบัญชี MT5 และชื่อเซิร์ฟเวอร์ให้ทีมงานทาง LINE\nทีมงานเพิ่มบัญชีของคุณเข้าระบบสิทธิ์\nEA เริ่มทำงานเองภายใน 5 นาที ไม่ต้องลากลงกราฟใหม่",
			'home_license_card_title' => 'LOGIN',
			'home_license_account' => 'บัญชี •••• ••••',
			'home_license_caption' => 'ภาพจำลองการ์ดล็อกอิน ไม่ใช่หน้าจอจริง',

			/* ---------- [compare] เทรดเอง กับ EA BULL ---------- */
			'show_home_compare'    => true,
			'home_compare_title'   => 'เทรดเองทุกไม้ กับให้ EA BULL ช่วยทำตามแผน',
			'home_compare_sub'     => 'ตารางนี้ไม่ได้บอกว่าแบบไหนได้ผลดีกว่า แต่ช่วยให้เห็นว่าแต่ละแบบใช้เวลา แรง และวินัยต่างกันอย่างไร',
			'home_compare_rows'    => "เรื่อง | เทรดเอง | ใช้ EA BULL\n"
				. "เวลาเฝ้าจอ | ต้องดูกราฟเองตลอดช่วงที่อยากเทรด | EA ดูราคาให้ คุณเช็กบัญชีเป็นช่วง ๆ\n"
				. "วินัยตามแผน | ขึ้นกับอารมณ์และความเหนื่อยในวันนั้น | ทำตามเงื่อนไขเดิมทุกครั้ง\n"
				. "การส่งคำสั่ง | ต้องกดเองทีละคำสั่ง | ส่งคำสั่งทันทีเมื่อเงื่อนไขครบ\n"
				. "ช่วงที่คุณไม่ว่าง | หยุดตามเวลาของคุณ | ทำงานต่อได้เมื่อรันบน VPS\n"
				. "การคุมความเสี่ยง | คำนวณเองทุกครั้งก่อนเปิดออเดอร์ | เลือกโหมดและปรับทุนคิดไม้บนแดชบอร์ด\n"
				. 'โอกาสขาดทุน | มี | มี เช่นเดียวกัน',
			'home_compare_note'    => 'EA เป็นเครื่องมือช่วยทำตามแผน ไม่ได้ลบความเสี่ยงของตลาดออกไป ทั้งสองแบบขาดทุนได้',

			/* ---------- [ready] เช็กลิสต์พร้อมเริ่ม ---------- */
			'show_home_ready'      => true,
			'home_ready_title'     => 'พร้อมเริ่มใช้ EA BULL หรือยัง? เช็กได้ใน 1 นาที',
			'home_ready_sub'       => 'ติ๊กข้อที่คุณมีแล้ว ข้อไหนยังขาด กดลิงก์ไปดูวิธีเตรียมได้ทันที',
			'home_ready_items'     => "มีบัญชีเทรดแบบ MT5 แล้ว | /open-mt5-account/\n"
				. "ติดตั้ง MT5 บน Windows หรือ VPS ได้ | /mt5-login/\n"
				. "มีเครื่องที่เปิด MT5 ค้างไว้ได้ทั้งวัน หรือพร้อมเช่า VPS | /vps-windows/\n"
				. "อ่านคำเตือนความเสี่ยงแล้ว และเข้าใจว่าขาดทุนได้ | /risk-disclosure/\n"
				. "ใช้เฉพาะเงินที่เสียไปแล้วไม่กระทบชีวิตประจำวัน |\n"
				. 'พร้อมเริ่มกับบัญชีเดโมหรือบัญชีเล็กก่อน | /forward-test/',
			'home_ready_link_text' => 'ดูวิธีเตรียม',
			'home_ready_progress'  => 'พร้อมแล้ว {n} จาก {total} ข้อ',
			'home_ready_done'      => 'ครบทุกข้อแล้ว ทัก LINE ให้ทีมงานช่วยเริ่มได้เลย',
			'home_ready_btn'       => 'ทัก LINE เริ่มใช้งาน',

			/* ---------- [articles] บทความล่าสุด ---------- */
			'show_home_articles'   => true,
			'home_articles_title'  => 'บทความล่าสุดจาก EA BULL',
			'home_articles_sub'    => 'ความรู้เรื่องวางแผน ติดตามบัญชี และบริหารความเสี่ยงของการใช้ EA บน MT5 อ่านฟรีทุกเรื่อง',
			'home_articles_more'   => 'ดูบทความทั้งหมด',
			'home_articles_count'  => 3,

			/* ---------- FAQ ข้อ 11 ถึง 14 (ต่อจากหมวด 2.7) ---------- */
			'faq11_q' => 'EA BULL ทำงานกับสินทรัพย์อะไร?',
			'faq11_a' => 'EA BULL ออกแบบให้ทำงานบนกราฟ XAUUSD หรือทองคำ กรอบเวลา M1 ในโปรแกรม MetaTrader 5 ชื่อสัญลักษณ์อาจมีตัวท้ายต่างกันตามประเภทบัญชี เช่น XAUUSD.c ในบัญชีเซนต์ หรือ XAUUSD.s ในบัญชีมาตรฐาน ให้ใช้ชื่อที่เห็นใน Market Watch ของบัญชีคุณ',
			'faq12_q' => 'โหมด Lite, Advance และ Full ต่างกันอย่างไร?',
			'faq12_a' => 'ทั้งสามโหมดอยู่ในไฟล์เดียวกันและออกแบบไว้ที่ทุนคิดไม้เท่ากัน ต่างกันที่ขนาดออเดอร์และเงินในบัญชีที่ควรมี โหมด Lite เป็นค่าเริ่มต้น เงินในบัญชีที่แนะนำ 1 เท่าของทุนคิดไม้ โหมด Advance 2 เท่า และโหมด Full 3 เท่า ยิ่งโหมดสูงออเดอร์ยิ่งใหญ่ ทั้งกำไรและติดลบลอยเป็นเงินยิ่งมาก ใส่เงินน้อยกว่าที่แนะนำเสี่ยงพอร์ตแตก เปลี่ยนได้จากปุ่มบนแดชบอร์ด ทุกโหมดขาดทุนได้ และควรคุยกับทีมงานก่อนเปลี่ยน',
			'faq13_q' => 'ทำไมต้องติ๊ก Allow DLL imports?',
			'faq13_a' => 'EA BULL ต้องใช้ DLL ในการทำงาน ถ้าไม่ติ๊ก EA จะโหลดไม่ขึ้นทั้งบนกราฟและใน Strategy Tester ต้องติ๊กสองที่ คือ Tools ไปที่ Options แท็บ Expert Advisors และในแท็บ Dependencies ตอนลาก EA ลงกราฟ ขั้นตอนพร้อมภาพอยู่ในคู่มือติดตั้ง',
			'faq14_q' => 'ค่าแพ็กเกจจ่ายครั้งเดียว หรือต้องจ่ายรายเดือน?',
			'faq14_a' => 'จ่ายครั้งเดียว แพ็กเกจ Pro และ VIP ไม่มีค่าบริการรายเดือนหรือรายปี ส่วน Starter เป็นการปรึกษาฟรี สิทธิ์ใช้งานในระบบมีวันหมดอายุซึ่งการ์ดล็อกอินแสดงไว้ เมื่อใกล้ครบกำหนด แจ้งทีมงานทาง LINE เพื่อต่ออายุได้โดยไม่มีค่าใช้จ่ายเพิ่ม ค่าแพ็กเกจที่ชำระแล้วไม่มีการคืนเงิน จึงควรปรึกษาทีมงานและอ่านข้อกำหนดให้ครบก่อนตัดสินใจ',
		)
	);
}

/* ==============================================================
 * Customizer · หมวด 2.9 ถึง 2.14 (ต่อจากหมวดหน้าแรกเดิม) + FAQ ข้อ 11 ถึง 14
 * ============================================================== */

add_filter( 'eab_customizer_sections', 'eab_homeplus_customizer_sections', 11, 2 );

function eab_homeplus_customizer_sections( $sections, $d ) {
	$rule  = 'ห้ามใส่ตัวเลขผลเทรด เปอร์เซ็นต์กำไร หรือคำสัญญาว่าได้กำไร';
	$link  = 'slug ในเว็บ (เช่น /pricing/) หรือ URL เต็ม';
	$plus  = array(
		'eab_home_story'    => array(
			'title'  => '2.9) หน้าแรก · EA BULL คืออะไร (เนื้อหายาว)',
			'fields' => array(
				'show_home_story'   => array( 'แสดงส่วนนี้', 'checkbox' ),
				'home_story_title'  => array( 'หัวข้อ (H2)', 'text' ),
				'home_story_lead'   => array( 'ย่อหน้าเกริ่น', 'textarea', $rule ),
				'home_story_blocks' => array( 'หัวข้อย่อย (บรรทัดละ 1 ข้อ: หัวข้อ | เนื้อหา)', 'textarea', $rule ),
				'home_story_img'        => array( 'แผนภาพ · จอคอม (แนวนอน · ว่าง = ซ่อน)', 'image', 'ค่าเริ่มต้น: แผนภาพ 4 ขั้นของธีม (1600×760)' ),
				'home_story_img_mobile' => array( 'แผนภาพ · จอมือถือ (แนวตั้ง · ว่าง = ใช้ภาพจอคอม)', 'image' ),
				'home_story_img_alt'    => array( 'คำอธิบายแผนภาพ (alt)', 'text' ),
				'home_story_btn'    => array( 'ปุ่มท้ายส่วน · ข้อความ (เว้นว่าง = ซ่อน)', 'text' ),
				'home_story_url'    => array( 'ปุ่มท้ายส่วน · ลิงก์', 'path', $link ),
			),
		),
		'eab_home_modes'    => array(
			'title'  => '2.10) หน้าแรก · โหมด Lite / Advance / Full (แท็บสลับ)',
			'fields' => array(
				'show_home_modes'   => array( 'แสดงส่วนนี้', 'checkbox' ),
				'home_modes_title'  => array( 'หัวข้อ (H2)', 'text' ),
				'home_modes_sub'    => array( 'คำอธิบายใต้หัวข้อ', 'textarea' ),
				'home_mode1_label'  => array( 'แท็บ 1 · ชื่อโหมด (ว่าง = ซ่อนแท็บนี้)', 'text' ),
				'home_mode1_tag'    => array( 'แท็บ 1 · ป้ายเล็ก', 'text' ),
				'home_mode1_title'  => array( 'แท็บ 1 · หัวข้อ', 'text' ),
				'home_mode1_text'   => array( 'แท็บ 1 · คำอธิบาย', 'textarea', $rule ),
				'home_mode1_points' => array( 'แท็บ 1 · จุดสำคัญ (บรรทัดละ 1 ข้อ)', 'textarea', $rule ),
				'home_mode1_img'     => array( 'แท็บ 1 · ภาพ (ไม่ใส่ = ภาพวาดเส้นทางบิน)', 'image', 'ภาพแดชบอร์ดตอนเลือกโหมดนี้ · ไม่มีเลขบัญชีหรือยอดเงิน' ),
				'home_mode1_img_alt' => array( 'แท็บ 1 · คำอธิบายภาพ (alt)', 'text' ),
				'home_mode2_label'  => array( 'แท็บ 2 · ชื่อโหมด (ว่าง = ซ่อนแท็บนี้)', 'text' ),
				'home_mode2_tag'    => array( 'แท็บ 2 · ป้ายเล็ก', 'text' ),
				'home_mode2_title'  => array( 'แท็บ 2 · หัวข้อ', 'text' ),
				'home_mode2_text'   => array( 'แท็บ 2 · คำอธิบาย', 'textarea', $rule ),
				'home_mode2_points' => array( 'แท็บ 2 · จุดสำคัญ (บรรทัดละ 1 ข้อ)', 'textarea', $rule ),
				'home_mode2_img'     => array( 'แท็บ 2 · ภาพ (ไม่ใส่ = ภาพวาดเส้นทางบิน)', 'image', 'ภาพแดชบอร์ดตอนเลือกโหมดนี้ · ไม่มีเลขบัญชีหรือยอดเงิน' ),
				'home_mode2_img_alt' => array( 'แท็บ 2 · คำอธิบายภาพ (alt)', 'text' ),
				'home_mode3_label'  => array( 'แท็บ 3 · ชื่อโหมด (ว่าง = ซ่อนแท็บนี้)', 'text' ),
				'home_mode3_tag'    => array( 'แท็บ 3 · ป้ายเล็ก', 'text' ),
				'home_mode3_title'  => array( 'แท็บ 3 · หัวข้อ', 'text' ),
				'home_mode3_text'   => array( 'แท็บ 3 · คำอธิบาย', 'textarea', $rule ),
				'home_mode3_points' => array( 'แท็บ 3 · จุดสำคัญ (บรรทัดละ 1 ข้อ)', 'textarea', $rule ),
				'home_mode3_img'     => array( 'แท็บ 3 · ภาพ (ไม่ใส่ = ภาพวาดเส้นทางบิน)', 'image', 'ภาพแดชบอร์ดตอนเลือกโหมดนี้ · ไม่มีเลขบัญชีหรือยอดเงิน' ),
				'home_mode3_img_alt' => array( 'แท็บ 3 · คำอธิบายภาพ (alt)', 'text' ),
				'home_modes_img_cap' => array( 'คำบรรยายใต้ภาพทุกแท็บ', 'text' ),
				'home_modes_note'   => array( 'หมายเหตุท้ายส่วน', 'textarea' ),
			),
		),
		'eab_home_license'  => array(
			'title'  => '2.11) หน้าแรก · การ์ดล็อกอิน (แตะเปลี่ยนสี)',
			'fields' => array(
				'show_home_license'        => array( 'แสดงส่วนนี้', 'checkbox' ),
				'home_license_title'       => array( 'หัวข้อ (H2)', 'text' ),
				'home_license_sub'         => array( 'คำอธิบายใต้หัวข้อ', 'textarea' ),
				'home_license_states'      => array( 'สถานะ (บรรทัดละ 1 ข้อ: green หรือ yellow หรือ red | ชื่อ | คำอธิบาย)', 'textarea' ),
				'home_license_steps_title' => array( 'หัวข้อขั้นตอนขอสิทธิ์', 'text' ),
				'home_license_steps'       => array( 'ขั้นตอนขอสิทธิ์ (บรรทัดละ 1 ขั้น)', 'textarea' ),
				'home_license_card_title'  => array( 'การ์ดจำลอง · ชื่อการ์ด', 'text' ),
				'home_license_account'     => array( 'การ์ดจำลอง · บรรทัดเลขบัญชี (ห้ามใส่เลขจริง)', 'text' ),
				'home_license_caption'     => array( 'คำบรรยายใต้การ์ดจำลอง (จำเป็น)', 'text' ),
			),
		),
		'eab_home_compare'  => array(
			'title'  => '2.12) หน้าแรก · ตารางเทรดเอง กับ EA BULL',
			'fields' => array(
				'show_home_compare'  => array( 'แสดงส่วนนี้', 'checkbox' ),
				'home_compare_title' => array( 'หัวข้อ (H2)', 'text' ),
				'home_compare_sub'   => array( 'คำอธิบายใต้หัวข้อ', 'textarea' ),
				'home_compare_rows'  => array( 'ตาราง (บรรทัดแรกคือหัวตาราง · คั่นช่องด้วย | )', 'textarea', $rule ),
				'home_compare_note'  => array( 'หมายเหตุใต้ตาราง (ห้ามลบเรื่องขาดทุน)', 'textarea' ),
			),
		),
		'eab_home_ready'    => array(
			'title'  => '2.13) หน้าแรก · เช็กลิสต์พร้อมเริ่ม',
			'fields' => array(
				'show_home_ready'      => array( 'แสดงส่วนนี้', 'checkbox' ),
				'home_ready_title'     => array( 'หัวข้อ (H2)', 'text' ),
				'home_ready_sub'       => array( 'คำอธิบายใต้หัวข้อ', 'textarea' ),
				'home_ready_items'     => array( 'รายการ (บรรทัดละ 1 ข้อ: ข้อความ | ลิงก์คู่มือ หรือเว้นว่าง)', 'textarea' ),
				'home_ready_link_text' => array( 'ข้อความลิงก์คู่มือท้ายแต่ละข้อ', 'text' ),
				'home_ready_progress'  => array( 'ข้อความความคืบหน้า ({n} = ที่ติ๊ก · {total} = ทั้งหมด)', 'text' ),
				'home_ready_done'      => array( 'ข้อความเมื่อติ๊กครบ', 'text' ),
				'home_ready_btn'       => array( 'ปุ่ม LINE เมื่อติ๊กครบ', 'text' ),
			),
		),
		'eab_home_articles' => array(
			'title'  => '2.14) หน้าแรก · บทความล่าสุด',
			'fields' => array(
				'show_home_articles'  => array( 'แสดงส่วนนี้', 'checkbox' ),
				'home_articles_title' => array( 'หัวข้อ (H2)', 'text' ),
				'home_articles_sub'   => array( 'คำอธิบายใต้หัวข้อ', 'textarea' ),
				'home_articles_more'  => array( 'ปุ่มไปหน้าบทความทั้งหมด (เว้นว่าง = ซ่อน)', 'text' ),
				'home_articles_count' => array( 'จำนวนบทความ (3 หรือ 6)', 'number' ),
			),
		),
	);

	if ( isset( $sections['eab_home_faq']['fields'] ) ) {
		for ( $i = 11; $i <= 14; $i++ ) {
			$sections['eab_home_faq']['fields'][ 'faq' . $i . '_q' ] = array( 'คำถามข้อ ' . $i, 'text' );
			$sections['eab_home_faq']['fields'][ 'faq' . $i . '_a' ] = array( 'คำตอบข้อ ' . $i, 'textarea', $rule );
		}
	} else {
		$plus['eab_home_faq_more'] = array(
			'title'  => '2.7.1) หน้าแรก · FAQ ข้อ 11 ถึง 14',
			'fields' => array(),
		);
		for ( $i = 11; $i <= 14; $i++ ) {
			$plus['eab_home_faq_more']['fields'][ 'faq' . $i . '_q' ] = array( 'คำถามข้อ ' . $i, 'text' );
			$plus['eab_home_faq_more']['fields'][ 'faq' . $i . '_a' ] = array( 'คำตอบข้อ ' . $i, 'textarea', $rule );
		}
	}

	/* แทรกหลังหมวด FAQ ของหน้าแรก ให้ลำดับในแผงปรับแต่งเรียงตามหน้า */
	$out = array();
	foreach ( $sections as $sid => $section ) {
		$out[ $sid ] = $section;
		if ( 'eab_home_faq' === $sid ) {
			$out = array_merge( $out, $plus );
			$plus = array();
		}
	}
	return array_merge( $out, $plus );
}

/* ==============================================================
 * ตัวช่วย
 * ============================================================== */

/**
 * จำนวนข้อ FAQ ของหน้าแรก (ใช้ทั้งการแสดงผลและ FAQ schema)
 */
function eab_home_faq_count() {
	return 14;
}

/**
 * แยกบรรทัด "ก | ข | ค" เป็นอาร์เรย์ (ตัดช่องว่าง · ข้ามบรรทัดว่าง)
 */
function eab_homeplus_rows( $text, $min = 1 ) {
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
 * หัวแผงของส่วนเสริม (H2 + คำอธิบาย) · ใช้หน้าตาเดียวกับ eab_home_head
 */
function eab_homeplus_head( $id, $title, $sub = '' ) {
	eab_home_head( $id, '', $title, $sub );
}

/**
 * พิมพ์ส่วนเสริมตามชื่อ · front-page.php เรียกตามตำแหน่ง
 */
function eab_homeplus_section( $name ) {
	$fn = 'eab_homeplus_' . $name;
	if ( function_exists( $fn ) && eab_mod( 'show_home_' . $name ) ) {
		call_user_func( $fn );
	}
}

/* ==============================================================
 * [story] EA BULL คืออะไร
 * ============================================================== */
function eab_homeplus_story() {
	$blocks = eab_homeplus_rows( eab_mod( 'home_story_blocks' ), 2 );
	$icons  = array( 'chart', 'gear', 'shield', 'lock' );
	$tiles  = array( 'gold', 'sky', 'navy', 'blue' );
	$btn    = eab_home_link( eab_mod( 'home_story_url' ) );
	?>
<section class="glass-panel hm-panel hp-story" id="story" aria-labelledby="hp-story-title">
	<?php eab_homeplus_head( 'hp-story-title', eab_mod( 'home_story_title' ) ); ?>
	<?php if ( '' !== trim( (string) eab_mod( 'home_story_lead' ) ) ) : ?>
		<p class="hp-lead"><?php echo eab_text( eab_mod( 'home_story_lead' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></p>
	<?php endif; ?>
	<?php
	$img     = eab_theme_asset_url( eab_mod( 'home_story_img' ) );
	$img_m   = eab_theme_asset_url( eab_mod( 'home_story_img_mobile' ) );
	$img_alt = trim( (string) eab_mod( 'home_story_img_alt' ) );
	?>
	<?php if ( '' !== $img ) : ?>
		<figure class="hp-story-figure">
			<picture>
				<?php if ( '' !== $img_m ) : ?>
					<source media="(max-width: 680px)" srcset="<?php echo esc_url( $img_m ); ?>" width="800" height="1140">
				<?php endif; ?>
				<img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $img_alt ); ?>" width="1600" height="760" loading="lazy" decoding="async">
			</picture>
		</figure>
	<?php endif; ?>
	<?php if ( $blocks ) : ?>
		<div class="hp-story-grid">
			<?php foreach ( $blocks as $i => $block ) : ?>
				<article class="hp-story-item">
					<span class="tile tile--<?php echo esc_attr( $tiles[ $i % 4 ] ); ?>" aria-hidden="true"><?php echo eab_home_icon( $icons[ $i % 4 ], 'icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<h3 class="hp-h3"><?php echo eab_text( $block[0] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></h3>
					<p><?php echo eab_text( isset( $block[1] ) ? $block[1] : '' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
	<?php if ( '' !== $btn ) : ?>
		<div class="hp-actions"><?php eab_home_btn( eab_mod( 'home_story_btn' ), $btn, 'btn btn-ghost hm-btn hm-btn--sm', 'arrow-ur' ); ?></div>
	<?php endif; ?>
</section>
	<?php
}

/* ==============================================================
 * [modes] Lite / Advance / Full · แท็บสลับ (ไม่มี JS = แสดงทุกแผง)
 * ============================================================== */
function eab_homeplus_modes() {
	$modes = array();
	for ( $i = 1; $i <= 3; $i++ ) {
		$label = trim( (string) eab_mod( 'home_mode' . $i . '_label' ) );
		if ( '' === $label ) {
			continue;
		}
		$modes[ $i ] = array(
			'label'  => $label,
			'tag'    => trim( (string) eab_mod( 'home_mode' . $i . '_tag' ) ),
			'title'  => trim( (string) eab_mod( 'home_mode' . $i . '_title' ) ),
			'text'   => trim( (string) eab_mod( 'home_mode' . $i . '_text' ) ),
			'points' => eab_lines( eab_mod( 'home_mode' . $i . '_points' ) ),
			'img'    => eab_theme_asset_url( eab_mod( 'home_mode' . $i . '_img' ) ), // assets/... = ภาพที่มากับธีม
			'alt'    => trim( (string) eab_mod( 'home_mode' . $i . '_img_alt' ) ),
		);
	}
	if ( ! $modes ) {
		return;
	}
	$note    = trim( (string) eab_mod( 'home_modes_note' ) );
	$img_cap = trim( (string) eab_mod( 'home_modes_img_cap' ) );
	$first   = (int) key( $modes );
	/* ภาพวาดสำรอง (ใช้เมื่อแท็บไม่มีภาพ): ระดับความสูงของเส้นทางบิน ต่ำ กลาง สูง */
	$alt     = array(
		1 => array( 'low', 'M14 128 C70 124 120 112 200 100', 'translate(176 86)' ),
		2 => array( 'mid', 'M14 128 C70 118 120 88 200 66', 'translate(176 52)' ),
		3 => array( 'high', 'M14 128 C70 110 120 60 200 30', 'translate(176 16)' ),
	);
	?>
<section class="glass-panel hm-panel hp-modes" id="modes" aria-labelledby="hp-modes-title" data-hp-tabs>
	<?php eab_homeplus_head( 'hp-modes-title', eab_mod( 'home_modes_title' ), eab_mod( 'home_modes_sub' ) ); ?>
	<div class="hp-modes-body">
		<div class="hp-modes-tabs" role="tablist" aria-label="<?php echo esc_attr( wp_strip_all_tags( (string) eab_mod( 'home_modes_title' ) ) ); ?>" hidden>
			<?php foreach ( $modes as $i => $mode ) : ?>
				<button type="button" class="hp-mode-tab" role="tab" id="hp-mode-tab-<?php echo (int) $i; ?>" aria-controls="hp-mode-<?php echo (int) $i; ?>" aria-selected="<?php echo $first === $i ? 'true' : 'false'; ?>" tabindex="<?php echo $first === $i ? '0' : '-1'; ?>">
					<span class="hp-mode-tab-name"><?php echo esc_html( $mode['label'] ); ?></span>
					<?php if ( '' !== $mode['tag'] ) : ?>
						<span class="hp-mode-tab-tag"><?php echo esc_html( $mode['tag'] ); ?></span>
					<?php endif; ?>
				</button>
			<?php endforeach; ?>
		</div>
		<div class="hp-modes-panels">
			<?php foreach ( $modes as $i => $mode ) : ?>
				<div class="hp-mode hp-mode--<?php echo (int) $i; ?>" id="hp-mode-<?php echo (int) $i; ?>" role="tabpanel" aria-labelledby="hp-mode-tab-<?php echo (int) $i; ?>" data-alt="<?php echo esc_attr( $alt[ $i ][0] ); ?>">
					<?php if ( '' !== $mode['img'] ) : ?>
						<?php $info = eab_media_info( $mode['img'], 852, 422 ); ?>
						<figure class="hp-shot">
							<span class="hp-shot-frame">
								<img src="<?php echo esc_url( $mode['img'] ); ?>" alt="<?php echo esc_attr( '' !== $mode['alt'] ? $mode['alt'] : $mode['label'] ); ?>" width="<?php echo (int) $info['width']; ?>" height="<?php echo (int) $info['height']; ?>" loading="lazy" decoding="async">
							</span>
							<?php if ( '' !== $img_cap ) : ?>
								<figcaption><?php echo esc_html( $img_cap ); ?></figcaption>
							<?php endif; ?>
						</figure>
					<?php else : ?>
					<div class="hp-alt" aria-hidden="true">
						<svg viewBox="0 0 220 150" focusable="false">
							<path class="hp-alt-line" d="M10 30H210M10 75H210M10 120H210"/>
							<path class="hp-alt-path" d="<?php echo esc_attr( $alt[ $i ][1] ); ?>"/>
							<g class="hp-alt-wing" transform="<?php echo esc_attr( $alt[ $i ][2] ); ?>">
								<path d="M0 14 C10 6 20 2 32 0 C24 6 18 10 14 16 Z" />
								<path d="M10 16 C18 12 26 10 36 10 C28 14 22 18 18 22 Z" opacity=".7"/>
							</g>
						</svg>
						<span class="hp-alt-label"><?php echo esc_html( $mode['tag'] ); ?></span>
					</div>
					<?php endif; ?>
					<div class="hp-mode-copy">
						<p class="hp-mode-name"><?php echo esc_html( $mode['label'] ); ?></p>
						<?php if ( '' !== $mode['title'] ) : ?>
							<h3 class="hp-h3"><?php echo esc_html( $mode['title'] ); ?></h3>
						<?php endif; ?>
						<?php if ( '' !== $mode['text'] ) : ?>
							<p><?php echo esc_html( $mode['text'] ); ?></p>
						<?php endif; ?>
						<?php if ( $mode['points'] ) : ?>
							<ul class="hp-checks">
								<?php foreach ( $mode['points'] as $point ) : ?>
									<li><?php echo eab_icon( 'check', 'icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo esc_html( $point ); ?></span></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<?php if ( '' !== $note ) : ?>
		<p class="hm-note"><?php echo eab_icon( 'warn', 'icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo esc_html( $note ); ?></span></p>
	<?php endif; ?>
</section>
	<?php
}

/* ==============================================================
 * [license] การ์ดล็อกอินจำลอง · แตะสีเพื่อเปลี่ยนสถานะ
 * ============================================================== */
function eab_homeplus_license() {
	$states = array();
	foreach ( eab_homeplus_rows( eab_mod( 'home_license_states' ), 2 ) as $row ) {
		$tone = in_array( strtolower( $row[0] ), array( 'green', 'yellow', 'red' ), true ) ? strtolower( $row[0] ) : 'green';
		$states[] = array(
			'tone' => $tone,
			'name' => isset( $row[1] ) ? $row[1] : '',
			'text' => isset( $row[2] ) ? $row[2] : '',
		);
	}
	if ( ! $states ) {
		return;
	}
	$steps = eab_lines( eab_mod( 'home_license_steps' ) );
	?>
<section class="glass-panel hm-panel hp-license" id="license" aria-labelledby="hp-license-title" data-hp-license>
	<?php eab_homeplus_head( 'hp-license-title', eab_mod( 'home_license_title' ), eab_mod( 'home_license_sub' ) ); ?>
	<div class="hp-license-grid">
		<figure class="hp-license-demo">
			<div class="hp-lcard" data-tone="<?php echo esc_attr( $states[0]['tone'] ); ?>" aria-hidden="true">
				<div class="hp-lcard-head">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/brand/eabull-icon-180.png' ); ?>" alt="" width="36" height="36" loading="lazy" decoding="async">
					<span class="hp-lcard-title"><?php echo esc_html( eab_mod( 'home_license_card_title' ) ); ?></span>
					<span class="hp-lcard-dot"></span>
				</div>
				<p class="hp-lcard-status"><?php echo esc_html( $states[0]['name'] ); ?></p>
				<p class="hp-lcard-acc"><?php echo esc_html( eab_mod( 'home_license_account' ) ); ?></p>
				<span class="hp-lcard-bar"><span></span></span>
			</div>
			<?php if ( '' !== trim( (string) eab_mod( 'home_license_caption' ) ) ) : ?>
				<figcaption><?php echo esc_html( eab_mod( 'home_license_caption' ) ); ?></figcaption>
			<?php endif; ?>
		</figure>
		<div class="hp-license-side">
			<ul class="hp-states">
				<?php foreach ( $states as $i => $state ) : ?>
					<li>
						<button type="button" class="hp-state" data-tone="<?php echo esc_attr( $state['tone'] ); ?>" data-name="<?php echo esc_attr( $state['name'] ); ?>" aria-pressed="<?php echo 0 === $i ? 'true' : 'false'; ?>">
							<span class="hp-state-dot" aria-hidden="true"></span>
							<span class="hp-state-text"><strong><?php echo esc_html( $state['name'] ); ?></strong><span><?php echo esc_html( $state['text'] ); ?></span></span>
						</button>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php if ( $steps ) : ?>
				<div class="hp-steps">
					<?php if ( '' !== trim( (string) eab_mod( 'home_license_steps_title' ) ) ) : ?>
						<h3 class="hp-h3"><?php echo esc_html( eab_mod( 'home_license_steps_title' ) ); ?></h3>
					<?php endif; ?>
					<ol>
						<?php foreach ( $steps as $step ) : ?>
							<li><?php echo esc_html( $step ); ?></li>
						<?php endforeach; ?>
					</ol>
					<?php eab_home_contact_link( eab_mod( 'go_license_line_label' ), 'btn hm-btn hm-btn--sm hp-line-btn', 'home-license' ); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
	<?php
}

/* ==============================================================
 * [compare] ตารางเทรดเอง กับ EA BULL
 * ============================================================== */
function eab_homeplus_compare() {
	$rows = eab_homeplus_rows( eab_mod( 'home_compare_rows' ), 2 );
	if ( count( $rows ) < 2 ) {
		return;
	}
	$head = array_shift( $rows );
	$note = trim( (string) eab_mod( 'home_compare_note' ) );
	?>
<section class="glass-panel hm-panel hp-compare" id="compare" aria-labelledby="hp-compare-title">
	<?php eab_homeplus_head( 'hp-compare-title', eab_mod( 'home_compare_title' ), eab_mod( 'home_compare_sub' ) ); ?>
	<div class="hp-table-wrap">
		<table class="hp-table">
			<thead>
				<tr>
					<?php foreach ( $head as $c => $cell ) : ?>
						<th scope="col"<?php echo 2 === $c ? ' class="is-wing"' : ''; ?>><?php echo esc_html( $cell ); ?></th>
					<?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $rows as $row ) : ?>
					<tr>
						<?php foreach ( array_slice( array_pad( $row, count( $head ), '' ), 0, count( $head ) ) as $c => $cell ) : ?>
							<?php if ( 0 === $c ) : ?>
								<th scope="row"><?php echo esc_html( $cell ); ?></th>
							<?php else : ?>
								<td<?php echo 2 === $c ? ' class="is-wing"' : ''; ?> data-label="<?php echo esc_attr( $head[ $c ] ); ?>"><?php echo esc_html( $cell ); ?></td>
							<?php endif; ?>
						<?php endforeach; ?>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php if ( '' !== $note ) : ?>
		<p class="hm-note"><?php echo eab_icon( 'warn', 'icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo esc_html( $note ); ?></span></p>
	<?php endif; ?>
</section>
	<?php
}

/* ==============================================================
 * [ready] เช็กลิสต์พร้อมเริ่ม · ไม่มี JS = รายการพร้อมลิงก์
 * ============================================================== */
function eab_homeplus_ready() {
	$items = eab_homeplus_rows( eab_mod( 'home_ready_items' ), 1 );
	if ( ! $items ) {
		return;
	}
	$total = count( $items );
	$prog  = (string) eab_mod( 'home_ready_progress' );
	?>
<section class="glass-panel hm-panel hp-ready" id="ready" aria-labelledby="hp-ready-title" data-hp-ready data-progress="<?php echo esc_attr( $prog ); ?>">
	<?php eab_homeplus_head( 'hp-ready-title', eab_mod( 'home_ready_title' ), eab_mod( 'home_ready_sub' ) ); ?>
	<div class="hp-ready-grid">
		<ul class="hp-ready-list">
			<?php foreach ( $items as $i => $item ) : ?>
				<?php $url = isset( $item[1] ) ? eab_home_link( $item[1] ) : ''; ?>
				<li class="hp-ready-item">
					<label>
						<input type="checkbox" class="hp-ready-box">
						<span class="hp-ready-tick" aria-hidden="true"><?php echo eab_icon( 'check', 'icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<span class="hp-ready-text"><?php echo esc_html( $item[0] ); ?></span>
					</label>
					<?php if ( '' !== $url && '' !== trim( (string) eab_mod( 'home_ready_link_text' ) ) ) : ?>
						<a class="hp-ready-link" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( eab_mod( 'home_ready_link_text' ) ); ?></a>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
		<div class="hp-ready-meter">
			<div class="hp-ring" style="--p:0" aria-hidden="true">
				<svg viewBox="0 0 120 120" focusable="false"><circle class="hp-ring-bg" cx="60" cy="60" r="50"/><circle class="hp-ring-fg" cx="60" cy="60" r="50" pathLength="100"/></svg>
				<span class="hp-ring-num"><b>0</b>/<?php echo (int) $total; ?></span>
			</div>
			<p class="hp-ready-status" aria-live="polite"><?php echo esc_html( str_replace( array( '{n}', '{total}' ), array( '0', (string) $total ), $prog ) ); ?></p>
			<div class="hp-ready-done" hidden>
				<p><?php echo esc_html( eab_mod( 'home_ready_done' ) ); ?></p>
				<?php eab_home_contact_link( eab_mod( 'home_ready_btn' ), 'btn hm-btn hp-line-btn', 'home-ready' ); ?>
			</div>
		</div>
	</div>
</section>
	<?php
}

/* ==============================================================
 * [articles] บทความล่าสุด (ลิงก์ภายใน · ภาพปก · หมวด)
 * ============================================================== */
function eab_homeplus_articles() {
	if ( ! class_exists( 'WP_Query' ) ) {
		return;
	}
	$count = (int) eab_mod( 'home_articles_count' );
	$count = $count >= 6 ? 6 : 3;
	$query = new WP_Query(
		array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => $count,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);
	if ( empty( $query->posts ) ) {
		return;
	}
	$archive = (int) get_option( 'page_for_posts' ) ? get_permalink( (int) get_option( 'page_for_posts' ) ) : '';
	?>
<section class="glass-panel hm-panel hp-articles" id="latest" aria-labelledby="hp-articles-title">
	<?php eab_home_head( 'hp-articles-title', '', eab_mod( 'home_articles_title' ), eab_mod( 'home_articles_sub' ), $archive ? eab_mod( 'home_articles_more' ) : '', $archive ); ?>
	<ul class="hp-posts">
		<?php foreach ( $query->posts as $post ) : ?>
			<?php
			$link  = get_permalink( $post );
			$thumb = get_the_post_thumbnail_url( $post, 'medium_large' );
			$cats  = get_the_category( $post->ID );
			$cat   = ! empty( $cats ) ? $cats[0]->name : '';
			?>
			<li>
				<a class="hp-post" href="<?php echo esc_url( $link ); ?>">
					<?php if ( $thumb ) : ?>
						<img class="hp-post-img" src="<?php echo esc_url( $thumb ); ?>" alt="" width="768" height="432" loading="lazy" decoding="async">
					<?php endif; ?>
					<span class="hp-post-body">
						<?php if ( '' !== $cat ) : ?>
							<span class="hp-post-cat"><?php echo esc_html( $cat ); ?></span>
						<?php endif; ?>
						<span class="hp-post-title"><?php echo esc_html( get_the_title( $post ) ); ?></span>
						<span class="hp-post-excerpt"><?php echo esc_html( wp_trim_words( wp_strip_all_tags( get_the_excerpt( $post ) ), 22, '…' ) ); ?></span>
					</span>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</section>
	<?php
	wp_reset_postdata();
}
