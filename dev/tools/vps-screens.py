"""Draw the simulated Windows App screens used by the VPS guides (Android / iPhone).

EA WING had these as navy and gold pictures naming "EA WING VPS"; EA BULL redraws
them flat in Bull Black (docs/design.md) with headless Chrome.
Output: eabull/assets/img/guides/eabull-gvps{a|i}-<name>-v1.webp (1320x1080)
Usage: py -3 -I dev/tools/vps-screens.py
"""
import html
import subprocess
import sys
from pathlib import Path

from PIL import Image

sys.stdout.reconfigure(encoding='utf-8')

ROOT = Path(__file__).resolve().parents[2]
FONTS = (ROOT / 'eabull' / 'assets' / 'fonts').as_uri()
OUT = ROOT / 'eabull' / 'assets' / 'img' / 'guides'
TMP = ROOT / 'dev' / 'shots' / '_tmp'
CHROME = r'C:\Program Files\Google\Chrome\Application\chrome.exe'
W, H = 1320, 1080

CSS = f"""
@font-face {{ font-family: Anuphan; src: url('{FONTS}/anuphan-thai.woff2') format('woff2'); font-weight: 400 700; unicode-range: U+0E01-0E5B; }}
@font-face {{ font-family: Inter; src: url('{FONTS}/inter-latin.woff2') format('woff2'); font-weight: 400 700; }}
* {{ box-sizing: border-box; margin: 0; }}
html, body {{ width: {W}px; height: {H}px; background: #0B0B0C; color: #F4F4F5; font-family: Inter, Anuphan, sans-serif; overflow: hidden; }}
.frame {{ position: absolute; inset: 60px 150px; background: #141416; border: 2px solid #2E2E33; padding: 56px 64px; display: flex; flex-direction: column; }}
.top {{ font-size: 26px; color: #9B9BA3; font-weight: 500; }}
h1 {{ font-size: 54px; font-weight: 700; margin-top: 6px; }}
.sub {{ font-size: 28px; color: #C9C9CE; margin-top: 8px; }}
.rows {{ display: grid; gap: 22px; margin-top: 44px; }}
.row {{ background: #1A1A1D; border: 2px solid #2E2E33; padding: 24px 30px; }}
.row b {{ display: block; font-size: 32px; }}
.row span {{ display: block; font-size: 25px; color: #9B9BA3; margin-top: 4px; }}
.on {{ border: 5px solid #FFCC00; padding: 21px 27px; }}
.empty {{ margin-top: 60px; border: 2px dashed #45454C; padding: 70px 30px; text-align: center; }}
.empty b {{ font-size: 34px; }} .empty span {{ display: block; margin-top: 10px; font-size: 25px; color: #9B9BA3; }}
.bar {{ margin-top: auto; display: flex; align-items: center; gap: 70px; font-size: 26px; color: #9B9BA3; }}
.add {{ margin-left: auto; padding: 18px 34px; border: 5px solid #FFCC00; color: #FFCC00; font-size: 32px; font-weight: 700; }}
.note {{ margin-top: auto; font-size: 26px; color: #C9C9CE; }}
.app {{ display: flex; gap: 30px; align-items: center; margin-top: 40px; }}
.ico {{ width: 132px; height: 132px; background: #222226; border: 2px solid #45454C; display: grid; grid-template-columns: 1fr 1fr; gap: 8px; padding: 28px; }}
.ico i {{ background: #F4F4F5; }}
.app b {{ display: block; font-size: 46px; }} .app span {{ display: block; font-size: 26px; color: #9B9BA3; margin-top: 4px; }}
.install {{ margin-top: 40px; background: #06C755; color: #FFFFFF; text-align: center; font-size: 34px; font-weight: 700; padding: 24px; border: 5px solid #FFCC00; }}
.shots {{ display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px; margin-top: 40px; }}
.shots i {{ height: 190px; background: #1A1A1D; border: 2px solid #2E2E33; }}
"""


