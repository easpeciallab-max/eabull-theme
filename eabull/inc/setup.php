<?php
/**
 * EA BULL · โครงสร้างเพจ + ตัวช่วยตั้งค่าเว็บ (รูปแบบ → EA BULL Setup)
 *
 * - eab_site_pages()     : รายการเพจทั้งหมดของเว็บ (slug → template, เนื้อหาเริ่มต้น, กลุ่มเมนู)
 * - eab_seed_articles()  : บทความ SEO เริ่มต้น (นำเข้าเป็นฉบับร่าง)
 * - หน้า admin             : สร้างเพจที่ยังไม่มี, ตั้งหน้าแรก/หน้าบทความ, สร้างเมนู, นำเข้าบทความ
 *
 * เนื้อหาเริ่มต้นอยู่ใน inc/content/pages/*.html และ inc/content/articles/*.html
 * บรรทัดแรกของไฟล์เป็น <!--meta {...json...} --> (title, seo_title, description, keyword, excerpt, category, kicker)
 *
 * @package eabull
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * รายการเพจของเว็บ (ลำดับ = ลำดับที่แสดงในหน้า Setup)
 * group: test | guide | pricing | doc | hub
 * status: publish | draft (เพจกฎหมายสร้างเป็นร่างให้เจ้าของตรวจก่อนเผยแพร่)
 */
function eab_site_pages() {
	return array(
		'home'             => array( 'title' => 'หน้าแรก', 'template' => '', 'content' => '', 'group' => 'hub', 'front' => true, 'seo' => array( 'seo_title' => 'EA BULL · ระบบเทรดอัตโนมัติ EA บน MetaTrader 5', 'description' => 'EA BULL ระบบเทรดอัตโนมัติสำหรับ MetaTrader 5 เลือก EA ตามเลขรุ่น ติดตั้งตามคู่มือ รันบน VPS และติดตามผลจากมือถือ ปรึกษาทีมงานทาง LINE', 'keyword' => 'EA BULL EA MT5' ) ),
		'backtest'         => array( 'title' => 'ทดสอบ EA ย้อนหลังด้วย Strategy Tester ของ MT5', 'template' => 'template-backtest.php', 'content' => 'pages/backtest', 'group' => 'test', 'menu' => 'Backtest' ),
		'forward-test'     => array( 'title' => 'Forward Test: ลอง EA กับตลาดจริงก่อนเพิ่มเงิน', 'template' => 'template-forward.php', 'content' => 'pages/forward-test', 'group' => 'test', 'menu' => 'Forward Test' ),
		'how-to-install'   => array( 'title' => 'วิธีติดตั้ง EA ใน MT5', 'template' => 'template-install.php', 'content' => 'pages/how-to-install', 'group' => 'guide', 'menu' => 'วิธีติดตั้ง EA บน MT5' ),
		'open-mt5-account' => array( 'title' => 'เปิดบัญชี MT5', 'template' => 'template-guide.php', 'content' => 'pages/open-mt5-account', 'group' => 'guide', 'menu' => 'เปิดบัญชีเทรด MT5' ),
		'deposit'          => array( 'title' => 'ฝากเงินเข้าบัญชี Zaurix', 'template' => 'template-guide.php', 'content' => 'pages/deposit', 'group' => 'guide', 'menu' => 'ฝากเงิน Zaurix' ),
		'mt5-login'        => array( 'title' => 'ติดตั้งและล็อกอิน MT5', 'template' => 'template-guide.php', 'content' => 'pages/mt5-login', 'group' => 'guide', 'menu' => 'ติดตั้งและล็อกอิน MT5' ),
		'vps-windows'      => array( 'title' => 'คู่มือ VPS บน Windows', 'template' => 'template-guide.php', 'content' => 'pages/vps-windows', 'group' => 'guide', 'menu' => 'VPS บน Windows' ),
		'vps-android'      => array( 'title' => 'คู่มือ VPS บน Android', 'template' => 'template-guide.php', 'content' => 'pages/vps-android', 'group' => 'guide', 'menu' => 'VPS บน Android' ),
		'vps-ios'          => array( 'title' => 'คู่มือ VPS บน iPhone', 'template' => 'template-guide.php', 'content' => 'pages/vps-ios', 'group' => 'guide', 'menu' => 'VPS บน iPhone / iPad' ),
		'tools'            => array( 'title' => 'เครื่องมือคำนวณ', 'template' => 'template-guide.php', 'content' => 'pages/tools', 'group' => 'guide', 'menu' => 'เครื่องคำนวณ Lot / Drawdown' ),
		'pricing'          => array( 'title' => 'แพ็กเกจ EA BULL', 'template' => 'template-pricing.php', 'content' => 'pages/pricing', 'group' => 'pricing' ),
		/* สินค้าตัวอย่าง 3 รุ่น (slug ชั่วคราว · ชื่อ เลขรุ่น และ slug จริงตั้งใน Customizer หมวด 3.x · inc/modules/products.php) */
		'product-1'        => array( 'title' => 'สินค้าตัวอย่าง 1', 'template' => 'template-product.php', 'content' => 'pages/product-1', 'group' => 'product' ),
		'product-2'        => array( 'title' => 'สินค้าตัวอย่าง 2', 'template' => 'template-product.php', 'content' => 'pages/product-2', 'group' => 'product' ),
		'product-3'        => array( 'title' => 'สินค้าตัวอย่าง 3', 'template' => 'template-product.php', 'content' => 'pages/product-3', 'group' => 'product' ),
		'risk-disclosure'  => array( 'title' => 'ความเสี่ยงของการใช้ EA เทรดบน MT5', 'template' => 'template-risk.php', 'content' => 'pages/risk-disclosure', 'group' => 'doc' ),
		'about'            => array( 'title' => 'เกี่ยวกับ EA BULL', 'template' => 'template-about.php', 'content' => 'pages/about', 'group' => 'doc' ),
		'privacy-policy'   => array( 'title' => 'นโยบายความเป็นส่วนตัว', 'template' => '', 'content' => 'pages/privacy-policy', 'group' => 'doc', 'status' => 'draft' ),
		'terms-of-use'     => array( 'title' => 'ข้อกำหนดการใช้บริการ', 'template' => '', 'content' => 'pages/terms-of-use', 'group' => 'doc', 'status' => 'draft' ),
		'data-deletion'    => array( 'title' => 'คำขอลบข้อมูลส่วนบุคคล', 'template' => '', 'content' => 'pages/data-deletion', 'group' => 'doc', 'status' => 'draft' ),
		'go'               => array( 'title' => 'ติดต่อ EA BULL', 'template' => 'template-go.php', 'content' => '', 'group' => 'hub', 'seo' => array( 'seo_title' => 'ติดต่อ EA BULL · LINE และลิงก์รวมสำหรับเริ่มใช้งาน', 'description' => 'ทัก LINE ทีมงาน EA BULL หรือเข้ากลุ่ม OpenChat แล้วเดินตาม 6 ขั้นตอนเริ่มใช้งาน: เปิดบัญชี ติดตั้ง MT5 ฝากเงิน รับไฟล์ EA ติดตั้ง และรันบน VPS', 'keyword' => 'ติดต่อ EA BULL' ) ),
		'articles'         => array( 'title' => 'บทความ EA BULL', 'template' => '', 'content' => '', 'group' => 'hub', 'posts' => true, 'seo' => array( 'seo_title' => 'บทความ EA MT5: พื้นฐาน วางแผน ติดตามผล ความเสี่ยง · EA BULL', 'description' => 'บทความภาษาไทยสำหรับคนใช้ EA บน MT5: พื้นฐานการติดตั้งและแก้ปัญหา เขียนแผนการเทรด แบ่งทุน ติดตามพอร์ตบนมือถือ อ่าน Log และคุมความเสี่ยงอย่างเป็นระบบ', 'keyword' => 'บทความ EA MT5' ) ),
	);
}

/**
 * รายการบทความเริ่มต้น: slug => แบนเนอร์สำรอง
 * (ใช้เมื่อยังไม่มีรูปปกเฉพาะบทความที่ assets/img/covers/<slug>.webp · สร้างด้วย dev/make-covers.php)
 * ฟังก์ชันนี้ไม่อ่านไฟล์ จึงเรียกจากหน้าเว็บได้ทุกคำขอ
 */
function eab_seed_article_covers() {
	return eab_seed_article_slugs();
}

function eab_seed_article_covers_legacy() {
	/* บทความของ EA BULL เป็นงานเฟส 4 (docs/brief.md) · ยังไม่มีบทความเริ่มต้นและปกที่ต้องผูก */
	return array();
}

