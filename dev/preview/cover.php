<?php
/**
 * Article cover card (1200×630) · preview route /__cover/<article-slug>/
 * Page cover card (1200×630) · preview route /__cover/page-<page-slug>/ → covers/page-<slug>.webp (featured image + og:image ของเพจ)
 * Rendered by headless Chrome in dev/make-covers.php and saved to eabull/assets/img/covers/<slug>.webp
 * Style: EA BULL "Bull Black" (docs/design.md) · flat black, yellow #FFCC00, a little white · square corners,
 * cut corner on the solid tile · no gradient, no shadow, no glow · Inter + Anuphan.
 * Text only: no results, no profit numbers. Not part of the theme.
 *
 * @var string $fx_cover_slug set by router.php
 */

/* ปกของเพจ: หัวเรื่อง: คำขยาย | ป้ายไทย | ไอคอน | สี (ไม่ใช้แล้ว: ปก Bull Black มีแบบเดียว) */
$page_covers = array(
	'backtest'         => array( 'ทดสอบ EA ย้อนหลัง: ด้วย Strategy Tester ใน MT5', 'ผลทดสอบ', 'candles', 'blue' ),
	'forward-test'     => array( 'Forward Test: ทดสอบ EA กับตลาดจริง', 'ผลทดสอบ', 'pulse', 'sky' ),
	'how-to-install'   => array( 'ติดตั้ง EA BULL บน MT5: ทีละขั้น พร้อมไล่แก้ปัญหา', 'คู่มือ', 'download', 'gold' ),
	'open-mt5-account' => array( 'เปิดบัญชี MT5: ทีละขั้นสำหรับใช้ EA', 'คู่มือ', 'user', 'sky' ),
	'deposit'          => array( 'ฝากเงิน Zaurix: เข้าบัญชีเทรด MT5 ทีละขั้น', 'คู่มือ', 'dollar', 'gold' ),
	'mt5-login'        => array( 'ติดตั้งและล็อกอิน MT5: คอม มือถือ และ VPS', 'คู่มือ', 'monitor', 'blue' ),
	'vps-windows'      => array( 'รัน EA บน Windows VPS: ทำงานต่อเนื่องทั้งวันทั้งคืน', 'คู่มือ', 'windows', 'sky' ),
	'vps-android'      => array( 'เข้า VPS จาก Android: ดูแล EA ผ่านมือถือ', 'คู่มือ', 'android', 'gold' ),
	'vps-ios'          => array( 'เข้า VPS จาก iPhone: ดูแล EA ผ่าน iPhone และ iPad', 'คู่มือ', 'apple', 'blue' ),
	'tools'            => array( 'เครื่องมือคำนวณ: Lot และ Drawdown สำหรับวางแผน', 'เครื่องมือ', 'calc', 'gold' ),
	'pricing'          => array( 'แพ็กเกจ EA BULL: Starter Pro VIP จ่ายครั้งเดียว', 'แพ็กเกจ', 'tag', 'gold' ),
	'risk-disclosure'  => array( 'ความเสี่ยงของการใช้ EA: อ่านก่อนเริ่มใช้เงินจริง', 'ข้อมูลสำคัญ', 'shield', 'blue' ),
	'about'            => array( 'เกี่ยวกับ EA BULL: ผู้ช่วยเทรดอัตโนมัติสำหรับ MT5', 'เกี่ยวกับเรา', 'users', 'sky' ),
	'articles'         => array( 'บทความ EA BULL: วางแผน ติดตาม บริหารความเสี่ยง', 'บทความ', 'book', 'gold' ),
);
$is_page = 0 === strpos( $fx_cover_slug, 'page-' );
if ( $is_page ) {
	$pslug = substr( $fx_cover_slug, 5 );
	if ( ! isset( $page_covers[ $pslug ] ) ) {
		http_response_code( 404 );
		echo 'No page cover: ' . htmlspecialchars( $pslug );
		return;
	}
	$pc  = $page_covers[ $pslug ];
	$art = array(
		'title' => $pc[0],
		'meta'  => array( 'category' => $pc[1] ),
	);
} else {
	$arts = eab_seed_articles();
	if ( ! isset( $arts[ $fx_cover_slug ] ) ) {
		http_response_code( 404 );
		echo 'No article: ' . htmlspecialchars( $fx_cover_slug );
		return;
	}
	$art = $arts[ $fx_cover_slug ];
}

