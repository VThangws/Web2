// Hiệu ứng typing + xuất hiện cho phần GIỚI THIỆU TỔNG QUAN
document.addEventListener('DOMContentLoaded', () => {
    const section = document.querySelector('.library-intro');
    const title = document.querySelector('.type-title');
    const p1 = document.querySelector('.intro-1');
    const p2 = document.querySelector('.intro-2');
    const img = document.querySelector('.intro-image');

    if (!section || !title) return;

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