/**
 * slug ของบทความเริ่มต้นทั้งหมด = ไฟล์ใน inc/content/articles/ · บทความที่ไม่มีแบนเนอร์สำรองในรายการเดิมใช้แบนเนอร์กลาง
 * (ปกจริงของแต่ละบทอยู่ที่ covers/<slug>.webp)
 */
function eab_seed_article_slugs() {
	static $map = null;
	if ( null !== $map ) {
		return $map;
	}
	$map = eab_seed_article_covers_legacy();
	foreach ( (array) glob( get_template_directory() . '/inc/content/articles/*.html' ) as $file ) {
		$slug = basename( $file, '.html' );
		if ( ! isset( $map[ $slug ] ) ) {
			$map[ $slug ] = 'eabull-plan-sky-blue.webp';
		}
	}
	return $map;
}

/**
 * บทความเริ่มต้น (slug => ไฟล์ + รูปหน้าปก) · อ่านไฟล์เนื้อหา ใช้ในหน้า Setup เท่านั้น
 */
function eab_seed_articles() {
	static $list = null;
	if ( null !== $list ) {
		return $list;
	}
	$img  = get_template_directory() . '/assets/img/';
	$list = array();
	foreach ( eab_seed_article_covers() as $slug => $banner ) {
		$meta = eab_seed_meta( 'articles/' . $slug );
		if ( null === $meta ) {
			continue; // ยังไม่มีไฟล์เนื้อหา
		}
		/* บทความที่เขียนใหม่ใช้ปกชื่อใหม่ covers/<slug>-r<N>.webp (Cloudflare และ Media Library ผูกรูปกับชื่อไฟล์)
		 * เลือก N สูงสุดที่มีไฟล์และไม่เกิน rev ของเนื้อหา · เนื้อหาขึ้นรุ่นโดยปกเดิมยังใช้ได้จึงไม่ต้องทำปกใหม่ทุกรุ่น */
		$rev           = ! empty( $meta['rev'] ) ? (int) $meta['rev'] : 1;
		$own           = 'covers/' . $slug . '.webp';
		for ( $r = $rev; $r >= 2; $r-- ) {
			if ( file_exists( $img . 'covers/' . $slug . '-r' . $r . '.webp' ) ) {
				$own = 'covers/' . $slug . '-r' . $r . '.webp';
				break;
			}
		}
		$list[ $slug ] = array(
			'title'   => ! empty( $meta['title'] ) ? $meta['title'] : $slug,
			'content' => 'articles/' . $slug,
			'cover'   => file_exists( $img . $own ) ? $own : 'banners/' . $banner,
			'meta'    => $meta,
		);
	}
	return $list;
}

/**
 * อ่านไฟล์เนื้อหาเริ่มต้น → array( 'meta' => array, 'body' => string ) หรือ null ถ้าไม่มีไฟล์
 */
