/**
 * EA BULL · PDPA consent engine · markup from inc/modules/consent.php
 *
 * - The card HTML is the same for every visitor; this script reads the consent cookie in the
 *   browser and decides whether to show the card.
 * - gtag.js and fbevents.js are added only after their category is allowed.
 *   The X button and Esc mean "not answered yet" (asked again on the next visit).
 * - Cookie eab_consent = v<version>.a<0|1|->.m<0|1|->.t<unix> · 365 days · SameSite=Lax.
 *   "-" = the category had no service when the visitor answered; adding one later asks again.
 * - Consent Mode v2: <head> already declared every signal denied (consent.php); an update is
 *   sent when the visitor allows a category, then gtag.js is loaded.
 * - Turning a category off: ga-disable, consent update denied, fbq('consent','revoke'),
 *   service cookies removed, page reloaded.
 * - Card hooks: #cookie-settings[data-view] · [data-show="intro|prefs"] · [data-when="none|optional"]
 *   · [data-cat] rows with an input named after the category ·
 *   [data-consent="accept|reject|ok|save|prefs|back|close"] · #consent-title (focus target).
 * - Openers outside the card (TRIGGERS): a[href$="#cookie-settings"], .cookie-reopen, [data-cookie-settings].
 * - API: window.eabConsent.has('analytics'|'marketing') / .get() / .open() · event 'eab:consent' on document.
 */
