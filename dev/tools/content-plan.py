"""EA BULL article plan (phase 4) · single source for the generated parts of docs/content-plan.md and dev/content/plan.json.

  py -3 -I dev/tools/content-plan.py           → check the plan, rewrite the generated sections + plan.json
  py -3 -I dev/tools/content-plan.py --check   → check only (exit 1 on problems)

Checks: 120 articles · 12 per cluster · slugs and focus keywords unique · no focus keyword that EA WING's
articles (D:\\EA VIDEO\\ea-wing-repo, read only) or EA BULL's own pages already use · every bridge exists and
sits in another cluster · no forbidden sales words or dashes in titles · pillars are in round 1.
Stars: ★★★ autocomplete shows many variants and the topic is close to using an EA · ★★ in autocomplete
or a common customer question · ★ specialist term, little competition, completes the cluster.
Keyword evidence: dev/content/keywords-raw.json + keywords-raw-2.json (dev/tools/kw-suggest.py, 3 Nov 2026).
"""
import datetime as dt
import json
import re
import sys
from pathlib import Path

sys.stdout.reconfigure(encoding='utf-8')
ROOT = Path(__file__).resolve().parents[2]
DOC = ROOT / 'docs' / 'content-plan.md'
JSON_OUT = ROOT / 'dev' / 'content' / 'plan.json'
WING_ARTICLES = Path(r'D:\EA VIDEO\ea-wing-repo\eawing\inc\content\articles')
OUR_PAGES = ROOT / 'eabull' / 'inc' / 'content' / 'pages'

START = dt.date(2026, 11, 9)  # first publish day · move it and rerun to shift the whole calendar
TIME = '09:00'
PER_CLUSTER = 12
PAGES = {'home': '/', 'pricing': '/pricing/', 'how-to-install': '/how-to-install/', 'backtest': '/backtest/',
         'forward-test': '/forward-test/', 'open-mt5-account': '/open-mt5-account/', 'deposit': '/deposit/',
         'mt5-login': '/mt5-login/', 'vps-windows': '/vps-windows/', 'vps-android': '/vps-android/',
         'vps-ios': '/vps-ios/', 'tools': '/tools/', 'risk-disclosure': '/risk-disclosure/', 'about': '/about/'}
FORBIDDEN = re.compile(r'รับประกัน|การันตี|กำไรแน่นอน|ไม่มีขาดทุน|ปลอดภัย 100|รวยเร็ว|ต่อเดือน|ต่อปี|[–—]')
THAI_DAY = ['จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.', 'อา.']
THAI_MON = ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.']

CLUSTERS = {}  # publishing order = insertion order (closest to using an EA first)
ARTICLES = []


def C(key, name, money, sources):
    CLUSTERS[key] = {'slug': key, 'name': name, 'money': money, 'sources': sources}


def A(cluster, slug, title, kw, stars, rnd, kw2='', lt='', br='', money=None, note=''):
    """kw2 / lt / br are ' · ' separated · money = page key in PAGES (None = the cluster default)"""
    split = lambda s: [x.strip() for x in s.split('·') if x.strip()]
    first = not any(a['cluster'] == cluster for a in ARTICLES)
    ARTICLES.append({'cluster': cluster, 'slug': slug, 'title': title, 'kw': kw, 'stars': stars, 'round': rnd,
                     'kw2': split(kw2), 'longtail': split(lt), 'bridges': split(br),
                     'money': money or CLUSTERS[cluster]['money'], 'note': note, 'pillar': first})


# ─── 1 · บอทเทรดทอง ──────────────────────────────────────────────────────────
C('gold-bot', 'บอทเทรดทอง', 'home', 'mql5.com/en/docs (MQL5 Reference) · metatrader5.com/en/terminal/help/algotrading (Algo Trading)')
A('gold-bot', 'gold-trading-bot', 'บอทเทรดทอง คืออะไร: ทำงานยังไงบน MT5 และเลือกแบบไหนดี', 'บอทเทรดทอง', 3, 1,
  'บอทเทรด คือ · บอทเทรดทอง mt5 · โรบอทเทรด คืออะไร · ea xauusd mt5 · การใช้บอทเทรดทอง',
  'บอทเทรด คือ อะไร · บอทเทรด ea คือ · robot เทรดทอง · xauusd ea bot · ใช้บอทเทรดทอง',
  'run-ea-guide · backtest-explained')
A('gold-bot', 'gold-bot-worth-it', 'บอทเทรดทองดีไหม: ข้อดี ข้อจำกัด และคนแบบไหนเหมาะ', 'บอทเทรดทองดีไหม', 3, 1,
  'บอทเทรด ดีไหม · บอทเทรด pantip · ใช้ ea เทรด', 'ใช้บอทเทรดทองดีไหม · บอทเทรดทองดีไหม pantip',
  'trading-scams · overfitting-trading', 'pricing')
A('gold-bot', 'how-to-use-trading-bot', 'วิธีใช้บอทเทรด: เปิดให้ EA เทรดบน MT5 ครั้งแรกทีละขั้น', 'วิธีใช้บอทเทรด', 3, 1,
  'วิธีใช้ ea mt5 · การใช้ ea · สอนใช้ ea mt5 · การใช้ ea ใน mt5', 'วิธีใช้บอทเทรด forex · สอนใช้บอทเทรด · วิธีเปิดใช้งาน ea mt5',
  'ea-settings · run-ea-guide', 'how-to-install', 'ห้ามแย่งคีย์เพจ /how-to-install/ (วิธีติดตั้ง EA บน MT5): บทนี้คือภาพรวมการใช้ ส่งต่อไปเพจติดตั้ง')
A('gold-bot', 'gold-trading-program', 'โปรแกรมเทรดทอง: เทรดทองใช้โปรแกรมอะไร และตัวไหนเทรดแทนได้', 'โปรแกรมเทรดทอง', 2, 1,
  'เทรดทองใช้โปรแกรมอะไร · โปรแกรมเทรดทอง mt5 · โปรแกรมเทรดทองในคอม · โปรแกรมวิเคราะห์เทรดทอง',
  'โปรแกรม ai เทรดทอง · โปรแกรมเทรดทองคำ', 'mt5-web-terminal · gold-mt5-first-order', 'how-to-install')
A('gold-bot', 'gold-trading-system', 'ระบบเทรดทอง: ระบบที่ดีต้องมีอะไรบ้าง ก่อนให้บอทเทรดแทน', 'ระบบเทรดทอง', 2, 1,
  'ระบบเทรดทองคำ · ระบบเทรดที่ดี · ระบบเทรดทองที่ดีที่สุด · ระบบเทรด ea คือ', 'ระบบเทรด คือ · ระบบเทรดทอง m5',
  'gold-trading-techniques · backtest-explained', 'backtest')
A('gold-bot', 'buy-ea-mt5', 'ซื้อ EA MT5: ขั้นตอนซื้อบอทเทรดทองให้ปลอดภัย ตั้งแต่คุยจนติดตั้ง', 'ซื้อ ea mt5', 2, 1,
  'ซื้อ ea เทรดทอง · ซื้อ ea mql5 · คนซื้อ ea · บอทเทรดทอง ราคา', 'ซื้อ ea ใน mql5 · ซื้อรหัส ea',
  'fake-pages · ea-review-check', 'pricing', 'ใช้ข้อเท็จจริงแพ็กเกจที่ยืนยันแล้วเท่านั้น (content-spec ข้อ 3)')
A('gold-bot', 'algo-trading', 'Algo Trading คืออะไร: เทรดด้วยอัลกอริทึมบน MT5 ทำงานยังไง', 'algo trading คือ', 2, 2,
  'algo trading mt5 คือ · mql5 algo trading คือ · algo trading metatrader 5', 'algo trading คือ อะไร',
  'strategy-tester-mt5', 'how-to-install')
A('gold-bot', 'build-trading-bot', 'สร้างบอทเทรดเอง: เขียนบอทเทรดทองบน MT5 ต้องรู้อะไรบ้าง', 'สร้างบอทเทรด', 3, 2,
  'เขียนบอทเทรด · สร้างบอทเทรด mt5 · สร้างบอทเทรดทอง · สอนเขียนบอทเทรด · วิธีสร้างบอทเทรด',
  'การสร้างบอทเทรดใน mt5 · ea ใช้ภาษาอะไร · รับเขียนบอทเทรด', 'backtest-explained · overfitting-trading', 'pricing')
A('gold-bot', 'ai-trading-bot-builder', 'ให้ AI สร้างบอทเทรด: ได้จริงแค่ไหน และต้องเช็กอะไรก่อนใช้เงินจริง', 'ai สร้างบอทเทรด', 2, 2,
  'ai เขียนบอทเทรด · สร้างบอทเทรดด้วย ai · ใช้ ai สร้าง ea · บอทเทรด ai', 'ea ที่ใช้ ai · เทรดทองด้วย ai',
  'walk-forward · ai-investment-scam', 'pricing')
