<?php
/**
 * EA BULL · Shortcodes สำหรับใช้ในเนื้อหาเพจ/บทความ
 *
 * [eabull_line pos="xxx"]ข้อความปุ่ม[/eabull_line]  ปุ่ม LINE (ลิงก์จาก Customizer)
 * [eabull_brand]                                   ชื่อแบรนด์
 * [eabull_broker] / [eabull_broker field="server"] ชื่อโบรกเกอร์ / ชื่อเซิร์ฟเวอร์ MT5 (ตั้งค่าใน Customizer)
 * [eabull_ea] / [eabull_ea field="name|version|bt_range"] ชื่อไฟล์ EA รุ่นปัจจุบัน (EA_BULL_V5.0.ex5) / ชื่อไม่มีนามสกุล / เลขรุ่น / ช่วงวันที่ Backtest มาตรฐาน
 * [eabull_calc type="lot|drawdown"]                เครื่องคำนวณ (JS ใน main.js)
 * [eabull_figure name="license-flow" alt="" caption=""] แผนภาพในธีม (assets/img/illus · รายชื่อใน eab_figure_registry())
 *
 * @package eabull
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function eab_sc_line( $atts, $content = '' ) {
	$atts = shortcode_atts( array( 'pos' => 'content' ), $atts, 'eabull_line' );
	$text = trim( wp_strip_all_tags( (string) $content ) );
	if ( '' === $text ) {
		$text = 'สอบถามทีมงานทาง LINE';
	}
	// ไม่มี LINE → ใช้หน้า /go/ แทน · ไม่มีทั้งคู่ = ไม่แสดงปุ่ม (ไม่ชี้ไปที่ '#')
	$target = eab_contact_target();
	if ( '' === $target['url'] ) {
		return '';
	}
	if ( ! $target['is_line'] ) {
		$text = eab_mod( 'contact_fallback_text' );
	}
	return '<span class="sc-line"><a class="btn btn-fire" href="' . esc_url( $target['url'] ) . '"' . ( $target['is_line'] ? ' target="_blank" rel="noopener"' : '' ) . ' data-line-pos="' . esc_attr( sanitize_key( $atts['pos'] ) ) . '">'
		. eab_icon( $target['is_line'] ? 'line' : 'chat' ) . '<span>' . esc_html( $text ) . '</span>' . eab_icon( 'arrow' ) . '</a></span>';
}
add_shortcode( 'eabull_line', 'eab_sc_line' );

function eab_sc_brand() {
	return esc_html( eab_mod( 'hero_title' ) ? eab_mod( 'hero_title' ) : 'EA BULL' );
}
add_shortcode( 'eabull_brand', 'eab_sc_brand' );

function eab_sc_broker( $atts ) {
	$atts = shortcode_atts( array( 'field' => 'name' ), $atts, 'eabull_broker' );
	$key  = 'server' === $atts['field'] ? 'broker_server' : ( 'url' === $atts['field'] ? 'broker_signup_url' : 'broker_name' );
	$val  = eab_mod( $key );
	if ( 'broker_signup_url' === $key ) {
		return $val ? '<a class="btn btn-dark" href="' . esc_url( $val ) . '" target="_blank" rel="noopener sponsored">' . esc_html( eab_mod( 'broker_signup_text' ) ) . ' ' . eab_icon( 'external', 'icon icon-sm' ) . '</a>' : '';
	}
	return esc_html( $val );
}
add_shortcode( 'eabull_broker', 'eab_sc_broker' );

/**
 * รุ่นของไฟล์ EA ที่แจกอยู่ · ตั้งที่เดียว (Customizer หมวด 29: ea_version, ea_bt_from, ea_bt_to) แล้วทุกหน้าเปลี่ยนตาม
 * เนื้อหาเพจ/บทความใช้ [eabull_ea] · ข้อความคู่มือใน Customizer ใช้ {ea} {ea_file} {ea_version} {bt_range} (eab_guide_tokens)
 */
function eab_ea_version() {
	$v = preg_replace( '/[^0-9.]/', '', (string) eab_mod( 'ea_version' ) );
	return '' !== $v ? $v : '5.0';
}

function eab_ea_name() {
	return 'EA_BULL_V' . eab_ea_version();
}

