# EA BULL · Content spec (seed pages & articles)

Rewritten 3 Nov 2026 for EA BULL's own article plan (`docs/content-plan.md`). The old file was the EA WING spec renamed; its product rules (EA BULL 5.0, WING TWIN, WING NITRO) and its "risk callout in every article" rule do not apply here.

Seed content lives in `eabull/inc/content/pages/<slug>.html` (pages) and
`eabull/inc/content/articles/<slug>.html` (blog posts). The setup tool in
WP admin (Appearance → EA BULL Setup) imports them into WordPress. After import the
owner edits them in the normal WP editor, so the files are only the starting point.

## File format

The first line must be a meta comment with JSON, and the body is plain HTML (no `<html>`, `<head>` or `<body>` tags, and no `<h1>`, because the template prints the H1 from the title):

```html
<!--meta {"title":"...","seo_title":"...","description":"...","keyword":"...","excerpt":"...","category":"...","date":"2026-11-09 09:00"} -->
<p class="lead">...</p>
<h2>...</h2>
...
```

- `title` is the post title (H1). Thai with the keyword phrase first, then a colon and the promise of the article: "บอทเทรดทอง คืออะไร: ...". Keep it under about 70 characters. The cover prints the part before the colon large and the rest as a subtitle.
- `seo_title` is the `<title>` for Yoast. 60 characters or less, ends with ` · EA BULL`, starts with the focus keyword.
- `description` is the meta description. 120 to 155 Thai characters, has the focus keyword once, says what the reader gets. No forbidden sales words.
- `keyword` is the focus keyword exactly as planned in `docs/content-plan.md` (lower case as people type it, e.g. `บอทเทรดทอง`, `trailing stop คือ`).
- `excerpt` is 1 to 2 sentences for article cards.
- `category` must be one of the article clusters in `eab_article_categories()` (`inc/setup.php`), listed in `docs/content-plan.md` section 3. `dev/check-content.php` rejects anything else, and `dev/preview/cover.php` maps each one to its cover kicker and tile colour.
- `date` is the scheduled publish time in site time (Asia/Bangkok), format `"2026-11-09 09:00"`. A future date becomes a WordPress scheduled post, a past date publishes with that date. The checker rejects two articles with the same date.
- `rev` (optional) is the content revision. Setup's "อัปเดตบทความที่มีอยู่" replaces the live article only when the file's `rev` is 2 or more and higher than the imported one.

## Shortcodes (rendered live by the theme)

- `[eabull_line]ข้อความปุ่ม[/eabull_line]` renders the green LINE button (URL from the Customizer; until the LINE OA exists it goes to /go/). Once per article, at the end. Add `pos="<article-slug>"` for click tracking.
- `[eabull_brand]` renders "EA BULL".
- `[eabull_broker]` renders the broker name from the Customizer. `[eabull_broker field="url"]` renders the partner sign-up button (`rel="sponsored noopener"` built in). Only in articles about opening an account, and the sentence before it says the broker is EA BULL's partner.
- `[eabull_calc type="lot"]` and `[eabull_calc type="drawdown"]` render the calculators. Use them only where the reader really calculates something (lot size, recovery after a drop).
- `[eabull_figure name="..."]` renders a theme diagram (`eab_figure_registry()` in `inc/shortcodes.php`). No figure is registered yet (3 Nov 2026), so articles do not use it; use a table or `guide-steps` instead.
- Do not use `[eabull_ea]` in articles. There is no product data yet, and the shortcode shows a "waiting for owner" badge.

## Internal links

Write internal links as `{{home}}/slug/`. The token is replaced with the site URL on import. Articles link each other as `{{home}}/<article-slug>/`.

Pages you may link (keyword the page owns in brackets: no article may use it as its focus keyword):
`how-to-install` (วิธีติดตั้ง EA บน MT5) · `backtest` (Backtest EA MT5) · `forward-test` (Forward Test EA) · `pricing` (แพ็กเกจ EA BULL) · `open-mt5-account` (เปิดบัญชีเทรด MT5) · `deposit` (ฝากเงินเข้าบัญชีเทรด) · `mt5-login` (ล็อกอิน MT5) · `vps-windows` (vps รัน ea) · `vps-android` · `vps-ios` · `tools` (เครื่องคำนวณ Lot) · `risk-disclosure` · `about` · `articles`.

Never link `/go/` from an article body, and never link `product-1..3` (example pages, noindex).

Until a seed article is published, the theme removes links that point to it (`eab_unlink_slugs()` in `inc/setup.php`): an item in a `related-links` list that holds only that link disappears, and a link inside a sentence becomes plain text. So write sentences that still read correctly without the link. This is why articles may link to articles scheduled later.

