<?php
/**
 * EA BULL · ส่วนประกอบกลางที่ทุกหน้าใช้ร่วมกัน (หน้าตา Bull Black ตาม dev/mockup)
 *
 * - ช่องทางติดต่อ: eab_has_line_url(), eab_contact_target(), eab_contact_href(), eab_contact_button() → a.btn.btn--line
 * - ข้อความ: eab_text(), eab_rich_inline(), eab_rich_text(), eab_keep_words()
 * - รูป: eab_media_slot(), eab_media_picture(), eab_media_open() (a.lightbox), eab_media_info(), eab_media_fields()
 * - หัวส่วน: eab_chapter_head() → .shead (สัญลักษณ์ 3 แท่ง + H2 + คำอธิบาย + ลิงก์ด้านขวา)
 * - โมดูลเพิ่มค่าเริ่มต้น / หมวด Customizer ผ่านฟิลเตอร์ 'eab_defaults' และ 'eab_customizer_sections'
 *
 * รูปแบบข้อความใน textarea (eab_rich_text):
 * - เว้นบรรทัดว่าง = ขึ้นบล็อกใหม่ · "- " = รายการ · "1. " = ลำดับ · "### " = หัวข้อย่อย (h3)
 * - ทุกบรรทัดของบล็อกคั่นช่องด้วย " | " → table.tbl (แถวแรกคือหัวคอลัมน์) · [ชื่อลิงก์](/slug/) → ลิงก์ไปหน้าในเว็บ
 *
 * @package eabull
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ==============================================================
 * ค่าเริ่มต้นและช่อง Customizer ของส่วนประกอบในไฟล์นี้
 * ============================================================== */

add_filter(
	'eab_defaults',
	function ( $d ) {
		return array_merge(
			$d,
			array(
				'ch_more_label'  => 'ดูทั้งหมด',
				'line_wait_text' => 'ลิงก์ LINE OA ของ EA BULL รอข้อมูลจากเจ้าของ',
			)
		);
	}
);

add_filter(
	'eab_customizer_sections',
	function ( $sections ) {
		$sections['eab_components'] = array(
			'title'       => '33) ข้อความกลาง · ปุ่มและหัวส่วน',
			'description' => 'ข้อความสั้นของปุ่มและหัวส่วนที่หลายหน้าเรียกใช้',
			'fields'      => array(
				'ch_more_label'  => array( 'ลิงก์ท้ายหัวส่วน (ชิดขวา)', 'text' ),
				'line_wait_text' => array( 'ปุ่ม LINE · ข้อความแจ้งเมื่อยังไม่มีลิงก์ LINE และยังไม่เผยแพร่หน้า /go/', 'text' ),
			),
		);
		return $sections;
	}
);

/* ==============================================================
 * ช่องทางติดต่อ
 * ============================================================== */

/**
 * มีลิงก์ LINE จริงหรือไม่ (ค่าว่าง หรือ '#' = ยังไม่ได้ตั้ง)
 */
function eab_has_line_url() {
	$url = trim( (string) eab_mod( 'line_url' ) );
	return '' !== $url && '#' !== $url;
}

/**
 * ลิงก์ของปุ่มติดต่อ: ใช้ LINE OA ก่อน · ไม่มีก็ใช้หน้า /go/ ที่เผยแพร่แล้ว · ไม่มีทั้งสองอย่าง = ''
 *
 * @return array { url, is_line }
 */
function eab_contact_target() {
	if ( eab_has_line_url() ) {
		return array(
			'url'     => trim( (string) eab_mod( 'line_url' ) ),
			'is_line' => true,
		);
	}
	$go = function_exists( 'eab_published_page_url' ) ? eab_published_page_url( 'go' ) : '';
	if ( $go && ! is_page( 'go' ) ) {
		return array(
			'url'     => $go,
			'is_line' => false,
		);
	}
	return array(
		'url'     => '',
		'is_line' => false,
	);
}

function eab_contact_href() {
	$target = eab_contact_target();
	return $target['url'];
}