function eab_ea_bt_range() {
	return trim( (string) eab_mod( 'ea_bt_from' ) ) . ' ถึง ' . trim( (string) eab_mod( 'ea_bt_to' ) );
}

function eab_sc_ea( $atts ) {
	$atts = shortcode_atts(
		array(
			'field'   => 'file',
			'product' => '',
		),
		$atts,
		'eabull_ea'
	);
	/* สินค้าอื่น (inc/modules/products.php) · [eabull_ea product="nitro|twin" field="name|version|bt_range"] */
	if ( '' !== $atts['product'] && function_exists( 'eab_product' ) ) {
		$item = eab_product( sanitize_key( $atts['product'] ) );
		if ( ! $item ) {
			return '';
		}
		switch ( $atts['field'] ) {
			case 'name':
				return esc_html( $item['file'] );
			case 'version':
				return esc_html( $item['version'] );
			case 'bt_range':
				return esc_html( eab_product_bt_range( $item ) );
		}
		return esc_html( $item['file'] . '.ex5' );
	}
	switch ( $atts['field'] ) {
		case 'name':
			return esc_html( eab_ea_name() );
		case 'version':
			return esc_html( eab_ea_version() );
		case 'bt_range':
			return esc_html( eab_ea_bt_range() );
	}
	return esc_html( eab_ea_name() . '.ex5' );
}
add_shortcode( 'eabull_ea', 'eab_sc_ea' );

/**
 * แผนภาพในเนื้อหา (สร้างด้วย dev/make-art.php) · ชื่อ => ไฟล์กว้าง, ไฟล์มือถือ, alt ตั้งต้น
 * เปลี่ยนภาพเมื่อไหร่ให้เปลี่ยนเลข v ในชื่อไฟล์ (Cloudflare แคชตาม URL)
 */
