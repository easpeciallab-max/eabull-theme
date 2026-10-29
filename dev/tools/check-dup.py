"""Find Thai copy in the EA BULL theme that repeats text from the EA WING theme.

Both brands belong to the same owner, so copied sentences count as duplicate
content across domains (see docs/brief.md). Every Thai text chunk in eabull/ is
cut into 12-character shingles; a chunk is flagged when most of its shingles
also appear somewhere in the EA WING theme (D:\\EA VIDEO\\ea-wing-repo\\eawing,
read only). Brand names are blanked first so "EA BULL" vs "EA WING" does not hide a copy.
Short labels and MT5 menu names repeat naturally, so only chunks with at least
30 Thai characters count, and the default bar is 70% of shingles.

Usage: py -3 -I dev/tools/check-dup.py [--min 0.7] [--show 40] [path ...]
Exit code 1 when anything is flagged.
"""
import argparse
import re
import sys
from pathlib import Path

sys.stdout.reconfigure(encoding='utf-8')

ROOT = Path(__file__).resolve().parents[2]
OURS = ROOT / 'eabull'
THEIRS = Path(r'D:\EA VIDEO\ea-wing-repo\eawing')
SKIP_PARTS = {'articles'}  # posts are phase 4 and are not forked
EXT = {'.php', '.html', '.js'}
K = 12
MIN_THAI = 30
THAI = re.compile(r'[\u0E00-\u0E7F]')
CHUNK = re.compile(r"[^<>\"'`{}|]+")  # text between quotes/tags
BRANDS = re.compile(r'EA ?BULL|EA ?WING|WING (?:NITRO|TWIN)|eabull|eawing', re.I)


def chunks(path):
    text = path.read_text(encoding='utf-8', errors='replace')
    for lineno, line in enumerate(text.splitlines(), 1):
        for m in CHUNK.finditer(line):
            seg = BRANDS.sub('#', m.group(0)).strip()
            if len(THAI.findall(seg)) >= MIN_THAI:
                yield lineno, seg


def norm(s):
    return re.sub(r'\s+', ' ', s)


def shingles(s):
    s = norm(s)
    return {s[i:i + K] for i in range(max(1, len(s) - K + 1))}


def files(base):
    for p in base.rglob('*'):
        if p.is_file() and p.suffix in EXT and not SKIP_PARTS.intersection(p.parts):
            yield p


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument('paths', nargs='*')
    ap.add_argument('--min', type=float, default=0.7, help='share of shingles that must match')
    ap.add_argument('--show', type=int, default=40)
    a = ap.parse_args()

    theirs = set()
    for p in files(THEIRS):
        for _, seg in chunks(p):
            theirs |= shingles(seg)

    targets = [ROOT / x for x in a.paths] if a.paths else [OURS]
    hits, total = [], 0
    for t in targets:
        for p in ([t] if t.is_file() else files(t)):
            for lineno, seg in chunks(p):
                total += 1
                sh = shingles(seg)
                share = len(sh & theirs) / len(sh)
                if share >= a.min:
                    hits.append((share, p.relative_to(ROOT).as_posix(), lineno, norm(seg)[:90]))
    hits.sort(key=lambda h: (h[1], h[2]))
    by_file = {}
    for h in hits:
        by_file[h[1]] = by_file.get(h[1], 0) + 1
    for share, f, lineno, seg in hits[:a.show]:
        print(f'{share:4.0%}  {f}:{lineno}  {seg}')
    if len(hits) > a.show:
        print(f'... {len(hits) - a.show} more')
    print('--- per file:')
    for f, n in sorted(by_file.items(), key=lambda x: -x[1]):
        print(f'{n:5d}  {f}')
    print(f'{len(hits)} of {total} Thai chunks repeat EA WING text' if hits else f'no duplicates in {total} Thai chunks')
    sys.exit(1 if hits else 0)


if __name__ == '__main__':
    main()
