"""Assemble the EA BULL phase-1 mockup pages.

Sources live in _src/ and the finished pages are written next to this file,
so never edit dev/mockup/*.html by hand.

  _src/layout.html        page skeleton
  _src/partials/*.html    header, footer, contact band, dock, fab, cookie card
  _src/pages/<slug>.html  page body; line 1 is <!--{json front matter}-->
  _src/icons.json         line icons used through {{i:name}}

Front matter keys:
  title    page title (" · EA BULL" is appended)
  desc     meta description
  nav      list of data-nav keys to mark as the current menu item
  dock     data-dock key to mark in the mobile bottom bar
  layout   "site" (default) or "bare" (no header, footer, dock, fab)
  cta      false to drop the contact band, or {"title": .., "sub": ..}
  body     extra class for <body>
  outputs  [{"slug": .., "vars": {..}}] to render one source several times;
           {{v:key}} reads from vars

Usage: py -3 -I dev/mockup/build.py
"""
import json
import re
import sys
import time
from pathlib import Path

sys.stdout.reconfigure(encoding='utf-8')

ROOT = Path(__file__).resolve().parent
SRC = ROOT / '_src'
ICONS = json.loads((SRC / 'icons.json').read_text(encoding='utf-8'))
VERSION = time.strftime('%Y%m%d%H%M')

OPENCHAT = 'https://line.me/ti/g2/2JBjB5FQGk26L9d0Nm-geWaVr-CUFe2y8MONQw'
FACEBOOK = 'https://www.facebook.com/eabull.trading/'
ZAURIX = 'https://portal.zaurix.com/sign-up?ref=211281'

LINKS = {
    # Links the owner has not given yet: clicking shows a toast instead.
    'line': 'href="#" data-wait="ลิงก์ LINE OA ของ EA BULL รอข้อมูลจากเจ้าของ"',
    'dash': 'href="#" data-wait="ลิงก์แดชบอร์ดลูกค้า ยังไม่เสร็จ"',
    'mail': 'href="#" data-wait="อีเมลติดต่อ รอข้อมูลจากเจ้าของ"',
    # Real links from the owner (29 Oct 2026).
    'openchat': f'href="{OPENCHAT}" target="_blank" rel="noopener"',
    'fb': f'href="{FACEBOOK}" target="_blank" rel="noopener"',
    'zaurix': f'href="{ZAURIX}" target="_blank" rel="sponsored noopener"',
}

DEFAULT_CTA = {
    'title': 'มีคำถามก่อนเริ่ม คุยกับทีมงานได้เลย',
    'sub': 'ทักมาทาง LINE ได้ทุกเรื่อง ตั้งแต่เลือกแพ็กเกจ เปิดบัญชี ไปจนถึงติดตั้ง EA บน MT5',
}

BARS = '<span class="bars" aria-hidden="true"><i></i><i></i><i></i></span>'


def icon(name):
    if name not in ICONS:
        raise SystemExit(f'unknown icon: {name}')
    return ('<svg class="i" viewBox="0 0 24 24" aria-hidden="true" fill="none" '
            'stroke="currentColor" stroke-width="1.75" stroke-linecap="round" '
            f'stroke-linejoin="round">{ICONS[name]}</svg>')


def partial(name):
    return (SRC / 'partials' / f'{name}.html').read_text(encoding='utf-8')


def expand(html, variables):
    # Partials first (they may contain other tokens), then the inline tokens.
    for _ in range(3):
        html = re.sub(r'\{\{>\s*([\w-]+)\s*\}\}', lambda m: partial(m.group(1)), html)
    html = re.sub(r'\{\{v:([\w-]+)\}\}', lambda m: variables[m.group(1)], html)
    html = re.sub(r'\{\{i:([\w-]+)\}\}', lambda m: icon(m.group(1)), html)
    html = re.sub(r'\{\{link:([\w-]+)\}\}', lambda m: LINKS[m.group(1)], html)
    html = re.sub(r'\{\{wait-link:([^}]+)\}\}', lambda m: f'href="#" data-wait="{m.group(1)}"', html)
    html = re.sub(r'\{\{wait:([^}]+)\}\}', lambda m: f'<span class="wait">{m.group(1)}</span>', html)
    html = html.replace('{{wait}}', '<span class="wait">รอข้อมูลจากเจ้าของ</span>')
    html = html.replace('{{bars}}', BARS)
    left = re.findall(r'\{\{[^}]*\}\}', html)
    if left:
        raise SystemExit(f'unresolved tokens: {sorted(set(left))[:5]}')
    return html


def mark_current(html, attr, keys):
    for key in keys:
        html = html.replace(f'{attr}="{key}"', f'{attr}="{key}" aria-current="page"')
    return html


def render(meta, body, variables):
    layout = meta.get('layout', 'site')
    top, bottom = '', ''
    if layout == 'site':
        top = partial('header')
        cta = meta.get('cta', DEFAULT_CTA)
        if cta:
            cta = {**DEFAULT_CTA, **(cta if isinstance(cta, dict) else {})}
            bottom += partial('contact').replace('{{cta_title}}', cta['title']).replace('{{cta_sub}}', cta['sub'])
        bottom += partial('footer') + partial('dock') + partial('fab')
    page = partial('../layout')
    page = page.replace('{{chrome_top}}', top).replace('{{chrome_bottom}}', bottom)
    page = page.replace('{{title}}', meta['title']).replace('{{desc}}', meta.get('desc', ''))
    page = page.replace('{{body_class}}', meta.get('body', ''))
    page = page.replace('{{ver}}', VERSION)
    page = page.replace('{{body}}', body)
    page = expand(page, variables)
    page = mark_current(page, 'data-nav', meta.get('nav', []))
    page = mark_current(page, 'data-dock', [meta['dock']] if meta.get('dock') else [])
    return page


def main():
    count = 0
    for src in sorted((SRC / 'pages').glob('*.html')):
        text = src.read_text(encoding='utf-8')
        m = re.match(r'\s*<!--(\{.*?\})-->\s*\n', text, re.S)
        if not m:
            raise SystemExit(f'{src.name}: missing front matter')
        meta = json.loads(m.group(1))
        body = text[m.end():]
        outputs = meta.get('outputs') or [{'slug': src.stem, 'vars': {}}]
        for out in outputs:
            variables = out.get('vars', {})
            page_meta = {k: (expand(v, variables) if isinstance(v, str) else v) for k, v in meta.items()}
            if 'nav' in out:
                page_meta['nav'] = out['nav']
            html = render(page_meta, body, variables)
            (ROOT / f"{out['slug']}.html").write_text(html, encoding='utf-8', newline='\n')
            count += 1
    print(f'built {count} pages · v{VERSION}')


if __name__ == '__main__':
    main()
