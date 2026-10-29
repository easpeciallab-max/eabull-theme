<?php
/**
 * EA BULL · สินค้า EA (ชุด B · docs/phase2.md ข้อ 6 · ต้นแบบ dev/mockup/_src/pages/home.html และ product.html)
 *
 * สินค้าจริงยังไม่มี (เจ้าของ 29 ต.ค. 2026): ใช้ "สินค้าตัวอย่าง 1 · V1.0", "2 · V2.0", "3 · V3.0"
 * ทุกช่องข้อมูลเป็น setting ค่าว่าง → eab_wait() แสดงป้าย "รอข้อมูลจากเจ้าของ"
 *
 * - eab_products(): สินค้าทั้งหมดจาก setting product{1..3}_* เรียงตามเลขรุ่น (ใช้ทั้งหน้าแรก เมนู /go และหน้าสินค้า)
 * - eab_product( $key ): สินค้าตาม key ('product1' หรือ '1')
 * - eab_homeplus_products(): ส่วนสินค้าในหน้าแรก (.prods > .prow)
 * - eab_go_product_downloads( $echo ): การ์ดดาวน์โหลด .dlcards ในขั้น 4 ของหน้า /go
 * - eab_products_page(): หน้าสินค้า (template-product.php) หัวเพจ → การ์ดข้อมูล 8 ช่อง → เอกสารยาวจาก editor
 * - [eabull_product part="compare|tests|panel" product="1"] และ [eabull_product field="risk|file|..." product="1"]
 *   ใช้ในเนื้อหา inc/content/pages/product-N.html
 *
 * @package eabull
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * จำนวนช่องสินค้าใน Customizer
 */
function eab_product_count() {
	return 3;
}

/* ==============================================================
 * ค่าเริ่มต้น
 * ============================================================== */

add_filter( 'eab_defaults', 'eab_products_defaults' );

function eab_products_defaults( $d ) {
	$p = array(
		/* หน้าแรก · ส่วนสินค้า */
		'show_home_products'    => true,
		'home_products_title'   => 'สินค้า EA ของ EA BULL',
		'home_products_sub'     => 'เรียงตามเลขรุ่นจากน้อยไปมาก แต่ละตัวมีหน้าของตัวเองที่บอกวิธีทำงาน ทุนที่ต้องใช้ และผลทดสอบพร้อมเงื่อนไข',
		'home_products_note'    => 'EA แต่ละตัวรับความเสี่ยงไม่เท่ากัน อ่านหน้าสินค้าและ[ประกาศความเสี่ยง](/risk-disclosure/)ให้ครบก่อนเลือก',

		/* ข้อความที่ใช้ร่วมกันทุกสินค้า */
		'product_ver_label'     => 'รุ่น',
		'product_badge'         => 'ตัวอย่าง',
		'product_menu_sub'      => 'หน้าสินค้าตัวอย่าง รอข้อมูลจริง',
		'product_desc_hint'     => 'คำอธิบายสั้นของสินค้า 1 ถึง 2 บรรทัด',
		'product_lead_hint'     => 'คำอธิบายสั้นว่าสินค้านี้เทรดอะไร ทำงานแบบไหน และเหมาะกับใคร',
		'product_more_text'     => 'ดูรายละเอียด',
		'product_dl_text'       => 'ดาวน์โหลดไฟล์',
		'product_dl_wait'       => 'ไฟล์ EA รอจากเจ้าของ',
		'product_go_dl_text'    => 'ดาวน์โหลด',
		'product_go_read_text'  => 'อ่านก่อนใช้',
		'product_updated_label' => 'อัปเดตล่าสุด',

		/* หน้าสินค้า */
		'product_crumb_label'   => 'สินค้า',
		'product_meta'          => "Expert Advisor สำหรับ MetaTrader 5\nรันบน MT5 ของ Windows หรือ VPS",
		'product_line_text'     => 'สอบถามทาง LINE',
		'product_page_dl_text'  => 'ดาวน์โหลดไฟล์ EA',
		'product_sum_title'     => 'ข้อมูลสั้น',
		'product_sum_file'      => 'ชื่อไฟล์',
		'product_sum_version'   => 'เลขรุ่น',
		'product_info_title'    => 'ข้อมูลที่ต้องรู้ก่อนเลือก',
		'product_info_sub'      => 'อ่านให้ครบทุกช่อง โดยเฉพาะเงินในบัญชีที่แนะนำและวิธีตัดขาดทุน เพราะสองเรื่องนี้บอกว่าบัญชีรับแรงได้แค่ไหน',
		'product_info_warn'     => 'ถ้าเงินในบัญชีน้อยกว่าที่แนะนำ หรือใช้เลเวอเรจสูงกว่าที่ทดสอบไว้ บัญชีอาจเสียหายเร็วกว่าที่เห็นในผลทดสอบ อ่าน[ประกาศความเสี่ยง](/risk-disclosure/)ก่อนใช้เงินจริง',
		'product_doc_label'     => 'รายละเอียด{name}',
		'product_toc_title'     => 'ในหน้านี้',
		'product_cmp_head'      => 'หัวข้อ',
		'product_cmp_rows'      => "สินทรัพย์\nประเภทระบบ\nทุนที่แนะนำ\nตัดขาดทุนอัตโนมัติ",
		'product_cmp_page'      => 'หน้าสินค้า',
		'product_cmp_link'      => 'ดูหน้าสินค้า',
		'product_cmp_here'      => 'หน้าที่เปิดอยู่',
		'product_panel_title'   => 'ภาพแผงข้อมูลของ EA บนกราฟ MT5',
		'product_panel_cap'     => 'ภาพประกอบ: ตัวเลขบนแผงในภาพเป็นตัวอย่างการแสดงผล ไม่ใช่ผลเทรดของสินค้า',
		'product_shot_wait'     => 'ภาพหน้าจอ รอจากเจ้าของ',
		'product_report_title'  => 'รายงาน Strategy Tester ของ{name}',
		'product_report_wait'   => 'รายงาน MT5 รอจากเจ้าของ',
		'product_report_cap'    => 'ช่องนี้ใช้เฉพาะรายงานจาก MT5 จริงของเจ้าของ ห้ามใช้ภาพที่ทำขึ้นเองหรือแต่งตัวเลข',
		'product_risk_wait'     => 'รอเอกสารสินค้าจากเจ้าของ',
		'product_cta_title'     => 'สนใจ{name} คุยกับทีมงานก่อนตัดสินใจ',
		'product_cta_sub'       => 'แจ้งทีมงานว่าจะเริ่มด้วยเงินเท่าไรและมีบัญชี MT5 แบบไหน แล้วเลือกโหมดที่เหมาะกับบัญชีของคุณด้วยกัน',
	);

	/* ชื่อช่องที่ใช้ร่วมกัน: การ์ดข้อมูล 8 ช่องในหน้าสินค้า · เงื่อนไขการทดสอบ 8 ช่อง */
	$stats = array( 'สินทรัพย์', 'กรอบเวลากราฟ', 'ประเภทบัญชี', 'โหมดในตัว', 'เงินในบัญชีที่แนะนำ', 'ตัดขาดทุนอัตโนมัติ', 'ตัวกรองข่าว', 'ประเภทระบบ' );
	$tests = array( 'ช่วงเวลาที่ทดสอบ', 'สินทรัพย์ / กรอบเวลา', 'โบรกเกอร์ / ประเภทบัญชี', 'ทุนตั้งต้น', 'เลเวอเรจ', 'โหมดที่ใช้', 'วิธีที่ MT5 จำลองราคา', 'ติดลบลอยสูงสุด' );
	foreach ( $stats as $i => $label ) {
		$p[ 'product_stat' . ( $i + 1 ) . '_label' ] = $label;
	}
	foreach ( $tests as $i => $label ) {
		$p[ 'product_test' . ( $i + 1 ) . '_label' ] = $label;
	}

	/* สินค้าตัวอย่าง 1 ถึง 3 · ทุกช่องข้อมูลว่าง (ห้ามแต่ง · CLAUDE.md หลักข้อ 1) */
	$specs = array( 'สินทรัพย์', 'กรอบเวลากราฟ', 'ประเภทบัญชี', 'ทุนที่แนะนำ' );
	for ( $n = 1; $n <= eab_product_count(); $n++ ) {
		$k = 'product' . $n . '_';

		$p[ $k . 'name' ]    = 'สินค้าตัวอย่าง ' . $n;
		$p[ $k . 'version' ] = $n . '.0';
		$p[ $k . 'slug' ]    = 'product-' . $n;
		$p[ $k . 'example' ] = true;
		$p[ $k . 'sub' ]     = '';
		$p[ $k . 'file' ]    = '';
		$p[ $k . 'dl_url' ]  = '';
		$p[ $k . 'updated' ] = '';
		foreach ( $specs as $i => $label ) {
			$p[ $k . 'spec' . ( $i + 1 ) . '_label' ] = $label;
			$p[ $k . 'spec' . ( $i + 1 ) . '_value' ] = '';
		}
		for ( $i = 1; $i <= 8; $i++ ) {
			$p[ $k . 'stat' . $i . '_value' ] = '';
		}
		for ( $i = 1; $i <= 8; $i++ ) {
			$p[ $k . 'test' . $i . '_value' ] = '';
		}
		$p[ $k . 'risk' ]       = '';
		$p[ $k . 'panel_img' ]  = '';
		$p[ $k . 'report_img' ] = '';
	}

	return array_merge( $d, $p );
}

