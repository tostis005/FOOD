document.addEventListener('DOMContentLoaded', function () {
  const isEnglish = document.documentElement.lang.toLowerCase().startsWith('en');

  const englishOptionFlag = document.querySelector('.language-option[hreflang="en"] .language-option-flag');
  if (englishOptionFlag) englishOptionFlag.textContent = '🇺🇸';

  if (isEnglish) {
    const currentFlag = document.querySelector('.language-current-flag');
    if (currentFlag) currentFlag.textContent = '🇺🇸';
  }

  const searchTitle = document.querySelector('.search-overlay-title');
  const searchInput = document.querySelector('#overlay-food-search');
  const heroSearch = document.querySelector('#food-search');
  const mobileSearch = document.querySelector('#mobile-food-search');

  if (searchTitle) searchTitle.textContent = isEnglish ? 'Search' : 'Buscar';
  if (searchInput) searchInput.setAttribute('placeholder', isEnglish ? 'Type your search…' : 'Escribe tu búsqueda…');
  if (heroSearch) heroSearch.setAttribute('placeholder', isEnglish ? 'Search Pometum…' : 'Busca en Pometum…');
  if (mobileSearch) mobileSearch.setAttribute('placeholder', isEnglish ? 'Search Pometum…' : 'Busca en Pometum…');

  const configs = [
    {
      openButton: document.querySelector('.language-toggle'),
      overlay: document.querySelector('.language-overlay'),
      closeButton: document.querySelector('.language-overlay-close'),
      bodyClass: 'language-overlay-open',
      initialFocus: function (overlay, closeButton) { return closeButton; }
    },
    {
      openButton: document.querySelector('.search-toggle'),
      overlay: document.querySelector('.search-overlay'),
      closeButton: document.querySelector('.search-overlay-close'),
      bodyClass: 'search-overlay-open',
      initialFocus: function (overlay) { return overlay.querySelector('input[type="search"]'); }
    }
  ].filter(function (config) {
    return config.openButton && config.overlay && config.closeButton;
  });

  if (!configs.length) return;

  const focusableSelector = 'a[href], button:not([disabled]), input:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])';
  let activeConfig = null;
  let lastFocused = null;

  function closeMobileMenu() {
    const menuOverlay = document.querySelector('.mobile-menu-overlay.is-open');
    const menuClose = menuOverlay ? menuOverlay.querySelector('.mobile-menu-close') : null;
    if (menuClose) menuClose.click();
  }

  function closeConfig(config, restoreFocus) {
    if (!config || !config.overlay.classList.contains('is-open')) return;
    config.overlay.classList.remove('is-open');
    config.overlay.setAttribute('aria-hidden', 'true');
    config.openButton.setAttribute('aria-expanded', 'false');
    document.body.classList.remove(config.bodyClass);
    if (activeConfig === config) activeConfig = null;

    if (restoreFocus && lastFocused && typeof lastFocused.focus === 'function') {
      lastFocused.focus();
    }
  }

  function closeOthers(current) {
    configs.forEach(function (config) {
      if (config !== current) closeConfig(config, false);
    });
  }

  function openConfig(config) {
    closeMobileMenu();
    closeOthers(config);
    lastFocused = document.activeElement;
    activeConfig = config;
    config.overlay.classList.add('is-open');
    config.overlay.setAttribute('aria-hidden', 'false');
    config.openButton.setAttribute('aria-expanded', 'true');
    document.body.classList.add(config.bodyClass);

    window.requestAnimationFrame(function () {
      const focusTarget = config.initialFocus ? config.initialFocus(config.overlay, config.closeButton) : config.closeButton;
      if (focusTarget && typeof focusTarget.focus === 'function') focusTarget.focus();
    });
  }

  configs.forEach(function (config) {
    config.openButton.addEventListener('click', function () { openConfig(config); });
    config.closeButton.addEventListener('click', function () { closeConfig(config, true); });

    config.overlay.querySelectorAll('a[href]').forEach(function (link) {
      link.addEventListener('click', function () { closeConfig(config, false); });
    });
  });

  document.addEventListener('keydown', function (event) {
    if (!activeConfig || !activeConfig.overlay.classList.contains('is-open')) return;

    if (event.key === 'Escape') {
      event.preventDefault();
      closeConfig(activeConfig, true);
      return;
    }

    if (event.key !== 'Tab') return;

    const focusable = Array.from(activeConfig.overlay.querySelectorAll(focusableSelector)).filter(function (element) {
      return element.offsetParent !== null;
    });

    if (!focusable.length) return;

    const first = focusable[0];
    const last = focusable[focusable.length - 1];

    if (event.shiftKey && document.activeElement === first) {
      event.preventDefault();
      last.focus();
    } else if (!event.shiftKey && document.activeElement === last) {
      event.preventDefault();
      first.focus();
    }
  });
});
