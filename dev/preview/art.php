<?php
/**
 * ภาพประกอบในเนื้อหา (ไม่ใช่ปก) · preview route /__art/<name>/?layout=wide|tall
 * Rendered by headless Chrome in dev/make-art.php → eabull/assets/img/illus/<file>.webp
 * Glass Sky · Kanit + Noto Sans Thai · ไม่มีตัวเลขผลเทรด ไม่มีเงา (ตามดีไซน์ของเว็บ)
 *
 * how-it-works: EA BULL ทำงานอย่างไร 4 ขั้น (กราฟ → ตรวจสิทธิ์ → ส่งคำสั่งเข้าบัญชีของคุณ → ติดตามจากมือถือ)
 * license-flow: เปิดสิทธิ์ (ส่งเลขบัญชี → 5 นาที → การ์ดเขียว → ผ่อนผัน 72 ชม.) · หน้า how-to-install
 * vps-flow: VPS ช่วยให้ EA ทำงานทั้งวัน · หน้า vps-windows / vps-android / vps-ios
 * account-flow: เปิดบัญชี MT5 4 ขั้น · หน้า open-mt5-account
 * แผนภาพเสาหลัก 8 กลุ่มบทความ (ea-loop, mt5-map, vps-path, gold-sessions, risk-layers, plan-cycle, monitor-rhythm, broker-checklist) อยู่ใน art-types.php
 *
 * @var string $fx_art_name set by router.php
 */

