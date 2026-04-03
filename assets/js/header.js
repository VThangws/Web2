// JS chỉ dành cho header: menu, logout, thanh tìm kiếm

document.addEventListener('DOMContentLoaded', () => {
  // 1) Toggle mobile menu (nếu có trong giao diện mobile)
  const menuToggle = document.querySelector('.menu-toggle');
  const menuClose = document.querySelector('.menu-close');
  const menuHeader = document.querySelector('.menu_header');
  if (menuToggle && menuHeader) {
    menuToggle.addEventListener('click', () => menuHeader.classList.toggle('active'));
  }
  if (menuClose && menuHeader) {
    menuClose.addEventListener('click', () => menuHeader.classList.remove('active'));
  }

  // 2) Xử lý logout: clear cart + cập nhật badge nếu có
  const params = new URLSearchParams(window.location.search);
  if (params.get('loggedout') === '1') {
    try {
      localStorage.removeItem('cart');
    } catch (e) {
      console.warn('Cannot access localStorage', e);
    }
    if (typeof updateCartCount === 'function') {
      updateCartCount();
    } else {
      const b = document.getElementById('cart-count-badge');
      if (b) b.textContent = '0';
    }
    params.delete('loggedout');
    history.replaceState(null, '', window.location.pathname + (params.toString() ? '?' + params.toString() : ''));
  }

  // 3) Thanh tìm kiếm header kiểu overlay (Apple style)
  const openSearchBtn = document.getElementById('openSearch');
  const searchBarHeader = document.getElementById('searchBarHeader');
  const searchInputHeader = document.getElementById('searchInputHeader');
  const btnSearchHeader = document.getElementById('btnSearchHeader');

  if (openSearchBtn && searchBarHeader) {
    openSearchBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      const isOpen = searchBarHeader.classList.toggle('open');
      if (isOpen && searchInputHeader) {
        setTimeout(() => searchInputHeader.focus(), 200);
      }
    });
  }

  // Đóng search khi click ra ngoài
  document.addEventListener('click', (e) => {
    if (!searchBarHeader || !openSearchBtn) return;
    if (!searchBarHeader.contains(e.target) && !openSearchBtn.contains(e.target)) {
      searchBarHeader.classList.remove('open');
    }
  });

  // Đóng search khi nhấn Esc
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && searchBarHeader) {
      searchBarHeader.classList.remove('open');
    }
  });

  // 4) Xử lý submit tìm kiếm (chuyển qua trang books với tham số search)
  function doHeaderSearch() {
    if (!searchInputHeader) return;
    const keyword = searchInputHeader.value.trim();
    if (keyword) {
      const url = '/index.php?page=books&search=' + encodeURIComponent(keyword);
      window.location.href = url;
    }
  }

  if (btnSearchHeader) {
    btnSearchHeader.addEventListener('click', (e) => {
      e.preventDefault();
      doHeaderSearch();
    });
  }

  if (searchInputHeader) {
    searchInputHeader.addEventListener('keypress', (e) => {
      if (e.key === 'Enter') {
        e.preventDefault();
        doHeaderSearch();
      }
    });
  }

  // === Gợi ý tìm kiếm (autocomplete) khi gõ ===
  if (searchInputHeader) {
    // Tạo container kết quả nếu chưa có
    let resultBox = document.getElementById('search-suggest-box');
    if (!resultBox) {
      resultBox = document.createElement('div');
      resultBox.id = 'search-suggest-box';
      resultBox.className = 'search-suggest-box';
      searchBarHeader.parentNode.insertBefore(resultBox, searchBarHeader.nextSibling);
    }

    let typingTimer = null;
    const TYPING_DELAY = 250; // ms

    function clearResults() {
      resultBox.innerHTML = '';
      resultBox.classList.remove('show');
    }

    function renderResults(data, keyword) {
      if (!Array.isArray(data) || data.length === 0) {
        clearResults();
        return;
      }

      const safeKeyword = keyword.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
      const re = new RegExp(safeKeyword, 'ig');

      const itemsHtml = data.map(item => {
        const title = item.tensach || '';
        const price = item.dongia || 0;
        const img = item.anhbia || '';
        const madausach = item.madausach;

        const highlighted = safeKeyword
          ? title.replace(re, m => `<mark>${m}</mark>`)
          : title;

        return `
          <a href="/index.php?page=book_detail&madausach=${encodeURIComponent(madausach)}" class="ss-item">
            <div class="ss-thumb">
              ${img
            ? `<img src="/assets/img/books/${img}" alt="${title}">`
            : `<img src="/assets/img/categories/booknew.png" alt="${title}">`}
            </div>
            <div class="ss-info">
              <div class="ss-title">${highlighted}</div>
              <div class="ss-price">${Number(price).toLocaleString('vi-VN')}đ</div>
            </div>
          </a>`;
      }).join('');

      resultBox.innerHTML = `<div class="ss-list">${itemsHtml}</div>`;
      resultBox.classList.add('show');
    }

    async function fetchSuggest(keyword) {
      const q = keyword.trim();
      if (!q) {
        clearResults();
        return;
      }
      try {
        const resp = await fetch(`/ajax/search_suggest.php?q=${encodeURIComponent(q)}`, {
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        if (!resp.ok) return;
        const data = await resp.json();
        renderResults(data, q);
      } catch (err) {
        console.error('Search suggest error:', err);
      }
    }

    searchInputHeader.addEventListener('input', () => {
      clearTimeout(typingTimer);
      const val = searchInputHeader.value;
      if (!val.trim()) {
        clearResults();
        return;
      }
      typingTimer = setTimeout(() => fetchSuggest(val), TYPING_DELAY);
    });

    // Ẩn box khi blur nhẹ (cho phép click vào item)
    document.addEventListener('click', (e) => {
      if (!resultBox) return;
      if (!resultBox.contains(e.target) && e.target !== searchInputHeader) {
        clearResults();
      }
    });
  }

  // 5) Hiệu ứng placeholder typing: xóa/gõ từng ký tự với nhiều câu gợi ý
  if (searchInputHeader) {
    const messages = [
      'Cách Tạo Video Triệu View',
      'Chữ, Văn Quốc Ngữ',
      'Tự truyện David Beckham',
      'Khỏe Đẹp Từ Gốc',
      'Ánh Sao Bên Tôi',
      'Bạn Là Ai Và Làm Thế Nào Để Sống Tốt Hơn',
      'Nihongo So-matome N4 Kanji',
      '1500 Từ Vựng Dành Cho Kỳ Thi Năng Lực Nhật Ngữ N4',
      'Thuyết Trình Tiếng Anh',
      'Chinh Phục Công Thức Viết Đoạn Văn Và Bài Văn Nghị Luận Văn Học'
    ];

    let msgIndex = 0;
    let charIndex = 0;
    let deleting = false;

    function updatePlaceholder() {
      const current = messages[msgIndex];
      const currentText = searchInputHeader.getAttribute('placeholder') || '';

      if (!deleting) {
        const nextText = current.slice(0, charIndex + 1);
        searchInputHeader.setAttribute('placeholder', nextText);
        charIndex++;
        if (charIndex === current.length) {
          deleting = true;
          setTimeout(updatePlaceholder, 1600);
          return;
        }
      } else {
        const nextLength = Math.max(0, currentText.length - 1);
        const nextText = currentText.slice(0, nextLength);
        searchInputHeader.setAttribute('placeholder', nextText);
        if (nextLength === 0) {
          deleting = false;
          charIndex = 0;
          msgIndex = (msgIndex + 1) % messages.length;
        }
      }

      const delay = deleting ? 40 : 70;
      setTimeout(updatePlaceholder, delay);
    }

    setTimeout(updatePlaceholder, 800);
  }

  // LƯU Ý: Không thêm bất kỳ xử lý nào cho .reveal-section, .reveal-item, hay hiệu ứng của trang home ở đây.
  // Mọi animation, scroll effect, typing effect của home đã chuyển sang assets/js/home.js để hai file không ảnh hưởng nhau.
});
// Thêm vào cuối file header.js
document.addEventListener('DOMContentLoaded', () => {
  const bookContainer = document.getElementById('main-book-container');

  function loadCategory(loaiSlug) {
    // Nếu không tìm thấy container (đang ở trang chủ/chi tiết), chuyển hướng link bình thường
    if (!bookContainer) {
      window.location.href = `/index.php?page=books&loai=${loaiSlug}`;
      return;
    }

    // Nếu đang ở trang danh sách sách, thực hiện gọi AJAX
    bookContainer.style.opacity = '0.5';

    fetch(`/ajax/books_filter.php?loai=${loaiSlug}&page=1`)
      .then(res => res.json())
      // Tìm đoạn fetch gọi đến books_filter.php và sửa phần xử lý kết quả:
      .then(data => {
        // 1. Đổ danh sách sách vào vùng nội dung
        const listContent = document.getElementById('books-list-content');
        if (listContent) listContent.innerHTML = data.html;

        // 2. Đổ thanh phân trang vào vùng phân trang
        const pagContent = document.getElementById('pagination-content');
        if (pagContent) pagContent.innerHTML = data.pagination;

        // Hiển thị lại nội dung (nếu bạn có làm mờ trước đó)
        const wrapper = document.querySelector('.books-grid-wrapper');
        if (wrapper) wrapper.style.opacity = '1';
      });
  }

  // Gán sự kiện click cho các link thể loại trên header
  // Đảm bảo trong header.php bạn đã thêm class "ajax-filter" cho các thẻ <a>
  document.querySelectorAll('.ajax-filter').forEach(link => {
    link.addEventListener('click', function (e) {
      e.preventDefault();
      const loai = this.getAttribute('data-loai');
      loadCategory(loai);
    });
  });
});