A('gold-bot', 'rent-vs-buy-ea', 'เช่า EA หรือซื้อขาด: เทียบ EA รายเดือนกับจ่ายครั้งเดียว', 'เช่า ea', 2, 2,
  'ea รายเดือน · เช่าea เทรด · ea ฟรีตลอดชีพ', 'สมัคร ea รายเดือน', 'trading-scams', 'pricing',
  'EA BULL จ่ายครั้งเดียว ไม่มีรายเดือน (ยืนยันแล้ว)')
A('gold-bot', 'copy-trade-gold', 'Copy Trade ทอง กับบอทเทรดทอง: ตามออเดอร์คนอื่นหรือให้ EA เทรด', 'copy trade ทอง', 2, 2,
  'copy trade ดีไหม · ระบบ copy trade · ea copy trade คือ · social trading คือ', 'copy trade รีวิว · copy trade ทำยังไง',
  'ea-review-check', None, 'EA WING มี "copy trade คือ" แล้ว: บทนี้เทียบกับบอทเทรดทองเท่านั้น')
A('gold-bot', 'ea-vs-indicator', 'EA กับ Indicator ต่างกันยังไง: ไฟล์ไหนเทรดให้ ไฟล์ไหนแค่ชี้สัญญาณ', 'ea กับ indicator', 1, 2,
  'indicator mt5 · script mt5 · ไฟล์ ex5', '', 'what-is-indicator', 'how-to-install')

# ─── 2 · รัน EA 24 ชั่วโมง ───────────────────────────────────────────────────
C('run-ea', 'รัน EA 24 ชั่วโมง', 'vps-windows', 'learn.microsoft.com (Windows App · Remote Desktop · Power options) · metatrader5.com/en/terminal/help (Virtual Hosting · Web Terminal)')
A('run-ea', 'run-ea-guide', 'รัน EA คืออะไร: วิธีรัน EA บน MT5 ให้ทำงานต่อเนื่อง 24 ชั่วโมง', 'รัน ea', 3, 1,
  'รัน ea คือ · วิธีรัน ea mt5 · การรัน ea · วิธีรัน ea บน vps · คอมรัน ea', 'รันea คืออะไร · การรัน ea mt5 · รัน ea บนมือถือ',
  'how-to-use-trading-bot · gold-trading-bot', 'vps-windows', 'คีย์ "vps รัน ea" เป็นของเพจ /vps-windows/ ห้ามใช้เป็นคีย์หลัก')
A('run-ea', 'vps-explained', 'VPS คืออะไร: ทำไมคนใช้ EA ถึงเช่า VPS แทนเปิดคอมทิ้งไว้', 'vps คืออะไร', 3, 1,
  'vps คืออะไร forex · vps mt5 คือ · เช่า vps คือ · cloud vps คือ · vps server คือ', 'vps คือ อะไร pantip · metatrader vps คือ · vps hosting คือ',
  'gold-trading-bot')
A('run-ea', 'ea-per-vps', 'VPS รัน EA ได้กี่ตัว: คิดจาก CPU RAM และจำนวน MT5', 'vps รัน ea ได้กี่ตัว', 2, 1,
  'vps สําหรับ mt5 · vps รัน mt5 · vps mt5 ราคา', 'mt5 กินแรมเท่าไหร่', 'ea-settings')
A('run-ea', 'remote-desktop', 'Remote Desktop คืออะไร: ควบคุม VPS ระยะไกลเพื่อดูแล EA', 'remote desktop คือ', 2, 1,
  'remote desktop วิธีใช้ · remote desktop มือถือ · remote desktop คีย์ลัด', 'remote desktop ค้าง · remote desktop กระตุก · remote desktop ทำยังไง',
  'mt5-2fa')
A('run-ea', 'windows-app', 'Windows App คืออะไร: แอปเข้า VPS จากมือถือที่มาแทน Remote Desktop', 'windows app คือ', 2, 1,
  'windows app วิธีใช้ · windows app mobile คือ · สมัคร windows app', 'windows app mobile วิธีใช้', 'mt5-2fa', 'vps-android',
  'ลิงก์ทั้ง /vps-android/ และ /vps-ios/')
A('run-ea', 'pc-sleep-ea', 'ปิดสลีปคอม: ตั้ง Windows ไม่ให้หลับระหว่างรัน EA', 'ปิดสลีปคอม', 2, 1,
  'ปิดคอมแล้ว ea ทำงานไหม · ปิดคอมออโต้ · ปิดอัพเดทคอม', 'ปิดจอคอมแต่ไม่ปิดเครื่อง', 'gold-weekend')
A('run-ea', 'mt5-web-terminal', 'MT5 Web Terminal: เทรดบนเว็บได้ แต่รัน EA ได้ไหม', 'mt5 web terminal', 2, 2,
  'mt5 บนเว็บ · mt5 webtrader · mt5 web login · mt5 web app', 'mt5 web terminal real account', 'gold-trading-program', 'mt5-login')
A('run-ea', 'mt5-cloud', 'MT5 Cloud: Virtual Hosting กับ MQL5 Cloud Network ต่างกันยังไง', 'mt5 cloud', 1, 2,
  'mt5 cloud server · mt5 cloud vps · mql5 cloud network', 'mt5 cloud network mode is off', 'optimize-ea-mt5')
A('run-ea', 'ea-not-working-check', 'EA MT5 ไม่ทำงาน: เช็กทีละจุดว่า EA ยังรันอยู่จริงไหม', 'ea mt5 ไม่ทำงาน', 2, 2,
  'ea ไม่ทำงาน · ea ไม่ทำงาน mt5', 'ไอคอน ea บนกราฟ · ea หายจากกราฟ', 'ea-settings', 'how-to-install',
  'EA WING มี "EA ไม่เปิดออเดอร์" (EA รันแต่ไม่เทรด): บทนี้คือ EA ไม่รันเลย')
A('run-ea', 'mt5-update', 'อัปเดต MT5: วิธีอัปเดตในคอมและ VPS โดยที่ EA ไม่สะดุด', 'mt5 อัพเดท', 2, 2,
  'อัพเดท mt5 ยังไง · วิธีอัพเดท mt5 ในคอม · mt5 อัพเดท ล่าสุด', '', 'download-mt5', 'mt5-login')
A('run-ea', 'mt5-linux', 'MT5 บน Linux: รันบน VPS Linux ได้ไหม และต่างจาก Windows ตรงไหน', 'mt5 linux', 1, 2,
  'mt5 linux vps · mt5 linux ubuntu · mt5 linux install', '', 'strategy-tester-mt5')
A('run-ea', 'ea-power-outage', 'ไฟดับ เน็ตหลุด ระหว่าง EA เทรด: ออเดอร์ค้างเป็นยังไง และต้องเช็กอะไร', 'ไฟดับ ea', 1, 2,
  'เน็ตหลุด mt5 · mt5 หลุด · ea หยุดทำงาน', '', 'stop-loss-take-profit', None, 'คำถามลูกค้า (Autocomplete ยังไม่มี)')

# ─── 3 · ทดสอบและปรับค่า EA ─────────────────────────────────────────────────
C('ea-testing', 'ทดสอบและปรับค่า EA', 'backtest', 'metatrader5.com/en/terminal/help/algotrading/testing (Strategy Tester) · mql5.com/en/docs')
A('ea-testing', 'backtest-explained', 'Backtest คืออะไร: ทดสอบระบบเทรดย้อนหลังให้เป็นก่อนใช้เงินจริง', 'backtest คือ', 3, 1,
  'การ backtest ea · backtest ทอง · วิธี backtest mt5 · backtest forex คือ · ทำ backtest mt5',
  'backtest คือ อะไร · สอน backtest mt5 · backtest กราฟ', 'gold-trading-system · gold-bot-worth-it', 'backtest',
  'เพจ /backtest/ ถือคีย์ "Backtest EA MT5" (วิธีทดสอบ EA BULL): บทนี้คือความรู้ แล้วส่งต่อไปเพจ')
A('ea-testing', 'ea-settings', 'ตั้งค่า EA: ช่อง Inputs ใน MT5 อ่านยังไง แก้ตรงไหนได้บ้าง', 'ตั้งค่า ea', 2, 1,
  'ตั้งค่า ea mt5 · วิธีตั้งค่า ea · การตั้งค่า ea forex', 'ค่า input ea · ตั้งค่า ea ก่อนรัน',
  'how-to-use-trading-bot · fixed-lot-vs-auto-lot', 'how-to-install', 'ห้ามบอกค่าตั้งของ EA BULL (ยังไม่มีข้อมูล)')
A('ea-testing', 'every-tick-real-ticks', 'Every Tick Based on Real Ticks คืออะไร: โหมดทดสอบที่ใกล้ตลาดจริงที่สุด', 'every tick based on real ticks', 2, 1,
  'every tick based on real ticks คือ · every tick vs every tick based on real ticks · every tick based on real ticks mt5', '',
  'xauusd-spread')
