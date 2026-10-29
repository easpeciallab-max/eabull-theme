<?php
/**
 * EA BULL · รายการสินค้าทั้งหมด เรียงตามเลขรุ่น (เจ้าของขอ 21 ต.ค. 2026 "จัดลำดับตามเลข")
 *
 * - eab_products(): WING NITRO (nitro_version) · EA BULL (ea_version) · WING TWIN (twin_version) เรียงจากเลขรุ่นน้อยไปมาก
 *   เปลี่ยนเลขรุ่นใน Customizer เมื่อไหร่ ลำดับในหน้าแรก /go และเมนูที่สร้างจาก Setup เปลี่ยนตาม
 * - eab_products_page( $prefix ): โครงหน้าสินค้า (template-nitro.php · template-eabull.php · template-twin.php)
 * - eab_homeplus_products(): ส่วน "EA ทองคำของ EA BULL" ในหน้าแรก การ์ดละ 1 สินค้า (home_{key}_*)
 * - eab_go_product_downloads(): การ์ดดาวน์โหลดทุกสินค้าในขั้น 4 ของหน้า /go (go_fast_* · go_nitro_* · go_twin_*)
 *
 * @package eabull
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * สินค้าทั้งหมด เรียงตามเลขรุ่น
 *
 * @return array[] { key, name, file (ชื่อไฟล์ไม่มีนามสกุล), version, slug (เพจสินค้า), go (prefix การ์ด /go), pos, bt }
 */
function eab_products() {
	$items = array(
		array(
			'key'     => 'nitro',
			'name'    => 'WING NITRO',
			'version' => function_exists( 'eab_nitro_version' ) ? eab_nitro_version() : '4.2',
			'prefix'  => 'WING_NITRO_V',
			'slug'    => 'wing-nitro',
			'go'      => 'go_nitro',
			'pos'     => 'go-download-nitro',
			'bt'      => array( 'nitro_bt_from', 'nitro_bt_to' ),
		),
		array(
			'key'     => 'eabull',
			'name'    => 'EA BULL',
			'version' => eab_ea_version(),
			'prefix'  => 'EA_BULL_V',
			'slug'    => 'ea-wing',
			'go'      => 'go_fast',
			'pos'     => 'go-download',
			'bt'      => array( 'ea_bt_from', 'ea_bt_to' ),
		),
		array(
			'key'     => 'twin',
			'name'    => 'WING TWIN',
			'version' => function_exists( 'eab_twin_version' ) ? eab_twin_version() : '6.3',
			'prefix'  => 'WING_TWIN_V',
			'slug'    => 'wing-twin',
			'go'      => 'go_twin',
			'pos'     => 'go-download-twin',
			'bt'      => array(),
		),
	);
	foreach ( $items as $i => $item ) {
		$items[ $i ]['file']  = $item['prefix'] . $item['version'];
		$items[ $i ]['label'] = $item['name'] . ' ' . $item['version'];
		$items[ $i ]['order'] = $i;
	}
	usort(
		$items,
		static function ( $a, $b ) {
			$cmp = version_compare( $a['version'], $b['version'] );
			return 0 !== $cmp ? $cmp : $a['order'] - $b['order'];
		}
	);
	return (array) apply_filters( 'eab_products', $items );
}

/**
 * สินค้าตาม key ('nitro' | 'eabull' | 'twin') · ไม่พบ = null
 */
function eab_product( $key ) {
	foreach ( eab_products() as $item ) {
		if ( $item['key'] === $key ) {
			return $item;
		}
	}
	return null;
}

/**
 * ช่วง Backtest มาตรฐานของสินค้า ("2026.01.01 ถึง 2026.08.31") · ไม่มี = ''
 */
function eab_product_bt_range( $item ) {
	if ( empty( $item['bt'] ) ) {
		return '';
	}
	$from = trim( (string) eab_mod( $item['bt'][0] ) );
	$to   = trim( (string) eab_mod( $item['bt'][1] ) );
	return '' !== $from && '' !== $to ? $from . ' ถึง ' . $to : '';
}

/* ==============================================================
 * หน้าสินค้า (template-nitro.php · template-eabull.php · template-twin.php)
 * โครง (Glass Sky): หัวเพจ (ชื่อ คำอธิบาย ปุ่ม LINE ปุ่มดาวน์โหลด ภาพการ์ดสินค้า)
 *       → แผงการ์ดข้อมูล ({prefix}_stat1..8 · หมายเหตุ · คำเตือน) → เนื้อหายาวจาก editor → แถบติดต่อ
 * ============================================================== */