function eab_seed_file( $rel ) {
	static $cache = array();
	if ( isset( $cache[ $rel ] ) ) {
		return $cache[ $rel ];
	}
	$rel  = preg_replace( '#[^a-z0-9/_\-]#', '', strtolower( (string) $rel ) );
	$path = get_template_directory() . '/inc/content/' . $rel . '.html';
	if ( '' === $rel || ! file_exists( $path ) ) {
		$cache[ $rel ] = null;
		return null;
	}
	$raw  = (string) file_get_contents( $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	$meta = array();
	if ( preg_match( '/^\s*<!--meta\s+(\{.*?\})\s*-->\s*/s', $raw, $m ) ) {
		$decoded = json_decode( $m[1], true );
		if ( is_array( $decoded ) ) {
			$meta = $decoded;
		}
		$raw = substr( $raw, strlen( $m[0] ) );
	}
	$cache[ $rel ] = array(
		'meta' => $meta,
		'body' => trim( $raw ),
	);
	return $cache[ $rel ];
}

function eab_seed_meta( $rel ) {
	$file = eab_seed_file( $rel );
	return $file ? $file['meta'] : null;
}

/**
 * เนื้อหาพร้อมใช้ ({{home}} → URL เว็บ)
 */
function eab_seed_content( $rel ) {
	$file = eab_seed_file( $rel );
	if ( ! $file ) {
		return '';
	}
	return str_replace( '{{home}}', untrailingslashit( home_url() ), $file['body'] );
}

/**
 * ป้ายเล็กเหนือหัวเพจ (kicker) จากไฟล์เนื้อหา หรือกลุ่มเพจ
 */
function eab_page_kicker( $slug ) {
	$pages = eab_site_pages();
	if ( isset( $pages[ $slug ] ) && ! empty( $pages[ $slug ]['content'] ) ) {
		$meta = eab_seed_meta( $pages[ $slug ]['content'] );
		if ( ! empty( $meta['kicker'] ) ) {
			return $meta['kicker'];
		}
	}
	$groups = array(
		'test'    => 'Testing',
		'guide'   => 'Guide',
		'pricing' => 'Pricing',
		'doc'     => 'Document',
	);
	$group = isset( $pages[ $slug ]['group'] ) ? $pages[ $slug ]['group'] : '';
	return isset( $groups[ $group ] ) ? $groups[ $group ] : 'EA BULL';
}

/**
 * รายการคู่มือ (ใช้ในเมนู, footer, หน้า /articles/, กล่องคู่มือที่เกี่ยวข้อง)
 */
function eab_guide_links( $groups = array( 'guide', 'test' ) ) {
	$out = array();
	foreach ( eab_site_pages() as $slug => $page ) {
		if ( in_array( $page['group'], (array) $groups, true ) ) {
			$out[ $slug ] = array(
				'label' => ! empty( $page['menu'] ) ? $page['menu'] : $page['title'],
				'url'   => home_url( '/' . $slug . '/' ),
			);
		}
	}
	return $out;
}

/**
 * URL ของเพจตาม slug เฉพาะเมื่อเผยแพร่แล้ว (ฉบับร่าง/ไม่มี = '') · กันลิงก์ 404 ไปยังเพจกฎหมายที่ยังเป็นร่าง
 */
function eab_published_page_url( $slug ) {
	static $cache = array();
	if ( isset( $cache[ $slug ] ) ) {
		return $cache[ $slug ];
	}
	$page            = get_page_by_path( $slug );
	$cache[ $slug ] = ( $page && 'publish' === get_post_status( $page ) ) ? get_permalink( $page ) : '';
	return $cache[ $slug ];
}

/**
 * slug ของบทความเริ่มต้นที่ผู้เข้าชมยังเปิดไม่ได้ (ยังไม่ได้นำเข้า หรือยังเป็นฉบับร่าง)
 * · 1 query (อ่านเฉพาะคอลัมน์ post_name) ต่อคำขอ
 */
function eab_unpublished_seed_articles() {
	if ( isset( $GLOBALS['eab_unpublished_seed'] ) ) {
		return $GLOBALS['eab_unpublished_seed'];
	}
	global $wpdb;
	$slugs = array_keys( eab_seed_article_covers() );
	$in    = implode( ',', array_fill( 0, count( $slugs ), '%s' ) );
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare -- $in มีแต่ %s
	$have = (array) $wpdb->get_col( $wpdb->prepare( "SELECT post_name FROM {$wpdb->posts} WHERE post_type = 'post' AND post_status = 'publish' AND post_name IN ($in)", $slugs ) );

	$GLOBALS['eab_unpublished_seed'] = array_values( array_diff( $slugs, $have ) );
	return $GLOBALS['eab_unpublished_seed'];
}

/**
 * เผยแพร่/ถอนบทความระหว่างคำขอ (เช่นนำเข้าแบบเผยแพร่ทันที) → คำนวณรายการใหม่
 */
function eab_unpublished_seed_reset() {
	unset( $GLOBALS['eab_unpublished_seed'] );
}
add_action( 'transition_post_status', 'eab_unpublished_seed_reset' );

/**
 * ตัดลิงก์ที่ชี้ไปยัง slug ในรายการ $slugs (ปลายทางที่ผู้เข้าชมยังเปิดไม่ได้) ออกจาก HTML
 * 1) ประโยคอ้างอิงที่ห่อด้วย <span class="xref"> หรือ <p class="xref"> → ลบทั้งประโยค
 * 2) รายการ <li> ที่มีแต่ลิงก์นั้น (เช่นในกล่อง "ที่เกี่ยวข้อง") → ลบทั้งรายการ · กล่องที่ว่างลงถูกลบด้วย
 * 3) ลิงก์ที่เหลือในเนื้อความ → ข้อความธรรมดา
 * กติกาความปลอดภัย: ทุกขั้นหยุดที่ขอบบล็อก (li, p, ul, div, h2 ...) และไม่ข้ามแท็กซ้อนชนิดเดียวกัน
 * ถ้า HTML ผิดรูปจน match ไม่ได้ ขั้นนั้นไม่ทำอะไร (ลิงก์อาจยังเหลือ แต่เนื้อหาไม่มีวันหาย)
 * · ขั้นใดล้มเหลว (null) จะข้ามขั้นนั้น · ใช้ quantifier แบบ possessive จึงไม่ติด backtrack/JIT limit
 */
function eab_unlink_slugs( $content, $slugs ) {
	if ( ! $slugs || false === stripos( (string) $content, '<a' ) ) {
		return $content;
	}
	// ที่อยู่ปลายทาง: แบบเต็ม (http/https หรือ //), แบบ /slug/ ภายในเว็บ · ท้ายมี ?query หรือ #anchor ได้
	$parts = wp_parse_url( home_url() );
	$host  = isset( $parts['host'] ) ? preg_quote( $parts['host'], '#' ) : '';
	$path  = isset( $parts['path'] ) ? preg_quote( untrailingslashit( $parts['path'] ), '#' ) : '';
	$alt   = implode( '|', array_map( function ( $s ) { return preg_quote( $s, '#' ); }, $slugs ) );
	$url   = '(?:(?:https?:)?//' . $host . '(?::\d+)?)?' . $path . '/(?:' . $alt . ')/?(?:[?\#][^"\'\s>]*)?';
	$href  = '<a\s[^>]*?href\s*=\s*(?:"' . $url . '"|\'' . $url . '\')[^>]*>';
	$block = '(?:li|p|ul|ol|div|h[1-6]|table|thead|tbody|tr|td|th|details|summary|blockquote|section|figure)\b';
	// ข้อความในลิงก์: ห้ามข้าม </a> และห้ามข้ามขอบบล็อก
	$text  = '((?:[^<]++|<(?!/a\s*>|/?' . $block . '))*+)';
	$link  = $href . $text . '</a\s*>';
	// ข้อความในประโยค xref: ห้ามมีแท็กชนิดเดียวกันซ้อน และห้ามข้ามขอบบล็อก
	$xtext = '(?:(?!</?(?:\1\b|' . $block . ')).)*';
	$steps = array(
		array( '#\s*<(p|span) class="xref">' . $xtext . '?' . $href . $xtext . '</\1>#is', '' ),
		array( '#<li>\s*' . $link . '\s*</li>\s*#is', '' ),
		array( '#<div class="related-links">\s*<h2[^>]*>(?:(?!</?h2\b).)*</h2>\s*<ul[^>]*>\s*</ul>\s*</div>#is', '' ),
		array( '#' . $link . '#is', '$1' ),
	);
	foreach ( $steps as $step ) {
		$next = preg_replace( $step[0], $step[1], $content );
		if ( null !== $next ) {
			$content = $next;
		}
	}
	return $content;
}

/**
 * ในเนื้อหา: ลิงก์ไปเพจที่ Setup สร้างเป็นฉบับร่าง (เพจกฎหมาย) หรือบทความเริ่มต้นที่ยังไม่เผยแพร่
 * จะไม่เป็นลิงก์จนกว่าปลายทางจะเผยแพร่ (กันลิงก์ 404 ระหว่างทยอยเผยแพร่บทความ) · กติกาอยู่ที่ eab_unlink_slugs()
 * · priority 15: หลัง wpautop (10) และ shortcode (11) ก่อนสร้างสารบัญ (20) สารบัญจึงไม่มีหัวข้อที่ถูกลบ
 * · ทำเฉพาะตอนแสดงผลให้ผู้เข้าชม ไม่ทำในหลังบ้าน/REST/cron (ปลั๊กอิน SEO ที่นับลิงก์ภายในจะเห็นลิงก์ครบ)
 */
function eab_unlink_draft_pages( $content ) {
	if ( false === stripos( (string) $content, '<a' ) ) {
		return $content;
	}
	if ( ( is_admin() && ! wp_doing_ajax() ) || wp_doing_cron() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
		return $content;
	}
	return eab_unlink_slugs( $content, eab_unreachable_slugs() );
}

/**
 * slug ที่ผู้เข้าชมยังเปิดไม่ได้: เพจที่ Setup สร้างเป็นฉบับร่าง (เพจกฎหมาย) + บทความเริ่มต้นที่ยังไม่เผยแพร่
 * ใช้ทั้งกับเนื้อหาเพจ (eab_unlink_draft_pages) และลิงก์ [ข้อความ](/slug/) ในข้อความ Customizer (eab_rich_inline)
 */
function eab_unreachable_slugs() {
	$drafts = array();
	foreach ( eab_site_pages() as $slug => $page ) {
		if ( isset( $page['status'] ) && 'draft' === $page['status'] && ! eab_published_page_url( $slug ) ) {
			$drafts[] = $slug;
		}
	}
	return array_merge( $drafts, eab_unpublished_seed_articles() );
}
add_filter( 'the_content', 'eab_unlink_draft_pages', 15 );

/* ==============================================================
 * Admin · รูปแบบ → EA BULL Setup
 * ============================================================== */

function eab_setup_menu() {
	add_theme_page( 'EA BULL Setup', 'EA BULL Setup', 'edit_theme_options', 'eabull-setup', 'eab_setup_screen' );
}
add_action( 'admin_menu', 'eab_setup_menu' );

/**
 * หา page จาก slug (รวมฉบับร่าง)
 */
function eab_find_page( $slug ) {
	$page = get_page_by_path( $slug, OBJECT, 'page' );
	if ( $page ) {
		return $page;
	}
	$q = get_posts(
		array(
			'name'           => $slug,
			'post_type'      => 'page',
			'post_status'    => array( 'publish', 'draft', 'pending', 'private' ),
			'posts_per_page' => 1,
		)
	);
	return $q ? $q[0] : null;
}

function eab_find_post( $slug ) {
	$q = get_posts(
		array(
			'name'           => $slug,
			'post_type'      => 'post',
			'post_status'    => array( 'publish', 'draft', 'pending', 'private', 'future' ),
			'posts_per_page' => 1,
		)
	);
	return $q ? $q[0] : null;
}

/**
 * ใส่ SEO meta ให้ Yoast / Rank Math (ถ้ามีค่าในไฟล์)
 */
function eab_apply_seo_meta( $post_id, $meta ) {
	$meta = wp_slash( $meta ); // update_post_meta() คาดหวังข้อมูลแบบ slashed
	if ( ! empty( $meta['seo_title'] ) ) {
		update_post_meta( $post_id, '_yoast_wpseo_title', $meta['seo_title'] );
		update_post_meta( $post_id, 'rank_math_title', $meta['seo_title'] );
	}
	if ( ! empty( $meta['description'] ) ) {
		update_post_meta( $post_id, '_yoast_wpseo_metadesc', $meta['description'] );
		update_post_meta( $post_id, 'rank_math_description', $meta['description'] );
		update_post_meta( $post_id, 'eab_meta_description', $meta['description'] );
	}
	if ( ! empty( $meta['keyword'] ) ) {
		update_post_meta( $post_id, '_yoast_wpseo_focuskw', $meta['keyword'] );
		update_post_meta( $post_id, 'rank_math_focus_keyword', $meta['keyword'] );
	}
}

/**
 * นำรูปแบนเนอร์ในธีมเข้า Media Library (ครั้งเดียว) เพื่อใช้เป็นรูปหน้าปกบทความ
 */
function eab_banner_attachment( $file, $alt = '' ) {
	// $file = พาธใต้ assets/img/ เฉพาะโฟลเดอร์ banners/ covers/ หรือ brand/ (ชื่อไฟล์ล้วน = banners/)
	$file = ltrim( str_replace( '\\', '/', (string) $file ), '/' );
	if ( false === strpos( $file, '/' ) ) {
		$file = 'banners/' . $file;
	}
	if ( ! preg_match( '#^(banners|covers|brand)/[a-z0-9._-]+$#i', $file ) ) {
		return 0;
	}
	$map = get_option( 'eab_banner_attachments', array() );
	if ( ! empty( $map[ $file ] ) && get_post( $map[ $file ] ) ) {
		return (int) $map[ $file ];
	}
	$src = get_template_directory() . '/assets/img/' . $file;
	if ( ! file_exists( $src ) ) {
		return 0;
	}
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$tmp = wp_tempnam( basename( $file ) );
	if ( ! $tmp || ! copy( $src, $tmp ) ) {
		return 0;
	}
	$id = media_handle_sideload(
		array(
			'name'     => basename( $file ),
			'tmp_name' => $tmp,
		),
		0,
		'' !== $alt ? $alt : 'EA BULL · ภาพประกอบ'
	);
	if ( is_wp_error( $id ) ) {
		@unlink( $tmp ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		return 0;
	}
	update_post_meta( $id, '_wp_attachment_image_alt', '' !== $alt ? $alt : 'ภาพประกอบ EA BULL ระบบเทรดอัตโนมัติบน MetaTrader 5' );
	$map[ $file ] = (int) $id;
	update_option( 'eab_banner_attachments', $map, false );
	return (int) $id;
}

/**
 * สร้างเพจที่ยังไม่มี · ไม่แตะเนื้อหาเพจที่มีอยู่แล้ว (เว้นแต่เลือก overwrite)
 * ลำดับเพจ (menu_order) = ลำดับใน eab_site_pages() × 10 · เพจเดิมที่ลำดับยังเป็น 0 จะได้ลำดับนี้ ส่วนที่เจ้าของจัดเองไม่ถูกแตะ
 */
function eab_setup_create_pages( $overwrite = false ) {
	$log   = array();
	$order = 0;
	foreach ( eab_site_pages() as $slug => $page ) {
		$order  += 10;
		$meta    = ! empty( $page['content'] ) ? eab_seed_meta( $page['content'] ) : ( isset( $page['seo'] ) ? $page['seo'] : array() );
		$title   = ! empty( $meta['title'] ) ? $meta['title'] : $page['title'];
		$content = ! empty( $page['content'] ) ? eab_seed_content( $page['content'] ) : '';
		$status  = isset( $page['status'] ) ? $page['status'] : 'publish';
		$found   = eab_find_page( $slug );

		$seed_rev = ! empty( $meta['rev'] ) ? (int) $meta['rev'] : 1;
		if ( ! $found ) {
			$id = wp_insert_post(
				array(
					'post_type'    => 'page',
					'post_status'  => $status,
					'post_title'   => wp_slash( $title ),
					'post_name'    => $slug,
					'post_content' => wp_slash( $content ),
					'menu_order'   => $order,
				),
				true
			);
			if ( is_wp_error( $id ) ) {
				$log[] = '✗ ' . $slug . ' · ' . $id->get_error_message();
				continue;
			}
			update_post_meta( $id, 'eab_seed_rev', $seed_rev );
			$log[] = '✓ สร้าง /' . $slug . '/' . ( 'draft' === $status ? ' (ฉบับร่าง รอตรวจ)' : '' );
			if ( 'privacy-policy' === $slug ) {
				update_option( 'wp_page_for_privacy_policy', (int) $id ); // ตั้งเป็นเพจนโยบายความเป็นส่วนตัวของเว็บ
			}
		} else {
			$id = $found->ID;
			if ( 0 === (int) $found->menu_order ) {
				wp_update_post(
					array(
						'ID'         => $id,
						'menu_order' => $order,
					)
				);
			}
			// เพจ Privacy Policy ฉบับร่างที่ WordPress สร้างให้ตอนติดตั้ง (ยังไม่เคยแก้) → แทนด้วยเนื้อหาของธีม
			$core_privacy = 'privacy-policy' === $slug
				&& (int) get_option( 'wp_page_for_privacy_policy' ) === (int) $id
				&& 'draft' === $found->post_status
				&& $found->post_modified === $found->post_date;
			if ( $core_privacy && $content && ! $overwrite ) {
				wp_update_post(
					array(
						'ID'           => $id,
						'post_title'   => wp_slash( $title ),
						'post_content' => wp_slash( $content ),
					)
				);
				update_post_meta( $id, '_wp_page_template', $page['template'] );
				update_post_meta( $id, 'eab_seed_rev', ! empty( $meta['rev'] ) ? (int) $meta['rev'] : 1 );
				eab_apply_seo_meta( $id, $meta );
				$log[] = '↻ แทนเพจ Privacy Policy เริ่มต้นของ WordPress ด้วยฉบับของธีม (ยังเป็นฉบับร่าง)';
				continue;
			}
			if ( $overwrite && $content ) {
				wp_update_post(
					array(
						'ID'           => $id,
						'post_content' => wp_slash( $content ),
					)
				);
				update_post_meta( $id, 'eab_seed_rev', $seed_rev );
				$log[] = '↻ เขียนทับเนื้อหา /' . $slug . '/';
			}
		}

		if ( ! empty( $page['template'] ) ) {
			$current = get_post_meta( $id, '_wp_page_template', true );
			// ว่าง / ปกติ / เขียนทับ / ไฟล์เทมเพลตเดิมไม่มีในธีมนี้แล้ว (มาจากธีมก่อนหน้า) → ใช้เทมเพลตของธีม
			if ( ! $current || 'default' === $current || $overwrite || ! locate_template( $current ) ) {
				update_post_meta( $id, '_wp_page_template', $page['template'] );
			}
		}
		// เพจเดิมจากธีมก่อนหน้าที่ไม่มีไฟล์เนื้อหา (หน้าแรก /go บทความ) รับชื่อและ SEO ของธีมครั้งแรกครั้งเดียว
		$adopt = $found && empty( $page['content'] ) && '' === (string) get_post_meta( $id, 'eab_seed_rev', true );
		if ( $adopt ) {
			if ( ! empty( $page['title'] ) && $found->post_title !== $page['title'] && empty( $page['front'] ) ) {
				wp_update_post(
					array(
						'ID'         => $id,
						'post_title' => wp_slash( $page['title'] ),
					)
				);
			}
			update_post_meta( $id, 'eab_seed_rev', 1 );
			$log[] = '↻ ปรับชื่อและ SEO ของ /' . $slug . '/ ให้เป็นของ EA BULL';
		}
		if ( $meta && ( ! $found || $overwrite || $adopt ) ) {
			eab_apply_seo_meta( $id, $meta );
		}
	}
	return $log;
}

/**
 * ตั้งหน้าแรก (home) + หน้าบทความ (articles)
 */
function eab_setup_reading() {
	$home     = eab_find_page( 'home' );
	$articles = eab_find_page( 'articles' );
	if ( ! $home || ! $articles ) {
		return array( '✗ ต้องสร้างเพจ home และ articles ก่อน' );
	}
	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $home->ID );
	update_option( 'page_for_posts', $articles->ID );
	return array( '✓ ตั้ง "หน้าแรก" เป็นหน้าเว็บหลัก และ "บทความ" เป็นหน้ารวมบทความ' );
}

/**
 * สร้างเมนูหลัก (ถ้ายังไม่มีเมนูที่ตำแหน่ง primary)
 */
function eab_setup_menu_build() {
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	if ( ! empty( $locations['primary'] ) && wp_get_nav_menu_object( $locations['primary'] ) ) {
		return array( '• มีเมนูหลักอยู่แล้ว (ไม่สร้างซ้ำ) · แก้ได้ที่ รูปแบบ → เมนู' );
	}
	// มีเมนู EA BULL อยู่แล้ว (เช่นกดซ้ำ หรือเคยถอดออกจากตำแหน่ง) → แค่ตั้งตำแหน่งให้ ไม่เพิ่มรายการซ้ำ
	$existing = wp_get_nav_menu_object( 'EA BULL · เมนูหลัก' );
	if ( $existing ) {
		$locations['primary'] = $existing->term_id;
		set_theme_mod( 'nav_menu_locations', $locations );
		return array( '• ใช้เมนู "EA BULL · เมนูหลัก" ที่มีอยู่แล้ว และตั้งเป็นเมนู Header' );
	}
	$menu_id = wp_create_nav_menu( 'EA BULL · เมนูหลัก' );
	if ( is_wp_error( $menu_id ) ) {
		return array( '✗ สร้างเมนูไม่สำเร็จ: ' . $menu_id->get_error_message() );
	}
	// ตั้งตำแหน่งก่อนเพิ่มรายการ เพื่อให้การกดซ้ำระหว่างทำงานเจอเมนูนี้และไม่สร้างซ้ำ
	$locations['primary'] = $menu_id;
	set_theme_mod( 'nav_menu_locations', $locations );

	// $target = slug ของเพจ (ลิงก์แบบ page object ให้ไฮไลต์เมนูปัจจุบันได้) หรือ URL เต็ม
	$add = function ( $title, $target, $parent = 0 ) use ( $menu_id ) {
		$args = array(
			'menu-item-title'     => $title,
			'menu-item-status'    => 'publish',
			'menu-item-parent-id' => $parent,
		);
		$page = 0 === strpos( $target, 'http' ) ? null : eab_find_page( $target );
		if ( $page ) {
			$args['menu-item-type']      = 'post_type';
			$args['menu-item-object']    = 'page';
			$args['menu-item-object-id'] = $page->ID;
		} else {
			$args['menu-item-type'] = 'custom';
			$args['menu-item-url']  = 0 === strpos( $target, 'http' ) ? $target : home_url( '/' . $target . '/' );
		}
		return wp_update_nav_menu_item( $menu_id, 0, $args );
	};

	$add( 'หน้าแรก', home_url( '/' ) );
	// สินค้าทั้งหมดเรียงตามเลขรุ่น (products.php · item: label = "ชื่อ · Vเลขรุ่น", slug) · หัวเมนูลิงก์ไปสินค้าตัวแรก
	$list    = function_exists( 'eab_products' ) ? eab_products() : array();
	$targets = array();
	foreach ( $list as $item ) {
		$targets[ $item['label'] ] = '' !== $item['slug'] ? $item['slug'] : home_url( '/#products' );
	}
	if ( $targets ) {
		$products = $add( 'สินค้า', reset( $targets ) );
		foreach ( $targets as $label => $target ) {
			$add( $label, $target, $products );
		}
	}
	$test = $add( 'การทดสอบ', 'backtest' );
	foreach ( eab_guide_links( array( 'test' ) ) as $slug => $link ) {
		$add( $link['label'], $slug, $test );
	}
	$guide = $add( 'คู่มือการใช้งาน', 'how-to-install' );
	foreach ( eab_guide_links( array( 'guide' ) ) as $slug => $link ) {
		$add( $link['label'], $slug, $guide );
	}
	$add( 'แพ็กเกจ', 'pricing' );
	$add( 'บทความ', 'articles' );
	$add( 'ติดต่อ', 'go' );

	return array( '✓ สร้างเมนูหลักและตั้งที่ตำแหน่ง Header แล้ว' );
}

/**
 * นำเข้าบทความ (ข้ามบทความที่มี slug อยู่แล้ว)
 */
function eab_setup_import_articles( $publish = false ) {
	$log = array();
	foreach ( eab_seed_articles() as $slug => $art ) {
		if ( eab_find_post( $slug ) ) {
			$log[] = '• มีอยู่แล้ว: ' . $slug;
			continue;
		}
		$meta   = $art['meta'];
		$cat    = eab_article_category_id( isset( $meta['category'] ) ? $meta['category'] : '' );
		$status = $publish ? 'publish' : 'draft';
		$post   = array(
			'post_type'     => 'post',
			'post_title'    => wp_slash( $art['title'] ),
			'post_name'     => $slug,
			'post_content'  => wp_slash( eab_seed_content( $art['content'] ) ),
			'post_excerpt'  => isset( $meta['excerpt'] ) ? wp_slash( $meta['excerpt'] ) : '',
			'post_category' => $cat ? array( $cat ) : array(),
		);
		/* มีวันลงในไฟล์: อนาคต = ตั้งเวลา (ระบบตั้งเวลาของ WordPress) · เลยมาแล้ว = เผยแพร่ด้วยวันนั้น */
		$date = eab_seed_article_date( $meta );
		if ( $date ) {
			$status                = strtotime( $date[1] . ' UTC' ) > time() ? 'future' : 'publish';
			$post['post_date']     = $date[0];
			$post['post_date_gmt'] = $date[1];
		}
		$post['post_status'] = $status;
		$id                  = wp_insert_post( $post, true );
		if ( is_wp_error( $id ) ) {
			$log[] = '✗ ' . $slug . ' · ' . $id->get_error_message();
			continue;
		}
		eab_apply_seo_meta( $id, $meta );
		update_post_meta( $id, 'eab_seed_rev', ! empty( $meta['rev'] ) ? (int) $meta['rev'] : 1 );
		// รูปปกเฉพาะบทความ (covers/) ใช้ชื่อบทความเป็น alt · แบนเนอร์สำรองใช้ alt กลาง
		$thumb = eab_banner_attachment( $art['cover'], 0 === strpos( $art['cover'], 'covers/' ) ? $art['title'] : '' );
		if ( $thumb ) {
			set_post_thumbnail( $id, $thumb );
		}
		$labels = array(
			'publish' => 'เผยแพร่',
			'draft'   => 'ฉบับร่าง',
			'future'  => 'ตั้งเวลา ' . ( $date ? $meta['date'] : '' ),
		);
		$log[]  = '✓ ' . $labels[ $status ] . ': ' . $art['title'];
	}
	return $log ? $log : array( '• ไม่มีไฟล์บทความให้นำเข้า' );
}

/**
 * คำอธิบายหมวดบทความ (ใช้เป็น meta description ของหน้าหมวดผ่าน %%term_description%% ของ Yoast)
 */
function eab_article_category_descriptions() {
	return wp_list_pluck( eab_article_categories(), 1 );
}

/**
 * หมวดบทความ 8 หมวด = 8 กลุ่มบทความ (docs/content-plan.md) · slug => array( ชื่อหมวด, คำอธิบาย )
 * ชื่อหมวดในไฟล์บทความ (meta category) ต้องตรงกับชื่อที่นี่ · ปุ่ม "อัปเดตบทความที่มีอยู่" ตั้งชื่อและคำอธิบายให้ตรงเสมอ
 */
function eab_article_categories() {
	return array(
		'ea-basics'       => array( 'EA และบอทเทรด', 'EA และบอทเทรดบน MT5: EA คืออะไร EA เทรดทองทำงานยังไง เลือก EA ตัวไหนดี ฟรีกับเสียเงินต่างกันยังไง และสัญญาณเตือนบอทเทรดหลอก' ),
		'mt5'             => array( 'ใช้งาน MT5', 'คู่มือใช้งาน MetaTrader 5: ตั้งค่าที่ EA ต้องใช้ เพิ่มสัญลักษณ์ อ่านเวลาเซิร์ฟเวอร์ และแก้อาการ MT5 ค้าง ไม่ขึ้นราคา หรือขึ้น Error ที่เจอบ่อย' ),
		'vps'             => array( 'VPS สำหรับ EA', 'VPS สำหรับรัน EA ทั้งวันทั้งคืน: เลือกสเปกและผู้ให้บริการ เข้า VPS จากคอมและมือถือ ตั้งค่าไม่ให้ MT5 หยุด และแก้ปัญหา Remote Desktop หลุด' ),
		'gold-trading'    => array( 'เทรดทองคำ XAUUSD', 'ความรู้เทรดทองคำ XAUUSD: เวลาตลาดตามเวลาไทย ขนาด Lot และ Pip คำนวณกำไรขาดทุน ปัจจัยที่ทำให้ทองขึ้นลง และข่าวเศรษฐกิจที่ทำให้ราคาผันผวน' ),
		'risk-management' => array( 'บริหารความเสี่ยง', 'บริหารความเสี่ยงสำหรับคนใช้ EA: Drawdown Margin Level Stop Out ขนาด Lot Equity กับ Balance และความเสี่ยงของกลยุทธ์ที่ควรรู้ก่อนใช้เงินจริง' ),
		'trading-plan'    => array( 'วางแผนการเทรด', 'วางแผนการเทรดก่อนเปิดใช้ EA: เขียนแผน กำหนดความเสี่ยงต่อออเดอร์ แบ่งทุน ตั้งเพดานขาดทุน จิตวิทยาการเทรด และเรื่องที่มือใหม่ควรรู้ก่อนเริ่ม' ),
		'monitoring'      => array( 'ติดตามผล', 'ติดตามผล EA บน MT5: ดูพอร์ตบนมือถือ ตั้งแจ้งเตือน อ่าน Log แชร์ผลด้วย Investor Password และ Myfxbook และอ่านค่าอย่าง Profit Factor ให้เป็น' ),
		'broker-account'  => array( 'บัญชีและโบรกเกอร์', 'บัญชีเทรดและโบรกเกอร์สำหรับ EA: เลือกโบรกเกอร์ ประเภทบัญชี Spread Swap การยืนยันตัวตน และการฝากถอนเงินกับ Zaurix' ),
	);
}

/**
 * ภาพปกของเพจเป็นภาพเด่น (featured image) · ข้ามเพจที่มีภาพเด่นอยู่แล้ว
 *
 * @return string[] log
 */
function eab_setup_page_covers() {
	$log  = array();
	$done = array();
	foreach ( eab_site_pages() as $slug => $page ) {
		$file = 'covers/page-' . $slug . '.webp';
		if ( ! file_exists( get_template_directory() . '/assets/img/' . $file ) ) {
			continue;
		}
		$found = eab_find_page( $slug );
		if ( ! $found || has_post_thumbnail( $found->ID ) ) {
			continue;
		}
		$id = eab_banner_attachment( $file, wp_strip_all_tags( $found->post_title ) . ' · EA BULL' );
		if ( $id && set_post_thumbnail( $found->ID, $id ) ) {
			$done[] = '/' . $slug . '/';
		}
	}
	$log[] = $done ? '✓ ใส่ภาพปกเป็นภาพเด่นของเพจ: ' . implode( ' ', $done ) : '• ภาพปกของเพจ: ทุกเพจมีภาพเด่นแล้ว';
	return $log;
}

/**
 * ตั้งค่า SEO และระบบครั้งเดียว: Yoast (องค์กร โลโก้ รูปแชร์ ชื่อหน้าภาษาไทย ปิด archive ที่ไม่ใช้)
 * ปิดคอมเมนต์ทั้งเว็บ · alt ของโลโก้ใน Media · คำอธิบายหมวดบทความ · กดซ้ำได้ (ทับด้วยค่าเดิม)
 */
function eab_setup_seo() {
	$log = array();

	/* 1) ปิดคอมเมนต์และ pingback ทั้งเว็บ (ธีมปิดในโค้ดอยู่แล้ว ตั้งค่า WordPress ซ้ำไว้เผื่อเปลี่ยนธีม) */
	update_option( 'default_comment_status', 'closed' );
	update_option( 'default_ping_status', 'closed' );
	update_option( 'default_pingback_flag', 0 );
	global $wpdb;
	$closed = (int) $wpdb->query( "UPDATE {$wpdb->posts} SET comment_status = 'closed', ping_status = 'closed' WHERE comment_status = 'open' OR ping_status = 'open'" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
	clean_post_cache( 0 );
	$log[] = '✓ ปิดคอมเมนต์และ pingback ทั้งเว็บ (ปิดเพิ่ม ' . $closed . ' เรื่อง/เพจ)';

	/* 2) alt ของโลโก้ใน Media (ไอคอนเว็บ + โลโก้ที่ตั้งไว้) */
	$logo_ids = array_filter( array_unique( array( (int) get_option( 'site_icon' ), (int) get_theme_mod( 'custom_logo' ) ) ) );
	foreach ( get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'posts_per_page' => 20, 's' => 'EA-BULL', 'fields' => 'ids' ) ) as $att ) {
		$logo_ids[] = (int) $att;
	}
	$alt_done = 0;
	foreach ( array_unique( $logo_ids ) as $att ) {
		if ( '' === trim( (string) get_post_meta( $att, '_wp_attachment_image_alt', true ) ) ) {
			update_post_meta( $att, '_wp_attachment_image_alt', 'โลโก้ EA BULL' );
			++$alt_done;
		}
	}
	$log[] = '✓ เติม alt ให้โลโก้ใน Media ' . $alt_done . ' ไฟล์';

	/* 3) คำอธิบายหมวดบทความ */
	foreach ( eab_article_category_descriptions() as $slug => $desc ) {
		$term = get_term_by( 'slug', $slug, 'category' );
		if ( $term && '' === trim( (string) $term->description ) ) {
			wp_update_term( $term->term_id, 'category', array( 'description' => $desc ) );
			$log[] = '✓ ใส่คำอธิบายหมวด ' . $term->name;
		}
	}

	/* 4) ภาพปกของเพจ (assets/img/covers/page-<slug>.webp) เป็นภาพเด่น · ใช้เป็น og:image และภาพหลักของหน้าใน schema
	 * ตั้งเฉพาะเพจที่ยังไม่มีภาพเด่น (ภาพที่เจ้าของเลือกเองไม่ถูกทับ) */
	$log = array_merge( $log, eab_setup_page_covers() );

	/* 5) Yoast */
	if ( ! class_exists( 'WPSEO_Options' ) ) {
		$log[] = '• ไม่พบ Yoast SEO · ข้ามการตั้งค่า Yoast';
		return $log;
	}
	$name = get_bloginfo( 'name' );
	$logo = (int) get_option( 'site_icon' );
	if ( ! $logo ) {
		$logo = (int) get_theme_mod( 'custom_logo' );
	}
	$share = eab_banner_attachment( 'brand/eabull-share-1200x630.jpg', $name . ' · ผู้ช่วยเทรดอัตโนมัติสำหรับ MT5' );

	$set = array(
		'website_name'                => $name,
		'company_or_person'           => 'company',
		'company_name'                => $name,
		'separator'                   => 'sc-middot',
		'disable-author'              => true,
		'disable-date'                => true,
		'disable-post_format'         => true,
		'title-tax-category'          => 'หมวด %%term_title%% %%page%% %%sep%% %%sitename%%',
		'metadesc-tax-category'       => '%%term_description%%',
		'title-search-wpseo'          => 'ผลการค้นหา %%searchphrase%% %%page%% %%sep%% %%sitename%%',
		'title-404-wpseo'             => 'ไม่พบหน้าที่ต้องการ %%sep%% %%sitename%%',
		'rssbefore'                   => '',
		'rssafter'                    => 'บทความ %%POSTLINK%% เผยแพร่ครั้งแรกที่ %%BLOGLINK%%',
		'remove_feed_global_comments' => true,
		'remove_feed_post_comments'   => true,
		'remove_shortlinks'           => true,
	);
	if ( $logo && wp_get_attachment_url( $logo ) ) {
		$set['company_logo']    = wp_get_attachment_url( $logo );
		$set['company_logo_id'] = $logo;
	}
	if ( $share && wp_get_attachment_url( $share ) ) {
		$set['og_default_image']    = wp_get_attachment_url( $share );
		$set['og_default_image_id'] = $share;
	}
	$facebook = trim( (string) eab_mod( 'facebook_url' ) );
	if ( '' !== $facebook && '#' !== $facebook ) {
		$set['facebook_site'] = esc_url_raw( $facebook );
	}
	$failed = array();
	foreach ( $set as $key => $value ) {
		WPSEO_Options::set( $key, $value );
		/* ตรวจด้วยการอ่านกลับ (update_option คืน false เมื่อค่าเดิมเท่ากัน จึงใช้ผลของ set ไม่ได้) */
		if ( (string) WPSEO_Options::get( $key ) !== (string) $value ) {
			$failed[] = $key;
		}
	}
	$log[] = '✓ ตั้งค่า Yoast: องค์กร "' . $name . '" · โลโก้ · รูปแชร์เริ่มต้น · Facebook · ตัวคั่นหัวเรื่อง (จุดกลาง) · ปิดหน้า author / date / format · ชื่อหน้าหมวด ค้นหา 404 เป็นภาษาไทย · ตัด feed คอมเมนต์';
	if ( $failed ) {
		$log[] = '✗ Yoast ไม่รับค่า: ' . implode( ', ', $failed );
	}
	return $log;
}

