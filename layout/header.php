<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../database/ConnectDB.php';

// Lấy đối tượng kết nối mysqli từ ConnectDB
$conn = ConnectDB::getInstance()->getConnection();

$so_luong_gio_hang = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $so_luong_gio_hang += $item['soluong'];
    }
}
// Xử lý thông tin người dùng (Độc giả/Thủ thư)
$user = null;
if (isset($_SESSION['user_id'])) {
    $stmtUser = $conn->prepare('SELECT * FROM users WHERE user_id = ?');
    if ($stmtUser) {
        $stmtUser->bind_param('i', $_SESSION['user_id']);
        $stmtUser->execute();
        $resultUser = $stmtUser->get_result();
        if ($resultUser && $resultUser->num_rows > 0) {
            $user = $resultUser->fetch_assoc();
        }
        $stmtUser->close();
    }
}
?>

<link rel="stylesheet" href="/assets/css/header.css">

<header class="main-header">
    <div class="main-header-inner">
        <!-- Logo bên trái -->
        <div class="main-logo">
            <a href="/index.php">
                <img src="/assets/img/logo-library/library.png" alt="Library Logo">
            </a>
        </div>

        <!-- Thanh tìm kiếm lớn + các link thể loại -->
        <div class="header-search-big">
            <div id="searchBarHeader" class="search-big-box">
                <input id="searchInputHeader" type="text" placeholder="Tìm theo thương hiệu...">
                <button id="btnSearchHeader" type="button">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>

            <div class="search-category-mini">
                <a href="javascript:void(0)" class="header-filter-category ajax-filter" data-loai="kinh-te">Kinh Tế</a>
                <a href="javascript:void(0)" class="header-filter-category ajax-filter" data-loai="van-hoc-trong-nuoc">Văn Học Trong Nước</a>
                <a href="javascript:void(0)" class="header-filter-category ajax-filter" data-loai="van-hoc-nuoc-ngoai">Văn Học Nước Ngoài</a>
                <a href="javascript:void(0)" class="header-filter-category ajax-filter" data-loai="doi-song">Đời Sống</a>
                <a href="javascript:void(0)" class="header-filter-category ajax-filter" data-loai="thieu-nhi">Thiếu Nhi</a>
                <a href="javascript:void(0)" class="header-filter-category ajax-filter" data-loai="phat-trien-ban-than">Phát Triển Bản Thân</a>
                <a href="javascript:void(0)" class="header-filter-category ajax-filter" data-loai="tin-hoc-ngoai-ngu">Tin Học Ngoại Ngữ</a>
                <a href="javascript:void(0)" class="header-filter-category ajax-filter" data-loai="chuyen-nganh">Chuyên Ngành</a>
            </div>
        </div>

        <!-- Icon + nút bên phải -->
        <div class="main-actions">
            <div class="header-support">
                <div class="support-icon">
                    <i class="fa-solid fa-phone-volume"></i>
                </div>
                <div class="support-info">
                    <span class="support-title">Hỗ trợ khách hàng</span>
                    <strong class="support-phone">0987654321</strong>
                </div>
            </div>
            <!-- Nút Đăng ký / Đăng nhập -->
            <?php if ($user): ?>
                <a href="/index.php?page=taikhoan" class="main-btn main-btn-outline">
                    Xin chào, <?= htmlspecialchars($user['name'], ENT_QUOTES) ?>
                </a>
            <?php else: ?>
                <a href="/User-form/Login_Form/Register_Form.php" class="main-btn main-btn-outline">Đăng ký</a>
                <a href="/User-form/Login_Form/Login_Form.php" class="main-btn main-btn-primary">Đăng nhập</a>
            <?php endif; ?>

            <a href="#miniCart" data-bs-toggle="offcanvas" role="button" aria-controls="miniCart" class="main-icon-btn main-cart position-relative d-inline-flex align-items-center justify-content-center" style="text-decoration: none; margin-left: 10px;">
                <i class="fa-solid fa-cart-shopping fs-5 text-dark"></i>
                <span id="cart-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger <?= $so_luong_gio_hang > 0 ? '' : 'd-none' ?>" style="font-size: 0.65rem; padding: 0.25em 0.5em; margin-top: 5px; margin-left: -5px;">
                    <?= $so_luong_gio_hang ?>
                </span>
            </a>
        </div>
    </div>

    <!-- Subheader: thanh menu phụ bên dưới header chính
    <div class="subheader">
        <div class="subheader-inner">
            <a href="/index.php" class="subheader-link">Trang chủ</a>
            <a href="/index.php?page=books&sort=latest" class="subheader-link">Sách mới</a>
            <a href="/index.php?page=books&tag=best-seller" class="subheader-link">Bán chạy</a>
            <a href="/index.php?page=books&tag=khuyen-mai" class="subheader-link">Khuyến mãi</a>
            <a href="/index.php?page=books" class="subheader-link">Tất cả sách</a>
            <a href="#" class="subheader-link">Hỗ trợ</a>
        </div>
    </div> -->