function eab_products_page( $prefix ) {
	get_header();

	if ( have_posts() ) {
		the_post();
	}
	$sub     = eab_mod( $prefix . '_sub' );
	$note    = eab_mod( $prefix . '_note' );
	$actions = eab_pages_capture(
		'eab_contact_button',
		array(
			'class' => 'btn btn-fire',
			'pos'   => $prefix . '-hero',
		)
	);
	$dl      = eab_theme_asset_url( eab_mod( $prefix . '_dl_url' ) );
	if ( '' !== $dl && '' !== trim( (string) eab_mod( $prefix . '_dl_label' ) ) ) {
		$actions .= sprintf(
			'<a class="btn btn-ghost" href="%1$s"%2$s data-go-pos="%3$s-download">%4$s<span>%5$s</span></a>',
			esc_url( $dl ),
			preg_match( '/\.(zip|ex5|pdf)([?#]|$)/i', $dl ) ? ' download' : '',
			esc_attr( $prefix ),
			eab_icon( 'download' ),
			esc_html( eab_mod( $prefix . '_dl_label' ) )
		);
	}
	?>

<main id="main" class="pg tests-page product-page product-page--<?php echo esc_attr( $prefix ); ?>">

	<?php
	eab_pages_hero(
		array(
			'title'   => get_the_title(),
			'lead'    => eab_pages_has( $sub ) ? $sub : '',
			'actions' => $actions,
			'media'   => eab_pages_hero_media( $prefix, '' ),
		)
	);
	?>

	<section class="pg-sec tests-top">
		<div class="container">
			<div class="glass-panel pg-panel tests-panel">
				<?php eab_pages_stats( $prefix, 8, $prefix ); ?>
				<div class="tests-notes<?php echo eab_pages_has( $note ) ? '' : ' tests-notes--solo'; ?>">
					<?php if ( eab_pages_has( $note ) ) : ?>
						<p class="pg-note tests-note"><span class="tile tile--sky" aria-hidden="true"><?php echo eab_icon( 'cpu' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span><span><?php echo esc_html( $note ); ?></span></p>
					<?php endif; ?>
					<?php /* คำเตือน · ไม่ใช้ .reveal เพื่อให้เห็นทันทีแม้ JS ไม่ทำงาน */ ?>
					<div class="pg-warn tests-disclaimer">
						<span class="tile tile--gold" aria-hidden="true"><?php echo eab_icon( 'warn' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<p><?php echo esc_html( eab_mod( $prefix . '_disclaimer' ) ); ?></p>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php
	eab_pages_enable_table_cards();
	eab_pages_longform();
	eab_line_cta( eab_mod( $prefix . '_cta_title' ), eab_mod( $prefix . '_cta_text' ) );
	?>

</main>

	<?php
	get_footer();
}

/* ==============================================================
 * หน้าแรก [products] · การ์ดสินค้าเรียงตามเลขรุ่น (home_{key}_* ของแต่ละสินค้า)
 * ============================================================== */

add_filter( 'eab_defaults', 'eab_products_defaults' );

function eab_products_defaults( $d ) {
	return array_merge(
		$d,
		array(
			'show_home_products'  => true,
			'home_products_title' => 'EA ทองคำของ EA BULL ทั้ง 3 ตัว',
			'home_products_sub'   => 'เรียงตามเลขรุ่น ทั้งสามตัวเทรดทองคำ XAUUSD บน MT5 แต่ทำงานคนละวิธี ใช้บัญชีและเงินทุนต่างกัน แพ็กเกจเดียวใช้ได้ทั้ง 3 ตัว เลือกตัวที่เข้ากับบัญชีของคุณ หรือถามทีมงานทาง LINE ก่อนก็ได้',
			'home_products_note'  => 'ทั้งสามตัวไม่มีการตัดขาดทุนอัตโนมัติ บัญชีเสียหายหนักได้ถึงเสียเงินทั้งหมด · ผลทดสอบย้อนหลังไม่ใช่สัญญาว่าจะได้กำไร · ห้ามรันสองตัวบนทองตัวเดียวกันในบัญชีเดียวกัน',

			/* ---------- หน้า /ea-wing/ (ข้อเท็จจริงจากเอกสาร EA BULL 5.0 ของเจ้าของ · หลักข้อ 2 ใน CLAUDE.md) ---------- */
			'eabull_sub'          => 'EA เทรดทองคำ XAUUSD อัตโนมัติบน MT5 มีสามโหมด Lite Advance Full ในไฟล์เดียว เปิดออเดอร์เป็นชุด ราคาสวนทางเติมไม้แก้เป็นขั้นบันไดสูงสุด 9 ขั้น แล้วปิดทั้งชุดเมื่อถึงเป้ากำไร โหมดสำเร็จรูปไม่มีการตัดขาดทุนอัตโนมัติ อ่านความเสี่ยงให้ครบก่อนใช้เงินจริง',
			'eabull_hero_img'     => 'assets/img/products/ea-wing-card-v2.webp',
			'eabull_dl_url'       => 'assets/downloads/EA_BULL_V5.0.zip',
			'eabull_dl_label'     => 'ดาวน์โหลด EA BULL V5.0',
			'eabull_stat1_label'  => 'โหมดในตัว',
			'eabull_stat1_value'  => 'Lite · Advance · Full',
			'eabull_stat2_label'  => 'กราฟ',
			'eabull_stat2_value'  => 'XAUUSD · M1',
			'eabull_stat3_label'  => 'ไม้แก้',
			'eabull_stat3_value'  => 'สูงสุด 9 ขั้น',
			'eabull_stat4_label'  => 'เงินในบัญชีที่แนะนำ',
			'eabull_stat4_value'  => '1 / 2 / 3 เท่าของทุนคิดไม้',
			'eabull_stat5_label'  => 'ทุนแนะนำ บัญชีเซนต์',
			'eabull_stat5_value'  => 'ราว 1,000 USD ขึ้นไป',
			'eabull_stat6_label'  => 'ทุนแนะนำ บัญชีมาตรฐาน',
			'eabull_stat6_value'  => 'ราว 100,000 USD ขึ้นไป',
			'eabull_stat7_label'  => 'หลบข่าวแดง USD',
			'eabull_stat7_value'  => 'ก่อน 90 · หลัง 720 นาที',
			'eabull_stat8_label'  => 'ตัดขาดทุนอัตโนมัติ',
			'eabull_stat8_value'  => 'ไม่มีในโหมดสำเร็จรูป',
			'eabull_note'         => 'ทุนคิดไม้คือตัวเลขที่ EA ใช้คิดขนาดออเดอร์ เงินในบัญชีที่เกินทุนคิดไม้คือเงินกันชน · Lite 1 เท่า Advance 2 เท่า Full 3 เท่าของทุนคิดไม้ · แพ็กเกจเดียวใช้ได้ทั้ง 3 ตัว · ห้ามรันคู่กับไม้มือหรือ EA ตัวอื่นบนทองตัวเดียวกันในบัญชีเดียวกัน',
			'eabull_disclaimer'   => 'ระบบทบไม้ โหมดสำเร็จรูปไม่มีการตัดขาดทุนอัตโนมัติ บัญชีเสียหายหนักได้ถึงเสียเงินทั้งหมด · ผลทดสอบย้อนหลังไม่ใช่ผลการเทรดจริงและไม่รับประกันผลในอนาคต',
			'eabull_cta_title'    => 'สนใจ EA BULL หรืออยากรู้ว่าโหมดไหนเข้ากับเงินในบัญชี',
			'eabull_cta_text'     => 'แพ็กเกจเดียวใช้ได้ทั้ง 3 ตัว ทักทีมงานทาง LINE ถามเรื่องโหมดและเงินในบัญชี แล้วส่งเลขบัญชี MT5 กับชื่อเซิร์ฟเวอร์เพื่อขอเปิดสิทธิ์ใช้งาน',

			/* การ์ด EA BULL ในส่วนสินค้าหน้าแรก */
			'home_eabull_name'    => 'EA BULL',
			'home_eabull_heading' => 'สามโหมด Lite Advance Full',
			'home_eabull_text'    => 'เปิดออเดอร์เป็นชุด ราคาสวนทางเติมไม้แก้ที่ใหญ่ขึ้นเป็นขั้นบันไดสูงสุด 9 ขั้น แล้วปิดทั้งชุดเมื่อถึงเป้ากำไร เลือกระดับความเสี่ยงได้สามโหมดจากแดชบอร์ด',
			'home_eabull_points'  => "เงินในบัญชีที่แนะนำ 1 / 2 / 3 เท่าของทุนคิดไม้ ตามโหมด Lite Advance Full\nทุนแนะนำ: บัญชีเซนต์ราว 1,000 USD ขึ้นไป · บัญชีมาตรฐานราว 100,000 USD ขึ้นไป\nโหมดสำเร็จรูปไม่มีการตัดขาดทุนอัตโนมัติ",
			'home_eabull_img'     => 'assets/img/products/ea-wing-go-card-v2.webp',
			'home_eabull_img_alt' => 'การ์ดสินค้า EA BULL Expert Advisor สำหรับ MT5',
			'home_eabull_btn'     => 'ดูรายละเอียด EA BULL',
			'home_eabull_url'     => '/ea-wing/',
		)
	);
}

add_filter( 'eab_customizer_sections', 'eab_products_customizer_sections', 12, 2 );

function eab_products_customizer_sections( $sections, $d ) {
	$rule                      = 'ใช้ข้อเท็จจริงจากเอกสาร EA BULL 5.0 ของเจ้าของ · ต้องบอกว่าไม่มีการตัดขาดทุนอัตโนมัติ';
	$stats                     = array();
	for ( $i = 1; $i <= 8; $i++ ) {
		$stats[ 'eabull_stat' . $i . '_label' ] = array( 'การ์ดข้อมูล ' . $i . ' · หัวข้อ (ว่าง = ซ่อน)', 'text' );
		$stats[ 'eabull_stat' . $i . '_value' ] = array( 'การ์ดข้อมูล ' . $i . ' · ค่า', 'text' );
	}
	$sections['eab_eabull']    = array(
		'title'       => '34.5) สินค้า EA BULL (หน้า /ea-wing/)',
		'description' => 'เนื้อหายาวของหน้าอยู่ที่ inc/content/pages/ea-wing.html (EA BULL Setup) · ชื่อไฟล์และเลขรุ่นมาจากหมวด 29 · การ์ด /go ใช้ go_fast_* ในหมวด /go',
		'fields'      => array_merge(
			array(
				'eabull_sub'      => array( 'หน้าสินค้า · คำอธิบายใต้ชื่อ', 'textarea', $rule ),
				'eabull_hero_img' => array( 'หน้าสินค้า · ภาพการ์ดสินค้าในหัวเพจ', 'image' ),
				'eabull_dl_url'   => array( 'หน้าสินค้า · ลิงก์ดาวน์โหลดไฟล์ zip (ว่าง = ไม่มีปุ่ม)', 'path' ),
				'eabull_dl_label' => array( 'หน้าสินค้า · ข้อความปุ่มดาวน์โหลด', 'text' ),
			),
			$stats,
			array(
				'eabull_note'       => array( 'หน้าสินค้า · หมายเหตุใต้การ์ดข้อมูล', 'textarea' ),
				'eabull_disclaimer' => array( 'หน้าสินค้า · คำเตือนใต้การ์ดข้อมูล (ห้ามลบ)', 'textarea' ),
				'eabull_cta_title'  => array( 'หน้าสินค้า · แถบติดต่อท้ายหน้า · หัวข้อ', 'text' ),
				'eabull_cta_text'   => array( 'หน้าสินค้า · แถบติดต่อท้ายหน้า · คำอธิบาย', 'textarea' ),
			)
		),
	);
	$sections['eab_products']  = array(
		'title'       => '37) หน้าแรก · ส่วนสินค้า (เรียงตามเลขรุ่น)',
		'description' => 'การ์ดสินค้าเรียงตามเลขรุ่นเอง (WING NITRO · EA BULL · WING TWIN) · ข้อความการ์ด WING NITRO อยู่หมวด 35 · WING TWIN อยู่หมวด 36 · การ์ด EA BULL อยู่ที่นี่',
		'fields'      => array(
			'show_home_products'  => array( 'แสดงส่วนสินค้าในหน้าแรก', 'checkbox' ),
			'home_products_title' => array( 'หัวข้อส่วน (H2)', 'text' ),
			'home_products_sub'   => array( 'คำอธิบายใต้หัวข้อ', 'textarea' ),
			'home_products_note'  => array( 'คำเตือนใต้การ์ด (ห้ามลบ)', 'textarea' ),
			'home_eabull_name'    => array( 'การ์ด EA BULL · ชื่อ (เลขรุ่นต่อท้ายให้เอง)', 'text' ),
			'home_eabull_heading' => array( 'การ์ด EA BULL · หัวข้อย่อย', 'text' ),
			'home_eabull_text'    => array( 'การ์ด EA BULL · คำอธิบาย', 'textarea', $rule ),
			'home_eabull_points'  => array( 'การ์ด EA BULL · จุดสำคัญ (บรรทัดละ 1 ข้อ)', 'textarea', $rule ),
			'home_eabull_img'     => array( 'การ์ด EA BULL · ภาพ (2:1)', 'image' ),
			'home_eabull_img_alt' => array( 'การ์ด EA BULL · คำอธิบายภาพ (alt)', 'text' ),
			'home_eabull_btn'     => array( 'การ์ด EA BULL · ข้อความปุ่ม', 'text' ),
			'home_eabull_url'     => array( 'การ์ด EA BULL · ลิงก์ปุ่ม', 'path', 'slug ในเว็บ เช่น /ea-wing/' ),
		),
	);
	return $sections;
}

function eab_homeplus_products() {
	$cards = '';
	foreach ( eab_products() as $item ) {
		$p    = 'home_' . $item['key'];
		$name = trim( (string) eab_mod( $p . '_name' ) );
		if ( '' === $name ) {
			continue;
		}
		$img    = eab_theme_asset_url( eab_mod( $p . '_img' ) );
		$points = eab_lines( eab_mod( $p . '_points' ) );
		$url    = eab_home_link( eab_mod( $p . '_url' ) );
		ob_start();
		?>
		<article class="hp-prod hp-prod--<?php echo esc_attr( $item['key'] ); ?>">
			<?php if ( '' !== $img ) : ?>
				<?php $info = eab_media_info( $img, 960, 480 ); ?>
				<span class="hp-prod-shot">
					<img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( trim( (string) eab_mod( $p . '_img_alt' ) ) ); ?>" width="<?php echo (int) $info['width']; ?>" height="<?php echo (int) $info['height']; ?>" loading="lazy" decoding="async">
				</span>
			<?php endif; ?>
			<div class="hp-prod-copy">
				<h3 class="hp-prod-name"><?php echo esc_html( $name ); ?> <span class="hp-prod-ver"><?php echo esc_html( $item['version'] ); ?></span></h3>
				<?php if ( '' !== trim( (string) eab_mod( $p . '_heading' ) ) ) : ?>
					<p class="hp-prod-tag"><?php echo esc_html( eab_mod( $p . '_heading' ) ); ?></p>
				<?php endif; ?>
				<?php if ( '' !== trim( (string) eab_mod( $p . '_text' ) ) ) : ?>
					<p><?php echo esc_html( eab_mod( $p . '_text' ) ); ?></p>
				<?php endif; ?>
				<?php if ( $points ) : ?>
					<ul class="hp-checks">
						<?php foreach ( $points as $point ) : ?>
							<li><?php echo eab_icon( 'check', 'icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo esc_html( $point ); ?></span></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
				<?php if ( '' !== $url && '' !== trim( (string) eab_mod( $p . '_btn' ) ) ) : ?>
					<div class="hp-actions"><?php eab_home_btn( eab_mod( $p . '_btn' ), $url, 'btn btn-ghost hm-btn hm-btn--sm', 'arrow-ur' ); ?></div>
				<?php endif; ?>
			</div>
		</article>
		<?php
		$cards .= ob_get_clean();
	}
	if ( '' === $cards ) {
		return;
	}
	$warn = trim( (string) eab_mod( 'home_products_note' ) );
	?>
<section class="glass-panel hm-panel hp-products" id="products" aria-labelledby="hp-products-title">
	<?php eab_homeplus_head( 'hp-products-title', eab_mod( 'home_products_title' ), eab_mod( 'home_products_sub' ) ); ?>
	<div class="hp-prod-grid">
		<?php echo $cards; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above ?>
	</div>
	<?php if ( '' !== $warn ) : ?>
		<p class="hm-note"><?php echo eab_icon( 'warn', 'icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo esc_html( $warn ); ?></span></p>
	<?php endif; ?>
</section>
	<?php
}

/* ==============================================================
 * หน้า /go ขั้น 4 · การ์ดดาวน์โหลดทุกสินค้าตามลำดับเลขรุ่น
 * ใบแรกไม่มีกรอบคั่น ใบถัดไปห่อด้วย .lh-product (เส้นคั่นบน) · มีปุ่มเล็ก {go}_info_label / {go}_info_url ได้
 * ============================================================== */

function eab_go_product_downloads() {
	$html  = '';
	$first = true;
	foreach ( eab_products() as $item ) {
		$card = eab_go_download_card( $item['go'], $item['pos'] );
		if ( '' === $card ) {
			continue;
		}
		$card .= eab_go_button( 'lh-mini-btn', eab_go_mod( $item['go'] . '_info_label' ), eab_go_mod( $item['go'] . '_info_url' ), 'book' );
		$html .= $first ? $card : '<div class="lh-product">' . $card . '</div>';
		$first = false;
	}
	return $html;
}
