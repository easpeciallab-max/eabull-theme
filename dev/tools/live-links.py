"""Crawl the live eabull.com and check every link, image and in-page anchor.

Starts at the home page and the Yoast sitemap, follows internal links, then reports:
  - internal URLs that do not answer 200 (404, redirect chains, server errors)
  - external links that fail, and external links without noopener / broker links without sponsored
  - "#id" anchors whose target id is missing on the page
  - pages in the sitemap that no other page links to (orphans), and how many pages link to each page
Usage: py -3 -I dev/tools/live-links.py
"""
import re
import sys
import urllib.error
import urllib.request
from collections import defaultdict
from html import unescape
from urllib.parse import urldefrag, urljoin, urlparse

sys.stdout.reconfigure(encoding='utf-8')
SITE = 'https://eabull.com/'
HOST = 'eabull.com'
UA = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130 Safari/537.36'
BROKERS = ('zaurix.com',)
SKIP_PATH = ('/wp-admin', '/wp-login', '/wp-json', '/xmlrpc', '/feed', '/comments/feed', '/cdn-cgi/')


class NoRedirect(urllib.request.HTTPRedirectHandler):
    def redirect_request(self, *a, **k):
        return None


OPENER = urllib.request.build_opener(NoRedirect)


def fetch(url, body=True):
    """(status, location, text) without following redirects."""
    req = urllib.request.Request(url, headers={'User-Agent': UA, 'Accept-Language': 'th,en'},
                                 method='GET' if body else 'HEAD')
    try:
        with OPENER.open(req, timeout=30) as r:
            return r.status, r.headers.get('Location'), r.read().decode('utf-8', 'replace') if body else ''
    except urllib.error.HTTPError as e:
        return e.code, e.headers.get('Location'), ''
    except Exception as e:  # noqa: BLE001
        return 0, None, str(e)


def follow(url, limit=5):
    """Follow redirects by hand: returns (final status, chain)."""
    chain = []
    for _ in range(limit):
        st, loc, _ = fetch(url, body=False)
        if st in (403, 405, 0):  # some servers refuse HEAD
            st, loc, _ = fetch(url)
        chain.append((st, url))
        if st in (301, 302, 303, 307, 308) and loc:
            url = urljoin(url, loc)
            continue
        return st, chain
    return st, chain


def internal(url):
    return urlparse(url).netloc in (HOST, 'www.' + HOST)


def norm(url):
    u, _ = urldefrag(url)
    p = urlparse(u)
    return f'{p.scheme}://{p.netloc}{p.path or "/"}' + (f'?{p.query}' if p.query else '')


def main():
    pages, queue, seen = {}, [SITE], set()
    st, _, smi = fetch(SITE + 'sitemap_index.xml')
    sitemap = []
    for m in re.findall(r'<loc>([^<]+)</loc>', smi):
        sitemap += re.findall(r'<loc>([^<]+)</loc>', fetch(m)[2])
    sitemap = [u for u in sitemap if not re.search(r'\.(jpe?g|png|webp|gif)$', u)]
    queue += sitemap
    links_to = defaultdict(set)      # target page -> pages that link to it
    externals = {}                   # url -> (rel, first page)
    anchors_bad, rel_bad, waits = [], [], 0
    resources = set()

    while queue:
        url = norm(queue.pop(0))
        if url in seen or not internal(url) or url.startswith(tuple(SITE.rstrip('/') + s for s in SKIP_PATH)):
            continue
        if re.search(r'\.(jpe?g|png|webp|gif|svg|pdf|zip|ex5|woff2?|css|js)(\?|$)', url):
            continue
        seen.add(url)
        st, loc, html = fetch(url)
        pages[url] = st
        if st != 200:
            continue
        html_nohead = re.sub(r'(?is)<head\b.*?</head>', '', html)
        ids = set(re.findall(r'\sid="([^"]+)"', html_nohead))
        for tag in re.findall(r'<a\b[^>]*>', html_nohead):
            href = re.search(r'href="([^"]*)"', tag)
            if not href:
                continue
            href = unescape(href.group(1)).strip()
            if 'data-wait' in tag:
                waits += 1
                continue
            if href.startswith(('mailto:', 'tel:', 'javascript:')) or href == '#':
                continue
            if href.startswith('#'):
                if href[1:] not in ids:
                    anchors_bad.append((url, href))
                continue
            full = urljoin(url, href)
            base, frag = urldefrag(full)
            if internal(full):
                n = norm(base)
                if frag and n == norm(url) and frag not in ids:
                    anchors_bad.append((url, href))
                if not re.search(r'\.(jpe?g|png|webp|gif|svg|pdf|zip|ex5)(\?|$)', n):
                    if n != norm(url):
                        links_to[n].add(url)
                    if n not in seen:
                        queue.append(n)
                else:
                    resources.add(n)
            elif full.startswith('http'):
                rel = (re.search(r'rel="([^"]*)"', tag) or [None, ''])[1]
                externals.setdefault(full, (rel, url))
                if 'target="_blank"' in tag and 'noopener' not in rel:
                    rel_bad.append((url, full, 'no noopener'))
                if any(b in full for b in BROKERS) and 'sponsored' not in rel:
                    rel_bad.append((url, full, 'broker link without sponsored'))
        for src in re.findall(r'<img\b[^>]*\ssrc="([^"]+)"', html_nohead):
            resources.add(norm(urljoin(url, unescape(src))))

    print(f'== หน้าในเว็บที่ตรวจ: {len(pages)} หน้า (sitemap {len(sitemap)} URL)')
    for u, st in sorted(pages.items()):
        if st != 200:
            fst, chain = follow(u)
            print(f'  {st}  {u}  ->  ' + ' -> '.join(f'{s} {c}' for s, c in chain[1:]) if len(chain) > 1 else f'  {st}  {u}')
    print('== ไฟล์ภาพ/ไฟล์แนบในเว็บ ที่เปิดไม่ได้')
    bad_res = [(u, follow(u)[0]) for u in sorted(resources)]
    for u, st in bad_res:
        if st != 200:
            print(f'  {st}  {u}')
    print(f'  ตรวจ {len(bad_res)} ไฟล์')
    print('== ลิงก์ภายนอก')
    for u, (rel, page) in sorted(externals.items()):
        st, chain = follow(u)
        mark = 'ok ' if st == 200 else 'CHK'
        print(f'  {mark} {st}  {u}  rel="{rel}"  (เจอที่ {page.replace(SITE, "/")})')
    print('== rel ไม่ครบ')
    for x in sorted(set(rel_bad)):
        print('  ', x)
    print('== anchor (#) ที่ไม่มีเป้าหมายในหน้า')
    for x in sorted(set(anchors_bad)):
        print('  ', x)
    print(f'== ปุ่มที่ยังรอข้อมูลเจ้าของ (data-wait): {waits} จุด')
    print('== จำนวนหน้าที่ลิงก์เข้าหาแต่ละหน้า (ไม่นับหน้าตัวเอง)')
    for u in sorted(set(sitemap) | {SITE}):
        n = norm(u)
        print(f'  {len(links_to.get(n, ())):3d}  {n.replace(SITE, "/")}')


if __name__ == '__main__':
    main()
