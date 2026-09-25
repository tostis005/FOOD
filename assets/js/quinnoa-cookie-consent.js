(function () {
	'use strict';

	var COOKIE_NAME = 'quinnoa_cookie_consent_v2';
	var LEGACY_COOKIE_NAME = 'quinnoa_cookie_consent';
	var banner = document.getElementById('quinnoa-cookie-banner');
	var settings = document.getElementById('quinnoa-cookie-settings');
	var analyticsToggle = document.getElementById('quinnoa-cookie-analytics');
	var advertisingToggle = document.getElementById('quinnoa-cookie-advertising');

	if (!banner || !settings || !analyticsToggle || !advertisingToggle) {
		return;
	}

	function readCookie(name) {
		var match = document.cookie.match(new RegExp('(?:^|; )' + name + '=([^;]*)'));
		return match ? decodeURIComponent(match[1]) : '';
	}

	function readConsent() {
		return readCookie(COOKIE_NAME);
	}

	function writeConsent(value) {
		var secure = location.protocol === 'https:' ? '; Secure' : '';
		document.cookie = COOKIE_NAME + '=' + encodeURIComponent(value) + '; Max-Age=31536000; Path=/; SameSite=Lax' + secure;
	}

	function hasAnalytics(value) {
		return value === 'analytics' || value === 'all';
	}

	function hasAdvertising(value) {
		return value === 'ads' || value === 'all';
	}

	function updateGoogleConsent(analyticsGranted) {
		if (typeof window.gtag !== 'function') {
			return;
		}
		window.gtag('consent', 'update', {
			'ad_storage': 'denied',
			'ad_user_data': 'denied',
			'ad_personalization': 'denied',
			'analytics_storage': analyticsGranted ? 'granted' : 'denied'
		});
	}

	function closeAll() {
		banner.hidden = true;
		settings.hidden = true;
		document.documentElement.classList.remove('quinnoa-cookie-settings-open');
	}

	function valueFromToggles() {
		if (analyticsToggle.checked && advertisingToggle.checked) return 'all';
		if (analyticsToggle.checked) return 'analytics';
		if (advertisingToggle.checked) return 'ads';
		return 'necessary';
	}

	function applyChoice(value, isFreshChoice) {
		var previous = readConsent();
		var analyticsGranted = hasAnalytics(value);
		var advertisingChanged = hasAdvertising(previous) !== hasAdvertising(value);

		writeConsent(value);
		updateGoogleConsent(analyticsGranted);
		closeAll();

		if (analyticsGranted && isFreshChoice && typeof window.gtag === 'function') {
			window.gtag('event', 'cookie_consent_granted', {
				event_category: 'consent',
				non_interaction: true
			});
		}

		if (advertisingChanged) window.location.reload();
	}

	function openSettings() {
		var saved = readConsent();
		if (!saved && readCookie(LEGACY_COOKIE_NAME) === 'analytics') {
			analyticsToggle.checked = true;
			advertisingToggle.checked = false;
		} else {
			analyticsToggle.checked = hasAnalytics(saved);
			advertisingToggle.checked = hasAdvertising(saved);
		}
		settings.hidden = false;
		document.documentElement.classList.add('quinnoa-cookie-settings-open');
		var heading = settings.querySelector('h2');
		if (heading) {
			heading.setAttribute('tabindex', '-1');
			heading.focus();
		}
	}

	var saved = readConsent();
	if (saved === 'all' || saved === 'analytics' || saved === 'ads' || saved === 'necessary') {
		updateGoogleConsent(hasAnalytics(saved));
		banner.hidden = true;
	} else {
		updateGoogleConsent(readCookie(LEGACY_COOKIE_NAME) === 'analytics');
		banner.hidden = false;
	}

	document.querySelectorAll('[data-quinnoa-cookie-accept]').forEach(function (button) {
		button.addEventListener('click', function () { applyChoice('all', true); });
	});
	document.querySelectorAll('[data-quinnoa-cookie-reject]').forEach(function (button) {
		button.addEventListener('click', function () { applyChoice('necessary', false); });
	});
	document.querySelectorAll('[data-quinnoa-cookie-settings]').forEach(function (button) {
		button.addEventListener('click', openSettings);
	});
	document.querySelectorAll('[data-quinnoa-cookie-close]').forEach(function (button) {
		button.addEventListener('click', function () {
			settings.hidden = true;
			document.documentElement.classList.remove('quinnoa-cookie-settings-open');
		});
	});
	document.querySelectorAll('[data-quinnoa-cookie-save]').forEach(function (button) {
		button.addEventListener('click', function () {
			var value = valueFromToggles();
			applyChoice(value, hasAnalytics(value));
		});
	});
	settings.addEventListener('click', function (event) {
		if (event.target === settings) {
			settings.hidden = true;
			document.documentElement.classList.remove('quinnoa-cookie-settings-open');
		}
	});
	document.addEventListener('keydown', function (event) {
		if (event.key === 'Escape' && !settings.hidden) {
			settings.hidden = true;
			document.documentElement.classList.remove('quinnoa-cookie-settings-open');
		}
	});
})();