A('ea-testing', 'overfitting-trading', 'Overfitting คืออะไร: ทำไม EA ที่ Backtest สวยถึงพังในบัญชีจริง', 'overfitting คือ', 2, 1,
  'curve fitting · overfitting เกิดจาก · overfitting and underfitting คือ', 'ปัญหา overfitting คือ',
  'gold-bot-worth-it · ea-review-check', 'forward-test')
A('ea-testing', 'optimize-ea-mt5', 'Optimize EA MT5: หาค่าที่เหมาะโดยไม่หลอกตัวเอง', 'optimize ea mt5', 2, 1,
  'optimization คือ · การ optimization · optimization mt5 · ปรับค่า ea', 'optimization คืออะไร', 'mt5-cloud')
A('ea-testing', 'walk-forward', 'Walk Forward คืออะไร: ทดสอบ EA แบบแบ่งช่วงให้ผลน่าเชื่อขึ้น', 'walk forward คือ', 2, 1,
  'walk forward analysis mt5 · walk forward optimization · walk forward test', 'walk forward validation คือ · forward test คือ',
  'ai-trading-bot-builder', 'forward-test')
A('ea-testing', 'strategy-tester-mt5', 'Strategy Tester MT5: เมนูและโหมดทดสอบทุกตัวที่ต้องรู้', 'strategy tester mt5', 2, 2,
  'strategy tester · mt5 strategy tester agent · strategy tester mt5 shortcut', '', 'algo-trading',
  None, 'EA WING มี "backtest mt5 ไม่ได้" (แก้ปัญหา): บทนี้คือแผนที่เมนู')
A('ea-testing', 'tick-data-mt5', 'Tick Data คืออะไร: ข้อมูลราคาทีละ Tick สำคัญกับการทดสอบ EA ยังไง', 'tick data คือ', 1, 2,
  'tick data mt5 · tick data manager · tick data download', '', 'bid-ask')
A('ea-testing', 'max-spread', 'Max Spread คืออะไร: ค่าที่ EA ใช้กันเข้าออเดอร์ตอนสเปรดกว้าง', 'max spread คือ', 2, 2,
  'max spread forex · xauusd max spread · max spread points', '', 'xauusd-spread', 'how-to-install')
A('ea-testing', 'news-filter-ea', 'News Filter EA: หยุดเปิดออเดอร์ช่วงข่าวแรง ทำงานยังไง', 'news filter ea', 1, 2,
  'news filter ea mt5 · mql5 news filter ea', '', 'fed-rates-gold', None, '⚠')
A('ea-testing', 'monte-carlo-trading', 'Monte Carlo คืออะไร: สุ่มลำดับออเดอร์ดูความเสี่ยงที่ Backtest ไม่บอก', 'monte carlo คือ', 1, 2,
  'monte carlo simulation คือ · monte carlo method คือ · walk forward monte carlo', '', 'blown-account')
A('ea-testing', 'visual-mode-mt5', 'Visual Mode MT5: ดู EA เทรดย้อนหลังทีละแท่งเทียน', 'visual mode mt5', 1, 2,
  'strategy tester visualization · backtest แบบเห็นกราฟ', '', 'candlestick-chart')

# ─── 4 · เทรดอย่างปลอดภัย ───────────────────────────────────────────────────
C('trading-safety', 'เทรดอย่างปลอดภัย', 'about', 'sec.or.th (เตือนภัยและตรวจสอบรายชื่อ) · thaipoliceonline.go.th (แจ้งความออนไลน์) · metatrader5.com (OTP)')
A('trading-safety', 'trading-scams', 'มิจฉาชีพสายเทรด: กลโกงชวนเทรดทองและวิธีเช็กก่อนโอนเงิน', 'มิจฉาชีพ เทรด', 3, 1,
  'มิจฉาชีพ ชวน เทรด ทอง · หลอกลงทุนทองคำ · เทรดทอง หลอกลวง · มิจฉาชีพ หลอก เทรด ทอง · เทรดทอง โดนหลอก',
  'หลอกลงทุน forex · เทรดทอง มิจฉาชีพ · รับเทรดแทน', 'buy-ea-mt5 · rent-vs-buy-ea', 'about',
  '⚠ · EA WING มี "บอทเทรด หลอก" (ผู้ขายบอท): บทนี้คือมิจฉาชีพชวนเทรด')
A('trading-safety', 'is-gold-trading-real', 'เทรดทองมีจริงไหม: แยกการเทรดจริงออกจากแอปหลอกลงทุน', 'เทรดทอง มีจริงไหม', 2, 1,
  'app หลอกลงทุน · หลอกลงทุน ทอง · เทรดทอง หลอกลวง ไหม', 'เทรด ทอง ถอนเงิน ไม่ ได้', 'what-is-gold-trading', 'open-mt5-account')
A('trading-safety', 'fake-pages', 'เพจปลอม: เช็กเพจ Facebook และบัญชี LINE ที่แอบอ้างทีมเทรด', 'เพจปลอม', 2, 1,
  'เพจปลอมดูยังไง · ระวังเพจปลอม · รายงานเพจปลอม · เพจทองปลอม', 'ค้นหาเพจปลอม · เพจปลอมคือ', 'buy-ea-mt5', 'about',
  'ช่องทางจริงของ EA BULL: Facebook และ OpenChat (ยืนยันแล้ว) · LINE OA ยังไม่มี')
A('trading-safety', 'prop-firm', 'กองทุน Forex คืออะไร: สอบกองทุนทำงานยังไง และใช้ EA ได้ไหม', 'กองทุน forex', 3, 1,
  'กองทุน forex คือ อะไร · สอบกองทุน forex · สอบกองทุน forex คือ อะไร · กองทุน forex ที่น่าเชื่อถือ · เทรด กองทุน forex คือ',
  'สอบ กองทุน forex เพื่อ อะไร · สอบ กองทุน forex ได้ อะไร · กองทุน forex ไม่ต้องสอบ', 'drawdown-limiter · gold-trading-bot', 'home',
  '⚠ ไม่จัดอันดับหรือแนะนำกองทุนเจ้าไหน')
A('trading-safety', 'ea-review-check', 'รีวิวบอทเทรด: อ่านรีวิว EA ยังไงให้ไม่โดนหลอก', 'รีวิวบอทเทรด', 2, 1,
  'รีวิว ea · copy trade รีวิว · myfxbook รีวิว', '', 'overfitting-trading · copy-trade-gold', 'forward-test')
A('trading-safety', 'ai-investment-scam', 'AI หลอกลงทุน: คลิปปลอมคนดังชวนเทรด สังเกตยังไง', 'ai หลอกลงทุน', 2, 1,
  'หลอกลงทุนออนไลน์ · หลอก ลงทุน คือ · deepfake หลอกลงทุน', '', 'ai-trading-bot-builder', 'about', '⚠ ตรวจข้อเท็จจริง')
A('trading-safety', 'download-mt5', 'ดาวน์โหลด MT5: โหลดของแท้จากที่ไหน ทั้งคอม มือถือ และ VPS', 'ดาวน์โหลด mt5', 3, 2,
  'ดาวน์โหลด mt5 pc · ดาวน์โหลด mt5 ฟรี · วิธีดาวน์โหลด mt5 ในคอม · download mt5 for windows',
  'ดาวน์โหลด mt5 ลงคอม · วิธีดาวน์โหลด mt5 ในโน้ตบุ๊ก', 'mt5-update · run-ea-guide', 'mt5-login',
  'เพจ /mt5-login/ ถือคีย์ "ล็อกอิน MT5": บทนี้เน้นแหล่งโหลดของแท้ · ห้ามใช้ชื่อโบรกเกอร์อื่น')
A('trading-safety', 'forex-ponzi', 'แชร์ลูกโซ่ Forex: สังเกตยังไง ต่างจากการเทรดจริงตรงไหน', 'แชร์ลูกโซ่ forex', 2, 2,
  'แชร์ลูกโซ่ forex คืออะไร · คดีแชร์ลูกโซ่ forex · ข่าวแชร์ลูกโซ่ forex', '', 'what-is-gold-trading', None, '⚠ ไม่เล่ารายละเอียดคดีหรือชื่อบุคคล')
A('trading-safety', 'investment-scam-report', 'โดนหลอกลงทุนต้องทำยังไง: ขั้นตอนแจ้งความและอายัดบัญชี', 'โดนหลอกลงทุน', 2, 2,
  'หลอกลงทุนออนไลน์ แจ้งความ · โดนหลอก ร่วมลงทุน', '', 'buy-ea-mt5', None, 'ตรวจข้อเท็จจริง (สายด่วนและขั้นตอนของรัฐ)')
