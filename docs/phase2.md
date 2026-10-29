# EA BULL · เฟส 2 ธีม WordPress: แผนและกติกาสำหรับคนทำ

เจ้าของอนุมัติต้นแบบ (dev/mockup) และสั่งเริ่มเฟส 2 เมื่อ 29 ต.ค. 2026
**เป้าหมาย: ธีม `eabull/` ต้องออกมาหน้าตาเหมือนต้นแบบที่อนุมัติทุกหน้า** โดยคงระบบของธีมต้นทาง (Customizer, Setup, SEO, PDPA, REST, LINE analytics) ไว้

อ่านก่อนเริ่ม: `CLAUDE.md` · `docs/design.md` · `dev/mockup/_src/README.md` · `dev/content-spec.md`

## 1) สถานะตอนเริ่ม

- `eabull/` fork มาจากธีม EA WING เปลี่ยนชื่อภายในแล้ว (`eab_` `eab-` `eabCamel` · text domain `eabull` · REST `eabull/v1` · shortcode `[eabull_*]` · cookie `eab_consent`)
- **ทำแล้ว (เจ้าของงานกลาง)**
  - `style.css` = ดีไซน์ Bull Black ทั้งชุดจาก `dev/mockup/assets/css/bull.css` + คลาสเนื้อหา (callout, checklist, crosslist, guide-steps, table-wrap/data-table, faq-item, related-links, xref) + lightbox
  - CSS ของโมดูลเดิมทั้งหมดถูกลบ · `home.js` ถูกลบ · `main.js` เขียนใหม่ (hook ตามข้อ 4)
  - ฟอนต์ Inter + Anuphan (โหลด Google Fonts ชั่วคราว จนกว่าจะได้ไฟล์ self-host)
  - `eab_icon()` ใช้ชุดไอคอนของต้นแบบ (ชื่อเดียวกับ `dev/mockup/_src/icons.json`) และคืน `<svg class="i">` · ชื่อเดิมยังใช้ได้ผ่าน alias (warn, arrow, calc, gear, windows, apple, android, macos, image, chart)
  - `eab_wait( $value )` ใน functions.php: ค่าว่าง = ป้าย `.wait` "รอข้อมูลจากเจ้าของ"
  - รูปแบรนด์ `assets/img/brand/` (eabull-logo, -emblem, -wordmark-dark/light, -icon-512/180/96, -logo-160, favicon-32, -share-1200x630.jpg) และ `screenshot.png`
  - ค่าเริ่มต้นโบรกเกอร์ Zaurix, ลิงก์พาร์ตเนอร์ EA BULL, OpenChat, Facebook ใน functions.php
- **ยังเป็นของ EA WING**: markup ทุกโมดูล ข้อความทุก setting และเนื้อหา `inc/content/pages/*.html` · สินค้า 3 ตัวเดิม (nitro/twin/ea-wing) ยังค้างใน setup.php, products.php, seo.php, go.php, home.php และเนื้อหาบางไฟล์

## 2) วิธีทำ

1. เปิดหน้าต้นแบบที่ตรงกัน (`dev/mockup/<slug>.html` และต้นฉบับ `dev/mockup/_src/pages/<slug>.html`) แล้วให้ PHP ออก markup แบบเดียวกัน ใช้คลาสเดียวกับต้นแบบ
2. ข้อความ รูป และลิงก์ทุกจุดเป็น setting: เพิ่มใน defaults ของโมดูล (`add_filter('eab_defaults', ...)`) **และ** control (`add_filter('eab_customizer_sections', ...)`) คู่กันเสมอ · ลบ setting และ section ที่ไม่ใช้แล้วออกทั้งสองฝั่ง · `php dev/check-settings.php` ต้องได้ (none)
3. ข้อความใหม่ทั้งหมดเป็นภาษาไทย **ใช้ข้อความจากต้นแบบก่อน** (เจ้าของอนุมัติแล้ว) ส่วนที่ต้นแบบไม่มีให้เขียนใหม่ · **ห้ามดัดแปลงประโยคของ EA WING ที่ค้างอยู่ในไฟล์** ให้ลบแล้วเขียนใหม่ · ตรวจด้วย `py -3 -I dev/tools/check-dup.py <ไฟล์ของคุณ>` ต้องไม่มีบรรทัดที่ถูกจับ
4. เนื้อหายาว `inc/content/pages/*.html` ใช้รูปแบบใน `dev/content-spec.md` (บรรทัดแรก `<!--meta {...} -->` · `rev` ใหม่เริ่มที่ 1) และคลาสเนื้อหาตามข้อ 5 · `php dev/check-content.php` ต้อง all good
5. ข้อมูลที่ยังไม่มี: ห้ามแต่ง ใช้ setting ค่าว่าง + `eab_wait()` · ข้อเท็จจริงที่ใช้ได้อยู่ใน `CLAUDE.md` ส่วน "ข้อมูลจริงที่ได้แล้ว" (แพ็กเกจ ราคา เงื่อนไข ผู้ให้บริการ อีเมล ระยะเวลาลบข้อมูล ตาม EA WING)
6. ตรวจ: `php -l` ทุกไฟล์ที่แก้ · เปิด http://localhost:8791/<slug>/ (preview "eabull-preview") ต้องไม่มี PHP Warning/Notice · เทียบกับ http://localhost:8790/<slug>.html (ต้นแบบ) ที่จอ 1366 และ 375 · `php dev/check-brand.php` ไฟล์ของคุณต้องไม่มี wing/eawing เหลือ

