document.addEventListener('DOMContentLoaded', function () {
  const openButton = document.querySelector('.menu-toggle');
  const overlay = document.querySelector('.mobile-menu-overlay');
  const closeButton = document.querySelector('.mobile-menu-close');

  if (!openButton || !overlay || !closeButton) return;

  let lastFocused = null;
  const focusableSelector = 'a[href], button:not([disabled]), input:not([disabled]), [tabindex]:not([tabindex="-1"])';

  function openMenu() {
    lastFocused = document.activeElement;
    overlay.classList.add('is-open');
    overlay.setAttribute('aria-hidden', 'false');
    openButton.setAttribute('aria-expanded', 'true');
    document.body.classList.add('menu-open');
    window.requestAnimationFrame(function () {
      closeButton.focus();
    });
  }

  function closeMenu() {
    overlay.classList.remove('is-open');
    overlay.setAttribute('aria-hidden', 'true');
    openButton.setAttribute('aria-expanded', 'false');
    document.body.classList.remove('menu-open');

    if (lastFocused && typeof lastFocused.focus === 'function') {
      lastFocused.focus();
    }
  }

  openButton.addEventListener('click', openMenu);
  closeButton.addEventListener('click', closeMenu);

  overlay.querySelectorAll('a').forEach(function (link) {
    link.addEventListener('click', closeMenu);
  });

  document.addEventListener('keydown', function (event) {
    if (!overlay.classList.contains('is-open')) return;

    if (event.key === 'Escape') {
      event.preventDefault();
      closeMenu();
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

  window.addEventListener('resize', function () {
    if (window.innerWidth > 960 && overlay.classList.contains('is-open')) {
      closeMenu();
    }
  });
});

document.addEventListener('DOMContentLoaded', function () {
  const buttons = document.querySelectorAll('.article-share-button, .card-share-button');
  if (!buttons.length) return;

  function fallbackCopy(text) {
    const input = document.createElement('textarea');
    input.value = text;
    input.setAttribute('readonly', '');
    input.style.position = 'fixed';
    input.style.opacity = '0';
    document.body.appendChild(input);
    input.select();
    const copied = document.execCommand('copy');
    document.body.removeChild(input);
    return copied;
  }

  buttons.forEach(function (button) {
    button.addEventListener('click', async function () {
      const url = button.dataset.shareUrl || window.location.href;
      const title = button.dataset.shareTitle || document.title;
      const defaultLabel = button.dataset.shareLabel || 'Share';
      const copiedLabel = button.dataset.copyLabel || 'Copied';
      const label = button.querySelector('.article-share-label, .card-share-label');
      const container = button.closest('.article-share, .card-share');
      const status = container ? container.querySelector('.article-share-status, .card-share-status') : null;

      if (navigator.share) {
        try {
          await navigator.share({ title: title, url: url });
          return;
        } catch (error) {
          if (error && error.name === 'AbortError') return;
        }
      }

      let copied = false;
      if (navigator.clipboard && window.isSecureContext) {
        try {
          await navigator.clipboard.writeText(url);
          copied = true;
        } catch (error) {
          copied = false;
        }
      }
      if (!copied) copied = fallbackCopy(url);
      if (!copied) return;

      if (label) label.textContent = copiedLabel;
      if (status) status.textContent = copiedLabel;
      window.setTimeout(function () {
        if (label) label.textContent = defaultLabel;
        if (status) status.textContent = '';
      }, 1800);
    });
  });
});