def rows(items):
    return '<div class="rows">' + ''.join(
        f'<div class="row{" on" if on else ""}"><b>{html.escape(a)}</b><span>{html.escape(b)}</span></div>'
        for a, b, on in items) + '</div>'


def devices(platform):
    return (f'<p class="top">{platform} Windows App</p><h1>Devices</h1>'
            '<div class="empty"><b>No devices</b><span>Add your first device to get started.</span></div>'
            '<div class="bar"><span>Devices</span><span>Settings</span><span class="add">+ Add</span></div>')


def credentials():
    return ('<h1>Add PC</h1><p class="sub">ข้อมูลเข้าเครื่อง (Credentials)</p>' + rows([
        ('PC Name', '203.0.113.10', False),
        ('Credentials', 'Ask When Required', True),
        ('Friendly Name', 'EA BULL VPS', False)]))


def save(note):
    return ('<h1>Add PC</h1><p class="sub">ตรวจชื่อเรียกแล้วบันทึก</p>' + rows([
        ('Friendly Name', 'EA BULL VPS', True),
        ('Gateway', 'No Gateway', False),
        ('Clipboard', 'เปิดใช้งาน', False)]) + f'<p class="note">{html.escape(note)}</p>')


SCREENS = {
    'gvpsa-install-windows-app': ('<p class="top">Google Play</p>'
                                  '<div class="app"><div class="ico"><i></i><i></i><i></i><i></i></div>'
                                  '<div><b>Windows App</b><span>Microsoft Corporation</span></div></div>'
                                  '<div class="install">Install</div><div class="shots"><i></i><i></i><i></i></div>'
                                  '<p class="note">ใช้เชื่อมต่อคอมพิวเตอร์และ Windows VPS จากมือถือ</p>'),
    'gvpsa-devices-add': devices('Android'),
    'gvpsa-add-pc': ('<h1>Add</h1><p class="sub">เลือกสิ่งที่จะเพิ่ม</p>' + rows([
        ('Work or School Account', 'สำหรับบัญชีของบริษัทหรือองค์กร', False),
        ('Workspace', 'เพิ่มจากลิงก์ที่ผู้ดูแลระบบให้มา', False),
        ('PC', 'เพิ่มคอมพิวเตอร์หรือ VPS ด้วย IP', True)])),
    'gvpsa-pc-name': ('<h1>Add PC</h1><p class="sub">ใส่ที่อยู่ของ VPS</p>' + rows([
        ('PC Name', 'Hostname or IP Address', True),
        ('User Account', 'Ask When Required', False),
        ('Show Additional Options', 'ตั้งค่าเพิ่มเติม', False)])),
    'gvpsa-credentials': credentials(),
    'gvpsa-save': save('กด Save เพื่อบันทึกเครื่องนี้'),
    'gvpsi-credentials': credentials(),
    'gvpsi-devices-add': devices('iPhone'),
    'gvpsi-save': save('กด Save ที่มุมขวาบนของหน้าจอ'),
}


def main():
    TMP.mkdir(parents=True, exist_ok=True)
    OUT.mkdir(parents=True, exist_ok=True)
    for name, body in SCREENS.items():
        page = TMP / f'{name}.html'
        page.write_text(f'<!doctype html><html lang="th"><head><meta charset="utf-8"><style>{CSS}</style></head>'
                        f'<body><div class="frame">{body}</div></body></html>', encoding='utf-8')
        png = TMP / f'{name}.png'
        subprocess.run([CHROME, '--headless=new', '--disable-gpu', '--hide-scrollbars', '--force-device-scale-factor=1',
                        '--allow-file-access-from-files', '--virtual-time-budget=3000', f'--window-size={W},{H}',
                        f'--screenshot={png}', page.as_uri()], capture_output=True, timeout=120)
        Image.open(png).convert('RGB').save(OUT / f'eabull-{name}-v1.webp', quality=90, method=6)
        print(f'eabull-{name}-v1.webp')


if __name__ == '__main__':
    main()
