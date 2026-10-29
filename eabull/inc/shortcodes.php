<?php
/**
 * EA BULL · Shortcodes สำหรับใช้ในเนื้อหาเพจ/บทความ
 *
 * [eabull_line pos="xxx"]ข้อความปุ่ม[/eabull_line]  ปุ่ม LINE (ลิงก์จาก Customizer)
 * [eabull_brand]                                   ชื่อแบรนด์
 * [eabull_broker field="name|server|url"]          ชื่อโบรกเกอร์ · ชื่อ Server ที่เลือกตอนล็อกอิน MT5 · ปุ่มสมัคร (ลิงก์พาร์ตเนอร์)
 * [eabull_ea field="name|version|bt_range"]        ค่ากลางแบบไม่ระบุสินค้า (ea_version, ea_bt_*) · ว่าง = ป้ายรอข้อมูล
 * [eabull_ea product="1|2|3" field="file|name|version"] ชื่อไฟล์ / ชื่อไม่มีนามสกุล / เลขรุ่น ของสินค้าในหมวด 3.x (ยังไม่กรอก = ป้ายรอข้อมูล)
 * [eabull_calc type="lot|drawdown"]                เครื่องคำนวณ markup ตามต้นแบบ (ตัวเลขคำนวณใน assets/js/main.js · ข้อความใน setting calc_*)
 * [eabull_figure name="..." alt="" caption=""]     แผนภาพในธีม (assets/img/illus · ลงทะเบียนผ่านฟิลเตอร์ eab_figure_registry · ไม่มีไฟล์ = ไม่แสดง)
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
		$text = 'ทัก LINE คุยกับทีมงาน';
	}
	// ปลายทางตามกติกาเดียวกับปุ่มติดต่ออื่น (eab_contact_target): LINE OA ก่อน ถ้ายังไม่มีไปหน้า /go/
	$target = eab_contact_target();
	if ( '' === $target['url'] ) {
		return '';
	}
	if ( ! $target['is_line'] ) {
		$text = eab_mod( 'contact_fallback_text' );
	}
	return '<p class="sc-line"><a class="btn btn--line" href="' . esc_url( $target['url'] ) . '"' . ( $target['is_line'] ? ' target="_blank" rel="noopener"' : '' ) . ' data-line-pos="' . esc_attr( sanitize_key( $atts['pos'] ) ) . '">'
		. eab_icon( 'chat' ) . esc_html( $text ) . '</a></p>';
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
		return $val ? '<a class="btn btn--primary" href="' . esc_url( $val ) . '" target="_blank" rel="sponsored noopener">' . eab_icon( 'user' ) . esc_html( eab_mod( 'broker_signup_text' ) ) . '</a>' : '';
	}
	return esc_html( $val );
}
add_shortcode( 'eabull_broker', 'eab_sc_broker' );

/**
 * ค่ากลางของไฟล์ EA แบบไม่ผูกกับสินค้าตัวใด (ea_version, ea_bt_from, ea_bt_to)
 * EA BULL มีหลายสินค้า แต่ละตัวมีชื่อไฟล์และเลขรุ่นของตัวเองใน product{n}_* (ใช้ [eabull_ea product="n"])
 * ยังไม่กรอก = คืนค่าว่าง แล้ว shortcode แสดงป้ายรอข้อมูล · ห้ามเดาเลขรุ่นแทนเจ้าของ
 */
function eab_ea_version() {
	return preg_replace( '/[^0-9.]/', '', (string) eab_mod( 'ea_version' ) );
}

function eab_ea_name() {
	$v = eab_ea_version();
	return '' !== $v ? 'EA_BULL_V' . $v : '';
}

