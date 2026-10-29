/* EA BULL · main.js
   Behaviour for the Bull Black markup (docs/phase2.md lists every hook):
   menu and submenus, quick contact sheet, placeholder links, tabs, readiness
   checklist, calculators, reading bar, share copy, load more, LINE click
   analytics, table of contents, lightbox. No dependencies. */
(function () {
	'use strict';
	var $ = function (s, r) { return (r || document).querySelector(s); };
	var $$ = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };
	var cfg = window.eabChrome || {};
	var reduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	window.eab = window.eab || {};
	window.eab.reduced = reduced;

	/* Motion (owner 29 Oct 2026) · section heading bars, [data-reveal] children one by one, step track.
	   style.css hides the start states only with .js and when motion is allowed, so this just adds .is-in. */
	var motionEls = $$('.shead, [data-reveal], .track');
	motionEls.forEach(function (el) {
		Array.prototype.forEach.call(el.children, function (child, i) { child.style.setProperty('--i', i); });
	});
	if (!reduced && 'IntersectionObserver' in window) {
		var motionObs = new IntersectionObserver(function (entries) {
			entries.forEach(function (en) {
				if (en.isIntersecting) { en.target.classList.add('is-in'); motionObs.unobserve(en.target); }
			});
		}, { rootMargin: '0px 0px -10% 0px', threshold: 0.1 });
		motionEls.forEach(function (el) { motionObs.observe(el); });
		/* second path: a plain position check on scroll and resize, so nothing can stay hidden
		   if the observer misses a fast scroll or a jump to an anchor */
		var motionTick = false;
		var motionCheck = function () {
			motionTick = false;
			var line = window.innerHeight * 0.95;
			motionEls = motionEls.filter(function (el) {
				if (el.getBoundingClientRect().top < line) { el.classList.add('is-in'); return false; }
				return true;
			});
			if (!motionEls.length) {
				window.removeEventListener('scroll', motionQueue);
				window.removeEventListener('resize', motionQueue);
			}
		};
		var motionQueue = function () {
			if (!motionTick) { motionTick = true; window.requestAnimationFrame(motionCheck); }
		};
		window.addEventListener('scroll', motionQueue, { passive: true });
		window.addEventListener('resize', motionQueue);
		window.addEventListener('load', motionQueue);
	} else {
		motionEls.forEach(function (el) { el.classList.add('is-in'); });
	}

	/* Toast · links without a real URL yet carry data-wait="<message>" */
	var toast = $('#toast');
	var toastTimer;
	function say(msg) {
		if (!toast || !msg) return;
		toast.textContent = msg;
		toast.classList.add('is-on');
		clearTimeout(toastTimer);
		toastTimer = setTimeout(function () { toast.classList.remove('is-on'); }, 2600);
	}
	window.eab.say = say;
	document.addEventListener('click', function (e) {
		var a = e.target.closest ? e.target.closest('[data-wait]') : null;
		if (a) { e.preventDefault(); say(a.getAttribute('data-wait')); }
	});

	/* Header menu · .hd__toggle opens #nav (sheet ≤ 1100px)
	   Parents: button.nav__parent (fallback menu) or .menu-item-has-children > a (WP menu).
	   Desktop opens on hover/focus via CSS; a click on a WP parent link still navigates there.
	   On the sheet a parent tap folds its submenu open instead. */
	var nav = $('#nav');
	var toggle = $('.hd__toggle');
	var sheetMq = window.matchMedia('(max-width: 1100px)');
	var parentSel = '.has-sub, .menu-item-has-children';
	function setParent(li, open) {
		li.classList.toggle('is-open', open);
		var ctl = li.querySelector(':scope > .nav__parent, :scope > a');
		if (ctl) ctl.setAttribute('aria-expanded', String(open));
	}
	function closeParents(except) {
		$$('.nav .is-open').forEach(function (li) { if (li !== except) setParent(li, false); });
	}
	function setNav(open) {
		if (!nav || !toggle) return;
		nav.classList.toggle('is-open', open);
		toggle.setAttribute('aria-expanded', String(open));
		toggle.setAttribute('aria-label', open ? (toggle.getAttribute('data-close') || 'ปิดเมนู') : (toggle.getAttribute('data-open') || 'เปิดเมนู'));
		document.body.classList.toggle('nav-open', open);
		if (open && sheetMq.matches) {
			$$(parentSel, nav).forEach(function (li) {
				if (li.querySelector('[aria-current], .current-menu-item')) setParent(li, true);
			});
		}
	}
	if (toggle) toggle.addEventListener('click', function () { setNav(!nav.classList.contains('is-open')); });
	$$('.nav__parent').forEach(function (btn) {
		btn.addEventListener('click', function () {
			var li = btn.parentElement;
			var open = !li.classList.contains('is-open');
			if (!sheetMq.matches) closeParents(li);
			setParent(li, open);
		});
	});
	$$('.nav .menu-item-has-children > a').forEach(function (link) {
		link.setAttribute('aria-expanded', 'false');
		link.addEventListener('click', function (e) {
			if (!sheetMq.matches) return;
			e.preventDefault();
			var li = link.parentElement;
			setParent(li, !li.classList.contains('is-open'));
		});
	});
	document.addEventListener('click', function (e) {
		if (!sheetMq.matches && !e.target.closest(parentSel)) closeParents();
	});
	sheetMq.addEventListener && sheetMq.addEventListener('change', function () { setNav(false); closeParents(); });

	/* Floating LINE button · .fab opens #quick */
	var fab = $('.fab');
	var quick = $('#quick');
	function setQuick(open) {
		if (!fab || !quick) return;
		quick.hidden = !open;
		fab.setAttribute('aria-expanded', String(open));
	}
	if (fab && quick) fab.addEventListener('click', function () { setQuick(quick.hidden); });
	$$('[data-quick-close]').forEach(function (b) { b.addEventListener('click', function () { setQuick(false); if (fab) fab.focus(); }); });
	document.addEventListener('click', function (e) {
		if (quick && !quick.hidden && !e.target.closest('#quick') && !e.target.closest('.fab')) setQuick(false);
	});

	document.addEventListener('keydown', function (e) {
		if (e.key !== 'Escape') return;
		if (nav && nav.classList.contains('is-open')) { setNav(false); toggle.focus(); }
		closeParents();
		setQuick(false);
	});

	/* Tabs · [data-tabs] with [role=tab] / [role=tabpanel] in the same order */
	$$('[data-tabs]').forEach(function (box) {
		var tabs = $$('[role="tab"]', box);
		var panels = $$('[role="tabpanel"]', box);
		function show(i, focus) {
			tabs.forEach(function (t, j) {
				t.setAttribute('aria-selected', String(i === j));
				t.tabIndex = i === j ? 0 : -1;
				if (panels[j]) panels[j].hidden = i !== j;
			});
			if (focus) tabs[i].focus();
		}
		tabs.forEach(function (t, i) {
			t.addEventListener('click', function () { show(i, false); });
			t.addEventListener('keydown', function (e) {
				var n = e.key === 'ArrowRight' ? 1 : e.key === 'ArrowLeft' ? -1 : 0;
				if (!n) return;
				e.preventDefault();
				show((i + n + tabs.length) % tabs.length, true);
			});
		});
		show(0, false);
	});

	/* Readiness checklist · [data-ready] (texts come from data-left / data-full, {n} = items left) */
	$$('[data-ready]').forEach(function (box) {
		var checks = $$('input[type="checkbox"]', box);
		var bars = $$('.meter i', box);
		var count = $('[data-ready-count]', box);
		var status = $('[data-ready-status]', box);
		var finish = $('[data-ready-done]', box);
		var left = box.getAttribute('data-left') || '{n}';
		var full = box.getAttribute('data-full') || '';
		function update() {
			var n = checks.filter(function (c) { return c.checked; }).length;
			bars.forEach(function (b, i) { b.classList.toggle('on', i < n); });
			if (count) count.textContent = n;
			if (status) status.textContent = n === checks.length ? full : left.replace('{n}', checks.length - n);
			if (finish) finish.hidden = n !== checks.length;
		}
		checks.forEach(function (c) { c.addEventListener('change', update); });
		update();
	});

	/* Select boxes · .sel > select becomes a Bull Black listbox (owner 29 Oct 2026: the browser's own list
	   looked unfinished). The native select stays in place, hidden, keeps the value and gets the change
	   event, so the calculators read it as before. Option text "name · detail" shows on two lines. */
	var selCheck = '<svg class="i" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>';
	var selText = function (el, text) {
		var cut = text.indexOf(' · ');
		var b = document.createElement('b');
		b.textContent = cut > 0 ? text.slice(0, cut) : text;
		el.appendChild(b);
		if (cut > 0) {
			var small = document.createElement('small');
			small.textContent = text.slice(cut + 3);
			el.appendChild(small);
		}
	};
	$$('.sel > select').forEach(function (sel, n) {
		var wrap = sel.parentNode;
		var base = sel.id || 'sel-' + n;
		var label = sel.id ? $('label[for="' + sel.id + '"]') : null;
		var arrow = $('.sel__arrow', wrap);
		var btn = document.createElement('button');
		var val = document.createElement('span');
		var list = document.createElement('ul');
		var opts = [];
		var active = -1;

		if (label && !label.id) label.id = base + '-label';
		btn.type = 'button';
		btn.className = 'sel__btn';
		btn.id = base + '-btn';
		btn.setAttribute('role', 'combobox');
		btn.setAttribute('aria-haspopup', 'listbox');
		btn.setAttribute('aria-expanded', 'false');
		btn.setAttribute('aria-controls', base + '-list');
		btn.setAttribute('aria-labelledby', (label ? label.id + ' ' : '') + base + '-val');
		val.className = 'sel__val';
		val.id = base + '-val';
		btn.appendChild(val);
		if (arrow) btn.appendChild(arrow);
		list.className = 'sel__list';
		list.id = base + '-list';
		list.setAttribute('role', 'listbox');
		list.tabIndex = -1;
		if (label) list.setAttribute('aria-labelledby', label.id);
		list.hidden = true;

		Array.prototype.forEach.call(sel.options, function (o, i) {
			var li = document.createElement('li');
			var txt = document.createElement('span');
			li.className = 'sel__opt';
			li.id = base + '-opt' + i;
			li.setAttribute('role', 'option');
			selText(txt, o.text);
			li.appendChild(txt);
			li.insertAdjacentHTML('beforeend', selCheck);
			li.addEventListener('mousemove', function () { setActive(i, false); });
			li.addEventListener('click', function () { pick(i); close(true); });
			list.appendChild(li);
			opts.push(li);
		});

		function show() {
			val.textContent = '';
			if (sel.selectedIndex >= 0) selText(val, sel.options[sel.selectedIndex].text);
			opts.forEach(function (li, i) { li.setAttribute('aria-selected', String(i === sel.selectedIndex)); });
		}
		function setActive(i, scroll) {
			active = Math.max(0, Math.min(opts.length - 1, i));
			opts.forEach(function (li, j) { li.classList.toggle('is-active', j === active); });
			btn.setAttribute('aria-activedescendant', opts[active].id);
			if (scroll) opts[active].scrollIntoView({ block: 'nearest' });
		}
		function open() {
			if (!list.hidden) return;
			list.hidden = false;
			btn.setAttribute('aria-expanded', 'true');
			/* open upward when the page has no room below and more room above */
			var r = btn.getBoundingClientRect();
			wrap.classList.toggle('sel--up', window.innerHeight - r.bottom < list.offsetHeight + 12 && r.top > window.innerHeight - r.bottom);
			setActive(sel.selectedIndex, true);
		}
		function close(focus) {
			if (list.hidden) return;
			list.hidden = true;
			btn.setAttribute('aria-expanded', 'false');
			btn.removeAttribute('aria-activedescendant');
			if (focus) btn.focus();
		}
		function pick(i) {
			if (i === sel.selectedIndex) return;
			sel.selectedIndex = i;
			show();
			sel.dispatchEvent(new Event('change', { bubbles: true }));
		}

		btn.addEventListener('click', function () { if (list.hidden) open(); else close(false); });
		btn.addEventListener('keydown', function (e) {
			var k = e.key;
			if (list.hidden) {
				if (k === 'ArrowDown' || k === 'ArrowUp' || k === 'Enter' || k === ' ') { e.preventDefault(); open(); }
				return;
			}
			if (k === 'ArrowDown') { e.preventDefault(); setActive(active + 1, true); }
			else if (k === 'ArrowUp') { e.preventDefault(); setActive(active - 1, true); }
			else if (k === 'Home') { e.preventDefault(); setActive(0, true); }
			else if (k === 'End') { e.preventDefault(); setActive(opts.length - 1, true); }
			else if (k === 'Enter' || k === ' ') { e.preventDefault(); pick(active); close(true); }
			else if (k === 'Escape') { e.preventDefault(); close(true); }
			else if (k === 'Tab') { pick(active); close(false); }
		});
		document.addEventListener('pointerdown', function (e) { if (!wrap.contains(e.target)) close(false); });
		sel.addEventListener('change', show);
		if (label) label.htmlFor = btn.id;
		sel.tabIndex = -1;
		sel.setAttribute('aria-hidden', 'true');
		wrap.insertBefore(btn, sel.nextSibling);
		wrap.appendChild(list);
		wrap.classList.add('is-on');
		show();
	});

	/* Calculators · .calc[data-calc="lot"|"dd"] (unit and empty texts from data-* on the box) */
	var num = function (el) { var v = parseFloat(el && el.value); return isFinite(v) ? v : 0; };
	var fmt = function (v, d) { return v.toLocaleString('th-TH', { minimumFractionDigits: d, maximumFractionDigits: d }); };
	$$('.calc[data-calc="lot"]').forEach(function (box) {
		var f = function (n) { return box.querySelector('[name="' + n + '"]'); };
		var out = function (n) { return box.querySelector('[data-out="' + n + '"]'); };
		var preset = f('preset');
		var pip = f('pip');
		var run = function () {
			var money = num(f('balance')) * num(f('risk')) / 100;
			var sl = num(f('sl'));
			var step = num(f('step')) || 0.01;
			var raw = sl > 0 && num(pip) > 0 ? money / (sl * num(pip)) : 0;
			var size = Math.floor(raw / step + 1e-9) * step;
			if (out('money')) out('money').textContent = fmt(money, 2) + ' ' + (box.getAttribute('data-money-unit') || 'USD');
			if (out('lot')) out('lot').textContent = fmt(size, 2) + ' ' + (box.getAttribute('data-lot-unit') || 'lot');
		};
		if (preset && pip) {
			preset.addEventListener('change', function () {
				var custom = preset.value === 'custom';
				if (!custom) pip.value = preset.value;
				pip.readOnly = !custom;
				run();
			});
		}
		box.addEventListener('input', run);
		run();
	});
	$$('.calc[data-calc="dd"]').forEach(function (box) {
		var f = function (n) { return box.querySelector('[name="' + n + '"]'); };
		var out = function (n) { return box.querySelector('[data-out="' + n + '"]'); };
		var run = function () {
			var pct = Math.min(Math.max(num(f('dd')), 0), 99.9);
			var cap = num(f('capital'));
			if (out('back')) out('back').textContent = fmt(pct / (100 - pct) * 100, 2) + '%';
			if (out('left')) out('left').textContent = cap > 0 ? fmt(cap * (1 - pct / 100), 2) + ' ' + (box.getAttribute('data-money-unit') || 'USD') : (box.getAttribute('data-empty') || '');
			if (out('bar')) out('bar').style.width = (100 - pct) + '%';
		};
		box.addEventListener('input', run);
		run();
	});

	/* Reading bar · .readbar i follows the whole page */
	var readbar = $('.readbar i');
	if (readbar) {
		var onRead = function () {
			var h = document.documentElement;
			var max = h.scrollHeight - h.clientHeight;
			readbar.style.width = (max > 0 ? h.scrollTop / max * 100 : 0) + '%';
		};
		window.addEventListener('scroll', onRead, { passive: true });
		onRead();
	}

	/* Share · copy link to clipboard */
	document.querySelectorAll('.share-copy').forEach(function (btn) {
		btn.addEventListener('click', function () {
			var url = btn.getAttribute('data-url') || window.location.href;
			var done = function () {
				btn.classList.add('is-copied');
				say(btn.getAttribute('data-done') || '');
				setTimeout(function () {
					btn.classList.remove('is-copied');
				}, 1600);
			};
			if (navigator.clipboard && navigator.clipboard.writeText) {
				navigator.clipboard.writeText(url).then(done).catch(function () {});
			} else {
				var ta = document.createElement('textarea');
				ta.value = url;
				ta.setAttribute('readonly', '');
				ta.style.position = 'absolute';
				ta.style.left = '-9999px';
				document.body.appendChild(ta);
				ta.select();
				try {
					document.execCommand('copy');
					done();
				} catch (e) {}
				document.body.removeChild(ta);
			}
		});
	});

	/* Load more posts (AJAX) */
	var loadMoreBtn = document.querySelector('.load-more-btn');
	if (loadMoreBtn && window.eabLoadMore) {
		var loadMoreLabel = loadMoreBtn.textContent.trim();
		var loadingText = loadMoreBtn.getAttribute('data-loading') || cfg.loadingText || '…';
		loadMoreBtn.addEventListener('click', function () {
			var page = parseInt(loadMoreBtn.getAttribute('data-page'), 10) || 1;
			var max = parseInt(loadMoreBtn.getAttribute('data-max'), 10) || 1;
			var next = page + 1;
			if (loadMoreBtn.classList.contains('is-loading') || next > max) {
				return;
			}
			loadMoreBtn.classList.add('is-loading');
			loadMoreBtn.textContent = loadingText;

			var data = new FormData();
			data.append('action', 'eab_load_more');
			data.append('nonce', window.eabLoadMore.nonce);
			data.append('page', next);
			data.append('query', loadMoreBtn.getAttribute('data-query') || '');

			fetch(window.eabLoadMore.ajaxUrl, {
				method: 'POST',
				body: data,
				credentials: 'same-origin'
			})
				.then(function (res) { return res.text(); })
				.then(function (html) {
					var grid = document.querySelector('.posts-grid, .posts');
					if (grid && html.trim()) {
						grid.insertAdjacentHTML('beforeend', html);
					}
					loadMoreBtn.setAttribute('data-page', String(next));
					loadMoreBtn.classList.remove('is-loading');
					loadMoreBtn.textContent = loadMoreLabel;
					if (next >= max) {
						var wrap = loadMoreBtn.closest('.load-more');
						if (wrap) {
							wrap.parentNode.removeChild(wrap);
						}
					}
				})
				.catch(function () {
					loadMoreBtn.classList.remove('is-loading');
					loadMoreBtn.textContent = loadMoreLabel;
				});
		});
	}

	/* นับคลิก LINE · แยก LINE OA (line_click) กับ OpenChat (openchat_click)
	   ส่งเฉพาะเมื่อ gtag / fbq ถูกโหลดแล้ว (หลังผู้ใช้ยินยอม ผ่าน consent.js) · Meta Contact เฉพาะ LINE OA
	   ตำแหน่งปุ่มอ่านจาก data-line-pos ถ้าไม่มีใช้ id ของกล่องที่ใกล้ที่สุด · data-line-pkg = ชื่อแพ็กเกจ (ถ้ามี) */
	document.addEventListener('click', function (e) {
		var link = e.target && e.target.closest ? e.target.closest('a[href]') : null;
		if (!link) {
			return;
		}
		var url;
		try {
			url = new URL(link.getAttribute('href') || '', window.location.href);
		} catch (err) {
			return;
		}
		var host = url.hostname.replace(/^www\./, '').toLowerCase();
		var isLine = host === 'line.me' || host === 'lin.ee' || host === 'liff.line.me' || host === 'page.line.me' || url.protocol === 'line:';
		if (!isLine) {
			return;
		}
		var isOpenChat = (host === 'line.me' && /^\/(?:R\/)?ti\/g2\//i.test(url.pathname)) || /openchat/i.test(link.getAttribute('data-line-pos') || '');
		var copy = link.cloneNode(true);
		Array.prototype.forEach.call(copy.querySelectorAll('.sr-only'), function (node) {
			node.parentNode.removeChild(node);
		});
		var box = link.parentNode && link.parentNode.closest ? link.parentNode.closest('[id]') : null;
		var payload = {
			link_url: url.href,
			link_text: (link.getAttribute('aria-label') || copy.textContent || 'LINE').replace(/\s+/g, ' ').trim(),
			line_pos: link.getAttribute('data-line-pos') || (box ? box.id : ''),
			page_path: window.location.pathname,
			page_title: document.title
		};
		if (link.getAttribute('data-line-pkg')) {
			payload.package_name = link.getAttribute('data-line-pkg');
		}
		/* เช็กความยินยอม ณ ตอนคลิก (consent.js โหลดหลังไฟล์นี้) · กันปลั๊กอินอื่นที่ประกาศ gtag/fbq ไว้ก่อนผู้ใช้ยินยอม */
		var consent = window.eabConsent && typeof window.eabConsent.has === 'function' ? window.eabConsent : null;
		if (typeof window.gtag === 'function' && (!consent || consent.has('analytics'))) {
			window.gtag('event', isOpenChat ? 'openchat_click' : 'line_click', payload);
		}
		if (!isOpenChat && typeof window.fbq === 'function' && (!consent || consent.has('marketing'))) {
			window.fbq('track', 'Contact', { content_name: payload.line_pos || 'line' });
		}
	});

	/* สารบัญ (หน้าคู่มือ) · ไฮไลต์หัวข้อที่กำลังอ่าน */
	var tocLinks = document.querySelectorAll('.longform-toc a[href^="#"], .toc a[href^="#"]');
	if (tocLinks.length && 'IntersectionObserver' in window) {
		var tocMap = {};
		tocLinks.forEach(function (a) { tocMap[decodeURIComponent(a.getAttribute('href').slice(1))] = a; });
		var tocObs = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (!entry.isIntersecting) return;
				tocLinks.forEach(function (a) { a.classList.remove('is-active'); });
				var link = tocMap[entry.target.id];
				if (link) link.classList.add('is-active');
			});
		}, { rootMargin: '-20% 0px -70% 0px' });
		Object.keys(tocMap).forEach(function (id) {
			var h = document.getElementById(id);
			if (h) tocObs.observe(h);
		});
	}

	/* Lightbox · a.lightbox (จัดกลุ่มด้วย data-group) */
	var lbLinks = document.querySelectorAll('a.lightbox');
	if (lbLinks.length) {
		var arrow = '<svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>';
		var lb = document.createElement('div');
		lb.className = 'lb';
		lb.setAttribute('role', 'dialog');
		lb.setAttribute('aria-modal', 'true');
		lb.setAttribute('aria-label', 'ภาพขนาดใหญ่');
		lb.innerHTML = '<figure><img alt=""><figcaption></figcaption></figure>' +
			'<button type="button" class="lb-close" aria-label="ปิด"><svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg></button>' +
			'<button type="button" class="lb-prev" aria-label="ภาพก่อนหน้า">' + arrow + '</button>' +
			'<button type="button" class="lb-next" aria-label="ภาพถัดไป">' + arrow + '</button>';
		document.body.appendChild(lb);
		var lbImg = lb.querySelector('img');
		var lbCap = lb.querySelector('figcaption');
		var group = [];
		var idx = 0;
		var lastFocus = null;
		var show = function () {
			var a = group[idx];
			lbImg.src = a.getAttribute('href');
			var img = a.querySelector('img');
			lbImg.alt = img ? img.alt : '';
			lbCap.textContent = a.getAttribute('data-caption') || '';
			var multi = group.length > 1;
			lb.querySelector('.lb-prev').hidden = !multi;
			lb.querySelector('.lb-next').hidden = !multi;
		};
		var close = function () {
			lb.classList.remove('is-open');
			document.body.style.overflow = '';
			if (lastFocus) lastFocus.focus();
		};
		lbLinks.forEach(function (a) {
			a.addEventListener('click', function (e) {
				e.preventDefault();
				var g = a.getAttribute('data-group');
				group = g ? Array.prototype.slice.call(document.querySelectorAll('a.lightbox[data-group="' + g + '"]')) : [a];
				idx = Math.max(0, group.indexOf(a));
				lastFocus = a;
				show();
				lb.classList.add('is-open');
				document.body.style.overflow = 'hidden';
				// รอให้ visibility เปลี่ยนก่อน แล้วค่อยย้ายโฟกัส (ปุ่มที่ยัง hidden รับโฟกัสไม่ได้)
				setTimeout(function () { lb.querySelector('.lb-close').focus(); }, 30);
			});
		});
		lb.addEventListener('click', function (e) {
			if (e.target === lb) close();
		});
		lb.querySelector('.lb-close').addEventListener('click', close);
		lb.querySelector('.lb-prev').addEventListener('click', function () { idx = (idx + group.length - 1) % group.length; show(); });
		lb.querySelector('.lb-next').addEventListener('click', function () { idx = (idx + 1) % group.length; show(); });
		document.addEventListener('keydown', function (e) {
			if (!lb.classList.contains('is-open')) return;
			if (e.key === 'Escape') close();
			if (e.key === 'Tab') {
				var btns = Array.prototype.filter.call(lb.querySelectorAll('button'), function (b) { return !b.hidden; });
				var at = btns.indexOf(document.activeElement);
				e.preventDefault();
				var nextBtn = btns[(at + (e.shiftKey ? btns.length - 1 : 1) + btns.length) % btns.length];
				if (nextBtn) nextBtn.focus();
			}
			if (e.key === 'ArrowRight' && group.length > 1) { idx = (idx + 1) % group.length; show(); }
			if (e.key === 'ArrowLeft' && group.length > 1) { idx = (idx + group.length - 1) % group.length; show(); }
		});
	}
})();
