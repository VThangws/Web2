<div id="offline-overlay" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: #ffffff; z-index: 2147483647; display: none; flex-direction: column; justify-content: center; align-items: center; text-align: center; font-family: 'Oswald', sans-serif;">
    <div id="lottie-offline-container" style="width: 350px; height: 350px;"></div>
    <h2 style="color: #20c997; font-weight: 700; text-transform: uppercase;">Mất kết nối Internet rồi bạn ơi!</h2>
    <p style="color: #666; max-width: 400px; padding: 0 20px;">Vui lòng kiểm tra lại đường truyền INTERNET.</p>
</div>

<script src="assets/js/lottie.min.js"></script>

<script>
(function() {
    // Hàm này giúp kiểm tra và hiển thị overlay ngay lập tức
    function updateNetworkStatus() {
        const overlay = document.getElementById('offline-overlay');
        if (!overlay) return;

        if (!navigator.onLine) {
            // Mất mạng: Hiện overlay bằng flex để căn giữa
            overlay.style.setProperty('display', 'flex', 'important');
        } else {
            // Có mạng: Ẩn overlay
            overlay.style.display = 'none';
        }
    }

    // Khởi tạo Lottie sau khi thư viện đã sẵn sàng
    function initLottie() {
        const container = document.getElementById('lottie-offline-container');
        if (typeof lottie !== 'undefined' && container) {
            lottie.loadAnimation({
                container: container,
                renderer: 'svg',
                loop: true,
                autoplay: true,
                path: 'assets/json/loading.json' // Đường dẫn tới file JSON ní đã lưu
            });
        }
    }

    // Lắng nghe sự kiện "mất mạng đột ngột" (offline) và "có mạng lại" (online)
    window.addEventListener('online', updateNetworkStatus);
    window.addEventListener('offline', updateNetworkStatus);

    // Chạy kiểm tra ngay khi load trang
    document.addEventListener('DOMContentLoaded', () => {
        initLottie();
        updateNetworkStatus();
    });
    
    // Kiểm tra dự phòng ngay lập tức nếu DOM đã xong
    updateNetworkStatus();
})();
</script>