/**
 * slug ภาษาอังกฤษของหมวดบทความ (URL อ่านง่าย ไม่เป็นภาษาไทยเข้ารหัส)
 */
function eab_article_category_slug( $name ) {
	$map = array( 'พื้นฐาน EA และ MT5' => 'ea-basics' ); // ชื่อเดิมของหมวด ea-basics (ก่อน 8 ต.ค. 2026)
	foreach ( eab_article_categories() as $slug => $cat ) {
		$map[ $cat[0] ] = $slug;
	}
	return isset( $map[ $name ] ) ? $map[ $name ] : sanitize_title( $name );
}

/**
 * term_id ของหมวดตามชื่อในไฟล์บทความ (สร้างหมวดถ้ายังไม่มี · ใช้ชื่อและคำอธิบายจาก eab_article_categories())
 */
function eab_article_category_id( $name ) {
	if ( '' === trim( (string) $name ) ) {
		return 0;
	}
	$slug = eab_article_category_slug( $name );
	$term = term_exists( $slug, 'category' );
	if ( ! $term ) {
		$cats = eab_article_categories();
		$term = wp_insert_term(
			isset( $cats[ $slug ] ) ? $cats[ $slug ][0] : $name,
			'category',
			array(
				'slug'        => $slug,
				'description' => isset( $cats[ $slug ] ) ? $cats[ $slug ][1] : '',
			)
		);
	}
	if ( is_wp_error( $term ) ) {
		return 0;
	}
	return (int) ( is_array( $term ) ? $term['term_id'] : $term );
}

