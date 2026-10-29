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
    // scroll through once so scroll-triggered reveals (main.js .is-in) have run, then back to the top
    await evaluate(`(async () => { const H = document.documentElement.scrollHeight; for (let y = 0; y < H; y += 500) { window.scrollTo(0, y); await new Promise(r => setTimeout(r, 90)); } window.scrollTo(0, 0); await new Promise(r => setTimeout(r, 900)); return 1; })()`);
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

  for (const kind of Object.keys(WIDTHS)) {
    const dir = join(OUT, kind);
    mkdirSync(dir, { recursive: true });
    await viewport(kind);
    // first visit: the PDPA cookie card
    await send('Network.clearBrowserCookies');
    await go(BASE);
    await shot(join(dir, '00-cookie-card.png'), false);
    await evaluate(`(document.querySelector('[data-consent="prefs"]')||{click(){}}).click(), 1`);
    await sleep(500);
    await shot(join(dir, '00-cookie-settings.png'), false);
    await evaluate(`(document.querySelector('[data-consent="reject"]')||document.querySelector('[data-consent="save"]')||{click(){}}).click(), 1`);
    await sleep(400);
    // menu states
    if (kind === 'desktop') {
      const box = await evaluate(`(() => { const a = document.querySelector('.nav .menu-item-has-children > a'); if (!a) return null; const r = a.getBoundingClientRect(); return [r.x + r.width / 2, r.y + r.height / 2]; })()`);
      if (box) {
        await send('Input.dispatchMouseEvent', { type: 'mouseMoved', x: box[0], y: box[1] });
        await sleep(400);
        await shot(join(dir, '01-menu-dropdown.png'), false);
        await send('Input.dispatchMouseEvent', { type: 'mouseMoved', x: 5, y: 600 });
      }
    } else {
      await evaluate(`document.querySelector('.hd__toggle').click(), 1`);
      await sleep(500);
      await shot(join(dir, '01-menu-open.png'), false);
      await evaluate(`(document.querySelector('.nav .menu-item-has-children > a')||{click(){}}).click(), 1`);
      await sleep(400);
      await shot(join(dir, '02-menu-submenu.png'), false);
      await evaluate(`document.querySelector('.hd__toggle').click(), 1`);
      await sleep(300);
    }
    await evaluate(`(document.querySelector('.fab')||{click(){}}).click(), 1`);
    await sleep(500);
    await shot(join(dir, '03-quick-contact.png'), false);
    // full pages
    let n = 10;
    for (const p of PAGES) {
      await go(BASE + (p ? p + '/' : ''));
      await shot(join(dir, `${n++}-${p || 'home'}.png`), true);
    }
  }
} catch (e) {
  console.error('ERROR', e);
  process.exitCode = 1;
} finally {
  try { ws && ws.close(); } catch { /* closed */ }
  chrome.kill();
}