/**
 * ปุ่ม LINE ทุกจุดของเว็บ: a.btn.btn--line (สีเขียว LINE) · ไอคอนแชตหน้าข้อความ
 * ยังไม่มีลิงก์ LINE OA = ไปหน้า /go/ · ยังไม่มีทั้งคู่ = ปุ่มเดิม แต่กดแล้วขึ้นข้อความแจ้ง (data-wait · เจ้าของสั่งให้เตรียมปุ่มไว้)
 *
 * @param array $args {
 *     @type string $text  ข้อความปุ่ม
 *     @type string $class คลาสปุ่ม (btn และ btn--line ถูกเติมให้เสมอ)
 *     @type string $pos   ตำแหน่งสำหรับนับคลิก (data-line-pos)
 *     @type string $pkg   ชื่อแพ็กเกจ (data-line-pkg)
 *     @type bool   $icon  แสดงไอคอนแชต
 *     @type bool   $arrow แสดงลูกศรท้ายปุ่ม
 * }
 */
function eab_contact_button( $args = array() ) {
	$args   = wp_parse_args(
		$args,
		array(
			'text'  => eab_mod( 'footer_line_text' ),
			'class' => 'btn btn--line',
			'pos'   => 'content',
			'pkg'   => '',
			'icon'  => true,
			'arrow' => false,
		)
	);
	$target = eab_contact_target();

	/* คลาสของธีมต้นทาง → คลาสของ Bull Black */
	$legacy = array(
		'btn-fire'    => '',
		'btn-dark'    => '',
		'btn-ghost'   => '',
		'btn-contact' => '',
		'btn-lg'      => 'btn--lg',
		'btn-block'   => 'btn--block',
	);
	$class  = array();
	foreach ( preg_split( '/\s+/', trim( (string) $args['class'] ) ) as $c ) {
		$c = isset( $legacy[ $c ] ) ? $legacy[ $c ] : $c;
		if ( '' !== $c ) {
			$class[] = $c;
		}
	}
	$class = array_unique( array_merge( array( 'btn', 'btn--line' ), $class ) );

	$text = trim( (string) $args['text'] );
	if ( '' === $text ) {
		$text = (string) eab_mod( 'contact_fallback_text' );
	}
	$attrs = ' data-line-pos="' . esc_attr( $args['pos'] ) . '"';
	if ( '' !== trim( (string) $args['pkg'] ) ) {
		$attrs .= ' data-line-pkg="' . esc_attr( $args['pkg'] ) . '"';
	}
	if ( '' === $target['url'] ) {
		$href   = '#';
		$attrs .= ' data-wait="' . esc_attr( eab_mod( 'line_wait_text' ) ) . '"';
	} else {
		$href   = $target['url'];
		$attrs .= $target['is_line'] ? ' target="_blank" rel="noopener"' : '';
	}
	printf(
		'<a class="%1$s" href="%2$s"%3$s>%4$s%5$s%6$s</a>',
		esc_attr( implode( ' ', $class ) ),
		esc_url( $href ),
		$attrs, // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above
		$args['icon'] ? eab_icon( 'chat' ) : '', // phpcs:ignore WordPress.Security.EscapeOutput
		esc_html( $text ),
		$args['arrow'] ? ' ' . eab_icon( 'arrow-right' ) : '' // phpcs:ignore WordPress.Security.EscapeOutput
	);
}

/* ==============================================================
 * ข้อความ
 * ============================================================== */

/**
 * ช่วยไม่ให้เบราว์เซอร์ตัดคำผิดที่ (ทำงานกับ HTML ที่ escape แล้ว และข้ามส่วนที่เป็นแท็ก)
 * - คำอังกฤษที่มีขีดกลาง อย่าง Stop-Loss → span.nobr
 * - $thai = true: เติม U+2060 ระหว่างพยางค์ไทยในรายการของฟิลเตอร์ eab_keep_words
 */