A('trading-safety', 'trading-groups', 'กลุ่มเทรดทอง: เข้ากลุ่ม LINE OpenChat Telegram ยังไงไม่ให้โดนหลอก', 'กลุ่มเทรดทอง', 2, 2,
  'กลุ่มเทรดทอง forex · กลุ่มเทรดทอง telegram · กลุ่มเทรดทองคำ', '', 'copy-trade-gold')
A('trading-safety', 'trading-course-check', 'คอร์สเทรดทอง: เช็กก่อนจ่ายเงินเรียน และสัญญาณคอร์สขายฝัน', 'คอร์สเทรดทอง', 2, 2,
  'คอร์สเรียนเทรดทองฟรี · คอร์ส เทรด ทอง', '', 'gold-trading-techniques', 'home')
A('trading-safety', 'mt5-2fa', 'MT5 2FA: เปิด OTP กันคนอื่นเข้าบัญชีเทรด', 'mt5 2fa', 1, 2,
  'otp mt5 · รหัส otp metatrader 5', '', 'remote-desktop', 'mt5-login', 'ตรวจข้อเท็จจริง (เมนู OTP ของ MT5)')

# ─── 5 · พื้นฐานเทรดทอง ─────────────────────────────────────────────────────
C('gold-basics', 'พื้นฐานเทรดทอง', 'open-mt5-account', 'cmegroup.com (Gold Futures contract) · gold.org (World Gold Council)')
A('gold-basics', 'what-is-gold-trading', 'เทรดทอง คืออะไร: เทรดทองออนไลน์ทำงานยังไง และเริ่มยังไงให้ถูก', 'เทรดทอง คือ', 3, 1,
  'เทรดทองคำ คือ · เทรดทองออนไลน์ · เทรดทอง forex คือ · การเทรดทอง forex · เริ่มเทรดทอง',
  'เทรดทอง ทำยังไง · เทรดทอง คืออะไร pantip · เริ่มเทรดทองยังไง · วิธีเริ่มเทรดทอง', 'gold-trading-bot · world-gold-price')
A('gold-basics', 'gold-vs-forex', 'เทรดทองกับ Forex ต่างกันอย่างไร: เทียบทีละข้อก่อนเลือก', 'เทรดทอง กับ forex ต่างกันอย่างไร', 3, 1,
  'เทรดทอง คือ forex ไหม · ทองคำ forex · เทรดทอง forex', '', 'xauusd-001-lot-thb', 'home')
A('gold-basics', 'gold-trading-vs-saving', 'เทรดทองกับออมทองต่างกันยังไง: เทียบกับทองแท่งและ ETF ทองคำ', 'เทรดทอง กับ ออมทอง ต่างกัน', 3, 1,
  'etf ทองคำ คือ · ออมทอง · ทองแท่ง', 'etf ทองคำ ไทย', 'spdr-gold · world-gold-price', 'home')
A('gold-basics', 'gold-sessions', 'เทรดทองกี่โมงดี: ช่วงลอนดอนและนิวยอร์กที่ทองวิ่งแรง', 'เทรดทอง กี่โมง', 2, 1,
  'อเมริกา เริ่มเทรดทองกี่โมง · เทรดทองคำ 24 ชั่วโมง · เทรด ทอง วัน จันทร์ เปิด กี่ โมง', 'เทรดทอง วันศุกร์ปิดกี่โมง · ราคาทองโลก เปิดกี่โมง',
  'news-filter-ea · gold-short-term', 'home', 'ตรวจข้อเท็จจริง · EA WING มี "xauusd เปิดกี่โมง" (เวลาเปิดปิด): บทนี้คือช่วงที่ทองวิ่ง')
A('gold-basics', 'gold-trading-costs', 'เทรดทองมีค่าธรรมเนียมไหม: Spread Commission Swap ต้นทุนที่จ่ายจริง', 'เทรดทอง มีค่าธรรมเนียมไหม', 2, 1,
  'ค่าธรรมเนียมเทรดทอง · swap ทองคำ · commission ทองคำ', '', 'position-size')
A('gold-basics', 'gold-mt5-first-order', 'เทรดทองใน MT5: เปิดออเดอร์ทองครั้งแรกทีละขั้น', 'เทรดทอง mt5', 2, 1,
  'เทรดทอง metatrader 5 · เทรด ทอง forex mt5 · กราฟทอง mt5', 'เทรด ทอง buy sell คือ', 'stop-loss-take-profit · gold-trading-program', 'mt5-login')
A('gold-basics', 'gold-weekend', 'เทรดทองวันเสาร์อาทิตย์ได้ไหม: ตลาดปิดแล้วออเดอร์และ EA เป็นยังไง', 'เทรดทอง วันเสาร์อาทิตย์', 2, 2,
  'เทรดทอง มีวันหยุดไหม · ราคาทองโลก มีวันหยุดไหม · ราคาทองโลก เสาร์อาทิตย์', '', 'pc-sleep-ea', 'home', 'ตรวจข้อเท็จจริง')
A('gold-basics', 'xauusd-spread', 'XAUUSD Spread: สเปรดทองเท่าไหร่ถึงปกติ และกว้างขึ้นตอนไหน', 'xauusd spread', 2, 2,
  'เทรดทอง สเปรดต่ำ · xauusd average spread · spread ทองคำ', '', 'max-spread · every-tick-real-ticks',
  None, 'ไม่ใส่ตัวเลขสเปรดของโบรกเกอร์ใด')
A('gold-basics', 'gold-trading-types', 'เทรดทองมีกี่แบบ: CFD Futures ออมทอง และ ETF ต่างกันยังไง', 'เทรดทอง มีกี่แบบ', 2, 2,
  'เทรดทอง มีแบบไหนบ้าง · เทรดทอง มีอะไรบ้าง · gold futures', '', 'comex-gold', 'home')
A('gold-basics', 'short-gold', 'Short ทอง คืออะไร: เปิด Sell ทำกำไรตอนทองลงได้ยังไง', 'short ทอง', 2, 2,
  'short ทอง คือ · short sell ทอง · sell ทอง คือ · เทรด ทอง buy sell คือ', 'วิธี short ทอง · เล่น short ทอง', 'why-gold-falls', 'home')
A('gold-basics', 'bid-ask', 'Bid Ask คืออะไร: ราคาซื้อขายทองใน MT5 และทำไมเปิดแล้วติดลบทันที', 'bid ask คือ', 2, 2,
  'bid ask spread คือ · ราคา bid ask คือ · เส้น bid ask คือ', '', 'max-spread', 'mt5-login')
A('gold-basics', 'cfd-explained', 'CFD คืออะไร: ทอง CFD ต่างจากซื้อทองจริงยังไง', 'cfd คืออะไร', 2, 2,
  'ทอง cfd คือ · gold cfd คือ · เทรด cfd คือ · cfd forex คือ', 'cfd คือ pantip', 'blown-account', 'home')

# ─── 6 · ทุนและขนาดไม้ ──────────────────────────────────────────────────────
C('capital-lot', 'ทุนและขนาดไม้', 'tools', 'metatrader5.com/en/terminal/help (Margin · Contract specification)')
A('capital-lot', 'gold-trading-capital', 'เทรดทองต้องมีทุนเท่าไหร่: คิดทุนและขนาดไม้ให้พอร์ตรับแรงเหวี่ยงไหว', 'เทรดทอง ต้องมีทุนเท่าไหร่', 2, 1,
  'เทรดทองควรมีทุนเท่าไหร่ · เทรดทอง ทุนน้อย · เทรด ทอง ทุน เท่า ไหร่ · ขนาดไม้', 'เทรด ทอง กัน หน้า ทุน คือ',
  'what-is-gold-trading · buy-ea-mt5', 'tools', 'ห้ามบอกทุนแนะนำของ EA BULL (ยังไม่มีข้อมูล)')
A('capital-lot', 'xauusd-001-lot-thb', 'XAUUSD 0.01 Lot เท่ากับกี่บาท: มูลค่าต่อจุดและ Margin ที่ต้องมี', 'xauusd 0.01 lot เท่ากับกี่บาท', 3, 1,
  'xauusd 1 lot เท่ากับกี่บาท · xauusd 0.01 lot margin · xauusd 1 lot margin · คำนวณ lot ทองคำ', 'xauusd micro lot · 1 lot ทองคำ',
  'gold-vs-forex', 'tools', 'ตรวจข้อเท็จจริง · EA WING มี "ทองคำ 1 lot" (ขนาดสัญญา): บทนี้คิดเป็นเงินบาท')
A('capital-lot', 'blown-account', 'พอร์ตแตก คืออะไร: ทำไมบัญชีเทรดหมดได้ และสัญญาณก่อนแตก', 'พอร์ตแตก', 3, 1,
  'พอร์ตแตก คือ · พอร์ตทองแตก · ล้างพอร์ต คือ · โดนล้างพอร์ต · พอร์ตแตกทำไงดี', 'mt5 พอร์ตแตก · พอร์ตแตก pantip · ล้างพอร์ต ทอง',
  'cut-loss · monte-carlo-trading', 'tools', 'ลิงก์ /risk-disclosure/ ได้ 1 ครั้ง')
