# วิธีเขียนหน้าในต้นแบบ EA BULL

ระบบดีไซน์และเหตุผลอยู่ใน `docs/design.md` · กติกาเนื้อหาอยู่ใน `CLAUDE.md` · บรีฟเต็มอยู่ใน `docs/brief.md`

## สร้างหน้า
- เขียนไฟล์ `_src/pages/<slug>.html` แล้วรัน `py -3 -I dev/mockup/build.py` ไฟล์จริงจะออกมาที่ `dev/mockup/<slug>.html` (ห้ามแก้ไฟล์ปลายทางตรง ๆ)
- บรรทัดแรกต้องเป็น front matter: `<!--{"title": "...", "desc": "...", "nav": ["guides", "vps-windows"], "dock": "guides", "cta": {"title": "...", "sub": "..."}}-->`
  - `nav`: key ของเมนูที่เป็นหน้าปัจจุบัน (ดู `data-nav` ใน `partials/header.html`) ใส่ทั้งกลุ่มและหน้า
  - `dock`: home · pricing · guides (ช่องแถบล่างมือถือ)
  - `cta`: หัวข้อและบรรทัดรองของแถบติดต่อสีเหลืองท้ายหน้า (ไม่ใส่ = ข้อความกลาง · `false` = ไม่มีแถบ)
  - `layout: "bare"` = ไม่มี header footer แถบล่าง ปุ่มลอย (ใช้กับ /go)
  - `outputs`: สร้างหลายไฟล์จากต้นฉบับเดียว `[{"slug": "product-1", "vars": {"n": "1"}, "nav": ["products", "product-1"]}]` แล้วใช้ `{{v:n}}`
- ไฟล์เนื้อหาไม่ต้องมี header/footer/แถบติดต่อ (ระบบใส่ให้) เริ่มที่ `<section class="phead">` ได้เลย
- หน้าต้นแบบที่ต้องดูก่อนเขียน: `pages/home.html` (ส่วนหน้าแรก) และ `pages/vps-windows.html` (โครงหน้าย่อยทั้งหมด: หัวเพจ ก่อนเริ่ม ขั้นตอน เอกสารยาว คู่มือที่เกี่ยวข้อง)

## โทเคนในไฟล์
| โทเคน | ผล |
|---|---|
| `{{i:name}}` | ไอคอนเส้น (ชื่อใน `_src/icons.json`) |
| `{{bars}}` | สัญลักษณ์ 3 แท่งหน้าหัวข้อ H2 |
| `{{wait}}` | ป้าย "รอข้อมูลจากเจ้าของ" |
| `{{wait:ข้อความ}}` | ป้ายรอข้อมูลแบบระบุข้อความ |
| `{{link:line}}` `{{link:dash}}` `{{link:mail}}` | ลิงก์ที่ยังไม่มี (กดแล้วขึ้นแจ้งเตือน) ใส่แทน `href` |
| `{{link:openchat}}` `{{link:fb}}` `{{link:zaurix}}` | ลิงก์จริง (Zaurix ติด `rel="sponsored noopener"` ให้เอง) |
| `{{wait-link:ข้อความ}}` | ลิงก์อื่นที่ยังไม่มี เช่นไฟล์ดาวน์โหลด |

## คลาสที่ใช้ได้ (อยู่ใน `assets/css/bull.css` แล้ว ห้ามเขียน style ในหน้า)
- โครง: `.band` (ส่วนพื้นดำ) `.band--alt` (ดำรอง) `.band paper` (พื้นขาวนวล) `.wrap` `.grid .g2/.g3/.g4` `.stack` `.btns` `.row` `.mt-16/24/32/48` `.center`
- หัว: `.phead` + `.crumbs` + `.phead__grid` + `.lead` + `.phead__meta` + `.sumbox` · `.shead` > `.shead__text` > `h2` (`{{bars}}`) + `p`
- ปุ่ม: `.btn` + `--primary` (เหลือง ลงมือทำ) `--light` (ขาวบนพื้นดำ) `--dark` `--outline` `--line` (ทุกปุ่มที่ไป LINE) `--openchat` `--fb` · ขนาด `--sm` `--lg` `--block` · ลิงก์ข้อความ `.tlink`
- ชิ้นเล็ก: `.tile` (`--accent` `--ink` `--lg`) `.badge` (`--acc` `--ex`) `.checks` (`--x`) `.note` (`--warn` `--info` `--tip` `--ok` `--bad`) `.shot` (ช่องรูปที่รอ: `--tall` `--sq`) `.cap` `.cut`
- การ์ด: `.card` `.stats` > `.stat` `.prep` (dl) `.related` > `.rel` `.posts` > `.post`
- ตาราง: `.tbl-wrap` > `table.tbl` (`.tbl--stack` = มือถือเป็นการ์ด ต้องใส่ `data-label` ทุก td) · คอลัมน์ไฮไลต์ `.us` · `.yn--y/--n/--m` · `table.spec-tbl`
- คู่มือ: `.guide` > `nav.rail` + `ol.gsteps` > `li.gstep` > `.gstep__n` + `.gstep__body` · แอป `.apps` > `.app`
- เอกสารยาว: `.doclay` > `details.toc` + `article.doc.paper` (ใส่ `paper` ให้เป็นการ์ดขาวนวล) · FAQ `details.qa` > `summary` + `.qa__a`
- ราคา: `.plans` > `.plan` (`.plan--pick` แนะนำ) > `.plan__head` + `.plan__body`
- เครื่องคำนวณ: `.calc[data-calc="lot|dd"]` > `.calc__in` (`.field`) + `.calc__out` (`.out`)
- อื่น ๆ: `.tabs[data-tabs]` `.ready[data-ready]` `.faq` `.riskbox` `.feat` `.track` `.prods` > `.prow` `.chips` `.searchbox` `.mk-note` (หมายเหตุของต้นแบบ)

## กติกาเนื้อหา (ห้ามพลาด)
- ภาษาไทยทั้งหมด เขียนใหม่ ห้ามคัดลอกจาก eawing.co หรือเว็บในเครือ
- ห้าม em dash (—) และ en dash (–) ใช้ `·` หรือ `:` แทน
- ห้ามแต่งข้อมูลธุรกิจหรือของ EA (ราคา ทุน ค่าตั้ง เวลาทำการ คืนเงิน ผลเทรด ชื่อสินค้า ชื่อเซิร์ฟเวอร์) ใช้ `{{wait}}`
- ห้ามคำ: รับประกัน (ในเชิงขาย) กำไรแน่นอน ไม่มีขาดทุน ปลอดภัย 100% รวยเร็ว และ % ต่อเดือนหรือต่อปี
- พูดถึง Zaurix ครั้งแรกในหน้า ต้องบอกว่าเป็นโบรกเกอร์พันธมิตรของ EA BULL · ลิงก์สมัครใช้ `{{link:zaurix}}` เท่านั้น
- ทุกปุ่มที่ไป LINE เป็น `.btn--line` · ไม่มีฟอร์มอีเมล
- ภาพที่มีตัวเลขต้องมีคำบรรยายว่าเป็นภาพประกอบหรือตัวอย่าง
- คำอังกฤษที่ต้องคงตัวพิมพ์ ห่อ `<span class="nocaps">` (อีเมล @LINE ID ชื่อไฟล์ XAUUSD.c iPhone iPad iOS mstsc)
- ทุกหน้า H1 เดียว · ปุ่มและลิงก์ต้องไปหน้าที่มีอยู่ (ชื่อไฟล์ในเมนู)
