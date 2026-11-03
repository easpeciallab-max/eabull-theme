"""Check every article in eabull/inc/content/articles against the plan (dev/content/plan.json).

The plan (docs/content-plan.md, built by dev/tools/content-plan.py) fixes each article's
slug, title, focus keyword, secondary and long-tail keywords, cluster, pillar, bridges,
money page and publish date. This script checks that the written file follows it and
the SEO and link rules of dev/content-spec.md:

  meta      title/keyword/category/date as planned · seo_title ≤ 60, starts with the keyword,
            ends " · EA BULL" · description 120 to 155 chars with the keyword · excerpt
  opening   <p class="lead"> first with the keyword · "สรุปสั้น" info callout before the first H2
  keywords  focus keyword 3+ times (≤ 2.5% of the text) and in an H2 · every secondary/long-tail phrase at least once
            (spaces and case ignored · phrases with "pantip" are optional)
  size      Thai text without spaces: pillar ≥ 12,000 · supporting ≥ 5,500 · H2 and FAQ counts
  links     pillar before the first H2 · 2 to 4 siblings (pillar: all 11) · every planned bridge
            · money page in the second half · one [eabull_line pos="<slug>"] after it · related-links
            3 to 4 items at the end (one live on publish day) · 1 to 3 external links to primary sources only · no /go/ or product-N
  words     forbidden sales words outside a warning sentence · % per month/year · no warn callouts
            · no [eabull_ea] / [eabull_figure] · no "คลิกที่นี่"

Across the set it reports anchor texts reused in more than 3 articles and how many articles
link the brand name to the homepage (plan: about 1 in 3).

Usage: py -3 -I dev/tools/check-articles.py [slug ...] [--review]
  --review also prints sentences that name EA BULL or a % figure, for a human read.
Exit code 1 when any article has an error. Missing planned files are listed, not errors.
"""
import json
import re
import sys
from collections import defaultdict
from pathlib import Path

sys.stdout.reconfigure(encoding='utf-8')

ROOT = Path(__file__).resolve().parents[2]
ART = ROOT / 'eabull' / 'inc' / 'content' / 'articles'
PLAN = ROOT / 'dev' / 'content' / 'plan.json'
PAGES = {'how-to-install', 'backtest', 'forward-test', 'pricing', 'open-mt5-account', 'deposit', 'mt5-login',
         'vps-windows', 'vps-android', 'vps-ios', 'tools', 'risk-disclosure', 'about', 'articles'}
# primary sources only (docs/content-plan.md section 5) · zaurix links are partner links, counted apart
ALLOWED = ('metatrader5.com', 'mql5.com', 'microsoft.com', 'sec.or.th', 'thaipoliceonline.go.th', 'bot.or.th',
           'cmegroup.com', 'gold.org', 'lbma.org.uk', 'federalreserve.gov', 'treasury.gov', 'cftc.gov',
           'bls.gov', 'imf.org', 'bis.org', 'etda.or.th', 'thaicert.or.th', 'spdrgoldshares.com', 'ssga.com',
           'goldtraders.or.th', 'set.or.th', 'tfex.co.th', 'ecb.europa.eu', 'bea.gov', 'dol.gov', 'mdes.go.th')
FORBIDDEN = ('รับประกัน', 'การันตี', 'กำไรแน่นอน', 'ไม่มีขาดทุน', 'ปลอดภัย 100%', 'ปลอดภัย100%', 'รวยเร็ว')
WARN_CTX = re.compile(r'ถ้า|หาก|อย่า|ระวัง|อ้าง|โฆษณา|หลอก|มิจฉา|ไม่มีใคร|ไม่มีระบบ|ไม่มี EA|ไม่มีบอท|สัญญาว่า|คำว่า|ชวนเชื่อ|เกินจริง|ไม่ควร|ไม่ได้|ไม่สามารถ|ไม่มีทาง|ปลอม|ไม่จริง')
PCT_TIME = re.compile(r'\d[\d.,]*\s*%\s*(?:ต่อ|/)\s*(?:เดือน|ปี|สัปดาห์|วัน)|(?:เดือนละ|ปีละ|วันละ|สัปดาห์ละ)\s*\d[\d.,]*\s*%')