/* ==============================================================
 * Customizer
 * ============================================================== */

add_filter( 'eab_customizer_sections', 'eab_products_customizer_sections', 12, 2 );

function eab_products_customizer_sections( $sections, $d ) {
	$wait  = 'เว้นว่าง = แสดงป้าย "รอข้อมูลจากเจ้าของ" · ห้ามแต่งข้อมูล ใช้จากเอกสารสินค้าของเจ้าของเท่านั้น';
	$token = '{name} = ชื่อสินค้า';

	$home = array(
		'eab_home_products' => array(
			'title'       => '2.2) หน้าแรก · สินค้า',
			'description' => 'แถวสินค้าเรียงตามเลขรุ่นเอง · ชื่อ เลขรุ่น คำอธิบาย และสเปกของแต่ละตัวตั้งที่หมวด "3.x) สินค้า"',
			'fields'      => array(
				'show_home_products'  => array( 'แสดงส่วนนี้', 'checkbox' ),
				'home_products_title' => array( 'หัวข้อ (H2)', 'text' ),
				'home_products_sub'   => array( 'คำอธิบายใต้หัวข้อ', 'textarea' ),
				'home_products_note'  => array( 'คำเตือนใต้แถวสินค้า (ห้ามลบ)', 'textarea', 'ลิงก์ในเว็บเขียนแบบ [ข้อความ](/slug/)' ),
			),
		),
	);

	$shared = array(
		'product_ver_label'     => array( 'ป้ายเหนือเลขรุ่น (เช่น รุ่น)', 'text' ),
		'product_badge'         => array( 'ป้ายสินค้าตัวอย่าง', 'text', 'แสดงเมื่อติ๊ก "เป็นสินค้าตัวอย่าง" ในหมวดของสินค้านั้น' ),
		'product_menu_sub'      => array( 'บรรทัดรองในเมนูของสินค้าตัวอย่างที่ยังไม่มีคำอธิบาย', 'text' ),
		'product_desc_hint'     => array( 'หน้าแรก · ข้อความหน้าป้ายรอข้อมูล เมื่อยังไม่มีคำอธิบายสั้น', 'text' ),
		'product_lead_hint'     => array( 'หน้าสินค้า · ข้อความหน้าป้ายรอข้อมูล เมื่อยังไม่มีคำอธิบายสั้น', 'text' ),
		'product_more_text'     => array( 'ปุ่มไปหน้าสินค้า (หน้าแรก)', 'text' ),
		'product_dl_text'       => array( 'ปุ่มดาวน์โหลด (หน้าแรก)', 'text' ),
		'product_dl_wait'       => array( 'ข้อความแจ้งเมื่อกดดาวน์โหลดแต่ยังไม่มีไฟล์', 'text' ),
		'product_go_dl_text'    => array( 'หน้า /go · ปุ่มดาวน์โหลด', 'text' ),
		'product_go_read_text'  => array( 'หน้า /go · ลิงก์ไปหน้าสินค้า', 'text' ),
		'product_updated_label' => array( 'ป้าย "อัปเดตล่าสุด"', 'text' ),
		'product_crumb_label'   => array( 'หน้าสินค้า · ชื่อกลุ่มใน breadcrumb (ลิงก์ไปส่วนสินค้าของหน้าแรก)', 'text' ),
		'product_meta'          => array( 'หน้าสินค้า · บรรทัดข้อมูลใต้คำอธิบาย (บรรทัดละ 1 ข้อ · สูงสุด 2)', 'textarea' ),
		'product_line_text'     => array( 'หน้าสินค้า · ปุ่ม LINE', 'text' ),
		'product_page_dl_text'  => array( 'หน้าสินค้า · ปุ่มดาวน์โหลด', 'text' ),
		'product_sum_title'     => array( 'หน้าสินค้า · หัวข้อกล่องข้อมูลสั้น', 'text' ),
		'product_sum_file'      => array( 'หน้าสินค้า · ป้ายชื่อไฟล์', 'text' ),
		'product_sum_version'   => array( 'หน้าสินค้า · ป้ายเลขรุ่น', 'text' ),
		'product_info_title'    => array( 'หน้าสินค้า · หัวข้อการ์ดข้อมูล (H2)', 'text' ),
		'product_info_sub'      => array( 'หน้าสินค้า · คำอธิบายใต้หัวข้อการ์ดข้อมูล', 'textarea' ),
	);
	for ( $i = 1; $i <= 8; $i++ ) {
		$shared[ 'product_stat' . $i . '_label' ] = array( 'การ์ดข้อมูลช่อง ' . $i . ' · ชื่อช่อง (ใช้กับทุกสินค้า)', 'text' );
	}
	$shared = array_merge(
		$shared,
		array(
			'product_info_warn'    => array( 'หน้าสินค้า · ข้อความเตือนถัดจากการ์ด 8 ช่อง (ต้องมีเสมอ)', 'textarea', 'ลิงก์ในเว็บเขียนแบบ [ข้อความ](/slug/)' ),
			'product_doc_label'    => array( 'หน้าสินค้า · ชื่อส่วนเอกสาร (โปรแกรมอ่านหน้าจอ · ' . $token . ')', 'text' ),
			'product_toc_title'    => array( 'หน้าสินค้า · หัวข้อสารบัญ', 'text' ),
			'product_cmp_head'     => array( 'ตารางเทียบ · หัวคอลัมน์แรก', 'text' ),
			'product_cmp_rows'     => array( 'ตารางเทียบ · แถว (บรรทัดละ 1 ชื่อ)', 'textarea', 'ค่าในแต่ละแถวดึงจากช่องสเปกหรือการ์ดข้อมูลของสินค้าที่ใช้ชื่อเดียวกัน' ),
			'product_cmp_page'     => array( 'ตารางเทียบ · ชื่อแถวลิงก์หน้าสินค้า', 'text' ),
			'product_cmp_link'     => array( 'ตารางเทียบ · ข้อความลิงก์', 'text' ),
			'product_cmp_here'     => array( 'ตารางเทียบ · ข้อความของหน้าที่เปิดอยู่', 'text' ),
		)
	);
	for ( $i = 1; $i <= 8; $i++ ) {
		$shared[ 'product_test' . $i . '_label' ] = array( 'เงื่อนไขการทดสอบช่อง ' . $i . ' · ชื่อช่อง', 'text' );
	}
	$shared = array_merge(
		$shared,
		array(
			'product_panel_title'  => array( 'ภาพแผงข้อมูล · ชื่อภาพ (ใช้เป็น alt)', 'text' ),
			'product_panel_cap'    => array( 'ภาพแผงข้อมูล · คำบรรยาย (จำเป็นเมื่อภาพมีตัวเลข)', 'text' ),
			'product_shot_wait'    => array( 'ช่องภาพที่ยังไม่มี · ข้อความ', 'text' ),
			'product_report_title' => array( 'รายงาน MT5 · ชื่อภาพ (' . $token . ')', 'text' ),
			'product_report_wait'  => array( 'รายงาน MT5 · ข้อความเมื่อยังไม่มีภาพ', 'text' ),
			'product_report_cap'   => array( 'รายงาน MT5 · คำบรรยาย', 'text' ),
			'product_risk_wait'    => array( 'ความเสี่ยงเฉพาะ · ข้อความเมื่อยังไม่มีข้อมูล', 'text' ),
			'product_cta_title'    => array( 'แถบติดต่อท้ายหน้าสินค้า · หัวข้อ (' . $token . ')', 'text' ),
			'product_cta_sub'      => array( 'แถบติดต่อท้ายหน้าสินค้า · คำอธิบาย', 'textarea' ),
		)
	);

	$items = array(
		'eab_products' => array(
			'title'       => '3) สินค้า · ข้อความที่ใช้ร่วมกัน',
			'description' => 'ป้าย ปุ่ม และชื่อช่องที่ทุกสินค้าใช้ร่วมกัน (หน้าแรก หน้า /go และหน้าสินค้า) · เนื้อหายาวของหน้าสินค้าอยู่ที่ inc/content/pages/product-N.html (นำเข้าด้วย EA BULL Setup)',
			'fields'      => $shared,
		),
	);

	for ( $n = 1; $n <= eab_product_count(); $n++ ) {
		$k      = 'product' . $n . '_';
		$fields = array(
			$k . 'name'    => array( 'ชื่อสินค้า (ว่าง = ซ่อนสินค้านี้ทั้งเว็บ)', 'text' ),
			$k . 'version' => array( 'เลขรุ่น (เช่น 1.0 · ใช้เรียงลำดับ)', 'text' ),
			$k . 'slug'    => array( 'slug ของหน้าสินค้า (เช่น product-' . $n . ')', 'text', 'ต้องตรงกับ slug ของเพจที่ใช้เทมเพลตหน้าสินค้า' ),
			$k . 'example' => array( 'เป็นสินค้าตัวอย่าง (แสดงป้าย "ตัวอย่าง")', 'checkbox' ),
			$k . 'sub'     => array( 'คำอธิบายสั้น 1 ถึง 2 บรรทัด', 'textarea', $wait ),
			$k . 'file'    => array( 'ชื่อไฟล์ EA (เช่น ชื่อไฟล์.ex5)', 'text', $wait ),
			$k . 'dl_url'  => array( 'ลิงก์ดาวน์โหลดไฟล์ (URL เต็ม หรือ assets/...)', 'path', 'เว้นว่าง = ปุ่มดาวน์โหลดแจ้งว่าไฟล์ยังไม่พร้อม' ),
			$k . 'updated' => array( 'อัปเดตล่าสุด (วันที่)', 'text', $wait ),
		);
		for ( $i = 1; $i <= 4; $i++ ) {
			$fields[ $k . 'spec' . $i . '_label' ] = array( 'สเปกแถวที่ ' . $i . ' (หน้าแรก) · ชื่อ', 'text' );
			$fields[ $k . 'spec' . $i . '_value' ] = array( 'สเปกแถวที่ ' . $i . ' · ค่า', 'text', 'เว้นว่าง = ใช้ค่าการ์ดข้อมูลที่ชื่อเดียวกัน · ไม่มี = ป้ายรอข้อมูล' );
		}
		for ( $i = 1; $i <= 8; $i++ ) {
			$label                                  = isset( $d[ 'product_stat' . $i . '_label' ] ) ? $d[ 'product_stat' . $i . '_label' ] : '';
			$fields[ $k . 'stat' . $i . '_value' ] = array( 'การ์ดข้อมูลช่อง ' . $i . ( '' !== $label ? ' (' . $label . ')' : '' ), 'text', 1 === $i ? $wait : '' );
		}
		for ( $i = 1; $i <= 8; $i++ ) {
			$label                                  = isset( $d[ 'product_test' . $i . '_label' ] ) ? $d[ 'product_test' . $i . '_label' ] : '';
			$fields[ $k . 'test' . $i . '_value' ] = array( 'เงื่อนไขการทดสอบช่อง ' . $i . ( '' !== $label ? ' (' . $label . ')' : '' ), 'text', 1 === $i ? 'ใส่เงื่อนไขจากรายงาน MT5 จริงเท่านั้น' : '' );
		}
		$fields[ $k . 'risk' ]       = array( 'ความเสี่ยงเฉพาะของสินค้านี้', 'textarea', 'ต้องบอกให้ครบตามเอกสารสินค้า ห้ามลดทอน · ' . $wait );
		$fields[ $k . 'panel_img' ]  = array( 'ภาพแผงข้อมูลของ EA บนกราฟ', 'image', 'ถ้าในภาพมีตัวเลข คำบรรยายต้องบอกว่าเป็นภาพตัวอย่าง' );
		$fields[ $k . 'report_img' ] = array( 'ภาพรายงาน Strategy Tester', 'image', 'รายงานจาก MT5 จริงเท่านั้น ห้ามแต่งตัวเลข' );

		$items[ 'eab_product_' . $n ] = array(
			'title'       => '3.' . $n . ') สินค้า ' . $n,
			'description' => 'ข้อมูลของสินค้าช่องที่ ' . $n . ' · ทุกหน้าที่แสดงสินค้าเรียงตามเลขรุ่นเอง',
			'fields'      => $fields,
		);
	}

	$sections = eab_products_insert_after( $sections, array( 'eab_home_hero' ), $home );
	return eab_products_insert_after( $sections, array( 'eab_home_articles', 'eab_home_risk' ), $items );
}

