"""Article photos: free real photos (Unsplash License) placed in the seed articles.

The owner asked on 3 Nov 2026 for 3 to 4 real photos per article (no AI or made-up images),
free and good quality, renamed for SEO, with alt text and captions, never the same photo twice.
Pillars get 4 photos, supporting articles 3.

Photos come from unsplash.com with the "free" licence filter (never Unsplash+). The Unsplash
License allows commercial use without credit; the photographer and photo page are still kept
in dev/content/photos/<slug>.json. Picking rules (docs/content-plan.md "รูปประกอบ"):
  real photographs only (no illustrations, 3D renders or AI images) · no company or platform
  names or logos on screens · no crypto · no recognisable faces next to scam topics · colours as
  shot (no yellow tint) · a photo that shows numbers gets a caption that says "ภาพประกอบ"

Each photo is cropped to 16:9, saved as 1200 x 675 WebP (at most about 150 KB) in
eabull/assets/img/articles/<slug>/<name>.webp, and written into the article as
  [eabull_photo file="<slug>/<name>.webp" alt="..." caption="..." w="1200" h="675" credit="..."]
right after the first paragraph under the chosen H2. Setup then uploads it to the Media Library
(eab_seed_photos() in inc/setup.php) and the article's rev goes to 2 so "อัปเดตบทความที่มีอยู่"
replaces the scheduled posts.

Usage (py -3 -I dev/tools/article-photos.py ...):
  preview <url> [<url> ...] [--out sheet.png]   numbered contact sheet of candidate photos to look at
  add <slug> --src <images.unsplash.com/photo-... url> --page <unsplash.com/photos/... url>
      --credit "Photographer" --name <seo-file-name> --after "<text in the H2>"
      --alt "<Thai alt>" [--caption "<Thai caption>"]
  remove <slug> <name>
  sheet <slug> [--out file.png]                  the photos placed in one article, to check
  check [slug ...]                               counts, files, sizes, alt, duplicates (exit 1 on errors)
  used                                           Unsplash photo ids already used (to skip when picking)
"""
import io
import json
import re
import sys
import urllib.request
from pathlib import Path

from PIL import Image, ImageDraw

sys.stdout.reconfigure(encoding='utf-8')

ROOT = Path(__file__).resolve().parents[2]
ART = ROOT / 'eabull' / 'inc' / 'content' / 'articles'
OUT = ROOT / 'eabull' / 'assets' / 'img' / 'articles'
HOME = ROOT / 'eabull' / 'assets' / 'img' / 'home'
REG = ROOT / 'dev' / 'content' / 'photos'  # one JSON file per article (writers work in parallel)
PLAN = ROOT / 'dev' / 'content' / 'plan.json'
W, H = 1200, 675
MAX_BYTES = 150_000
DUP_BITS = 6  # dHash distance at or below this = the same picture
TOKEN = re.compile(r'\[eabull_photo\s+([^\]]*)\]')
# Unsplash page ids already on the site (home page photos, dev/tools/home-photos.py)
RESERVED = {'Q_vhJv5im-8': 'home', '-DWqOL_d91I': 'home', 'sqok1QIK1mw': 'home', 'bJb75YQRl98': 'home'}


def load_reg():
    out = []
    for f in sorted(REG.glob('*.json')):
        out += json.loads(f.read_text(encoding='utf-8'))
    return out


def save_slug(slug, entries):
    REG.mkdir(parents=True, exist_ok=True)
    f = REG / f'{slug}.json'
    entries = sorted(entries, key=lambda r: r['file'])
    if entries:
        f.write_text(json.dumps(entries, ensure_ascii=False, indent=1) + '\n', encoding='utf-8', newline='\n')
    else:
        f.unlink(missing_ok=True)


def plan():
    return {a['slug']: a for a in json.loads(PLAN.read_text(encoding='utf-8'))['articles']}


def src_id(url):
    m = re.search(r'photo-([0-9]+-[0-9a-f]+)', url)
    return m.group(1) if m else url


def page_id(url):
    return url.rstrip('/').split('/')[-1][-11:]