function eab_figure_registry() {
	return (array) apply_filters(
		'eab_figure_registry',
		array(
			'how-it-works' => array( 'eabull-how-it-works-v2.webp', 'eabull-how-it-works-mobile-v2.webp', 'แผนภาพ EA BULL ทำงานอย่างไร 4 ขั้น จากกราฟ XAUUSD M1 ถึงการติดตามจากมือถือ' ),
			'license-flow' => array( 'eabull-license-flow-v2.webp', 'eabull-license-flow-mobile-v2.webp', 'แผนภาพขั้นตอนเปิดสิทธิ์ EA BULL: ส่งเลขบัญชีทาง LINE ทีมงานเปิดสิทธิ์ใน 5 นาที การ์ดล็อกอินเป็นสีเขียว และทำงานต่อได้ 72 ชั่วโมงเมื่อเน็ตหลุด' ),
			'vps-flow'     => array( 'eabull-vps-flow-v2.webp', 'eabull-vps-flow-mobile-v2.webp', 'แผนภาพการใช้ Windows VPS รัน EA: เช่า VPS ลง MT5 และ EA ต่อจากคอมหรือมือถือ แล้วปิดเครื่องของคุณได้' ),
			'account-flow' => array( 'eabull-account-flow-v2.webp', 'eabull-account-flow-mobile-v2.webp', 'แผนภาพเปิดบัญชี MT5 สำหรับใช้ EA 4 ขั้น: สมัคร ยืนยันตัวตน สร้างบัญชีเทรด และส่งเลขบัญชีให้ทีมงาน' ),
			/* แผนภาพเสาหลักของ 8 กลุ่มบทความ (dev/preview/art-types.php · 8 ต.ค. 2026) */
			'ea-loop'          => array( 'eabull-ea-loop-v2.webp', 'eabull-ea-loop-mobile-v2.webp', 'แผนภาพ EA ทำงานอย่างไรใน MT5: ราคาใหม่เข้ามา EA ตรวจเงื่อนไข ส่งคำสั่งเมื่อครบเงื่อนไข และโบรกเกอร์รับคำสั่ง วนซ้ำทุกครั้งที่ราคาขยับ' ),
			'mt5-map'          => array( 'eabull-mt5-map-v2.webp', 'eabull-mt5-map-mobile-v2.webp', 'แผนที่หน้าจอ MT5 หกจุดที่คนใช้ EA ต้องรู้จัก: ปุ่ม Algo Trading, Market Watch, Navigator, กราฟ, Toolbox และ Strategy Tester พร้อมปุ่มลัด' ),
			'vps-path'         => array( 'eabull-vps-path-v2.webp', 'eabull-vps-path-mobile-v2.webp', 'แผนภาพ VPS สำหรับ EA สามช่วง: เลือก ตั้งค่า และดูแล พร้อมรายการที่ต้องทำในแต่ละช่วง' ),
			'gold-sessions'    => array( 'eabull-gold-sessions-v2.webp', 'eabull-gold-sessions-mobile-v2.webp', 'ไทม์ไลน์หนึ่งวันของทองคำ XAUUSD ตามเวลาไทยช่วงเวลามาตรฐานของสหรัฐฯ: พักรายวัน ช่วงเอเชีย ยุโรปเปิด ยุโรปและสหรัฐฯ ซ้อนกัน และช่วงสหรัฐฯ พร้อมเวลาข่าวสหรัฐฯ และ FOMC โดยประมาณ' ),
			'risk-layers'      => array( 'eabull-risk-layers-v2.webp', 'eabull-risk-layers-mobile-v2.webp', 'แผนภาพชั้นป้องกันความเสี่ยงของพอร์ต 6 ชั้น: ความเสี่ยงต่อออเดอร์ ขนาด Lot เพดานขาดทุนรายวัน Drawdown ที่รับได้ Margin Level และ Stop Out และเงินเย็น' ),
			'plan-cycle'       => array( 'eabull-plan-cycle-v2.webp', 'eabull-plan-cycle-mobile-v2.webp', 'แผนภาพวงจรแผนการเทรดของคนใช้ EA 6 ขั้น: ตั้งเป้าหมาย กำหนดความเสี่ยง ทดสอบ เริ่มบัญชีจริงขนาดเล็ก ติดตามผล และทบทวนปรับแผน' ),
			'monitor-rhythm'   => array( 'eabull-monitor-rhythm-v2.webp', 'eabull-monitor-rhythm-mobile-v2.webp', 'แผนภาพจังหวะติดตามผล EA รายวัน รายสัปดาห์ และรายเดือน พร้อมสิ่งที่ต้องเช็กในแต่ละรอบ' ),
			'broker-checklist' => array( 'eabull-broker-checklist-v2.webp', 'eabull-broker-checklist-mobile-v2.webp', 'เช็กลิสต์เลือกโบรกเกอร์สำหรับ EA 6 ข้อ: ใบอนุญาต รองรับ MT5 และ EA ต้นทุนการเทรด สเปกสัญลักษณ์ ฝากถอนเงิน และลองด้วยเงินน้อยก่อน' ),
			/* ภาพหน้าจอ MT5 (assets/img/install/) · ช่อง 4-5 = ขนาดจริง · ช่อง 6 = แสดงขนาดจริงกลางกรอบ (ภาพเล็ก) */
			'mt5-open-data-folder' => array( '../install/eabull-mt5-open-data-folder-v1.webp', '', 'เมนู File ของ MT5 ที่ชี้ไปที่ Open Data Folder', 620, 300, true ),
			'mt5-navigator'        => array( '../install/eabull-mt5-navigator-v2.webp', '', 'หน้าต่าง Navigator ของ MT5 หมวด Expert Advisors ที่มีไฟล์ EA BULL', 210, 270, true ),
			/* ครอปจากแดชบอร์ดในคู่มือ PDF ของชุดส่งลูกค้ารุ่น 5.0 (ภาพฝังในไฟล์ PDF ไม่ได้แก้ไข) */
			'login-card-states'    => array( '../install/eabull-login-card-states-v2.webp', '', 'การ์ดล็อกอินบนแดชบอร์ด EA BULL 4 แบบ: ล็อกอินสำเร็จ (เขียว) กำลังตรวจสิทธิ์และใช้แบบผ่อนผัน (เหลือง) และล็อกอินไม่ผ่าน (แดง)', 822, 368 ),
			'dashboard-overview'   => array( '../install/eabull-dashboard-overview-v2.webp', '', 'ภาพรวมแดชบอร์ด EA BULL พร้อมหมายเลขกำกับ 17 จุด ฝั่งซ้ายเป็นสถานะ ปุ่มโหมด 3 ปุ่ม และปุ่มหลัก ฝั่งขวาเป็นการปรับค่าระหว่างวันและข้อมูลตะกร้า', 1400, 980 ),
			'modes-version'        => array( '../install/eabull-modes-version-v2.webp', '', 'ปุ่มเลือกโหมด Lite Advance และ Full บนแดชบอร์ด EA BULL ใต้ปุ่มบอกระดับความเสี่ยงของแต่ละโหมด และแถบท้ายแผงที่บอกเวอร์ชันของ EA', 849, 524 ),
			/* รายงาน Strategy Tester ของจริง รุ่น 5.0 (assets/img/results/ · ครอปส่วน Results + กราฟ Balance จากรายงาน MT5 ต้นฉบับ ไม่ตัดต่อ) · ของโหมด Lite อยู่ในช่องรูปของหน้า (backtest_img) */
			'backtest-advance'     => array( '../results/eabull-backtest-advance-v5-2026-v1.webp', '', 'ส่วน Results ของรายงาน Strategy Tester ของ EA BULL 5.0 โหมด Advance ช่วง 1 ม.ค. ถึง 30 ก.ย. 2026: Initial Deposit 20,000 Total Net Profit 103,912.47 Profit Factor 2.44 Equity Drawdown Maximal 14,850.29 Total Trades 20,337 และกราฟ Balance', 1120, 872 ),
			'backtest-full'        => array( '../results/eabull-backtest-full-v5-2026-v1.webp', '', 'ส่วน Results ของรายงาน Strategy Tester ของ EA BULL 5.0 โหมด Full ช่วง 1 ม.ค. ถึง 30 ก.ย. 2026: Initial Deposit 30,000 Total Net Profit 188,048.46 Profit Factor 2.46 Equity Drawdown Maximal 25,069.86 Total Trades 20,406 และกราฟ Balance', 1120, 872 ),
		)
	);
}

