# EA BULL · Content spec (seed pages & articles)

Seed content lives in `eabull/inc/content/pages/<slug>.html` (pages) and
`eabull/inc/content/articles/<slug>.html` (blog posts). The setup tool in
WP admin (Appearance → EA BULL Setup) imports them into WordPress. After import the
owner edits them in the normal WP editor, so the files are only the starting point.

## File format

The first line must be a meta comment with JSON, and the body is plain HTML (no `<html>`, `<head>` or `<body>` tags, and no `<h1>`, because the template prints the H1 from the title):

```html
<!--meta {"title":"...","seo_title":"...","description":"...","keyword":"...","excerpt":"...","category":"...","kicker":"..."} -->
<p class="lead">...</p>
<h2>...</h2>
...
```

- `title` is the page/post title (H1). Use Thai with the English keyword included, and keep it under about 70 characters.
- `seo_title` is the `<title>` for Yoast. Keep it at 60 characters or less and end it with ` · EA BULL`.
- `description` is the meta description. Aim for 120 to 155 Thai characters and include the focus keyword naturally.
- `keyword` is the focus keyword (Thai or mixed), e.g. `EA MT5`, `VPS สำหรับ EA`.
- `excerpt` is 1 to 2 sentences, used for article cards. It is for articles only.
- `category` is for articles only and must be one of the 8 article clusters (8 Oct 2026, `eab_article_categories()` in `inc/setup.php`, plan in `docs/content-plan.md`): `EA และบอทเทรด` (kicker `EA & Bots`, slug ea-basics), `ใช้งาน MT5` (`MT5 Guide`), `VPS สำหรับ EA` (`VPS`), `เทรดทองคำ XAUUSD` (`Gold Trading`), `บริหารความเสี่ยง` (`Risk Management`), `วางแผนการเทรด` (`Trading Plan`), `ติดตามผล` (`Monitoring`), `บัญชีและโบรกเกอร์` (`Broker & Account`). `dev/check-content.php` rejects anything else, and `dev/preview/cover.php` maps each one to its cover kicker and tile colour.
- `date` (articles, optional) is the scheduled publish time in site time (Asia/Bangkok), format `"2026-10-15 08:00"`. On import a future date becomes a WordPress scheduled post, a past date publishes with that date. The checker rejects two articles with the same date.
- `rev` (optional) is the content revision. Setup's "อัปเดตบทความที่มีอยู่" replaces the live article only when the file's `rev` is 2 or more and higher than the imported one (files without `rev` are never overwritten, so edits made in wp-admin stay).
- `kicker` is a small English label above the H1 on pages (e.g. `Guide`, `Account`, `VPS · Windows`).

## Shortcodes (rendered live by the theme)

- `[eabull_line]ข้อความปุ่ม[/eabull_line]` renders a green LINE button (URL from Customizer). Use it 1 to 2 times per page.
- `[eabull_line pos="vps-guide"]...[/eabull_line]` adds the optional `pos` attribute, which is used for click tracking.
- `[eabull_brand]` renders "EA BULL".
- `[eabull_broker]` renders the broker name set in the Customizer. The default reads like "โบรกเกอร์ที่คุณเลือก", so write sentences that still work generically.
- `[eabull_broker field="server"]` renders the MT5 server name the owner sets. The default is a generic phrase like "ชื่อเซิร์ฟเวอร์ที่ได้รับทางอีเมล".
- `[eabull_ea]` renders the current EA file name (`EA_BULL_V5.0.ex5`). `[eabull_ea field="name"]` drops the extension, `field="version"` gives `5.0`, and `field="bt_range"` gives the standard Strategy Tester date range (`2026.01.01 ถึง 2026.10.01`). Never type the file name, version number or backtest range by hand: they change with every release and `dev/check-content.php` rejects them. EA BULL has three modes: Lite (default, risk level 1 of 3), Advance and Full. Write "Lite, Advance และ Full" and do not describe Lite as risk-controlling or less volatile.
- `[eabull_ea product="nitro"]` and `[eabull_ea product="twin"]` render the file names of the other two products, WING NITRO (`WING_NITRO_V4.2.ex5`) and WING TWIN (`WING_TWIN_V6.3.ex5`), with `field="name|version"`; `field="bt_range"` works for NITRO (`2026.01.01 ถึง 2026.08.31`). Never type `WING_NITRO_V...` or `WING_TWIN_V...` by hand. When the products are listed together, order them by version number: WING NITRO 4.2, EA BULL 5.0, WING TWIN 6.3. Never call one of them "the second product".
- `[eabull_figure name="license-flow"]` renders a theme diagram (wide + mobile versions, alt text built in). Names: `how-it-works`, `license-flow`, `vps-flow`, `account-flow` (registry `eab_figure_registry()` in `inc/shortcodes.php`, images made with `dev/make-art.php`). Put it on its own line. Optional `alt=""` and `caption=""`.
- `[eabull_calc type="lot"]` renders the lot size calculator, and `[eabull_calc type="drawdown"]` renders the drawdown recovery calculator. Use these only where relevant.

