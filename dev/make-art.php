<?php
/**
 * Build in-content illustrations: eabull/assets/img/illus/<name>-v2.webp (wide) + <name>-mobile-v2.webp (tall) · เปลี่ยนเลข v เมื่อภาพเปลี่ยน (Cloudflare แคชตาม URL).
 *
 * Needs the preview server (php -S localhost:8775 dev/preview/router.php), Google Chrome and GD:
 *   php -d extension=gd dev/make-art.php how-it-works
 * Template: dev/preview/art.php (route /__art/<name>/?layout=wide|tall).
 */

$root   = dirname( __DIR__ );
$outdir = $root . '/eabull/assets/img/illus';
$base   = getenv( 'EAB_PREVIEW' ) ? getenv( 'EAB_PREVIEW' ) : 'http://localhost:8775';
$chrome = getenv( 'CHROME_BIN' );
if ( ! $chrome ) {
	foreach ( array( 'C:/Program Files/Google/Chrome/Application/chrome.exe', 'C:/Program Files (x86)/Google/Chrome/Application/chrome.exe', '/usr/bin/google-chrome', '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome' ) as $c ) {
		if ( file_exists( $c ) ) {
			$chrome = $c;
			break;
		}
	}
}
if ( ! $chrome || ! function_exists( 'imagewebp' ) ) {
	fwrite( STDERR, "Chrome and GD with WebP are required.\n" );
	exit( 1 );
}
if ( ! is_dir( $outdir ) ) {
	mkdir( $outdir, 0755, true );
}
$tmp  = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'eabull-art';
$prof = $tmp . DIRECTORY_SEPARATOR . 'profile';
if ( ! is_dir( $prof ) ) {
	mkdir( $prof, 0755, true );
}

$layouts = array(
	'wide' => array( 1600, 760, '' ),
	'tall' => array( 800, 1140, '-mobile' ),
);
$fail = 0;
foreach ( array_slice( $argv, 1 ) as $name ) {
	if ( ! preg_match( '/^[a-z0-9-]+$/', $name ) ) {
		continue;
	}
	foreach ( $layouts as $layout => $size ) {
		$png = $tmp . DIRECTORY_SEPARATOR . $name . '-' . $layout . '.png';
		@unlink( $png ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		$url = $base . '/__art/' . $name . '/?layout=' . $layout;
		$cmd = '"' . $chrome . '" --headless=new --disable-gpu --hide-scrollbars --force-device-scale-factor=1'
			. ' --window-size=' . $size[0] . ',' . $size[1] . ' --virtual-time-budget=4000'
			. ' --user-data-dir="' . $prof . '" --screenshot="' . $png . '" "' . $url . '"';
		exec( $cmd . ' 2>&1', $o, $rc );
		if ( ! file_exists( $png ) ) {
			echo "✗ $name ($layout) · screenshot failed\n";
			$fail++;
			continue;
		}
		$im  = imagecreatefrompng( $png );
		$out = $outdir . '/eabull-' . $name . $size[2] . '-v2.webp';
		imagepalettetotruecolor( $im );
		imagewebp( $im, $out, 86 );
		printf( "✓ %s (%dx%d, %d KB)\n", basename( $out ), imagesx( $im ), imagesy( $im ), (int) round( filesize( $out ) / 1024 ) );
	}
}
exit( $fail ? 1 : 0 );
