<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../auth.php';
require_admin_login();

$admin = admin_current_user();
$adminUsername = (string)($admin['tendangnhap'] ?? 'Admin');
?>

<?php
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '';
$isActive = static function (string $pattern) use ($currentPath): bool {
    return $pattern !== '' && strpos($currentPath, $pattern) !== false;
};

$isHome = $isActive('/admin/adminMenu.php');
$isDauSach = $isActive('/admin/QuanLy/Sach/DauSach/') || $isActive('/admin/QuanLy/Sach/CuonSach/');
$isHoaDon = $isActive('/admin/QuanLy/HoaDon/');
$isScanQr = $isActive('/admin/QuanLy/Scan_QR/');
$isTaiKhoan = $isActive('/admin/QuanLy/TaiKhoan/');
$isDocGia = $isActive('/admin/QuanLy/DocGia/');
$isNhanVien = $isActive('/admin/QuanLy/NhanVien/');
$isTacGia = $isActive('/admin/QuanLy/TacGia/');
$isTheLoai = $isActive('/admin/QuanLy/Sach/TheLoai/');
$isNxb = $isActive('/admin/QuanLy/Sach/NhaXuatBan/');
$isNhaCungCap = $isActive('/admin/QuanLy/NhaCungCap/');
$isThongKe = $isActive('/admin/QuanLy/ThongKe/');
?>

<link rel="stylesheet" href="/assets/css/header.css">
<link rel="stylesheet" href="/assets/fonts/font.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
<link rel="stylesheet" href="/assets/css/admin_sidebar.css">

<style>
    .admin-sidebar {
        font-family: 'Oswald', sans-serif !important;
    }
</style>

<aside class="admin-sidebar" aria-label="Menu quản trị">
    <div class="admin-sidebar-header">
        <a href="/admin/adminMenu.php" class="d-flex align-items-center gap-2 text-decoration-none">
            <i class="fa-solid fa-book-open-reader admin-brand-icon" aria-hidden="true"></i>
            <span class="admin-sidebar-title">Quản lý thư viện</span>
        </a>
    </div>

    <nav class="admin-sidebar-nav">
        <ul>
            <?php if (admin_has_permission('DASHBOARD')): ?>
                <li>
                    <a href="/admin/adminMenu.php" class="<?php echo $isHome ? 'active' : ''; ?>">
                        <i class="fa-solid fa-house admin-nav-icon" aria-hidden="true"></i>
                        <span>Trang quản trị</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if (admin_has_permission('SACH')): ?>
                <li>
                    <a href="/admin/QuanLy/Sach/DauSach/QL_DauSach.php" class="<?php echo $isDauSach ? 'active' : ''; ?>">
                        <i class="fa-solid fa-book admin-nav-icon" aria-hidden="true"></i>
                        <span>Đầu sách</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if (admin_has_permission('HOADON')): ?>
                <li>
                    <a href="/admin/QuanLy/HoaDon/QL_HoaDon.php" class="<?php echo $isHoaDon ? 'active' : ''; ?>">
                        <i class="fa-solid fa-receipt admin-nav-icon" aria-hidden="true"></i>
                        <span>Hóa đơn</span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if (admin_has_permission('SCAN_QR')): ?>
                <li>
                    <a href="/admin/QuanLy/Scan_QR/scan_qr.php" class="<?php echo $isScanQr ? 'active' : ''; ?>">
                        <i class="fa-solid fa-receipt admin-nav-icon" aria-hidden="true"></i>
                        <span>SCAN QR</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if (admin_has_permission('TAIKHOAN')): ?>
                <li>
                    <a href="/admin/QuanLy/TaiKhoan/QL_TaiKhoan.php" class="<?php echo $isTaiKhoan ? 'active' : ''; ?>">
                        <i class="fa-solid fa-user-gear admin-nav-icon" aria-hidden="true"></i>
                        <span>Tài khoản</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if (admin_has_permission('DOCGIA')): ?>
                <li>
                    <a href="/admin/QuanLy/DocGia/QL_DocGia.php" class="<?php echo $isDocGia ? 'active' : ''; ?>">
                        <i class="fa-solid fa-users admin-nav-icon" aria-hidden="true"></i>
                        <span>Độc giả</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if (admin_has_permission('NHANVIEN')): ?>
                <li>
                    <a href="/admin/QuanLy/NhanVien/QL_NhanVien.php" class="<?php echo $isNhanVien ? 'active' : ''; ?>">
                        <i class="fa-solid fa-user-tie admin-nav-icon" aria-hidden="true"></i>
                        <span>Nhân viên</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if (admin_has_permission('TACGIA')): ?>
                <li>
                    <a href="/admin/QuanLy/TacGia/QL_TacGia.php" class="<?php echo $isTacGia ? 'active' : ''; ?>">
                        <i class="fa-solid fa-pen-nib admin-nav-icon" aria-hidden="true"></i>
                        <span>Tác giả</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if (admin_has_permission('THELOAI')): ?>
                <li>
                    <a href="/admin/QuanLy/Sach/TheLoai/QL_TheLoai.php" class="<?php echo $isTheLoai ? 'active' : ''; ?>">
                        <i class="fa-solid fa-tags admin-nav-icon" aria-hidden="true"></i>
                        <span>Thể loại</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if (admin_has_permission('NXB')): ?>
                <li>
                    <a href="/admin/QuanLy/Sach/NhaXuatBan/QL_NhaXuatBan.php" class="<?php echo $isNxb ? 'active' : ''; ?>">
                        <i class="fa-solid fa-building admin-nav-icon" aria-hidden="true"></i>
                        <span>NXB</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if (admin_has_permission('NHACUNGCAP')): ?>
                <li>
                    <a href="/admin/QuanLy/NhaCungCap/QL_NhaCungCap.php" class="<?php echo $isNhaCungCap ? 'active' : ''; ?>">
                        <i class="fa-solid fa-truck admin-nav-icon" aria-hidden="true"></i>
                        <span>Nhà cung cấp</span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if (admin_has_permission('THONGKE')): ?>
                <li>
                    <a href="/admin/QuanLy/ThongKe/thongke.php" class="<?php echo $isThongKe ? 'active' : ''; ?>">
                        <i class="fa-solid fa-chart-line admin-nav-icon" aria-hidden="true"></i>
                        <span>Thống kê</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>

    <div class="admin-sidebar-actions">
        <?php
        $displayName = $adminUsername;
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
    if (document.body) document.body.classList.add('admin-has-sidebar');
</script>
