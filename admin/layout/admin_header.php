<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../database/ConnectDB.php';

$conn = ConnectDB::getInstance()->getConnection();

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
        <div class="main-logo">
            <a href="/admin/index.php">
                <img src="/assets/img/logo-library/library.png" alt="Library Logo">
            </a>
        </div>

        <nav class="main-nav">
            <ul>
                <li><a href="/admin/index.php">Trang chủ</a></li>
                <li><a href="/admin/QuanLy/DauSach/QL_DauSach.php">Đầu sách</a></li>
                <li><a href="/admin/QuanLy/DocGia/QL_DocGia.php">Độc giả</a></li>
                <li><a href="/admin/QuanLy/NhanVien/QL_NhanVien.php">Nhân viên</a></li>
                <li><a href="/admin/QuanLy/TacGia/QL_TacGia.php">Tác giả</a></li>
                <li><a href="/admin/QuanLy/TheLoai/QL_TheLoai.php">Thể loại</a></li>
                <li><a href="/admin/QuanLy/NhaXuatBan/QL_NhaXuatBan.php">NXB</a></li>
            </ul>
        </nav>

        <div class="main-actions">
            <button id="openSearch" class="main-icon-btn" type="button" aria-label="Tìm kiếm">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>

            <?php if ($user): ?>
                <a href="/index.php?page=taikhoan" class="main-btn main-btn-outline">
                    Xin chào, <?= htmlspecialchars($user['name'], ENT_QUOTES) ?>
                </a>
            <?php endif; ?>

            <a href="/admin/logout.php" class="main-btn main-btn-primary" aria-label="Đăng xuất">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span class="ms-2">Đăng xuất</span>
            </a>
        </div>
    </div>

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