## 3) ใครทำไฟล์ไหน (ห้ามแก้ไฟล์ของคนอื่น)

| ชุด | ไฟล์ที่เป็นเจ้าของ |
|---|---|
| A · chrome + PDPA + /go | header.php · footer.php · inc/modules/chrome.php · inc/modules/consent.php · assets/js/consent.js · template-go.php · inc/modules/go.php · dev/preview/stubs-chrome.php, stubs-consent.php, stubs-go.php |
| B · หน้าแรก + สินค้า | front-page.php · inc/modules/home.php · inc/modules/homeplus.php · inc/parts/ (ลบได้) · inc/modules/products.php · template-eabull.php → template-product.php · inc/content/pages/product-*.html · dev/preview/stubs-home.php |
| C · หน้าเพจและเอกสาร | inc/components.php · inc/modules/pages.php · inc/modules/clusters.php · page.php · index.php · single.php · search.php · 404.php · template-pricing.php · template-backtest.php · template-forward.php · template-risk.php · inc/content/pages/{pricing, backtest, forward-test, risk-disclosure, about, privacy-policy, terms-of-use, data-deletion}.html · dev/preview/stubs-clusters.php, stubs-seo.php |
| D · คู่มือ + เครื่องคำนวณ | inc/modules/guides.php · template-guide.php · template-install.php · inc/content/pages/{how-to-install, open-mt5-account, deposit, mt5-login, vps-windows, vps-android, vps-ios, tools}.html · dev/preview/stubs-guides.php |

**ไฟล์ที่ใช้ร่วมกัน (แก้ด้วย Edit เฉพาะส่วนของตัวเองเท่านั้น ห้าม Write ทับทั้งไฟล์):**
- `inc/setup.php`: B แก้รายการสินค้าในหน้า manifest และเมนูสินค้า · C แก้ seo ของเพจตัวเอง
- `inc/seo.php`: B ลบของค้างจากสินค้าเดิม (zip redirect, sitemap image keys)
- `inc/shortcodes.php`: B แก้ `[eabull_ea]` · D แก้ `[eabull_calc]` และ `[eabull_figure]`
- `functions.php`, `inc/customizer.php`, `style.css`, `assets/js/main.js`: **เจ้าของงานกลางเท่านั้น** · ต้องการ setting ใหม่ ให้ใส่ใน defaults ของโมดูลตัวเอง · ต้องการ CSS เพิ่ม ให้เขียนใน `assets/css/<โมดูล>.css` ของตัวเอง (โหลดอัตโนมัติ: chrome ทุกหน้ายกเว้น /go · home เฉพาะหน้าแรก · pages และ components ทุกหน้า · guides เฉพาะเทมเพลตคู่มือ · go เฉพาะ /go · consent ทุกหน้า · clusters เฉพาะบทความ) · ต้องการ JS เพิ่ม ให้แจ้งในรายงาน
- section ใน customizer.php ที่เป็นของเก่า: ลบผ่าน filter ของโมดูลตัวเอง (`unset( $sections[...] )`) ไม่แก้ไฟล์กลาง

## 4) Hook ที่ main.js / consent.js ใช้ (markup ต้องตรง)