function eab_ea_bt_range() {
	$from = trim( (string) eab_mod( 'ea_bt_from' ) );
	$to   = trim( (string) eab_mod( 'ea_bt_to' ) );
	return ( '' !== $from && '' !== $to ) ? $from . ' ถึง ' . $to : '';
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
	/* สินค้าใน Customizer หมวด 3.x (inc/modules/products.php) · [eabull_ea product="1|2|3" field="file|name|version"]
	 * file = ชื่อไฟล์ตามที่เจ้าของกรอก · name = ชื่อไฟล์ไม่มีนามสกุล · version = เลขรุ่น · ยังไม่กรอก = ป้าย "รอข้อมูลจากเจ้าของ" */
	if ( '' !== $atts['product'] && function_exists( 'eab_product' ) ) {
		$item = eab_product( $atts['product'] );
		if ( ! $item ) {
			return '';
		}
		switch ( $atts['field'] ) {
			case 'name':
				return eab_wait( preg_replace( '/\.(ex5|zip)$/i', '', $item['file'] ) );
			case 'version':
				return eab_wait( $item['version'] );
		}
		return eab_wait( $item['file'] );
	}
	switch ( $atts['field'] ) {
		case 'name':
			return eab_wait( eab_ea_name() );
		case 'version':
			return eab_wait( eab_ea_version() );
		case 'bt_range':
			return eab_wait( eab_ea_bt_range() );
	}
	return eab_wait( '' !== eab_ea_name() ? eab_ea_name() . '.ex5' : '' );
}
add_shortcode( 'eabull_ea', 'eab_sc_ea' );

/**
 * แผนภาพในเนื้อหา · ชื่อ => array( ไฟล์กว้าง, ไฟล์มือถือ, alt ตั้งต้น, กว้าง, สูง, แสดงขนาดจริง )
 * ไฟล์อยู่ใน assets/img/illus/ (หรือ ../<โฟลเดอร์>/ ใต้ assets/img) · ลงทะเบียนผ่านฟิลเตอร์ eab_figure_registry
 * แผนภาพชุดเดิมของธีมต้นทางถูกเอาออกแล้ว รายการจึงว่างเป็นค่าเริ่มต้น
 * ภาพใหม่ต้องใช้ชื่อไฟล์ใหม่ (เลข v ท้ายชื่อ) เพราะเบราว์เซอร์และ CDN จำไฟล์ตาม URL
 */
function eab_figure_registry() {
	return (array) apply_filters( 'eab_figure_registry', array() );
}

/**
 * [eabull_figure name="..." alt="" caption=""]
 * มีไฟล์ = <figure> · ไม่มีชื่อในรายการหรือไม่มีไฟล์ = ผู้เข้าชมไม่เห็นอะไร (แอดมินเห็นกรอบ .shot บอกว่ารอภาพอะไร)
 */
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
	$fig  = isset( $all[ $name ] ) ? (array) $all[ $name ] : array();
	$file = isset( $fig[0] ) ? (string) $fig[0] : '';
	$path = 0 === strpos( $file, '../' ) ? substr( $file, 3 ) : 'illus/' . $file;

	if ( '' === $file || ! is_readable( get_template_directory() . '/assets/img/' . $path ) ) {
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			return '';
		}
		$label = '' !== trim( (string) $atts['caption'] ) ? $atts['caption'] : ( isset( $fig[2] ) ? $fig[2] : $name );
		return '<div class="shot">' . eab_icon( 'file' ) . '<span><b>' . esc_html( $label ) . '</b>' . esc_html( eab_mod( 'guides_shot_wait' ) ) . '</span></div>';
	}

	$base = get_template_directory_uri() . '/assets/img/';
	$alt  = '' !== trim( (string) $atts['alt'] ) ? $atts['alt'] : ( isset( $fig[2] ) ? $fig[2] : '' );
	$w    = isset( $fig[3] ) ? (int) $fig[3] : 1600;
	$h    = isset( $fig[4] ) ? (int) $fig[4] : 760;
	$html = '<figure class="eab-figure' . ( ! empty( $fig[5] ) ? ' eab-figure--fit' : '' ) . '"><picture>';
	if ( ! empty( $fig[1] ) ) {
		$mobile = 0 === strpos( (string) $fig[1], '../' ) ? substr( (string) $fig[1], 3 ) : 'illus/' . $fig[1];
		$html  .= '<source media="(max-width: 680px)" srcset="' . esc_url( $base . $mobile ) . '" width="800" height="1140">';
	}
	$html .= '<img src="' . esc_url( $base . $path ) . '" alt="' . esc_attr( $alt ) . '" width="' . $w . '" height="' . $h . '" loading="lazy" decoding="async"></picture>';
	if ( '' !== trim( (string) $atts['caption'] ) ) {
		$html .= '<figcaption>' . esc_html( $atts['caption'] ) . '</figcaption>';
	}
	return $html . '</figure>';
}
add_shortcode( 'eabull_figure', 'eab_sc_figure' );

