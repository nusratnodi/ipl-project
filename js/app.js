// JS is used only for UI animations and alerts.
// All form submissions and data handling go through PHP.

document.addEventListener('DOMContentLoaded', () => {

  // Staggered fade-in entrance for cards
  document.querySelectorAll('.js-fade-in').forEach((el, i) => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(14px)';
    requestAnimationFrame(() => {
      el.style.transition = 'opacity 0.55s ease, transform 0.55s ease';
      el.style.transitionDelay = (i * 90) + 'ms';
      el.style.opacity = '1';
      el.style.transform = 'translateY(0)';
    });
  });

  // Delete confirmation
  document.querySelectorAll('form.js-delete').forEach((form) => {
    form.addEventListener('submit', (e) => {
      if (!confirm('Delete this attendance record?\nThis action cannot be undone.')) {
        e.preventDefault();
      }
    });
  });

  // Shake form fields on HTML5 validation failure
  document.querySelectorAll('form.js-form').forEach((form) => {
    form.addEventListener(
      'invalid',
      (e) => {
        const el = e.target;
        el.classList.remove('shake');
        // force reflow so the animation can replay
        void el.offsetWidth;
        el.classList.add('shake');
      },
      true
    );
  });

  // Smooth scroll to top when entering edit mode (?edit=...)
  if (new URLSearchParams(window.location.search).has('edit')) {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }
});