</header>

</header>

<div class="offcanvas offcanvas-end shadow" tabindex="-1" id="miniCart" aria-labelledby="miniCartLabel" style="width: 360px;">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title fw-bold fs-6" id="miniCartLabel">Sản phẩm trong giỏ (<span id="mini-cart-count"><?= $so_luong_gio_hang ?></span>)</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    
    <div class="offcanvas-body" id="mini-cart-body">
        </div>
    
    <style>
    .btn-mini-checkout {
        background-color: #20c997;
        color: white;
        border: 1px solid #20c997;
        border-radius: 50px;
        transition: background-color 0.2s ease, border-color 0.2s ease;
    }
    .btn-mini-checkout:hover {
        background-color: #1aa179;
        border-color: #1aa179;
        color: white;
    }

    .btn-mini-close {
        background-color: white;
        color: #20c997;
        border: 1px solid #20c997;
        border-radius: 50px;
        transition: background-color 0.2s ease, color 0.2s ease;
    }
    .btn-mini-close:hover {
        background-color: #eafaf5;
        color: #1aa179;
        border-color: #1aa179;
    }
    </style>

    <div class="offcanvas-footer border-top p-3 bg-white">
        <a href="/index.php?page=cart" class="btn btn-mini-checkout w-100 fw-bold mb-3 py-2">Chi tiết giỏ hàng</a>
        <button type="button" class="btn btn-mini-close w-100 fw-bold py-2" data-bs-dismiss="offcanvas">Đóng</button>
    </div>
</div>
<script src="/assets/js/header.js" defer></script>
<script>
    window.user_id = <?= isset($_SESSION['user_id']) ? json_encode($_SESSION['user_id']) : 'null' ?>;
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    
    // 1. ĐỒNG BỘ HÓA SỐ LƯỢNG
    function syncCartBadge() {
        fetch('/ajax/get_cart_count.php')
        .then(response => response.json())
        .then(data => {
            let badge = document.getElementById('cart-badge');
            let miniCount = document.getElementById('mini-cart-count');
            
            if (badge) {
                badge.innerText = data.total_items;
                if (data.total_items > 0) {
                    badge.classList.remove('d-none');
                } else {
                    badge.classList.add('d-none');
                }
            }
            if (miniCount) {
                miniCount.innerText = data.total_items;
            }
        })
        .catch(err => console.log('Lỗi đồng bộ:', err));
    }

    syncCartBadge();

    window.addEventListener('pageshow', function(event) {
        if (event.persisted) syncCartBadge();
    });
    window.addEventListener('focus', syncCartBadge);

    // 2. XỬ LÝ MINI CART
    var miniCartEl = document.getElementById('miniCart');
    var miniCartBody = document.getElementById('mini-cart-body');

    function loadMiniCart(showLoading = true) {
        if(!miniCartBody) return;
        
        if (showLoading) {
            miniCartBody.innerHTML = '<div class="text-center mt-5"><i class="fa-solid fa-spinner fa-spin fs-3 text-secondary"></i><p class="mt-2 text-secondary small">Đang tải...</p></div>';
        }

        fetch('/ajax/get_mini_cart.php')
        .then(response => response.json())
        .then(data => {
            miniCartBody.innerHTML = data.html; // Cập nhật thẳng HTML, không qua bước loading
            syncCartBadge(); 
        });
    }

    if(miniCartEl) {
        // Khi mở offcanvas mới cho hiện loading
        miniCartEl.addEventListener('show.bs.offcanvas', function() {
            loadMiniCart(true); 
        });
    }

    document.body.addEventListener('click', function(e) {
        let btn = e.target.closest('.btn-mini-update');
        if (!btn) return;

        let madausach = btn.getAttribute('data-id');
        let action = btn.getAttribute('data-action');

        let formData = new FormData();
        formData.append('madausach', madausach);
        formData.append('action', action);

        // Giữ lại chiều rộng của nút để tránh giật layout khi đổi chữ thành "..."
        let btnWidth = btn.offsetWidth;
        btn.style.width = btnWidth + 'px';
        btn.disabled = true;
        let originalContent = btn.innerHTML;
        btn.innerHTML = '...'; 

        fetch('/ajax/update_cart.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.status === 'success') {
                loadMiniCart(false); 

                if (window.location.href.includes('page=cart')) {
                    location.reload();
                }
            }
        })
        .catch(err => {
            console.error("Lỗi cập nhật giỏ:", err);
            btn.disabled = false;
            btn.innerHTML = originalContent;
            btn.style.width = 'auto';
        });
    });
});
</script>