def norm(s):
    # Autocomplete phrases sometimes type sara am as nikhahit + sara aa (\u0e4d\u0e32): treat it as \u0e33
    return re.sub(r'\s+', '', s).replace('\u0e4d\u0e32', '\u0e33').lower()


def text_of(html):
    html = re.sub(r'\[/?eabull_[a-z]+[^\]]*\]', ' ', html)
    return re.sub(r'\s+', ' ', re.sub(r'<[^>]+>', ' ', html)).strip()


def nospace_len(html):
    return len(re.sub(r'\s+', '', text_of(html)))


def links(html):
    """[(href, anchor_text, offset)]"""
    out = []
    for m in re.finditer(r'<a\s[^>]*href="([^"]+)"[^>]*>(.*?)</a>', html, re.S | re.I):
        out.append((m.group(1), text_of(m.group(2)), m.start()))
    return out


def slug_of(href):
    m = re.match(r'\{\{home\}\}/([a-z0-9-]*)/?(?:#.*)?$', href)
    return None if not m else (m.group(1) or 'home')


def sentence_at(text, i, span=90):
    a = max(0, i - span)
    return text[a:i + span]


def main():
    args = [a for a in sys.argv[1:] if not a.startswith('--')]
    review = '--review' in sys.argv
    plan = json.loads(PLAN.read_text(encoding='utf-8'))
    clusters = plan['clusters']
    arts = {a['slug']: a for a in plan['articles']}
    by_cluster = defaultdict(list)
    for a in plan['articles']:
        by_cluster[a['cluster']].append(a['slug'])
    pillar_of = {c: next(s for s in by_cluster[c] if arts[s]['pillar']) for c in by_cluster}

    files = sorted(ART.glob('*.html'))
    errors = 0
    anchors = defaultdict(set)
    brand_home = []
    seen = set()
    for f in files:
        slug = f.stem
        if args and slug not in args:
            continue
        seen.add(slug)
        err, warn = [], []
        raw = f.read_text(encoding='utf-8')
        p = arts.get(slug)
        if not p:
            print(f'✗ {slug}: not in the plan')
            errors += 1
            continue
        m = re.match(r'^\s*<!--meta\s+(\{.*?\})\s*-->', raw, re.S)
        meta = json.loads(m.group(1)) if m else {}
        body = raw[m.end():] if m else raw
        kw = p['kw']
        nkw = norm(kw)

        # meta
        cname = clusters[p['cluster']]['name']
        for k, want in (('title', p['title']), ('keyword', kw), ('category', cname), ('date', p['date'])):
            if meta.get(k) != want:
                err.append(f'meta.{k} = {meta.get(k)!r}, plan {want!r}')
        st = meta.get('seo_title', '')
        if len(st) > 60:
            err.append(f'seo_title {len(st)} chars (> 60)')
        if not st.endswith(' · EA BULL'):
            err.append('seo_title must end with " · EA BULL"')
        if norm(st).find(nkw) != 0:
            err.append('seo_title must start with the focus keyword')
        ds = meta.get('description', '')
        if not 120 <= len(ds) <= 155:
            err.append(f'description {len(ds)} chars (120 to 155)')
        if nkw not in norm(ds):
            err.append('description lacks the focus keyword')
        if not meta.get('excerpt'):
            err.append('excerpt empty')

        # split the related-links box off: it does not count as body text
        rel = re.search(r'<div class="related-links">(.*?)</div>\s*$', body, re.S)
        main_html = body[:rel.start()] if rel else body
        if not rel:
            err.append('related-links box missing at the end')
        else:
            items = re.findall(r'<li>', rel.group(1))
            if not 3 <= len(items) <= 4:
                err.append(f'related-links has {len(items)} items (3 to 4)')
            live = 0
            for href, _, _ in links(rel.group(1)):
                t = slug_of(href)
                if not t:
                    err.append(f'related-links item is not internal: {href}')
                elif t in PAGES or t == 'home' or (t in arts and arts[t]['date'] < p['date']):
                    live += 1
            # items whose article is not published yet are hidden by the theme on the day this one goes live
            if not live:
                err.append('related-links: no page or earlier article, so the box is empty on publish day')

        # opening
        if not re.match(r'\s*<p class="lead">', main_html):
            err.append('body must start with <p class="lead">')
        lead = re.match(r'\s*<p class="lead">(.*?)</p>', main_html, re.S)
        if lead and nkw not in norm(text_of(lead.group(1))):
            err.append('lead paragraph lacks the focus keyword')
        first_h2 = main_html.find('<h2')
        head = main_html[:first_h2] if first_h2 > 0 else main_html
        if 'callout callout--info' not in head or 'สรุปสั้น' not in head:
            err.append('"สรุปสั้น" callout--info missing before the first H2')

        # keywords
        txt = text_of(main_html)
        ntxt = norm(txt)
        count = ntxt.count(nkw)
        density = count * len(nkw) / max(1, len(ntxt))
        if count < 3:
            err.append(f'focus keyword {count}x (min 3)')
        elif density > 0.025:
            warn.append(f'focus keyword {count}x = {density:.1%} of the text (looks stuffed, > 2.5%)')
        h2s = re.findall(r'<h2[^>]*>(.*?)</h2>', main_html, re.S)
        if not any(nkw in norm(text_of(h)) for h in h2s):
            err.append('no H2 contains the focus keyword')
        missing = [k for k in p['kw2'] + p['longtail'] if 'pantip' not in k and norm(k) not in ntxt]
        if missing:
            err.append('missing keyword phrases: ' + ' | '.join(missing))

        # size
        n = nospace_len(main_html)
        lo, hi = (12000, 22000) if p['pillar'] else (5500, 12000)
        if n < lo:
            err.append(f'text {n} chars (min {lo})')
        elif n > hi:
            warn.append(f'text {n} chars (long, > {hi})')
        h2lo, h2hi = (10, 14) if p['pillar'] else (6, 12)
        if not h2lo <= len(h2s) <= h2hi:
            err.append(f'{len(h2s)} H2 ({h2lo} to {h2hi})')
        faq = main_html.count('class="faq-item"')
        flo, fhi = (6, 8) if p['pillar'] else (4, 6)
        if not flo <= faq <= fhi:
            err.append(f'{faq} FAQ items ({flo} to {fhi})')
        if 'data-table' not in main_html and 'guide-steps' not in main_html:
            err.append('needs a data-table or guide-steps')

        # links
        L = links(main_html)
        internal = [(slug_of(h), t, o) for h, t, o in L if slug_of(h)]
        targets = {s for s, _, _ in internal}
        for s, t, _ in internal:
            anchors[t].add(slug)
            if s == 'go' or re.match(r'product-\d', s or ''):
                err.append(f'links /{s}/')
            if s not in arts and s not in PAGES and s != 'home':
                err.append(f'link to unknown /{s}/')
            if s == slug:
                err.append('links to itself')
            if 'คลิกที่นี่' in t:
                err.append('anchor "คลิกที่นี่"')
        pil = pillar_of[p['cluster']]
        sibs = [s for s in by_cluster[p['cluster']] if s != slug and s != pil]
        linked_sibs = targets & set(sibs)
        if p['pillar']:
            miss = set(sibs) - targets
            if miss:
                err.append('pillar does not link: ' + ', '.join(sorted(miss)))
        else:
            if not any(s == pil and o < first_h2 for s, _, o in internal):
                err.append(f'pillar /{pil}/ not linked before the first H2')
            if not 2 <= len(linked_sibs) <= 4:
                err.append(f'{len(linked_sibs)} sibling links (2 to 4)')
        for b in p['bridges']:
            if b not in targets:
                err.append(f'bridge /{b}/ not linked')
        money = p['money']
        mpos = [o for s, _, o in internal if s == money]
        if not mpos:
            err.append(f'money page /{money}/ not linked')
        elif max(mpos) < len(main_html) * 0.5:
            err.append(f'money page /{money}/ only in the first half')
        lines = re.findall(r'\[eabull_line pos="([^"]*)"\]', main_html)
        if lines != [slug]:
            err.append(f'needs exactly one [eabull_line pos="{slug}"] (found {lines})')
        elif mpos and main_html.find('[eabull_line') < max(mpos):
            warn.append('LINE button comes before the money page link')
        if sum(1 for s, _, _ in internal if s == 'risk-disclosure') > 1:
            err.append('risk-disclosure linked more than once')
        ext = [h for h, _, _ in L if h.startswith('http')]
        ext_src = [h for h in ext if 'zaurix' not in h]
        if not 1 <= len(set(ext_src)) <= 3:
            err.append(f'{len(set(ext_src))} external source links (1 to 3)')
        for h in ext_src:
            host = re.sub(r'^https?://', '', h).split('/')[0].lower()
            if not any(host == d or host.endswith('.' + d) for d in ALLOWED):
                err.append(f'external link not a listed primary source: {host}')
        if any('[eabull_brand]' in a or 'EA BULL' in a for h, a, _ in [(h, t, o) for h, t, o in L if slug_of(h) == 'home']):
            brand_home.append(slug)
        if re.search(r'zaurix|\[eabull_broker', raw, re.I):
            i = re.search(r'zaurix|\[eabull_broker', txt + ' ' + raw, re.I).start()
            if 'พันธมิตร' not in sentence_at(txt, i, 160):
                err.append('first Zaurix mention must say it is EA BULL\'s partner broker')

        # words
        for w in FORBIDDEN:
            for mm in re.finditer(re.escape(w), txt):
                ctx = sentence_at(txt, mm.start(), 70)
                if not WARN_CTX.search(ctx):
                    err.append(f'forbidden "{w}": …{ctx}…')
        for mm in PCT_TIME.finditer(txt):
            warn.append(f'% per period (check it is not a return claim): …{sentence_at(txt, mm.start(), 60)}…')
        if 'callout--warn' in raw:
            err.append('callout--warn is not used in articles')
        if '[eabull_ea' in raw or '[eabull_figure' in raw:
            err.append('[eabull_ea] / [eabull_figure] not used in articles')

        if review:
            for mm in re.finditer(r'EA BULL|\[eabull_brand\]', main_html):
                warn.append('brand: …' + sentence_at(text_of(main_html[max(0, mm.start() - 400):mm.start() + 400]), 0, 400)[:300] + '…')

        status = '✗' if err else ('!' if warn else '✓')
        tag = 'P' if p['pillar'] else ' '
        print(f'{status} {tag} {slug:28} {n:6} chars  {len(h2s):2} H2  {faq} FAQ  kw {count}')
        for e in err:
            print('    ✗ ' + e)
        for w in warn:
            print('    ! ' + w)
        errors += bool(err)

    # across the set
    if not args:
        missing = [s for s in arts if s not in seen]
        if missing:
            print(f'\nnot written yet ({len(missing)}): ' + ' '.join(missing))
        reused = {t: s for t, s in anchors.items() if len(s) > 3 and len(t) > 3 and t != 'EA BULL'}  # the brand link is counted below
        for t, s in sorted(reused.items(), key=lambda x: -len(x[1])):
            print(f'! anchor "{t}" in {len(s)} articles: ' + ' '.join(sorted(s)))
        if seen:
            print(f'brand → home links: {len(brand_home)} of {len(seen)} articles')
    print(f'\n{len(seen)} checked · {errors} with errors')
    sys.exit(1 if errors else 0)


if __name__ == '__main__':
    main()
