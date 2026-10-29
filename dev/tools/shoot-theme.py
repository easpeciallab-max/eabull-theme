"""Full-page screenshots of the WordPress theme preview (port 8791).

Every width goes through /mockup/_frame.html on the theme preview itself, so the
frame can read the page height (same origin) and phone widths work too.
Usage: py -3 -I dev/tools/shoot-theme.py "" about pricing --widths 1366 375
       ("" = home page, "?s=MT5" = search)
Output: dev/shots/theme/<name>-<width>.png
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
OUT = ROOT / 'dev' / 'shots' / 'theme'
BASE = 'http://localhost:8791/'
CHROME = r'C:\Program Files\Google\Chrome\Application\chrome.exe'
COMMON = ['--headless=new', '--disable-gpu', '--hide-scrollbars', '--force-device-scale-factor=1',
          '--virtual-time-budget=6000', '--disable-extensions']


def run(args):
    return subprocess.run([CHROME, *COMMON, *args], capture_output=True, text=True,
                          encoding='utf-8', errors='replace', timeout=180)


def page_url(page):
    if page.startswith('?'):
        return BASE + page
    return BASE + (page.strip('/') + '/' if page else '')


def framed(url, width):
    return f'{BASE}mockup/_frame.html?w={width}&src=' + quote(url, safe='')


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument('pages', nargs='+')
    ap.add_argument('--widths', nargs='+', type=int, default=[1366, 375])
    a = ap.parse_args()
    OUT.mkdir(parents=True, exist_ok=True)
    for page in a.pages:
        name = (page.strip('/?').replace('=', '-') or 'home')
        for w in a.widths:
            win = max(w, 600)
            url = framed(page_url(page), w)
            res = run([f'--window-size={win},900', '--dump-dom', url])
            m = re.search(r'<meta name="page-height" content="(\d+)">', res.stdout)
            h = int(m.group(1)) if m else 4000
            png = OUT / f'{name}-{w}.png'
            run([f'--window-size={win},{h}', f'--screenshot={png}', url])
            Image.open(png).crop((0, 0, w, h)).save(png)
            print(f'{name}-{w}: {w}x{h}')


if __name__ == '__main__':
    main()