// slug => icon name from eab_icon() · articles not listed use their category icon ($cats below)
$icons = array();
// category (the 10 article clusters, eab_article_categories()) => icon
$cats = array(
	'บอทเทรดทอง'        => 'robot',
	'รัน EA 24 ชั่วโมง'  => 'server',
	'ทดสอบและปรับค่า EA' => 'flask',
	'เทรดอย่างปลอดภัย'   => 'shield',
	'พื้นฐานเทรดทอง'     => 'target',
	'ทุนและขนาดไม้'      => 'wallet',
	'จัดการออเดอร์'      => 'sliders',
	'กลยุทธ์เทรดทอง'     => 'flag',
	'ราคาทองโลก'        => 'chart',
	'อ่านกราฟทองคำ'      => 'candles',
);
$cat  = isset( $art['meta']['category'] ) ? $art['meta']['category'] : '';
$icon = isset( $icons[ $fx_cover_slug ] ) ? $icons[ $fx_cover_slug ] : ( isset( $cats[ $cat ] ) ? $cats[ $cat ] : 'book' );
if ( $is_page ) {
	$icon = $pc[2];
}
$theme = get_template_directory_uri();
$fonts = $theme . '/assets/fonts/';

// "หัวเรื่อง: คำขยาย" → บรรทัดใหญ่ + บรรทัดรอง · แต่ละวลี (คั่นด้วยช่องว่าง) ไม่ถูกตัดกลางคำ
$parts = array_map( 'trim', explode( ':', $art['title'], 2 ) );
$chunk = function ( $text ) {
	$out   = array();
	$words = preg_split( '/\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY );
	$n     = count( $words );
	// คำสั้นท้ายบรรทัด (เช่น "EA") เกาะกับวลีก่อนหน้า ไม่ตกไปอยู่บรรทัดเดียว
	if ( $n > 1 && mb_strlen( $words[ $n - 1 ] ) <= 3 ) {
		$words[ $n - 2 ] .= "\u{00A0}" . array_pop( $words );
	}
	foreach ( $words as $w ) {
		// ภาษาอังกฤษเป็นพิมพ์ใหญ่เหมือนบนเว็บ ยกเว้นชื่อที่ต้องสะกดแบบเดิม (.nocaps)
		$keep  = preg_match( '/^(iPhone|iPad|iOS|macOS)$/u', $w );
		$out[] = '<span' . ( $keep ? ' class="nocaps"' : '' ) . '>' . esc_html( $w ) . '</span>';
	}
	return implode( ' ', $out );
};
$thai  = 'U+02D7, U+0303, U+0331, U+0E01-0E5B, U+200C-200D, U+25CC';
$latin = 'U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD';
?>
<!doctype html>
<html lang="th">
<head>
<meta charset="utf-8">
<title><?php echo esc_html( $art['title'] ); ?></title>
<style>
@font-face{font-family:"Inter";font-weight:400 700;src:url("<?php echo esc_url( $fonts . 'inter-latin.woff2' ); ?>") format("woff2");unicode-range:<?php echo esc_html( $latin ); ?>}
@font-face{font-family:"Anuphan";font-weight:400 700;src:url("<?php echo esc_url( $fonts . 'anuphan-thai.woff2' ); ?>") format("woff2");unicode-range:<?php echo esc_html( $thai ); ?>}
@font-face{font-family:"Anuphan";font-weight:400 700;src:url("<?php echo esc_url( $fonts . 'anuphan-latin.woff2' ); ?>") format("woff2");unicode-range:<?php echo esc_html( $latin ); ?>}
:root{--k0:#0B0B0C;--k2:#1A1A1D;--k3:#222226;--accent:#FFCC00;--fg:#F4F4F5;--fg-2:#C9C9CE}
*{box-sizing:border-box;margin:0;padding:0}
html,body{width:1200px;height:630px;overflow:hidden}
body{position:relative;background:var(--k0);color:var(--fg);font-family:"Inter","Anuphan",sans-serif;-webkit-font-smoothing:antialiased}
/* แถบเหลืองด้านซ้าย + แท่งกราฟขาขึ้นสีเทาเข้มมุมขวาล่าง (สีทึบ ไม่ไล่สี ไม่มีเงา) */
.edge{position:absolute;left:0;top:0;width:14px;height:630px;background:var(--accent)}
.bars{position:absolute;right:56px;bottom:0;display:flex;align-items:flex-end;gap:14px}
.bars i{display:block;width:34px;background:var(--k2)}
.txt{position:absolute;left:78px;top:64px;width:700px;height:502px;display:flex;flex-direction:column}
.ttl{margin:auto 0;padding:0 0 12px}
.ticks{display:flex;gap:8px;margin-bottom:28px}
.ticks i{display:block;width:44px;height:8px;background:var(--accent)}
.ticks i:nth-child(2){width:28px}
.ticks i:nth-child(3){width:14px}
h1{font-size:68px;line-height:1.22;font-weight:700;letter-spacing:-.01em;color:var(--fg);text-transform:uppercase}
.sub{margin-top:.5em;font-size:34px;line-height:1.4;font-weight:500;color:var(--fg-2);text-transform:uppercase}
.ttl span{display:inline-block}
.ttl .nocaps{text-transform:none}
.foot{margin-top:auto;display:flex;align-items:center;gap:22px}
.foot img{height:46px;width:auto;display:block}
.foot .rule{width:2px;height:40px;background:var(--k3)}
.foot p{font-size:22px;line-height:1.2;font-weight:600;color:var(--accent)}
.tile{position:absolute;right:96px;top:50%;width:270px;height:270px;margin-top:-170px;display:flex;align-items:center;justify-content:center;background:var(--accent);color:var(--k0);clip-path:polygon(0 0,calc(100% - 46px) 0,100% 46px,100% 100%,0 100%)}
.tile svg{width:132px;height:132px;fill:none;stroke:currentColor;stroke-width:1.6}
</style>
</head>
<body>
	<span class="edge"></span>
	<div class="bars" aria-hidden="true"><i style="height:70px"></i><i style="height:110px"></i><i style="height:92px"></i><i style="height:150px"></i><i style="height:196px"></i></div>
	<div class="txt">
		<div class="ttl" id="t">
			<div class="ticks"><i></i><i></i><i></i></div>
			<h1><?php echo $chunk( $parts[0] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped per chunk ?></h1>
			<?php if ( ! empty( $parts[1] ) ) : ?>
				<p class="sub"><?php echo $chunk( $parts[1] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped per chunk ?></p>
			<?php endif; ?>
		</div>
		<div class="foot">
			<img src="<?php echo esc_url( $theme . '/assets/img/brand/eabull-wordmark-light.webp' ); ?>" alt="">
			<?php if ( $cat || $is_page ) : ?>
				<span class="rule"></span>
				<p><?php echo esc_html( $is_page ? $art['meta']['category'] : $cat ); ?></p>
			<?php endif; ?>
		</div>
	</div>
	<div class="tile"><?php echo eab_icon( $icon ); // phpcs:ignore WordPress.Security.EscapeOutput -- trusted inline SVG ?></div>
<script>
// Shrink the title until no phrase wraps mid-word (down to 44px) and the block fits above the footer.
(function(){var t=document.getElementById('t'),h=t.querySelector('h1'),p=t.querySelector('.sub'),s=68;function set(){h.style.fontSize=s+'px';if(p){p.style.fontSize=Math.round(Math.max(s*.5,26))+'px';}}
function split(){var a=h.querySelectorAll('span');for(var i=0;i<a.length;i++){if(a[i].offsetHeight>s*1.8){return true;}}return false;}
function wide(){return h.scrollWidth>t.clientWidth+1||(p&&p.scrollWidth>t.clientWidth+1);}
function fit(){while((split()||wide())&&s>44){s-=2;set();}while(t.offsetHeight>400&&s>36){s-=2;set();}}
if(document.fonts&&document.fonts.ready){document.fonts.ready.then(fit);}fit();})();
</script>
</body>
</html>
