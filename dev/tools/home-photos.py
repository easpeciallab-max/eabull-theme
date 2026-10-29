"""Home page photos: free Unsplash photos of dark trading screens, kept in their own colours.

The owner asked for photos on the home page in one consistent style (29 Oct 2026) and
said not to use the sample images on his computer. Sources are Unsplash License photos
(free for commercial use, no attribution required) downloaded to a scratch folder:
  49.jpg  Arturo Añez         unsplash.com/photos/Q_vhJv5im-8   rising candlestick chart
  79.jpg  Jakub Żerdzicki     unsplash.com/photos/-DWqOL_d91I   trader with calculator and charts
  63.jpg  Jakub Żerdzicki     unsplash.com/photos/sqok1QIK1mw   hands holding a phone chart
  70.jpg  Dimitri Karastelev  unsplash.com/photos/bJb75YQRl98   tablet chart by candlelight
All four are dark trading-screen photos so they read as one set. Colours stay as shot
(owner 29 Oct 2026: no yellow tint); the script only crops and resizes.
Usage: py -3 -I dev/tools/home-photos.py <folder with the downloaded jpgs>
Output: eabull/assets/img/home/eabull-*.webp
"""
import sys
from pathlib import Path

from PIL import Image

sys.stdout.reconfigure(encoding='utf-8')
OUT = Path(__file__).resolve().parents[2] / 'eabull' / 'assets' / 'img' / 'home'

# source, output name, size (w, h), horizontal focus 0..1, vertical focus 0..1
PHOTOS = [
    ('49.jpg', 'eabull-ea-mt5-candlestick-chart', (960, 720), .55, .5),
    ('79.jpg', 'eabull-mt5-trader-desk', (1600, 600), .5, .45),
    ('70.jpg', 'eabull-mt5-chart-tablet', (720, 900), .5, .5),
    ('63.jpg', 'eabull-mt5-mobile-chart', (960, 720), .5, .5),
]

def crop(im, size, fx, fy):
    w, h = size
    scale = max(w / im.width, h / im.height)
    im = im.resize((round(im.width * scale), round(im.height * scale)), Image.LANCZOS)
    x = round((im.width - w) * fx)
    y = round((im.height - h) * fy)
    return im.crop((x, y, x + w, y + h))


def main():
    src = Path(sys.argv[1])
    OUT.mkdir(parents=True, exist_ok=True)
    for name, out, size, fx, fy in PHOTOS:
        im = Image.open(src / name).convert('RGB')
        im = crop(im, size, fx, fy)
        im.save(OUT / f'{out}.webp', quality=82, method=6)
        print(f'{out}.webp', im.size)


if __name__ == '__main__':
    main()