function eab_sc_figure( $atts ) {
	$atts = shortcode_atts(
		array(
			'name'    => '',
			'alt'     => '',
			'caption' => '',
		),
		$atts,
		'eabull_figure'
	);
	$all  = eab_figure_registry();
	$name = sanitize_key( $atts['name'] );
	if ( ! isset( $all[ $name ] ) ) {
		return '';
	}
	$fig  = $all[ $name ];
	$base = get_template_directory_uri() . '/assets/img/illus/';
	$alt  = '' !== trim( (string) $atts['alt'] ) ? $atts['alt'] : $fig[2];
	$w    = isset( $fig[3] ) ? (int) $fig[3] : 1600;
	$h    = isset( $fig[4] ) ? (int) $fig[4] : 760;
	$html = '<figure class="eab-figure' . ( ! empty( $fig[5] ) ? ' eab-figure--fit' : '' ) . '"><picture>';
	if ( ! empty( $fig[1] ) ) {
		$html .= '<source media="(max-width: 680px)" srcset="' . esc_url( $base . $fig[1] ) . '" width="800" height="1140">';
	}
	$html .= '<img src="' . esc_url( $base . $fig[0] ) . '" alt="' . esc_attr( $alt ) . '" width="' . $w . '" height="' . $h . '" loading="lazy" decoding="async"></picture>';
	if ( '' !== trim( (string) $atts['caption'] ) ) {
		$html .= '<figcaption>' . esc_html( $atts['caption'] ) . '</figcaption>';
	}
	return $html . '</figure>';
}
add_shortcode( 'eabull_figure', 'eab_sc_figure' );

