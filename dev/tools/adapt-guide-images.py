"""Bring the EA WING guide screenshots into the EA BULL theme.

The owner allowed reusing them on 29 Oct 2026 ("ใช้รูปจากอีเอวิงก์ได้เลย อันไหนไม่ได้
ก็ปรับให้เป็นของเรา"). Source folder is read only: D:\\EA VIDEO\\ea-wing-repo\\eawing\\assets\\img
  - guides/*.webp      copied as eabull-*.webp (real Zaurix / MT5 / Remote Desktop screens)
  - install/ MT5 shots copied and edited: the EA WING file name becomes "EA_BULL",
    the chart's symbol line is blanked so it does not hint at a symbol or timeframe
  - skipped: the EA WING /go button shot and the 9 simulated Windows App screens
    (navy and gold, "EA WING VPS"), which dev/tools/vps-screens.py redraws in Bull Black
Usage: py -3 -I dev/tools/adapt-guide-images.py
"""
import sys
from collections import Counter
from pathlib import Path

from PIL import Image, ImageDraw, ImageFont

sys.stdout.reconfigure(encoding='utf-8')

SRC = Path(r"D:\EA VIDEO\ea-wing-repo\eawing\assets\img")
OUT = Path(__file__).resolve().parents[2] / 'eabull' / 'assets' / 'img'
SKIP = {'eawing-go-zaurix-signup-button-v1.webp'} | {
    f'eawing-gvps{s}-{n}-v1.webp' for s, names in {
        'a': ['add-pc', 'credentials', 'devices-add', 'install-windows-app', 'pc-name', 'save'],
        'i': ['credentials', 'devices-add', 'save'],
    }.items() for n in names}
FONT = r'C:\Windows\Fonts\segoeui.ttf'
FONT_BOLD = r'C:\Windows\Fonts\segoeuib.ttf'
# output names that must not hint a symbol or timeframe the owner has not given
RENAME = {'eawing-mt5-xauusd-m1-chart-v2.webp': 'eabull-mt5-ea-on-chart-v2.webp'}
NEW = 'EA_BULL'
OLD = 'EA_WING_V5.0'

# file: list of (rough region, replacement text or None to blank, bold)
EDITS = {
    'eawing-mt5-common-tab-v3.webp': [((20, 12, 232, 46), NEW, False), ((130, 96, 252, 126), NEW, True)],
    'eawing-mt5-experts-folder-v2.webp': [((64, 274, 182, 302), NEW, False)],
    'eawing-mt5-navigator-v2.webp': [],  # done by navigator() below: the selection box needs exact pixels
    'eawing-mt5-xauusd-m1-chart-v2.webp': [((1284, 30, 1381, 53), NEW, False), ((58, 30, 472, 53), None, False)],
    'eawing-mt5-open-data-folder-v1.webp': [],
}


def lum(c):
    return 0.299 * c[0] + 0.587 * c[1] + 0.114 * c[2]


def edit(im, region, text, bold):
    x0, y0, x1, y1 = region
    crop = im.crop(region)
    bg = Counter(crop.get_flattened_data()).most_common(1)[0][0]
    # tight box of the old text = pixels clearly different from the background
    pts = [(x, y) for y in range(crop.height) for x in range(crop.width)
           if abs(lum(crop.getpixel((x, y))) - lum(bg)) > 40]
    if not pts:
        return
    tx0 = x0 + min(p[0] for p in pts)
    ty0 = y0 + min(p[1] for p in pts)
    tx1 = x0 + max(p[0] for p in pts)
    ty1 = y0 + max(p[1] for p in pts)
    ink = max(crop.get_flattened_data(), key=lambda c: abs(lum(c) - lum(bg)))
    draw = ImageDraw.Draw(im)
    if lum(bg) < 60:
        # dark chart: blank the band, then copy the dotted grid columns back from
        # the clean rows just below it (24 px down keeps the dot rhythm)
        draw.rectangle((tx0 - 2, ty0 - 2, tx1 + 2, ty1 + 2), fill=bg)
        for x in range(tx0 - 2, tx1 + 3):
            below = [lum(im.getpixel((x, y))) for y in range(ty1 + 4, ty1 + 28)]
            if sum(1 for v in below if 35 < v < 170) >= 6:
                for y in range(ty0 - 2, ty1 + 3):
                    im.putpixel((x, y), im.getpixel((x, y + 24)))
    else:
        draw.rectangle((tx0 - 1, ty0 - 1, tx1 + 1, ty1 + 1), fill=bg)
    if text is None:
        return
    old = 'EA_WING_V5.0 (XAUUSD.c,M1)' if (tx1 - tx0) > 150 and not bold and region[0] < 40 else OLD
    path = FONT_BOLD if bold else FONT
    # pick the size whose rendering of the old label matches its measured width
    best = min(range(8, 40), key=lambda s: abs(ImageFont.truetype(path, s).getbbox(old)[2] - (tx1 - tx0)))
    font = ImageFont.truetype(path, best)
    bx0, by0, _, _ = font.getbbox(text)
    oy = font.getbbox(old)[1]
    draw.text((tx0 - bx0, ty0 - oy), text, font=font, fill=ink)


def navigator(im):
    """Repaint the selected row of the Navigator tree with the new name."""
    fill = (205, 232, 252)
    draw = ImageDraw.Draw(im)
    draw.rectangle((74, 210, 196, 230), fill=fill)
    font = ImageFont.truetype(FONT, 15)
    top = font.getbbox(NEW)[1]
    draw.text((78, 214 - top), NEW, font=font, fill=(16, 28, 38))


def main():
    (OUT / 'guides').mkdir(parents=True, exist_ok=True)
    (OUT / 'install').mkdir(parents=True, exist_ok=True)
    for f in sorted((SRC / 'guides').glob('*.webp')):
        if f.name in SKIP:
            continue
        Image.open(f).save(OUT / 'guides' / f.name.replace('eawing-', 'eabull-'), quality=90, method=6)
        print('guides/', f.name.replace('eawing-', 'eabull-'))
    for name, edits in EDITS.items():
        im = Image.open(SRC / 'install' / name).convert('RGB')
        for region, text, bold in edits:
            edit(im, region, text, bold)
        if 'navigator' in name:
            navigator(im)
        out = RENAME.get(name, name.replace('eawing-', 'eabull-'))
        im.save(OUT / 'install' / out, quality=90, method=6)
        print('install/', name.replace('eawing-', 'eabull-'), f'{len(edits)} edit(s)')


if __name__ == '__main__':
    main()