(function () {
	'use strict';

	var cfg = window.eabConsentConfig || {};
	var COOKIE = 'eab_consent';
	var DISMISS_KEY = 'eabConsentDismissed';
	var MAX_AGE = 365 * 24 * 60 * 60;
	var TRIGGERS = 'a[href$="#cookie-settings"], .cookie-reopen, [data-cookie-settings]';

	var version = /^\d+$/.test(String(cfg.version || '')) ? String(cfg.version) : '1';
	var gaId = /^G-[A-Z0-9]{4,20}$/.test(String(cfg.ga || '')) ? String(cfg.ga) : '';
	var pixelId = /^\d{10,20}$/.test(String(cfg.pixel || '')) ? String(cfg.pixel) : '';
	var tools = { analytics: !!gaId, marketing: !!pixelId };
	var hasOptional = tools.analytics || tools.marketing;
	var loaded = { analytics: false, marketing: false };
	var current = null;
	var box = null;
	var bound = false;
	var trigger = null;

	/* ---------------- Consent cookie ---------------- */

	function read() {
		var m = document.cookie.match(/(?:^|;\s*)eab_consent=([^;]+)/);
		var p = m ? /^v(\d+)\.a([01-])\.m([01-])\.t(\d+)$/.exec(m[1]) : null;
		if (!p || p[1] !== version) {
			return null;
		}
		return {
			analytics: p[2] === '1',
			marketing: p[3] === '1',
			asked: { analytics: p[2] !== '-', marketing: p[3] !== '-' }
		};
	}

	function write(choice) {
		function flag(cat) {
			return tools[cat] ? (choice[cat] ? '1' : '0') : '-';
		}
		var value = 'v' + version + '.a' + flag('analytics') + '.m' + flag('marketing') + '.t' + Math.floor(Date.now() / 1000);
		document.cookie = COOKIE + '=' + value + '; max-age=' + MAX_AGE + '; path=/; SameSite=Lax' + (window.location.protocol === 'https:' ? '; Secure' : '');
	}

	/* No answer yet, or a service was added that the visitor was never asked about */
	function needsAnswer(stored) {
		if (!stored) {
			return true;
		}
		return (tools.analytics && !stored.asked.analytics) || (tools.marketing && !stored.asked.marketing);
	}

	function dismissedThisVisit() {
		try {
			return window.sessionStorage.getItem(DISMISS_KEY) === '1';
		} catch (err) {
			return false;
		}
	}

	function rememberDismiss() {
		try {
			window.sessionStorage.setItem(DISMISS_KEY, '1');
		} catch (err) {}
	}

	function forgetDismiss() {
		try {
			window.sessionStorage.removeItem(DISMISS_KEY);
		} catch (err) {}
	}

	/* Remove cookies by name on this host and every parent domain (GA and Pixel often use .domain) */
	function clearCookies(pattern) {
		var parts = window.location.hostname.split('.');
		var domains = [''];
		for (var i = 0; i < parts.length - 1; i++) {
			var d = parts.slice(i).join('.');
			domains.push(d, '.' + d);
		}
		document.cookie.split(';').forEach(function (part) {
			var name = part.split('=')[0].trim();
			if (!name || !pattern.test(name)) {
				return;
			}
			domains.forEach(function (domain) {
				document.cookie = name + '=; max-age=0; path=/' + (domain ? '; domain=' + domain : '');
			});
		});
	}

	/* ---------------- Tracking tags ---------------- */

	function signals(choice) {
		var ads = choice.marketing ? 'granted' : 'denied';
		return {
			analytics_storage: choice.analytics ? 'granted' : 'denied',
			ad_storage: ads,
			ad_user_data: ads,
			ad_personalization: ads
		};
	}

	function loadAnalytics(choice) {
		if (loaded.analytics || !gaId) {
			return;
		}
		loaded.analytics = true;
		window['ga-disable-' + gaId] = false;
		window.dataLayer = window.dataLayer || [];
		if (typeof window.gtag !== 'function') {
			window.gtag = function () {
				window.dataLayer.push(arguments);
			};
		}
		if (!window.eabConsentDefault) {
			window.gtag('consent', 'default', signals({ analytics: false, marketing: false }));
		}
		window.gtag('consent', 'update', signals(choice));
		window.gtag('js', new Date());
		window.gtag('config', gaId);
		var tag = document.createElement('script');
		tag.async = true;
		tag.src = 'https://www.googletagmanager.com/gtag/js?id=' + encodeURIComponent(gaId);
		document.head.appendChild(tag);
	}

	function loadMarketing() {
		if (loaded.marketing || !pixelId) {
			return;
		}
		loaded.marketing = true;
		if (typeof window.fbq !== 'function') {
			var n = function () {
				if (n.callMethod) {
					n.callMethod.apply(n, arguments);
				} else {
					n.queue.push(arguments);
				}
			};
			window.fbq = n;
			if (!window._fbq) {
				window._fbq = n;
			}
			n.push = n;
			n.loaded = true;
			n.version = '2.0';
			n.queue = [];
			var tag = document.createElement('script');
			tag.async = true;
			tag.src = 'https://connect.facebook.net/en_US/fbevents.js';
			document.head.appendChild(tag);
		}
		window.fbq('consent', 'grant');
		window.fbq('init', pixelId);
		window.fbq('track', 'PageView');
	}

	function announce() {
		var detail = { analytics: !!(current && current.analytics), marketing: !!(current && current.marketing) };
		try {
			document.dispatchEvent(new CustomEvent('eab:consent', { detail: detail }));
		} catch (err) {}
	}

	function apply(choice) {
		var gaWasLoaded = loaded.analytics;
		current = choice;
		if (choice.analytics) {
			loadAnalytics(choice);
		}
		if (choice.marketing) {
			loadMarketing();
		}
		if (gaWasLoaded && typeof window.gtag === 'function') {
			window.gtag('consent', 'update', signals(choice));
		}
		announce();
	}

	function save(choice) {
		choice = {
			analytics: tools.analytics && !!choice.analytics,
			marketing: tools.marketing && !!choice.marketing,
			asked: { analytics: tools.analytics, marketing: tools.marketing }
		};
		var revoke = (loaded.analytics && !choice.analytics) || (loaded.marketing && !choice.marketing);

		write(choice);
		forgetDismiss();

		if (!choice.analytics) {
			if (gaId) {
				window['ga-disable-' + gaId] = true;
			}
			clearCookies(/^(_ga(_.+)?|_gid|_gat(_.+)?)$/);
		}
		if (!choice.marketing) {
			if (loaded.marketing && typeof window.fbq === 'function') {
				window.fbq('consent', 'revoke');
			}
			clearCookies(/^(_fbp|_fbc|_gcl_.+)$/);
		}

		if (revoke) {
			/* Tags already on the page cannot be unloaded: send the update, then reload without them */
			if (loaded.analytics && typeof window.gtag === 'function') {
				window.gtag('consent', 'update', signals(choice));
			}
			current = choice;
			announce();
			window.location.reload();
			return;
		}

		apply(choice);
		hide();
	}

	/* ---------------- Card ---------------- */

	function each(selector, fn) {
		if (!box) {
			return;
		}
		Array.prototype.forEach.call(box.querySelectorAll(selector), fn);
	}

	function title() {
		return box ? box.querySelector('#consent-title') : null;
	}

	function focusTitle() {
		var el = title();
		if (el) {
			el.focus();
		}
	}

	function view(name) {
		var mode = hasOptional ? 'optional' : 'none';
		box.setAttribute('data-view', name);
		each('[data-show], [data-when]', function (el) {
			var show = el.getAttribute('data-show');
			var when = el.getAttribute('data-when');
			el.hidden = !!((show && show !== name) || (when && when !== mode));
		});
		each('[data-cat]', function (el) {
			var cat = el.getAttribute('data-cat');
			el.hidden = !tools[cat];
			var input = el.querySelector('input');
			if (input) {
				input.checked = !!(current && current[cat]);
			}
		});
	}

	function show(name, focus) {
		if (!ensureBox()) {
			return;
		}
		view(name);
		box.hidden = false;
		if (focus) {
			focusTitle();
		}
	}

	function hide() {
		if (!box) {
			return;
		}
		var focusInside = box.contains(document.activeElement);
		box.hidden = true;
		if (trigger && document.contains(trigger)) {
			trigger.focus();
		} else if (focusInside && document.activeElement && document.activeElement.blur) {
			document.activeElement.blur();
		}
		trigger = null;
	}

	function dismiss() {
		if (needsAnswer(read())) {
			rememberDismiss();
		}
		hide();
	}

	function onBoxClick(e) {
		var btn = e.target && e.target.closest ? e.target.closest('[data-consent]') : null;
		if (!btn || !box.contains(btn)) {
			return;
		}
		var action = btn.getAttribute('data-consent');
		if (action === 'accept') {
			save({ analytics: true, marketing: true });
		} else if (action === 'reject' || action === 'ok') {
			save({ analytics: false, marketing: false });
		} else if (action === 'save') {
			var picked = {};
			each('[data-cat] input', function (input) {
				picked[input.name] = input.checked;
			});
			save(picked);
		} else if (action === 'prefs' || action === 'back') {
			view(action === 'prefs' ? 'prefs' : 'intro');
			focusTitle();
		} else if (action === 'close') {
			dismiss();
		}
	}

	function ensureBox() {
		if (!box) {
			box = document.getElementById('cookie-settings');
		}
		if (box && !bound) {
			bound = true;
			box.addEventListener('click', onBoxClick);
			box.addEventListener('keydown', function (e) {
				if (e.key === 'Escape' || e.key === 'Esc') {
					dismiss();
				}
			});
		}
		return box;
	}

	function init() {
		if (!ensureBox()) {
			return;
		}

		if ((hasOptional || cfg.force) && needsAnswer(read()) && !dismissedThisVisit()) {
			show('intro', false);
		}

		/* Openers outside the card (footer, /go, privacy policy text) */
		document.addEventListener('click', function (e) {
			var el = e.target && e.target.closest ? e.target.closest(TRIGGERS) : null;
			if (!el || box.contains(el)) {
				return;
			}
			e.preventDefault();
			trigger = el;
			show('prefs', true);
		});

		function fromHash() {
			if (window.location.hash === '#cookie-settings') {
				show('prefs', true);
			}
		}
		fromHash();
		/* Opened from a URL with #cookie-settings: the browser scrolls to the fragment after the DOM is
		   ready and takes focus away, so focus the card title again on load */
		if (window.location.hash === '#cookie-settings' && document.readyState !== 'complete') {
			window.addEventListener('load', function () {
				var el = title();
				if (!box.hidden && el && !box.contains(document.activeElement)) {
					el.focus();
				}
			}, { once: true });
		}
		window.addEventListener('hashchange', fromHash);
	}

	/* ---------------- Start ---------------- */

	/* Apply a stored answer at once (no need to wait for the DOM) so allowed tags load early */
	var stored = read();
	if (stored) {
		apply(stored);
	}

	window.eabConsent = {
		version: version,
		tools: { analytics: tools.analytics, marketing: tools.marketing },
		has: function (cat) {
			if (cat === 'necessary') {
				return true;
			}
			return !!(current && (cat === 'analytics' || cat === 'marketing') && current[cat]);
		},
		get: function () {
			return current ? { analytics: !!current.analytics, marketing: !!current.marketing } : null;
		},
		open: function (name) {
			show(name === 'intro' ? 'intro' : 'prefs', true);
		}
	};

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
