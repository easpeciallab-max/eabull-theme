<?php
/**
 * Dev check: brand leaks from the owner's other brands + files that must never ship in the public theme.
 *
 * Scans every file under eabull/ (text files line by line, binary files for readable metadata, and every
 * file/folder NAME) for the owner's other brands: EA WING (wing, eawing, eaw_) · FALCON · FENIX · fenixpro
 * · EA2000 · speccub (case-insensitive). EA BULL was forked from the EA WING theme, so any "wing" left in
 * the source is a leak.
 * Zaurix is allowed: it is EA BULL's partner broker.
 * The internal code prefix is eab_ / eab- / eabCamel, so no brand-like identifier needs an exemption.
 * Mentions inside code comments are reported too (the repo is public) and tagged [comment].
 * Also flags archives and MetaTrader binaries/sources: .zip .rar .7z .ex4 .ex5 .mq4 .mq5
 *
 * Run from the repo root:  php dev/check-brand.php        (exit code 1 when anything is found)
 * Optional first argument: another folder to scan (used to test this script against fixtures).
 */

$root = realpath( isset( $argv[1] ) && '' !== $argv[1] ? $argv[1] : __DIR__ . '/../eabull' );
if ( ! $root || ! is_dir( $root ) ) {
	fwrite( STDERR, "theme folder not found\n" );
	exit( 2 );
}
$label = basename( $root );

$brand_re      = '/(?<![a-z])wing|eawing|\beaw[_\-A-Z]|@fenixpro|fenixpro|fenix|falcon|ea2000|speccub/i'; // (?<![a-z]) = not inside words like "following"
$forbidden_ext = array( 'zip', 'rar', '7z', 'ex4', 'ex5', 'mq4', 'mq5' );
$text_ext      = array( 'php', 'css', 'js', 'html', 'htm', 'txt', 'json', 'md', 'svg', 'xml', 'po', 'pot', 'csv', 'yml', 'yaml', 'map', 'webmanifest' );

/**
 * Rough "is this inside a comment" label (for the report only; comment hits still count).
 */
function fx_brand_in_comment( $line, $offset, $ext, $in_block ) {
	if ( $in_block ) {
		return true;
	}
	$head    = substr( $line, 0, $offset );
	$trimmed = ltrim( $line );
	if ( '' !== $trimmed && ( '*' === $trimmed[0] || 0 === strpos( $trimmed, '/*' ) || 0 === strpos( $trimmed, '<!--' ) ) ) {
		return true;
	}
	if ( in_array( $ext, array( 'php', 'js', 'css' ), true ) && preg_match( '#(?<![:"\'])//|/\*#', $head ) ) {
		return true;
	}
	if ( 'php' === $ext && preg_match( '/^\s*#/', $line ) ) {
		return true;
	}
	return false !== strpos( $head, '<!--' );
}

$findings = array();
$scanned  = 0;

$it = new RecursiveIteratorIterator(
	new RecursiveDirectoryIterator( $root, FilesystemIterator::SKIP_DOTS ),
	RecursiveIteratorIterator::SELF_FIRST
);

foreach ( $it as $file ) {
	$path = $file->getPathname();
	$rel  = $label . '/' . str_replace( '\\', '/', substr( $path, strlen( $root ) + 1 ) );
	$name = $file->getFilename();

	if ( preg_match( $brand_re, $name, $m ) ) {
		$findings[] = sprintf( '%s  [name] "%s" in a file/folder name', $rel, $m[0] );
	}
	if ( $file->isDir() ) {
		continue;
	}

	$ext = strtolower( $file->getExtension() );
	/* ยกเว้นชุดดาวน์โหลดของลูกค้าใน assets/downloads/*.zip (EA ล็อกสิทธิ์ตามเลขบัญชีอยู่แล้ว) · ไม่สแกนข้อความข้างในไฟล์บีบอัด */
	if ( 'zip' === $ext && preg_match( '#^[^/]+/assets/downloads/[^/]+\.zip$#', $rel ) ) {
		continue;
	}
	if ( in_array( $ext, $forbidden_ext, true ) ) {
		$findings[] = sprintf( '%s  [file] .%s must not be in the theme (EA files are sent privately, never from the public repo)', $rel, $ext );
		continue;
	}

	$data = file_get_contents( $path );
	if ( false === $data ) {
		$findings[] = sprintf( '%s  [read] could not read file', $rel );
		continue;
	}
	$scanned++;

	if ( ! in_array( $ext, $text_ext, true ) ) {
		/* images / fonts: look for readable brand text in metadata (XMP, PNG tEXt, EXIF) */
		if ( preg_match_all( $brand_re, $data, $bm ) ) {
			$findings[] = sprintf( '%s  [binary] contains "%s" (image/font metadata?)', $rel, implode( '", "', array_unique( $bm[0] ) ) );
		}
		continue;
	}

	$in_block = false; // inside a /* … */ or <!-- … --> block that started on an earlier line
	foreach ( preg_split( '/\r\n|\r|\n/', $data ) as $i => $line ) {
		if ( preg_match_all( $brand_re, $line, $mm, PREG_OFFSET_CAPTURE ) ) {
			foreach ( $mm[0] as $hit ) {
				list( $word, $offset ) = $hit;
				$tag        = fx_brand_in_comment( $line, $offset, $ext, $in_block ) ? ' [comment]' : '';
				$start      = max( 0, $offset - 40 );
				$snippet    = trim( function_exists( 'mb_strcut' ) ? mb_strcut( $line, $start, strlen( $word ) + 80, 'UTF-8' ) : substr( $line, $start, strlen( $word ) + 80 ) );
				$findings[] = sprintf( '%s:%d%s  "%s"  … %s', $rel, $i + 1, $tag, $word, $snippet );
			}
		}
		/* track multi-line comment blocks for the [comment] label */
		$opens  = max( substr_count( $line, '/*' ), substr_count( $line, '<!--' ) );
		$closes = max( substr_count( $line, '*/' ), substr_count( $line, '-->' ) );
		if ( $opens > $closes ) {
			$in_block = true;
		} elseif ( $closes > $opens ) {
			$in_block = false;
		}
	}
}

echo "brand check · scanned $scanned files under $label/\n";
if ( $findings ) {
	echo count( $findings ) . " finding(s):\n  " . implode( "\n  ", $findings ) . "\n";
	exit( 1 );
}
echo "no brand leaks, no forbidden files\n";
exit( 0 );
