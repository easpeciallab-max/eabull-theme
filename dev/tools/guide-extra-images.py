"""Guide images for the steps that had no screenshot, made from the EA WING shots already in the theme.

The owner asked on 29 Oct 2026 to fill the missing guide steps by converting the EA WING images
("เอาภาพของอีเอวิงก์มาแปลงแล้วใส่ได้เลย ต้องการแบบนี้เท่านั้น"):
  - open-mt5-account step 1: EA WING showed its own /go/ signup button, so the EA BULL version is the
    same step on eabull.com/go/ (captured from the live page by dev/tools/shot-element.mjs)
  - deposit steps 3 and 5: crops of the Zaurix Funds screenshots with a flat yellow frame on the field
  - how-to-install step 6: the toolbar's Algo Trading button and the EA name at the chart corner,
    two crops of the MT5 chart shot stacked (EA WING used its product dashboard here, which EA BULL
    does not have data for)
The deposit "check Balance" step stays without an image, as in EA WING.
Usage: py -3 -I dev/tools/guide-extra-images.py      (needs Node and Chrome for the /go/ capture)
"""
import subprocess
import sys
import tempfile
import time
from pathlib import Path

from PIL import Image, ImageDraw

sys.stdout.reconfigure(encoding='utf-8')

ROOT = Path(__file__).resolve().parents[2]
IMG = ROOT / 'eabull' / 'assets' / 'img'
ACCENT = (255, 204, 0)
BLACK = (10, 10, 10)


def frame(im, box, width=3):
    """Flat square highlight (brand yellow, no glow)."""
    draw = ImageDraw.Draw(im)
    x0, y0, x1, y1 = box
    for i in range(width):
        draw.rectangle((x0 - i, y0 - i, x1 + i, y1 + i), outline=ACCENT)
    return im


def save(im, rel):
    out = IMG / rel
    im.convert('RGB').save(out, quality=90, method=6)
    print(rel, im.size)


def go_signup():
    with tempfile.TemporaryDirectory() as tmp:
        png = Path(tmp) / 'go.png'
        url = f'https://eabull.com/go/?shot&nocache={int(time.time())}'
        subprocess.run(['node', str(ROOT / 'dev' / 'tools' / 'shot-element.mjs'), url, '.gostep', str(png), '480', '2', '16'], check=True)
        im = Image.open(png).convert('RGB')
        # drop the divider line under the step (the 16 px padding catches it)
        save(im.crop((0, 0, im.width, im.height - 38)), 'guides/eabull-go-zaurix-signup-v1.webp')


def deposit_account():
    im = Image.open(IMG / 'guides' / 'eabull-deposit-form-v1.webp').convert('RGB')
    im = im.crop((200, 170, 650, 480))
    save(frame(im, (38, 154, 412, 202)), 'guides/eabull-deposit-trading-account-v1.webp')


def deposit_confirm():
    im = Image.open(IMG / 'guides' / 'eabull-deposit-payment-details-v1.webp').convert('RGB')
    im = im.crop((250, 196, 800, 420))
    save(frame(im, (40, 64, 518, 156)), 'guides/eabull-deposit-confirm-details-v1.webp')


def algo_and_corner():
    im = Image.open(IMG / 'install' / 'eabull-mt5-ea-on-chart-v2.webp').convert('RGB')
    left = frame(im.crop((0, 0, 520, 150)), (3, 3, 130, 25))
    right = frame(im.crop((880, 0, 1400, 150)), (398, 28, 516, 52))
    gap = 16
    out = Image.new('RGB', (520, 150 * 2 + gap), BLACK)
    out.paste(left, (0, 0))
    out.paste(right, (0, 150 + gap))
    save(out, 'install/eabull-mt5-algo-on-ea-corner-v1.webp')


if __name__ == '__main__':
    go_signup()
    deposit_account()
    deposit_confirm()
    algo_and_corner()
