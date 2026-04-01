// Hiệu ứng typing + xuất hiện cho phần GIỚI THIỆU TỔNG QUAN
document.addEventListener('DOMContentLoaded', () => {
    const section = document.querySelector('.library-intro');
    const title = document.querySelector('.type-title');
    const p1 = document.querySelector('.intro-1');
    const p2 = document.querySelector('.intro-2');
    const img = document.querySelector('.intro-image');

    if (!section || !title) return;

    if (!section || !title || !p1 || !p2 || !img) return;

    const originalText = title.textContent;
    title.textContent = '';

    let hasTyped = false;

    function typeWriter(text, el, speed = 20) {
        let i = 0;
        function typing() {
            if (i < text.length) {
                el.textContent += text.charAt(i);
                i++;
                setTimeout(typing, speed);
            } else {
                if (p1) setTimeout(() => p1.classList.add('show'), 300);
                if (p2) setTimeout(() => p2.classList.add('show'), 900);
                if (img) setTimeout(() => img.classList.add('show'), 1200);
            }
        }
        typing();
    }

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !hasTyped) {
                hasTyped = true;
                typeWriter(originalText, title);
                observer.unobserve(section);
            }
        });
    }, { threshold: 0.5 });

    observer.observe(section);
});

// Scroll reveal cho các section và item trên home
(function () {
    const sections = document.querySelectorAll('.reveal-section');
    if (!sections.length) return;

    const observerSection = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                const items = entry.target.querySelectorAll('.reveal-item');
                items.forEach((item, i) => {
                    setTimeout(() => {
                        item.classList.add('visible');
                    }, 200 * i);
                });
            }
        });
    }, { threshold: 0.25 });

    sections.forEach(section => observerSection.observe(section));
})();

// Full-page section scroll: từ lần lướt xuống đầu tiên tới hết section thống kê cuối cùng
(function () {
    const allSections = Array.from(document.querySelectorAll('.reveal-section'));
    const introSection = document.querySelector('.library-intro');
    const statsSection = document.querySelector('.stats-section');
    if (!allSections.length || !introSection || !statsSection) return;

    const introIndex = allSections.indexOf(introSection);
    const statsIndex = allSections.indexOf(statsSection);
    if (introIndex === -1 || statsIndex === -1 || statsIndex <= introIndex) return;

    const sections = allSections.slice(introIndex, statsIndex + 1);

    let fullPageActive = false;
    let isAutoScrolling = false;
    let currentIndex = 0;

    function scrollToSection(index) {
        if (index < 0 || index >= sections.length) return;
        isAutoScrolling = true;
        currentIndex = index;
        sections[index].scrollIntoView({ behavior: 'smooth', block: 'start' });
        setTimeout(() => {
            isAutoScrolling = false;
        }, 800);
    }

    function detectCurrentSection() {
        let minDiff = Infinity;
        let bestIndex = currentIndex;
        const vpMiddle = window.innerHeight / 2;
        sections.forEach((sec, i) => {
            const rect = sec.getBoundingClientRect();
            const secMiddle = rect.top + rect.height / 2;
            const diff = Math.abs(secMiddle - vpMiddle);
            if (diff < minDiff) {
                minDiff = diff;
                bestIndex = i;
            }
        });
        currentIndex = bestIndex;
    }

    window.addEventListener('resize', () => {
        if (!fullPageActive) return;
        detectCurrentSection();
    });

    window.addEventListener('wheel', (e) => {
        // Kích hoạt full-page khi lần đầu lướt xuống từ trên banner vào phần giới thiệu
        if (!fullPageActive && e.deltaY > 0) {
            const introTop = introSection.getBoundingClientRect().top;
            if (introTop > 0) {
                e.preventDefault();
                fullPageActive = true;
                currentIndex = 0;
                scrollToSection(0);
                return;
            }
            fullPageActive = true;
            detectCurrentSection();
        }

        if (!fullPageActive) return;

        if (isAutoScrolling) {
            e.preventDefault();
            return;
        }

        if (Math.abs(e.deltaY) < 10) return;

        const atLastSection = currentIndex === sections.length - 1;

        if (e.deltaY > 0) {
            if (!atLastSection) {
                e.preventDefault();
                scrollToSection(currentIndex + 1);
            } else {
                // Đang ở thống kê, lướt xuống nữa thì tắt chế độ để cuộn tiếp tới footer
                fullPageActive = false;
            }
        } else {
            if (currentIndex > 0) {
                e.preventDefault();
                scrollToSection(currentIndex - 1);
            } else {
                // Lên trên khỏi phần giới thiệu thì tắt chế độ
                fullPageActive = false;
            }
        }
    }, { passive: false });
})();

