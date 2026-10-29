"""SEO audit of every live eabull.com page: head tags, headings, images, links, schema, sitemap.

Pages come from the Yoast sitemap plus the pages that are meant to be noindex (/go/, /articles/,
the sample products), so both "should be indexed" and "should not be" are checked.
Prints one block per page with problems, then site-wide checks (duplicate titles/descriptions,
images, links, sitemap vs robots). Exit code 1 when anything is flagged.
Usage: py -3 -I dev/tools/live-seo-audit.py
"""
import html
import json
import re
import sys
import time
import urllib.error
import urllib.request
from collections import defaultdict
from urllib.parse import urljoin, urlparse

sys.stdout.reconfigure(encoding='utf-8')
SITE = 'https://eabull.com/'
HOST = 'eabull.com'
UA = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130 Safari/537.36'
NOINDEX_EXPECTED = ('/go/', '/articles/', '/product-1/', '/product-2/', '/product-3/')
EXTRA = ['/go/', '/articles/', '/product-1/', '/product-2/', '/product-3/', '/no-such-page-seo-check/', '/?s=MT5']
BROKERS = ('zaurix.com',)


class NoRedirect(urllib.request.HTTPRedirectHandler):
    def redirect_request(self, *a, **k):
        return None


OPENER = urllib.request.build_opener(NoRedirect)


def fetch(url, body=True, buster=True):
    if buster and internal(url):
        url += ('&' if '?' in url else '?') + 'seo=' + str(int(time.time()))
    req = urllib.request.Request(url, headers={'User-Agent': UA}, method='GET' if body else 'HEAD')
    try:
        with OPENER.open(req, timeout=30) as r:
            data = r.read() if body else b''
            return r.status, dict(r.headers), data
    except urllib.error.HTTPError as e:
        return e.code, dict(e.headers), b''
    except Exception as e:  # noqa: BLE001
        return 0, {}, str(e).encode()


def internal(url):
    return urlparse(url).netloc in ('', HOST, 'www.' + HOST)


def text_of(s):
    return re.sub(r'\s+', ' ', html.unescape(re.sub(r'<[^>]+>', ' ', s))).strip()


def attr(tag, name):
    m = re.search(r'\s' + name + r'\s*=\s*(["\'])(.*?)\1', tag, re.S | re.I)
    return html.unescape(m.group(2)) if m else None


def meta(head, key, value):
    for tag in re.findall(r'<meta\b[^>]*>', head, re.I):
        if (attr(tag, key) or '').lower() == value.lower():
            return attr(tag, 'content')
    return None