/**
 * แทรกหมวดต่อจาก section แรกที่พบในรายการ $after (ไม่พบ = ต่อท้าย)
 */
function eab_products_insert_after( $sections, $after, $insert ) {
	foreach ( (array) $after as $key ) {
		if ( isset( $sections[ $key ] ) ) {
			$out = array();
			foreach ( $sections as $sid => $section ) {
				$out[ $sid ] = $section;
				if ( $sid === $key ) {
					$out = array_merge( $out, $insert );
				}
			}
			return $out;
		}
	}
	return array_merge( $sections, $insert );
}

/* ==============================================================
 * ข้อมูลสินค้า
 * ============================================================== */

/**
 * สินค้าทั้งหมด เรียงตามเลขรุ่นจากน้อยไปมาก (ชื่อว่าง = ไม่แสดง)
 *
 * @return array[] {
 *     key      string  'product1' ...
 *     n        int     ช่องใน Customizer (1..3)
 *     name     string  ชื่อสินค้า
 *     version  string  เลขรุ่น ('1.0')
 *     label    string  ชื่อ + " · V" + เลขรุ่น (เมนู ตารางเทียบ)
 *     slug     string  slug ของหน้าสินค้า
 *     url      string  ลิงก์หน้าสินค้า ('' = เพจยังไม่เผยแพร่)
 *     sub      string  บรรทัดรองสำหรับเมนู (คำอธิบายสั้น หรือข้อความของสินค้าตัวอย่าง · อาจว่าง)
 *     desc     string  คำอธิบายสั้นตามที่กรอก ('' = รอข้อมูล)
 *     file     string  ชื่อไฟล์ EA ('' = รอข้อมูล)
 *     dl_url   string  ลิงก์ดาวน์โหลด ('' = ยังไม่มีไฟล์)
 *     updated  string  อัปเดตล่าสุด ('' = รอข้อมูล)
 *     example  bool    เป็นสินค้าตัวอย่าง
 *     specs    array   [ [ ชื่อ, ค่า ], ... ] 4 แถวของหน้าแรก (ค่าว่าง = รอข้อมูล)
 * }
 */
