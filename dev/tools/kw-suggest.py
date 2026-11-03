"""Thai keyword ideas from Google Autocomplete (what people in Thailand actually type).

  py -3 -I dev/tools/kw-suggest.py            → dev/content/keywords-raw.json
  py -3 -I dev/tools/kw-suggest.py 2          → dev/content/keywords-raw-2.json (round 2 seeds: broader terms)
Seeds below · each seed is asked as is and with a few Thai/English follow-up letters.
Autocomplete shows that people search a phrase, not how many: stars in the plan come from evidence + closeness to buying.
"""
import json
import sys
import time
import urllib.parse
import urllib.request
from pathlib import Path

sys.stdout.reconfigure(encoding='utf-8')
OUT = Path(__file__).resolve().parents[1] / 'content' / 'keywords-raw.json'
SEEDS = [
    # gold automation
    'บอทเทรดทอง', 'เทรดทองอัตโนมัติ', 'โปรแกรมเทรดทอง', 'ระบบเทรดทอง', 'โรบอทเทรดทอง', 'robot เทรดทอง', 'บอทเทรดทองคำ',
    'ea ทองคำ', 'ea xauusd', 'บอทเทรด xauusd', 'เทรดทองคำ ออนไลน์', 'เทรดทองคำ', 'เทรดทอง', 'เทรดทอง forex', 'ทองคำ forex',
    'บอทเทรด', 'บอทเทรดอัตโนมัติ', 'ระบบเทรดอัตโนมัติ', 'เทรดอัตโนมัติ', 'expert advisor', 'robot forex', 'algo trading',
    # running without a PC / cloud
    'รัน ea', 'ea 24 ชั่วโมง', 'ปิดคอม ea', 'ea บนคลาวด์', 'mt5 cloud', 'เทรดอัตโนมัติ 24 ชั่วโมง', 'ไม่มี vps', 'mt5 บนเว็บ',
    'mt5 web', 'เทรดผ่านเว็บ', 'ดูพอร์ตเทรด', 'แดชบอร์ดเทรด',
    # strategies
    'trend following', 'breakout', 'price action ทองคำ', 'indicator ทองคำ', 'rsi ทองคำ', 'ema ทองคำ', 'macd ทองคำ',
    'แนวรับแนวต้าน ทองคำ', 'trailing stop', 'break even', 'stop loss ทองคำ', 'take profit', 'dca เทรด', 'averaging',
    'กลยุทธ์เทรดทอง', 'เทคนิคเทรดทอง', 'สูตรเทรดทอง', 'เทรดทองสั้น', 'เทรดทองระยะสั้น', 'swing trade ทองคำ', 'scalping ทองคำ',
    # settings and testing
    'optimize ea', 'strategy tester', 'ปรับค่า ea', 'ตั้งค่า ea', 'overfitting', 'walk forward', 'backtest ทองคำ',
    'every tick based on real ticks', 'tick data', 'lot อัตโนมัติ', 'fixed lot', 'news filter ea', 'max spread',
    # gold knowledge not in the other site
    'ราคาทองโลก', 'ทองคำ ดอกเบี้ย', 'ทองคำ เงินเฟ้อ', 'ทองคำ สงคราม', 'dxy', 'dollar index', 'bond yield ทองคำ',
    'ธนาคารกลาง ซื้อทอง', 'spdr ทองคำ', 'etf ทองคำ', 'ทองคำ ขึ้น', 'ทองคำ ลง', 'session ทองคำ', 'ทองคำ ช่วงเวลา',
    'spread ทองคำ', 'swap ทองคำ', 'ทองคำ 1 pip', 'ทองคำ leverage', 'margin ทองคำ', 'xauusd lot', 'xauusd spread',
    # portfolio / control
    'พอร์ต ea', 'หลาย ea', 'ปิดออเดอร์ทั้งหมด mt5', 'close all mt5', 'drawdown limit', 'หยุด ea อัตโนมัติ', 'kill switch',
    # trust and safety
    'ea ปลอม', 'บอทเทรด จริงไหม', 'บอทเทรด ได้เงินจริงไหม', 'บอทเทรด ดีไหม', 'บอทเทรดทอง ดีไหม', 'ระวังมิจฉาชีพ เทรด',
    'แชร์ลูกโซ่ forex', 'กองทุน forex', 'ให้คนอื่นเทรดแทน', 'รหัส mt5 ห้ามให้ใคร', 'mt5 2fa',
    # starting
    'เริ่มเทรดทอง', 'มือใหม่ ea', 'ea มือใหม่', 'เช่า ea', 'ซื้อ ea', 'ราคา ea', 'ea รายเดือน', 'ea ตลอดชีพ',
]
# round 2 · seeds without "ทองคำ" for topics where round 1 found nothing, and gaps found while planning
SEEDS_2 = [
    'แท่งเทียน', 'กราฟแท่งเทียน', 'แนวรับแนวต้าน', 'price action', 'smc คือ', 'order block', 'supply demand',
    'ema คือ', 'rsi คือ', 'macd คือ', 'fibonacci', 'bollinger band', 'atr คือ', 'indicator คือ', 'timeframe คือ',
    'ไทม์เฟรม', 'วิเคราะห์กราฟทอง', 'กราฟทองคำ', 'กราฟทอง', 'เทรนด์ คือ', 'sideway คือ', 'mean reversion', 'scalping คือ',
    'swing trade', 'day trade', 'stop loss คือ', 'stop loss', 'sl tp', 'pending order', 'buy limit', 'buy stop',
    'backtest คือ', 'backtest', 'forward test', 'optimization', 'ปรับแต่ง ea', 'ค่า input ea', 'monte carlo',
    'ทองคํา ดอกเบี้ย', 'ทองขึ้น', 'ทองลง', 'ทองคํา safe haven', 'bond yield', 'เฟด ทองคํา', 'ทองคํา สงคราม',
    'ออนซ์', 'ทองคํา 1 ออนซ์', 'บาททองคํา', 'comex', 'lbma', 'spdr',
    'พอร์ตแตก', 'ล้างพอร์ต', 'ขนาดไม้', 'ออกไม้', 'position size', 'ทุนน้อย เทรดทอง', 'เทรดทอง ทุน',
    'ถัวขาดทุน', 'ถัวเฉลี่ย เทรด', 'แก้ไม้', 'ไม้ติด', 'cut loss', 'คัทลอส',
    'vps คือ', 'vps mt5', 'ปิดคอม', 'ไฟดับ ea', 'เน็ตหลุด mt5', 'windows app', 'remote desktop',
    'มิจฉาชีพ เทรด', 'หลอกลงทุน', 'เทรดทอง หลอก', 'เพจปลอม', 'ภาพกำไร', 'myfxbook', 'investor password',
    'วิธีใช้ ea', 'ใช้ ea', 'ติดตั้ง ea', 'license ea', 'ea ผูกบัญชี', 'บริการติดตั้ง ea', 'ea ทองคํา mt5',
    'บอทเทรดคริปโต', 'บอทเทรดหุ้น', 'copy trade', 'social trading', 'signal mt5', 'mql5 market',
]
SUFFIX = ['', ' ก', ' ค', ' ท', ' ม', ' ร', ' ว', ' ส', ' อ', ' a', ' m']


def ask(q):
    url = 'https://suggestqueries.google.com/complete/search?client=firefox&hl=th&gl=th&q=' + urllib.parse.quote(q)
    req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
    with urllib.request.urlopen(req, timeout=10) as r:
        data = json.loads(r.read().decode('utf-8', 'replace'))
    return data[1] if len(data) > 1 else []


def main():
    two = len(sys.argv) > 1 and sys.argv[1] == '2'
    out = OUT.with_name('keywords-raw-2.json') if two else OUT
    found = {}
    for seed in (SEEDS_2 if two else SEEDS):
        for s in SUFFIX:
            try:
                for k in ask(seed + s):
                    found.setdefault(k.strip().lower(), set()).add(seed)
            except Exception as e:
                print('!', seed + s, e)
            time.sleep(0.25)
        print(seed, len(found))
    out.parent.mkdir(parents=True, exist_ok=True)
    out.write_text(json.dumps({k: sorted(v) for k, v in sorted(found.items())}, ensure_ascii=False, indent=1), encoding='utf-8')
    print('phrases:', len(found))


if __name__ == '__main__':
    main()
