<?php
/**
 * EA BULL · กลุ่มบทความ (เสาหลัก + บทความเสริม) · docs/content-plan.md
 *
 * - หมวดบทความ = กลุ่ม · แต่ละหมวดมีบทความเสาหลัก 1 บท (eab_cluster_pillars())
 * - ท้ายบทความแสดงกล่อง "บทความในชุดนี้" อัตโนมัติ (เฉพาะบทความที่เผยแพร่แล้ว):
 *   บทความเสริม = ลิงก์ขึ้นเสาหลัก + บทความพี่น้องล่าสุด · เสาหลัก = รายการบทความเสริมทั้งหมดของกลุ่ม
 * - หน้าหมวดหน้าแรก: เสาหลักขึ้นเป็นบทความแรก
 * - ข้อความทุกคำเป็น setting ในหมวด Customizer "บทความ · กล่องชุดบทความ"
 *
 * @package eabull
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * slug หมวด => slug บทความเสาหลัก
 */
function eab_cluster_pillars() {
	return (array) apply_filters(
		'eab_cluster_pillars',
		array(
			'ea-basics'       => 'what-is-ea',
			'mt5'             => 'mt5-guide',
			'vps'             => 'vps-for-ea',
			'gold-trading'    => 'xauusd-guide',
			'risk-management' => 'money-management',
			'trading-plan'    => 'trading-plan-for-ea',
			'monitoring'      => 'monitoring-guide',
			'broker-account'  => 'choose-forex-broker',
		)
	);
}

function eab_cluster_defaults( $d ) {
	$d['show_cluster_box']        = true;
	$d['cluster_box_title']       = 'บทความในชุด {cat}';
	$d['cluster_pillar_label']    = 'เริ่มอ่านจากบทความหลักของชุดนี้';
	$d['cluster_pillar_title']    = 'บทความทั้งหมดในชุด {cat}';
	$d['cluster_more_label']      = 'ดูบทความทั้งหมดในหมวด {cat}';
	$d['cluster_sibling_count']   = 6;
	return $d;
}
add_filter( 'eab_defaults', 'eab_cluster_defaults' );

function eab_cluster_sections( $sections, $d ) {
	$sections['eab_cluster'] = array(
		'title'       => 'บทความ · กล่องชุดบทความ',
		'description' => 'กล่องท้ายบทความที่ลิงก์บทความในหมวดเดียวกัน (เสาหลัก + บทความเสริม) แสดงเฉพาะบทความที่เผยแพร่แล้ว · {cat} = ชื่อหมวด',
		'fields'      => array(
			'show_cluster_box'      => array( 'แสดงกล่องชุดบทความท้ายบทความ', 'checkbox' ),
			'cluster_box_title'     => array( 'หัวกล่อง (บทความเสริม)', 'text' ),
			'cluster_pillar_label'  => array( 'ป้ายเหนือลิงก์บทความหลัก', 'text' ),
			'cluster_pillar_title'  => array( 'หัวกล่อง (บนบทความหลักของชุด)', 'text' ),
			'cluster_more_label'    => array( 'ข้อความลิงก์ไปหน้าหมวด', 'text' ),
			'cluster_sibling_count' => array( 'จำนวนบทความพี่น้องที่แสดง (บนบทความเสริม)', 'number' ),
		),
	);
	return $sections;
}
add_filter( 'eab_customizer_sections', 'eab_cluster_sections', 40, 2 );

/**
 * หมวดแรกของบทความที่มีเสาหลักกำหนดไว้ → array( term, pillar WP_Post|null ) หรือ null
 */
function eab_cluster_of( $post_id ) {
	$map = eab_cluster_pillars();
	foreach ( (array) get_the_category( $post_id ) as $term ) {
		if ( isset( $map[ $term->slug ] ) ) {
			$pillar = get_posts(
				array(
					'name'           => $map[ $term->slug ],
					'post_type'      => 'post',
					'post_status'    => 'publish',
					'posts_per_page' => 1,
					'no_found_rows'  => true,
				)
			);
			return array( $term, $pillar ? $pillar[0] : null );
		}
	}
	return null;
}

/**
 * กล่อง "บทความในชุดนี้" ท้ายบทความ
 */
