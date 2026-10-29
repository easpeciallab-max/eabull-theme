"""Full screenshot set for the owner, one folder per device.

  dev/shots/1 คอม 1366px/      1 หน้าเว็บ/  2 เมนู ดรอปดาวน์ คุกกี้ และอื่น ๆ/
  dev/shots/2 แท็บเล็ต 820px/  (same)
  dev/shots/3 มือถือ 375px/    (same · phone pages are column sheets)

Needs the preview server (launch config "eabull-mockup", port 8790).
Usage: py -3 -I dev/tools/shoot-set.py [desktop|tablet|mobile ...]
"""
import sys
from pathlib import Path

from PIL import Image

sys.path.insert(0, str(Path(__file__).resolve().parent))
import sheet  # noqa: E402
import shoot  # noqa: E402

sys.stdout.reconfigure(encoding='utf-8')

SHOTS = shoot.OUT
TMP = SHOTS / '_tmp'

DEVICES = {
    'desktop': ('1 คอม 1366px', 1366, 820),
    'tablet': ('2 แท็บเล็ต 820px', 820, 1180),
    'mobile': ('3 มือถือ 375px', 375, 812),
}
PAGES_DIR = '1 หน้าเว็บ'
STATES_DIR = '2 เมนู ดรอปดาวน์ คุกกี้ และอื่น ๆ'

PAGES = [
    ('home', 'หน้าแรก'), ('product-1', 'สินค้าตัวอย่าง 1'), ('product-2', 'สินค้าตัวอย่าง 2'),
    ('product-3', 'สินค้าตัวอย่าง 3'), ('pricing', 'แพ็กเกจและราคา'), ('backtest', 'Backtest'),
    ('forward-test', 'Forward Test'), ('how-to-install', 'วิธีติดตั้ง EA'), ('open-mt5-account', 'เปิดบัญชี MT5'),
    ('deposit', 'ฝากเงิน'), ('mt5-login', 'ติดตั้งและล็อกอิน MT5'), ('vps-windows', 'VPS บน Windows'),
    ('vps-android', 'VPS บน Android'), ('vps-ios', 'VPS บน iPhone และ iPad'), ('tools', 'เครื่องคำนวณ Lot และ Drawdown'),
    ('risk-disclosure', 'ประกาศความเสี่ยง'), ('about', 'เกี่ยวกับเรา'), ('privacy-policy', 'นโยบายความเป็นส่วนตัว'),
    ('terms-of-use', 'ข้อกำหนดการใช้บริการ'), ('data-deletion', 'คำขอลบข้อมูลส่วนบุคคล'), ('go', 'หน้า go รวมลิงก์เริ่มต้น'),
    ('articles', 'รายการบทความ (โครง)'), ('article', 'หน้าบทความ (โครง)'), ('search', 'ผลการค้นหา'),
    ('404', 'ไม่พบหน้า 404'), ('index', 'หน้ารวมลิงก์ต้นแบบ'),
]

COMMON_STATES = [
    ('การ์ดคุกกี้ PDPA ตอนนี้ (ใช้คุกกี้จำเป็นอย่างเดียว)', 'home.html?cookie=1'),
    ('การ์ดคุกกี้ PDPA เมื่อใส่ GA4 แล้ว', 'home.html?cookie=ga'),
    ('การ์ดคุกกี้ PDPA หน้าตั้งค่า', 'home.html?cookie=settings'),
    ('แผงติดต่อจากปุ่ม LINE ลอย', 'pricing.html?shot&quick=open'),
    ('ข้อความแจ้งเมื่อกดลิงก์ที่ยังไม่มี', 'pricing.html?shot&toast=1'),
    ('แท็บโหมด เลือกโหมด 2', 'home.html?shot&tab=2&focus=modes'),
    ('แท็บโหมด เลือกโหมด 3', 'home.html?shot&tab=3&focus=modes'),
    ('เช็กลิสต์ความพร้อม ติ๊กครบ 6 ข้อ', 'home.html?shot&ready=all&focus=ready'),
    ('คำถามที่พบบ่อย เปิดคำตอบ', 'home.html?shot&focus=faq'),
]
STATES = {
    'desktop': [
        ('เมนูย่อย สินค้า', 'home.html?shot&sub=products'),
        ('เมนูย่อย การทดสอบ', 'backtest.html?shot&sub=tests'),
        ('เมนูย่อย คู่มือการใช้งาน', 'how-to-install.html?shot&sub=guides'),
    ] + COMMON_STATES,
    'touch': [
        ('เมนูเปิด จากหน้าแรก', 'home.html?shot&menu=open'),
        ('เมนูเปิด กลุ่มสินค้ากางเอง (อยู่หน้าสินค้า)', 'product-1.html?shot&menu=open'),
        ('เมนูเปิด กลุ่มคู่มือกางเอง (อยู่หน้าคู่มือ)', 'vps-ios.html?shot&menu=open'),
    ] + COMMON_STATES + [
        ('แถบล่างมือถือ อยู่หน้าแรก', 'home.html?shot'),
        ('แถบล่างมือถือ อยู่หน้าแพ็กเกจ', 'pricing.html?shot'),
        ('แถบล่างมือถือ อยู่หน้าคู่มือ', 'vps-windows.html?shot'),
    ],
}


def capture(url, width, height, dest):
    """Shoot url at width x height into dest (any path, Thai names fine)."""
    TMP.mkdir(parents=True, exist_ok=True)
    tmp = TMP / 'shot.png'
    if width < 600:
        target = shoot.framed(url, width, viewport=True) + f'&vh={height}'
        shoot.run([f'--window-size=600,{height}', f'--screenshot={tmp}', target])
        Image.open(tmp).crop((0, 0, width, height)).save(dest, optimize=True)
    else:
        shoot.run([f'--window-size={width},{height}', f'--screenshot={tmp}', url])
        Image.open(tmp).save(dest, optimize=True)


def main():
    wanted = sys.argv[1:] or list(DEVICES)
    for key in wanted:
        folder, width, vh = DEVICES[key]
        pages_dir = SHOTS / folder / PAGES_DIR
        states_dir = SHOTS / folder / STATES_DIR
        pages_dir.mkdir(parents=True, exist_ok=True)
        states_dir.mkdir(parents=True, exist_ok=True)
        query = 'shot'
        for i, (slug, name) in enumerate(PAGES, 1):
            url = f'{shoot.BASE}{slug}.html?{query}'
            h = shoot.height(url, width, 600 if width < 600 else 0)
            label = f'{i:02d} {name}'
            if width < 600:
                long_dir = pages_dir / 'ภาพยาวต่อเนื่อง'
                long_dir.mkdir(exist_ok=True)
                raw = long_dir / f'{label}.png'
                capture(url, width, h, raw)
                sheet.SEND = pages_dir
                sheet.phone_sheet(Image.open(raw).convert('RGB'), label)
            else:
                capture(url, width, h, pages_dir / f'{label}.png')
            print(f'{folder} · {label} ({width}x{h})')
        states = STATES['desktop' if key == 'desktop' else 'touch']
        for i, (name, path) in enumerate(states, 1):
            if key == 'desktop' and 'แถบล่าง' in name:
                continue
            base, _, frag = path.partition('#')
            url = f'{shoot.BASE}{base}' + (f'#{frag}' if frag else '')
            capture(url, width, vh, states_dir / f'{i:02d} {name}.png')
            print(f'{folder} · state {i:02d} {name}')


if __name__ == '__main__':
    main()