/**
 * ตัวเลขสำหรับแสดงผลตอนโหลดหน้า (รูปแบบเดียวกับ main.js: คั่นหลักพัน ทศนิยม 2 ตำแหน่ง)
 */
function eab_calc_num( $value ) {
	$value = (float) $value;
	return number_format( is_finite( $value ) ? $value : 0, 2 );
}

/**
 * ช่องกรอกของเครื่องคำนวณ (.field)
 */
function eab_calc_field( $id, $name, $key, $attrs, $value = null, $readonly = false ) {
	$help  = trim( (string) eab_mod( 'calc_' . $key . '_help' ) );
	$value = null === $value ? trim( (string) eab_mod( 'calc_' . $key . '_value' ) ) : $value;
	return '<div class="field"><label for="' . esc_attr( $id ) . '">' . esc_html( eab_mod( 'calc_' . $key . '_label' ) ) . '</label>'
		. '<input id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" type="number" inputmode="decimal" ' . $attrs . ' value="' . esc_attr( $value ) . '"' . ( $readonly ? ' readonly' : '' ) . '>'
		. ( '' !== $help ? '<small>' . esc_html( $help ) . '</small>' : '' ) . '</div>';
}

/**
 * [eabull_calc type="lot|drawdown"] · markup ตาม hook ของ main.js (docs/phase2.md ข้อ 4)
 * lot: .calc[data-calc="lot"][data-money-unit][data-lot-unit] · ช่อง balance risk sl step preset pip · ผล money lot
 * drawdown: .calc[data-calc="dd"][data-money-unit][data-empty] · ช่อง dd capital · ผล back left bar
 * ค่าในช่องผลลัพธ์คำนวณจากตัวเลขตัวอย่างไว้ก่อน (อ่านได้แม้ JS ยังไม่ทำงาน)
 */