function eab_keep_words( $html, $thai = true ) {
	$html = (string) $html;
	if ( '' === $html ) {
		return $html;
	}
	$from = array();
	$to   = array();
	if ( $thai ) {
		foreach ( (array) apply_filters( 'eab_keep_words', array( 'ทีม|งาน', 'ต่าง|จาก' ) ) as $word ) {
			$word = (string) $word;
			if ( false === strpos( $word, '|' ) ) {
				continue;
			}
			$from[] = str_replace( '|', '', $word );
			$to[]   = str_replace( '|', "\u{2060}", $word );
		}
	}
	$parts = preg_split( '/(<[^>]*>)/', $html, -1, PREG_SPLIT_DELIM_CAPTURE );
	if ( false === $parts ) {
		return $html;
	}
	foreach ( $parts as $idx => $part ) {
		if ( '' === $part || '<' === $part[0] ) {
			continue;
		}
		if ( $from ) {
			$part = str_replace( $from, $to, $part );
		}
		$wrapped       = preg_replace( '/(?<![A-Za-z0-9&#-])[A-Za-z0-9]+(?:-[A-Za-z0-9]+)+(?![A-Za-z0-9-])/', '<span class="nobr">$0</span>', $part );
		$parts[ $idx ] = null === $wrapped ? $part : $wrapped;
	}
	return implode( '', $parts );
}

/**
 * ข้อความสั้น (หัวข้อ ป้าย) → esc_html แล้วผ่าน eab_keep_words
 */
function eab_text( $text ) {
	return eab_keep_words( esc_html( (string) $text ) );
}

/**
 * ข้อความบรรทัดเดียวจาก setting · escape ทั้งหมด เหลือไว้เฉพาะ [ชื่อลิงก์](/slug/)
 * ปลายทางต้องเป็น path ในเว็บนี้ (ขึ้นต้น / ตัวเดียว) จึงลิงก์ออกนอกเว็บไม่ได้
 */
function eab_rich_inline( $text ) {
	$parts = preg_split( '#\[([^\[\]\n]+)\]\((/(?!/)[^\s()<>"\'\\\\]*)\)#u', (string) $text, -1, PREG_SPLIT_DELIM_CAPTURE );
	if ( false === $parts ) {
		return esc_html( $text );
	}
	$html  = '';
	$label = '';
	foreach ( $parts as $idx => $part ) {
		switch ( $idx % 3 ) {
			case 0:
				$html .= esc_html( $part );
				break;
			case 1:
				$label = $part;
				break;
			default:
				// หน้าปลายทางยังไม่เผยแพร่ (ร่าง) = แสดงแค่ชื่อ ไม่สร้างลิงก์ที่เปิดแล้วเจอ 404
				$slug = (string) strtok( trim( $part, '/' ), '/?#' );
				if ( '' !== $slug && function_exists( 'eab_unreachable_slugs' ) && in_array( $slug, eab_unreachable_slugs(), true ) ) {
					$html .= esc_html( $label );
				} else {
					$html .= '<a href="' . esc_url( home_url( $part ) ) . '">' . esc_html( $label ) . '</a>';
				}
		}
	}
	return eab_keep_words( $html, false );
}

/**
 * textarea → HTML ปลอดภัย: ย่อหน้า · รายการ · h3 · ตาราง .tbl · ลิงก์ในเว็บ
 */
