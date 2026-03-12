document.addEventListener("DOMContentLoaded", () => {

    const section = document.querySelector(".library-intro");
    const title = document.querySelector(".type-title");
    const p1 = document.querySelector(".intro-1");
    const p2 = document.querySelector(".intro-2");
    const img = document.querySelector(".intro-image");

    const originalText = title.textContent;
    title.textContent = "";

    let hasTyped = false;

    function typeWriter(text, el, speed = 40) {
        let i = 0;

        function typing() {
            if (i < text.length) {
                el.textContent += text.charAt(i);
                i++;
                setTimeout(typing, speed);
            } else {

                setTimeout(() => p1.classList.add("show"), 300);
                setTimeout(() => p2.classList.add("show"), 900);
                setTimeout(() => img.classList.add("show"), 1200);

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
    }, {
        threshold: 0.5
    });

    observer.observe(section);

});
// scroll reveal toàn bộ section
const sections = document.querySelectorAll(".reveal-section");

const observerSection = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {

            entry.target.classList.add("active");

            const items = entry.target.querySelectorAll(".reveal-item");

            items.forEach((item, i) => {
                setTimeout(() => {
                    item.classList.add("active");
                }, 200 * i);
            });

        }
    });
}, {
    threshold: 0.25
});

sections.forEach(section => {
    observerSection.observe(section);
});

// Scroll animation kiểu Apple cho trang home

document.addEventListener('DOMContentLoaded', () => {
    const sections = document.querySelectorAll('.reveal-section');

    if (!sections.length) return;

    // Thêm class để bật snap scrolling trên body
    document.body.classList.add('snap-scroll');

    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                const sec = entry.target;
                if (entry.isIntersecting) {
                    sections.forEach(s => {
                        if (s !== sec) s.classList.remove('section-active');
                    });
                    sec.classList.add('section-active');
                }
            });
        }, {
            threshold: 0.6
        });

        sections.forEach(sec => observer.observe(sec));
    } else {
        sections[0].classList.add('section-active');
    }
});

// === Full-page section scroll: chỉ chặn wheel ở lần lướt xuống đầu tiên và trong thời gian auto scroll ===
(function () {
    const introSection = document.querySelector('.library-intro');
    if (!introSection) return;

    let firstScrollHandled = false;
    let isAutoScrolling = false;

    window.addEventListener('wheel', (e) => {
        // Nếu đang auto scroll sau lần đầu thì chặn để tránh người dùng phá animation
        if (isAutoScrolling) {
            e.preventDefault();
            return;
        }

        // Chỉ xử lý logic đặc biệt cho lần lướt xuống đầu tiên
        if (!firstScrollHandled && e.deltaY > 0) {
            const introTop = introSection.getBoundingClientRect().top;
            if (introTop > 0) {
                e.preventDefault();
                firstScrollHandled = true;
                isAutoScrolling = true;
                introSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                setTimeout(() => {
                    isAutoScrolling = false;
                }, 800); // sau 0.8s coi như auto scroll xong, trả wheel về bình thường
                return;
            } else {
                // nếu banner đã không còn trên viewport thì xem như đã qua lần đầu
                firstScrollHandled = true;
            }
        }

        // Các wheel sau (hoặc wheel không phải lần đầu lướt xuống) đều để browser xử lý bình thường
    }, { passive: false });
})();

// === Full-page section scroll: từ lần lướt xuống đầu tiên tới hết section thống kê cuối cùng ===
(function () {
    const allSections = Array.from(document.querySelectorAll('.reveal-section'));
    const introSection = document.querySelector('.library-intro');
    const statsSection = document.querySelector('.stats-section');
    if (!allSections.length || !introSection || !statsSection) return;

    const introIndex = allSections.indexOf(introSection);
    const statsIndex = allSections.indexOf(statsSection);
    if (introIndex === -1 || statsIndex === -1 || statsIndex <= introIndex) return;

    // Danh sách section sẽ bị điều khiển scroll: từ Giới thiệu đến hết Thống kê
    const sections = allSections.slice(introIndex, statsIndex + 1);

    let fullPageActive = false;    // đã bắt đầu chế độ chặn scroll + snap
    let isAutoScrolling = false;   // đang auto scroll
    let currentIndex = 0;          // index trong mảng sections

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
        // Nếu chưa kích hoạt chế độ full-page và lướt xuống lần đầu
        if (!fullPageActive && e.deltaY > 0) {
            const introTop = introSection.getBoundingClientRect().top;
            if (introTop > 0) {
                // Đang ở trên phần Giới thiệu (banner còn hiện) -> kích hoạt chế độ full-page từ đây
                e.preventDefault();
                fullPageActive = true;
                currentIndex = 0; // luôn vào section Giới thiệu đầu tiên
                scrollToSection(0);
                return;
            }
            // Nếu đã xuống dưới Giới thiệu rồi thì coi như người dùng đã vượt qua điểm bắt đầu
            fullPageActive = true;
            detectCurrentSection();
        }

        // Nếu chưa active thì cho scroll bình thường
        if (!fullPageActive) return;

        // Nếu đang auto scroll thì chặn input để tránh giật
        if (isAutoScrolling) {
            e.preventDefault();
            return;
        }

        if (Math.abs(e.deltaY) < 10) return;

        const atLastSection = currentIndex === sections.length - 1;

        // Cuộn xuống
        if (e.deltaY > 0) {
            // Nếu còn section phía dưới trong chuỗi thì snap tiếp
            if (!atLastSection) {
                e.preventDefault();
                scrollToSection(currentIndex + 1);
            } else {
                // Đang ở section Thống kê (cuối chuỗi) và người dùng tiếp tục lướt xuống
                // -> tắt chế độ full-page để có thể cuộn xuống footer bình thường
                fullPageActive = false;
                // KHÔNG e.preventDefault() ở đây để lần cuộn này đi xuyên xuống footer
            }
        } else {
            // Cuộn lên trong phạm vi sections (Giới thiệu -> các section trước Thống kê)
            if (currentIndex > 0) {
                e.preventDefault();
                scrollToSection(currentIndex - 1);
            } else {
                // Ở section Giới thiệu, nếu cuộn lên thì thoát khỏi chế độ và trả scroll cho trình duyệt
                fullPageActive = false;
                // Không chặn để cuộn ngược lên banner bình thường
            }
        }
    }, { passive: false });
})();

// === Random rolling numbers for stats section ===
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

            const duration = 1500 + Math.random() * 800; // 1.5s - 2.3s
            const startTime = performance.now();

            function update(now) {
                const progress = Math.min((now - startTime) / duration, 1);
                const current = Math.floor(target * progress);

                // randomize last digit while not finished
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
        // fallback: start after small delay
        setTimeout(startStatsAnimation, 1000);
    }
})();

// Nút quay lại đầu trang (back-to-top bubble)
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