function eab_sc_calc( $atts ) {
	$atts = shortcode_atts( array( 'type' => 'lot' ), $atts, 'eabull_calc' );
	static $n = 0;
	$n++;
	$id = 'calc-' . $n;

	ob_start();
	if ( 'drawdown' === $atts['type'] ) :
		?>
		<div class="calc" data-calc="drawdown" id="<?php echo esc_attr( $id ); ?>">
			<div class="calc-head">
				<?php echo eab_icon_badge( 'chart' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<div>
					<strong>เครื่องคำนวณการฟื้นตัวจาก Drawdown</strong>
					<small>ใส่เปอร์เซ็นต์ที่พอร์ตลดลง แล้วดูว่าต้องเติบโตกลับอีกเท่าไรจึงกลับมาที่ทุนเดิม</small>
				</div>
			</div>
			<div class="calc-grid">
				<label class="calc-field">
					<span>พอร์ตลดลง (Drawdown %)</span>
					<input type="number" inputmode="decimal" min="0" max="99" step="0.1" value="20" data-in="dd">
				</label>
				<label class="calc-field">
					<span>ทุนตั้งต้น (USD) · ใส่หรือไม่ก็ได้</span>
					<input type="number" inputmode="decimal" min="0" step="1" value="1000" data-in="balance">
				</label>
			</div>
			<div class="calc-out" aria-live="polite">
				<div><span>ต้องเติบโตกลับ</span><strong data-out="gain">-</strong></div>
				<div><span>ทุนหลังลดลง</span><strong data-out="left">-</strong></div>
			</div>
			<div class="calc-bar" aria-hidden="true"><i data-out="bar"></i></div>
			<p class="calc-note">สูตรที่ใช้: ส่วนที่ต้องเติบโตกลับ = DD ÷ (1 - DD) · เป็นคณิตศาสตร์ล้วน ไม่ใช่ผลการเทรดของระบบใด</p>
		</div>
		<?php
	else :
		?>
		<div class="calc" data-calc="lot" id="<?php echo esc_attr( $id ); ?>">
			<div class="calc-head">
				<?php echo eab_icon_badge( 'calc' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<div>
					<strong>เครื่องคำนวณ Lot ตามเงินที่ยอมเสีย</strong>
					<small>เริ่มจากทุน เปอร์เซ็นต์ที่ยอมเสียต่อออเดอร์ และระยะ Stop Loss แล้วได้ขนาด Lot ที่สอดคล้อง</small>
				</div>
			</div>
			<div class="calc-grid">
				<label class="calc-field">
					<span>ทุนในบัญชี (USD)</span>
					<input type="number" inputmode="decimal" min="0" step="1" value="1000" data-in="balance">
				</label>
				<label class="calc-field">
					<span>ยอมเสียต่อออเดอร์ (%)</span>
					<input type="number" inputmode="decimal" min="0" max="100" step="0.1" value="1" data-in="risk">
				</label>
				<label class="calc-field">
					<span>ระยะ Stop Loss (pips)</span>
					<input type="number" inputmode="decimal" min="0" step="0.1" value="50" data-in="sl">
				</label>
				<label class="calc-field">
					<span>ตัวอย่างสินทรัพย์</span>
					<select data-in="preset">
						<option value="10">คู่เงินที่ USD เป็นสกุลหลัง เช่น EURUSD · บัญชี USD</option>
						<option value="10">ทองคำ XAUUSD สัญญา 100 oz (นับ 1 pip = 0.10)</option>
						<option value="custom">ใส่มูลค่า pip เอง</option>
					</select>
				</label>
				<label class="calc-field">
					<span>มูลค่า 1 pip ที่ 1 lot (USD)</span>
					<input type="number" inputmode="decimal" min="0" step="0.01" value="10" data-in="pipval">
				</label>
				<label class="calc-field">
					<span>ขั้นของ Lot (เช่น 0.01)</span>
					<input type="number" inputmode="decimal" min="0.001" step="0.01" value="0.01" data-in="step">
				</label>
			</div>
			<div class="calc-out" aria-live="polite">
				<div><span>เงินที่ยอมเสียต่อออเดอร์</span><strong data-out="money">-</strong></div>
				<div><span>ขนาด Lot ที่ได้</span><strong data-out="lot">-</strong></div>
			</div>
			<p class="calc-note">ตัวเลขนี้เป็นค่าประมาณ มูลค่า pip จริงขึ้นกับสินทรัพย์ โบรกเกอร์ และสกุลเงินของบัญชี · ดูค่าจริงใน MT5 ได้ที่ Market Watch: คลิกขวาที่สินทรัพย์ แล้วเลือก Specification</p>
		</div>
		<?php
	endif;
	return ob_get_clean();
}
add_shortcode( 'eabull_calc', 'eab_sc_calc' );