function eab_rich_text( $text ) {
	$text   = str_replace( array( "\r\n", "\r" ), "\n", (string) $text );
	$blocks = preg_split( '/\n\s*\n/', trim( $text ) );
	$html   = '';

	foreach ( $blocks as $block ) {
		$lines = array_values( array_filter( array_map( 'trim', explode( "\n", $block ) ), 'strlen' ) );
		if ( empty( $lines ) ) {
			continue;
		}

		$is_table = count( $lines ) > 1;
		foreach ( $lines as $line ) {
			if ( false === strpos( $line, ' | ' ) ) {
				$is_table = false;
				break;
			}
		}

		if ( $is_table ) {
			$heads = array_map( 'trim', explode( ' | ', $lines[0] ) );
			$html .= '<div class="tbl-wrap"><table class="tbl tbl--stack">';
			foreach ( $lines as $idx => $line ) {
				$cells = array_map( 'trim', explode( ' | ', $line ) );
				$html .= 0 === $idx ? '<thead><tr>' : ( 1 === $idx ? '<tbody><tr>' : '<tr>' );
				foreach ( $cells as $col => $cell ) {
					if ( 0 === $idx ) {
						$html .= '<th scope="col">' . esc_html( $cell ) . '</th>';
						continue;
					}
					if ( 0 === $col ) {
						$html .= '<th scope="row">' . eab_rich_inline( $cell ) . '</th>';
						continue;
					}
					$label = isset( $heads[ $col ] ) ? $heads[ $col ] : '';
					$html .= '<td data-label="' . esc_attr( $label ) . '">' . eab_rich_inline( $cell ) . '</td>';
				}
				$html .= '</tr>' . ( 0 === $idx ? '</thead>' : '' );
			}
			$html .= '</tbody></table></div>';
			continue;
		}

		$first = $lines[0];

		if ( 0 === strpos( $first, '### ' ) ) {
			$html .= '<h3 class="rt-h3">' . eab_text( substr( $first, 4 ) ) . '</h3>';
			$rest  = array_slice( $lines, 1 );
			if ( ! empty( $rest ) ) {
				$html .= '<p>' . implode( '<br>', array_map( 'eab_rich_inline', $rest ) ) . '</p>';
			}
			continue;
		}

		if ( 0 === strpos( $first, '- ' ) ) {
			$html .= '<ul class="rt-list">';
			foreach ( $lines as $line ) {
				$html .= '<li>' . eab_rich_inline( preg_replace( '/^-\s+/', '', $line ) ) . '</li>';
			}
			$html .= '</ul>';
			continue;
		}

		if ( preg_match( '/^\d+\.\s/', $first ) ) {
			$html .= '<ol class="rt-list">';
			foreach ( $lines as $line ) {
				$html .= '<li>' . eab_rich_inline( preg_replace( '/^\d+\.\s+/', '', $line ) ) . '</li>';
			}
			$html .= '</ol>';
			continue;
		}

		$html .= '<p>' . implode( '<br>', array_map( 'eab_rich_inline', $lines ) ) . '</p>';
	}

	return $html;
}

/* ==============================================================
 * รูป
 * ============================================================== */

/**
 * กว้าง สูง และ srcset ของรูป · ไฟล์ในธีมอ่านขนาดจากไฟล์ · รูปในคลังสื่ออ่านจาก metadata · อื่น ๆ ใช้ค่าที่ส่งมา
 */
function eab_media_info( $url, $width, $height ) {
	static $cache = array();
	$url = (string) $url;
	if ( isset( $cache[ $url ] ) ) {
		return $cache[ $url ];
	}
	$info = array(
		'width'  => (int) $width,
		'height' => (int) $height,
		'srcset' => '',
	);
	/* ไฟล์ในธีม (assets/...) อ่านขนาดจริงจากไฟล์ */
	$theme = get_template_directory_uri() . '/';
	if ( 0 === strpos( $url, $theme . 'assets/' ) ) {
		$file = get_template_directory() . '/' . substr( $url, strlen( $theme ) );
		$size = is_readable( $file ) ? @getimagesize( $file ) : false; // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		if ( $size ) {
			$info['width']  = (int) $size[0];
			$info['height'] = (int) $size[1];
		}
		$cache[ $url ] = $info;
		return $info;
	}
	$id = function_exists( 'attachment_url_to_postid' ) ? (int) attachment_url_to_postid( $url ) : 0;
	if ( $id ) {
		$meta = wp_get_attachment_metadata( $id );
		if ( is_array( $meta ) && ! empty( $meta['width'] ) && ! empty( $meta['height'] ) ) {
			$info['width']  = (int) $meta['width'];
			$info['height'] = (int) $meta['height'];
			$srcset         = wp_get_attachment_image_srcset( $id, 'full', $meta );
			$info['srcset'] = is_string( $srcset ) ? $srcset : '';
		}
	}
	$cache[ $url ] = $info;
	return $info;
}

/**
 * <img> (หรือ <picture> ถ้ามีภาพมือถือ {key}_img_mobile)
 */
