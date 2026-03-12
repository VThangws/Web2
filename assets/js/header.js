document.addEventListener('DOMContentLoaded', () => {
  // 1) Toggle mobile menu
  const menuToggle = document.querySelector('.menu-toggle');
  const menuClose = document.querySelector('.menu-close');
  const menuHeader = document.querySelector('.menu_header');
  menuToggle?.addEventListener('click', () => menuHeader.classList.toggle('active'));
  menuClose?.addEventListener('click', () => menuHeader.classList.remove('active'));

  // 2) Xử lý logout
  const params = new URLSearchParams(window.location.search);
  if (params.get('loggedout') === '1') {
    localStorage.removeItem('cart');
    if (typeof updateCartCount === 'function') {
      updateCartCount();
    } else {
      const b = document.getElementById('cart-count-badge');
      if (b) b.textContent = '0';
    }
    params.delete('loggedout');
    history.replaceState(null, '', window.location.pathname + (params.toString() ? '?' + params.toString() : ''));
  }

  // 3) Hiệu ứng scroll reveal cho các section và item
  const revealSections = document.querySelectorAll('.reveal-section');
  const revealItems = document.querySelectorAll('.reveal-item');

  if ('IntersectionObserver' in window) {
    const sectionObserver = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          sectionObserver.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.15
    });

    const itemObserver = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          itemObserver.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.2
    });

    revealSections.forEach((sec) => sectionObserver.observe(sec));
    revealItems.forEach((it) => itemObserver.observe(it));
  } else {
    // Fallback: nếu trình duyệt không hỗ trợ thì hiện luôn
    revealSections.forEach((sec) => sec.classList.add('visible'));
    revealItems.forEach((it) => it.classList.add('visible'));
  }
});
