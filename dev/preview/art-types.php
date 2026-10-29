<?php
/**
 * แผนภาพประจำเสาหลักของแต่ละกลุ่มบทความ (docs/content-plan.md) · ใช้กับ dev/preview/art.php
 * ชนิดภาพ: map (หน้าจอ MT5) · columns (3 คอลัมน์ + รายการ) · stack (ชั้นซ้อน) · cycle (วงจร 6 ขั้น)
 *          timeline (24 ชั่วโมงตามเวลาไทย) · grid (การ์ด 6 ใบ) · ชนิด steps ใช้แม่แบบเดิมใน art.php
 * ไม่มีตัวเลขผลเทรด · เวลาตลาดเป็นภาพรวมโดยประมาณ (ระบุในภาพ)
 *
 * @var string $fx_art_name
 */

function eab_art_pillar_data() {
	return array(
		'ea-loop'          => array(
			'type'  => 'steps',
			'title' => 'EA ทำงานอย่างไรใน MT5',
			'sub'   => 'วงจรเดิมที่เกิดซ้ำทุกครั้งที่ราคาขยับ',
			'steps' => array(
				array( 'pulse', 'ราคาใหม่เข้ามา', 'MT5 รับราคาจากเซิร์ฟเวอร์ของโบรกเกอร์ทีละ tick' ),
				array( 'cpu', 'EA ตรวจเงื่อนไข', 'อ่านราคา บัญชี และออเดอร์ที่เปิดอยู่ตามกฎที่เขียนไว้' ),
				array( 'bolt', 'ส่งคำสั่งเมื่อครบเงื่อนไข', 'เปิด ปิด หรือแก้ออเดอร์ผ่าน MT5' ),
				array( 'server', 'โบรกเกอร์รับคำสั่ง', 'ผลกลับมาที่บัญชี แล้ววนรอราคาถัดไป' ),
			),
			'note'  => 'EA ทำงานเฉพาะตอน MT5 เปิดอยู่ เชื่อมต่อได้ และปุ่ม Algo Trading เป็นสีเขียว',
			'icon'  => 'warn',
		),
		'mt5-map'          => array(
			'type'  => 'map',
			'title' => 'แผนที่หน้าจอ MT5 สำหรับคนใช้ EA',
			'sub'   => 'หกจุดที่ต้องรู้จักก่อนติดตั้งและดูแล EA',
			'items' => array(
				array( 'Algo Trading', 'ปุ่มบนแถบเครื่องมือ', 'สวิตช์รวม ถ้าดับ EA ส่งคำสั่งไม่ได้' ),
				array( 'Market Watch', 'Ctrl+M', 'รายชื่อสัญลักษณ์ คลิกขวาดู Specification' ),
				array( 'Navigator', 'Ctrl+N', 'บัญชีและ Expert Advisors ลาก EA ลงกราฟ' ),
				array( 'กราฟ', 'ที่วาง EA', 'ไอคอนมุมขวาบนบอกว่า EA ทำงานอยู่ไหม' ),
				array( 'Toolbox', 'Ctrl+T', 'แท็บ Trade History Journal และ Experts' ),
				array( 'Strategy Tester', 'Ctrl+R', 'ทดสอบ EA ย้อนหลังก่อนใช้เงินจริง' ),
			),
		),
		'vps-path'         => array(
			'type'  => 'columns',
			'title' => 'VPS สำหรับ EA: เลือก ตั้งค่า ดูแล',
			'sub'   => 'สามช่วงที่ทำให้ EA รันต่อเนื่องทั้งวันทั้งคืน',
			'cols'  => array(
				array( 'server', 'เลือก', array( 'Windows รัน MT5 และ EA ที่ใช้ DLL', 'RAM พอสำหรับ MT5 ทุกตัวที่จะเปิด', 'ที่ตั้งใกล้เซิร์ฟเวอร์ของโบรกเกอร์', 'ผู้ให้บริการที่ติดต่อได้จริง' ) ),
				array( 'gear', 'ตั้งค่า', array( 'ลง MT5 แล้วล็อกอินบัญชีเทรด', 'ติ๊ก Allow DLL imports แล้วแนบ EA', 'ให้ MT5 เปิดเองหลังรีสตาร์ท', 'คุมเวลาของ Windows Update' ) ),
				array( 'pulse', 'ดูแล', array( 'เช็กการ์ดล็อกอินและแท็บ Experts', 'ดู Ping และ RAM เป็นระยะ', 'รีสตาร์ทเฉพาะช่วงตลาดปิด', 'ใช้รหัสผ่านที่เดายาก' ) ),
			),
			'note'  => 'ปิดหน้าต่าง Remote Desktop ได้ แต่อย่าปิด MT5 และอย่า Sign out',
			'icon'  => 'warn',
		),
		'gold-sessions'    => array(
			'type'  => 'timeline',
			'title' => 'หนึ่งวันของทองคำ XAUUSD ตามเวลาไทย',
			'sub'   => 'ช่วงเวลามาตรฐานของสหรัฐฯ (ต้น พ.ย. ถึงกลาง มี.ค.) · ภาพรวมโดยประมาณ',
			'start' => 5,
			'segs'  => array(
				/* ชั่วโมงเริ่ม, ชั่วโมงจบ (นับต่อจาก 24 ได้), ชื่อ, คำอธิบาย, ระดับ 0-3 */
				array( 5, 6, 'พัก', 'ตลาดหยุดราว 1 ชั่วโมง', 0 ),
				array( 6, 15, 'ช่วงเอเชีย', 'ราคามักแกว่งแคบกว่าช่วงอื่น', 1 ),
				array( 15, 20.5, 'ยุโรปเปิด', 'ปริมาณซื้อขายเพิ่มขึ้น', 2 ),
				array( 20.5, 23.5, 'ยุโรปและสหรัฐฯ ซ้อนกัน', 'มักคึกคักที่สุดของวัน', 3 ),
				array( 23.5, 29, 'ช่วงสหรัฐฯ', 'เบาลงจนถึงช่วงพัก', 2 ),
			),
			'marks' => array(
				array( 20.5, '20:30', 'ข่าวเศรษฐกิจสหรัฐฯ หลายตัว' ),
				array( 26, '02:00', 'ผลประชุม FOMC (บางคืน)' ),
			),
			'note'  => 'ช่วงออมแสงของสหรัฐฯ ทุกเวลาเร็วขึ้นราว 1 ชั่วโมง · เวลาจริงของบัญชีดูที่ Specification หัวข้อ Sessions',
			'icon'  => 'clock',
		),
		'risk-layers'      => array(
			'type'   => 'stack',
			'title'  => 'ชั้นป้องกันความเสี่ยงของพอร์ต',
			'sub'    => 'ตั้งจากชั้นเล็กไปชั้นใหญ่ ก่อนที่ Stop Out ของโบรกเกอร์จะตัดสินใจแทน',
			'layers' => array(
				array( 'target', 'ความเสี่ยงต่อออเดอร์', 'ออเดอร์หนึ่งเสียได้ไม่เกินเท่าไร' ),
				array( 'calc', 'ขนาด Lot', 'คิดจากความเสี่ยงและสเปกของสัญลักษณ์' ),
				array( 'gauge', 'เพดานขาดทุนรายวัน', 'ถึงแล้วหยุดเพิ่มความเสี่ยงและทบทวน' ),
				array( 'chart', 'Drawdown ที่รับได้', 'จุดที่ต้องหยุดระบบแล้วประเมินใหม่' ),
				array( 'shield', 'Margin Level และ Stop Out', 'เส้นสุดท้ายที่โบรกเกอร์ปิดออเดอร์ให้' ),
				array( 'dollar', 'เงินเย็น', 'เงินที่เสียทั้งก้อนแล้วชีวิตยังเดินต่อได้' ),
			),
		),
		'plan-cycle'       => array(
			'type'  => 'cycle',
			'title' => 'วงจรแผนการเทรดของคนใช้ EA',
			'sub'   => 'เขียนก่อนเริ่ม ใช้ระหว่างทาง ทบทวนตามรอบ แล้ววนใหม่',
			'steps' => array(
				array( 'flag', 'ตั้งเป้าหมายและเงินเย็น', 'รู้ว่าเทรดเพื่ออะไร ใช้เงินก้อนไหน' ),
				array( 'shield', 'กำหนดความเสี่ยง', 'ต่อออเดอร์ รายวัน และจุดหยุดใหญ่' ),
				array( 'flask', 'ทดสอบก่อนใช้จริง', 'Backtest แล้วตามด้วย Forward Test' ),
				array( 'play', 'เริ่มบัญชีจริงขนาดเล็ก', 'ใช้ขนาดที่ยอมรับได้เมื่อผิดคาด' ),
				array( 'chart', 'ติดตามผลตามรอบ', 'รายวัน รายสัปดาห์ รายเดือน' ),
				array( 'book', 'ทบทวนและปรับแผน', 'เทียบผลกับแผน แล้วบันทึกบทเรียน' ),
			),
			'note'  => 'แก้แผนตอนทบทวนตามรอบ ไม่ใช่ตอนพอร์ตติดลบระหว่างวัน',
			'icon'  => 'warn',
		),
		'monitor-rhythm'   => array(
			'type'  => 'columns',
			'title' => 'จังหวะติดตามผล EA',
			'sub'   => 'ใช้เวลาไม่นาน แต่ทำสม่ำเสมอ ดีกว่าเฝ้าทั้งวัน',
			'cols'  => array(
				array( 'clock', 'รายวัน', array( 'การ์ดล็อกอินและปุ่ม Algo Trading', 'แท็บ Experts และ Journal ไม่มีข้อความผิดปกติ', 'Equity เทียบเพดานขาดทุนรายวัน', 'VPS และเน็ตยังเชื่อมต่อ' ) ),
				array( 'bars', 'รายสัปดาห์', array( 'สรุปลง Trading Journal', 'เทียบกับเส้นฐานจาก Backtest', 'ดู Drawdown สูงสุดของสัปดาห์', 'เช็ก RAM และพื้นที่บน VPS' ) ),
				array( 'chart', 'รายเดือน', array( 'ดึงรายงาน History ของ MT5', 'อ่าน Profit Factor และ Drawdown', 'ตัดสินใจคงไว้ ลดขนาด หรือหยุด', 'จดบทเรียนกลับเข้าแผน' ) ),
			),
			'note'  => 'ตัวเลขเดือนเดียวยังสรุปอะไรไม่ได้ ดูแนวโน้มหลายเดือนเทียบกับแผน',
			'icon'  => 'warn',
		),
		'broker-checklist' => array(
			'type'  => 'grid',
			'title' => 'เช็กลิสต์เลือกโบรกเกอร์สำหรับ EA',
			'sub'   => 'หกเรื่องที่ควรผ่านก่อนฝากเงินจริง',
			'cards' => array(
				array( 'shield', 'ใบอนุญาตและผู้กำกับ', 'ตรวจเลขใบอนุญาตบนเว็บของหน่วยงาน' ),
				array( 'monitor', 'รองรับ MT5 และ EA', 'เปิด Algo Trading ได้ รู้ว่าบัญชีเป็น Hedging หรือ Netting' ),
				array( 'tag', 'ต้นทุนการเทรด', 'Spread ค่าคอมมิชชัน และ Swap ของสัญลักษณ์ที่ใช้' ),
				array( 'candles', 'สเปกสัญลักษณ์', 'ขนาดสัญญา Lot ต่ำสุด และเวลาซื้อขาย' ),
				array( 'dollar', 'ฝากและถอนเงิน', 'ช่องทาง ระยะเวลา ค่าธรรมเนียม ชื่อบัญชีต้องตรงกัน' ),
				array( 'user', 'ลองก่อนด้วยเงินน้อย', 'ดูการส่งคำสั่งและการถอนจริงก่อนเพิ่มทุน' ),
			),
			'note'  => 'เงินทุนต้องอยู่ในบัญชีชื่อคุณเสมอ อย่าโอนผ่านตัวกลางหรือบัญชีบุคคล',
			'icon'  => 'shield',
		),
	);
}