function eab_media_picture( $key, $src, $alt, $width, $height, $eager = false ) {
	$main   = eab_media_info( $src, $width, $height );
	$srcset = '' !== $main['srcset'] ? sprintf( ' srcset="%s" sizes="(max-width: 760px) 100vw, 1120px"', esc_attr( $main['srcset'] ) ) : '';
	$img    = sprintf(
		'<img src="%s"%s alt="%s" loading="%s" decoding="async" width="%s" height="%s">',
		esc_url( $src ),
		$srcset,
		esc_attr( $alt ),
		$eager ? 'eager' : 'lazy',
		esc_attr( (string) $main['width'] ),
		esc_attr( (string) $main['height'] )
	);
	$mobile = $key ? eab_theme_asset_url( eab_mod( $key . '_img_mobile' ) ) : '';
	if ( '' === $mobile ) {
		return $img;
	}
	$small = eab_media_info( $mobile, 1080, 1350 );
	return sprintf(
		'<picture><source media="(max-width: 640px)" srcset="%s" width="%s" height="%s">%s</picture>',
		esc_url( $mobile ),
		esc_attr( (string) $small['width'] ),
		esc_attr( (string) $small['height'] ),
		$img
	);
}

/**
 * ห่อรูปด้วยลิงก์เปิดภาพขนาดเต็ม (main.js: a.lightbox[data-group][data-caption]) · ไม่มี JS = เปิดไฟล์ในแท็บใหม่
 */
function eab_media_open( $src, $html, $caption = '', $group = '' ) {
	return sprintf(
		'<a class="lightbox" href="%1$s" target="_blank" rel="noopener"%2$s%3$s>%4$s</a>',
		esc_url( $src ),
		'' !== $caption ? ' data-caption="' . esc_attr( $caption ) . '"' : '',
		'' !== $group ? ' data-group="' . esc_attr( $group ) . '"' : '',
		$html
	);
}

/**
 * ช่องรูปมาตรฐาน · อ่าน {key}_img, {key}_img_alt, {key}_img_note, {key}_img_caption, {key}_img_mobile
 * - มีรูป: figure.shot-img > a.lightbox > img + figcaption.cap
 * - ไม่มีรูป: $args['placeholder'] มีข้อความ = กรอบ .shot "ภาพหน้าจอ รอจากเจ้าของ" ให้ทุกคนเห็น (แบบต้นแบบ)
 *   ไม่มี = เห็นเฉพาะแอดมิน (กรอบเดียวกันพร้อมโน้ต)
 *
 * @param string $key  prefix ของคีย์
 * @param array  $args { width, height, label, caption, eager, group, class, placeholder, icon }
 */
function eab_media_slot( $key, $args = array() ) {
	$args    = wp_parse_args(
		$args,
		array(
			'width'       => 1280,
			'height'      => 800,
			'label'       => '',
			'caption'     => '',
			'eager'       => false,
			'group'       => '',
			'class'       => '',
			'placeholder' => '',
			'icon'        => 'file',
		)
	);
	$src     = eab_theme_asset_url( eab_mod( $key . '_img' ) ); // ค่า assets/... = ภาพที่มากับธีม
	$alt     = (string) eab_mod( $key . '_img_alt' );
	$note    = trim( (string) eab_mod( $key . '_img_note' ) );
	$caption = '' !== $args['caption'] ? $args['caption'] : trim( (string) eab_mod( $key . '_img_caption' ) );
	$label   = trim( (string) $args['label'] );
	$cap     = '';
	if ( '' !== $label || '' !== $caption ) {
		$cap = trim( $label . ( '' !== $label && '' !== $caption ? ' · ' : '' ) . $caption );
	}

	if ( '' !== $src ) {
		printf(
			'<figure class="%1$s">%2$s%3$s</figure>',
			esc_attr( trim( 'shot-img ' . $args['class'] ) ),
			eab_media_open( $src, eab_media_picture( $key, $src, '' !== $alt ? $alt : $caption, $args['width'], $args['height'], $args['eager'] ), $caption, $args['group'] ), // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside
			'' !== $cap ? '<figcaption class="cap">' . esc_html( $cap ) . '</figcaption>' : ''
		);
		return true;
	}

	$name = '' !== trim( (string) $args['placeholder'] ) ? (string) $args['placeholder'] : '';
	if ( '' === $name && current_user_can( 'edit_theme_options' ) && ( '' !== $note || '' !== $alt ) ) {
		$name = '' !== $note ? $note : $alt;
	}
	if ( '' === $name ) {
		return false;
	}
	$wait = function_exists( 'eab_pages_shot' ) ? (string) eab_mod( 'pg_shot_wait' ) : '';
	printf(
		'<div class="%1$s">%2$s<span><b>%3$s</b>%4$s</span></div>%5$s',
		esc_attr( trim( 'shot ' . $args['class'] ) ),
		eab_icon( $args['icon'] ), // phpcs:ignore WordPress.Security.EscapeOutput
		esc_html( $name ),
		esc_html( $wait ),
		'' !== $cap ? '<p class="cap">' . esc_html( $cap ) . '</p>' : '' // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside
	);
	return false;
}