A('capital-lot', 'position-size', 'Position Size คืออะไร: คำนวณขนาดไม้จากทุนและจุด Stop Loss', 'position size คือ', 2, 1,
  'คำนวณ position size · position size คือ อะไร', '', 'stop-loss-take-profit')
A('capital-lot', 'fixed-lot-vs-auto-lot', 'Fixed Lot กับ Lot อัตโนมัติ: EA ควรออกไม้แบบไหน', 'fixed lot คือ', 2, 1,
  'fixed lot size คือ · lot อัตโนมัติ · auto lot', '', 'ea-settings')
A('capital-lot', 'stuck-trade', 'ติดดอยคืออะไร: ทองติดดอยแล้วทำยังไงต่อ', 'ติดดอย คือ', 3, 1,
  'ติดดอยทอง · ติดดอยทำไง · ติดดอยทองคำ', 'ติดดอยทอง pantip · ติด ดอย ทอง คือ', 'averaging-dca · cut-loss', 'tools', '⚠')
A('capital-lot', 'floating-loss', 'Floating Loss คืออะไร: ขาดทุนลอยตัวกับขาดทุนจริงต่างกันยังไง', 'floating loss คือ', 1, 2,
  'floating loss limit · ขาดทุนลอยตัว', '', 'stop-loss-take-profit')
A('capital-lot', 'drawdown-limiter', 'Drawdown Limiter: ตั้งเพดานขาดทุนให้ EA หยุดเอง', 'drawdown limiter', 1, 2,
  'drawdown limiter ea · drawdown limiter mt5 · max drawdown limit', '', 'prop-firm')
A('capital-lot', 'xauusd-minimum-lot', 'XAUUSD Minimum Lot: ไม้เล็กสุดของทอง และทุนเริ่มต้นที่ใช้ได้จริง', 'xauusd minimum lot', 1, 2,
  'xauusd minimum lot size · xauusd micro lot size · xauusd mini lot size', '', 'gold-trading-costs', None, 'ตรวจข้อเท็จจริง')
A('capital-lot', 'gold-trading-loss', 'เทรดทองขาดทุน: สาเหตุที่เจอบ่อย และวิธีกลับมาตั้งหลัก', 'เทรดทอง ขาดทุน', 2, 2,
  'เทรดทอง ขาดทุน pantip', '', 'overfitting-trading · trading-scams')
A('capital-lot', 'capital-per-lot', 'ทุนต่อ 0.01 Lot: คิดยังไงให้ EA รับแรงเหวี่ยงทองไหว', 'ทุนต่อ 0.01 lot', 1, 2,
  'ทุนคิดไม้ · ทุนกี่บาทต่อ lot', '', 'every-tick-real-ticks', None, 'คำถามลูกค้า · ห้ามบอกตัวเลขของ EA BULL')
A('capital-lot', 'protect-capital', 'กันหน้าทุนคืออะไร: ถอนทุนคืนก่อน แล้วปล่อยกำไรทำงาน', 'กันหน้าทุน', 1, 2,
  'เทรด ทอง กัน หน้า ทุน คือ', '', 'trailing-stop')

# ─── 7 · จัดการออเดอร์ ───────────────────────────────────────────────────────
C('order-control', 'จัดการออเดอร์', 'how-to-install', 'metatrader5.com/en/terminal/help/trading (Orders · Trailing Stop)')
A('order-control', 'stop-loss-take-profit', 'Stop Loss กับ Take Profit คืออะไร: ตั้ง SL TP ยังไงให้มีแผน', 'stop loss คือ', 3, 1,
  'take profit คือ · sl tp คือ · stop loss กับ take profit คือ · stop loss ทอง คือ · take profit stop loss คือ',
  'จุด stop loss คือ · stop loss มีกี่แบบ · การตั้ง sl กับ tp ใน mt5 · เทรดทอง sl tp คืออะไร', 'position-size · gold-mt5-first-order', 'tools')
A('order-control', 'trailing-stop', 'Trailing Stop คืออะไร: เลื่อน Stop Loss ตามราคาอัตโนมัติ', 'trailing stop คือ', 3, 1,
  'trailing stop mt5 · trailing stop loss คือ · trailing stop กับ stop loss ต่างกันอย่างไร · trailing stop ตั้งอย่างไร',
  'trailing stop ทำงานยังไง · trailing stop ตั้งค่าอะไรบ้าง', 'trend-following')
A('order-control', 'break-even', 'Break Even คืออะไร: เลื่อน SL มาจุดคุ้มทุนตอนไหนดี', 'break even คือ', 3, 1,
  'break even forex คือ · break even ในการเทรด คือ · break even point คือ', 'break even แปลว่า', 'ea-settings')
A('order-control', 'cut-loss', 'Cut Loss คืออะไร: ตัดขาดทุนตอนไหน ต่างจาก Stop Loss ยังไง', 'cut loss คือ', 3, 1,
  'คัทลอส คือ · cut loss ทอง · cut loss กับ stop loss · ควร cut loss ที่เท่าไหร่ · จุด cut loss คือ', 'cut loss กี่เปอร์เซ็นต์',
  'blown-account · stuck-trade', 'tools')
A('order-control', 'close-all-orders-mt5', 'ปิดออเดอร์ทั้งหมด MT5: ปิดทีเดียวทั้งในคอมและมือถือ', 'ปิดออเดอร์ทั้งหมด mt5', 3, 1,
  'close all mt5 · วิธีปิดออเดอร์ทั้งหมด mt5 มือถือ · ปิดออเดอร์ mt5 พร้อมกัน · close all mt5 android',
  'ปิดออเดอร์ mt5 ทั้งหมด pc · close all mt5 script', 'ea-power-outage', 'mt5-login', 'ตรวจข้อเท็จจริง (เมนู Bulk Operations)')
A('order-control', 'buy-limit-buy-stop', 'Buy Limit กับ Buy Stop ต่างกันยังไง: คำสั่งรอใน MT5 ใช้ตอนไหน', 'buy limit กับ buy stop', 2, 1,
  'buy limit คือ · buy stop คือ · sell limit sell stop · buy stop limit คือ · pending order คือ', 'buy limit buy stop คือ · buy limit sell stop คือ',
  'breakout', 'mt5-login', 'EA WING มี "คำสั่ง mt5" (ประเภทคำสั่ง): บทนี้เน้นเลือก Buy Limit หรือ Buy Stop กับทอง')
A('order-control', 'partial-close-mt5', 'ปิดออเดอร์บางส่วน MT5: แบ่งปิดกำไรทีละส่วนยังไง', 'ปิดออเดอร์บางส่วน mt5', 2, 2,
  'แบ่งปิดออเดอร์ mt5 · partial close', '', 'protect-capital', 'mt5-login')
A('order-control', 'trailing-stop-mt5-mobile', 'ตั้ง Trailing Stop MT5 มือถือ: ทำได้ไหม และทางเลือกที่ใช้ได้จริง', 'ตั้ง trailing stop mt5 มือถือ', 2, 2,
  'trailing stop mt5 มือถือ · trailing stop metatrader 5 mobile · mt5 android trailing stop', '', 'remote-desktop',
  'vps-windows', 'ตรวจข้อเท็จจริง (แอปมือถือไม่มี Trailing Stop)')
A('order-control', 'atr-trailing-stop', 'ATR Trailing Stop: ระยะ Stop ที่ปรับตามความผันผวนของทอง', 'atr trailing stop', 1, 2,
  'atr คือ · trailing stop atr mt5 · atr คือ forex', '', 'bollinger-band')
A('order-control', 'stop-loss-not-triggered', 'Stop Loss ไม่ทำงาน: ทำไมราคาเลยจุด SL แล้วยังไม่ปิด', 'stop loss ไม่ทำงาน', 2, 2,
  'sl ไม่ทำงาน · stop loss ไม่ปิด', '', 'bid-ask · xauusd-spread')
A('order-control', 'modify-order-mt5', 'แก้ไขออเดอร์ MT5: เลื่อน SL TP และแก้คำสั่งรอ', 'แก้ไขออเดอร์ mt5', 1, 2,
  'modify order mt5 · ยกเลิกคำสั่งรอ mt5', '', 'gold-mt5-first-order', 'mt5-login')
A('order-control', 'ea-without-stop-loss', 'EA ไม่มี Stop Loss: เสี่ยงตรงไหน และคุมด้วยอะไรแทน', 'ea ไม่มี stop loss', 1, 2,
  'ea ไม่ตั้ง sl · ea ไม่มี sl', '', 'recovery-ea · drawdown-limiter', 'tools', '⚠ ห้ามใบ้ว่า EA BULL มีหรือไม่มี SL')