/* ---------- ตัวช่วย ---------- */
function eab_art_hhmm( $h ) {
	$h = fmod( $h, 24 );
	return sprintf( '%02d:%02d', (int) floor( $h ), (int) round( ( $h - floor( $h ) ) * 60 ) );
}

/**
 * CSS ของแผนภาพชนิดใหม่ (body.is-tall = แบบมือถือ 800×1140)
 */
function eab_art_types_css() {
	return <<<'CSS'
.t-gold{background:#EFC25A!important;color:var(--navy)}
/* map */
.map{flex:1;display:grid;grid-template-columns:1.25fr 1fr;gap:34px;margin-top:34px;min-height:0}
.is-tall .map{grid-template-columns:1fr;grid-template-rows:auto 1fr;gap:22px;margin-top:26px}
.win{position:relative;border:2px solid rgba(10,46,102,.18);border-radius:18px;background:#fff;overflow:hidden;display:grid;grid-template-rows:38px 46px 1fr 112px}
.is-tall .win{height:360px;grid-template-rows:30px 38px 1fr 80px}
.win-bar{background:var(--navy);color:#fff;font:600 17px "Kanit";display:flex;align-items:center;padding:0 16px;gap:8px}
.win-bar i{width:11px;height:11px;border-radius:50%;background:rgba(255,255,255,.35)}
.win-bar span{margin-left:8px}
.win-tools{display:flex;align-items:center;gap:10px;padding:0 14px;border-bottom:1px solid #E2EDFB;background:#F7FAFE}
.win-tools b{display:block;width:30px;height:14px;border-radius:4px;background:#DCE7F6}
.algo{display:flex;align-items:center;gap:8px;margin-left:8px;padding:6px 12px;border-radius:8px;border:2px solid #16A34A;background:#ECFDF3;font:600 16px "Kanit";color:#14532D}
.algo i{width:12px;height:12px;border-radius:50%;background:#16A34A}
.win-body{display:grid;grid-template-columns:30% 1fr;min-height:0}
.win-body,.win-side,.pane,.chart{overflow:hidden}
.is-tall .pane{padding:10px 14px}
.is-tall .pane h4{margin-bottom:4px}
.is-tall .pane p{margin:5px 0;height:8px}
.win-side{display:grid;grid-template-rows:1fr 1fr;border-right:1px solid #E2EDFB}
.pane{position:relative;padding:12px 14px;border-bottom:1px solid #E2EDFB}
.pane h4{font:600 16px "Kanit";color:var(--navy);margin-bottom:8px}
.pane p{height:9px;border-radius:5px;background:#E8F0FB;margin:7px 0}
.pane p:nth-child(odd){width:78%}
.chart{position:relative;background:linear-gradient(180deg,#FBFDFF,#F2F7FE)}
.chart svg.c{position:absolute;inset:18px 18px 18px 18px;width:calc(100% - 36px);height:calc(100% - 36px)}
.ea-tag{position:absolute;right:14px;top:12px;display:flex;align-items:center;gap:6px;padding:4px 10px;border-radius:999px;background:#fff;border:1.5px solid rgba(10,46,102,.18);font:600 14px "Kanit";color:var(--navy)}
.ea-tag svg{width:16px;height:16px;color:var(--blue)}
.toolbox{position:relative;border-top:2px solid #E2EDFB;padding:10px 14px}
.tabs{display:flex;gap:6px}
.tabs span{padding:4px 10px;border-radius:7px;background:#EEF4FC;font:500 14px "Kanit";color:var(--ink-2)}
.tabs span.on{background:var(--navy);color:#fff}
.toolbox p{height:9px;border-radius:5px;background:#E8F0FB;margin:10px 0 0;width:86%}
.pin{position:absolute;width:38px;height:38px;border-radius:50%;display:grid;place-items:center;background:#EFC25A;color:var(--navy);font:700 20px "Kanit";border:3px solid #fff;z-index:2}
.is-tall .pin{width:34px;height:34px;font-size:18px}
.legend{display:grid;grid-template-rows:repeat(6,1fr);gap:10px;min-height:0}
.is-tall .legend{gap:8px}
.is-tall .lg{padding:6px 16px}
.lg{display:grid;grid-template-columns:42px 1fr;align-items:center;gap:14px;padding:8px 16px;border:2px solid rgba(10,46,102,.1);border-radius:18px;background:rgba(255,255,255,.88)}
.lg .n{width:40px;height:40px;border-radius:50%;display:grid;place-items:center;background:#EFC25A;color:var(--navy);font:700 21px "Kanit"}
.lg h3{font:600 23px "Kanit";color:var(--navy);line-height:1.2}
.lg h3 small{font:500 17px "Noto Sans Thai";color:var(--blue);margin-left:8px}
.lg p{font-size:18px;line-height:1.35;color:var(--ink-2)}
.is-tall .lg h3{font-size:23px}
.is-tall .lg p{font-size:18px}
/* columns */
.cols{flex:1;display:grid;grid-template-columns:repeat(3,1fr);gap:26px;margin:34px 0 26px;min-height:0}
.is-tall .cols{grid-template-columns:1fr;gap:16px;margin:26px 0 20px}
.col{display:flex;flex-direction:column;gap:14px;padding:24px 26px;border:2px solid rgba(10,46,102,.12);border-radius:28px;background:rgba(255,255,255,.88)}
.is-tall .col{padding:18px 22px;gap:10px}
.col-head{display:flex;align-items:center;gap:16px}
.col-head .tile{width:74px;height:74px;border-radius:22px}
.col-head .tile svg{width:40px;height:40px}
.is-tall .col-head .tile{width:60px;height:60px;border-radius:18px}
.is-tall .col-head .tile svg{width:32px;height:32px}
.col-head h2{font:700 34px "Kanit";color:var(--navy)}
.is-tall .col-head h2{font-size:30px}
.col ul{list-style:none;display:grid;gap:14px}
.is-tall .col ul{grid-template-columns:1fr;gap:6px}
.col li{display:grid;grid-template-columns:28px 1fr;gap:12px;font-size:24px;line-height:1.4;color:var(--ink-2)}
.is-tall .col li{font-size:21px;grid-template-columns:24px 1fr;gap:10px}
.col li svg{width:24px;height:24px;margin-top:3px;color:#16A34A;stroke-width:2.4}
.is-tall .col li svg{width:20px;height:20px}
/* stack */
.stack{flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:9px;margin-top:26px}
.is-tall .stack{gap:16px;margin-top:28px;justify-content:flex-start}
.layer{display:grid;grid-template-columns:44px 56px 1fr;align-items:center;gap:16px;height:72px;padding:0 24px;border-radius:20px;border:2px solid rgba(10,46,102,.12);background:rgba(255,255,255,.9)}
.is-tall .layer{width:100%!important;height:auto;padding:24px 22px;grid-template-columns:40px 56px 1fr;gap:16px}
.layer .n{width:40px;height:40px;border-radius:50%;display:grid;place-items:center;background:var(--navy);color:#fff;font:600 20px "Kanit"}
.layer .ic{width:52px;height:52px;border-radius:16px;display:grid;place-items:center;background:var(--blue);color:#fff}
.layer .ic svg{width:30px;height:30px}
.layer.l6 .ic{background:#EFC25A;color:var(--navy)}
.layer.l5 .ic{background:var(--navy);color:#F6D57A}
.layer .tx{display:flex;align-items:baseline;gap:18px;min-width:0}
.is-tall .layer .tx{flex-direction:column;gap:2px}
.layer h2{font:600 28px "Kanit";color:var(--navy);white-space:nowrap}
.layer p{font-size:21px;color:var(--ink-2);white-space:nowrap}
.is-tall .layer h2{font-size:28px}
.is-tall .layer p{font-size:20px;white-space:normal;line-height:1.35}
.stack-ends{display:flex;justify-content:space-between;width:100%;margin-top:6px;font:600 20px "Kanit";color:var(--muted)}
.is-tall .stack-ends{display:none}
/* cycle */
.cyc{flex:1;display:grid;grid-template-columns:1fr 56px 1fr 56px 1fr;grid-template-rows:1fr 54px 1fr;margin:34px 0 26px;align-items:stretch}
.cyc > div:not(.ca):not(.loop){display:flex}
.cyc > div > .cc{flex:1}
.is-tall .cyc{display:flex;flex-direction:column;gap:0;margin:24px 0 16px}
.cc{position:relative;display:flex;align-items:center;gap:18px;padding:20px 22px;border:2px solid rgba(10,46,102,.12);border-radius:26px;background:rgba(255,255,255,.9)}
.is-tall .cc{padding:14px 18px;gap:14px}
.cc .tile{width:70px;height:70px;border-radius:22px}
.cc .tile svg{width:38px;height:38px}
.is-tall .cc .tile{width:58px;height:58px;border-radius:18px}
.is-tall .cc .tile svg{width:30px;height:30px}
.cc .num{position:absolute;left:-12px;top:-12px;width:40px;height:40px;border-radius:50%;display:grid;place-items:center;background:var(--navy);color:#fff;font:600 20px "Kanit";border:3px solid #fff}
.cc h2{font:600 27px "Kanit";color:var(--navy);line-height:1.25}
.cc p{font-size:20px;line-height:1.4;color:var(--ink-2);margin-top:4px}
.is-tall .cc h2{font-size:26px}
.is-tall .cc p{font-size:19px}
.ca{display:grid;place-items:center;color:#D9A12E}
.ca svg{width:34px;height:34px;stroke-width:2.6}
.is-tall .ca{height:30px}
.is-tall .ca svg{width:26px;height:26px;transform:rotate(90deg)}
.loop{display:flex;align-items:center;justify-content:center;gap:10px;font:600 20px "Kanit";color:var(--gold-text)}
.loop svg{width:26px;height:26px;color:#D9A12E}
/* timeline */
.tl{flex:1;display:flex;flex-direction:column;justify-content:flex-end;margin:56px 0 16px}
.tl-bar{position:relative;height:300px;margin:0 6px}
.seg{position:absolute;bottom:42px;border-radius:14px 14px 0 0;display:flex;flex-direction:column;justify-content:flex-start;padding:12px 14px;overflow:hidden}
.seg h3{font:600 22px "Kanit";line-height:1.2}
.seg p{font-size:17px;line-height:1.35;margin-top:4px}
.lv0{background:#E5E9F0;color:#5A6785}
.lv1{background:#D4E6FA;color:var(--navy)}
.lv2{background:var(--blue);color:#fff}
.lv3{background:var(--navy);color:#fff}
.axis{position:absolute;left:0;right:0;bottom:0;height:42px;border-top:3px solid var(--navy)}
.tick{position:absolute;top:6px;transform:translateX(-50%);font:600 18px "Kanit";color:var(--ink-2)}
.mark{position:absolute;bottom:42px;width:0;border-left:3px dashed #D9A12E;z-index:2}
.mark span{position:absolute;bottom:100%;left:50%;transform:translateX(-50%);white-space:nowrap;padding:6px 12px;border-radius:12px;background:#EFC25A;color:var(--navy);font:600 17px "Kanit"}
.tl-legend{display:flex;justify-content:center;gap:22px;margin-top:16px;font:500 18px "Noto Sans Thai";color:var(--ink-2)}
.tl-legend i{display:inline-block;width:18px;height:18px;border-radius:5px;margin-right:8px;vertical-align:-3px}
/* timeline tall: แนวตั้ง */
.is-tall .tl{justify-content:flex-start;margin:22px 0 14px}
.tlv{display:grid;grid-template-columns:86px 1fr;gap:0 16px}
.tlv .t{font:600 22px "Kanit";color:var(--ink-2);text-align:right;padding-top:12px}
.tlv .b{border-radius:16px;padding:12px 18px;margin-bottom:10px}
.tlv .b h3{font:600 25px "Kanit"}
.tlv .b p{font-size:19px;line-height:1.35;margin-top:2px}
.tlv .m{grid-column:2;display:flex;align-items:center;gap:10px;margin:-2px 0 10px;padding:8px 14px;border-radius:12px;background:#EFC25A;color:var(--navy);font:600 19px "Kanit"}
.tlv .m svg{width:22px;height:22px}
.tlv-week{margin-top:8px;text-align:center;font:600 21px "Kanit";color:var(--ink-2)}
/* grid */
.grid6{flex:1;display:grid;grid-template-columns:repeat(3,1fr);grid-template-rows:1fr 1fr;gap:22px;margin:32px 0 24px;min-height:0}
.is-tall .grid6{grid-template-columns:1fr;grid-template-rows:none;gap:12px;margin:24px 0 18px}
.gc{position:relative;display:flex;align-items:center;gap:20px;padding:20px 24px;border:2px solid rgba(10,46,102,.12);border-radius:26px;background:rgba(255,255,255,.9)}
.is-tall .gc{padding:14px 18px;gap:16px}
.gc .num{position:absolute;left:-10px;top:-10px;width:38px;height:38px;border-radius:50%;display:grid;place-items:center;background:var(--navy);color:#fff;font:600 19px "Kanit";border:3px solid #fff}
.gc .tile{width:72px;height:72px;border-radius:22px}
.gc .tile svg{width:38px;height:38px}
.is-tall .gc .tile{width:58px;height:58px;border-radius:18px}
.is-tall .gc .tile svg{width:30px;height:30px}
.gc h2{font:600 27px "Kanit";color:var(--navy);line-height:1.25}
.gc p{font-size:20px;line-height:1.4;color:var(--ink-2);margin-top:4px}
.is-tall .gc h2{font-size:25px}
.is-tall .gc p{font-size:18px}
.is-tall .note{font-size:23px;padding:18px 22px}
CSS;
}

/**
 * HTML ของแผนภาพชนิดใหม่
 */
function eab_art_types_body( $a, $tall ) {
	$tones = array( 'sky', 'gold', 'blue', 'navy' );
	ob_start();
	switch ( $a['type'] ) {
		case 'map':
			$pins = $tall
				? array( array( 262, 18 ), array( 116, 74 ), array( 96, 190 ), array( 430, 130 ), array( 284, 290 ), array( 652, 20 ) )
				: array( array( 270, 26 ), array( 124, 92 ), array( 104, 246 ), array( 470, 170 ), array( 300, 398 ), array( 742, 30 ) );
			?>
			<div class="map">
				<div class="win">
					<div class="win-bar"><i></i><i></i><i></i><span>MetaTrader 5</span></div>
					<div class="win-tools"><b></b><b></b><b></b><span class="algo"><i></i>Algo Trading</span><b></b><b></b><span style="flex:1"></span><b></b></div>
					<div class="win-body">
						<div class="win-side">
							<div class="pane"><h4>Market Watch</h4><p></p><p></p><p></p><p></p></div>
							<div class="pane"><h4>Navigator</h4><p></p><p></p><p></p></div>
						</div>
						<div class="chart">
							<svg class="c" viewBox="0 0 400 220" preserveAspectRatio="none" aria-hidden="true">
								<g stroke="#0B5CAD" stroke-width="2">
								<?php
								$y = 140;
								for ( $i = 0; $i < 26; $i++ ) {
									$d  = ( ( $i * 37 ) % 11 ) - 4.6;
									$o  = $y;
									$c  = $y - $d * 6;
									$hi = min( $o, $c ) - 8 - ( $i % 3 ) * 3;
									$lo = max( $o, $c ) + 8 + ( $i % 4 ) * 2;
									$x  = 10 + $i * 15;
									$up = $c < $o;
									echo '<line x1="' . (int) $x . '" y1="' . (int) $hi . '" x2="' . (int) $x . '" y2="' . (int) $lo . '"/>';
									echo '<rect x="' . (int) ( $x - 5 ) . '" y="' . (int) min( $o, $c ) . '" width="10" height="' . (int) max( 3, abs( $c - $o ) ) . '" fill="' . ( $up ? '#0B5CAD' : '#FFFFFF' ) . '"/>';
									$y = $c + ( $i % 5 === 0 ? -6 : 2 );
									$y = max( 40, min( 190, $y ) );
								}
								?>
								</g>
							</svg>
							<span class="ea-tag"><?php echo eab_icon( 'robot' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>EA</span>
						</div>
					</div>
					<div class="toolbox">
						<div class="tabs"><span class="on">Trade</span><span>History</span><span>Journal</span><span>Experts</span></div>
						<p></p>
					</div>
					<?php foreach ( $pins as $i => $p ) : ?>
						<span class="pin" style="left:<?php echo (int) $p[0]; ?>px;top:<?php echo (int) $p[1]; ?>px"><?php echo (int) ( $i + 1 ); ?></span>
					<?php endforeach; ?>
				</div>
				<div class="legend">
					<?php foreach ( $a['items'] as $i => $it ) : ?>
						<div class="lg"><span class="n"><?php echo (int) ( $i + 1 ); ?></span><div><h3><?php echo esc_html( $it[0] ); ?><small><?php echo esc_html( $it[1] ); ?></small></h3><p><?php echo esc_html( $it[2] ); ?></p></div></div>
					<?php endforeach; ?>
				</div>
			</div>
			<?php
			break;

		case 'columns':
			?>
			<div class="cols">
				<?php foreach ( $a['cols'] as $i => $c ) : ?>
					<div class="col">
						<div class="col-head"><span class="tile t-<?php echo esc_attr( $tones[ $i % 4 ] ); ?>"><?php echo eab_icon( $c[0] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span><h2><?php echo esc_html( $c[1] ); ?></h2></div>
						<ul>
							<?php foreach ( $c[2] as $li ) : ?>
								<li><?php echo eab_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo esc_html( $li ); ?></span></li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endforeach; ?>
			</div>
			<?php
			break;

		case 'stack':
			$n = count( $a['layers'] );
			?>
			<div class="stack">
				<?php foreach ( $a['layers'] as $i => $l ) : ?>
					<?php $w = 62 + ( 38 * $i / max( 1, $n - 1 ) ); ?>
					<div class="layer l<?php echo (int) ( $i + 1 ); ?>" style="width:<?php echo esc_attr( round( $w, 1 ) ); ?>%">
						<span class="n"><?php echo (int) ( $i + 1 ); ?></span>
						<span class="ic"><?php echo eab_icon( $l[0] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<div class="tx"><h2><?php echo esc_html( $l[1] ); ?></h2><p><?php echo esc_html( $l[2] ); ?></p></div>
					</div>
				<?php endforeach; ?>
			</div>
			<?php
			break;

		case 'cycle':
			$s     = $a['steps'];
			$arrow = eab_icon( 'arrow' );
			$card  = function ( $i ) use ( $s, $tones ) {
				return '<div class="cc"><span class="num">' . (int) ( $i + 1 ) . '</span><span class="tile t-' . esc_attr( $tones[ $i % 4 ] ) . '">' . eab_icon( $s[ $i ][0] ) . '</span><div><h2>' . esc_html( $s[ $i ][1] ) . '</h2><p>' . esc_html( $s[ $i ][2] ) . '</p></div></div>';
			};
			echo '<div class="cyc">';
			if ( $tall ) {
				foreach ( $s as $i => $x ) {
					if ( $i ) {
						echo '<div class="ca">' . $arrow . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput
					}
					echo $card( $i ); // phpcs:ignore WordPress.Security.EscapeOutput
				}
				echo '<div class="ca">' . $arrow . '</div><p class="loop">' . eab_icon( 'arrow' ) . '<span>กลับไปเริ่มขั้น 1 ในรอบถัดไป</span></p>'; // phpcs:ignore WordPress.Security.EscapeOutput
			} else {
				/* แถวบน 1 → 2 → 3 · ลงทางขวา · แถวล่าง 6 ← 5 ← 4 · ขึ้นทางซ้ายกลับไป 1 */
				echo $card( 0 ) . '<div class="ca">' . $arrow . '</div>' . $card( 1 ) . '<div class="ca">' . $arrow . '</div>' . $card( 2 ); // phpcs:ignore WordPress.Security.EscapeOutput
				echo '<div class="ca" style="grid-column:1;grid-row:2"><span style="transform:rotate(-90deg);display:grid">' . $arrow . '</span></div>'; // phpcs:ignore WordPress.Security.EscapeOutput
				echo '<div class="loop" style="grid-column:2 / span 3;grid-row:2">รอบถัดไปเริ่มจากผลการทบทวน</div>';
				echo '<div class="ca" style="grid-column:5;grid-row:2"><span style="transform:rotate(90deg);display:grid">' . $arrow . '</span></div>'; // phpcs:ignore WordPress.Security.EscapeOutput
				echo '<div style="grid-column:1;grid-row:3">' . $card( 5 ) . '</div><div class="ca" style="grid-column:2;grid-row:3"><span style="transform:rotate(180deg);display:grid">' . $arrow . '</span></div>'; // phpcs:ignore WordPress.Security.EscapeOutput
				echo '<div style="grid-column:3;grid-row:3">' . $card( 4 ) . '</div><div class="ca" style="grid-column:4;grid-row:3"><span style="transform:rotate(180deg);display:grid">' . $arrow . '</span></div>'; // phpcs:ignore WordPress.Security.EscapeOutput
				echo '<div style="grid-column:5;grid-row:3">' . $card( 3 ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput
			}
			echo '</div>';
			break;

		case 'timeline':
			$start = $a['start'];
			if ( $tall ) {
				?>
				<div class="tl"><div class="tlv">
					<?php
					foreach ( $a['segs'] as $sg ) {
						echo '<span class="t">' . esc_html( eab_art_hhmm( $sg[0] ) ) . '</span>';
						echo '<div class="b lv' . (int) $sg[4] . '"><h3>' . esc_html( $sg[2] ) . '</h3><p>' . esc_html( $sg[3] ) . '</p></div>';
						foreach ( $a['marks'] as $mk ) {
							if ( $mk[0] >= $sg[0] && $mk[0] < $sg[1] ) {
								echo '<div class="m">' . eab_icon( 'flame' ) . '<span>' . esc_html( $mk[1] . ' · ' . $mk[2] ) . '</span></div>'; // phpcs:ignore WordPress.Security.EscapeOutput
							}
						}
					}
					?>
				</div><p class="tlv-week">ตลาดเปิดเช้าวันจันทร์ ปิดเช้าวันเสาร์ ตามเวลาไทย</p></div>
				<?php
			} else {
				$px = function ( $h ) use ( $start ) {
					return ( $h - $start ) / 24 * 100;
				};
				$hts = array( 70, 120, 175, 240 );
				?>
				<div class="tl">
					<div class="tl-bar">
						<?php foreach ( $a['segs'] as $sg ) : ?>
							<div class="seg lv<?php echo (int) $sg[4]; ?>" style="left:<?php echo esc_attr( round( $px( $sg[0] ), 3 ) ); ?>%;width:calc(<?php echo esc_attr( round( $px( $sg[1] ) - $px( $sg[0] ), 3 ) ); ?>% - 4px);height:<?php echo (int) $hts[ $sg[4] ]; ?>px">
								<?php if ( $sg[1] - $sg[0] >= 2 ) : ?><h3><?php echo esc_html( $sg[2] ); ?></h3><p><?php echo esc_html( $sg[3] ); ?></p><?php endif; ?>
							</div>
						<?php endforeach; ?>
						<?php foreach ( $a['marks'] as $mk ) : ?>
							<div class="mark" style="left:<?php echo esc_attr( round( $px( $mk[0] ), 3 ) ); ?>%;height:<?php echo 262; ?>px"><span><?php echo esc_html( $mk[1] . ' ' . $mk[2] ); ?></span></div>
						<?php endforeach; ?>
						<div class="axis">
							<?php for ( $h = $start; $h <= $start + 24; $h += 3 ) : ?>
								<span class="tick" style="left:<?php echo esc_attr( round( $px( $h ), 3 ) ); ?>%"><?php echo esc_html( eab_art_hhmm( $h ) ); ?></span>
							<?php endfor; ?>
						</div>
					</div>
					<p class="tl-legend"><span><i class="lv0"></i>พักรายวัน</span><span><i class="lv1"></i>ค่อนข้างเงียบ</span><span><i class="lv2"></i>คึกคัก</span><span><i class="lv3"></i>มักคึกคักที่สุด</span><span>เปิดเช้าวันจันทร์ ปิดเช้าวันเสาร์</span></p>
				</div>
				<?php
			}
			break;

		case 'grid':
			?>
			<div class="grid6">
				<?php foreach ( $a['cards'] as $i => $c ) : ?>
					<div class="gc"><span class="num"><?php echo (int) ( $i + 1 ); ?></span><span class="tile t-<?php echo esc_attr( $tones[ $i % 4 ] ); ?>"><?php echo eab_icon( $c[0] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span><div><h2><?php echo esc_html( $c[1] ); ?></h2><p><?php echo esc_html( $c[2] ); ?></p></div></div>
				<?php endforeach; ?>
			</div>
			<?php
			break;
	}
	return ob_get_clean();
}
