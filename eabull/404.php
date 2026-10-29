<?php
/**
 * 404 · ไม่พบหน้า
 *
 * Glass Sky: แผงกระจกกลางจอ (ตัวเลข 404 ไล่สี · H1 · คำอธิบาย · ช่องค้นหาแคปซูลกระจก · ปุ่ม) → การ์ดลิงก์ด่วน
 * ปุ่มติดต่อใช้ eab_contact_target(): LINE (ปุ่มทอง) → หน้า /go/ → ไม่แสดง (ไม่มีลิงก์ '#')
 *
 * @package eabull
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$eab_target = eab_contact_target();
$eab_links  = eab_pages_pairs( eab_mod( 'err404_links' ) );
?>

<main id="main" class="pg err-page">
	<section class="pg-sec error-404">
		<div class="container">
			<div class="glass-panel pg-panel err-panel">
				<span class="orb err-orb err-orb--a" aria-hidden="true"></span>
				<span class="orb err-orb err-orb--b" aria-hidden="true"></span>

				<div class="err-main">
					<p class="error-code" aria-hidden="true">404</p>
					<h1 class="error-title"><?php echo eab_text( eab_mod( 'err404_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside ?></h1>
					<p class="error-text"><?php echo esc_html( eab_mod( 'err404_text' ) ); ?></p>

					<?php eab_pages_search_form( eab_mod( 'err404_search_placeholder' ), 'error-search' ); ?>

					<div class="error-actions">
						<a class="btn btn-dark" href="<?php echo esc_url( home_url( '/' ) ); ?>">
							<?php echo eab_icon( 'home', 'icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<span><?php echo esc_html( eab_mod( 'err404_home_label' ) ); ?></span>
						</a>
						<?php
						eab_contact_button(
							array(
								'text'  => eab_mod( 'err404_contact_text' ),
								'class' => $eab_target['is_line'] ? 'btn btn-fire' : 'btn btn-ghost',
								'pos'   => '404',
								'arrow' => false,
							)
						);
						?>
					</div>
				</div>

				<?php if ( $eab_links ) : ?>
					<nav class="error-links" aria-label="<?php echo esc_attr( eab_mod( 'err404_title' ) ); ?>">
						<ul>
							<?php foreach ( $eab_links as $eab_link ) : ?>
								<?php if ( '' !== $eab_link[1] ) : ?>
									<li><a class="err-link" href="<?php echo esc_url( eab_link_url( $eab_link[1] ) ); ?>"><span><?php echo esc_html( $eab_link[0] ); ?></span><span class="err-link-ar" aria-hidden="true"><?php echo eab_icon( 'arrow', 'icon icon-sm' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span></a></li>
								<?php endif; ?>
							<?php endforeach; ?>
						</ul>
					</nav>
				<?php endif; ?>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