function eab_cluster_box( $post_id = 0 ) {
	$post_id = $post_id ? (int) $post_id : (int) get_the_ID();
	if ( ! $post_id || ! eab_mod( 'show_cluster_box' ) ) {
		return;
	}
	$cluster = eab_cluster_of( $post_id );
	if ( ! $cluster ) {
		return;
	}
	list( $term, $pillar ) = $cluster;
	$is_pillar = $pillar && (int) $pillar->ID === $post_id;
	$exclude   = array( $post_id );
	if ( $pillar ) {
		$exclude[] = (int) $pillar->ID;
	}
	$limit = $is_pillar ? 100 : max( 1, (int) eab_mod( 'cluster_sibling_count' ) );
	$items = get_posts(
		array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'cat'            => (int) $term->term_id,
			'post__not_in'   => $exclude,
			'posts_per_page' => $limit,
			'orderby'        => 'date',
			'order'          => $is_pillar ? 'ASC' : 'DESC',
			'no_found_rows'  => true,
		)
	);
	if ( ! $items && ( $is_pillar || ! $pillar ) ) {
		return;
	}
	$cat   = $term->name;
	$title = str_replace( '{cat}', $cat, (string) eab_mod( $is_pillar ? 'cluster_pillar_title' : 'cluster_box_title' ) );
	$more  = str_replace( '{cat}', $cat, (string) eab_mod( 'cluster_more_label' ) );
	?>
	<nav class="cluster-box<?php echo $is_pillar ? ' cluster-box--pillar' : ''; ?>" aria-label="<?php echo esc_attr( $title ); ?>">
		<p class="cluster-box-title"><?php echo esc_html( $title ); ?></p>
		<?php if ( $pillar && ! $is_pillar ) : ?>
			<a class="cluster-pillar" href="<?php echo esc_url( get_permalink( $pillar ) ); ?>">
				<span class="tile tile--gold" aria-hidden="true"><?php echo eab_icon( 'book' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				<span class="cluster-pillar-text">
					<span class="cluster-pillar-label"><?php echo esc_html( eab_mod( 'cluster_pillar_label' ) ); ?></span>
					<span class="cluster-pillar-name"><?php echo esc_html( wp_strip_all_tags( get_the_title( $pillar ) ) ); ?></span>
				</span>
				<?php echo eab_icon( 'arrow', 'icon cluster-arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</a>
		<?php endif; ?>
		<?php if ( $items ) : ?>
			<ul class="cluster-list">
				<?php foreach ( $items as $item ) : ?>
					<li><a href="<?php echo esc_url( get_permalink( $item ) ); ?>"><?php echo esc_html( wp_strip_all_tags( get_the_title( $item ) ) ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
		<?php if ( '' !== trim( $more ) ) : ?>
			<a class="cluster-more" href="<?php echo esc_url( get_category_link( $term ) ); ?>"><span><?php echo esc_html( $more ); ?></span> <?php echo eab_icon( 'arrow', 'icon icon-sm' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
		<?php endif; ?>
	</nav>
	<?php
}

/**
 * หน้าหมวด (หน้าแรกของรายการ): บทความเสาหลักขึ้นก่อน · หน้าถัดไปไม่แสดงซ้ำ
 */
function eab_cluster_pillar_first( $posts, $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_category() ) {
		return $posts;
	}
	$term = $query->get_queried_object();
	$map  = eab_cluster_pillars();
	if ( ! $term || empty( $term->slug ) || ! isset( $map[ $term->slug ] ) ) {
		return $posts;
	}
	$slug  = $map[ $term->slug ];
	$found = null;
	foreach ( $posts as $i => $p ) {
		if ( $p->post_name === $slug ) {
			$found = $p;
			unset( $posts[ $i ] );
			break;
		}
	}
	if ( max( 1, (int) $query->get( 'paged' ) ) > 1 ) {
		return array_values( $posts );
	}
	if ( ! $found ) {
		$q     = get_posts(
			array(
				'name'           => $slug,
				'post_type'      => 'post',
				'post_status'    => 'publish',
				'posts_per_page' => 1,
				'no_found_rows'  => true,
			)
		);
		$found = $q ? $q[0] : null;
	}
	if ( $found ) {
		array_unshift( $posts, $found );
	}
	return array_values( $posts );
}
add_filter( 'the_posts', 'eab_cluster_pillar_first', 10, 2 );

function eab_cluster_asset_needed( $needed, $module ) {
	return 'clusters' === $module ? is_singular( 'post' ) : $needed;
}
add_filter( 'eab_asset_module_needed', 'eab_cluster_asset_needed', 10, 2 );

function eab_cluster_asset_modules( $modules ) {
	$modules[] = 'clusters';
	return $modules;
}
add_filter( 'eab_asset_modules', 'eab_cluster_asset_modules' );