# ─── 8 · กลยุทธ์เทรดทอง ─────────────────────────────────────────────────────
C('gold-strategy', 'กลยุทธ์เทรดทอง', 'backtest', 'metatrader5.com/en/terminal/help/indicators (Technical indicators)')
A('gold-strategy', 'gold-trading-techniques', 'เทคนิคเทรดทอง: กลยุทธ์หลักที่เทรดเดอร์และ EA ใช้ เลือกให้เข้ากับตัวเอง', 'เทคนิคเทรดทอง', 3, 1,
  'กลยุทธ์เทรดทอง · เทคนิคการเทรดทองคำ · สูตรเทรดทอง · เทรดทอง มีเทคนิคอะไรบ้าง',
  'รวมเทคนิคเทรดทอง · กลยุทธ์การเทรดทองคำ · เทคนิคเทรดทอง m15', 'gold-trading-system · gold-chart-reading')
A('gold-strategy', 'gold-short-term', 'เทรดทองระยะสั้น: Scalping ทองทำงานยังไง และต้นทุนที่ต้องคิด', 'เทรดทองระยะสั้น', 3, 1,
  'เทคนิคเทรดทองระยะสั้น · เทรดทองระยะสั้น คือ · วิธีเทรดทองระยะสั้น · scalping ทอง · scalping คือ',
  'เทรดทองระยะสั้น pantip · ข้อดีของการเทรดทองระยะสั้น · เทรดทองระยะสั้นดีไหม', 'xauusd-spread', None,
  '⚠ · EA WING มี "ea scalping คือ": บทนี้คือการเทรดทองระยะสั้น')
A('gold-strategy', 'breakout', 'Breakout คืออะไร: เทรดตอนราคาทะลุกรอบ และกับดัก False Breakout', 'breakout คือ', 3, 1,
  'breakout forex คือ · breakout ทอง · กลยุทธ์ breakout · false breakout คือ · breakout กราฟ', 'breakout คือ อะไร · กราฟ breakout คือ',
  'buy-limit-buy-stop · support-resistance')
A('gold-strategy', 'trend-following', 'Trend Following คืออะไร: เทรดตามเทรนด์ทองแบบมีกติกา', 'trend following คือ', 2, 1,
  'เทรดตามเทรนด์ · กลยุทธ์ trend following · ระบบ trend following · trend following strategy', 'เทรด ตาม เทรน คือ',
  'trailing-stop · ema')
A('gold-strategy', 'gold-5-minute', 'เทคนิคเทรดทอง 5 นาที: เทรด M5 และ M1 ต้องระวังอะไร', 'เทคนิคเทรดทอง 5 นาที', 2, 1,
  'ระบบเทรดทอง 5 นาที · เทคนิคการเทรดทองคำ 5 นาที · เทรดทอง m5 · เทรดทอง m1 · ระบบเทรดทอง m1', 'เทคนิคเทรดทอง m1 · เทรดทองคำ m5',
  'timeframe', None, '⚠')
A('gold-strategy', 'day-trade', 'Day Trade คืออะไร: เทรดทองจบในวันต่างจากถือข้ามคืนยังไง', 'day trade คือ', 2, 1,
  'day trade ทอง · กลยุทธ์ day trade · เทรดทอง รายวัน · วิธี day trade', 'เทรดทอง รายวัน ดีไหม · day trade มือใหม่', 'gold-sessions')
A('gold-strategy', 'sideway', 'Sideway คืออะไร: ทองวิ่งในกรอบ เทรดยังไงให้ไม่โดนหลอก', 'sideway คือ', 2, 2,
  'ทอง sideway · ตลาด sideway คือ · กรอบ sideway คือ · ทอง sideway คืออะไร', 'sideway up คือ · sideway down คือ', 'support-resistance')
A('gold-strategy', 'mean-reversion', 'Mean Reversion คืออะไร: กลยุทธ์รอราคากลับเข้าค่าเฉลี่ย', 'mean reversion คือ', 1, 2,
  'กลยุทธ์ mean reversion · mean reversion trading · trend following and mean reversion', '', 'bollinger-band')
A('gold-strategy', 'swing-trade', 'Swing Trade คืออะไร: ถือออเดอร์ทองหลายวันต่างจากเทรดสั้นยังไง', 'swing trade คือ', 2, 2,
  'swing trade ทอง · กลยุทธ์ swing trade · swing trade gold · เทรดทอง ระยะยาว', 'swing trade สอน', 'gold-weekend')
A('gold-strategy', 'averaging-dca', 'ถัวเฉลี่ยในการเทรด: Averaging และ DCA กับทองคำต่างกันยังไง', 'averaging คือ', 2, 2,
  'dca เทรด · เทรด dca คือ · เทรดทอง dca · dollar cost averaging คือ', 'ถัวขาดทุน', 'stuck-trade · blown-account', None,
  '⚠ ห้ามใบ้ว่า EA BULL ใช้หรือไม่ใช้')
A('gold-strategy', 'recovery-ea', 'EA แก้ไม้ คืออะไร: ระบบกู้ออเดอร์ติดลบทำงานยังไง', 'ea แก้ไม้', 1, 2,
  'ea แก้ไม้ mt5 · ระบบเทรด มาติงเกล', '', 'blown-account · ea-without-stop-loss', None,
  '⚠ ห้ามใบ้ว่า EA BULL ใช้หรือไม่ใช้ · EA WING มี "martingale คือ"')
A('gold-strategy', 'multi-timeframe', 'Multi Timeframe คืออะไร: ดูหลายกรอบเวลาก่อนเข้าออเดอร์ทอง', 'multi timeframe คือ', 1, 2,
  'มัลติไทม์เฟรม · multi timeframe analysis คือ · ดูกราฟมัลติไทม์เฟรม', '', 'timeframe')

# ─── 9 · ราคาทองโลก ─────────────────────────────────────────────────────────
C('gold-market', 'ราคาทองโลก', 'home', 'gold.org · lbma.org.uk · cmegroup.com · federalreserve.gov · home.treasury.gov')
A('gold-market', 'world-gold-price', 'ราคาทองโลก คืออะไร: มาจากไหน ใครกำหนด และดูที่ไหน', 'ราคาทองโลก', 3, 1,
  'ราคาทองโลก คือ · ราคาทองโลก ใครกำหนด · ราคาทองโลก ดูที่ไหน · ราคาทองโลก คิดยังไง · ราคาทองโลก หน่วยเป็นอะไร',
  'ราคาทองโลกเรียลไทม์ · กราฟราคาทองโลก · ราคาทองเท่ากันทั่วโลกไหม · ราคาทองโลก ต่อออนซ์', 'what-is-gold-trading · gold-chart-reading',
  'home', 'ไม่ใส่ราคาวันนี้ (ต้องอัปเดตทุกวัน)')
A('gold-market', 'gold-world-vs-thai', 'ราคาทองโลกกับราคาทองไทย: คิดเป็นเงินบาทยังไง ทำไมขยับไม่เท่ากัน', 'ราคาทองโลก กับ ราคาทองไทย', 3, 1,
  'ราคาทองโลก คิดเป็นเงินไทย · ราคาทองโลก เทียบ ทองไทย · ราคาทองโลก กี่บาท · บาททองคำ', 'ทองคำ 1 บาท กี่กรัม · ทอง คำ บาท ต่อ ออนซ์',
  'xauusd-001-lot-thb', 'home', 'ตรวจข้อเท็จจริง (สูตรแปลงหน่วย)')
A('gold-market', 'gold-ounce', 'ทองคำ 1 ออนซ์ เท่ากับกี่บาท: ทรอยออนซ์ กรัม และบาททองคำ', 'ทองคำ 1 ออนซ์', 3, 1,
  'ทองคำ 1 ทรอยออนซ์ · ทองคำ 1 ออนซ์ กี่กรัม · ทองคำ 1 ออนซ์ เท่ากับกี่บาท · ออนซ์ ทอง · oz ทอง คือ',
  'ทองคำ 1 ออนซ์มีกี่บาท · ทองคำ 1 ออนซ์กี่ดอลลาร์', 'xauusd-minimum-lot', 'home', 'ตรวจข้อเท็จจริง')
A('gold-market', 'dxy', 'DXY คืออะไร: ดัชนีดอลลาร์กับราคาทองสัมพันธ์กันยังไง', 'dxy คือ', 3, 1,
  'dollar index คือ · dxy กับทอง · dxy ลง ทองขึ้น · dollar index กับราคาทอง · dxy ดูยังไง', 'dxy mt5 · dxy แข็งค่า · dxy อ่อนค่า · dxy บอกอะไร',
  'trend-following', 'home', 'EA WING มี "ทองคำ ดอลลาร์": บทนี้อธิบายตัว DXY เป็นหลัก')
