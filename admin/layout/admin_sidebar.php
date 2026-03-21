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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
<link rel="stylesheet" href="/assets/css/admin_sidebar.css">

<aside class="admin-sidebar" aria-label="Menu quản trị">
    <div class="admin-sidebar-header">
        <a href="/admin/adminMenu.php" class="d-flex align-items-center gap-2 text-decoration-none">
            <img src="/assets/img/logo-library/library.png" alt="Library Logo">
            <span class="admin-sidebar-title">Quản lý thư viện</span>
        </a>
    </div>

    <nav class="admin-sidebar-nav">
        <ul>
            <li>
                <a href="/admin/adminMenu.php">
                    <i class="fa-solid fa-house admin-nav-icon" aria-hidden="true"></i>
                    <span>Trang chủ</span>
                </a>
            </li>
            <li>
                <a href="/admin/QuanLy/Sach/DauSach/QL_DauSach.php">
                    <i class="fa-solid fa-book admin-nav-icon" aria-hidden="true"></i>
                    <span>Đầu sách</span>
                </a>
            </li>
            <li>
                <a href="/admin/QuanLy/HoaDon/QL_HoaDon.php">
                    <i class="fa-solid fa-receipt admin-nav-icon" aria-hidden="true"></i>
                    <span>Hóa đơn</span>
                </a>
            </li>
            <li>
                <a href="/admin/QuanLy/TaiKhoan/QL_TaiKhoan.php">
                    <i class="fa-solid fa-user-gear admin-nav-icon" aria-hidden="true"></i>
                    <span>Tài khoản</span>
                </a>
            </li>
            <li>
                <a href="/admin/QuanLy/DocGia/QL_DocGia.php">
                    <i class="fa-solid fa-users admin-nav-icon" aria-hidden="true"></i>
                    <span>Độc giả</span>
                </a>
            </li>
            <li>
                <a href="/admin/QuanLy/NhanVien/QL_NhanVien.php">
                    <i class="fa-solid fa-user-tie admin-nav-icon" aria-hidden="true"></i>
                    <span>Nhân viên</span>
                </a>
            </li>
            <li>
                <a href="/admin/QuanLy/TacGia/QL_TacGia.php">
                    <i class="fa-solid fa-pen-nib admin-nav-icon" aria-hidden="true"></i>
                    <span>Tác giả</span>
                </a>
            </li>
            <li>
                <a href="/admin/QuanLy/Sach/TheLoai/QL_TheLoai.php">
                    <i class="fa-solid fa-tags admin-nav-icon" aria-hidden="true"></i>
                    <span>Thể loại</span>
                </a>
            </li>
            <li>
                <a href="/admin/QuanLy/Sach/NhaXuatBan/QL_NhaXuatBan.php">
                    <i class="fa-solid fa-building admin-nav-icon" aria-hidden="true"></i>
                    <span>NXB</span>
                </a>
            </li>
            <li>
                <a href="/admin/QuanLy/NhaCungCap/QL_NhaCungCap.php">
                    <i class="fa-solid fa-truck admin-nav-icon" aria-hidden="true"></i>
                    <span>Nhà cung cấp</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="admin-sidebar-actions">
        <?php
        $displayName = $user && isset($user['name']) ? (string)$user['name'] : 'Admin';
        $initial = 'A';
        if (function_exists('mb_substr')) {
            $initial = mb_strtoupper(mb_substr(trim($displayName), 0, 1, 'UTF-8'), 'UTF-8');
        } else {
            $initial = strtoupper(substr(trim($displayName), 0, 1));
        }
        ?>

        <div class="admin-user-chip" aria-label="Tài khoản">
            <div class="admin-avatar rounded-circle border bg-light d-inline-flex align-items-center justify-content-center">
                <?= htmlspecialchars($initial, ENT_QUOTES) ?>
            </div>
            <span class="admin-user-name">
                <?= htmlspecialchars($displayName, ENT_QUOTES) ?>
            </span>
        </div>

        <a href="/admin/logout.php" class="main-btn main-btn-primary" aria-label="Đăng xuất">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span class="ms-2">Đăng xuất</span>
        </a>
    </div>
</aside>

<script>
    // Make room for the fixed left sidebar across admin pages.
    if (document.body) document.body.classList.add('admin-has-sidebar');
</script>

<script>
    window.user_id = <?= isset($_SESSION['user_id']) ? json_encode($_SESSION['user_id']) : 'null' ?>;
</script>
<script src="/assets/js/header.js" defer></script>
