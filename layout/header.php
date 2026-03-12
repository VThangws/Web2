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

        <!-- Menu giữa -->
        <nav class="main-nav">
            <ul>
                <li><a href="/index.php?page=sach&loai=skill">Kinh Tế</a></li>
                <li><a href="/index.php?page=sach&loai=detective">Văn Học Trong Nước</a></li>
                <li><a href="/index.php?page=sach&loai=children">Văn Học Nước Ngoài</a></li>
                <li><a href="/index.php?page=sach&loai=literature">Đời Sống</a></li>
                <li><a href="/index.php?page=sach&loai=romance">Thiếu Nhi</a></li>
                <li><a href="/index.php?page=sach&loai=education">Phát Triển Bản Thân</a></li>
                <li><a href="/index.php?page=sach&loai=fantasy">Tin Học Ngoại Ngữ</a></li>
                <li><a href="/index.php?page=sach&loai=fantasy">Chuyên Ngành</a></li>
            </ul>
        </nav>

        <!-- Icon + nút bên phải -->
        <div class="main-actions">
            <!-- Icon tìm kiếm -->
            <button id="openSearch" class="main-icon-btn" type="button">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>

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

    <!-- Khung tìm kiếm overlay -->
    <div id="searchOverlay" class="search-overlay">
        <div class="search-box">
            <button class="close-btn" id="closeSearch">&times;</button>
            <input type="text" id="searchInput" placeholder="Nhập tên sách, tác giả...">
            <div id="searchResultBox"></div>
        </div>
    </div>
</header>

<script>
    window.user_id = <?= isset($_SESSION['user_id']) ? json_encode($_SESSION['user_id']) : 'null' ?>;
</script>
<script src="/assets/js/header.js" defer></script>