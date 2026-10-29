<?php
/**
 * EA BULL · ส่วนประกอบกลางที่ทุกหน้าใช้ร่วมกัน (หน้าตาแบบ EA BULL)
 *
 * - ช่องทางติดต่อ: eab_has_line_url(), eab_contact_href(), eab_contact_button()
 * - ข้อความ: eab_text(), eab_rich_inline(), eab_rich_text(), eab_keep_words()
 * - หัวข้อเนื้อหาจาก Customizer: eab_page_sections(), eab_page_section_fields()
 * - รูป: eab_media_slot(), eab_media_picture(), eab_media_open(), eab_media_info()
 * - หัวบท: eab_chapter_head() · kicker + H2 + ปุ่มแคปซูลกระจก "ดูทั้งหมด" ด้านขวา (ไม่บังคับ)
 * - โมดูลเพิ่ม default / section ของ Customizer ผ่านฟิลเตอร์ 'eab_defaults' และ 'eab_customizer_sections'
 * - หน้าตา Glass Sky (docs/design.md) อยู่ใน assets/css/components.css
 *
 * รูปแบบข้อความใน textarea (eab_rich_text):
 * - เว้นบรรทัดว่าง = ขึ้นบล็อกใหม่ · "- " = รายการ · "1. " = ลำดับ · "### " = หัวข้อย่อย (h3)
 * - บล็อกที่ทุกบรรทัดมี " | " = ตาราง (บรรทัดแรกเป็นหัวตาราง) · [ข้อความ](/slug/) = ลิงก์ภายในเว็บ
 *
 * @package eabull
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ==============================================================
 * ข้อความกลางของส่วนประกอบ (ค่าเริ่มต้น + Customizer)
 * ============================================================== */

add_filter(
	'eab_defaults',
	function ( $d ) {
		return array_merge(
			$d,
			array(
				'ch_more_label'    => 'ดูทั้งหมด',
				'media_open_label' => 'ดูภาพขนาดใหญ่',
			)
		);
	}
);

