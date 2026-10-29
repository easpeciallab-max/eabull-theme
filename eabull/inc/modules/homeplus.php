<?php
/**
 * EA BULL · ตัวพิมพ์ส่วนต่าง ๆ ของหน้าแรก (front-page.php เรียก eab_homeplus_section() ตามลำดับ)
 *
 * markup และคลาสตรงกับต้นแบบ dev/mockup/_src/pages/home.html (style.css ใช้ชื่อคลาสเดียวกัน)
 * hook ของ main.js: แท็บ [data-tabs] · เช็กลิสต์ [data-ready] · ลิงก์ที่ยังไม่มี [data-wait] · ปุ่ม LINE data-line-pos
 * ค่าเริ่มต้นและหมวด Customizer อยู่ใน inc/modules/home.php · ส่วนสินค้าอยู่ใน inc/modules/products.php
 *
 * @package eabull
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * พิมพ์ส่วนตามชื่อ เมื่อเปิดไว้ใน Customizer
 */
function eab_homeplus_section( $name ) {
	$toggles = array(
		'hero'     => 'show_hero',
		'plat'     => 'home_show_plat',
		'intro'    => 'show_home_intro',
		'plans'    => 'show_home_plans',
		'hub'      => 'show_home_hub',
		'learn'    => 'show_home_learn',
		'products' => 'show_home_products',
		'modes'    => 'show_home_modes',
		'features' => 'show_features',
		'steps'    => 'show_steps',
		'compare'  => 'show_home_compare',
		'ready'    => 'show_home_ready',
		'faq'      => 'show_faq',
		'risk'     => 'show_risk',
		'articles' => 'show_home_articles',
	);
	$fn = 'eab_homeplus_' . $name;
	if ( ! function_exists( $fn ) ) {
		return;
	}
	if ( isset( $toggles[ $name ] ) && ! eab_mod( $toggles[ $name ] ) ) {
		if ( 'hero' === $name ) {
			/* ปิด hero แล้วยังมี H1 ให้โปรแกรมอ่านหน้าจอและเสิร์ชเอนจิน */
			echo '<h1 class="sr-only">' . esc_html( eab_home_plain( eab_mod( 'home_hero_h1' ) ) ) . '</h1>';
		}
		return;
	}
	call_user_func( $fn );
}

/* ==============================================================
 * Hero · หัววัว H1 คำขวัญ คำอธิบาย ปุ่ม คำเตือน 3 เสาหลัก (จัดกลาง)
 * ============================================================== */
