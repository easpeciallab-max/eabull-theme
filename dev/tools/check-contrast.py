"""Contrast check for the EA BULL design tokens (WCAG 2.x, text needs 4.5:1).

Keep the pairs in sync with :root in dev/mockup/assets/css/bull.css.
Usage: py -3 -I dev/tools/check-contrast.py
"""
import sys

sys.stdout.reconfigure(encoding='utf-8')


def lum(hex_color):
    h = hex_color.lstrip('#')
    out = []
    for i in (0, 2, 4):
        c = int(h[i:i + 2], 16) / 255
        out.append(c / 12.92 if c <= 0.03928 else ((c + 0.055) / 1.055) ** 2.4)
    r, g, b = out
    return 0.2126 * r + 0.7152 * g + 0.0722 * b


def ratio(a, b):
    la, lb = sorted((lum(a), lum(b)), reverse=True)
    return (la + 0.05) / (lb + 0.05)


DARK_BG = ['#0B0B0C', '#141416', '#1A1A1D', '#222226']
PAPER_BG = ['#F5F3EE', '#EBE8E1', '#FFFFFF']
PAIRS = [
    # (label, foreground, backgrounds, minimum)
    ('fg on dark', '#F4F4F5', DARK_BG, 4.5),
    ('fg-2 on dark', '#C9C9CE', DARK_BG, 4.5),
    ('muted on dark', '#9B9BA3', DARK_BG, 4.5),
    ('yellow text on dark', '#FFCC00', DARK_BG, 4.5),
    ('silver on dark', '#C8CCD2', DARK_BG, 4.5),
    ('ok on dark', '#3DD68C', DARK_BG, 4.5),
    ('bad on dark', '#FF6B6B', DARK_BG, 4.5),
    ('warn on dark', '#FF9F43', DARK_BG, 4.5),
    ('info on dark', '#5AA9FF', DARK_BG, 4.5),
    ('fg on paper', '#111113', PAPER_BG, 4.5),
    ('fg-2 on paper', '#3A3A40', PAPER_BG, 4.5),
    ('muted on paper', '#5F5F66', PAPER_BG, 4.5),
    ('yellow ink on paper', '#7A5E00', PAPER_BG, 4.5),
    ('ok on paper', '#0F6E3D', PAPER_BG, 4.5),
    ('bad on paper', '#C0322B', PAPER_BG, 4.5),
    ('warn on paper', '#954F00', PAPER_BG, 4.5),
    ('info on paper', '#1F5FBF', PAPER_BG, 4.5),
    ('black on yellow btn', '#0B0B0C', ['#FFCC00', '#EBBC00'], 4.5),
    ('black on silver btn', '#0B0B0C', ['#F4F4F5', '#DCDCE0'], 4.5),
    ('white on LINE (brand rule)', '#FFFFFF', ['#06C755'], 0),
    ('white on Facebook (brand rule)', '#FFFFFF', ['#1877F2'], 0),
]

failed = 0
for label, fg, bgs, minimum in PAIRS:
    cells = []
    for bg in bgs:
        r = ratio(fg, bg)
        mark = '' if r >= minimum else ' FAIL'
        failed += bool(mark)
        cells.append(f'{bg} {r:4.1f}{mark}')
    print(f'{label:32} {fg}  ' + ' | '.join(cells))
print('all pass' if not failed else f'{failed} fail')
