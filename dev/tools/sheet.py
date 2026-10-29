"""Turn tall screenshots into images that fit chat limits (max 8000 px a side).

Phone shots become a contact sheet: the page is cut into columns placed side
by side. Desktop shots taller than the limit are cut into numbered parts.
Usage: py -3 -I dev/tools/sheet.py home-375 home-1366 ...
Output: dev/shots/send/
"""
import sys
from pathlib import Path

from PIL import Image

sys.stdout.reconfigure(encoding='utf-8')

SHOTS = Path(__file__).resolve().parents[2] / 'dev' / 'shots'
SEND = SHOTS / 'send'
BG = (44, 44, 50)
GAP = 28


def phone_sheet(im, name, col_h=2600):
    cols = []
    top = 0
    while top < im.height:
        cols.append(im.crop((0, top, im.width, min(top + col_h, im.height))))
        top += col_h
    per_row = 6
    rows = [cols[i:i + per_row] for i in range(0, len(cols), per_row)]
    out = []
    for r, row in enumerate(rows):
        w = len(row) * im.width + (len(row) + 1) * GAP
        h = col_h + 2 * GAP
        sheet = Image.new('RGB', (w, h), BG)
        for i, c in enumerate(row):
            sheet.paste(c, (GAP + i * (im.width + GAP), GAP))
        path = SEND / (f'{name}.png' if len(rows) == 1 else f'{name}-{r + 1}.png')
        sheet.save(path, optimize=True)
        out.append(path)
    return out


def desktop_parts(im, name, part_h=7800):
    if im.height <= 7900:
        path = SEND / f'{name}.png'
        im.save(path, optimize=True)
        return [path]
    out = []
    n = -(-im.height // part_h)
    step = -(-im.height // n)
    for i in range(n):
        path = SEND / f'{name}-{i + 1}.png'
        im.crop((0, i * step, im.width, min((i + 1) * step, im.height))).save(path, optimize=True)
        out.append(path)
    return out


def main():
    SEND.mkdir(parents=True, exist_ok=True)
    for name in sys.argv[1:]:
        im = Image.open(SHOTS / f'{name}.png').convert('RGB')
        paths = phone_sheet(im, name) if im.width < 600 else desktop_parts(im, name)
        for p in paths:
            print(p)


if __name__ == '__main__':
    main()