function eab_products() {
	$items = array();
	for ( $n = 1; $n <= eab_product_count(); $n++ ) {
		$k    = 'product' . $n . '_';
		$name = trim( (string) eab_mod( $k . 'name' ) );
		if ( '' === $name ) {
			continue;
		}
		$version = trim( preg_replace( '/[^0-9A-Za-z.]/', '', (string) eab_mod( $k . 'version' ) ) );
		$slug    = trim( strtolower( preg_replace( '/[^A-Za-z0-9\-]/', '', (string) eab_mod( $k . 'slug' ) ) ), '-' );
		$desc    = trim( (string) eab_mod( $k . 'sub' ) );
		$example = (bool) eab_mod( $k . 'example' );
		$specs   = array();
		for ( $i = 1; $i <= 4; $i++ ) {
			$label = trim( (string) eab_mod( $k . 'spec' . $i . '_label' ) );
			if ( '' === $label ) {
				continue;
			}
			$value = trim( (string) eab_mod( $k . 'spec' . $i . '_value' ) );
			if ( '' === $value ) {
				$value = eab_product_stat_by_label( $n, $label );
			}
			$specs[] = array( $label, $value );
		}
		$items[] = array(
			'key'     => 'product' . $n,
			'n'       => $n,
			'name'    => $name,
			'version' => $version,
			'label'   => $name . ( '' !== $version ? ' · V' . $version : '' ),
			'slug'    => $slug,
			'url'     => '' !== $slug ? eab_product_page_url( $slug ) : '',
			'sub'     => '' !== $desc ? $desc : ( $example ? trim( (string) eab_mod( 'product_menu_sub' ) ) : '' ),
			'desc'    => $desc,
			'file'    => trim( (string) eab_mod( $k . 'file' ) ),
			'dl_url'  => eab_theme_asset_url( eab_mod( $k . 'dl_url' ) ),
			'updated' => trim( (string) eab_mod( $k . 'updated' ) ),
			'example' => $example,
			'specs'   => $specs,
		);
	}
	usort(
		$items,
		static function ( $a, $b ) {
			$cmp = version_compare( '' !== $a['version'] ? $a['version'] : '0', '' !== $b['version'] ? $b['version'] : '0' );
			return 0 !== $cmp ? $cmp : $a['n'] - $b['n'];
		}
	);
	return (array) apply_filters( 'eab_products', $items );
}