A('gold-market', 'fed-rates-gold', 'ดอกเบี้ยขึ้น ทองลงจริงไหม: Fed กับราคาทองคำ', 'ดอกเบี้ยขึ้น ทองลง', 2, 1,
  'ทำไมดอกเบี้ยขึ้นทองลง · fed ลดดอกเบี้ย ทอง · เฟด ทองคำ · ดอกเบี้ยขึ้น ทองจะขึ้นหรือลง',
  'fed คง ดอกเบี้ย ทอง ขึ้น หรือ ลง · เฟด ลด ดอกเบี้ย มี ผล ต่อ ทอง อย่างไร', 'news-filter-ea', 'home', 'EA WING มี "fomc คือ"')
A('gold-market', 'bond-yield-gold', 'Bond Yield คืออะไร: ผลตอบแทนพันธบัตรสหรัฐฯ กดดันราคาทองยังไง', 'bond yield คือ', 2, 1,
  'bond yield สหรัฐฯ · bond yield กับราคา · bond yield บอกอะไร · bond yield ดูที่ไหน', '', 'swing-trade')
A('gold-market', 'central-bank-gold', 'ธนาคารกลางซื้อทอง: ทำไมแบงก์ชาติทั่วโลกสะสมทอง และส่งผลกับราคายังไง', 'ธนาคารกลางซื้อทอง', 2, 2,
  'ธนาคารกลางจีนซื้อทอง · ธนาคารกลางทั่วโลกซื้อทอง', '', 'gold-trading-vs-saving', None, 'ตรวจข้อเท็จจริง (ข้อมูล gold.org)')
A('gold-market', 'spdr-gold', 'SPDR คืออะไร: กองทุนทองคำที่คนดูยอดถือทองทุกวัน', 'spdr คือ', 2, 2,
  'spdr ทองคำ · spdr gold คือ · spdr ถือทอง · etf ทองคำ', 'spdr ซื้อทอง · spdr ขายทอง · รายงาน spdr', 'gold-trading-vs-saving')
A('gold-market', 'comex-gold', 'COMEX คืออะไร: ตลาดทองล่วงหน้าที่ขยับราคาทองโลก', 'comex คือ', 2, 2,
  'ทอง comex · ราคาทอง comex · comex gold คือ · ตลาด comex คือ · comex เปิดกี่โมง', '', 'gold-trading-types', None, 'ตรวจข้อเท็จจริง')
A('gold-market', 'lbma', 'LBMA คืออะไร: มาตรฐานทองคำและราคาอ้างอิงจากลอนดอน', 'lbma คือ', 2, 2,
  'ราคาทอง lbma · มาตรฐาน lbma · lbma gold price', '', 'gold-sessions', None, 'ตรวจข้อเท็จจริง')
A('gold-market', 'safe-haven-gold', 'ทองคำ Safe Haven: ทำไมทองขึ้นตอนสงครามและวิกฤต', 'ทองคำ safe haven', 1, 2,
  'ทองคำ สงคราม · ทองขึ้นเพราะ · ทองขึ้นทำไม', 'ทอง ขึ้น ทอง ลง', 'news-filter-ea')
A('gold-market', 'why-gold-falls', 'ทองลงเพราะอะไร: สาเหตุที่ราคาทองร่วงแรง และอ่านสัญญาณยังไง', 'ทองลงเพราะอะไร', 2, 2,
  'ทอง ลง ทำไม · ราคาทองโลกร่วง · ทองคำ ลง', '', 'short-gold', None, 'EA WING มี "ทองคำขึ้นลงขึ้นอยู่กับอะไร": บทนี้เน้นขาลง')

# ─── 10 · อ่านกราฟทองคำ ─────────────────────────────────────────────────────
C('gold-chart', 'อ่านกราฟทองคำ', 'home', 'metatrader5.com/en/terminal/help/indicators (Technical indicators)')
A('gold-chart', 'gold-chart-reading', 'วิเคราะห์กราฟทอง: อ่านกราฟทองคำให้เป็น ตั้งแต่แท่งเทียนถึง Indicator', 'วิเคราะห์กราฟทอง', 3, 1,
  'กราฟทองคำ · วิธีวิเคราะห์กราฟทอง · สอนวิเคราะห์กราฟทอง · กราฟทอง mt5 · การวิเคราะห์กราฟทองคำ',
  'กราฟทองคำเรียลไทม์ · ai วิเคราะห์กราฟทอง · กราฟทอง m5', 'gold-trading-techniques · world-gold-price', 'home',
  'ไม่วิเคราะห์ทองรายวัน')
A('gold-chart', 'candlestick-chart', 'กราฟแท่งเทียน คืออะไร: อ่านแท่งเทียนทองคำให้เป็น', 'กราฟแท่งเทียน', 3, 1,
  'กราฟแท่งเทียน คือ · แท่งเทียน คือ · อ่านกราฟแท่งเทียน · กราฟแท่งเทียนทองคำ · แท่งเทียนกลับตัว',
  'แท่งเทียน doji · แท่งเทียน hammer · กราฟแท่งเทียนอ่านยังไง', 'visual-mode-mt5')
A('gold-chart', 'support-resistance', 'แนวรับแนวต้าน คืออะไร: หาแนวรับแนวต้านทองคำยังไง', 'แนวรับแนวต้าน', 3, 1,
  'แนวรับแนวต้าน คือ · แนวรับแนวต้านทอง · แนวรับ ทอง · แนวรับแนวต้าน mt5 · สอนแนวรับแนวต้าน',
  'แนวรับแนวต้านคืออะไร · วิเคราะห์แนวรับทอง · กรอบแนวรับแนวต้าน', 'breakout')
A('gold-chart', 'what-is-indicator', 'Indicator คืออะไร: ตัวชี้วัดบนกราฟ MT5 มีกี่แบบ ใช้ยังไง', 'indicator คือ', 3, 1,
  'indicator คืออะไร · indicator ทองคำ · leading indicator คือ · lagging indicator คือ', 'indicator mt5',
  'ea-vs-indicator')
A('gold-chart', 'ema', 'EMA คืออะไร: เส้นค่าเฉลี่ยที่เทรดทองใช้กัน ตั้งค่ายังไง', 'ema คือ', 3, 1,
  'เส้น ema คือ · ema 200 คือ · ema ใน mt5 · เส้น ema เทรดทอง · ema cross คือ · ma คือ',
  'เส้น ema ที่นิยมใช้ · เส้น ema 50 คือ · เส้น ema ตั้งค่า', 'trend-following')
A('gold-chart', 'rsi', 'RSI คืออะไร: ดูแรงซื้อขายทองคำ Overbought Oversold', 'rsi คือ', 3, 1,
  'rsi ทองคำ · rsi ทอง คือ · rsi 14 คือ · สัญญาณ rsi · stochastic rsi คือ', '', 'mean-reversion')
A('gold-chart', 'macd', 'MACD คืออะไร: อ่านเส้น MACD และ Histogram บนกราฟทอง', 'macd คือ', 2, 2,
  'macd histogram คือ · macd divergence คือ · กราฟทอง macd · สัญญาณ macd', '', 'trend-following')
A('gold-chart', 'fibonacci', 'Fibonacci คืออะไร: กาง Fibo หาแนวรับแนวต้านทองคำ', 'fibonacci คือ', 2, 2,
  'fibonacci ทอง · กาง fibonacci · ระดับ fibonacci · fibonacci สอน', '', 'buy-limit-buy-stop')
A('gold-chart', 'bollinger-band', 'Bollinger Band คืออะไร: ตั้งค่าและอ่านกรอบราคาทอง', 'bollinger band คือ', 2, 2,
  'วิธีใช้ bollinger band · bollinger band ตั้งค่า · bollinger band ค่ามาตรฐาน', '', 'mean-reversion')
A('gold-chart', 'timeframe', 'Timeframe คืออะไร: M1 M5 H1 H4 ต่างกันยังไง ทองควรดูกรอบไหน', 'timeframe คือ', 2, 2,
  'ไทม์เฟรม คือ · timeframe h4 คือ · ไทม์เฟรม h1 คือ', '', 'gold-5-minute')
A('gold-chart', 'price-action', 'Price Action คืออะไร: อ่านพฤติกรรมราคาทองโดยไม่พึ่ง Indicator', 'price action คือ', 3, 2,
  'price action กลับตัว · price action รูปแบบ · price action forex คือ · price action มีกี่แบบ', '', 'breakout')
A('gold-chart', 'order-block', 'Order Block คืออะไร: ดูโซนคำสั่งใหญ่บนกราฟทองยังไง', 'order block คือ', 2, 2,
  'order block ดูยังไง · smc คือ forex · supply demand zone คือ · order block กับ fvg · ระบบเทรด smc',
  'order block มีกี่แบบ · เทรดทอง เทคนิค ดูกราฟ ob', 'gold-trading-techniques')