/**
 * วันลงจากไฟล์บทความ ("date":"2026-10-15 08:00" เวลาไทยตามที่ตั้งในเว็บ) → array( post_date, post_date_gmt ) หรือ null
 */
function eab_seed_article_date( $meta ) {
	if ( empty( $meta['date'] ) || ! preg_match( '/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/', (string) $meta['date'] ) ) {
		return null;
	}
	$local = $meta['date'] . ':00';
	return array( $local, get_gmt_from_date( $local ) );
}

/**
 * อัปเดตบทความที่นำเข้าแล้ว: หมวดให้ตรงไฟล์ · ชื่อและคำอธิบายหมวด · เนื้อหารุ่นใหม่ (rev ในไฟล์มากกว่าที่เคยนำเข้า)
 * · วันตั้งเวลาของบทความที่ยังไม่ถึงวันลง · ไม่แตะเนื้อหาบทความที่ไฟล์ไม่มี rev (กันทับที่แก้ในหลังบ้าน)
 */
function eab_setup_sync_articles() {
	$log = array();
	foreach ( eab_article_categories() as $slug => $cat ) {
		$term = get_term_by( 'slug', $slug, 'category' );
		if ( $term && ( $term->name !== $cat[0] || trim( (string) $term->description ) !== $cat[1] ) ) {
			wp_update_term( $term->term_id, 'category', array( 'name' => $cat[0], 'description' => $cat[1] ) );
			$log[] = '✓ หมวด ' . $cat[0] . ' · ตั้งชื่อและคำอธิบายแล้ว';
		}
	}
	foreach ( eab_seed_articles() as $slug => $art ) {
		$found = eab_find_post( $slug );
		if ( ! $found ) {
			continue;
		}
		$meta = $art['meta'];
		$cat  = eab_article_category_id( isset( $meta['category'] ) ? $meta['category'] : '' );
		if ( $cat && wp_get_post_categories( $found->ID ) !== array( $cat ) ) {
			wp_set_post_categories( $found->ID, array( $cat ) );
			$log[] = '↻ ย้ายหมวด: ' . $slug . ' → ' . $meta['category'];
		}
		$rev  = ! empty( $meta['rev'] ) ? (int) $meta['rev'] : 1;
		$prev = (int) get_post_meta( $found->ID, 'eab_seed_rev', true );
		if ( $rev >= 2 && $prev < $rev ) {
			wp_update_post(
				array(
					'ID'           => $found->ID,
					'post_title'   => wp_slash( $art['title'] ),
					'post_content' => wp_slash( eab_seed_content( $art['content'] ) ),
					'post_excerpt' => isset( $meta['excerpt'] ) ? wp_slash( $meta['excerpt'] ) : $found->post_excerpt,
				)
			);
			eab_apply_seo_meta( $found->ID, $meta );
			update_post_meta( $found->ID, 'eab_seed_rev', $rev );
			$log[] = '↻ เนื้อหารุ่น ' . $rev . ': ' . $slug;
		}
		/* ภาพเด่น = ปกรุ่นล่าสุดของบทความ · แก้เฉพาะเมื่อภาพเด่นปัจจุบันเป็นปกที่ธีมนำเข้าเอง (ภาพที่เจ้าของเลือกเองไม่ถูกแตะ) */
		if ( 0 === strpos( $art['cover'], 'covers/' ) ) {
			$ours    = array_map( 'intval', (array) get_option( 'eab_banner_attachments', array() ) );
			$current = (int) get_post_thumbnail_id( $found->ID );
			if ( ! $current || in_array( $current, $ours, true ) ) {
				$thumb = (int) eab_banner_attachment( $art['cover'], $art['title'] );
				if ( $thumb && $thumb !== $current ) {
					set_post_thumbnail( $found->ID, $thumb );
					wp_update_post( array( 'ID' => $found->ID ) ); // บันทึกซ้ำให้ Yoast อ่าน og:image ใหม่ (Yoast จำค่าไว้จนกว่าจะบันทึกโพสต์)
					$log[] = '↻ ภาพเด่น: ' . $slug . ' → ' . basename( $art['cover'] );
				}
			}
		}
		$date = eab_seed_article_date( $meta );
		if ( $date && 'future' === $found->post_status && $found->post_date !== $date[0] ) {
			wp_update_post(
				array(
					'ID'            => $found->ID,
					'post_status'   => 'future',
					'post_date'     => $date[0],
					'post_date_gmt' => $date[1],
					'edit_date'     => true,
				)
			);
			$log[] = '⏰ เลื่อนวันลง: ' . $slug . ' → ' . $meta['date'];
		}
	}
	return $log ? $log : array( '• ทุกบทความตรงกับไฟล์แล้ว' );
}