## Internal links

Write internal links as `{{home}}/slug/`, for example `<a href="{{home}}/how-to-install/">`. The token is replaced with the site URL on import.

Valid page slugs:
- `how-to-install`
- `backtest`
- `forward-test`
- `pricing`
- `risk-disclosure`
- `open-mt5-account`
- `deposit`
- `mt5-login`
- `vps-windows`
- `vps-android`
- `vps-ios`
- `tools`
- `about`
- `privacy-policy`
- `terms-of-use`
- `data-deletion`
- `go`
- `articles`

Articles link each other as `{{home}}/<article-slug>/`, because posts use the /%postname%/ permalink.

Articles with a `date` are imported as scheduled posts (others as drafts). Until a seed article is published, the theme removes links that point to it (`eab_unlink_slugs()` in `inc/setup.php`): an item in a `related-links` list that holds only that link disappears, and a link inside a sentence becomes plain text. So write sentences that still read correctly without the link.

On pages that go live before the articles (guides, backtest, forward-test), wrap a "read more in article X" sentence in `<span class="xref">…</span>` (or use `<p class="xref">` for a whole paragraph). The theme then hides the whole sentence while its target is still a draft, instead of leaving an unlinked title behind. `dev/check-content.php` simulates "every article is a draft" and fails if headings, tables, FAQ items or risk warnings would be lost.

A new article needs two things: the file in `inc/content/articles/` (every file there is picked up automatically) and a cover built with `dev/make-covers.php` (icon per slug in `dev/preview/cover.php`).

**Brand links to the homepage** (owner, 8 Oct 2026): only where it reads naturally. Link an existing sentence that introduces EA BULL (e.g. "EA BULL เป็นไฟล์ EA ที่คุณรันบน MT5"), anchor = the brand name. Never add a templated brand sentence to every article, never link the brand inside partner disclosures, parentheses or sensitive topics (martingale, grid, hedging, scalping). The header logo already links home on every page. About a third of the articles link home.

**Clusters (pillar + supporting articles).** Each category has one pillar article (`eab_cluster_pillars()` in `inc/modules/clusters.php`). Supporting articles link up to the pillar in the opening, to 2 to 3 siblings, to 1 to 2 articles of other clusters, and to one money page at the end. The pillar links down to every supporting article. The theme also prints an automatic "บทความในชุดนี้" box under each article (published posts only) and puts the pillar first on its category page. Put the keyword phrase before a colon in the title when you can ("Drawdown คืออะไร: ..."), because the cover prints the part before the colon large and the rest as a subtitle.

## HTML components (styled by the theme; use exactly these classes)

```html
<p class="lead">ย่อหน้าเปิด 2 to 3 บรรทัด สรุปว่าหน้านี้ตอบอะไร</p>

<div class="callout callout--info"><p><strong>สรุปสั้น:</strong> ...</p></div>
<div class="callout callout--tip"><p>...</p></div>
<div class="callout callout--warn"><p>...</p></div>

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

<div class="related-links"><h2>คู่มือที่เกี่ยวข้อง</h2><ul>
  <li><a href="{{home}}/vps-windows/">...</a></li>
</ul></div>
```

- The theme builds a table of contents from the H2/H3 headings automatically, so do not write your own TOC.
- The FAQ `details.faq-item` blocks are automatically turned into FAQPage schema.
- Do not add `<img>` tags (use `[eabull_figure]` for theme diagrams). The owner adds real screenshots in the editor.
- Do not add inline styles or scripts.

## Writing rules (must follow)

