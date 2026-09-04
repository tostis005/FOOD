(function () {
	'use strict';

	var COOKIE_NAME = 'quinnoa_cookie_consent';
	var banner = document.getElementById('quinnoa-cookie-banner');
	var settings = document.getElementById('quinnoa-cookie-settings');
	var analyticsToggle = document.getElementById('quinnoa-cookie-analytics');

	if (!banner || !settings || !analyticsToggle) {
		return;
	}

	function readConsent() {
		var match = document.cookie.match(new RegExp('(?:^|; )' + COOKIE_NAME + '=([^;]*)'));
		return match ? decodeURIComponent(match[1]) : '';
	}

	function writeConsent(value) {
		var secure = location.protocol === 'https:' ? '; Secure' : '';
		document.cookie = COOKIE_NAME + '=' + encodeURIComponent(value) + '; Max-Age=31536000; Path=/; SameSite=Lax' + secure;
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

	function applyChoice(value, isFreshChoice) {
		var granted = value === 'analytics';
		writeConsent(value);
		updateGoogleConsent(granted);
		closeAll();

		/* A consented event makes the newly granted Analytics session visible immediately. */
		if (granted && isFreshChoice && typeof window.gtag === 'function') {
			window.gtag('event', 'cookie_consent_granted', {
				event_category: 'consent',
				non_interaction: true
			});
		}
	}

	function openSettings() {
		analyticsToggle.checked = readConsent() === 'analytics';
		settings.hidden = false;
		document.documentElement.classList.add('quinnoa-cookie-settings-open');
		var heading = settings.querySelector('h2');
		if (heading) {
			heading.setAttribute('tabindex', '-1');
			heading.focus();
		}
	}

	var saved = readConsent();
	if (saved === 'analytics') {
		updateGoogleConsent(true);
		banner.hidden = true;
	} else if (saved === 'necessary') {
		updateGoogleConsent(false);
		banner.hidden = true;
	} else {
		banner.hidden = false;
	}

	document.querySelectorAll('[data-quinnoa-cookie-accept]').forEach(function (button) {
		button.addEventListener('click', function () {
			applyChoice('analytics', true);
		});
	});

	document.querySelectorAll('[data-quinnoa-cookie-reject]').forEach(function (button) {
		button.addEventListener('click', function () {
			applyChoice('necessary', false);
		});
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
			applyChoice(analyticsToggle.checked ? 'analytics' : 'necessary', analyticsToggle.checked);
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
