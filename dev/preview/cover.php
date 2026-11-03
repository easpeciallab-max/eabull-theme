<?php
/**
 * Article cover card (1200×630) · preview route /__cover/<article-slug>/
 * Page cover card (1200×630) · preview route /__cover/page-<page-slug>/ → covers/page-<slug>.webp (featured image + og:image ของเพจ)
 * Rendered by headless Chrome in dev/make-covers.php and saved to eabull/assets/img/covers/<slug>.webp
 * Style: EA BULL "Glass Sky" (docs/design.md) · sky gradient + blue/gold glows, white glass card,
 * EA BULL wordmark, category kicker, Kanit title in navy, icon on a gold or sky tile.
 * Text only: no numbers, no results. Not part of the theme.
 *
 * @var string $fx_cover_slug set by router.php
 */

/* ปกของเพจ: หัวเรื่อง: คำขยาย | ป้ายไทย | ไอคอน | สี (ไม่มีตัวเลข ไม่มีป้ายอังกฤษ) */
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
// category (the 10 article clusters, eab_article_categories()) => English kicker + tile colour
$cats = array(
	'บอทเทรดทอง'        => array( 'Gold Bot', 'gold', 'robot' ),
	'รัน EA 24 ชั่วโมง'  => array( 'Run 24/7', 'sky', 'server' ),
	'ทดสอบและปรับค่า EA' => array( 'Testing', 'blue', 'flask' ),
	'เทรดอย่างปลอดภัย'   => array( 'Safety', 'sky', 'shield' ),
	'พื้นฐานเทรดทอง'     => array( 'Gold Basics', 'gold', 'target' ),
	'ทุนและขนาดไม้'      => array( 'Capital & Lot', 'blue', 'wallet' ),
	'จัดการออเดอร์'      => array( 'Orders', 'sky', 'sliders' ),
	'กลยุทธ์เทรดทอง'     => array( 'Strategy', 'gold', 'flag' ),
	'ราคาทองโลก'        => array( 'Gold Market', 'blue', 'globe' ),
	'อ่านกราฟทองคำ'      => array( 'Chart Reading', 'sky', 'candles' ),
);
$cat     = isset( $art['meta']['category'] ) ? $art['meta']['category'] : '';
$kicker  = isset( $cats[ $cat ] ) ? $cats[ $cat ][0] : 'Article';
$variant = isset( $cats[ $cat ] ) ? $cats[ $cat ][1] : 'sky';
$icon    = isset( $icons[ $fx_cover_slug ] ) ? $icons[ $fx_cover_slug ] : ( isset( $cats[ $cat ][2] ) ? $cats[ $cat ][2] : 'book' );
if ( $is_page ) {
	$kicker  = '';
	$variant = $pc[3];
	$icon    = $pc[2];
}
$theme   = get_template_directory_uri();
$fonts   = $theme . '/assets/fonts/';

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
		$out[] = '<span>' . esc_html( $w ) . '</span>';
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
<?php foreach ( array( 500, 600, 700 ) as $w ) : ?>
@font-face{font-family:"Kanit";font-weight:<?php echo (int) $w; ?>;src:url("<?php echo esc_url( $fonts . 'kanit-' . $w . '-thai.woff2' ); ?>") format("woff2");unicode-range:<?php echo esc_html( $thai ); ?>}
@font-face{font-family:"Kanit";font-weight:<?php echo (int) $w; ?>;src:url("<?php echo esc_url( $fonts . 'kanit-' . $w . '-latin.woff2' ); ?>") format("woff2");unicode-range:<?php echo esc_html( $latin ); ?>}
<?php endforeach; ?>
@font-face{font-family:"Noto Sans Thai";font-weight:300 800;src:url("<?php echo esc_url( $fonts . 'noto-sans-thai-thai.woff2' ); ?>") format("woff2");unicode-range:<?php echo esc_html( $thai ); ?>}
@font-face{font-family:"Noto Sans Thai";font-weight:300 800;src:url("<?php echo esc_url( $fonts . 'noto-sans-thai-latin.woff2' ); ?>") format("woff2");unicode-range:<?php echo esc_html( $latin ); ?>}
:root{--sky-0:#F7FAFE;--sky-1:#EEF4FC;--sky-2:#E2EDFB;--ink-2:#33415F;--muted:#5A6785;--blue:#0B5CAD;--blue-2:#1A6DC2;--navy:#0A2E66;--gold-1:#F6D57A;--gold-2:#EFC25A;--gold-3:#D9A12E;--gold-text:#8F620C}
*{box-sizing:border-box;margin:0;padding:0}
html,body{width:1200px;height:630px;overflow:hidden}
body{position:relative;color:var(--navy);font-family:"Noto Sans Thai",sans-serif;-webkit-font-smoothing:antialiased;
	background:
		radial-gradient(40% 50% at 4% 4%,#BCD7F6,transparent 72%),
		radial-gradient(36% 44% at 97% 0%,#F4DFA6,transparent 72%),
		radial-gradient(46% 56% at 100% 96%,#C4DCF8,transparent 72%),
		radial-gradient(42% 50% at 0% 100%,rgba(244,223,166,.9),transparent 72%),
		linear-gradient(180deg,var(--sky-1),var(--sky-0) 45%,var(--sky-2))}
.lines{position:absolute;inset:0;width:1200px;height:630px;opacity:.7}
.orb{position:absolute;border-radius:50%;
	background:
		radial-gradient(circle at 32% 28%,rgba(255,255,255,.95) 0 8%,transparent 26%),
		radial-gradient(circle at 70% 75%,rgba(239,194,90,.7),transparent 55%),
		radial-gradient(circle at 50% 50%,rgba(11,92,173,.62),rgba(120,170,230,.55) 60%,rgba(207,227,251,.7) 100%);
	box-shadow:inset -10px -14px 30px rgba(11,92,173,.18),inset 8px 10px 20px rgba(255,255,255,.7),0 20px 40px rgba(10,46,102,.12)}
.orb-1{width:150px;height:150px;right:-38px;top:-44px}
.orb-2{width:92px;height:92px;left:22px;bottom:18px}
.card{position:absolute;left:48px;top:44px;width:1104px;height:542px;border-radius:36px;
	background:linear-gradient(160deg,rgba(255,255,255,.88),rgba(255,255,255,.66));
	border:1.5px solid rgba(255,255,255,.95);
	box-shadow:0 24px 60px rgba(10,46,102,.12),inset 0 1px 0 rgba(255,255,255,.9);
	-webkit-backdrop-filter:blur(18px) saturate(140%);backdrop-filter:blur(18px) saturate(140%)}
.txt{position:absolute;left:64px;top:52px;width:640px;height:438px;display:flex;flex-direction:column}
.kicker{display:flex;align-items:center;gap:14px;font-family:"Kanit",sans-serif;font-size:21px;line-height:1;font-weight:600;white-space:nowrap}
.kicker b{font-weight:600;letter-spacing:.16em;text-transform:uppercase;color:var(--blue)}
.kicker b:before{content:"";display:inline-block;width:12px;height:12px;margin:0 14px 2px 0;border-radius:50%;background:linear-gradient(135deg,var(--blue-2),var(--blue));box-shadow:0 0 0 5px rgba(11,92,173,.12);vertical-align:middle}
.kicker i{width:5px;height:5px;border-radius:50%;background:var(--gold-3)}
.kicker em{font-style:normal;font-weight:500;color:var(--ink-2)}
.ttl{margin:auto 0;padding:14px 0 18px}
h1{font-family:"Kanit",sans-serif;font-size:66px;line-height:1.24;font-weight:700;color:var(--navy);letter-spacing:-.005em}
.sub{margin-top:.4em;font-family:"Kanit",sans-serif;font-size:36px;line-height:1.36;font-weight:500;color:var(--ink-2)}
.ttl span{display:inline-block}
.foot{margin-top:auto;display:flex;align-items:center;gap:22px}
.foot img{height:78px;width:auto;display:block}
.foot .rule{width:2px;height:46px;border-radius:2px;background:linear-gradient(180deg,var(--gold-1),var(--gold-3))}
.foot p{font-family:"Kanit",sans-serif;font-size:21px;line-height:1.35;font-weight:500;color:var(--muted)}
.foot p strong{display:block;font-weight:600;color:var(--navy)}
.art{position:absolute;right:62px;top:50%;width:300px;height:300px;margin-top:-150px}
.halo{position:absolute;inset:-48px;border-radius:50%;background:radial-gradient(circle,rgba(160,198,242,.75) 30%,transparent 68%)}
.v-gold .halo{background:radial-gradient(circle,rgba(239,200,110,.6) 30%,transparent 68%)}
.ring{position:absolute;inset:-12px;border-radius:72px;border:2px dashed rgba(217,161,46,.45)}
.v-sky .ring,.v-blue .ring{border-color:rgba(11,92,173,.22)}
.tile{position:absolute;inset:22px;border-radius:60px;display:flex;align-items:center;justify-content:center;border:2px solid rgba(255,255,255,.95);box-shadow:0 22px 46px rgba(10,46,102,.14),inset 0 2px 0 rgba(255,255,255,.8)}
.v-gold .tile{background:linear-gradient(145deg,#FFF6DE,#F6DE9E 55%,#EFC25A);color:var(--gold-text)}
.v-sky .tile{background:linear-gradient(145deg,#F3F8FE,#D4E6FB 55%,#B9D5F7);color:var(--blue)}
.v-blue .tile{background:linear-gradient(145deg,#E6F1FD,#B9D5F7 60%,#8DBBEE);color:var(--navy)}
.tile svg{width:124px;height:124px;stroke-width:1.5}
.spark{position:absolute;width:62px;height:62px;right:-18px;bottom:-14px;border-radius:20px;display:flex;align-items:center;justify-content:center;background:linear-gradient(180deg,var(--gold-1),var(--gold-2) 45%,var(--gold-3));box-shadow:0 12px 26px rgba(187,131,31,.3);border:2px solid rgba(255,255,255,.9)}
.v-gold .spark{background:linear-gradient(180deg,#123B7E,var(--navy) 60%,#011D3B);box-shadow:0 12px 26px rgba(10,46,102,.3)}
.spark svg{width:30px;height:30px;color:var(--navy);stroke-width:2.2;transform:rotate(-45deg)}
.v-gold .spark svg{color:var(--gold-1)}
</style>
</head>
<body class="v-<?php echo esc_attr( $variant ); ?>">
	<svg class="lines" viewBox="0 0 1200 630" preserveAspectRatio="none" aria-hidden="true">
		<defs><linearGradient id="wl" x1="0" x2="1"><stop offset="0" stop-color="#FFFFFF" stop-opacity="0"/><stop offset=".5" stop-color="#FFFFFF" stop-opacity=".95"/><stop offset="1" stop-color="#EFC25A" stop-opacity=".45"/></linearGradient></defs>
		<path d="M-40 520C220 420 440 500 640 420S980 230 1240 260" fill="none" stroke="url(#wl)" stroke-width="2.4"/>
		<path d="M-40 565C240 470 470 545 680 465S1010 285 1240 310" fill="none" stroke="url(#wl)" stroke-width="1.6"/>
		<path d="M-40 610C260 520 500 590 720 510S1040 340 1240 360" fill="none" stroke="url(#wl)" stroke-width="1.1"/>
	</svg>
	<div class="orb orb-1"></div>
	<div class="orb orb-2"></div>
	<div class="card">
		<div class="txt">
			<p class="kicker"><?php if ( '' !== $kicker ) : ?><b><?php echo esc_html( $kicker ); ?></b><?php if ( $cat ) : ?><i></i><?php endif; ?><?php endif; ?><?php if ( $cat ) : ?><em><?php echo esc_html( $cat ); ?></em><?php endif; ?></p>
			<div class="ttl" id="t">
				<h1><?php echo $chunk( $parts[0] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped per chunk ?></h1>
				<?php if ( ! empty( $parts[1] ) ) : ?>
					<p class="sub"><?php echo $chunk( $parts[1] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped per chunk ?></p>
				<?php endif; ?>
			</div>
			<div class="foot">
				<img src="<?php echo esc_url( $theme . '/assets/img/brand/eabull-wordmark-dark.webp' ); ?>" alt="">
				<span class="rule"></span>
				<p><strong>บินอย่างมีแบบแผน</strong>วางแผน · ติดตาม · บริหารความเสี่ยง</p>
			</div>
		</div>
		<div class="art">
			<div class="halo"></div>
			<div class="ring"></div>
			<div class="tile"><?php echo eab_icon( $icon ); // phpcs:ignore WordPress.Security.EscapeOutput -- trusted inline SVG ?></div>
			<div class="spark"><?php echo eab_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput -- trusted inline SVG ?></div>
		</div>
	</div>
<script>
// Shrink the title until no phrase wraps mid-word (down to 46px) and the block fits between kicker and footer.
(function(){var t=document.getElementById('t'),h=t.querySelector('h1'),p=t.querySelector('.sub'),s=66;function set(){h.style.fontSize=s+'px';if(p){p.style.fontSize=Math.round(Math.max(s*.55,28))+'px';}}
function split(){var a=h.querySelectorAll('span');for(var i=0;i<a.length;i++){if(a[i].offsetHeight>s*1.8){return true;}}return false;}
function wide(){return h.scrollWidth>t.clientWidth+1||(p&&p.scrollWidth>t.clientWidth+1);}
function fit(){while((split()||wide())&&s>46){s-=2;set();}while(t.offsetHeight>300&&s>38){s-=2;set();}}
if(document.fonts&&document.fonts.ready){document.fonts.ready.then(fit);}fit();})();
</script>
</body>
</html>