1. **Write original Thai text.** Do not copy wording from other websites, including ea2000.co. Use a friendly, clear, professional tone written for Thai retail traders, with short paragraphs.
2. **Never invent performance numbers.** That means no win rate, profit %, drawdown %, trade counts or "results" for EA BULL. Generic educational examples are fine when they are clearly hypothetical (e.g. "สมมติทุน 1,000 USD เสี่ยง 1% = 10 USD"), are about maths or risk, and are never about EA BULL's results.
3. **Never promise or guarantee profit.** Avoid words like "รวยเร็ว", "กำไรแน่นอน", "ไม่มีขาดทุน" or "การันตี", except when warning against them.
4. **Every page and article must include risk context.** At least one `callout--warn` that says trading carries risk and can lose capital, and that past results do not guarantee future results.
5. **EA BULL product facts come only from the owner's 5.0 documents** (owner decision, 12 Oct 2026; see rule 2 in `CLAUDE.md`). EA BULL is a "ระบบทบไม้": it opens orders as a set, adds bigger recovery orders when price goes against it (up to 9 steps), and the preset modes have no automatic stop loss, so an account can be wiped out. Say this wherever EA BULL's results or risks are discussed; never soften it. When you explain the modes, give the recommended account money: Lite 1x, Advance 2x, Full 3x of "ทุนคิดไม้". Recommended capital: cent account about 1,000 USD (100,000 cent) or more, standard account about 100,000 USD or more. A daily profit target is a stop point of the system, never an expected return. Do not use profit percentages, monthly results, or any numbers from version 4.2.
6. **WING TWIN is a separate product** (owner request, 19 Oct 2026; version 6.3 since 27 Oct 2026). Its facts come only from the owner's WING_TWIN_V6.3 folder (customer manual PDF, "อ่านก่อน - WING TWIN 6.3" txt, the MT5 reports for N 1 and N 2). It is a two-way grid (DUAL GRID, one preset WING LIFE plus custom): 150-point grid, counter orders × 1.6 up to 4 compounds, up to 60 orders per cycle, cycles start when ADX M30 ≥ 18 between 11:00 and 24:00 Thai time. Recovery orders ("ไม้กู้"): when a basket is stuck over 90 market minutes, a recovery order opens at the edge of the 70-minute range at the largest lot × 1.3, then × 1.3 again, at most 9 per side, and the whole basket closes at break-even. There is no stop loss of any kind; say so wherever its results or risks appear. Size N is 1/2/4/8/16 (N 1 = 0.02 lot); N 4, 8 and 16 were not tested and the owner gives no recommended capital, so only say the account must absorb the floating loss of the chosen N. Any profit figure must sit next to the worst equity drop (N 1: 10,000 USD · 199,699.44 · PF 1.15 · Equity DD Relative 83.53% · Maximal 13,566.10 / N 2: 20,000 USD · 398,731.48 · PF 1.14 · Maximal 41,845.90), the 79.5% share of profit from the simulated-price period, the real-tick profit (+37,443.39 / +74,939.86), the 5 days without new cycles that were chosen from past results (without the skip: 203,537.73 / DD 21,000.23) and the conditions line (1 ม.ค. ถึง 30 ก.ย. 2026 · XAUUSD.s Zaurix · M1). Manual orders on the same gold symbol follow the readme (build 6.37, owner 27 Oct 2026): the EA adds their P/L when checking the cycle target and closes them with the cycle; they are not counted in the grid or recovery orders. Never: Feb to Mar or best-month figures as a selling point (only as a limitation), % per month or per year, claims for cent accounts, N 4 or above or custom start times presented as tested, "ไม่ใช่มาร์ติงเกล", AI or direction-predicting wording, or the monthly-% ad images. WING TWIN uses the same Starter/Pro/VIP packages and prices as EA BULL 5.0 (owner, 19 Oct 2026: the difference is the internal trading system); one package and one licence cover all three EAs (owner, 21 Oct 2026); an account that has the licence can run any of them, but only one EA per gold symbol per account. Do not tell readers to run EA BULL 5.0 and WING TWIN on the same gold symbol in one account.
7. **WING NITRO is a separate product** (owner request, 21 Oct 2026). Facts come only from the owner's WING_NITRO_V4.2 folder (customer manual PDF, วิธีใช้, มีอะไรใหม่, results summary PDF, MT5 report, campaign text). It is a martingale basket system (recovery orders in a ladder with growing lot size) with no stop loss; say so wherever its results or risks appear. Lot size uses the multiplier system (10,000 lot capital per 15,000 account money = x1, up to x60); cent accounts start at 15,000 cent, USD accounts need 100,000 USD. Its licence is shared with EA BULL 5.0. Any profit figure must sit next to the max floating loss (14,762.52), the conditions line, the advance-known 2026 no-trade calendar (ended 5 Aug 2026), the 60.5% of profit from simulated prices and the real-tick profit. Never: % per month or total % return, monthly results, the extra test runs' profit figures, ready-to-post images, internal set values (step count, multiplier, spacing, trading hours), the word FENIX.
8. **Do not invent facts about the business.** That covers company name, address, registration, broker partnerships, prices, refund terms, and team names or photos. Where such a fact is needed, either keep it generic ("ทีมงาน", "โบรกเกอร์ที่รองรับ") or use the shortcodes above. For legal pages, write standard clauses and refer to the business as "[eabull_brand]" or "เรา".
9. **Keep technical facts about MT5, VPS and Windows App accurate.**
   - Microsoft's remote desktop app on Android/iOS is now called "Windows App" (formerly "Remote Desktop").
   - The MT5 data folder path is File → Open Data Folder → MQL5 → Experts.
   - The toolbar button is called "Algo Trading" in current MT5 builds (formerly "AutoTrading").
   - The Strategy Tester opens with Ctrl+R.
   - EAs cannot run in the MT5 mobile app.
   - Use .ex5 for compiled files.
10. **SEO:** use the focus keyword in the title, the first paragraph, at least one H2, and naturally 3 to 6 times in total. Add related Thai search phrases, such as บอทเทรด, โรบอทเทรด, EA เทรดทอง, EA forex, MT5, and วิธีติดตั้ง EA.
11. **Structure and length:** articles are 1,200 to 2,200 Thai words, with 6 to 12 H2s, some H3s, and a FAQ block (3 to 5 Qs) near the end. They end with a short CTA paragraph plus `[eabull_line]`. Guide pages follow the per-page outline you are given.
12. Write English technical terms in normal case (e.g. "Stop Loss"). The theme uppercases them visually.
