"""Brand images for the EA BULL theme, made from the owner's logo files.

Source (read only): D:\\EA VIDEO\\EA META DATA\\EA BULL\\EA BULL ORIGINAL LOGO.png
Output: eabull/assets/img/brand/
  eabull-logo.webp/.png        full logo (bull + lettering), transparent
  eabull-emblem.webp/.png      bull and rising bars only
  eabull-wordmark-dark.webp    "EA BULL" lettering for dark backgrounds
  eabull-wordmark-light.webp   same lettering (the metal colours read on light too)
  eabull-icon-512.png          emblem on a flat black square (site icon source)
  eabull-icon-180.png / eabull-icon-96.webp / favicon-32.png   (names the theme code uses)
  eabull-logo-160.webp         emblem for the header brand
  eabull-share-1200x630.jpg    default og:image: flat black, logo, yellow base bar
  ../logo.png                  fallback for eab_logo_url()
  ../../../screenshot.png      theme cover in wp-admin (1200x900), same look
Rerun after a logo change and bump the file names (Cloudflare caches by URL).
Usage: py -3 -I dev/tools/make-brand.py
"""
import sys
from pathlib import Path

from PIL import Image

sys.stdout.reconfigure(encoding='utf-8')

SRC = Path(r"D:\EA VIDEO\EA META DATA\EA BULL\EA BULL ORIGINAL LOGO.png")
OUT = Path(__file__).resolve().parents[2] / 'eabull' / 'assets' / 'img' / 'brand'
BLACK = (11, 11, 12, 255)
YELLOW = (255, 204, 0, 255)
SPLIT_TOP, SPLIT_BOTTOM, CHIN_X = 984, 995, (730, 830)  # see dev/tools/make-logo.py


def trim(im, threshold=24):
    mask = im.getchannel('A').point(lambda v: 255 if v > threshold else 0)
    return im.crop(mask.getbbox())


def fit(im, width):
    return im.resize((width, round(im.height * width / im.width)), Image.LANCZOS) if im.width > width else im


def save(im, name, webp=True, png=True):
    if png:
        im.save(OUT / f'{name}.png', optimize=True)
    if webp:
        im.save(OUT / f'{name}.webp', quality=88, method=6)
    print(name, im.size)


def on_square(art, size, pad):
    sq = Image.new('RGBA', (size, size), BLACK)
    art = art.copy()
    art.thumbnail((size - 2 * pad, size - 2 * pad), Image.LANCZOS)
    sq.alpha_composite(art, ((size - art.width) // 2, (size - art.height) // 2))
    return sq


def main():
    OUT.mkdir(parents=True, exist_ok=True)
    logo = Image.open(SRC).convert('RGBA')
    w, h = logo.size
    clear = Image.new('RGBA', (w, SPLIT_BOTTOM - SPLIT_TOP))

    emblem = logo.crop((0, 0, w, SPLIT_BOTTOM))
    emblem.paste(clear.crop((0, 0, CHIN_X[0], clear.height)), (0, SPLIT_TOP))
    emblem.paste(clear.crop((0, 0, w - CHIN_X[1], clear.height)), (CHIN_X[1], SPLIT_TOP))
    emblem = trim(emblem)
    wordmark = logo.crop((0, SPLIT_TOP, w, h))
    wordmark.paste(clear.crop((0, 0, CHIN_X[1] - CHIN_X[0], clear.height)), (CHIN_X[0], 0))
    wordmark = trim(wordmark)
    full = trim(logo)

    save(fit(full, 640), 'eabull-logo')
    save(fit(emblem, 640), 'eabull-emblem')
    save(fit(wordmark, 720), 'eabull-wordmark-dark', png=False)
    save(fit(wordmark, 720), 'eabull-wordmark-light', png=False)
    icon = on_square(emblem, 512, 56)
    save(icon, 'eabull-icon-512', webp=False)
    save(icon.resize((180, 180), Image.LANCZOS), 'eabull-icon-180', webp=False)
    save(icon.resize((96, 96), Image.LANCZOS), 'eabull-icon-96', png=False)
    save(on_square(emblem, 64, 4).resize((32, 32), Image.LANCZOS), 'favicon-32', webp=False)
    save(fit(emblem, 160), 'eabull-logo-160', png=False)
    fit(full, 640).save(OUT.parent / 'logo.png', optimize=True)

    share = Image.new('RGBA', (1200, 630), BLACK)
    art = full.copy()
    art.thumbnail((1200, 470), Image.LANCZOS)
    share.alpha_composite(art, ((1200 - art.width) // 2, 50))
    bar = Image.new('RGBA', (1200, 34), YELLOW)
    share.alpha_composite(bar, (0, 630 - 34))
    share.convert('RGB').save(OUT / 'eabull-share-1200x630.jpg', quality=88, optimize=True)
    print('eabull-share-1200x630.jpg', share.size)

    cover = Image.new('RGBA', (1200, 900), BLACK)
    art = full.copy()
    art.thumbnail((1200, 660), Image.LANCZOS)
    cover.alpha_composite(art, ((1200 - art.width) // 2, 80))
    cover.alpha_composite(Image.new('RGBA', (1200, 48), YELLOW), (0, 900 - 48))
    cover.convert('RGB').save(OUT.parents[2] / 'screenshot.png', optimize=True)
    print('screenshot.png', cover.size)


if __name__ == '__main__':
    main()