/**
 * แทนเนื้อหาเพจเดียวด้วยเนื้อหาตั้งต้นรุ่นล่าสุด (เก็บรุ่นเก่าเป็น revision ของ WP)
 */
function eab_setup_replace_page( $slug ) {
	$pages = eab_site_pages();
	if ( ! isset( $pages[ $slug ] ) || empty( $pages[ $slug ]['content'] ) ) {
		return array( '✗ ไม่พบเนื้อหาตั้งต้นของ /' . $slug . '/' );
	}
	$found = eab_find_page( $slug );
	if ( ! $found ) {
		return array( '✗ ยังไม่มีเพจ /' . $slug . '/ · กด "สร้างเพจ" ก่อน' );
	}
	$meta   = eab_seed_meta( $pages[ $slug ]['content'] );
	$update = array(
		'ID'           => $found->ID,
		'post_content' => wp_slash( eab_seed_content( $pages[ $slug ]['content'] ) ),
	);
	if ( ! empty( $meta['title'] ) ) {
		$update['post_title'] = wp_slash( $meta['title'] );
	}
	wp_update_post( $update );
	update_post_meta( $found->ID, 'eab_seed_rev', ! empty( $meta['rev'] ) ? (int) $meta['rev'] : 1 );
	if ( $meta ) {
		eab_apply_seo_meta( $found->ID, $meta );
	}
	return array( '↻ แทนเนื้อหา /' . $slug . '/ ด้วยรุ่นล่าสุดแล้ว (รุ่นเดิมอยู่ใน Revisions ของเพจ)' );
}

