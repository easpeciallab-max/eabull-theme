// Screenshots of the live eabull.com through the Chrome DevTools protocol (any width, full page, UI states).
// Usage: node dev/tools/live-shots.mjs "D:/EA VIDEO/ea-bull-repo/dev/shots/live"   (live-check.mjs: same args, prints problems per page)
import { spawn } from 'node:child_process';
import { mkdirSync, writeFileSync } from 'node:fs';
import { join } from 'node:path';

const OUT = process.argv[2];
const CHROME = 'C:/Program Files/Google/Chrome/Application/chrome.exe';
const PORT = 9333;
const BASE = 'https://eabull.com/';
const PAGES = ['', 'pricing', 'product-1', 'how-to-install', 'tools', 'about', 'go', 'articles', 'risk-disclosure', 'privacy-policy'];
const WIDTHS = { desktop: [1366, 900], tablet: [820, 1180], mobile: [390, 844] };
const sleep = (ms) => new Promise((r) => setTimeout(r, ms));

const chrome = spawn(CHROME, ['--headless=new', '--disable-gpu', '--hide-scrollbars', `--remote-debugging-port=${PORT}`,
  `--user-data-dir=${join(OUT, '..', '_chrome-profile')}`, '--no-first-run', 'about:blank'], { stdio: 'ignore' });

let ws, seq = 0;
const waiting = new Map();
const events = [];
function send(method, params = {}) {
  const id = ++seq;
  ws.send(JSON.stringify({ id, method, params }));
  return new Promise((res, rej) => waiting.set(id, { res, rej }));
}
async function evaluate(expr) {
  const r = await send('Runtime.evaluate', { expression: expr, awaitPromise: true, returnByValue: true });
  return r.result ? r.result.value : undefined;
}
async function connect() {
  for (let i = 0; i < 40; i++) {
    try {
      const list = await (await fetch(`http://127.0.0.1:${PORT}/json/list`)).json();
      const page = list.find((t) => t.type === 'page');
      if (page) return page.webSocketDebuggerUrl;
    } catch { /* not up yet */ }
    await sleep(250);
  }
  throw new Error('chrome did not start');
}
async function viewport(kind) {
  const [w, h] = WIDTHS[kind];
  await send('Emulation.setDeviceMetricsOverride', { width: w, height: h, deviceScaleFactor: 1, mobile: kind === 'mobile' });
  await send('Emulation.setTouchEmulationEnabled', { enabled: kind !== 'desktop' });
}
async function go(url) {
  events.length = 0;
  await send('Page.navigate', { url });
  for (let i = 0; i < 120 && !events.includes('Page.loadEventFired'); i++) await sleep(100);
  await evaluate('document.fonts ? document.fonts.ready.then(() => 1) : 1');
  await sleep(1200);
}
async function shot(file, full) {
  let params = { format: 'png' };
  if (full) {
    const h = await evaluate('Math.ceil(document.documentElement.scrollHeight)');
    const w = await evaluate('document.documentElement.clientWidth');
    params = { ...params, captureBeyondViewport: true, clip: { x: 0, y: 0, width: w, height: h, scale: 1 } };
  }
  const r = await send('Page.captureScreenshot', params);
  writeFileSync(file, Buffer.from(r.data, 'base64'));
  console.log(file.replace(OUT, '').replace(/\\/g, '/'));
}

try {
  ws = new WebSocket(await connect());
  await new Promise((r) => ws.addEventListener('open', r));
  ws.addEventListener('message', (m) => {
    const d = JSON.parse(m.data);
    if (d.id && waiting.has(d.id)) { (d.error ? waiting.get(d.id).rej : waiting.get(d.id).res)(d.error || d.result); waiting.delete(d.id); }
    else if (d.method) events.push(d.method);
  });
  await send('Page.enable');
  await send('Network.enable');
  await send('Network.setCacheDisabled', { cacheDisabled: true });

  const ALL = ['', 'about', 'backtest', 'forward-test', 'how-to-install', 'open-mt5-account', 'deposit', 'mt5-login', 'vps-windows', 'vps-android', 'vps-ios', 'tools', 'pricing', 'product-1', 'product-2', 'product-3', 'risk-disclosure', 'privacy-policy', 'data-deletion', 'go', 'articles', 'no-such-page-xyz', '?s=MT5'];
  for (const kind of Object.keys(WIDTHS)) {
    await viewport(kind);
    for (const p of ALL) {
      await go(BASE + p + (p && !p.startsWith('?') && p !== '' ? '/' : ''));
      const r = await evaluate(`(() => { const d = document.documentElement; const out = [];
        if (d.scrollWidth > d.clientWidth + 1) out.push('overflow ' + d.scrollWidth + '>' + d.clientWidth);
        const h1 = document.querySelectorAll('h1').length; if (h1 !== 1) out.push('h1=' + h1);
        document.querySelectorAll('img').forEach(i => { if (!i.hasAttribute('alt')) out.push('img no alt ' + i.src.slice(-40)); if (i.complete && i.naturalWidth === 0) out.push('broken img ' + i.src.slice(-50)); });
        document.querySelectorAll('a[href^="http"]').forEach(a => { if (!a.href.includes(location.host) && !/noopener/.test(a.rel)) out.push('ext no noopener ' + a.href.slice(0, 50)); });
        if (/[–—]/.test(document.body.innerText)) out.push('dash in text');
        if (/(Warning|Notice|Fatal error|Deprecated):/.test(document.body.innerText)) out.push('php notice');
        if (/wing/i.test(document.body.innerText.replace(/Win(dows)?/g, ''))) out.push('WING in text');
        return out; })()`);
      console.log(kind.padEnd(8), (p || '/').padEnd(18), r.length ? r.join(' | ') : 'ok');
    }
  }
} catch (e) {
  console.error('ERROR', e);
  process.exitCode = 1;
} finally {
  try { ws && ws.close(); } catch { /* closed */ }
  chrome.kill();
}