- Header: `header.hd#top` · `button.hd__toggle[aria-controls="nav"][aria-expanded]` (+ `data-open`/`data-close` เป็นป้าย aria) · `nav.nav#nav` (`.is-open` = เปิด) · เมนูที่มีลูก: `li.has-sub > button.nav__parent + ul.sub` (เมนูสำรอง) หรือ `li.menu-item-has-children > a + ul.sub-menu` (เมนู WP · ให้ `wp_nav_menu` ใช้ `menu_class => 'nav__list'`) · หน้าปัจจุบัน `[aria-current]` หรือ `.current-menu-item` · `body.nav-open`
- แผงติดต่อลอย: `button.fab[aria-controls="quick"]` + `#quick[hidden]` + `[data-quick-close]`
- ลิงก์ที่ยังไม่มี: `[data-wait="ข้อความ"]` → ข้อความแจ้งใน `#toast` (ต้องมี `<div class="toast" id="toast" role="status" aria-live="polite"></div>` ใน footer)
- แท็บ: `[data-tabs]` > `[role=tablist]` > `[role=tab]` และ `[role=tabpanel]` ลำดับเดียวกัน
- เช็กลิสต์: `[data-ready][data-left="ยังเหลืออีก {n} ข้อ"][data-full="..."]` > checkbox ทุกตัว · `.meter i` · `[data-ready-count]` · `[data-ready-status]` · `[data-ready-done][hidden]`
- เครื่องคำนวณ: `.calc[data-calc="lot"][data-money-unit][data-lot-unit]` ช่อง name = balance, risk, sl, preset (option value = มูลค่า pip หรือ custom), pip, step · ผล `[data-out="money"]` `[data-out="lot"]` · `.calc[data-calc="dd"][data-empty]` ช่อง dd, capital · ผล back, left, bar
- บทความ: `.readbar > i` · `.share-copy[data-url][data-done]` · โหลดเพิ่ม `.load-more > .load-more-btn[data-page][data-max][data-query][data-loading]` ต่อท้ายใน `.posts-grid` หรือ `.posts` (markup การ์ดต้องเหมือนกันทั้งตอนโหลดหน้าและตอน AJAX: `eab_post_card()`)
- สารบัญ: `.toc a[href^="#"]` หรือ `.longform-toc a` → `.is-active`
- รูปขยาย: `a.lightbox[data-group][data-caption]`
- นับคลิก LINE: ลิงก์ไป line.me/lin.ee อัตโนมัติ · ใส่ `data-line-pos` ทุกปุ่ม LINE (ค่าเดิมดูได้ใน chrome.php/home.php/pages.php/go.php) และ `data-line-pkg` ที่ปุ่มแพ็กเกจ
- คุกกี้: consent.js ใช้ `#cookie-settings` และ `data-view`, `data-show`, `data-when`, `data-cat`, `data-consent="accept|reject|ok|save|prefs|close"` · ชุด A คง contract นี้ไว้ (แก้ consent.js ได้ถ้าจำเป็น)

## 5) ปุ่มและลิงก์

- ปุ่มที่ไป LINE: ใช้ helper เดิม (`eab_contact_button`, `eab_chrome_contact_button`, `eab_home_contact_link`, `eab_pages_package_button`, `eab_go_line_button`) แต่ให้ออกคลาส `btn btn--line` · ยังไม่มี LINE OA = helper ส่งไป /go/ อยู่แล้ว (เจ้าของสั่งให้เตรียมปุ่มไว้) 
- OpenChat `btn btn--openchat` · Facebook `btn btn--fb` · ลิงก์สมัครโบรกเกอร์ `rel="sponsored noopener"` เสมอ · บอกความเป็นพันธมิตรตรงที่พูดถึง Zaurix ครั้งแรก
- ปุ่มหลัก `btn btn--primary` · ปุ่มรอง `btn btn--light` (บนดำ) หรือ `btn btn--dark` (บนขาว) · ปุ่มเส้น `btn btn--outline`

## 6) สินค้า (ชุด B)

- สินค้าจริงยังไม่มี: ใช้ "สินค้าตัวอย่าง 1 · V1.0", "2 · V2.0", "3 · V3.0" แบบต้นแบบ · ทุกช่องข้อมูลเป็น setting ว่าง → `eab_wait()`
- `eab_products()` อ่านจาก setting `product{1..3}_*` (ชื่อ เลขรุ่น slug ไฟล์ zip ช่องสเปก) เรียงตามเลขรุ่น · หน้าสินค้าใช้ `template-product.php` เทมเพลตเดียว · slug ชั่วคราว `product-1..3`
- ลบทุกการอ้างถึง wing-nitro, wing-twin, ea-wing, go_nitro, go_twin, go_fast_*, nitro_*, twin_*, eabull_* (ชุด setting ของสินค้าเดิม)

## 7) หลังทุกชุดเสร็จ (เจ้าของงานกลาง)

ตรวจรวม: php -l · check-settings · check-content · check-brand (ต้อง no brand leaks) · check-dup · ทุกหน้าใน preview ที่ 375/820/1366 ไม่มีแถบเลื่อนแนวนอนและไม่มี PHP notice · ถ่ายภาพเทียบกับต้นแบบส่งเจ้าของ