function eab_setup_handle() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( 'ไม่มีสิทธิ์' );
	}
	check_admin_referer( 'eab_setup' );

	// กันกดซ้ำขณะกำลังทำงาน (ดับเบิลคลิก / สองแท็บ)
	if ( get_transient( 'eab_setup_lock' ) ) {
		set_transient( 'eab_setup_log', array( '• กำลังทำงานอยู่ · รอสักครู่แล้วรีเฟรชหน้านี้' ), 120 );
		wp_safe_redirect( admin_url( 'themes.php?page=eabull-setup' ) );
		exit;
	}
	set_transient( 'eab_setup_lock', 1, 120 );

	$do  = isset( $_POST['eab_do'] ) ? sanitize_key( wp_unslash( $_POST['eab_do'] ) ) : '';
	$log = array();

	if ( 'pages' === $do || 'all' === $do ) {
		$log = array_merge( $log, eab_setup_create_pages( ! empty( $_POST['eab_overwrite'] ) ) );
	}
	if ( 'reading' === $do || 'all' === $do ) {
		$log = array_merge( $log, eab_setup_reading() );
	}
	if ( 'menu' === $do || 'all' === $do ) {
		$log = array_merge( $log, eab_setup_menu_build() );
	}
	if ( 'replace' === $do ) {
		$slug = isset( $_POST['eab_slug'] ) ? sanitize_key( wp_unslash( $_POST['eab_slug'] ) ) : '';
		$log  = array_merge( $log, eab_setup_replace_page( $slug ) );
	}
	if ( 'articles' === $do ) {
		$log = array_merge( $log, eab_setup_import_articles( ! empty( $_POST['eab_publish'] ) ) );
	}
	if ( 'article_sync' === $do ) {
		$log = array_merge( $log, eab_setup_sync_articles() );
	}
	if ( 'seo' === $do ) {
		$log = array_merge( $log, eab_setup_seo() );
	}
	if ( 'all' === $do ) {
		flush_rewrite_rules( false );
	}

	delete_transient( 'eab_setup_lock' );
	set_transient( 'eab_setup_log', $log, 120 );
	wp_safe_redirect( admin_url( 'themes.php?page=eabull-setup&done=1' ) );
	exit;
}
add_action( 'admin_post_eab_setup', 'eab_setup_handle' );

