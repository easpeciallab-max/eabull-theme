"""Compare the visible Thai text of live eabull.com pages with live eawing.co pages.

Usage: py -3 -I dev/tools/live-dup.py   (edit BAR to 0.5 for a stricter look)
Same rule as dev/tools/check-dup.py: brand names blanked, 12-char shingles,
chunk flagged when >= 70% of its shingles appear anywhere on eawing.co and it
has >= 30 Thai characters. Read only on both sites.
"""
import html
import re
import sys
import time
import urllib.request

sys.stdout.reconfigure(encoding='utf-8')
K, MIN_THAI, BAR = 12, 30, 0.7
THAI = re.compile(r'[฀-๿]')
BRANDS = re.compile(r'EA ?BULL|EA ?WING|WING (?:NITRO|TWIN)|eabull|eawing|Zaurix', re.I)
UA = {'User-Agent': 'Mozilla/5.0 (dup-check)'}


def get(url):
    try:
        # cache buster: Cloudflare keeps pages up to 5 minutes (s-maxage=300)
        url += ('&' if '?' in url else '?') + 'nocache=' + str(int(time.time()))
        with urllib.request.urlopen(urllib.request.Request(url, headers=UA), timeout=40) as r:
            return r.read().decode('utf-8', 'replace')
    except Exception as e:  # noqa: BLE001
        print('  ! fetch failed', url, e)
        return ''


def sitemap_pages(base):
    urls = []
    idx = get(base + '/sitemap_index.xml')
    maps = re.findall(r'<loc>([^<]+)</loc>', idx) or [base + '/page-sitemap.xml']
    for m in maps:
        if 'page-sitemap' in m or 'post-sitemap' in m:
            urls += re.findall(r'<loc>([^<]+)</loc>', get(m))
    return [u for u in dict.fromkeys(urls) if not u.endswith(('.jpg', '.png', '.webp'))]


def chunks(page):
    page = re.sub(r'(?is)<(script|style|noscript|svg|head)\b.*?</\1>', ' ', page)
    for seg in re.split(r'<[^>]+>', page):
        seg = BRANDS.sub('#', html.unescape(seg))
        seg = re.sub(r'\s+', ' ', seg).strip()
        if len(THAI.findall(seg)) >= MIN_THAI:
            yield seg


def shingles(s):
    return {s[i:i + K] for i in range(max(1, len(s) - K + 1))}


def main():
    wing_urls = sitemap_pages('https://eawing.co')
    bull_urls = sitemap_pages('https://eabull.com')
    # eabull.com hides itself from search engines for now, so the sitemap may be off: use the known slugs
    slugs = ['', 'about', 'backtest', 'forward-test', 'how-to-install', 'open-mt5-account', 'deposit', 'mt5-login',
             'vps-windows', 'vps-android', 'vps-ios', 'tools', 'pricing', 'product-1', 'product-2', 'product-3',
             'risk-disclosure', 'privacy-policy', 'terms-of-use', 'data-deletion', 'go', 'articles']
    bull_urls = list(dict.fromkeys([f'https://eabull.com/{s}{"/" if s else ""}' for s in slugs]
                                   + [u for u in bull_urls if 'eabull.com' in u]))
    # old EA Special pages still waiting for the trash are not ours
    bull_urls = [u for u in bull_urls if not re.search(r'/(results|guides|risk-warning|ea-products)/', u)]
    print(f'eawing.co pages: {len(wing_urls)} · eabull.com pages: {len(bull_urls)}')
    theirs = set()
    for u in wing_urls:
        for seg in chunks(get(u)):
            theirs |= shingles(seg)
    total, hits = 0, []
    for u in bull_urls:
        n = 0
        for seg in chunks(get(u)):
            total += 1
            n += 1
            sh = shingles(seg)
            share = len(sh & theirs) / len(sh)
            if share >= BAR:
                hits.append((share, u, seg[:100]))
        print(f'  {n:4d} chunks  {u}')
    for share, u, seg in hits:
        print(f'{share:4.0%}  {u}  {seg}')
    print(f'{len(hits)} of {total} Thai chunks repeat eawing.co text' if hits else f'no duplicates in {total} Thai chunks')


if __name__ == '__main__':
    main()