# ─── checks ──────────────────────────────────────────────────────────────────
def meta_keywords(folder):
    out = {}
    for f in sorted(folder.glob('*.html')):
        m = re.search(r'"keyword":"([^"]*)"', f.read_text(encoding='utf-8').split('\n', 1)[0])
        if m:
            out[m.group(1).strip().lower()] = f.stem
    return out


def check():
    probs, notes = [], []
    slugs = {a['slug']: a for a in ARTICLES}
    if len(ARTICLES) != PER_CLUSTER * len(CLUSTERS):
        probs.append(f'{len(ARTICLES)} articles, expected {PER_CLUSTER * len(CLUSTERS)}')
    if len(slugs) != len(ARTICLES):
        probs.append('duplicate slug')
    for key in CLUSTERS:
        n = sum(a['cluster'] == key for a in ARTICLES)
        if n != PER_CLUSTER:
            probs.append(f'{key}: {n} articles')
    kws = {}
    wing = meta_keywords(WING_ARTICLES) if WING_ARTICLES.exists() else {}
    ours = meta_keywords(OUR_PAGES)
    if not wing:
        notes.append('EA WING articles not found: overlap not checked')
    for a in ARTICLES:
        k = a['kw'].lower()
        if k in kws:
            probs.append(f'focus keyword "{k}" used by {kws[k]} and {a["slug"]}')
        kws[k] = a['slug']
        if k in wing:
            probs.append(f'{a["slug"]}: focus keyword "{k}" is EA WING\'s ({wing[k]})')
        if k in ours:
            probs.append(f'{a["slug"]}: focus keyword "{k}" belongs to our page /{ours[k]}/')
        if FORBIDDEN.search(a['title']):
            probs.append(f'{a["slug"]}: forbidden word or dash in title')
        if len(a['title']) > 72:
            notes.append(f'{a["slug"]}: title {len(a["title"])} chars')
        if a['pillar'] and a['round'] != 1:
            probs.append(f'{a["slug"]}: pillar not in round 1')
        if a['money'] not in PAGES:
            probs.append(f'{a["slug"]}: unknown money page {a["money"]}')
        if not a['bridges']:
            probs.append(f'{a["slug"]}: no bridge')
        for b in a['bridges']:
            if b not in slugs:
                probs.append(f'{a["slug"]}: bridge {b} does not exist')
            elif slugs[b]['cluster'] == a['cluster']:
                probs.append(f'{a["slug"]}: bridge {b} is in the same cluster')
        for k2 in a['kw2'] + a['longtail']:
            if k2.lower() in wing:
                notes.append(f'{a["slug"]}: secondary "{k2}" is EA WING\'s focus keyword ({wing[k2.lower()]})')
    for a in ARTICLES:  # every focus keyword appears in no other article's secondary list
        for b in ARTICLES:
            if a is not b and a['kw'].lower() in [x.lower() for x in b['kw2']]:
                probs.append(f'{b["slug"]}: secondary "{a["kw"]}" is the focus keyword of {a["slug"]}')
    return probs, notes


# ─── schedule + output ───────────────────────────────────────────────────────
def schedule():
    day = START
    order = []
    for rnd in (1, 2):
        for key in CLUSTERS:
            for a in ARTICLES:
                if a['cluster'] == key and a['round'] == rnd:
                    order.append(a)
    for i, a in enumerate(order, 1):
        a['n'] = i
        a['date'] = f'{day.isoformat()} {TIME}'
        a['date_th'] = f'{THAI_DAY[day.weekday()]} {day.day} {THAI_MON[day.month - 1]} {str(day.year + 543)[2:]}'
        day += dt.timedelta(days=1)
    return order


def stars(n):
    return '★' * n


def link(slug):
    return f'`{slug}`'


def render(order):
    first, last = order[0], order[-1]
    r1 = [a for a in order if a['round'] == 1]
    count = {s: sum(a['stars'] == s for a in ARTICLES) for s in (3, 2, 1)}
    summary = '\n'.join([
        '| | จำนวน |', '|---|---|',
        f'| บทความทั้งหมด | **{len(ARTICLES)} บท** ใน {len(CLUSTERS)} กลุ่ม (กลุ่มละ 1 เสาหลัก + {PER_CLUSTER - 1} บทเสริม) |',
        f'| ความต้องการ | ★★★ {count[3]} บท · ★★ {count[2]} บท · ★ {count[1]} บท |',
        f'| จังหวะลง | วันละ 1 บท เวลา {TIME} น. ตั้งเวลาล่วงหน้าใน WordPress |',
        f'| รอบ 1 | {r1[0]["date_th"]} ถึง {r1[-1]["date_th"]} · {len(r1)} บท (เสาหลักครบ 10 กลุ่ม + บทแกน) |',
        f'| รอบ 2 | {order[len(r1)]["date_th"]} ถึง {last["date_th"]} · {len(order) - len(r1)} บท |',
        f'| คีย์ที่ใช้ในแผน | คีย์หลัก {len(ARTICLES)} คำ · คำรอง {sum(len(a["kw2"]) for a in ARTICLES)} คำ · คำยาว {sum(len(a["longtail"]) for a in ARTICLES)} คำ |',
    ])
    rows = ['| กลุ่ม | หมวด (slug) | เสาหลัก | คีย์เสาหลัก | หน้าเงินหลัก | แหล่งอ้างอิงภายนอก |', '|---|---|---|---|---|---|']
    for i, (key, c) in enumerate(CLUSTERS.items(), 1):
        p = next(a for a in ARTICLES if a['cluster'] == key and a['pillar'])
        rows.append(f'| G{i} {c["name"]} | `{key}` | {link(p["slug"])} | {p["kw"]} | {PAGES[c["money"]]} | {c["sources"]} |')
    clusters = '\n'.join(rows)

    cal = []
    for rnd in (1, 2):
        for gi, (key, c) in enumerate(CLUSTERS.items(), 1):
            arts = [a for a in order if a['cluster'] == key and a['round'] == rnd]
            if not arts:
                continue
            cal.append(f'#### รอบ {rnd} · G{gi} {c["name"]}\n')
            cal.append('| # | วันลง | slug | ชื่อบทความ | คีย์หลัก | ดาว | หมายเหตุ |')
            cal.append('|---|---|---|---|---|---|---|')
            for a in arts:
                note = ('**เสาหลัก**' + (' · ' if a['note'] else '') if a['pillar'] else '') + a['note']
                cal.append(f'| {a["n"]} | {a["date_th"]} | {link(a["slug"])} | {a["title"]} | {a["kw"]} | {stars(a["stars"])} | {note} |')
            cal.append('')
    cal.append('### คีย์รอง คำยาว และลิงก์ของทุกบท\n')
    cal.append('ลำดับในกลุ่ม = ลำดับลิงก์พี่น้อง (บทที่อยู่ติดกันลิงก์หากัน + ขึ้นเสาหลักทุกบท)\n')
    for gi, (key, c) in enumerate(CLUSTERS.items(), 1):
        cal.append(f'#### G{gi} {c["name"]}\n')
        for a in [x for x in ARTICLES if x['cluster'] == key]:
            parts = [f'**{link(a["slug"])}** {a["kw"]}']
            if a['kw2']:
                parts.append('คำรอง: ' + ' · '.join(a['kw2']))
            if a['longtail']:
                parts.append('คำยาว: ' + ' · '.join(a['longtail']))
            parts.append('สะพาน: ' + ' · '.join(link(b) for b in a['bridges']))
            parts.append('หน้าเงิน: ' + PAGES[a['money']])
            cal.append('- ' + ' ┃ '.join(parts))
        cal.append('')
    return {'summary': summary, 'clusters': clusters, 'calendar': '\n'.join(cal).rstrip()}


def write_doc(parts):
    text = DOC.read_text(encoding='utf-8')
    for name, body in parts.items():
        text, n = re.subn(rf'(<!-- gen:{name} -->\n).*?(<!-- /gen:{name} -->)', lambda m: m.group(1) + body + '\n' + m.group(2), text, flags=re.S)
        if n != 1:
            sys.exit(f'marker gen:{name} missing in {DOC}')
    DOC.write_text(text, encoding='utf-8')


def main():
    probs, notes = check()
    for n in notes:
        print('·', n)
    for p in probs:
        print('✗', p)
    if probs:
        sys.exit(1)
    order = schedule()
    print(f'ok · {len(ARTICLES)} articles · {order[0]["date"]} to {order[-1]["date"]}')
    if '--check' in sys.argv:
        return
    write_doc(render(order))
    JSON_OUT.parent.mkdir(parents=True, exist_ok=True)
    JSON_OUT.write_text(json.dumps({'clusters': CLUSTERS, 'articles': order}, ensure_ascii=False, indent=1), encoding='utf-8')
    print('wrote', DOC.relative_to(ROOT), 'and', JSON_OUT.relative_to(ROOT))


if __name__ == '__main__':
    main()