$arts = array(
	'how-it-works' => array(
		'title' => 'EA BULL ทำงานอย่างไร',
		'sub'   => 'จากกราฟใน MT5 ถึงบัญชีเทรดของคุณ ใน 4 ขั้น',
		'steps' => array(
			array( 'monitor', 'วางบนกราฟ XAUUSD M1', 'ใน MT5 บน Windows หรือ VPS ที่เปิดไว้ตลอด' ),
			array( 'lock', 'ตรวจสิทธิ์เลขบัญชี', 'การ์ดล็อกอินเป็นสีเขียวเมื่อบัญชีได้รับสิทธิ์' ),
			array( 'bolt', 'ส่งคำสั่งตามแผน', 'เข้าบัญชีโบรกเกอร์ที่เปิดไว้ในชื่อของคุณ' ),
			array( 'phone', 'ติดตามจากมือถือ', 'ดูออเดอร์และยอดบัญชีในแอป MT5 ได้ทุกที่' ),
		),
		'note'  => 'เงินทุนอยู่ในบัญชีของคุณเสมอ EA BULL ไม่รับฝากและไม่มีสิทธิ์ถอนเงิน',
	),
	'license-flow' => array(
		'title' => 'เปิดสิทธิ์ใช้งาน EA BULL',
		'sub'   => 'จากส่งเลขบัญชี จนการ์ดล็อกอินบนกราฟเป็นสีเขียว',
		'steps' => array(
			array( 'chat', 'ส่งเลขบัญชีทาง LINE', 'เลข Login ของ MT5 และชื่อเซิร์ฟเวอร์' ),
			array( 'clock', 'ทีมงานเปิดสิทธิ์', 'ใช้งานได้ภายในประมาณ 5 นาที' ),
			array( 'check', 'การ์ดล็อกอินสีเขียว', 'ตรวจสิทธิ์ผ่าน EA พร้อมทำงานบนกราฟ' ),
			array( 'shield', 'เน็ตหลุดยังทำงานต่อ', 'ได้ 72 ชั่วโมงนับจากครั้งล่าสุดที่ตรวจผ่าน' ),
		),
		'note'  => 'ส่งแค่เลขบัญชีกับชื่อเซิร์ฟเวอร์ ไม่ต้องส่งรหัสผ่านให้ใคร',
		'icon'  => 'lock',
	),
	'vps-flow' => array(
		'title' => 'VPS ช่วยให้ EA ทำงานได้ทั้งวัน',
		'sub'   => 'ปิดคอมที่บ้านได้ เพราะ MT5 เปิดอยู่บนเครื่องที่ไม่ดับ',
		'steps' => array(
			array( 'server', 'เช่า Windows VPS', 'คอมพิวเตอร์ในศูนย์ข้อมูลที่เปิดตลอด 24 ชั่วโมง' ),
			array( 'monitor', 'ลง MT5 และ EA บน VPS', 'ติ๊ก DLL แล้ววางบนกราฟ XAUUSD M1' ),
			array( 'phone', 'ต่อจากเครื่องไหนก็ได้', 'คอม Windows มือถือ Android หรือ iPhone' ),
			array( 'pulse', 'ปิดเครื่องของคุณได้', 'EA ยังทำงานบน VPS เปิดดูเมื่อไหร่ก็ได้' ),
		),
		'note'  => 'ปิดหน้าต่างที่ต่อ VPS ได้ แต่อย่าปิด MT5 อย่า Sign out และอย่า Shut down ตัว VPS',
		'icon'  => 'warn',
	),
	'account-flow' => array(
		'title' => 'เปิดบัญชี MT5 สำหรับใช้ EA',
		'sub'   => 'สี่ขั้นจากสมัครสมาชิก จนพร้อมขอเปิดสิทธิ์ EA BULL',
		'steps' => array(
			array( 'user', 'สมัครและยืนยันอีเมล', 'กรอกข้อมูล ยืนยันอีเมลและเบอร์โทร' ),
			array( 'check', 'ยืนยันตัวตน (KYC)', 'ส่งเอกสารในหน้าสมาชิก' ),
			array( 'candles', 'สร้างบัญชีเทรด MT5', 'จดเลข Login กับชื่อ Server' ),
			array( 'chat', 'ส่งเลขบัญชีให้ทีมงาน', 'ทาง LINE เพื่อขอเปิดสิทธิ์' ),
		),
		'note'  => 'บัญชีและเงินทุนอยู่ในชื่อของคุณเอง ทีมงานไม่ขอรหัสผ่านทุกกรณี',
		'icon'  => 'shield',
	),
);
require __DIR__ . '/art-types.php';
$arts = array_merge( $arts, eab_art_pillar_data() );
if ( ! isset( $arts[ $fx_art_name ] ) ) {
	http_response_code( 404 );
	echo 'No art: ' . htmlspecialchars( $fx_art_name );
	return;
}
$a      = $arts[ $fx_art_name ];
$tall   = isset( $_GET['layout'] ) && 'tall' === $_GET['layout']; // phpcs:ignore WordPress.Security.NonceVerification
$theme  = get_template_directory_uri();
$fonts  = $theme . '/assets/fonts/';
$thai   = 'U+02D7, U+0303, U+0331, U+0E01-0E5B, U+200C-200D, U+25CC';
$latin  = 'U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD';
$tones  = array( 'sky', 'gold', 'blue', 'navy' );
?>
<!doctype html>
<html lang="th">
<head>
<meta charset="utf-8">
<title><?php echo esc_html( $a['title'] ); ?></title>
<style>
<?php foreach ( array( 500, 600, 700 ) as $w ) : ?>
@font-face{font-family:"Kanit";font-weight:<?php echo (int) $w; ?>;src:url("<?php echo esc_url( $fonts . 'kanit-' . $w . '-thai.woff2' ); ?>") format("woff2");unicode-range:<?php echo esc_html( $thai ); ?>}
@font-face{font-family:"Kanit";font-weight:<?php echo (int) $w; ?>;src:url("<?php echo esc_url( $fonts . 'kanit-' . $w . '-latin.woff2' ); ?>") format("woff2");unicode-range:<?php echo esc_html( $latin ); ?>}
<?php endforeach; ?>
@font-face{font-family:"Noto Sans Thai";font-weight:300 800;src:url("<?php echo esc_url( $fonts . 'noto-sans-thai-thai.woff2' ); ?>") format("woff2");unicode-range:<?php echo esc_html( $thai ); ?>}
@font-face{font-family:"Noto Sans Thai";font-weight:300 800;src:url("<?php echo esc_url( $fonts . 'noto-sans-thai-latin.woff2' ); ?>") format("woff2");unicode-range:<?php echo esc_html( $latin ); ?>}
:root{--sky-0:#F7FAFE;--sky-1:#EEF4FC;--sky-2:#E2EDFB;--ink-2:#33415F;--muted:#5A6785;--blue:#0B5CAD;--navy:#0A2E66;--gold-1:#F6D57A;--gold-2:#EFC25A;--gold-3:#D9A12E;--gold-text:#8F620C}
*{box-sizing:border-box;margin:0;padding:0}
html,body{width:<?php echo $tall ? 800 : 1600; ?>px;height:<?php echo $tall ? 1140 : 760; ?>px;overflow:hidden}
body{position:relative;color:var(--navy);font-family:"Noto Sans Thai",sans-serif;-webkit-font-smoothing:antialiased;
	background:
		radial-gradient(40% 50% at 4% 4%,#CFE2F8,transparent 72%),
		radial-gradient(36% 44% at 97% 0%,#F6E6BA,transparent 72%),
		radial-gradient(46% 56% at 100% 96%,#D4E6FA,transparent 72%),
		linear-gradient(180deg,var(--sky-1),var(--sky-0) 50%,var(--sky-2))}
.wrap{position:absolute;inset:<?php echo $tall ? '44px 40px' : '52px 64px'; ?>;display:flex;flex-direction:column}
.head{text-align:center}
h1{font-family:"Kanit",sans-serif;font-size:<?php echo $tall ? 50 : 54; ?>px;font-weight:700;line-height:1.2;color:var(--navy)}
.sub{margin-top:10px;font-family:"Kanit",sans-serif;font-size:<?php echo $tall ? 28 : 28; ?>px;font-weight:500;color:var(--ink-2)}
.steps{display:flex;<?php echo $tall ? 'flex-direction:column;' : ''; ?>align-items:stretch;justify-content:center;gap:0;margin:<?php echo $tall ? "36px 0 28px" : "46px 0 28px"; ?>}
.step{position:relative;flex:1 1 0;display:flex;<?php echo $tall ? 'flex-direction:row;align-items:center;gap:26px;padding:26px 30px;' : 'flex-direction:column;align-items:center;text-align:center;gap:18px;padding:34px 26px 32px;'; ?>
	border:2px solid rgba(10,46,102,.12);border-radius:30px;background:rgba(255,255,255,.86)}
.num{position:absolute;<?php echo $tall ? 'left:-14px;top:-14px;' : 'left:50%;top:-24px;margin-left:-24px;'; ?>width:48px;height:48px;border-radius:50%;display:grid;place-items:center;background:var(--navy);color:#fff;font-family:"Kanit",sans-serif;font-size:24px;font-weight:600;border:4px solid #fff}
.tile{flex:0 0 auto;width:<?php echo $tall ? 104 : 118; ?>px;height:<?php echo $tall ? 104 : 118; ?>px;border-radius:32px;display:grid;place-items:center;border:2px solid rgba(255,255,255,.95)}
.tile svg{width:<?php echo $tall ? 54 : 60; ?>px;height:<?php echo $tall ? 54 : 60; ?>px;stroke-width:1.6}
.t-sky,.t-blue{background:var(--blue);color:#fff}
.t-gold{background:linear-gradient(145deg,#F6D57A,#EFC25A 55%,#D9A12E);color:var(--navy)}
.t-navy{background:var(--navy);color:var(--gold-1)}
.copy{display:flex;flex-direction:column;gap:8px}
.step h2{font-family:"Kanit",sans-serif;font-size:<?php echo $tall ? 34 : 31; ?>px;font-weight:600;line-height:1.25;color:var(--navy)}
.step p{font-size:<?php echo $tall ? 25 : 23; ?>px;line-height:1.5;color:var(--ink-2)}
.arrow{flex:0 0 auto;display:grid;place-items:center;<?php echo $tall ? 'height:44px;' : 'width:46px;'; ?>color:var(--gold-3)}
.arrow svg{width:34px;height:34px;stroke-width:2.6;<?php echo $tall ? 'transform:rotate(90deg);' : ''; ?>}
.note{margin-top:auto;display:flex;align-items:center;justify-content:center;gap:16px;padding:22px 28px;border-radius:24px;background:var(--navy);color:#fff;font-family:"Kanit",sans-serif;font-size:<?php echo $tall ? 26 : 27; ?>px;font-weight:500;line-height:1.4;text-align:<?php echo $tall ? 'left' : 'center'; ?>}
.note svg{flex:0 0 auto;width:38px;height:38px;color:var(--gold-1);stroke-width:1.8}
<?php echo eab_art_types_css(); // phpcs:ignore WordPress.Security.EscapeOutput -- static CSS ?>
</style>
</head>
<body class="<?php echo $tall ? 'is-tall' : 'is-wide'; ?>">
<div class="wrap">
	<div class="head">
		<h1><?php echo esc_html( $a['title'] ); ?></h1>
		<p class="sub"><?php echo esc_html( $a['sub'] ); ?></p>
	</div>
	<?php if ( ! empty( $a['type'] ) && 'steps' !== $a['type'] ) : ?>
		<?php echo eab_art_types_body( $a, $tall ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?>
	<?php else : ?>
	<div class="steps">
		<?php foreach ( $a['steps'] as $i => $s ) : ?>
			<?php if ( $i ) : ?>
				<div class="arrow"><?php echo eab_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput -- trusted SVG ?></div>
			<?php endif; ?>
			<div class="step">
				<span class="num"><?php echo (int) ( $i + 1 ); ?></span>
				<span class="tile t-<?php echo esc_attr( $tones[ $i % 4 ] ); ?>"><?php echo eab_icon( $s[0] ); // phpcs:ignore WordPress.Security.EscapeOutput -- trusted SVG ?></span>
				<div class="copy">
					<h2><?php echo esc_html( $s[1] ); ?></h2>
					<p><?php echo esc_html( $s[2] ); ?></p>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
	<?php endif; ?>
	<?php if ( ! empty( $a['note'] ) ) : ?>
	<p class="note"><?php echo eab_icon( isset( $a['icon'] ) ? $a['icon'] : 'shield' ); // phpcs:ignore WordPress.Security.EscapeOutput -- trusted SVG ?><span><?php echo esc_html( $a['note'] ); ?></span></p>
	<?php endif; ?>
</div>
</body>
</html>