function eab_homeplus_hero() {
	$img     = eab_theme_asset_url( eab_mod( 'home_hero_img' ) );
	$size    = eab_home_img_size( eab_mod( 'home_hero_img' ), 640, 571 );
	$tag     = trim( (string) eab_mod( 'home_hero_tag' ) );
	$lead    = trim( (string) eab_mod( 'home_hero_lead' ) );
	$risk    = trim( (string) eab_mod( 'home_hero_risk' ) );
	$btn1    = trim( (string) eab_mod( 'home_hero_btn1_text' ) );
	$btn1_to = eab_home_link( eab_mod( 'home_hero_btn1_url' ) );
	$btn2    = trim( (string) eab_mod( 'home_hero_btn2_text' ) );
	$btn2_to = eab_home_link( eab_mod( 'home_hero_btn2_url' ) );
	$pillars = eab_home_rows( eab_mod( 'home_pillars' ) );
	?>
<section class="hero" aria-labelledby="hero-title">
	<?php echo eab_homeplus_hero_chart(); // phpcs:ignore WordPress.Security.EscapeOutput -- static markup ?>
	<div class="wrap">
		<?php if ( '' !== $img ) : ?>
		<img class="hero__emblem" src="<?php echo esc_url( $img ); ?>" width="<?php echo (int) $size[0]; ?>" height="<?php echo (int) $size[1]; ?>" alt="<?php echo esc_attr( eab_mod( 'home_hero_img_alt' ) ); ?>" fetchpriority="high" decoding="async">
		<?php endif; ?>
		<h1 id="hero-title"><?php echo eab_home_inline( eab_mod( 'home_hero_h1' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></h1>
		<?php if ( '' !== $tag ) : ?>
		<p class="hero__tag"><?php echo eab_home_inline( $tag ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></p>
		<?php endif; ?>
		<?php if ( '' !== $lead ) : ?>
		<p class="lead"><?php echo eab_home_inline( $lead ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></p>
		<?php endif; ?>
		<?php if ( ( '' !== $btn1 && '' !== $btn1_to ) || ( '' !== $btn2 && '' !== $btn2_to ) ) : ?>
		<div class="btns">
			<?php if ( '' !== $btn1 && '' !== $btn1_to ) : ?>
			<a class="btn btn--primary btn--lg" href="<?php echo esc_url( $btn1_to ); ?>"><?php echo esc_html( $btn1 ); ?> <?php echo eab_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
			<?php endif; ?>
			<?php if ( '' !== $btn2 && '' !== $btn2_to ) : ?>
			<a class="btn btn--outline btn--lg" href="<?php echo esc_url( $btn2_to ); ?>"><?php echo esc_html( $btn2 ); ?></a>
			<?php endif; ?>
		</div>
		<?php endif; ?>
		<?php if ( '' !== $risk ) : ?>
		<p class="hero__risk"><?php echo eab_icon( 'alert' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo eab_home_inline( $risk ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></span></p>
		<?php endif; ?>
		<?php if ( $pillars ) : ?>
		<div class="pillars" data-reveal>
			<?php foreach ( $pillars as $pillar ) : ?>
			<div class="pillar">
				<span class="pillar__ic"><?php echo eab_icon( ! empty( $pillar[2] ) ? $pillar[2] : 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				<div><b><?php echo esc_html( $pillar[0] ); ?></b><?php if ( ! empty( $pillar[1] ) ) : ?><span><?php echo esc_html( $pillar[1] ); ?></span><?php endif; ?></div>
			</div>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
	</div>
</section>
	<?php
}

/* ==============================================================
 * แถบแพลตฟอร์มสีเหลืองเต็มจอ
 * ============================================================== */
function eab_homeplus_plat() {
	$items = eab_lines( eab_mod( 'home_plat_items' ) );
	if ( ! $items ) {
		return;
	}
	$label = trim( (string) eab_mod( 'home_plat_label' ) );
	?>
<div class="plat">
	<div class="wrap plat__in">
		<?php if ( '' !== $label ) : ?>
		<span class="plat__label"><?php echo esc_html( $label ); ?></span>
		<?php endif; ?>
		<ul class="plat__list">
		<?php foreach ( $items as $item ) : ?>
			<?php
			/* ชื่อที่ขึ้นต้นด้วยตัวเล็ก เช่น iPhone / iPad ไม่ถูกแปลงเป็นตัวพิมพ์ใหญ่ (.nocaps) */
			$text = preg_match( '/\bi[A-Z]/', $item ) ? '<span class="nocaps">' . esc_html( $item ) . '</span>' : esc_html( $item );
			?>
			<li><?php echo eab_icon( eab_home_plat_icon( $item ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo $text; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above ?></li>
		<?php endforeach; ?>
		</ul>
	</div>
</div>
	<?php
}

/* ==============================================================
 * โหมด · แท็บเต็มความกว้าง + ตารางสเปก ([data-tabs] · main.js)
 * ============================================================== */
function eab_homeplus_modes() {
	$c     = eab_home_counts();
	$modes = array();
	for ( $m = 1; $m <= $c['modes']; $m++ ) {
		$name = trim( (string) eab_mod( 'home_mode' . $m . '_name' ) );
		if ( '' !== $name ) {
			$modes[ $m ] = $name;
		}
	}
	if ( ! $modes ) {
		return;
	}
	$rows = array();
	for ( $r = 1; $r <= $c['rows']; $r++ ) {
		$label = trim( (string) eab_mod( 'home_modes_row' . $r ) );
		if ( '' !== $label ) {
			$rows[ $r ] = $label;
		}
	}
	$empty = trim( (string) eab_mod( 'home_modes_tag_empty' ) );
	$warn  = trim( (string) eab_mod( 'home_modes_warn' ) );
	$first = (int) key( $modes );
	?>
<section class="band band--alt" id="modes" aria-labelledby="modes-title">
	<div class="wrap">
		<?php eab_home_shead( 'modes-title', eab_mod( 'home_modes_title' ), eab_mod( 'home_modes_sub' ) ); ?>
		<div class="tabs" data-tabs>
			<div class="tabs__list" role="tablist" aria-label="<?php echo esc_attr( eab_mod( 'home_modes_aria' ) ); ?>">
				<?php foreach ( $modes as $m => $name ) : ?>
					<?php $tag = trim( (string) eab_mod( 'home_mode' . $m . '_tag' ) ); ?>
				<button class="tabs__btn" role="tab" id="mode-t<?php echo (int) $m; ?>" aria-controls="mode-p<?php echo (int) $m; ?>" aria-selected="<?php echo $first === $m ? 'true' : 'false'; ?>" type="button"><b><?php echo esc_html( $name ); ?></b><span><?php echo esc_html( '' !== $tag ? $tag : $empty ); ?></span></button>
				<?php endforeach; ?>
			</div>
			<?php foreach ( $modes as $m => $name ) : ?>
			<div class="tabs__panel" role="tabpanel" id="mode-p<?php echo (int) $m; ?>" aria-labelledby="mode-t<?php echo (int) $m; ?>"<?php echo $first === $m ? '' : ' hidden'; ?>>
				<div class="mode">
					<div>
						<h3><?php echo esc_html( $name ); ?></h3>
						<p><?php echo eab_home_pending( eab_mod( 'home_mode' . $m . '_text' ), eab_mod( 'home_modes_text_hint' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></p>
						<?php if ( '' !== $warn ) : ?>
						<p class="note note--warn mt-24"><?php echo eab_icon( 'alert' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo eab_home_inline( $warn ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></span></p>
						<?php endif; ?>
					</div>
					<?php if ( $rows ) : ?>
					<table class="spec-tbl">
						<tbody>
							<?php foreach ( $rows as $r => $label ) : ?>
							<tr><th scope="row"><?php echo esc_html( $label ); ?></th><td><?php echo eab_wait( eab_mod( 'home_mode' . $m . '_row' . $r ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></td></tr>
							<?php endforeach; ?>
						</tbody>
					</table>
					<?php endif; ?>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
	<?php
}

/* ==============================================================
 * สิ่งที่ EA BULL ทำแทนคุณ · ตาราง 2x2 แบ่งด้วยเส้น เลขลำดับใหญ่
 * ============================================================== */
function eab_homeplus_features() {
	$c     = eab_home_counts();
	$items = array();
	for ( $i = 1; $i <= $c['feat']; $i++ ) {
		$title = trim( (string) eab_mod( 'home_feat' . $i . '_title' ) );
		if ( '' === $title ) {
			continue;
		}
		$items[] = array(
			'title' => $title,
			'text'  => trim( (string) eab_mod( 'home_feat' . $i . '_text' ) ),
			'link'  => trim( (string) eab_mod( 'home_feat' . $i . '_link' ) ),
			'url'   => eab_home_link( eab_mod( 'home_feat' . $i . '_url' ) ),
		);
	}
	if ( ! $items ) {
		return;
	}
	?>
<section class="band" id="features" aria-labelledby="features-title">
	<div class="wrap">
		<?php eab_home_shead( 'features-title', eab_mod( 'home_feat_title' ), eab_mod( 'home_feat_sub' ) ); ?>
		<div class="feat" data-reveal>
			<?php foreach ( $items as $i => $item ) : ?>
			<div class="feat__item">
				<span class="feat__n"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
				<div>
					<h3><?php echo eab_home_inline( $item['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></h3>
					<?php if ( '' !== $item['text'] ) : ?>
					<p><?php echo eab_home_inline( $item['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></p>
					<?php endif; ?>
					<?php if ( '' !== $item['link'] && '' !== $item['url'] ) : ?>
					<a class="tlink" href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['link'] ); ?> <?php echo eab_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
					<?php endif; ?>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
	<?php
}

/* ==============================================================
 * 6 ขั้นตอน · รางขั้นตอน (.track) · ลิงก์ line = ปุ่มทัก LINE
 * ============================================================== */
function eab_homeplus_steps() {
	$c     = eab_home_counts();
	$steps = array();
	for ( $i = 1; $i <= $c['steps']; $i++ ) {
		$title = trim( (string) eab_mod( 'home_step' . $i . '_title' ) );
		if ( '' === $title ) {
			continue;
		}
		$steps[] = array(
			'title' => $title,
			'text'  => trim( (string) eab_mod( 'home_step' . $i . '_text' ) ),
			'link'  => trim( (string) eab_mod( 'home_step' . $i . '_link' ) ),
			'url'   => trim( (string) eab_mod( 'home_step' . $i . '_url' ) ),
		);
	}
	if ( ! $steps ) {
		return;
	}
	?>
<section class="band band--alt" id="steps" aria-labelledby="steps-title">
	<div class="wrap">
		<?php eab_home_shead( 'steps-title', eab_mod( 'home_steps_title' ), eab_mod( 'home_steps_sub' ), eab_mod( 'home_steps_more_text' ), eab_home_link( eab_mod( 'home_steps_more_url' ) ) ); ?>
		<ol class="track">
			<?php foreach ( $steps as $i => $step ) : ?>
			<li>
				<span class="track__n"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
				<h3><?php echo eab_home_inline( $step['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></h3>
				<?php if ( '' !== $step['text'] ) : ?>
				<p><?php echo eab_home_inline( $step['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></p>
				<?php endif; ?>
				<?php
				if ( '' !== $step['link'] ) {
					if ( 'line' === strtolower( $step['url'] ) ) {
						eab_home_contact_link( $step['link'], 'tlink', 'home-steps', '', 'arrow-right' );
					} else {
						$url = eab_home_link( $step['url'] );
						if ( '' !== $url ) {
							echo '<a class="tlink" href="' . esc_url( $url ) . '">' . esc_html( $step['link'] ) . ' ' . eab_icon( 'arrow-right' ) . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput
						}
					}
				}
				?>
			</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
	<?php
}

/* ==============================================================
 * ตารางเทียบ · แถบขาว (.paper) คอลัมน์สุดท้าย (EA BULL) = .us
 * ช่องที่ขึ้นต้นด้วย + / - / ! = เครื่องหมายถูก กากบาท เตือน
 * ============================================================== */
function eab_homeplus_compare() {
	$rows = eab_home_rows( eab_mod( 'home_compare_rows' ), 2 );
	if ( count( $rows ) < 2 ) {
		return;
	}
	$head = array_shift( $rows );
	$cols = count( $head );
	$last = $cols - 1;
	$note = trim( (string) eab_mod( 'home_compare_note' ) );
	$cell = function ( $text ) {
		$marks = array(
			'+' => array( 'y', 'check' ),
			'-' => array( 'n', 'x' ),
			'!' => array( 'm', 'alert' ),
		);
		$first = substr( $text, 0, 1 );
		if ( isset( $marks[ $first ] ) && ' ' === substr( $text, 1, 1 ) ) {
			return '<span class="yn yn--' . $marks[ $first ][0] . '">' . eab_icon( $marks[ $first ][1] ) . '<span>' . esc_html( trim( substr( $text, 1 ) ) ) . '</span></span>';
		}
		return esc_html( $text );
	};
	?>
<section class="band paper" id="compare" aria-labelledby="compare-title">
	<div class="wrap">
		<?php eab_home_shead( 'compare-title', eab_mod( 'home_compare_title' ), eab_mod( 'home_compare_sub' ) ); ?>
		<div class="tbl-wrap">
			<table class="tbl tbl--stack">
				<thead>
					<tr>
						<?php foreach ( $head as $c => $label ) : ?>
						<th scope="col"<?php echo $c === $last && $c > 0 ? ' class="us"' : ''; ?>><?php echo esc_html( $label ); ?></th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $rows as $row ) : ?>
					<tr>
						<?php foreach ( array_slice( array_pad( $row, $cols, '' ), 0, $cols ) as $c => $text ) : ?>
							<?php if ( 0 === $c ) : ?>
						<th scope="row"><?php echo esc_html( $text ); ?></th>
							<?php else : ?>
						<td data-label="<?php echo esc_attr( $head[ $c ] ); ?>"<?php echo $c === $last ? ' class="us"' : ''; ?>><?php echo $cell( $text ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></td>
							<?php endif; ?>
						<?php endforeach; ?>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php if ( '' !== $note ) : ?>
		<p class="note note--info mt-16"><?php echo eab_icon( 'info' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo eab_home_inline( $note ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></span></p>
		<?php endif; ?>
	</div>
</section>
	<?php
}

/* ==============================================================
 * เช็กลิสต์ความพร้อม · [data-ready] (main.js) แท่งความคืบหน้า 1 แท่งต่อ 1 ข้อ
 * ============================================================== */
function eab_homeplus_ready() {
	$items = eab_home_rows( eab_mod( 'home_ready_items' ) );
	if ( ! $items ) {
		return;
	}
	$total = count( $items );
	$left  = (string) eab_mod( 'home_ready_left' );
	$of    = trim( str_replace( '{total}', (string) $total, (string) eab_mod( 'home_ready_of' ) ) );
	$done  = trim( (string) eab_mod( 'home_ready_done' ) );
	?>
<section class="band" id="ready" aria-labelledby="ready-title">
	<div class="wrap">
		<?php eab_home_shead( 'ready-title', eab_mod( 'home_ready_title' ), eab_mod( 'home_ready_sub' ) ); ?>
		<div class="ready" data-ready data-left="<?php echo esc_attr( $left ); ?>" data-full="<?php echo esc_attr( eab_mod( 'home_ready_full' ) ); ?>">
			<ul class="ready__list">
				<?php foreach ( $items as $item ) : ?>
				<li><label class="ready__item"><input class="box" type="checkbox"><span><b><?php echo esc_html( $item[0] ); ?></b><?php if ( ! empty( $item[1] ) ) : ?><small><?php echo eab_home_inline( $item[1] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></small><?php endif; ?></span></label></li>
				<?php endforeach; ?>
			</ul>
			<div class="ready__meter" aria-live="polite">
				<div class="meter" aria-hidden="true"><?php echo str_repeat( '<i></i>', $total ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
				<p class="ready__count"><span data-ready-count>0</span><?php echo '' !== $of ? '<small> ' . esc_html( $of ) . '</small>' : ''; ?></p>
				<p class="ready__status" data-ready-status><?php echo esc_html( str_replace( '{n}', (string) $total, $left ) ); ?></p>
				<div class="ready__done" data-ready-done hidden>
					<?php if ( '' !== $done ) : ?>
					<p><?php echo eab_home_inline( $done ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></p>
					<?php endif; ?>
					<?php eab_home_contact_link( eab_mod( 'home_ready_btn' ), 'btn btn--line btn--block', 'home-ready' ); ?>
				</div>
			</div>
		</div>
	</div>
</section>
	<?php
}

/* ==============================================================
 * FAQ · หัวข้อติดหนึบซ้าย คำถามคอลัมน์เดียวขวา (details.qa · schema ใน inc/seo.php)
 * ============================================================== */
function eab_homeplus_faq() {
	$faqs = eab_home_faqs();
	if ( ! $faqs ) {
		return;
	}
	$sub = trim( (string) eab_mod( 'home_faq_sub' ) );
	?>
<section class="band paper" id="faq" aria-labelledby="faq-title">
	<div class="wrap faq">
		<div class="faq__head">
			<h2 id="faq-title"><?php echo eab_home_bars() . eab_home_inline( eab_mod( 'home_faq_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></h2>
			<?php if ( '' !== $sub ) : ?>
			<p><?php echo eab_home_inline( $sub ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></p>
			<?php endif; ?>
			<div class="btns"><?php eab_home_contact_link( eab_mod( 'home_faq_btn' ), 'btn btn--line', 'home-faq' ); ?></div>
		</div>
		<div>
			<?php foreach ( $faqs as $i => $faq ) : ?>
			<details class="qa"<?php echo 0 === $i ? ' open' : ''; ?>>
				<summary><?php echo esc_html( $faq['q'] ); ?><span class="faq-plus" aria-hidden="true"></span></summary>
				<div class="qa__a">
					<?php foreach ( preg_split( '/\n\s*\n/', $faq['a'] ) as $para ) : ?>
						<?php if ( '' !== trim( $para ) ) : ?>
					<p><?php echo eab_home_inline( trim( $para ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></p>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			</details>
			<?php endforeach; ?>
		</div>
	</div>
</section>
	<?php
}

/* ==============================================================
 * กล่องความเสี่ยง · 2 คอลัมน์ ข้อความเต็ม ไม่พับ ไม่ตัด
 * ============================================================== */
function eab_homeplus_risk() {
	$items = eab_lines( eab_mod( 'home_risk_items' ) );
	$title = trim( (string) eab_mod( 'home_risk_title' ) );
	if ( ! $items && '' === $title ) {
		return;
	}
	$cols = $items ? array_chunk( $items, (int) ceil( count( $items ) / 2 ) ) : array();
	$btn  = trim( (string) eab_mod( 'home_risk_btn' ) );
	$url  = eab_home_link( '/risk-disclosure/' );
	?>
<section class="band" id="risk"<?php echo '' !== $title ? ' aria-labelledby="risk-title"' : ''; ?>>
	<div class="wrap">
		<div class="riskbox">
			<?php if ( '' !== $title ) : ?>
			<h2 id="risk-title"><?php echo eab_icon( 'alert' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo eab_home_inline( $title ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></h2>
			<?php endif; ?>
			<?php if ( $cols ) : ?>
			<div class="riskbox__cols">
				<?php foreach ( $cols as $col ) : ?>
				<ul>
					<?php foreach ( $col as $line ) : ?>
					<li><?php echo eab_icon( 'alert' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo eab_home_inline( $line ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></span></li>
					<?php endforeach; ?>
				</ul>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>
			<?php if ( '' !== $btn && '' !== $url ) : ?>
			<div class="btns"><a class="btn btn--outline" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $btn ); ?> <?php echo eab_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a></div>
			<?php endif; ?>
		</div>
	</div>
</section>
	<?php
}

/* ==============================================================
 * บทความล่าสุด · การ์ดจริงจาก eab_post_card() · ไม่มีบทความเผยแพร่ = ไม่แสดงทั้งส่วน (ห้ามโพสต์ตัวอย่าง)
 * ============================================================== */
function eab_homeplus_articles() {
	if ( ! class_exists( 'WP_Query' ) || ! function_exists( 'eab_post_card' ) ) {
		return;
	}
	$count = (int) eab_mod( 'home_articles_count' );
	$query = new WP_Query(
		array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => $count >= 6 ? 6 : 3,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);
	if ( ! $query->have_posts() ) {
		return;
	}
	$page_id = (int) get_option( 'page_for_posts' );
	$archive = $page_id ? get_permalink( $page_id ) : eab_home_link( '/articles/' );
	?>
<section class="band band--alt" id="latest" aria-labelledby="latest-title">
	<div class="wrap">
		<?php eab_home_shead( 'latest-title', eab_mod( 'home_articles_title' ), eab_mod( 'home_articles_sub' ), eab_mod( 'home_articles_more' ), $archive ); ?>
		<div class="posts">
			<?php
			while ( $query->have_posts() ) {
				$query->the_post();
				eab_post_card();
			}
			?>
		</div>
	</div>
</section>
	<?php
	wp_reset_postdata();
}

/* ==============================================================
 * รู้จัก EA BULL · ย่อหน้าแนะนำ + กล่องข้อมูลสำคัญ (เจ้าของขอเนื้อหาหน้าแรกเพิ่ม 29 ต.ค. 2026)
 * ============================================================== */
function eab_homeplus_intro() {
	$title = trim( (string) eab_mod( 'home_intro_title' ) );
	$paras = array_filter( array_map( 'trim', preg_split( '/\n\s*\n/', (string) eab_mod( 'home_intro_text' ) ) ) );
	$facts = eab_home_rows( str_replace( '{plans}', eab_homeplus_plans_line(), (string) eab_mod( 'home_intro_facts' ) ), 2 );
	if ( '' === $title && ! $paras ) {
		return;
	}
	$facts_title = trim( (string) eab_mod( 'home_intro_facts_title' ) );
	?>
<section class="band band--alt" id="about-ea-bull" aria-labelledby="intro-title">
	<div class="wrap intro">
		<div class="intro__text">
			<h2 id="intro-title"><?php echo eab_home_bars() . eab_home_inline( $title ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></h2>
			<?php foreach ( $paras as $para ) : ?>
			<p><?php echo eab_homeplus_nocaps( eab_home_inline( preg_replace( '/\s*\n\s*/', ' ', $para ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></p>
			<?php endforeach; ?>
		</div>
		<?php if ( $facts ) : ?>
		<aside class="intro__facts cut" aria-labelledby="intro-facts-title">
			<?php if ( '' !== $facts_title ) : ?>
			<h3 id="intro-facts-title"><?php echo esc_html( $facts_title ); ?></h3>
			<?php endif; ?>
			<dl class="facts">
				<?php foreach ( $facts as $fact ) : ?>
				<div><dt><?php echo esc_html( $fact[0] ); ?></dt><dd><?php echo eab_homeplus_nocaps( eab_home_inline( $fact[1] ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></dd></div>
				<?php endforeach; ?>
			</dl>
		</aside>
		<?php endif; ?>
	</div>
</section>
	<?php
}

/**
 * ชื่อและราคาแพ็กเกจในบรรทัดเดียว เช่น "Starter ฟรี · Pro 6,990 บาท · VIP 9,990 บาท" (จากหมวดแพ็กเกจ)
 */
function eab_homeplus_plans_line() {
	$parts = array();
	foreach ( eab_homeplus_plan_list() as $plan ) {
		$parts[] = trim( $plan['name'] . ' ' . $plan['price'] . ( preg_match( '/\d/', $plan['price'] ) ? ' บาท' : '' ) );
	}
	return implode( ' · ', $parts );
}

/**
 * แพ็กเกจ 3 ตัวจาก Customizer หมวดแพ็กเกจ (ข้ามตัวที่ไม่มีชื่อ)
 *
 * @return array[] { name, price, period, tag, featured }
 */
function eab_homeplus_plan_list() {
	$out = array();
	for ( $i = 1; $i <= 3; $i++ ) {
		$name = trim( (string) eab_mod( 'pkg' . $i . '_name' ) );
		if ( '' === $name ) {
			continue;
		}
		$out[] = array(
			'name'     => $name,
			'price'    => trim( (string) eab_mod( 'pkg' . $i . '_price' ) ),
			'period'   => trim( (string) eab_mod( 'pkg' . $i . '_period' ) ),
			'tag'      => trim( (string) eab_mod( 'pkg' . $i . '_tag' ) ),
			'featured' => (bool) eab_mod( 'pkg' . $i . '_featured' ),
		);
	}
	return $out;
}

/* ==============================================================
 * แพ็กเกจและราคา · การ์ดย่อ 3 ใบ ลิงก์ไปหน้าแพ็กเกจ
 * ============================================================== */
function eab_homeplus_plans() {
	$plans = eab_homeplus_plan_list();
	$url   = eab_home_link( '/pricing/' );
	if ( ! $plans || '' === $url ) {
		return;
	}
	$btn  = trim( (string) eab_mod( 'home_plans_btn' ) );
	$flag = trim( (string) eab_mod( 'pr_flag_label' ) );
	?>
<section class="band" id="plans" aria-labelledby="plans-title">
	<div class="wrap">
		<?php eab_home_shead( 'plans-title', eab_mod( 'home_plans_title' ), eab_mod( 'home_plans_sub' ), eab_mod( 'home_plans_more' ), $url ); ?>
		<div class="plans" data-reveal>
			<?php foreach ( $plans as $plan ) : ?>
			<div class="plan<?php echo $plan['featured'] ? ' plan--hot' : ''; ?>">
				<?php if ( $plan['featured'] && '' !== $flag ) : ?>
				<span class="plan__flag"><?php echo esc_html( $flag ); ?></span>
				<?php endif; ?>
				<h3><?php echo esc_html( $plan['name'] ); ?></h3>
				<p class="plan__price"><b><?php echo esc_html( $plan['price'] ); ?></b><?php if ( '' !== $plan['period'] ) : ?> <span><?php echo esc_html( $plan['period'] ); ?></span><?php endif; ?></p>
				<?php if ( '' !== $plan['tag'] ) : ?>
				<p class="plan__tag"><?php echo esc_html( $plan['tag'] ); ?></p>
				<?php endif; ?>
				<?php if ( '' !== $btn ) : ?>
				<a class="btn <?php echo $plan['featured'] ? 'btn--primary' : 'btn--outline'; ?> btn--block" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $btn ); ?> <?php echo eab_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
				<?php endif; ?>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
	<?php
}

/* ==============================================================
 * คู่มือและเครื่องมือทั้งหมด · การ์ดลิงก์ (ไอคอน | /slug/ | ชื่อ | คำอธิบาย)
 * ============================================================== */
function eab_homeplus_hub() {
	$items = array();
	foreach ( eab_home_rows( eab_mod( 'home_hub_items' ), 3 ) as $row ) {
		$url = eab_home_link( $row[1] );
		if ( '' === $url ) {
			continue; /* เพจที่ยังไม่เผยแพร่ไม่ขึ้น */
		}
		$items[] = array(
			'icon'  => '' !== $row[0] ? $row[0] : 'book',
			'url'   => $url,
			'title' => $row[2],
			'text'  => isset( $row[3] ) ? $row[3] : '',
		);
	}
	if ( ! $items ) {
		return;
	}
	?>
<section class="band band--alt" id="guides-hub" aria-labelledby="hub-title">
	<div class="wrap">
		<?php eab_home_shead( 'hub-title', eab_mod( 'home_hub_title' ), eab_mod( 'home_hub_sub' ) ); ?>
		<ul class="hub" data-reveal>
			<?php foreach ( $items as $item ) : ?>
			<li><a class="hub__item" href="<?php echo esc_url( $item['url'] ); ?>">
				<span class="tile"><?php echo eab_icon( $item['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				<span class="hub__text"><b><?php echo eab_homeplus_nocaps( esc_html( $item['title'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></b><?php if ( '' !== $item['text'] ) : ?><span><?php echo eab_homeplus_nocaps( esc_html( $item['text'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></span><?php endif; ?></span>
				<?php echo eab_icon( 'arrow-right', 'hub__go' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</a></li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
	<?php
}

/* ==============================================================
 * EA บน MT5 คืออะไร · บทความสั้นสำหรับคนเริ่มต้น (### หัวข้อย่อย · - รายการ · เว้นบรรทัด = ย่อหน้า)
 * ============================================================== */
function eab_homeplus_learn() {
	$body = trim( (string) eab_mod( 'home_learn_body' ) );
	if ( '' === $body ) {
		return;
	}
	?>
<section class="band" id="ea-basics" aria-labelledby="learn-title">
	<div class="wrap">
		<?php eab_home_shead( 'learn-title', eab_mod( 'home_learn_title' ), eab_mod( 'home_learn_sub' ) ); ?>
		<div class="learn"><?php echo eab_homeplus_nocaps( eab_homeplus_prose( $body ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></div>
	</div>
</section>
	<?php
}

/**
 * ข้อความหลายย่อหน้าจาก Customizer → HTML (h3 / ul / p) ที่ escape แล้ว
 */
function eab_homeplus_prose( $text ) {
	$html = '';
	foreach ( preg_split( '/\n\s*\n/', str_replace( "\r", '', (string) $text ) ) as $block ) {
		$lines = array_values( array_filter( array_map( 'trim', explode( "\n", $block ) ), 'strlen' ) );
		while ( $lines && 0 === strpos( $lines[0], '### ' ) ) {
			$html .= '<h3>' . eab_home_inline( substr( array_shift( $lines ), 4 ) ) . '</h3>';
		}
		if ( ! $lines ) {
			continue;
		}
		if ( 0 === strpos( $lines[0], '- ' ) ) {
			$html .= '<ul>';
			foreach ( $lines as $line ) {
				$html .= '<li>' . eab_home_inline( ltrim( substr( $line, 0 === strpos( $line, '- ' ) ? 2 : 0 ) ) ) . '</li>';
			}
			$html .= '</ul>';
			continue;
		}
		$html .= '<p>' . eab_home_inline( implode( ' ', $lines ) ) . '</p>';
	}
	return $html;
}

/**
 * คำที่ขึ้นต้นด้วยตัวเล็กแบบ iPhone / iPad ไม่ถูกแปลงเป็นตัวพิมพ์ใหญ่ (.nocaps) · แตะเฉพาะข้อความนอกแท็ก
 */
function eab_homeplus_nocaps( $html ) {
	return preg_replace_callback(
		'/(<[^>]+>)|\bi[A-Z][A-Za-z]*/',
		function ( $m ) {
			return isset( $m[1] ) && '' !== $m[1] ? $m[1] : '<span class="nocaps">' . $m[0] . '</span>';
		},
		(string) $html
	);
}

/**
 * แท่งกราฟขาขึ้นด้านหลังหัววัว (ตกแต่ง · aria-hidden) · ขยับช้า ๆ ด้วย CSS เมื่อเครื่องไม่ได้ตั้งลดการเคลื่อนไหว
 * ความสูงคำนวณตายตัว (แนวโน้มขึ้น + แกว่งเล็กน้อย) ไม่สุ่ม เพื่อให้ทุกครั้งที่โหลดหน้าเหมือนกัน
 */
function eab_homeplus_hero_chart() {
	$n    = 28;
	$bars = '';
	for ( $i = 0; $i < $n; $i++ ) {
		$h     = 22 + 70 * $i / ( $n - 1 ) + 11 * sin( $i * 1.7 ) + 6 * cos( $i * 0.9 );
		$h     = max( 12, min( 100, (int) round( $h ) ) );
		$bars .= '<i style="--h:' . $h . ';--d:' . number_format( fmod( $i * 0.37, 3.2 ), 2, '.', '' ) . '"></i>';
	}
	return '<div class="hero__chart" aria-hidden="true">' . $bars . '</div>';
}