def main():
    status, _, smi = fetch(SITE + 'sitemap_index.xml')
    sitemap = []
    for m in re.findall(r'<loc>([^<]+)</loc>', smi.decode('utf-8', 'replace')):
        sitemap += re.findall(r'<loc>([^<]+)</loc>', fetch(m)[2].decode('utf-8', 'replace'))
    paths = [urlparse(u).path or '/' for u in sitemap] + EXTRA
    paths = list(dict.fromkeys(paths))
    print(f'== sitemap: {len(sitemap)} URL · pages checked: {len(paths)}')

    titles, descs = defaultdict(list), defaultdict(list)
    images, links_int, links_ext = {}, defaultdict(set), {}
    flagged = 0
    for path in paths:
        url = urljoin(SITE, path)
        st, hdr, raw = fetch(url)
        page = raw.decode('utf-8', 'replace')
        issues = []
        expect_404 = 'no-such-page' in path
        if expect_404:
            if st != 404:
                issues.append(f'404 page answers {st}')
        elif st != 200:
            issues.append(f'status {st}')
            print(f'\n-- {path}\n   ' + '\n   '.join(issues)); flagged += 1
            continue
        head = page.split('</head>')[0]
        body = page.split('<body', 1)[-1]
        title = text_of((re.search(r'<title>(.*?)</title>', head, re.S) or [None, ''])[1])
        desc = meta(head, 'name', 'description') or ''
        robots = (meta(head, 'name', 'robots') or '').lower()
        canon = attr((re.search(r'<link[^>]+rel=["\']canonical["\'][^>]*>', head, re.I) or [''])[0], 'href') if re.search(r'rel=["\']canonical', head) else None
        noindex = 'noindex' in robots
        want_noindex = path.startswith(NOINDEX_EXPECTED) or path.startswith('/?s=') or expect_404
        if noindex != want_noindex:
            issues.append(f'robots "{robots}" but expected {"noindex" if want_noindex else "index"}')
        if not want_noindex:
            titles[title].append(path)
            descs[desc].append(path)
            if not title:
                issues.append('no <title>')
            elif len(title) > 65:
                issues.append(f'title {len(title)} chars (long): {title}')
            if not desc:
                issues.append('no meta description')
            elif not 50 <= len(desc) <= 165:
                issues.append(f'meta description {len(desc)} chars')
            if canon != url:
                issues.append(f'canonical {canon} (page {url})')
            for key in ('og:title', 'og:description', 'og:url', 'og:image'):
                if not meta(head, 'property', key):
                    issues.append(f'no {key}')
            if not meta(head, 'name', 'twitter:card'):
                issues.append('no twitter:card')
            og_img = meta(head, 'property', 'og:image')
            if og_img:
                images.setdefault(og_img, set()).add(path + ' (og)')
            # schema
            blocks = re.findall(r'<script[^>]+application/ld\+json[^>]*>(.*?)</script>', page, re.S)
            types = []
            for b in blocks:
                try:
                    data = json.loads(b)
                except ValueError:
                    issues.append('schema JSON does not parse'); continue
                graph = data.get('@graph', [data]) if isinstance(data, dict) else data
                for node in graph if isinstance(graph, list) else [graph]:
                    t = node.get('@type') if isinstance(node, dict) else None
                    types += t if isinstance(t, list) else [t]
            if not types:
                issues.append('no JSON-LD schema')
            # headings
            hs = [(int(h[0]), text_of(h[1])) for h in re.findall(r'<h([1-6])\b[^>]*>(.*?)</h\1>', body, re.S | re.I)]
            h1 = [h for h in hs if h[0] == 1]
            if len(h1) != 1:
                issues.append(f'{len(h1)} H1')
            prev = 1
            for lvl, txt in hs:
                if lvl > prev + 1:
                    issues.append(f'heading jump h{prev} → h{lvl}: "{txt[:40]}"')
                prev = lvl
            words = len(re.findall(r'[฀-๿]', text_of(re.sub(r'(?is)<(script|style|nav|footer|header)\b.*?</\1>', ' ', body))))
            if words < 1500:
                issues.append(f'thin content: {words} Thai chars in main text')
        if '<html' in page and not re.search(r'<html[^>]+lang=["\']th', page):
            issues.append('html lang is not th')
        # images
        for tag in re.findall(r'<img\b[^>]*>', body, re.I):
            src = attr(tag, 'src') or ''
            if not src or src.startswith('data:'):
                continue
            full = urljoin(url, src)
            images.setdefault(full, set()).add(path)
            alt = attr(tag, 'alt')
            decorative = 'aria-hidden' in tag or (alt == '' and re.search(r'role=["\']presentation', tag))
            if alt is None:
                issues.append(f'img without alt: {src[-50:]}')
            elif alt == '' and not decorative and 'hd__emblem' not in tag and 'go-word' not in tag:
                pass  # empty alt is fine for decorative images (emblem next to the brand word, icons)
            if not attr(tag, 'width') or not attr(tag, 'height'):
                issues.append(f'img without width/height: {src[-50:]}')
        # links
        for tag in re.findall(r'<a\b[^>]*>', body, re.I):
            href = attr(tag, 'href')
            if href is None or href.startswith(('mailto:', 'tel:', 'javascript:')):
                continue
            if href in ('', '#'):
                if 'data-wait' not in tag and 'data-consent' not in tag:
                    issues.append(f'link with empty href: {tag[:80]}')
                continue
            if href.startswith('#'):
                continue
            full = urljoin(url, href)
            if full.startswith('http://'):
                issues.append(f'http (not https) link: {full}')
            if internal(full):
                links_int[full.split('#')[0]].add(path)
                if 'nofollow' in (attr(tag, 'rel') or ''):
                    issues.append(f'internal link with nofollow: {full}')
            else:
                rel = attr(tag, 'rel') or ''
                links_ext.setdefault(full, (rel, path))
                if attr(tag, 'target') == '_blank' and 'noopener' not in rel:
                    issues.append(f'external _blank without noopener: {full}')
                if any(b in full for b in BROKERS) and 'sponsored' not in rel:
                    issues.append(f'broker link without sponsored: {full}')
        if issues:
            flagged += 1
            print(f'\n-- {path}  ({title[:50]})')
            for i in dict.fromkeys(issues):
                print('   ' + i)

    print('\n== duplicate titles / descriptions (indexable pages)')
    for t, ps in titles.items():
        if len(ps) > 1:
            print(f'   title x{len(ps)}: {t} -> {ps}'); flagged += 1
    for d, ps in descs.items():
        if len(ps) > 1 and d:
            print(f'   description x{len(ps)}: {d[:60]} -> {ps}'); flagged += 1

    print(f'\n== images: {len(images)} unique')
    for src, pages in sorted(images.items()):
        st, hdr, data = fetch(src, body=True, buster=False)
        size = len(data)
        ctype = hdr.get('Content-Type', hdr.get('content-type', ''))
        name = urlparse(src).path.rsplit('/', 1)[-1]
        problems = []
        if st != 200:
            problems.append(f'status {st}')
        if size > 300_000:
            problems.append(f'{size // 1024} KB (heavy)')
        if re.search(r'^(IMG|DSC|image|screenshot)[-_ ]?\d', name, re.I) or ' ' in name:
            problems.append('file name not descriptive')
        if problems:
            flagged += 1
            print(f'   {name}: {", ".join(problems)}  [{ctype}] on {sorted(pages)[:3]}')
    print('   (only problems listed)')

    print(f'\n== internal links: {len(links_int)} unique')
    for u in sorted(links_int):
        st, hdr, _ = fetch(u, body=False)
        if st in (403, 405):
            st, hdr, _ = fetch(u)
        if st != 200:
            loc = hdr.get('Location', hdr.get('location', ''))
            flagged += 1
            print(f'   {st} {u} -> {loc}  (from {sorted(links_int[u])[:3]})')
    print('   (only non-200 listed)')

    print(f'\n== external links: {len(links_ext)} unique')
    for u, (rel, page) in sorted(links_ext.items()):
        st, hdr, _ = fetch(u, body=False, buster=False)
        if st in (0, 403, 405, 400):
            st, hdr, _ = fetch(u, buster=False)
        loc = hdr.get('Location', hdr.get('location', ''))
        print(f'   {st} {u} rel="{rel}"' + (f' -> {loc}' if loc else '') + f'  (on {page})')

    print(f'\n== sitemap vs pages: every sitemap URL answers 200 and is indexable')
    for u in sitemap:
        st, _, raw = fetch(u)
        r = (meta(raw.decode('utf-8', 'replace').split('</head>')[0], 'name', 'robots') or '')
        if st != 200 or 'noindex' in r:
            flagged += 1
            print(f'   {st} {u} robots="{r}"')
    print(f'\n{"PROBLEMS: " + str(flagged) if flagged else "no problems"}')
    sys.exit(1 if flagged else 0)


if __name__ == '__main__':
    main()
