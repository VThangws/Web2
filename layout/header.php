<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../database/ConnectDB.php';

// Lấy đối tượng kết nối mysqli từ ConnectDB
$conn = ConnectDB::getInstance()->getConnection();

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
                <a href="/index.php?page=books&loai=kinh-te">Kinh Tế</a>
                <a href="/index.php?page=books&loai=van-hoc-trong-nuoc">Văn Học Trong Nước</a>
                <a href="/index.php?page=books&loai=van-hoc-nuoc-ngoai">Văn Học Nước Ngoài</a>
                <a href="/index.php?page=books&loai=doi-song">Đời Sống</a>
                <a href="/index.php?page=books&loai=thieu-nhi">Thiếu Nhi</a>
                <a href="/index.php?page=books&loai=phat-trien-ban-than">Phát Triển Bản Thân</a>
                <a href="/index.php?page=books&loai=tin-hoc-ngoai-ngu">Tin Học Ngoại Ngữ</a>
                <a href="/index.php?page=books&loai=chuyen-nganh">Chuyên Ngành</a>
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

            <!-- Icon giỏ hàng: dùng Font Awesome -->
            <a href="/index.php?page=giohang" class="main-icon-btn main-cart">
                <i class="fa-solid fa-cart-shopping"></i>
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


<script>
    window.user_id = <?= isset($_SESSION['user_id']) ? json_encode($_SESSION['user_id']) : 'null' ?>;
</script>
<script src="/assets/js/header.js" defer></script>