// Random rolling numbers cho section thống kê
(function () {
    const statsSection = document.querySelector('.stats-section');
    if (!statsSection) return;

    const statElements = statsSection.querySelectorAll('[data-target]');
    if (!statElements.length) return;

    let started = false;

    function startStatsAnimation() {
        if (started) return;
        started = true;

        statElements.forEach(el => {
            const targetStr = el.getAttribute('data-target') || '0';
            const clean = targetStr.replace(/[^0-9]/g, '');
            const target = parseInt(clean || '0', 10);
            if (!target) return;

            const duration = 1500 + Math.random() * 800;
            const startTime = performance.now();

            function update(now) {
                const progress = Math.min((now - startTime) / duration, 1);
                const current = Math.floor(target * progress);

                let display = current;
                if (progress < 1) {
                    const randLast = Math.floor(Math.random() * 10);
                    display = current - (current % 10) + randLast;
                }

                el.textContent = display.toLocaleString('vi-VN') + '+';

                if (progress < 1) {
                    requestAnimationFrame(update);
                } else {
                    el.textContent = target.toLocaleString('vi-VN') + '+';
                }
            }

            requestAnimationFrame(update);
        });
    }

    if ('IntersectionObserver' in window) {
        const obs = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    startStatsAnimation();
                    obs.disconnect();
                }
            });
        }, { threshold: 0.5 });

        obs.observe(statsSection);
    } else {
        setTimeout(startStatsAnimation, 1000);
    }
})();

// Nút quay lại đầu trang riêng cho home (nếu chưa có)
(function () {
    const btn = document.getElementById('back-to-top');
    if (!btn) return;

    function toggleVisibility() {
        if (window.scrollY > 300) {
            btn.classList.add('show');
        } else {
            btn.classList.remove('show');
        }
    }

    window.addEventListener('scroll', toggleVisibility);

    btn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
})();

// 3D carousel bìa sách bên phải banner
(function () {
    const slider = document.querySelector('.banner-cover-slider');
    if (!slider) return;

    const cards = Array.from(slider.querySelectorAll('.banner-cover-card'));
    if (cards.length === 0) return;

    const prevBtn = slider.querySelector('.banner-prev');
    const nextBtn = slider.querySelector('.banner-next');

    let current = 0;

    function updatePositions() {
        const n = cards.length;
        const center = current;
        const left = (current - 1 + n) % n;
        const right = (current + 1) % n;

        cards.forEach((card, idx) => {
            card.classList.remove('position-center', 'position-left', 'position-right', 'position-hidden');
            if (idx === center) card.classList.add('position-center');
            else if (idx === left) card.classList.add('position-left');
            else if (idx === right) card.classList.add('position-right');
            else card.classList.add('position-hidden');
        });
    }

    function goNext() {
        current = (current + 1) % cards.length;
        updatePositions();
    }

    function goPrev() {
        current = (current - 1 + cards.length) % cards.length;
        updatePositions();
    }

    updatePositions();

    let timer = setInterval(goNext, 1500);

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            clearInterval(timer);
            goNext();
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            clearInterval(timer);
            goPrev();
        });
    }
})();

