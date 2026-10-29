"""Crop the owner's EA BULL logo into web assets for the mockup.

Source files live outside the repo (owner's brand folder) and are read only.
Usage: py -3 -I dev/tools/make-logo.py
"""
import sys
from pathlib import Path
from PIL import Image

sys.stdout.reconfigure(encoding='utf-8')

SRC_DIR = Path(r"D:\EA VIDEO\EA META DATA\EA BULL")
OUT = Path(__file__).resolve().parents[1] / "mockup" / "assets" / "img"
OUT.mkdir(parents=True, exist_ok=True)

# In ORIGINAL LOGO.png the lettering starts at row 984 while the bull's chin
# (x 730..830) still reaches down to row 993, so the split needs a notch.
SPLIT_TOP, SPLIT_BOTTOM = 984, 995
CHIN_X = (730, 830)


def trim(im, threshold=24):
    mask = im.getchannel("A").point(lambda v: 255 if v > threshold else 0)
    return im.crop(mask.getbbox())


def save(im, name, width):
    if im.width > width:
        im = im.resize((width, round(im.height * width / im.width)), Image.LANCZOS)
    im.save(OUT / f"{name}.png", optimize=True)
    im.save(OUT / f"{name}.webp", quality=88, method=6)
    print(f"{name}: {im.size}")


logo = Image.open(SRC_DIR / "EA BULL ORIGINAL LOGO.png").convert("RGBA")
w, h = logo.size
save(trim(logo), "bull-logo", 640)
emblem = logo.crop((0, 0, w, SPLIT_BOTTOM))
clear = Image.new("RGBA", (w, SPLIT_BOTTOM - SPLIT_TOP))
emblem.paste(clear.crop((0, 0, CHIN_X[0], clear.height)), (0, SPLIT_TOP))
emblem.paste(clear.crop((0, 0, w - CHIN_X[1], clear.height)), (CHIN_X[1], SPLIT_TOP))
save(trim(emblem), "bull-emblem", 640)

wordmark = logo.crop((0, SPLIT_TOP, w, h))
wordmark.paste(clear.crop((0, 0, CHIN_X[1] - CHIN_X[0], clear.height)), (CHIN_X[0], 0))
save(trim(wordmark), "bull-wordmark", 720)

icon = Image.open(SRC_DIR / "EA BULL LOGO 128X128.png").convert("RGBA")
icon.save(OUT / "favicon-128.png", optimize=True)
icon.resize((32, 32), Image.LANCZOS).save(OUT / "favicon-32.png", optimize=True)
print("favicon: done")
