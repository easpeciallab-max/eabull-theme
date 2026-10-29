/* EA BULL mockup behaviour: menu, contact sheet, cookie card, tabs,
   readiness checklist, calculators, reading bar, colour variants. */
(function () {
  'use strict';
  var $ = function (s, r) { return (r || document).querySelector(s); };
  var $$ = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };
  var params = new URLSearchParams(location.search);
  var store = {
    get: function (k) { try { return localStorage.getItem(k); } catch (e) { return null; } },
    set: function (k, v) { try { localStorage.setItem(k, v); } catch (e) { /* private mode */ } }
  };

  /* Toast for links the owner has not given yet ({{link:line}} etc). */
  var toast = $('#toast');
  var toastTimer;
  function say(msg) {
    if (!toast) return;
    toast.textContent = msg;
    toast.classList.add('is-on');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(function () { toast.classList.remove('is-on'); }, 2600);
  }
  document.addEventListener('click', function (e) {
    var a = e.target.closest('[data-wait]');
    if (a) { e.preventDefault(); say(a.getAttribute('data-wait')); }
  });

  /* Main menu */
  var nav = $('#nav');
  var toggle = $('.hd__toggle');
  var mobile = window.matchMedia('(max-width: 1100px)');
  function closeSubs(except) {
    $$('.has-sub.is-open').forEach(function (li) {
      if (li === except) return;
      li.classList.remove('is-open');
      $('.nav__parent', li).setAttribute('aria-expanded', 'false');
    });
  }
  function setNav(open) {
    if (!nav || !toggle) return;
    nav.classList.toggle('is-open', open);
    toggle.setAttribute('aria-expanded', String(open));
    toggle.setAttribute('aria-label', open ? 'ปิดเมนู' : 'เปิดเมนู');
    document.body.classList.toggle('nav-open', open);
    if (open && mobile.matches) {
      $$('.has-sub').forEach(function (li) {
        if ($('[aria-current]', li)) { li.classList.add('is-open'); $('.nav__parent', li).setAttribute('aria-expanded', 'true'); }
      });
    }
  }
  if (toggle) toggle.addEventListener('click', function () { setNav(!nav.classList.contains('is-open')); });
  $$('.nav__parent').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var li = btn.parentElement;
      var open = !li.classList.contains('is-open');
      if (!mobile.matches) closeSubs(li);
      li.classList.toggle('is-open', open);
      btn.setAttribute('aria-expanded', String(open));
    });
  });
  document.addEventListener('click', function (e) {
    if (!mobile.matches && !e.target.closest('.has-sub')) closeSubs();
  });

  /* Floating LINE button and quick contact sheet */
  var fab = $('.fab');
  var quick = $('#quick');
  function setQuick(open) {
    if (!fab || !quick) return;
    quick.hidden = !open;
    fab.setAttribute('aria-expanded', String(open));
  }
  if (fab) fab.addEventListener('click', function () { setQuick(quick.hidden); });
  $$('[data-quick-close]').forEach(function (b) { b.addEventListener('click', function () { setQuick(false); fab.focus(); }); });
  document.addEventListener('click', function (e) {
    if (quick && !quick.hidden && !e.target.closest('#quick') && !e.target.closest('.fab')) setQuick(false);
  });

  document.addEventListener('keydown', function (e) {
    if (e.key !== 'Escape') return;
    if (nav && nav.classList.contains('is-open')) { setNav(false); toggle.focus(); }
    closeSubs();
    setQuick(false);
  });

  /* Cookie card. ?cookie=ga shows the GA4 wording, ?cookie=settings opens
     the settings view, ?shot hides the card for screenshots. */
  var ck = $('#cookie');
  if (ck) {
    var mode = params.get('cookie');
    var withGa = mode === 'ga' || mode === 'settings';
    $$('[data-ck-text]', ck).forEach(function (el) { el.hidden = el.getAttribute('data-ck-text') !== (withGa ? 'ga' : 'none'); });
    $$('[data-ck-btns]', ck).forEach(function (el) { el.hidden = el.getAttribute('data-ck-btns') !== (withGa ? 'ga' : 'none'); });
    var view = function (name) {
      $$('[data-ck-view]', ck).forEach(function (v) { v.hidden = v.getAttribute('data-ck-view') !== name; });
    };
    var openCk = function (name) { view(name || 'intro'); ck.hidden = false; };
    var done = function (value) { store.set('bull_consent', value); ck.hidden = true; };
    if (mode) openCk(mode === 'settings' ? 'settings' : 'intro');
    else if (!params.has('shot') && !store.get('bull_consent')) openCk('intro');
    ck.addEventListener('click', function (e) {
      var b = e.target.closest('[data-ck]');
      if (!b) return;
      var act = b.getAttribute('data-ck');
      if (act === 'accept') done('all');
      else if (act === 'reject') done('necessary');
      else if (act === 'settings') view('settings');
      else if (act === 'back') view('intro');
      else if (act === 'save') done($('[data-ck-cat="analytics"]', ck).checked ? 'all' : 'necessary');
      else if (act === 'close') ck.hidden = true;
    });
    $$('[data-open-cookie]').forEach(function (b) { b.addEventListener('click', function () { openCk('intro'); }); });
  }

  /* Tabs */
  $$('[data-tabs]').forEach(function (box) {
    var tabs = $$('[role="tab"]', box);
    var panels = $$('[role="tabpanel"]', box);
    function show(i) {
      tabs.forEach(function (t, j) {
        t.setAttribute('aria-selected', String(i === j));
        t.tabIndex = i === j ? 0 : -1;
        panels[j].hidden = i !== j;
      });
    }
    tabs.forEach(function (t, i) {
      t.addEventListener('click', function () { show(i); });
      t.addEventListener('keydown', function (e) {
        var n = e.key === 'ArrowRight' ? 1 : e.key === 'ArrowLeft' ? -1 : 0;
        if (!n) return;
        var k = (i + n + tabs.length) % tabs.length;
        show(k);
        tabs[k].focus();
      });
    });
    show(0);
  });

  /* Readiness checklist */
  $$('[data-ready]').forEach(function (box) {
    var checks = $$('input[type="checkbox"]', box);
    var bars = $$('.meter i', box);
    var count = $('[data-ready-count]', box);
    var status = $('[data-ready-status]', box);
    var finish = $('[data-ready-done]', box);
    function update() {
      var n = checks.filter(function (c) { return c.checked; }).length;
      bars.forEach(function (b, i) { b.classList.toggle('on', i < n); });
      if (count) count.textContent = n;
      if (status) status.textContent = n === checks.length ? 'พร้อมเริ่มแล้ว ทักทีมงานได้เลย' : 'ยังเหลืออีก ' + (checks.length - n) + ' ข้อ';
      if (finish) finish.hidden = n !== checks.length;
    }
    checks.forEach(function (c) { c.addEventListener('change', update); });
    update();
  });

  /* Calculators (tools page) */
  var num = function (el) { var v = parseFloat(el && el.value); return isFinite(v) ? v : 0; };
  var fmt = function (v, d) { return v.toLocaleString('th-TH', { minimumFractionDigits: d, maximumFractionDigits: d }); };
  var lot = $('[data-calc="lot"]');
  if (lot) {
    var preset = $('[name="preset"]', lot);
    var pip = $('[name="pip"]', lot);
    var calcLot = function () {
      var money = num($('[name="balance"]', lot)) * num($('[name="risk"]', lot)) / 100;
      var sl = num($('[name="sl"]', lot));
      var step = num($('[name="step"]', lot)) || 0.01;
      var raw = sl > 0 && num(pip) > 0 ? money / (sl * num(pip)) : 0;
      var size = Math.floor(raw / step + 1e-9) * step;
      $('[data-out="money"]', lot).textContent = fmt(money, 2) + ' USD';
      $('[data-out="lot"]', lot).textContent = fmt(size, 2) + ' lot';
    };
    if (preset) preset.addEventListener('change', function () {
      if (preset.value !== 'custom') pip.value = preset.value;
      pip.readOnly = preset.value !== 'custom';
      calcLot();
    });
    lot.addEventListener('input', calcLot);
    calcLot();
  }
  var dd = $('[data-calc="dd"]');
  if (dd) {
    var calcDd = function () {
      var pct = Math.min(Math.max(num($('[name="dd"]', dd)), 0), 99.9);
      var cap = num($('[name="capital"]', dd));
      var back = pct / (100 - pct) * 100;
      $('[data-out="back"]', dd).textContent = fmt(back, 2) + '%';
      $('[data-out="left"]', dd).textContent = cap > 0 ? fmt(cap * (1 - pct / 100), 2) + ' USD' : 'ใส่ทุนตั้งต้นเพื่อดูตัวเลข';
      var bar = $('[data-out="bar"]', dd);
      if (bar) bar.style.width = (100 - pct) + '%';
    };
    dd.addEventListener('input', calcDd);
    calcDd();
  }

  /* Reading progress bar (article page) */
  var readbar = $('.readbar i');
  if (readbar) {
    var onScroll = function () {
      var h = document.documentElement;
      var max = h.scrollHeight - h.clientHeight;
      readbar.style.width = (max > 0 ? h.scrollTop / max * 100 : 0) + '%';
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  /* Screenshot states: ?menu=open, ?sub=<data-nav key>, ?quick=open */
  if (params.get('menu') === 'open') setNav(true);
  if (params.get('quick') === 'open') setQuick(true);
  if (params.get('sub')) {
    var subBtn = $('.nav__parent[data-nav="' + params.get('sub') + '"]');
    if (subBtn) subBtn.click();
  }

  /* More screenshot states: ?tab=<n> (1-based), ?ready=all, ?toast=1 */
  if (params.get('tab')) {
    var tab = $$('[data-tabs] [role="tab"]')[parseInt(params.get('tab'), 10) - 1];
    if (tab) tab.click();
  }
  if (params.get('ready') === 'all') {
    $$('[data-ready] input[type="checkbox"]').forEach(function (c) {
      c.checked = true;
      c.dispatchEvent(new Event('change'));
    });
  }
  if (params.get('toast')) { say('ลิงก์ LINE OA ของ EA BULL รอข้อมูลจากเจ้าของ'); clearTimeout(toastTimer); }
  /* ?focus=<id>: hide what comes before that section so a viewport shot starts there */
  var focus = params.get('focus') && document.getElementById(params.get('focus'));
  var main = $('#main');
  if (focus && main) {
    var top = focus;
    while (top.parentElement && top.parentElement !== main) top = top.parentElement;
    for (var el = top.previousElementSibling; el; el = el.previousElementSibling) el.hidden = true;
  }

  /* ?measure: dev/tools/shoot.py reads the page height from this meta tag. */
  if (params.has('measure') && document.fonts) {
    document.fonts.ready.then(function () {
      var m = document.createElement('meta');
      m.name = 'page-height';
      m.content = String(document.documentElement.scrollHeight);
      document.head.appendChild(m);
    });
  }
})();