// Banner: load sách theo thể loại và đổ vào slider 3D bên phải
(function () {
    const genreSelect = document.getElementById('bannerGenreSelect');
    const slider = document.getElementById('bannerCoverSlider');
    if (!genreSelect || !slider) return;

    // Tìm hàm renderBooks trong home.js và sửa lại như sau:
    function renderBooks(books) {
        // Chỉ reset nội dung của track, giữ nguyên 2 nút điều hướng nếu đã có trong HTML cứng
        // Hoặc render lại đầy đủ như sau:
        slider.innerHTML = `
    <button class="banner-nav banner-prev" id="prevBtn" type="button">
        <span class="material-symbols-outlined">chevron_left</span>
    </button>
    <div class="banner-cover-track"></div>
    <button class="banner-nav banner-next" id="nextBtn" type="button">
        <span class="material-symbols-outlined">chevron_right</span>
    </button>
`;

        const track = slider.querySelector('.banner-cover-track');

        if (books.length === 0) {
            track.innerHTML = '<p class="text-white">Không có sách trong thể loại này.</p>';
            return;
        }

        books.forEach((b, idx) => {
            const card = document.createElement('div');
            card.className = 'banner-cover-card';
            card.dataset.index = String(idx);

            const imgSrc = b.anhbia ? `/assets/img/books/${b.anhbia}` : '/assets/img/categories/booknew.png';

            card.innerHTML = `
        <a href="/index.php?page=book_detail&madausach=${encodeURIComponent(b.madausach)}" 
           style="display: block; width: 100%; height: 100%; text-decoration: none;">
            <img src="${imgSrc}" alt="${b.tensach || ''}" style="width: 100%; height: 100%; object-fit: cover;">
        </a>
    `;
            track.appendChild(card);
        });

        // Quan trọng: Sau khi render xong phải gọi lại hàm khởi tạo Carousel
        initBannerCarousel();
    }

    function loadBooksByGenre(matheloai) {
        fetch(`/ajax/banner_books.php?matheloai=${encodeURIComponent(matheloai || '')}`)
            .then(res => res.json())
            .then(renderBooks)
            .catch(() => renderBooks([]));
    }

    // Lần đầu load: tất cả thể loại (random)
    loadBooksByGenre('');

    genreSelect.addEventListener('change', () => {
        loadBooksByGenre(genreSelect.value);
    });
})();

// Gợi ý hôm nay: đổi câu nói hay mỗi ngày
(function () {
    const badge = document.querySelector('.banner-badge');
    const title = document.querySelector('.banner-book-title');
    const desc = document.querySelector('.banner-desc');
    if (!badge || !title || !desc) return;

    const quotes = [
        {
            badge: 'GỢI Ý HÔM NAY',
            title: 'Đọc sách mỗi ngày, mở rộng thế giới của bạn',
            desc: 'Từng trang sách lật qua không chỉ mang theo kiến thức mà còn mở ra những góc nhìn hoàn toàn mới mẻ. Thay vì lướt điện thoại vô định, hãy thử dành ra 15 phút tĩnh lặng mỗi ngày để đắm chìm vào những con chữ. Bạn sẽ bất ngờ với cách tư duy của mình dần trở nên sắc bén và tâm hồn được bồi đắp sâu sắc hơn.'
        },
        {
            badge: 'CÂU NÓI HÔM NAY',
            title: 'Không có người không thích đọc, chỉ là chưa tìm thấy cuốn sách của mình',
            desc: 'Giống như việc tìm kiếm một người bạn tri kỷ, đôi khi chúng ta cần kiên nhẫn thử nghiệm nhiều thể loại khác nhau. Đừng ngại bước ra khỏi vùng an toàn để khám phá một tác phẩm viễn tưởng hay một cuốn tản văn nhẹ nhàng. Chắc chắn có một câu chuyện ngoài kia đang kiên nhẫn chờ đợi để được bạn lật mở.'
        },
        {
            badge: 'GỢI Ý HÔM NAY',
            title: 'Mỗi cuốn sách là một người thầy',
            desc: 'Bất kể bạn đang chênh vênh giữa những ngã rẽ cuộc sống, hay đơn giản là cần một lời khuyên để vượt qua khó khăn, luôn có những người đã đi trước và đúc kết kinh nghiệm thành sách. Hãy để những bộ óc vĩ đại nhất của nhân loại làm người dẫn đường, soi sáng cho những quyết định quan trọng của bạn.'
        },
        {
            badge: 'TRÍ THỨC MỖI NGÀY',
            title: 'Sách là nơi an toàn để thử những trải nghiệm mới',
            desc: 'Chỉ với một cuốn sách trên tay, bạn đã sở hữu tấm vé thông hành quyền lực nhất: du hành ngược thời gian, vươn tới tương lai hay dạo bước trên những vùng đất xa xôi. Sách trao cho bạn đặc quyền được sống hàng ngàn cuộc đời rực rỡ khác nhau mà không phải chịu bất kỳ rủi ro nào ở hiện tại.'
        }
    ];

    // Dùng số ngày kể từ 1970 để chọn câu, nên mỗi ngày sẽ là một câu cố định
    const todayIndex = Math.floor(Date.now() / (1000 * 60 * 60 * 24));
    const q = quotes[todayIndex % quotes.length];

    badge.textContent = q.badge;
    title.textContent = q.title;
    desc.textContent = q.desc;
})();