def fetch(url, width):
    base = url.split('?')[0]
    with urllib.request.urlopen(f'{base}?w={width}&q=85&fm=jpg&fit=max', timeout=60) as r:
        return Image.open(io.BytesIO(r.read())).convert('RGB')


def dhash(im):
    g = im.convert('L').resize((9, 8), Image.LANCZOS)
    px = list(g.tobytes())
    bits = 0
    for row in range(8):
        for col in range(8):
            bits = (bits << 1) | (px[row * 9 + col] > px[row * 9 + col + 1])
    return f'{bits:016x}'


def dist(a, b):
    return bin(int(a, 16) ^ int(b, 16)).count('1')


def crop169(im):
    w, h = im.size
    if w / h > W / H:
        nw = round(h * W / H)
        im = im.crop(((w - nw) // 2, 0, (w - nw) // 2 + nw, h))
    else:
        nh = round(w * H / W)
        im = im.crop((0, (h - nh) // 2, w, (h - nh) // 2 + nh))
    return im.resize((W, H), Image.LANCZOS)


def webp(im):
    for q in (82, 78, 74, 70, 66, 62, 58):
        buf = io.BytesIO()
        im.save(buf, 'WEBP', quality=q, method=6)
        if buf.tell() <= MAX_BYTES:
            return buf.getvalue()
    return buf.getvalue()


def home_hashes():
    return {p.name: dhash(Image.open(p).convert('RGB')) for p in HOME.glob('*.webp')}


def esc(s):
    return s.replace('"', '').replace('[', '(').replace(']', ')').strip()


def read_art(slug):
    f = ART / f'{slug}.html'
    if not f.exists():
        sys.exit(f'no article {slug}')
    return f, f.read_text(encoding='utf-8')


def bump_rev(raw):
    m = re.match(r'(\s*<!--meta\s+)(\{.*?\})(\s*-->)', raw, re.S)
    meta = json.loads(m.group(2))
    if int(meta.get('rev', 1)) < 2:
        meta['rev'] = 2
    return m.group(1) + json.dumps(meta, ensure_ascii=False) + m.group(3) + raw[m.end():]


def cmd_preview(args):
    out = Path(args.pop(args.index('--out') + 1)) if '--out' in args else Path.cwd() / 'photo-candidates.png'
    if '--out' in args:
        args.remove('--out')
    thumbs = []
    for u in args:
        try:
            thumbs.append(fetch(u, 480))
        except Exception as e:  # noqa: BLE001 · a dead candidate just shows as grey
            print('✗', u, e)
            thumbs.append(Image.new('RGB', (480, 270), (60, 60, 60)))
    tw, th, cols = 480, 270, 3
    sheet = Image.new('RGB', (tw * cols, th * ((len(thumbs) + cols - 1) // cols)), (20, 20, 22))
    d = ImageDraw.Draw(sheet)
    for i, im in enumerate(thumbs):
        im = crop169(im).resize((tw - 6, th - 6))
        x, y = (i % cols) * tw, (i // cols) * th
        sheet.paste(im, (x + 3, y + 3))
        d.rectangle((x + 3, y + 3, x + 40, y + 30), fill=(255, 204, 0))
        d.text((x + 10, y + 8), str(i + 1), fill=(0, 0, 0))
    sheet.save(out)
    print(out)


def cmd_add(args):
    slug = args.pop(0)
    opt = {}
    while args:
        k = args.pop(0)
        if not k.startswith('--'):
            sys.exit(f'unexpected {k}')
        opt[k[2:]] = args.pop(0)
    for k in ('src', 'page', 'credit', 'name', 'after', 'alt'):
        if not opt.get(k):
            sys.exit(f'--{k} is required')
    name = opt['name']
    if not re.fullmatch(r'[a-z0-9]+(-[a-z0-9]+)*', name):
        sys.exit('--name must be lower-case English words joined by "-"')
    reg = load_reg()
    rel = f'{slug}/{name}.webp'
    sid = src_id(opt['src'])
    pid = page_id(opt['page'])
    if pid in RESERVED:
        sys.exit(f'photo already used on the {RESERVED[pid]} page')
    for r in reg:
        if r.get('page_id') == pid:
            sys.exit(f'photo already used in {r["slug"]} ({r["file"]})')
        if r['file'] == rel or r['name'] == name:
            sys.exit(f'name already used: {r["file"]}')
        if r['src_id'] == sid:
            sys.exit(f'photo already used in {r["slug"]} ({r["file"]})')
    f, raw = read_art(slug)
    im = crop169(fetch(opt['src'], 1800))
    h = dhash(im)
    for r in reg:
        if dist(h, r['dhash']) <= DUP_BITS:
            sys.exit(f'looks the same as {r["file"]} (dHash distance {dist(h, r["dhash"])})')
    for n, hh in home_hashes().items():
        if dist(h, hh) <= DUP_BITS:
            sys.exit(f'looks the same as the homepage photo {n}')
    # the H2 to sit under, then its first paragraph
    h2 = None
    for m in re.finditer(r'<h2[^>]*>(.*?)</h2>', raw, re.S):
        if opt['after'] in re.sub(r'<[^>]+>', '', m.group(1)):
            h2 = m
            break
    if not h2:
        sys.exit(f'no H2 contains "{opt["after"]}"')
    nxt = re.search(r'<h2[\s>]|<div class="related-links">', raw[h2.end():])
    stop = h2.end() + (nxt.start() if nxt else len(raw) - h2.end())
    if TOKEN.search(raw[h2.end():stop]):
        sys.exit('that section already has a photo')
    if 'faq-block' in raw[h2.end():stop] or 'related-links' in raw[h2.start():h2.end() + 40]:
        sys.exit('no photos in the FAQ or the related box')
    # after the paragraph that opens the section, or right under the H2 when a table or list opens it
    first = re.match(r'\s*<p>.*?</p>', raw[h2.end():stop], re.S)
    at = h2.end() + first.end() if first else h2.end()
    data = webp(im)
    dest = OUT / rel
    dest.parent.mkdir(parents=True, exist_ok=True)
    dest.write_bytes(data)
    cap = esc(opt.get('caption', ''))
    credit = esc(f'ภาพ: {opt["credit"]} / Unsplash')
    token = f'[eabull_photo file="{rel}" alt="{esc(opt["alt"])}" caption="{cap}" w="{W}" h="{H}" credit="{credit}"]'
    raw = raw[:at] + '\n' + token + raw[at:]
    f.write_text(bump_rev(raw), encoding='utf-8', newline='\n')
    mine = [r for r in reg if r['slug'] == slug]
    mine.append({'slug': slug, 'file': rel, 'name': name, 'alt': esc(opt['alt']), 'caption': cap,
                'credit': opt['credit'], 'page': opt['page'], 'src': opt['src'].split('?')[0],
                'src_id': sid, 'page_id': pid, 'dhash': h, 'bytes': len(data), 'license': 'Unsplash License'})
    save_slug(slug, mine)
    print(f'✓ {rel} ({len(data) // 1000} KB) under "{re.sub(r"<[^>]+>", "", h2.group(1)).strip()}"')


def cmd_remove(args):
    slug, name = args[0], args[1]
    rel = f'{slug}/{name}.webp'
    f, raw = read_art(slug)
    raw2 = re.sub(r'\n?\[eabull_photo\s+file="' + re.escape(rel) + r'"[^\]]*\]', '', raw)
    f.write_text(raw2, encoding='utf-8', newline='\n')
    (OUT / rel).unlink(missing_ok=True)
    save_slug(slug, [r for r in load_reg() if r['slug'] == slug and r['file'] != rel])
    print('removed', rel)


def cmd_sheet(args):
    slug = args[0]
    out = Path(args[args.index('--out') + 1]) if '--out' in args else Path.cwd() / f'photos-{slug}.png'
    files = sorted((OUT / slug).glob('*.webp'))
    if not files:
        sys.exit('no photos yet')
    tw, th = 600, 338
    sheet = Image.new('RGB', (tw * 2, th * ((len(files) + 1) // 2)), (20, 20, 22))
    for i, p in enumerate(files):
        sheet.paste(Image.open(p).convert('RGB').resize((tw - 6, th - 6)), ((i % 2) * tw + 3, (i // 2) * th + 3))
    sheet.save(out)
    print(out)


def norm(s):
    return re.sub(r'\s+', '', s).replace('ํา', 'ำ').lower()


def cmd_check(args):
    P = plan()
    reg = load_reg()
    by_file = {r['file']: r for r in reg}
    errors = 0
    files = sorted(ART.glob('*.html'))
    seen_alt, seen_src = {}, {}
    for r in reg:
        seen_src.setdefault(r['src_id'], []).append(r['file'])
    for f in files:
        slug = f.stem
        if args and slug not in args:
            continue
        raw = f.read_text(encoding='utf-8')
        toks = [dict(re.findall(r'(\w+)="([^"]*)"', t.group(1))) for t in TOKEN.finditer(raw)]
        want = 4 if P.get(slug, {}).get('pillar') else 3
        err = []
        if len(toks) != want:
            err.append(f'{len(toks)} photos (want {want})')
        kw = norm(P.get(slug, {}).get('kw', ''))
        if toks and kw and kw not in norm(toks[0].get('alt', '')):
            err.append('first photo alt lacks the focus keyword')
        for t in toks:
            rel, alt = t.get('file', ''), t.get('alt', '')
            if rel not in by_file:
                err.append(f'{rel} not in dev/content/photos/')
            p = OUT / rel
            if not p.exists():
                err.append(f'{rel} missing')
            elif p.stat().st_size > MAX_BYTES + 10_000:
                err.append(f'{rel} {p.stat().st_size // 1000} KB')
            if not 15 <= len(alt) <= 140:
                err.append(f'alt {len(alt)} chars: {alt[:40]}')
            if alt in seen_alt:
                err.append(f'alt repeats {seen_alt[alt]}')
            seen_alt[alt] = rel
            if re.search(r'[–—]', alt + t.get('caption', '')):
                err.append('dash in alt/caption')
        # never inside the FAQ (from the faq-block to the next H2) or the related box
        fq = raw.find('<div class="faq-block">')
        if fq >= 0:
            end = raw.find('<h2', fq)
            if TOKEN.search(raw[fq:end if end > 0 else len(raw)]):
                err.append('photo inside the FAQ')
        rl = raw.find('<div class="related-links">')
        if rl >= 0 and TOKEN.search(raw[rl:]):
            err.append('photo inside related-links')
        if toks and json.loads(re.match(r'\s*<!--meta\s+(\{.*?\})', raw, re.S).group(1)).get('rev', 1) < 2:
            err.append('meta rev must be 2 or more')
        print(('✗ ' if err else '✓ ') + f'{slug:28} {len(toks)}/{want}' + ('' if not err else '  ' + ' · '.join(err)))
        errors += bool(err)
    if not args:
        for sid, fl in seen_src.items():
            if len(fl) > 1:
                print(f'✗ same Unsplash photo twice: {fl}')
                errors += 1
        hh = [(r['file'], r['dhash']) for r in reg] + [(f'home/{n}', h) for n, h in home_hashes().items()]
        for i in range(len(hh)):
            for j in range(i + 1, len(hh)):
                if dist(hh[i][1], hh[j][1]) <= DUP_BITS:
                    print(f'✗ look alike: {hh[i][0]} · {hh[j][0]} (distance {dist(hh[i][1], hh[j][1])})')
                    errors += 1
        on_disk = {str(p.relative_to(OUT)).replace('\\', '/') for p in OUT.glob('*/*.webp')}
        for extra in sorted(on_disk - set(by_file)):
            print(f'✗ file not in dev/content/photos/: {extra}')
            errors += 1
        print(f'\n{len(reg)} photos registered · {errors} problem(s)')
    sys.exit(1 if errors else 0)


def cmd_used(args):
    for r in load_reg():
        print(r['src_id'], r['file'])


def main():
    if len(sys.argv) < 2:
        sys.exit(__doc__)
    cmd, args = sys.argv[1], sys.argv[2:]
    {'preview': cmd_preview, 'add': cmd_add, 'remove': cmd_remove, 'sheet': cmd_sheet,
     'check': cmd_check, 'used': cmd_used}.get(cmd, lambda a: sys.exit(__doc__))(args)


if __name__ == '__main__':
    main()
