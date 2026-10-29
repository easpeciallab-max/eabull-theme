<?php
/**
 * EA BULL · กลุ่มบทความ (เสาหลัก + บทความเสริม) · docs/content-plan.md
 *
 * - 1 หมวด = 1 ชุดบทความ มีบทความหลักหนึ่งบทตาม eab_cluster_pillars()
 * - กล่อง .series ท้ายบทความ ลิสต์เฉพาะบทความที่เผยแพร่แล้ว:
 *   บนบทความลูก = ลิงก์บทความหลัก + บทความล่าสุดในหมวด · บนบทความหลัก = บทความลูกทุกบท
 * - หน้าแรกของหน้าหมวด: บทความหลักขึ้นก่อนบทอื่น
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
	$d['cluster_box_title']       = 'บทความในชุดนี้';
	$d['cluster_pillar_label']    = 'บทความหลักของชุด';
	$d['cluster_pillar_title']    = 'บทความในชุด {cat}';
	$d['cluster_more_label']      = 'ทุกบทความในหมวด {cat}';
	$d['cluster_sibling_count']   = 6;
	return $d;
}
add_filter( 'eab_defaults', 'eab_cluster_defaults' );

function eab_cluster_sections( $sections, $d ) {
	$sections['eab_cluster'] = array(
		'title'       => 'บทความ · กล่องชุดบทความ',
		'description' => 'กล่องรายชื่อบทความหมวดเดียวกันใต้เนื้อหาบทความ (นับเฉพาะที่เผยแพร่) · {cat} แทนชื่อหมวด',
		'fields'      => array(
			'show_cluster_box'      => array( 'แสดงกล่องชุดบทความท้ายบทความ', 'checkbox' ),
			'cluster_box_title'     => array( 'หัวกล่อง (บทความเสริม)', 'text' ),
			'cluster_pillar_label'  => array( 'ป้ายเหนือลิงก์บทความหลัก', 'text' ),
			'cluster_pillar_title'  => array( 'หัวกล่อง (บนบทความหลักของชุด)', 'text' ),
			'cluster_more_label'    => array( 'ข้อความลิงก์ไปหน้าหมวด', 'text' ),
			'cluster_sibling_count' => array( 'บทความลูก: จำนวนบทความอื่นในหมวดที่ลิสต์', 'number' ),
		),
	);
	return $sections;
}
add_filter( 'eab_customizer_sections', 'eab_cluster_sections', 40, 2 );

/**
 * หมวดของบทความที่อยู่ใน eab_cluster_pillars() → array( term, บทความหลัก|null ) · ไม่อยู่ = null
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
	$name  = function ( $p ) {
		return html_entity_decode( wp_strip_all_tags( (string) get_the_title( $p ) ), ENT_QUOTES, 'UTF-8' );
	};
	/* กล่อง .series ของต้นแบบ (article.html): หัวกล่องตัวหนา · ลิงก์บทความหลัก · รายการลำดับ · ลิงก์ไปหน้าหมวด */
	?>
	<nav class="series mt-32" aria-label="<?php echo esc_attr( $title ); ?>">
		<b><?php echo esc_html( $title ); ?></b>
		<?php if ( $pillar && ! $is_pillar ) : ?>
			<p class="small"><?php echo esc_html( eab_mod( 'cluster_pillar_label' ) ); ?>: <a href="<?php echo esc_url( get_permalink( $pillar ) ); ?>"><?php echo esc_html( $name( $pillar ) ); ?></a></p>
		<?php endif; ?>
		<?php if ( $items ) : ?>
			<ol>
				<?php foreach ( $items as $item ) : ?>
					<li><a href="<?php echo esc_url( get_permalink( $item ) ); ?>"><?php echo esc_html( $name( $item ) ); ?></a></li>
				<?php endforeach; ?>
			</ol>
		<?php endif; ?>
		<?php if ( '' !== trim( $more ) ) : ?>
			<p class="small"><a class="tlink" href="<?php echo esc_url( get_category_link( $term ) ); ?>"><?php echo esc_html( $more ); ?> <?php echo eab_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a></p>
		<?php endif; ?>
	</nav>
	<?php
}

/**
 * หน้าหมวด: ดันบทความหลักขึ้นเป็นการ์ดแรกของหน้าแรก · หน้า 2 ขึ้นไปไม่ใส่ซ้ำ
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
