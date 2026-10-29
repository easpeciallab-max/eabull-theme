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
if ( ! defined( 'MINUTE_IN_SECONDS' ) ) {
	define( 'MINUTE_IN_SECONDS', 60 );
}