function eab_setup_screen() {
	$log   = get_transient( 'eab_setup_log' );
	$pages = eab_site_pages();
	delete_transient( 'eab_setup_log' );
	?>
	<div class="wrap">
		<h1>EA BULL · ตั้งค่าเว็บ</h1>
		<p>กดครั้งเดียวได้ครบทุกเพจของ EA BULL พร้อมเทมเพลตและเนื้อหาตั้งต้น ส่วนเพจที่มีอยู่แล้วจะ<strong>ไม่ถูกแตะ</strong> เว้นแต่ติ๊กช่องเขียนทับ</p>

		<?php if ( ! get_option( 'blog_public' ) ) : ?>
			<div class="notice notice-warning"><p><strong>เว็บยังปิดไม่ให้ Google ทำดัชนี</strong> · ไปที่ <a href="<?php echo esc_url( admin_url( 'options-reading.php' ) ); ?>">ตั้งค่า → การอ่าน</a> แล้วเอาเครื่องหมายออกจาก "ขอให้ search engines ไม่ทำดัชนีเว็บไซต์นี้" เมื่อพร้อมเปิดตัว</p></div>
		<?php endif; ?>
		<?php if ( ! get_option( 'permalink_structure' ) ) : ?>
			<div class="notice notice-warning"><p><strong>ลิงก์ถาวรยังเป็นแบบ ?p=</strong> · ไปที่ <a href="<?php echo esc_url( admin_url( 'options-permalink.php' ) ); ?>">ตั้งค่า → ลิงก์ถาวร</a> แล้วเลือก "ชื่อเรื่อง (Post name)"</p></div>
		<?php endif; ?>
		<?php if ( '#' === eab_mod( 'line_url' ) || ! eab_mod( 'line_url' ) ) : ?>
			<div class="notice notice-warning"><p><strong>ยังไม่ได้ใส่ลิงก์ LINE OA</strong> · ปุ่มติดต่อทุกปุ่มจะพาไปหน้า /go/ แทน (ถ้าหน้า /go/ ยังไม่เผยแพร่ ปุ่มจะถูกซ่อน) · ตั้งค่าที่ <a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=eab_general' ) ); ?>">ปรับแต่ง → ช่องทางติดต่อ</a></p></div>
		<?php endif; ?>

		<?php if ( $log ) : ?>
			<div class="notice notice-success"><p><?php echo implode( '<br>', array_map( 'esc_html', (array) $log ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p></div>
		<?php endif; ?>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="margin:18px 0 26px;padding:18px 20px;background:#fff;border:1px solid #dcdcde;border-radius:8px;max-width:760px">
			<?php wp_nonce_field( 'eab_setup' ); ?>
			<input type="hidden" name="action" value="eab_setup">
			<h2 style="margin-top:0">1) ตั้งค่าเว็บทั้งหมด</h2>
			<p>สร้างเพจที่ยังไม่มี → ตั้งหน้าแรก/หน้าบทความ → สร้างเมนูหลัก</p>
			<p><label><input type="checkbox" name="eab_overwrite" value="1"> เขียนทับเนื้อหาเพจที่มีอยู่ด้วยเนื้อหาเริ่มต้นของธีม (ระวัง: ข้อความที่แก้ไว้จะหาย)</label></p>
			<p><button class="button button-primary" name="eab_do" value="all">ตั้งค่าเว็บทั้งหมด</button>
				<button class="button" name="eab_do" value="pages">สร้างเพจอย่างเดียว</button>
				<button class="button" name="eab_do" value="menu">สร้างเมนูอย่างเดียว</button></p>
		</form>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="margin:0 0 26px;padding:18px 20px;background:#fff;border:1px solid #dcdcde;border-radius:8px;max-width:760px">
			<?php wp_nonce_field( 'eab_setup' ); ?>
			<input type="hidden" name="action" value="eab_setup">
			<h2 style="margin-top:0">2) นำเข้าบทความ SEO (<?php echo (int) count( eab_seed_articles() ); ?> บทความ)</h2>
			<p>นำเข้าเฉพาะบทความที่ยังไม่มีในเว็บ พร้อมหมวด คำอธิบาย SEO และรูปหน้าปก · บทความที่มีวันลงในไฟล์จะเข้าเป็น <strong>ตั้งเวลา</strong> ตามปฏิทินใน docs/content-plan.md (WordPress เผยแพร่เองเมื่อถึงเวลา) · บทความที่ไม่มีวันลงเข้าเป็นฉบับร่าง</p>
			<p><label><input type="checkbox" name="eab_publish" value="1"> เผยแพร่ทันทีสำหรับบทความที่ไม่มีวันลง (ไม่แนะนำ)</label></p>
			<p><button class="button button-primary" name="eab_do" value="articles">นำเข้าบทความ</button>
				<button class="button" name="eab_do" value="article_sync">อัปเดตบทความที่มีอยู่ (หมวด · รุ่นใหม่ · วันลง)</button></p>
		</form>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="margin:0 0 26px;padding:18px 20px;background:#fff;border:1px solid #dcdcde;border-radius:8px;max-width:760px">
			<?php wp_nonce_field( 'eab_setup' ); ?>
			<input type="hidden" name="action" value="eab_setup">
			<h2 style="margin-top:0">3) ตั้งค่า SEO และระบบ</h2>
			<p>ตั้ง Yoast ให้ครบ (ชื่อองค์กร โลโก้ รูปแชร์เริ่มต้น Facebook ชื่อหน้าหมวด/ค้นหา/404 ภาษาไทย ปิดหน้า author และ date archive) · ปิดคอมเมนต์ทั้งเว็บ · เติม alt ให้โลโก้ · ใส่คำอธิบายหมวดบทความ · ใส่ภาพปกเป็นภาพเด่นของเพจที่ยังไม่มี · กดซ้ำได้</p>
			<p><button class="button button-primary" name="eab_do" value="seo">ตั้งค่า SEO และระบบ</button></p>
		</form>

		<h2>สถานะเพจ</h2>
		<table class="widefat striped" style="max-width:980px">
			<thead><tr><th>URL</th><th>ชื่อเพจ</th><th>สถานะ</th><th>เทมเพลต</th><th>เนื้อหาตั้งต้น</th><th></th></tr></thead>
			<tbody>
			<?php
			foreach ( $pages as $slug => $page ) :
				$found = eab_find_page( $slug );
				$tpl   = $found ? get_post_meta( $found->ID, '_wp_page_template', true ) : '';
				$ok    = ! $page['template'] || $tpl === $page['template'];
				$smeta = ! empty( $page['content'] ) ? eab_seed_meta( $page['content'] ) : null;
				$srev  = $smeta ? ( ! empty( $smeta['rev'] ) ? (int) $smeta['rev'] : 1 ) : 0;
				$prev  = $found ? (int) get_post_meta( $found->ID, 'eab_seed_rev', true ) : 0;
				$stale = $found && $srev && $prev < $srev;
				?>
				<tr>
					<td><code>/<?php echo esc_html( $slug ); ?>/</code></td>
					<td><?php echo esc_html( $found ? $found->post_title : $page['title'] ); ?></td>
					<td><?php echo $found ? esc_html( 'publish' === $found->post_status ? 'เผยแพร่' : 'ฉบับร่าง' ) : '<span style="color:#b32d2e">ยังไม่มี</span>'; ?></td>
					<td><?php echo $page['template'] ? ( $ok ? '✓ ' : '<span style="color:#b32d2e">✗ </span>' ) . esc_html( $page['template'] ) : 'เทมเพลตปกติ'; ?></td>
					<td>
						<?php if ( ! $srev ) : ?>ไม่มี<?php elseif ( ! $found ) : ?>รุ่น <?php echo (int) $srev; ?><?php elseif ( $stale ) : ?>
							<span style="color:#b26c09">มีรุ่นใหม่ (<?php echo (int) $prev; ?> → <?php echo (int) $srev; ?>)</span>
							<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline;margin-left:6px" onsubmit="return confirm('แทนเนื้อหาเพจนี้ด้วยรุ่นล่าสุด? ข้อความที่แก้เองในเพจนี้จะถูกแทน (รุ่นเดิมยังอยู่ใน Revisions)');">
								<?php wp_nonce_field( 'eab_setup' ); ?>
								<input type="hidden" name="action" value="eab_setup">
								<input type="hidden" name="eab_slug" value="<?php echo esc_attr( $slug ); ?>">
								<button class="button button-small" name="eab_do" value="replace">แทนด้วยรุ่นล่าสุด</button>
							</form>
						<?php else : ?>✓ รุ่น <?php echo (int) $srev; ?><?php endif; ?>
					</td>
					<td><?php if ( $found ) : ?><a href="<?php echo esc_url( get_edit_post_link( $found->ID ) ); ?>">แก้ไข</a> · <a href="<?php echo esc_url( get_permalink( $found ) ); ?>" target="_blank" rel="noopener">ดู</a><?php endif; ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<p style="max-width:980px;color:#50575e">เพจ <strong>นโยบายความเป็นส่วนตัว / เงื่อนไขการใช้บริการ / คำขอลบข้อมูล</strong> เริ่มต้นเป็นฉบับร่าง เพราะยังต้องเติมข้อมูลจริงของผู้ให้บริการ เช่น ชื่อ ที่อยู่ อีเมลติดต่อ และนโยบายยกเลิกหรือคืนเงิน (หรือระบุว่าไม่มี) · เปิดโหมดแก้ไขโค้ดแล้วค้นคำว่า "เจ้าของเว็บ" จะเจอทุกจุดที่ต้องกรอก</p>
		<p style="max-width:980px;color:#50575e">อย่าแก้เพจเหล่านี้ด้วย Elementor · ตัวแก้ไขของ Elementor จะทับหน้าตาที่เทมเพลตของธีมจัดไว้</p>
	</div>
	<?php
}
