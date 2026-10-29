<?php
/**
 * Local preview router: php -S localhost:8791 dev/preview/router.php (from repo root)
 * Renders theme templates with stub WP functions so layout/CSS can be checked in a browser.
 */

$uri  = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
$uri  = rawurldecode( $uri );
$root = realpath( __DIR__ . '/../../eabull' );

// Design mockup: /mockup/ (dev/mockup, see docs/design.md)
if ( 0 === strpos( $uri, '/mockup/' ) ) {
	$base = realpath( __DIR__ . '/../mockup' );
	$rel  = substr( $uri, 8 );
	$file = realpath( $base . '/' . ( '' === $rel ? 'index.html' : $rel ) );
	if ( $file && 0 === strpos( $file, $base ) && is_file( $file ) ) {
		$types = array( 'html' => 'text/html; charset=utf-8', 'css' => 'text/css' );
		$ext   = strtolower( pathinfo( $file, PATHINFO_EXTENSION ) );
		header( 'Content-Type: ' . ( isset( $types[ $ext ] ) ? $types[ $ext ] : 'application/octet-stream' ) );
		readfile( $file );
		return true;
	}
	http_response_code( 404 );
	return true;
}

// Static theme files: /theme/...
if ( 0 === strpos( $uri, '/theme/' ) ) {
	$file = realpath( $root . substr( $uri, 6 ) );
	if ( $file && 0 === strpos( $file, $root ) && is_file( $file ) ) {
		$types = array( 'css' => 'text/css', 'js' => 'application/javascript', 'png' => 'image/png', 'jpg' => 'image/jpeg', 'webp' => 'image/webp', 'svg' => 'image/svg+xml' );
		$ext   = strtolower( pathinfo( $file, PATHINFO_EXTENSION ) );
		header( 'Content-Type: ' . ( isset( $types[ $ext ] ) ? $types[ $ext ] : 'application/octet-stream' ) );
		readfile( $file );
		return true;
	}
	http_response_code( 404 );
	return true;
}

error_reporting( E_ALL );
ini_set( 'display_errors', '1' );

require __DIR__ . '/wp-stubs.php';
require $root . '/functions.php';
do_action( 'after_setup_theme' );
do_action( 'init' );

$slug = trim( $uri, '/' );

// Search results: /?s=term or /search/ renders search.php (the WP_Query stub has no posts → "no results" state)
if ( isset( $_GET['s'] ) || 'search' === $slug ) {
	$GLOBALS['fx_post'] = array( 'title' => 'ค้นหา', 'content' => '', 'slug' => 'search', 'type' => 'search' );
	$GLOBALS['fx_loop'] = 1; // have_posts() = false → "no results" state
	require $root . '/search.php';
	return true;
}

if ( '' === $slug ) {
	$GLOBALS['fx_route']['front'] = true;
	$GLOBALS['fx_post']           = array( 'title' => 'หน้าแรก', 'content' => '', 'slug' => '', 'type' => 'page' );
	require $root . '/front-page.php';
	return true;
}

// Pages from the theme's own page manifest (inc/setup.php) when available.
$pages = function_exists( 'eab_site_pages' ) ? eab_site_pages() : array();
if ( isset( $pages[ $slug ] ) ) {
	$p       = $pages[ $slug ];
	$content = '';
	if ( ! empty( $p['content'] ) && function_exists( 'eab_seed_content' ) ) {
		$content = eab_seed_content( $p['content'] );
	}
	$GLOBALS['fx_post'] = array( 'title' => $p['title'], 'content' => $content, 'slug' => $slug, 'type' => 'page' );
	if ( 'articles' === $slug ) {
		$GLOBALS['fx_route']['home'] = true;
		require $root . '/index.php';
		return true;
	}
	$tpl = ! empty( $p['template'] ) ? $p['template'] : 'page.php';
	require $root . '/' . $tpl;
	return true;
}

// In-content illustration: /__art/<name>/?layout=wide|tall (source for dev/make-art.php)
if ( 0 === strpos( $slug, '__art/' ) ) {
	$fx_art_name = trim( substr( $slug, 6 ), '/' );
	require __DIR__ . '/art.php';
	return true;
}

// Article cover card: /__cover/<article-slug>/ (source for dev/make-covers.php)
if ( 0 === strpos( $slug, '__cover/' ) && function_exists( 'eab_seed_articles' ) ) {
	$fx_cover_slug = substr( $slug, 8 );
	require __DIR__ . '/cover.php';
	return true;
}

// Article preview: /article/<file> renders inc/content/articles/<file>.html with single.php
if ( 0 === strpos( $slug, 'article/' ) && function_exists( 'eab_seed_articles' ) ) {
	$key  = substr( $slug, 8 );
	$arts = eab_seed_articles();
	if ( isset( $arts[ $key ] ) ) {
		$GLOBALS['fx_post'] = array( 'title' => $arts[ $key ]['title'], 'content' => eab_seed_content( $arts[ $key ]['content'] ), 'slug' => $key, 'type' => 'post' );
		require $root . '/single.php';
		return true;
	}
}

$legacy = array(
	'backtest'        => array( 'template-backtest.php', 'Backtest' ),
	'forward-test'    => array( 'template-forward.php', 'Forward Test' ),
	'pricing'         => array( 'template-pricing.php', 'แพ็กเกจและราคา' ),
	'how-to-install'  => array( 'template-install.php', 'วิธีติดตั้ง' ),
	'risk-disclosure' => array( 'template-risk.php', 'ประกาศความเสี่ยง' ),
);
if ( isset( $legacy[ $slug ] ) ) {
	$GLOBALS['fx_post'] = array( 'title' => $legacy[ $slug ][1], 'content' => '', 'slug' => $slug, 'type' => 'page' );
	require $root . '/' . $legacy[ $slug ][0];
	return true;
}

// Anything else (and /404/): the theme's 404 template with a 404 status
http_response_code( 404 );
$GLOBALS['fx_post'] = array( 'title' => 'ไม่พบหน้า', 'content' => '', 'slug' => '404', 'type' => '404' );
require $root . '/404.php';
return true;