// Bóng sách mờ phía sau cuốn đang active
function updateBannerShadow(centerCard) {
    const col = document.querySelector('.banner-cover-col');
    if (!col || !centerCard) return;

    let shadow = col.querySelector('.banner-cover-shadow');
    if (!shadow) {
        shadow = document.createElement('div');
        shadow.className = 'banner-cover-shadow';
        shadow.innerHTML = '<img src="" alt="Bóng sách">';
        col.appendChild(shadow);
    }

    const img = centerCard.querySelector('img');
    const shadowImg = shadow.querySelector('img');
    if (img && shadowImg) {
        shadowImg.src = img.src;
    }
}

// Khởi tạo carousel 3D cho banner-cover-slider
// Khởi tạo carousel 3D cho banner-cover-slider với logic dừng 10s khi thao tác
function initBannerCarousel() {
    const slider = document.querySelector('.banner-cover-slider');
    if (!slider) return;

    const cards = Array.from(slider.querySelectorAll('.banner-cover-card'));
    if (!cards.length) return;

    const prevBtn = slider.querySelector('.banner-prev');
    const nextBtn = slider.querySelector('.banner-next');

    let current = 0;
    let timer = null; // Quản lý vòng lặp tự động (1.5s)
    let resumeTimeout = null; // Quản lý việc chờ 10s sau khi bấm

    function updatePositions() {
        const n = cards.length;
        const center = current;
        const left = (current - 1 + n) % n;
        const right = (current + 1) % n;

        cards.forEach((card, idx) => {
            card.classList.remove('position-center', 'position-left', 'position-right', 'position-hidden');
            if (idx === center) card.classList.add('position-center');
            else if (idx === left) card.classList.add('position-left');
            else if (idx === right) card.classList.add('position-right');
            else card.classList.add('position-hidden');
        });

        if (typeof updateBannerShadow === 'function') {
            updateBannerShadow(cards[center]);
        }
    }

    function goNext() {
        current = (current + 1) % cards.length;
        updatePositions();
    }

    function goPrev() {
        current = (current - 1 + cards.length) % cards.length;
        updatePositions();
    }

    // Hàm bắt đầu vòng lặp tự động 1.5s
    function startAutoPlay() {
        if (timer) clearInterval(timer);
        timer = setInterval(goNext, 1500);
    }

    // Hàm dừng tất cả các timer
    function stopAutoPlay() {
        if (timer) clearInterval(timer);
        if (resumeTimeout) clearTimeout(resumeTimeout);
    }

    // Hàm xử lý khi ní bấm nút
    function handleInteraction(action) {
        stopAutoPlay(); // Dừng chạy tự động ngay lập tức
        action(); // Thực hiện chuyển slide (Next/Prev)

        // Bắt đầu đếm ngược 10 giây để chạy lại
        resumeTimeout = setTimeout(() => {
            startAutoPlay();
        }, 5000);
    }

    // Gán sự kiện onclick cho nút bấm
    if (nextBtn) {
        nextBtn.onclick = () => handleInteraction(goNext);
    }
    if (prevBtn) {
        prevBtn.onclick = () => handleInteraction(goPrev);
    }

    // Khởi tạo ban đầu
    updatePositions();
    startAutoPlay();
}
// Gọi initBannerCarousel một lần khi DOM ready (trong trường hợp đã có sẵn card cứng)
document.addEventListener('DOMContentLoaded', initBannerCarousel);