The link rules per article (pillar, siblings, bridges, money page, anchor text) are in `docs/content-plan.md` section 4.

## HTML components (styled by the theme; use exactly these classes)

```html
<p class="lead">ย่อหน้าเปิด 2 ถึง 3 บรรทัด ตอบคำถามของหัวเรื่องทันที</p>

<div class="callout callout--info"><p><strong>สรุปสั้น:</strong> ...</p></div>
<div class="callout callout--tip"><p>...</p></div>

<ul class="checklist"><li>...</li></ul>          <!-- green check bullets -->
<ul class="crosslist"><li>...</li></ul>          <!-- red x bullets -->

<ol class="guide-steps">                            <!-- numbered step cards -->
  <li><h3>ชื่อขั้นตอน</h3><p>...</p></li>
</ol>

<div class="table-wrap"><table class="data-table">
  <thead><tr><th>..</th><th>..</th></tr></thead>
  <tbody><tr><td>..</td><td>..</td></tr></tbody>
</table></div>

<div class="faq-block">
  <details class="faq-item"><summary><span>คำถาม?</span><i class="faq-plus" aria-hidden="true"></i></summary>
    <div class="faq-answer"><p>คำตอบ</p></div></details>
</div>

<div class="related-links"><h2>อ่านต่อ</h2><ul>
  <li><a href="{{home}}/pricing/">...</a></li>
</ul></div>
```

- `callout--warn` is not used in articles (owner, 29 Oct 2026: no warning boxes outside /risk-disclosure/). A practical caution ("อย่าส่งรหัสผ่านให้ใคร") goes in normal text or a `callout--tip`.
- The theme builds the table of contents from H2/H3 and turns `faq-item` blocks into FAQPage schema. Do not write a TOC.
- No `<img>` (use `[eabull_figure]`), no inline styles or scripts.

## Writing rules (must follow)

1. **Original Thai text only.** Do not copy or paraphrase EA WING, ea2000, FENIX, FALCON or any other site. The owner runs EA WING too, so the same text on two domains is duplicate content. Run `py -3 -I dev/tools/check-dup.py` before import; it must find nothing.
2. **No made-up facts or numbers.** No results, win rate, profit %, drawdown % or trade counts for EA BULL or any EA. Hypothetical examples are fine when they are clearly examples about maths ("สมมติทุน 1,000 USD เสี่ยง 1% = 10 USD"). Facts about markets (contract size, trading hours, central bank data, indicator formulas) are checked against an official source on the day of writing.
3. **EA BULL product facts: none yet.** Until the owner sends the product documents, never describe EA BULL's strategy, settings, symbols, timeframes, recommended capital, modes or results, and never say what EA BULL does or does not do (no "EA BULL ไม่ใช้มาร์ติงเกล", no "EA BULL มี News Filter"). Business facts that are confirmed and may be used: Starter (free consultation), Pro 6,990 บาท (1 to 2 accounts, install yourself with the guide), VIP 9,990 บาท (team installs on your PC or VPS and checks the settings before running); one-time payment, no monthly or yearly fee, licence renewal costs nothing, no refunds; the licence is tied to the MT5 account number + server name; payment by bank transfer to the account the team names in the LINE chat; VPS rent is not included; the team announces new versions on LINE; partner broker Zaurix. Never write the ready-made line "EA BULL ทำ X ให้คุณ" about features nobody confirmed.
4. **No risk warning boxes.** Risk warnings live on /risk-disclosure/ only (owner, 29 Oct 2026). No warn callouts, no warning line under buttons, no "สิ่งที่ซื้อไม่ได้ / ไม่สัญญา" boxes, no "อ่านประกาศความเสี่ยง" checklist items. An article whose topic is risk (drawdown, ล้างพอร์ต, martingale) explains it as knowledge, and may link /risk-disclosure/ once where it fits the sentence.
5. **Forbidden sales words**: รับประกัน · การันตี · กำไรแน่นอน · ไม่มีขาดทุน · ปลอดภัย 100% · รวยเร็ว · any % per month or per year. Only allowed inside a sentence that warns against them ("ถ้าใครบอกว่ากำไรแน่นอน ...").
6. **No em dash or en dash.** Use `·`, `:` or a new sentence.
7. **Sensitive topics** (marked ⚠ in the plan: averaging, DCA, grid-like ideas, scalping, news trading, small capital, prop firms, scams): general knowledge and the mechanism only, never a hint about whether EA BULL uses it.
8. **Not written without asking the owner**: tax, legality of forex or gold CFDs in Thailand, broker licences, reviews or rankings of other EAs, brokers or VPS companies, crypto/stock/binary bots, daily gold price analysis, giving away EA files.
9. **Technical facts that must stay right**: the MT5 data folder is File → Open Data Folder → MQL5 → Experts · the toolbar button is "Algo Trading" · Strategy Tester opens with Ctrl+R · EAs cannot run in the MT5 mobile app · compiled EA files are .ex5 · Microsoft's mobile remote app is "Windows App".
10. **Partner disclosure**: the first time an article names Zaurix it says Zaurix is EA BULL's partner broker. External Zaurix links get `rel="sponsored noopener"` (the theme adds it for zaurix hosts).
11. **Brand mentions**: where it reads naturally, link an existing sentence that introduces EA BULL to the homepage (anchor = the brand name). Never a templated brand paragraph in every article. About one article in three links home.

