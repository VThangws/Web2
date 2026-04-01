<?php
require_once __DIR__ . '/../model/DocGia.php';
require_once __DIR__ . '/../model/TaiKhoan.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../database/ConnectDB.php';
require_once __DIR__ . '/../layout/login.php';

// Lấy đối tượng kết nối mysqli từ ConnectDB
$conn = ConnectDB::getInstance()->getConnection();

$so_luong_gio_hang = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $so_luong_gio_hang += $item['soluong'];
    }
}
// Xử lý thông tin người dùng (Độc giả/Thủ thư)

$docgia = $_SESSION['docgia'] ?? null; // vì đăng nhập trả về obj nên không cần phải query lại database, chỉ cần lấy từ session

?>
<link rel="stylesheet" href="/assets/css/login.css">
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
            <?php if ($docgia): ?>
                <a href="/index.php?page=taikhoan" class="main-btn main-btn-outline">
                    Xin chào, <?= htmlspecialchars($docgia->getTendocgia(), ENT_QUOTES) ?>
                </a>
                <a href="/ajax/logout.php" class="main-btn main-btn-outline">Đăng xuất</a>
            <?php else: ?>
                <button id="openLogin" class="main-btn main-btn-outline">
                    Đăng nhập
                </button>
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
    window.user_id = <?= $docgia ? json_encode($docgia->getMadocgia()) : 'null' ?>;
</script>

<script src="/assets/js/header.js" defer></script>
<script src="/assets/js/cart.js" defer></script>