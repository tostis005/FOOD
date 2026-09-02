document.addEventListener('DOMContentLoaded', function () {
  const openButton = document.querySelector('.language-toggle');
  const overlay = document.querySelector('.language-overlay');
  const closeButton = document.querySelector('.language-overlay-close');

  if (!openButton || !overlay || !closeButton) return;

  let lastFocused = null;
  const focusableSelector = 'a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"])';

  function setBodyState(open) {
    document.body.classList.toggle('language-overlay-open', open);
  }

  function openOverlay() {
    const menuOverlay = document.querySelector('.mobile-menu-overlay.is-open');
    const menuClose = menuOverlay ? menuOverlay.querySelector('.mobile-menu-close') : null;
    if (menuClose) menuClose.click();

    lastFocused = document.activeElement;
    overlay.classList.add('is-open');
    overlay.setAttribute('aria-hidden', 'false');
    openButton.setAttribute('aria-expanded', 'true');
    setBodyState(true);

    window.requestAnimationFrame(function () {
      closeButton.focus();
    });
  }

  function closeOverlay() {
    overlay.classList.remove('is-open');
    overlay.setAttribute('aria-hidden', 'true');
    openButton.setAttribute('aria-expanded', 'false');
    setBodyState(false);

    if (lastFocused && typeof lastFocused.focus === 'function') {
      lastFocused.focus();
    }
  }

  openButton.addEventListener('click', openOverlay);
  closeButton.addEventListener('click', closeOverlay);

  overlay.querySelectorAll('a[href]').forEach(function (link) {
    link.addEventListener('click', function () {
      overlay.classList.remove('is-open');
      overlay.setAttribute('aria-hidden', 'true');
      openButton.setAttribute('aria-expanded', 'false');
      setBodyState(false);
    });
  });

  document.addEventListener('keydown', function (event) {
    if (!overlay.classList.contains('is-open')) return;

    if (event.key === 'Escape') {
      event.preventDefault();
      closeOverlay();
      return;
    }

    if (event.key !== 'Tab') return;

    const focusable = Array.from(overlay.querySelectorAll(focusableSelector)).filter(function (element) {
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
