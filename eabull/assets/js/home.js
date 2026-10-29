/**
 * EA BULL · หน้าแรก (home.js)
 * โหลดหลัง main.js เฉพาะหน้าแรก · ทำงานเมื่อมี main.home-v3 (front-page.php)
 *
 * - FAQ: เปิดได้ทีละข้อในเบราว์เซอร์ที่ยังไม่รองรับ <details name> (เบราว์เซอร์ใหม่ทำเองอยู่แล้ว)
 * ไม่มี JS / ไฟล์นี้โหลดไม่ขึ้น = ทุกส่วนใช้งานได้ครบ (details เปิดปิดได้ตามปกติ)
 */
(function () {
	'use strict';

	var root = document.querySelector('.home-v3');
	if (!root) {
		return;
	}

	var questions = root.querySelectorAll('details.hm-q[name]');
	if (!questions.length || typeof window.HTMLDetailsElement === 'undefined' || 'name' in window.HTMLDetailsElement.prototype) {
		return;
	}

	Array.prototype.forEach.call(questions, function (item) {
		item.addEventListener('toggle', function () {
			if (!item.open) {
				return;
			}
			Array.prototype.forEach.call(questions, function (other) {
				if (other !== item && other.open && other.getAttribute('name') === item.getAttribute('name')) {
					other.open = false;
				}
			});
		});
	});
})();

/**
 * ส่วนเสริมหน้าแรก (inc/modules/homeplus.php)
 * - [data-hp-tabs] แท็บ Lite / Advance / Full: ไม่มี JS = เห็นทุกแผง · มี JS = แสดงแถบแท็บและทีละแผง (ลูกศรซ้ายขวาสลับได้)
 * - [data-hp-license] แตะสถานะ แล้วการ์ดจำลองเปลี่ยนสีและข้อความ
 * - [data-hp-ready] ติ๊กเช็กลิสต์ แล้ววงแหวนกับข้อความความคืบหน้าขยับ ครบทุกข้อ = แสดงปุ่ม LINE
 */
(function () {
	'use strict';

	var each = function (list, fn) {
		Array.prototype.forEach.call(list, fn);
	};

	each(document.querySelectorAll('[data-hp-tabs]'), function (box) {
		var bar = box.querySelector('[role="tablist"]');
		var tabs = box.querySelectorAll('[role="tab"]');
		var panels = box.querySelectorAll('[role="tabpanel"]');
		if (!bar || tabs.length < 2) {
			return;
		}
		var show = function (index, focus) {
			each(tabs, function (tab, i) {
				var on = i === index;
				tab.setAttribute('aria-selected', on ? 'true' : 'false');
				tab.tabIndex = on ? 0 : -1;
				if (on && focus) {
					tab.focus();
				}
			});
			each(panels, function (panel, i) {
				panel.hidden = i !== index;
				panel.classList.remove('is-in');
				if (i === index) {
					void panel.offsetWidth;
					panel.classList.add('is-in');
				}
			});
		};
		bar.hidden = false;
		each(tabs, function (tab, i) {
			tab.addEventListener('click', function () {
				show(i, false);
			});
			tab.addEventListener('keydown', function (e) {
				var next = e.key === 'ArrowRight' ? i + 1 : (e.key === 'ArrowLeft' ? i - 1 : null);
				if (next === null) {
					return;
				}
				e.preventDefault();
				show((next + tabs.length) % tabs.length, true);
			});
		});
		show(0, false);
	});

	each(document.querySelectorAll('[data-hp-license]'), function (box) {
		var card = box.querySelector('.hp-lcard');
		var status = box.querySelector('.hp-lcard-status');
		var buttons = box.querySelectorAll('.hp-state');
		if (!card || !buttons.length) {
			return;
		}
		each(buttons, function (btn) {
			btn.addEventListener('click', function () {
				each(buttons, function (other) {
					other.setAttribute('aria-pressed', other === btn ? 'true' : 'false');
				});
				card.setAttribute('data-tone', btn.getAttribute('data-tone') || 'green');
				if (status) {
					status.textContent = btn.getAttribute('data-name') || '';
				}
			});
		});
	});

	each(document.querySelectorAll('[data-hp-ready]'), function (box) {
		var boxes = box.querySelectorAll('.hp-ready-box');
		var ring = box.querySelector('.hp-ring');
		var num = box.querySelector('.hp-ring-num b');
		var text = box.querySelector('.hp-ready-status');
		var done = box.querySelector('.hp-ready-done');
		var tpl = box.getAttribute('data-progress') || '';
		if (!boxes.length) {
			return;
		}
		var update = function () {
			var n = 0;
			each(boxes, function (b) {
				if (b.checked) {
					n += 1;
				}
			});
			var total = boxes.length;
			if (ring) {
				ring.style.setProperty('--p', String(Math.round((n / total) * 100)));
				ring.classList.toggle('is-full', n === total);
			}
			if (num) {
				num.textContent = String(n);
			}
			if (text) {
				text.textContent = tpl.replace('{n}', String(n)).replace('{total}', String(total));
			}
			if (done) {
				done.hidden = n !== total;
			}
		};
		each(boxes, function (b) {
			b.addEventListener('change', update);
		});
		update();
	});
})();