add_filter(
	'eab_customizer_sections',
	function ( $sections ) {
		$sections['eab_components'] = array(
			'title'       => '33) ข้อความกลาง · หัวบทและช่องรูป',
			'description' => 'คำสั้น ๆ ที่ส่วนประกอบกลางใช้ซ้ำหลายหน้า',
			'fields'      => array(
				'ch_more_label'    => array( 'หัวบท · ข้อความปุ่มลิงก์ด้านขวา (เช่น ดูทั้งหมด)', 'text' ),
				'media_open_label' => array( 'ช่องรูป · ข้อความบนป้ายเปิดภาพขนาดเต็ม (เว้นว่าง = แสดงเฉพาะไอคอน)', 'text' ),
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
 * ปลายทางของปุ่มติดต่อ: LINE → หน้า /go/ (ถ้าเผยแพร่แล้ว) → '' (ไม่แสดงปุ่ม)
 * ไม่มีปุ่มใดชี้ไปที่ '#'
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
 * ปุ่มติดต่อหลัก · ไม่มีปลายทาง = ไม่พิมพ์ปุ่ม (แอดมินเห็นคำแนะนำให้ตั้งค่า)
 * ทุกปุ่มได้คลาส btn-contact เพิ่ม · ไอคอนหน้า = .btn-contact-ic · ลูกศรท้าย = .btn-contact-ar (วงกลมกระจกแบบ Glass Sky)
 * โครง HTML เดิมคงไว้ (ไอคอน · <span>ข้อความ</span> · ลูกศร) สไตล์ของแต่ละจุดจึงยังใช้ได้
 *
 * @param array $args {
 *     @type string $text     ข้อความปุ่มเมื่อเป็น LINE
 *     @type string $class    คลาสปุ่ม (ค่าเริ่มต้น 'btn btn-fire' = ปุ่มทอง)
 *     @type string $pos      ตำแหน่งสำหรับนับคลิก (data-line-pos)
 *     @type bool   $arrow    แสดงลูกศรท้ายปุ่ม
 *     @type bool   $icon     แสดงไอคอน LINE
 * }
 */
function eab_contact_button( $args = array() ) {
	$args   = wp_parse_args(
		$args,
		array(
			'text'  => eab_mod( 'footer_line_text' ),
			'class' => 'btn btn-fire',
			'pos'   => 'content',
			'arrow' => true,
			'icon'  => true,
		)
	);
	$target = eab_contact_target();

	if ( '' === $target['url'] ) {
		if ( current_user_can( 'edit_theme_options' ) ) {
			echo '<span class="admin-hint">ปุ่มติดต่อยังไม่แสดง: เพิ่มลิงก์ LINE ได้ที่ ปรับแต่ง → 1) ช่องทางติดต่อ</span>';
		}
		return;
	}

	$text  = $target['is_line'] ? $args['text'] : eab_mod( 'contact_fallback_text' );
	$class = trim( (string) $args['class'] );
	if ( false === strpos( ' ' . $class . ' ', ' btn-contact ' ) ) {
		$class .= ' btn-contact';
	}
	printf(
		'<a class="%1$s" href="%2$s"%3$s data-line-pos="%4$s">%5$s<span>%6$s</span>%7$s</a>',
		esc_attr( trim( $class ) ),
		esc_url( $target['url'] ),
		$target['is_line'] ? ' target="_blank" rel="noopener"' : '',
		esc_attr( $args['pos'] ),
		$args['icon'] ? eab_icon( $target['is_line'] ? 'line' : 'chat', 'icon btn-contact-ic' ) : '', // phpcs:ignore WordPress.Security.EscapeOutput
		esc_html( $text ),
		$args['arrow'] ? eab_icon( 'arrow', 'icon btn-contact-ar' ) : '' // phpcs:ignore WordPress.Security.EscapeOutput
	);
}

/* ==============================================================
 * ข้อความ
 * ============================================================== */

/**
 * กันการตัดบรรทัดกลางคำใน HTML ที่ escape แล้ว (แก้เฉพาะช่วงข้อความ ไม่แตะแท็ก)
 * - คำละตินที่มียัติภังค์ (เช่น Stop-Loss) ห่อด้วย span.nobr
 * - $thai = true: ใส่ word joiner ที่รอยต่อคำไทยที่เบราว์เซอร์มักตัดผิด (แก้รายการได้ด้วยฟิลเตอร์ eab_keep_words)
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
 * esc_html สำหรับหัวข้อ/ข้อความสั้น + กันตัดบรรทัดกลางคำ
 */
function eab_text( $text ) {
	return eab_keep_words( esc_html( (string) $text ) );
}

/**
 * ข้อความในบรรทัด: escape ทุกอย่าง ยกเว้นลิงก์ภายในรูปแบบ [ข้อความ](/slug/)
 * รับเฉพาะ path ที่ขึ้นต้นด้วย / ตัวเดียว (ไม่รับ // และโดเมนอื่น)
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
				// ปลายทางที่ยังเป็นฉบับร่าง (เพจกฎหมาย/บทความที่ยังไม่เผยแพร่) → ข้อความธรรมดา ไม่ลิงก์ไปหน้า 404
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
 * แปลงข้อความจาก textarea เป็น HTML ที่ escape แล้ว (ย่อหน้า รายการ หัวข้อย่อย ตาราง ลิงก์ภายใน)
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
			$html .= '<div class="table-wrap table-wrap--stack"><table class="data-table">';
			foreach ( $lines as $idx => $line ) {
				$cells = array_map( 'trim', explode( ' | ', $line ) );
				$html .= 0 === $idx ? '<thead><tr>' : ( 1 === $idx ? '<tbody><tr>' : '<tr>' );
				foreach ( $cells as $col => $cell ) {
					if ( 0 === $idx ) {
						$html .= '<th>' . esc_html( $cell ) . '</th>';
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
			$html .= '<ol class="rt-list rt-list--num">';
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

/**
 * พิมพ์หัวข้อเนื้อหาจาก Customizer ({prefix}_secN_title / {prefix}_secN_text)
 * ข้ามหัวข้อที่ว่างหรือยังเป็น placeholder ("ระบุ..." / "เช่น...")
 */
function eab_page_sections( $prefix, $max = 10, $wrap = true ) {
	$items = array();
	for ( $i = 1; $i <= $max; $i++ ) {
		$title = trim( (string) eab_mod( $prefix . '_sec' . $i . '_title' ) );
		$text  = trim( (string) eab_mod( $prefix . '_sec' . $i . '_text' ) );
		if ( '' === $title && '' === $text ) {
			continue;
		}
		if ( eab_is_placeholder( $title ) || eab_is_placeholder( $text ) ) {
			continue;
		}
		$items[] = array( $i, $title, $text );
	}
	if ( empty( $items ) ) {
		return;
	}
	if ( $wrap ) {
		echo '<section class="section doc-sections" id="' . esc_attr( $prefix . '-doc' ) . '"><div class="container container-narrow"><div class="entry-content guide-content doc-body">';
	}
	foreach ( $items as $item ) {
		echo '<article class="doc-section" id="' . esc_attr( $prefix . '-sec-' . $item[0] ) . '">';
		if ( '' !== $item[1] ) {
			echo '<h2>' . eab_text( $item[1] ) . '</h2>'; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside eab_text
		}
		echo eab_rich_text( $item[2] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside eab_rich_text
		echo '</article>';
	}
	if ( $wrap ) {
		echo '</div></div></section>';
	}
}

/**
 * ฟิลด์ Customizer ของหัวข้อเนื้อหา (ใช้คู่กับ eab_page_sections)
 */
function eab_page_section_fields( $prefix, $max = 10 ) {
	$fields = array();
	$rule   = 'รูปแบบ: เว้นบรรทัดว่าง = ย่อหน้าใหม่ · "- " = รายการ · "1. " = ลำดับ · "### " = หัวข้อย่อย · " | " = ตาราง · [ข้อความ](/slug/) = ลิงก์ภายในเว็บ · ห้ามใส่ตัวเลขผลเทรดสมมติหรือคำรับประกันกำไร';
	for ( $i = 1; $i <= $max; $i++ ) {
		$fields[ $prefix . '_sec' . $i . '_title' ] = array( 'หัวข้อที่ ' . $i, 'text' );
		$fields[ $prefix . '_sec' . $i . '_text' ]  = array( 'เนื้อหาหัวข้อที่ ' . $i, 'textarea', $rule );
	}
	return $fields;
}

/* ==============================================================
 * รูป
 * ============================================================== */

/**
 * ขนาดจริง + srcset ของรูปในคลังสื่อ (รูปนอกคลังสื่อใช้ขนาดที่ส่งมา)
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
	/* ไฟล์ในธีม (assets/...) อ่านขนาดจริงจากไฟล์ · ภาพหน้าจอแต่ละภาพสัดส่วนไม่เท่ากัน */
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
 * ห่อรูปด้วยลิงก์ "เปิดภาพขนาดเต็ม" (ภาพหน้าจอมีตัวหนังสือเล็ก ต้องกดดูเต็มได้เสมอ)
 * ใช้ lightbox ของธีมเมื่อ JS ทำงาน · ไม่มี JS = เปิดไฟล์ในแท็บใหม่
 */
function eab_media_open( $src, $html, $caption = '', $group = '' ) {
	$hint = trim( (string) eab_mod( 'media_open_label' ) );
	return sprintf(
		'<a class="media-open lightbox" href="%1$s" target="_blank" rel="noopener"%2$s%3$s>%4$s<span class="media-open-hint%5$s">%6$s%7$s</span></a>',
		esc_url( $src ),
		'' !== $caption ? ' data-caption="' . esc_attr( $caption ) . '"' : '',
		'' !== $group ? ' data-group="' . esc_attr( $group ) . '"' : '',
		$html,
		'' === $hint ? ' media-open-hint--icon' : '',
		eab_icon( 'external', 'icon icon-sm' ),
		'' !== $hint ? '<span>' . esc_html( $hint ) . '</span>' : ''
	);
}

/**
 * ช่องรูปมาตรฐาน (หน้าแรก คู่มือ หน้าทดสอบ)
 * อ่าน {key}_img, {key}_img_alt, {key}_img_note, {key}_img_caption, {key}_img_mobile
 * - มีรูป: รูปในกรอบ + คำบรรยาย "ภาพ NN · ..." + ลิงก์เปิดภาพเต็ม
 * - ไม่มีรูป: คนทั่วไปไม่เห็นอะไร · แอดมินเห็นกรอบเส้นประบอกว่าต้องใส่รูปอะไร
 *
 * @param string $key     prefix ของคีย์
 * @param array  $args    { width, height, label (เช่น 'ภาพ 01'), caption, eager, group, class }
 */
function eab_media_slot( $key, $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'width'   => 1280,
			'height'  => 800,
			'label'   => '',
			'caption' => '',
			'eager'   => false,
			'group'   => '',
			'class'   => '',
		)
	);
	$src     = eab_theme_asset_url( eab_mod( $key . '_img' ) ); // ค่า assets/... = ภาพที่มากับธีม
	$alt     = (string) eab_mod( $key . '_img_alt' );
	$note    = trim( (string) eab_mod( $key . '_img_note' ) );
	$caption = '' !== $args['caption'] ? $args['caption'] : trim( (string) eab_mod( $key . '_img_caption' ) );
	$label   = $args['label'];
	$figcap  = '';
	if ( '' !== $label || '' !== $caption ) {
		$figcap = '<figcaption class="media-cap">' . ( '' !== $label ? '<span class="media-cap-label">' . esc_html( $label ) . '</span>' : '' ) . esc_html( $caption ) . '</figcaption>';
	}
	$class = trim( 'media-frame reveal ' . $args['class'] );

	if ( '' !== $src ) {
		printf(
			'<figure class="%1$s">%2$s%3$s</figure>',
			esc_attr( $class ),
			eab_media_open( $src, eab_media_picture( $key, $src, '' !== $alt ? $alt : $caption, $args['width'], $args['height'], $args['eager'] ), $caption, $args['group'] ), // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside
			$figcap // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above
		);
		return true;
	}

	if ( current_user_can( 'edit_theme_options' ) && ( '' !== $note || '' !== $alt ) ) {
		printf(
			'<figure class="%1$s media-slot-empty" aria-hidden="true"><span class="media-slot-icon">%2$s</span><span class="media-slot-note"><strong>ช่องนี้ยังไม่มีรูป (แอดมินเท่านั้นที่เห็นกรอบนี้)</strong>%3$s</span>%4$s</figure>',
			esc_attr( $class ),
			eab_icon( 'image', 'icon' ), // phpcs:ignore WordPress.Security.EscapeOutput
			esc_html( '' !== $note ? $note : $alt ),
			$figcap // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above
		);
	}
	return false;
}

/**
 * ฟิลด์ Customizer ของช่องรูป (ใช้คู่กับ eab_media_slot)
 */
function eab_media_fields( $key, $label ) {
	return array(
		$key . '_img'         => array( $label . ' · รูป', 'image' ),
		$key . '_img_mobile'  => array( $label . ' · รูปสำหรับมือถือ (ไม่บังคับ)', 'image', 'ใส่เมื่อรูปหลักมีตัวหนังสือเล็กจนอ่านไม่ออกบนมือถือ' ),
		$key . '_img_alt'     => array( $label . ' · คำอธิบายรูป (alt)', 'text' ),
		$key . '_img_caption' => array( $label . ' · คำบรรยายใต้รูป', 'text', 'ถ้ารูปมีตัวเลข ให้บอกว่าเป็นภาพประกอบ ไม่ใช่ผลการเทรดจริง' ),
		$key . '_img_note'    => array( $label . ' · โน้ตสำหรับแอดมินเมื่อยังไม่มีรูป', 'text' ),
	);
}

/* ==============================================================
 * หัวบท (chapter head) · kicker (+ เลขบท) + H2 + คำอธิบาย + ปุ่ม "ดูทั้งหมด ↗" ด้านขวา (ไม่บังคับ)
 * ============================================================== */

/**
 * @param int          $num    เลขบท (0 = ไม่แสดงเลข ใช้ kicker ธรรมดา)
 * @param int          $total  จำนวนบททั้งหมด (0 = ไม่แสดง)
 * @param string       $kicker ป้ายเล็กภาษาอังกฤษ (ตัวพิมพ์ใหญ่ เว้นระยะ สีน้ำเงิน)
 * @param string       $title  H2 (ว่าง = ไม่พิมพ์ H2)
 * @param string       $sub    คำอธิบายใต้หัวข้อ
 * @param string       $align  'center' หรือ 'left' (มีปุ่มด้านขวา = ชิดซ้ายเสมอ)
 * @param array|string $more   ปุ่มลิงก์ด้านขวา: array( 'url' => ..., 'label' => ... ) หรือ URL อย่างเดียว
 *                             label ว่าง = ใช้ค่า ch_more_label · ไม่มี URL = ไม่แสดงปุ่ม
 */
function eab_chapter_head( $num, $total, $kicker, $title, $sub = '', $align = 'center', $more = array() ) {
	if ( is_string( $more ) ) {
		$more = array( 'url' => $more );
	}
	$more_url   = isset( $more['url'] ) ? trim( (string) $more['url'] ) : '';
	$more_label = isset( $more['label'] ) ? trim( (string) $more['label'] ) : '';
	if ( '' === $more_label ) {
		$more_label = trim( (string) eab_mod( 'ch_more_label' ) );
	}
	$more_href = ( '' !== $more_url && '#' !== $more_url && '' !== $more_label ) ? eab_link_url( $more_url ) : '';
	$classes   = 'ch-head ch-head--' . ( '' !== $more_href ? 'left ch-head--more' : $align );
	if ( ! eab_show_kickers() ) {
		$num    = 0;
		$kicker = '';
	}
	if ( '' === (string) $kicker && '' === (string) $title && ! $num ) {
		return;
	}
	?>
	<header class="<?php echo esc_attr( $classes ); ?> reveal">
		<?php if ( $num ) : ?>
			<span class="ch-index kicker"><span class="ch-num"><?php echo esc_html( sprintf( '%02d', (int) $num ) ); ?></span><?php if ( $total ) : ?><span class="ch-total"> / <?php echo esc_html( sprintf( '%02d', (int) $total ) ); ?></span><?php endif; ?><?php if ( $kicker ) : ?><span class="ch-kicker"><?php echo esc_html( $kicker ); ?></span><?php endif; ?></span>
		<?php elseif ( $kicker ) : ?>
			<span class="kicker"><?php echo esc_html( $kicker ); ?></span>
		<?php endif; ?>
		<?php if ( '' !== (string) $title ) : ?>
			<h2><?php echo eab_text( $title ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></h2>
		<?php endif; ?>
		<?php if ( $sub ) : ?>
			<p class="ch-sub"><?php echo eab_text( $sub ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></p>
		<?php endif; ?>
		<?php if ( '' !== $more_href ) : ?>
			<a class="ch-more" href="<?php echo esc_url( $more_href ); ?>"><span><?php echo esc_html( $more_label ); ?></span><span class="ch-more-ic" aria-hidden="true"><?php echo eab_icon( 'arrow', 'icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span></a>
		<?php endif; ?>
	</header>
	<?php
}