/**
 * ฟิลด์ Customizer ของช่องรูป (ใช้คู่กับ eab_media_slot)
 */
function eab_media_fields( $key, $label ) {
	return array(
		$key . '_img'         => array( $label . ' · รูป', 'image' ),
		$key . '_img_mobile'  => array( $label . ' · รูปสำหรับมือถือ (ไม่บังคับ)', 'image', 'ใช้เมื่อจอแคบอ่านตัวหนังสือในรูปหลักไม่ได้' ),
		$key . '_img_alt'     => array( $label . ' · คำอธิบายรูป (alt)', 'text' ),
		$key . '_img_caption' => array( $label . ' · คำบรรยายใต้รูป', 'text', 'รูปที่มีตัวเลขต้องเขียนกำกับว่าเป็นตัวอย่าง หรือบอกเงื่อนไขของผลทดสอบ' ),
		$key . '_img_note'    => array( $label . ' · ข้อความในกรอบรอรูป (แอดมินเห็น)', 'text' ),
	);
}

/* ==============================================================
 * หัวส่วน (.shead) · สัญลักษณ์ 3 แท่ง + H2 + คำอธิบาย + ลิงก์ด้านขวา (ไม่มีคำเล็กเหนือหัวข้อตาม docs/design.md)
 * ============================================================== */

/**
 * @param int          $num    เลขบท (ไม่ใช้แล้ว · คงไว้ให้ตัวเรียกเดิม)
 * @param int          $total  จำนวนบท (ไม่ใช้แล้ว)
 * @param string       $kicker ป้ายเล็ก (ไม่แสดง · ดีไซน์ไม่มี kicker)
 * @param string       $title  H2 (ว่าง = ไม่พิมพ์)
 * @param string       $sub    คำอธิบายใต้หัวข้อ
 * @param string       $align  'center' หรือ 'left' (มีลิงก์ด้านขวา = ชิดซ้ายเสมอ)
 * @param array|string $more   ลิงก์ด้านขวา: array( 'url' => ..., 'label' => ... ) หรือ URL อย่างเดียว · label ว่าง = ch_more_label
 */
function eab_chapter_head( $num, $total, $kicker, $title, $sub = '', $align = 'center', $more = array() ) {
	if ( '' === trim( (string) $title ) ) {
		return;
	}
	if ( is_string( $more ) ) {
		$more = array( 'url' => $more );
	}
	$more_url   = isset( $more['url'] ) ? trim( (string) $more['url'] ) : '';
	$more_label = isset( $more['label'] ) ? trim( (string) $more['label'] ) : '';
	if ( '' === $more_label ) {
		$more_label = trim( (string) eab_mod( 'ch_more_label' ) );
	}
	$more_href = ( '' !== $more_url && '#' !== $more_url && '' !== $more_label ) ? eab_link_url( $more_url ) : '';
	$class     = 'shead' . ( 'center' === $align && '' === $more_href ? ' shead--center' : '' );
	?>
	<div class="<?php echo esc_attr( $class ); ?>">
		<div class="shead__text">
			<h2><span class="bars" aria-hidden="true"><i></i><i></i><i></i></span><?php echo eab_text( $title ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></h2>
			<?php if ( '' !== trim( (string) $sub ) ) : ?>
				<p><?php echo eab_text( $sub ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></p>
			<?php endif; ?>
		</div>
		<?php if ( '' !== $more_href ) : ?>
			<a class="tlink" href="<?php echo esc_url( $more_href ); ?>"><?php echo esc_html( $more_label ); ?> <?php echo eab_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
		<?php endif; ?>
	</div>
	<?php
}