12. **Brand statements**: only the confirmed business facts in rule 3. Do not write promises about how the team works that nobody confirmed (no "ทีมงาน EA BULL ไม่เคยขอรหัสผ่าน", no response times, no office hours). General safety advice is fine ("อย่าส่งรหัสผ่านหลักของบัญชีเทรดให้ใคร").
13. **Time-proof text**: articles publish between Nov 2026 and Mar 2027. No "ปีนี้", no "ตอนนี้ดอกเบี้ยอยู่ที่ ...". Market numbers that change (rates, prices, spreads) are written as clearly marked examples, or with the source and the check date: "(ข้อมูลจาก <source> ตรวจเมื่อ พ.ย. 2569)". Thai dates use the Buddhist year.

## Article layout (in this order)

1. `<p class="lead">` with the focus keyword: answer the title in 2 to 3 lines.
2. `callout--info` "สรุปสั้น" with a `<ul>` of 3 to 5 points.
3. A short paragraph that links the cluster pillar (supporting articles), before the first H2.
4. Body H2 sections (at least one `data-table` or `guide-steps`; H3 inside where it helps).
5. FAQ H2 + `faq-block`.
6. A closing H2 (topic-specific, e.g. "สรุป: ..." or "EA BULL ช่วยอะไรได้บ้าง") that ties the topic to what EA BULL really offers and carries the money page link.
7. `[eabull_line pos="<slug>"]ข้อความปุ่ม[/eabull_line]` on its own line.
8. `related-links` box "อ่านต่อ" with 3 to 4 items (last thing in the file).

The reference article is `eabull/inc/content/articles/gold-trading-bot.html` (pillar). Follow its structure and HTML, not its sentences.

## SEO rules (every article)

1. Focus keyword in: `title` (first words), `seo_title` (first words), `description`, the lead paragraph (first 100 words), at least one H2, and 3 to 6 times in total. Never stuffed.
2. Secondary and long-tail keywords from the plan: each one appears at least once, best as an H2/H3 or an FAQ question in the words people type.
3. Answer first: the lead paragraph answers the title question in 2 to 3 lines, then a `callout--info` "สรุปสั้น" with 3 to 5 points.
4. Length (Thai text without spaces, outside the related-links box, as `check-articles.py` counts it): pillar 12,000 to 22,000 characters (about 3,000 to 4,000 words; aim for 13,000 to 16,000) with 10 to 14 H2; supporting 5,500 to 12,000 characters (about 1,200 to 2,200 words; aim for 6,500 to 9,500) with 6 to 12 H2. FAQ 4 to 6 questions (pillar 6 to 8) taken from real search phrases. Secondary and long-tail phrases are matched with spaces and case ignored; phrases that contain "pantip" are optional.
5. Links: see `docs/content-plan.md` section 4 (pillar in the first 2 paragraphs, 2 to 3 siblings, 1 to 2 bridges, 1 money page near the end, 1 to 2 external primary sources with `target="_blank" rel="noopener"`).
6. End: a short paragraph that connects the topic to what EA BULL helps with (installation, VPS guides, packages, asking the team), then `[eabull_line pos="<slug>"]...[/eabull_line]`, then `related-links` with 3 to 4 items.
7. Write English technical terms in normal case ("Trailing Stop"). The theme uppercases them visually.
8. Before import: `py -3 -I dev/tools/check-articles.py` no errors (plan, keywords, size, links, words) · `dev/check-content.php` all good · `dev/check-brand.php` no brand leaks · `py -3 -I dev/tools/check-dup.py eabull/inc/content/articles` nothing · covers built with `dev/make-covers.php` (icon per slug in `dev/preview/cover.php`).
9. `check-dup.py` also flags short factual sentences that happen to match EA WING wording, most often the package facts and MT5 steps. Reword the flagged chunk (different order, different words) until it passes; never paste the same package sentence into every article.
10. Anchor text: vary it. The pillar link uses the pillar keyword in a natural phrase ("บอทเทรดทองทำงานยังไง", "พื้นฐานของบอทเทรดทอง"), and no exact anchor text appears in more than 3 articles.