/**
 * สินค้าตาม key ('product1' · '1' · 1) · ไม่พบ = null
 */
function eab_product( $key ) {
	$n = (int) preg_replace( '/\D/', '', (string) $key );
	foreach ( eab_products() as $item ) {
		if ( $item['n'] === $n ) {
			return $item;
		}
	}
	return null;
}

/**
 * สินค้าของหน้าที่เปิดอยู่ (ตาม slug ของเพจ) · ไม่พบ = สินค้าตัวแรก · ไม่มีสินค้า = null
 */
function eab_product_current() {
	$items = eab_products();
	if ( ! $items ) {
		return null;
	}
	$slug = function_exists( 'get_queried_object_id' ) ? (string) get_post_field( 'post_name', get_queried_object_id() ) : '';
	foreach ( $items as $item ) {
		if ( '' !== $slug && $item['slug'] === $slug ) {
			return $item;
		}
	}
	return $items[0];
}

/**
 * ลิงก์หน้าสินค้าเมื่อเพจเผยแพร่แล้ว ('' = ยังไม่มีเพจ กันลิงก์ไปหน้า 404)
 */
function eab_product_page_url( $slug ) {
	if ( function_exists( 'eab_published_page_url' ) ) {
		return (string) eab_published_page_url( $slug );
	}
	return home_url( '/' . $slug . '/' );
}

/**
 * ค่าการ์ดข้อมูลของสินค้า N ที่ชื่อช่องตรงกับ $label ('' = ไม่มี/ยังไม่กรอก)
 */
function eab_product_stat_by_label( $n, $label ) {
	for ( $i = 1; $i <= 8; $i++ ) {
		if ( trim( (string) eab_mod( 'product_stat' . $i . '_label' ) ) === $label ) {
			return trim( (string) eab_mod( 'product' . $n . '_stat' . $i . '_value' ) );
		}
	}
	return '';
}

/**
 * ค่าของสินค้าตามชื่อช่อง: สเปกของหน้าแรกก่อน แล้วการ์ดข้อมูล (ใช้ในตารางเทียบ)
 */
function eab_product_fact( $item, $label ) {
	foreach ( $item['specs'] as $spec ) {
		if ( $spec[0] === $label && '' !== $spec[1] ) {
			return $spec[1];
		}
	}
	return eab_product_stat_by_label( $item['n'], $label );
}

/**
 * แทน {name} ด้วยชื่อสินค้า
 */
function eab_product_text( $key, $item ) {
	return str_replace( '{name}', $item['name'], trim( (string) eab_mod( $key ) ) );
}

/**
 * ข้อความในบรรทัดที่มีลิงก์ [ข้อความ](/slug/) · ใช้ตัวช่วยของหน้าแรกเมื่อมี
 */
function eab_product_inline( $text ) {
	return function_exists( 'eab_home_inline' ) ? eab_home_inline( $text ) : esc_html( $text );
}

/**
 * ป้ายสินค้าตัวอย่าง
 */
function eab_product_badge( $item ) {
	$badge = trim( (string) eab_mod( 'product_badge' ) );
	return $item['example'] && '' !== $badge ? '<span class="badge badge--ex">' . esc_html( $badge ) . '</span>' : '';
}

/**
 * ปุ่มดาวน์โหลดไฟล์ · ยังไม่มีไฟล์ = data-wait (main.js แจ้งใน #toast) ไม่ชี้ไปไฟล์ที่ไม่มีอยู่
 */
function eab_product_dl_link( $item, $class, $text, $pos ) {
	$text = trim( (string) $text );
	if ( '' === $text ) {
		return '';
	}
	$icon = eab_icon( 'download' );
	if ( '' === $item['dl_url'] ) {
		return sprintf(
			'<a class="%1$s" href="#" data-wait="%2$s">%3$s%4$s</a>',
			esc_attr( $class ),
			esc_attr( eab_mod( 'product_dl_wait' ) ),
			$icon,
			esc_html( $text )
		);
	}
	return sprintf(
		'<a class="%1$s" href="%2$s"%3$s data-go-pos="%4$s">%5$s%6$s</a>',
		esc_attr( $class ),
		esc_url( $item['dl_url'] ),
		preg_match( '/\.(zip|ex5|pdf)([?#]|$)/i', $item['dl_url'] ) ? ' download' : '',
		esc_attr( $pos ),
		$icon,
		esc_html( $text )
	);
}

