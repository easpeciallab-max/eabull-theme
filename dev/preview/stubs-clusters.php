<?php
/**
 * stub สำหรับโมดูล clusters (กล่องชุดบทความ) และตัวกันพลาดเวลาตั้งเวลาใน infra · preview ไม่มีฐานข้อมูล
 */
if ( ! function_exists( 'get_transient' ) ) {
	function get_transient( $key ) { return false; }
}
if ( ! function_exists( 'set_transient' ) ) {
	function set_transient( $key, $value, $ttl = 0 ) { return true; }
}
if ( ! function_exists( 'wp_doing_ajax' ) ) {
	function wp_doing_ajax() { return false; }
}
if ( ! function_exists( 'check_and_publish_future_post' ) ) {
	function check_and_publish_future_post( $id ) {}
}
/* หน้ารวมบทความ (index.php) · preview ไม่มีบทความและหมวดที่เผยแพร่ → สถานะ "ยังไม่มีบทความ" */
/* เพจกฎหมายปิดแถบติดต่อท้ายหน้าด้วย add_filter( 'eab_chrome_footer_contact', '__return_false' ) */
if ( ! function_exists( '__return_false' ) ) {
	function __return_false() { return false; }
}
if ( ! function_exists( 'get_terms' ) ) {
	function get_terms( $args = array() ) { return array(); }
}
if ( ! function_exists( 'single_cat_title' ) ) {
	function single_cat_title( $prefix = '', $display = true ) { return 'หมวด'; }
}
if ( ! function_exists( 'category_description' ) ) {
	function category_description() { return ''; }
}
if ( ! defined( 'MINUTE_IN_SECONDS' ) ) {
	define( 'MINUTE_IN_SECONDS', 60 );
}