function eab_sc_calc( $atts ) {
	$atts = shortcode_atts( array( 'type' => 'lot' ), $atts, 'eabull_calc' );
	static $count = 0;
	$count++;
	$sfx   = $count > 1 ? '-' . $count : '';
	$money = trim( (string) eab_mod( 'calc_money_unit' ) );
	$note  = trim( (string) eab_mod( 'calc_example' ) );
	$hint  = '' !== $note ? '<p class="field--full small muted">' . esc_html( $note ) . '</p>' : '';

	if ( in_array( $atts['type'], array( 'drawdown', 'dd' ), true ) ) {
		$dd   = min( max( (float) eab_mod( 'calc_dd_value' ), 0 ), 99.9 );
		$cap  = (float) eab_mod( 'calc_cap_value' );
		$back = $dd / ( 100 - $dd ) * 100;
		$left = $cap > 0 ? eab_calc_num( $cap * ( 1 - $dd / 100 ) ) . ' ' . $money : (string) eab_mod( 'calc_dd_empty' );
		return '<div class="calc" data-calc="dd" data-money-unit="' . esc_attr( $money ) . '" data-empty="' . esc_attr( eab_mod( 'calc_dd_empty' ) ) . '">'
			. '<div class="calc__in"><h3><span class="tile tile--accent">' . eab_icon( 'gauge' ) . '</span>' . esc_html( eab_mod( 'calc_dd_title' ) ) . '</h3>' . $hint
			. eab_calc_field( 'dd-pct' . $sfx, 'dd', 'dd', 'min="0" max="99.9" step="any"' )
			. eab_calc_field( 'dd-cap' . $sfx, 'capital', 'cap', 'min="0" step="any"' )
			. '</div><div class="calc__out" aria-live="polite">'
			. '<div class="out"><span>' . esc_html( eab_mod( 'calc_out_back' ) ) . '</span><b data-out="back">' . esc_html( eab_calc_num( $back ) ) . '%</b></div>'
			. '<div class="out out--sm"><span>' . esc_html( eab_mod( 'calc_out_left' ) ) . '</span><b data-out="left">' . esc_html( $left ) . '</b></div>'
			. '<div class="out"><span>' . esc_html( eab_mod( 'calc_out_bar' ) ) . '</span><div class="ddbar mt-16"><i data-out="bar" style="width: ' . esc_attr( (string) round( 100 - $dd, 2 ) ) . '%"></i></div></div>'
			. '</div></div>';
	}

	/* ตัวเลือกประเภทสินทรัพย์ "มูลค่า pip | ชื่อ" · ตัวเลือกแรกกำหนดค่าเริ่มต้นของช่องมูลค่า pip */
	$options = '';
	$first   = null;
	foreach ( eab_lines( eab_mod( 'calc_presets' ) ) as $line ) {
		$parts = array_map( 'trim', explode( '|', $line, 2 ) );
		if ( 2 !== count( $parts ) || '' === $parts[1] ) {
			continue;
		}
		$val      = 'custom' === strtolower( $parts[0] ) ? 'custom' : (string) (float) $parts[0];
		$first    = null === $first ? $val : $first;
		$options .= '<option value="' . esc_attr( $val ) . '">' . esc_html( $parts[1] ) . '</option>';
	}
	$custom  = null === $first || 'custom' === $first;
	$pip     = $custom ? '' : $first;
	$balance = (float) eab_mod( 'calc_balance_value' );
	$risk    = (float) eab_mod( 'calc_risk_value' );
	$sl      = (float) eab_mod( 'calc_sl_value' );
	$step    = (float) eab_mod( 'calc_step_value' );
	$step    = $step > 0 ? $step : 0.01;
	$loss    = $balance * $risk / 100;
	$raw     = $sl > 0 && (float) $pip > 0 ? $loss / ( $sl * (float) $pip ) : 0;
	$size    = floor( $raw / $step + 1e-9 ) * $step;
	$lot     = trim( (string) eab_mod( 'calc_lot_unit' ) );
	$warn    = trim( (string) eab_mod( 'calc_lot_note' ) );

	return '<div class="calc" data-calc="lot" data-money-unit="' . esc_attr( $money ) . '" data-lot-unit="' . esc_attr( $lot ) . '">'
		. '<div class="calc__in"><h3><span class="tile tile--accent">' . eab_icon( 'calculator' ) . '</span>' . esc_html( eab_mod( 'calc_lot_title' ) ) . '</h3>' . $hint
		. eab_calc_field( 'lot-balance' . $sfx, 'balance', 'balance', 'min="0" step="any"' )
		. eab_calc_field( 'lot-risk' . $sfx, 'risk', 'risk', 'min="0" max="100" step="any"' )
		. eab_calc_field( 'lot-sl' . $sfx, 'sl', 'sl', 'min="0" step="any"' )
		. eab_calc_field( 'lot-step' . $sfx, 'step', 'step', 'min="0.01" step="0.01"' )
		. '<div class="field field--full"><label for="' . esc_attr( 'lot-preset' . $sfx ) . '">' . esc_html( eab_mod( 'calc_preset_label' ) ) . '</label>'
		. '<select id="' . esc_attr( 'lot-preset' . $sfx ) . '" name="preset">' . $options . '</select>'
		. ( '' !== trim( (string) eab_mod( 'calc_preset_help' ) ) ? '<small>' . esc_html( eab_mod( 'calc_preset_help' ) ) . '</small>' : '' ) . '</div>'
		. str_replace( '<div class="field">', '<div class="field field--full">', eab_calc_field( 'lot-pip' . $sfx, 'pip', 'pip', 'min="0" step="any"', $pip, ! $custom ) )
		. '</div><div class="calc__out" aria-live="polite">'
		. '<div class="out"><span>' . esc_html( eab_mod( 'calc_out_money' ) ) . '</span><b data-out="money">' . esc_html( eab_calc_num( $loss ) . ' ' . $money ) . '</b></div>'
		. '<div class="out"><span>' . esc_html( eab_mod( 'calc_out_lot' ) ) . '</span><b data-out="lot">' . esc_html( eab_calc_num( $size ) . ' ' . $lot ) . '</b></div>'
		. ( '' !== $warn ? '<p class="note note--warn">' . eab_icon( 'alert' ) . '<span>' . esc_html( $warn ) . '</span></p>' : '' )
		. '</div></div>';
}
add_shortcode( 'eabull_calc', 'eab_sc_calc' );
