// Screenshot one element of a live eabull.com page through the Chrome DevTools protocol.
// Used for guide images that show EA BULL's own pages (e.g. the Zaurix signup step on /go/).
// Usage: node dev/tools/shot-element.mjs <url> <css selector> <out.png> [width=480] [scale=2] [pad=0] [selector to click first]
import { spawn } from 'node:child_process';
import { mkdtempSync, writeFileSync } from 'node:fs';
import { tmpdir } from 'node:os';
import { join } from 'node:path';

const [url, selector, out, width = '480', scale = '2', pad = '0', click = ''] = process.argv.slice(2);
const CHROME = 'C:/Program Files/Google/Chrome/Application/chrome.exe';
const PORT = 9334;
const sleep = (ms) => new Promise((r) => setTimeout(r, ms));

const chrome = spawn(CHROME, ['--headless=new', '--disable-gpu', '--hide-scrollbars', `--remote-debugging-port=${PORT}`,
  `--user-data-dir=${mkdtempSync(join(tmpdir(), 'eab-shot-'))}`, '--no-first-run', 'about:blank'], { stdio: 'ignore' });

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

try {
  ws = new WebSocket(await connect());
  await new Promise((r) => ws.addEventListener('open', r));
  ws.addEventListener('message', (m) => {
    const d = JSON.parse(m.data);
    if (d.id && waiting.has(d.id)) { (d.error ? waiting.get(d.id).rej : waiting.get(d.id).res)(d.error || d.result); waiting.delete(d.id); }
    else if (d.method) events.push(d.method);
  });
  await send('Page.enable');
  await send('Emulation.setDeviceMetricsOverride', { width: +width, height: 1200, deviceScaleFactor: +scale, mobile: false });
  await send('Page.navigate', { url });
  for (let i = 0; i < 150 && !events.includes('Page.loadEventFired'); i++) await sleep(100);
  await evaluate('document.fonts ? document.fonts.ready.then(() => 1) : 1');
  await sleep(1500);
  const box = await evaluate(`(() => { const el = document.querySelector(${JSON.stringify(selector)}); if (!el) return null;
    el.scrollIntoView({ block: 'center' }); const r = el.getBoundingClientRect();
    return { x: r.left + scrollX, y: r.top + scrollY, w: r.width, h: r.height }; })()`);
  if (!box) throw new Error(`no element ${selector}`);
  await sleep(800);
  if (click) {
    // open a UI state (e.g. a dropdown) before the capture · several selectors: "a || b"
    for (const sel of click.split('||').map((s) => s.trim()).filter(Boolean)) {
      await evaluate(`(document.querySelector(${JSON.stringify(sel)}) || { click() {} }).click()`);
      await sleep(500);
    }
  }
  const p = +pad;
  const r = await send('Page.captureScreenshot', { format: 'png', captureBeyondViewport: true,
    clip: { x: Math.max(0, box.x - p), y: Math.max(0, box.y - p), width: box.w + 2 * p, height: box.h + 2 * p, scale: 1 } });
  writeFileSync(out, Buffer.from(r.data, 'base64'));
  console.log(out, Math.round(box.w), 'x', Math.round(box.h));
} finally {
  chrome.kill();
}
