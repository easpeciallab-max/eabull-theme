<?php
/**
 * Template Name: EA BULL · หน้าสินค้า
 *
 * เทมเพลตเดียวของทุกสินค้า (ต้นแบบ dev/mockup/_src/pages/product.html) · สินค้าเลือกจาก slug ของเพจ
 * ให้ตรงกับช่อง "slug ของหน้าสินค้า" ใน Customizer หมวด 3.x) สินค้า
 * โครงหน้าอยู่ที่ eab_products_page() ใน inc/modules/products.php · เนื้อหายาว inc/content/pages/product-N.html
 *
 * @package eabull
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

eab_products_page();
