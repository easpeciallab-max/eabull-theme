"""Full-page screenshots of the mockup with headless Chrome.

Needs the preview server running (launch config "eabull-mockup", port 8790).
Usage:
  py -3 -I dev/tools/shoot.py home pricing          # widths 1366 and 375
  py -3 -I dev/tools/shoot.py all --widths 1366 820 375
  py -3 -I dev/tools/shoot.py home --query "v=a"    # extra URL query
Output: dev/shots/<page>-<width>[-<tag>].png (+ a small .jpg preview strip set
in dev/shots/_review/ for quick checks).
"""
import argparse
import re
import subprocess
import sys
from pathlib import Path
from urllib.parse import quote

from PIL import Image

sys.stdout.reconfigure(encoding='utf-8')

ROOT = Path(__file__).resolve().parents[2]
MOCKUP = ROOT / 'dev' / 'mockup'
OUT = ROOT / 'dev' / 'shots'
REVIEW = OUT / '_review'
BASE = 'http://localhost:8790/'
CHROME = r'C:\Program Files\Google\Chrome\Application\chrome.exe'
COMMON = ['--headless=new', '--disable-gpu', '--hide-scrollbars', '--force-device-scale-factor=1',
          '--virtual-time-budget=5000', '--disable-extensions']


def run(args):
    return subprocess.run([CHROME, *COMMON, *args], capture_output=True, text=True,
                          encoding='utf-8', errors='replace', timeout=120)


def framed(url, width, viewport=False):
    """Phone widths go through _frame.html (Chrome's window has a minimum width)."""
    keep = '&keep=1' if viewport else ''
    return f'{BASE}_frame.html?w={width}{keep}&src=' + quote(url, safe='')


def height(url, width, frame_w):
    if frame_w:
        target = framed(url, width)
    else:
        target = url + ('&' if '?' in url else '?') + 'measure'
    for _ in range(3):  # fonts can be slow when the machine is busy
        res = run([f'--window-size={frame_w or width},900', '--dump-dom', target])
        m = re.search(r'<meta name="page-height" content="(\d+)">', res.stdout)
        if m:
            return int(m.group(1))
    raise SystemExit(f'no height for {url} at {width}')


def review_strips(png, width):
    """Cut a tall shot into scaled strips that are easy to look at."""
    im = Image.open(png).convert('RGB')
    scale = 0.5 if width > 900 else 0.8
    step = 1500 if width > 900 else 1100
    REVIEW.mkdir(parents=True, exist_ok=True)
    for i, top in enumerate(range(0, im.height, step)):
        part = im.crop((0, top, im.width, min(top + step, im.height)))
        part = part.resize((round(part.width * scale), round(part.height * scale)), Image.LANCZOS)
        part.save(REVIEW / f'{png.stem}-{i + 1:02d}.jpg', quality=82)


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument('pages', nargs='+')
    ap.add_argument('--widths', nargs='+', type=int, default=[1366, 375])
    ap.add_argument('--query', default='shot')
    ap.add_argument('--tag', default='')
    ap.add_argument('--no-review', action='store_true')
    ap.add_argument('--vh', type=int, default=0, help='viewport-only shot of this height')
    a = ap.parse_args()
    pages = a.pages
    if pages == ['all']:
        pages = sorted(p.stem for p in MOCKUP.glob('*.html') if not p.stem.startswith('_'))
    OUT.mkdir(parents=True, exist_ok=True)
    query = a.query if 'shot' in a.query else f'shot&{a.query}'
    for page in pages:
        url = f'{BASE}{page}.html?{query}'
        for w in a.widths:
            frame_w = 600 if w < 600 else 0
            h = a.vh or height(url, w, frame_w)
            name = f'{page}-{w}' + (f'-{a.tag}' if a.tag else '')
            png = OUT / f'{name}.png'
            if frame_w:
                target = framed(url, w, viewport=bool(a.vh)) + (f'&vh={a.vh}' if a.vh else '')
                run([f'--window-size={frame_w},{h}', f'--screenshot={png}', target])
                Image.open(png).crop((0, 0, w, h)).save(png)
            else:
                run([f'--window-size={w},{h}', f'--screenshot={png}', url])
            if not a.no_review:
                review_strips(png, w)
            print(f'{name}: {w}x{h}')


if __name__ == '__main__':
    main()