/**
 * ค่าในตาราง/การ์ด: มีค่า = ข้อความ (ห่อด้วย $tag) · ว่าง = ป้ายรอข้อมูล
 */
function eab_product_value( $value, $tag = 'span', $label = '' ) {
	$value = trim( (string) $value );
	if ( '' === $value ) {
		return eab_wait( '', $label );
	}
	return '' !== $tag ? '<' . $tag . '>' . esc_html( $value ) . '</' . $tag . '>' : esc_html( $value );
}

/* ==============================================================
 * หน้าแรก · ส่วนสินค้า (.prods > .prow)
 * ============================================================== */

function eab_homeplus_products() {
	$items = eab_products();
	if ( ! $items ) {
		return;
	}
	$ver   = trim( (string) eab_mod( 'product_ver_label' ) );
	$more  = trim( (string) eab_mod( 'product_more_text' ) );
	$note  = trim( (string) eab_mod( 'home_products_note' ) );
	?>
<section class="band" id="products" aria-labelledby="products-title">
	<div class="wrap">
		<?php eab_home_shead( 'products-title', eab_mod( 'home_products_title' ), eab_mod( 'home_products_sub' ) ); ?>
		<div class="prods">
			<?php foreach ( $items as $item ) : ?>
			<article class="prow">
				<div class="prow__ver"><?php echo '' !== $ver ? '<small>' . esc_html( $ver ) . '</small>' : ''; ?><b><?php echo eab_wait( $item['version'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></b></div>
				<div class="prow__body">
					<div>
						<div class="prow__title"><h3><?php echo esc_html( $item['name'] ); ?></h3><?php echo eab_product_badge( $item ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></div>
						<p><?php echo eab_home_pending( $item['desc'], eab_mod( 'product_desc_hint' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></p>
					</div>
					<?php if ( $item['specs'] ) : ?>
					<ul class="specs">
						<?php foreach ( $item['specs'] as $spec ) : ?>
						<li><span><?php echo esc_html( $spec[0] ); ?></span><?php echo eab_product_value( $spec[1] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></li>
						<?php endforeach; ?>
					</ul>
					<?php endif; ?>
					<div class="prow__cta">
						<?php if ( '' !== $item['url'] && '' !== $more ) : ?>
						<a class="btn btn--light" href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $more ); ?> <?php echo eab_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
						<?php endif; ?>
						<?php echo eab_product_dl_link( $item, 'btn btn--outline', eab_mod( 'product_dl_text' ), 'home-download-' . $item['n'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?>
					</div>
				</div>
			</article>
			<?php endforeach; ?>
		</div>
		<?php if ( '' !== $note ) : ?>
		<p class="note note--warn mt-24"><?php echo eab_icon( 'alert' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo eab_product_inline( $note ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></span></p>
		<?php endif; ?>
	</div>
</section>
	<?php
}

/* ==============================================================
 * หน้า /go ขั้น 4 · การ์ดดาวน์โหลดทุกสินค้า (.dlcards) เรียงตามเลขรุ่น
 * ============================================================== */

/**
 * @param bool $echo true = พิมพ์ (ค่าเริ่มต้น) · false = คืนค่า HTML
 * @return string HTML เมื่อ $echo = false
 */
function eab_go_product_downloads( $echo = true ) {
	$items = eab_products();
	$html  = '';
	if ( $items ) {
		$label = trim( (string) eab_mod( 'product_updated_label' ) );
		$read  = trim( (string) eab_mod( 'product_go_read_text' ) );
		$html .= '<div class="dlcards">';
		foreach ( $items as $item ) {
			$html .= '<div class="dlcard">';
			$html .= '<span class="dlcard__ver">' . ( '' !== $item['version'] ? esc_html( 'V' . $item['version'] ) : '' ) . '</span>';
			$html .= '<div>';
			$html .= '<b>' . esc_html( $item['name'] ) . ( $item['example'] ? ' ' . eab_product_badge( $item ) : '' ) . '</b>';
			$html .= '<span class="small muted">' . ( '' !== $label ? esc_html( $label ) . ' ' : '' ) . eab_wait( $item['updated'] ) . '</span>';
			$html .= '<div class="row">' . eab_product_dl_link( $item, 'btn btn--primary btn--sm', eab_mod( 'product_go_dl_text' ), 'go-download-' . $item['n'] );
			if ( '' !== $item['url'] && '' !== $read ) {
				$html .= '<a class="tlink" href="' . esc_url( $item['url'] ) . '">' . esc_html( $read ) . ' ' . eab_icon( 'arrow-right' ) . '</a>';
			}
			$html .= '</div></div></div>';
		}
		$html .= '</div>';
	}
	if ( ! $echo ) {
		return $html;
	}
	echo $html; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above
	return '';
}

/* ==============================================================
 * หน้าสินค้า (template-product.php) · ต้นแบบ dev/mockup/_src/pages/product.html
 * หัวเพจ (.phead) → การ์ดข้อมูล 8 ช่อง (.stats) → เอกสารยาวจาก editor (.doclay: สารบัญ + .doc.paper) → แถบติดต่อ
 * ============================================================== */

function eab_products_page() {
	get_header();
	if ( have_posts() ) {
		the_post();
	}
	$item = eab_product_current();
	if ( ! $item ) {
		echo '<main id="main"></main>';
		get_footer();
		return;
	}
	$n       = $item['n'];
	$ver     = trim( (string) eab_mod( 'product_ver_label' ) );
	$meta    = array_slice( eab_lines( eab_mod( 'product_meta' ) ), 0, 2 );
	$icons   = array( 'candles', 'monitor' );
	$crumbs  = function_exists( 'eab_breadcrumbs' ) ? eab_breadcrumbs( $item['name'] ) : array();
	$home    = ! empty( $crumbs[0] ) ? $crumbs[0] : array(
		'name' => 'หน้าแรก',
		'url'  => home_url( '/' ),
	);
	$group   = trim( (string) eab_mod( 'product_crumb_label' ) );
	$content = apply_filters( 'the_content', get_the_content() ); // เติม $GLOBALS['eab_toc'] ก่อนพิมพ์สารบัญ
	$toc     = array();
	foreach ( (array) ( isset( $GLOBALS['eab_toc'] ) ? $GLOBALS['eab_toc'] : array() ) as $entry ) {
		if ( 2 === (int) $entry['level'] && '' !== $entry['text'] ) {
			$toc[] = $entry;
		}
	}
	$sum_title = trim( (string) eab_mod( 'product_sum_title' ) );
	$warn      = trim( (string) eab_mod( 'product_info_warn' ) );
	?>
<main id="main" class="product-page product-page--<?php echo (int) $n; ?>">

<section class="phead">
	<div class="wrap">
		<ol class="crumbs">
			<li><a href="<?php echo esc_url( $home['url'] ); ?>"><?php echo esc_html( $home['name'] ); ?></a></li>
			<?php if ( '' !== $group ) : ?>
			<li><a href="<?php echo esc_url( home_url( '/#products' ) ); ?>"><?php echo esc_html( $group ); ?></a></li>
			<?php endif; ?>
			<li aria-current="page"><?php echo esc_html( $item['name'] ); ?></li>
		</ol>
		<div class="phead__grid">
			<div>
				<div class="row">
					<?php if ( '' !== $item['version'] ) : ?>
					<span class="badge badge--acc"><?php echo esc_html( trim( $ver . ' V' . $item['version'] ) ); ?></span>
					<?php endif; ?>
					<?php echo eab_product_badge( $item ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?>
				</div>
				<h1 class="mt-16"><?php echo esc_html( $item['name'] ); ?><?php if ( '' !== $item['version'] ) : ?> <span class="nw">· V<?php echo esc_html( $item['version'] ); ?></span><?php endif; ?></h1>
				<p class="lead"><?php echo eab_home_pending( $item['desc'], eab_mod( 'product_lead_hint' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></p>
				<?php if ( $meta ) : ?>
				<div class="phead__meta">
					<?php foreach ( $meta as $i => $line ) : ?>
					<span><?php echo eab_icon( $icons[ $i ] ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( $line ); ?></span>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>
				<div class="btns">
					<?php
					if ( function_exists( 'eab_home_contact_link' ) ) {
						eab_home_contact_link( eab_mod( 'product_line_text' ), 'btn btn--line', 'product-' . $n . '-hero' );
					}
					echo eab_product_dl_link( $item, 'btn btn--outline', eab_mod( 'product_page_dl_text' ), 'product-' . $n . '-download' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside
					?>
				</div>
			</div>
			<aside class="sumbox"<?php echo '' !== $sum_title ? ' aria-label="' . esc_attr( $sum_title ) . '"' : ''; ?>>
				<?php if ( '' !== $sum_title ) : ?>
				<h2><?php echo esc_html( $sum_title ); ?></h2>
				<?php endif; ?>
				<ul>
					<li><b><?php echo esc_html( eab_mod( 'product_sum_file' ) ); ?></b><?php echo eab_product_value( $item['file'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></li>
					<li><b><?php echo esc_html( eab_mod( 'product_sum_version' ) ); ?></b><?php echo eab_product_value( '' !== $item['version'] ? 'V' . $item['version'] : '' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></li>
					<li><b><?php echo esc_html( eab_mod( 'product_updated_label' ) ); ?></b><?php echo eab_product_value( $item['updated'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></li>
				</ul>
			</aside>
		</div>
	</div>
</section>

<section class="band" aria-labelledby="info-title">
	<div class="wrap">
		<?php eab_home_shead( 'info-title', eab_mod( 'product_info_title' ), eab_mod( 'product_info_sub' ) ); ?>
		<?php eab_product_stats( 'stat', $n ); ?>
		<?php if ( '' !== $warn ) : ?>
		<p class="note note--warn mt-24"><?php echo eab_icon( 'alert' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo eab_product_inline( $warn ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></span></p>
		<?php endif; ?>
	</div>
</section>

	<?php if ( '' !== trim( wp_strip_all_tags( $content ) ) ) : ?>
<section class="band band--alt" aria-label="<?php echo esc_attr( eab_product_text( 'product_doc_label', $item ) ); ?>">
	<div class="wrap doclay">
		<?php if ( count( $toc ) > 2 ) : ?>
		<details class="toc" open>
			<summary><?php echo esc_html( eab_mod( 'product_toc_title' ) ); ?><?php echo eab_icon( 'chevron-down' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></summary>
			<ol>
				<?php foreach ( $toc as $entry ) : ?>
				<li><a href="#<?php echo esc_attr( $entry['id'] ); ?>"><?php echo esc_html( $entry['text'] ); ?></a></li>
				<?php endforeach; ?>
			</ol>
		</details>
		<?php endif; ?>
		<article class="doc paper">
			<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput -- the_content ?>
		</article>
	</div>
</section>
	<?php endif; ?>

</main>
	<?php
	if ( function_exists( 'eab_line_cta' ) ) {
		eab_line_cta( eab_product_text( 'product_cta_title', $item ), eab_mod( 'product_cta_sub' ) );
	}
	get_footer();
}

/**
 * การ์ดข้อมูล 8 ช่อง (.stats) · $type = 'stat' (ข้อมูลที่ต้องรู้) หรือ 'test' (เงื่อนไขการทดสอบ)
 */
function eab_product_stats( $type, $n, $echo = true ) {
	$html = '';
	for ( $i = 1; $i <= 8; $i++ ) {
		$label = trim( (string) eab_mod( 'product_' . $type . $i . '_label' ) );
		if ( '' === $label ) {
			continue;
		}
		$html .= '<div class="stat"><span>' . esc_html( $label ) . '</span>' . eab_product_value( eab_mod( 'product' . $n . '_' . $type . $i . '_value' ), 'b' ) . '</div>';
	}
	if ( '' !== $html ) {
		$html = '<div class="stats' . ( 'stat' === $type ? ' mt-24' : '' ) . '">' . $html . '</div>';
	}
	if ( ! $echo ) {
		return $html;
	}
	echo $html; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above
	return '';
}

/**
 * ช่องภาพในเอกสาร: มีภาพ = รูป (ขยายได้ด้วย a.lightbox) · ไม่มี = กรอบ .shot "รอจากเจ้าของ" · ตามด้วยคำบรรยาย
 */
function eab_product_shot( $item, $type ) {
	$img   = eab_theme_asset_url( eab_mod( 'product' . $item['n'] . '_' . $type . '_img' ) );
	$title = eab_product_text( 'product_' . $type . '_title', $item );
	$cap   = trim( (string) eab_mod( 'product_' . $type . '_cap' ) );
	$wait  = trim( (string) eab_mod( 'report' === $type ? 'product_report_wait' : 'product_shot_wait' ) );
	if ( '' !== $img ) {
		$html = '<a class="lightbox" href="' . esc_url( $img ) . '" data-group="product-' . (int) $item['n'] . '" data-caption="' . esc_attr( '' !== $cap ? $cap : $title ) . '">'
			. '<img src="' . esc_url( $img ) . '" alt="' . esc_attr( $title ) . '" width="1280" height="720" loading="lazy" decoding="async"></a>';
	} else {
		$html = '<div class="shot">' . eab_icon( 'report' === $type ? 'file' : 'candles' ) . '<span><b>' . esc_html( $title ) . '</b>' . esc_html( $wait ) . '</span></div>';
	}
	if ( '' !== $cap ) {
		$html .= '<p class="cap">' . esc_html( $cap ) . '</p>';
	}
	return $html;
}

/**
 * ตารางเทียบสินค้าทุกตัว (เรียงตามเลขรุ่น) · คอลัมน์ของสินค้าที่เปิดอยู่ = .us
 */
function eab_product_compare( $current ) {
	$items = eab_products();
	$rows  = eab_lines( eab_mod( 'product_cmp_rows' ) );
	if ( ! $items || ! $rows ) {
		return '';
	}
	$here = trim( (string) eab_mod( 'product_cmp_here' ) );
	$link = trim( (string) eab_mod( 'product_cmp_link' ) );
	$page = trim( (string) eab_mod( 'product_cmp_page' ) );
	$us   = function ( $item ) use ( $current ) {
		return $item['n'] === $current['n'] ? ' class="us"' : '';
	};

	/* table-wrap--own: บอก eab_infra_wrap_content_tables() (infra.php) ว่าตารางนี้มีกรอบแล้ว ไม่ต้องครอบ .table-wrap ซ้ำ */
	$html  = '<div class="tbl-wrap table-wrap--own"><table class="tbl tbl--stack"><thead><tr>';
	$html .= '<th scope="col">' . esc_html( eab_mod( 'product_cmp_head' ) ) . '</th>';
	foreach ( $items as $item ) {
		$html .= '<th scope="col"' . $us( $item ) . '>' . esc_html( $item['label'] ) . '</th>';
	}
	$html .= '</tr></thead><tbody>';
	foreach ( $rows as $label ) {
		$html .= '<tr><th scope="row">' . esc_html( $label ) . '</th>';
		foreach ( $items as $item ) {
			$html .= '<td data-label="' . esc_attr( $item['label'] ) . '"' . $us( $item ) . '>' . eab_product_value( eab_product_fact( $item, $label ), '' ) . '</td>';
		}
		$html .= '</tr>';
	}
	if ( '' !== $page ) {
		$html .= '<tr><th scope="row">' . esc_html( $page ) . '</th>';
		foreach ( $items as $item ) {
			if ( $item['n'] === $current['n'] ) {
				$cell = '<b>' . esc_html( $here ) . '</b>';
			} elseif ( '' !== $item['url'] && '' !== $link ) {
				$cell = '<a href="' . esc_url( $item['url'] ) . '">' . esc_html( $link ) . '</a>';
			} else {
				$cell = eab_wait( '' );
			}
			$html .= '<td data-label="' . esc_attr( $item['label'] ) . '"' . $us( $item ) . '>' . $cell . '</td>';
		}
		$html .= '</tr>';
	}
	return $html . '</tbody></table></div>';
}

/* ==============================================================
 * Shortcode ในเนื้อหาหน้าสินค้า
 * [eabull_product part="compare|tests|panel" product="1"]
 * [eabull_product field="risk|file|name|version|updated|desc|stat1..8|test1..8" product="1"]
 * ไม่ใส่ product = สินค้าของหน้าที่เปิดอยู่
 * ============================================================== */

function eab_sc_product( $atts ) {
	$atts = shortcode_atts(
		array(
			'part'    => '',
			'field'   => '',
			'product' => '',
		),
		$atts,
		'eabull_product'
	);
	$item = '' !== trim( (string) $atts['product'] ) ? eab_product( $atts['product'] ) : eab_product_current();
	if ( ! $item ) {
		return '';
	}
	switch ( sanitize_key( $atts['part'] ) ) {
		case 'compare':
			return eab_product_compare( $item );
		case 'tests':
			return eab_product_stats( 'test', $item['n'], false ) . eab_product_shot( $item, 'report' );
		case 'panel':
			return eab_product_shot( $item, 'panel' );
	}
	$field = sanitize_key( $atts['field'] );
	if ( 'risk' === $field ) {
		return eab_wait( eab_mod( 'product' . $item['n'] . '_risk' ), trim( (string) eab_mod( 'product_risk_wait' ) ) );
	}
	if ( in_array( $field, array( 'file', 'name', 'version', 'updated', 'desc' ), true ) ) {
		return eab_wait( $item[ $field ] );
	}
	if ( preg_match( '/^(stat|test)([1-8])$/', $field, $m ) ) {
		return eab_wait( eab_mod( 'product' . $item['n'] . '_' . $m[1] . $m[2] . '_value' ) );
	}
	return '';
}
add_shortcode( 'eabull_product', 'eab_sc_product' );
