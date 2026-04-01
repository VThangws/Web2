<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../DAO/DocGiaDAO.php';
require_once __DIR__ . '/../model/DocGia.php';

$docgiaDAO = new DocGiaDAO();

$user = $_SESSION['docgia'] ?? null;

// Helper hiển thị dữ liệu hoặc placeholder
function showData($value, $placeholder = "Chưa cập nhật") {
    return !empty($value) ? htmlspecialchars($value) : "<span class='text-muted'>$placeholder</span>";
}
?>
<link rel="stylesheet" href="assets/css/taikhoan.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<div class="container">
    <div class="profile-card">
        <div class="profile-header">
            <div class="profile-avatar">
                <i class="fas fa-user"></i>
            </div>
            <h3 class="mb-1"><?= $user ? htmlspecialchars($user->getHodocgia() . ' ' . $user->getTendocgia()) : "Người dùng" ?></h3>
            <div class="badge-reader">
                <i class="fas fa-id-card me-2"></i>Đọc giả
            </div>
        </div>

        <div class="profile-body">
            <!-- Mã độc giả (không thể sửa) -->
            <div class="info-group">
                <div class="info-label"><i class="fas fa-barcode"></i>Mã đọc giả</div>
                <div class="info-value">
                    <span class="badge bg-primary" style="font-size: 14px; padding: 8px 15px;">
                        <?= $user ? htmlspecialchars($user->getMaDocGia()) : "Chưa có" ?>
                    </span>
                </div>
            </div>

            <!-- Họ -->
            <div class="info-group">
                <div class="info-label"><i class="fas fa-user-circle"></i>Họ</div>
                <div class="info-value">
                    <input type="text" name="hodocgia" class="form-control" value="<?= $user ? htmlspecialchars($user->getHodocgia()) : '' ?>">
                </div>
            </div>

            <!-- Tên -->
            <div class="info-group">
                <div class="info-label"><i class="fas fa-signature"></i>Tên</div>
                <div class="info-value">
                    <input type="text" name="tendocgia" class="form-control" value="<?= $user ? htmlspecialchars($user->getTendocgia()) : '' ?>">
                </div>
            </div>

            <!-- Email -->
            <div class="info-group">
                <div class="info-label"><i class="fas fa-envelope"></i>Email</div>
                <div class="info-value">
                    <input type="email" name="email" class="form-control" value="<?= $user ? htmlspecialchars($user->getEmail()) : '' ?>">
                </div>
            </div>

            <!-- Ngày sinh -->
            <div class="info-group">
                <div class="info-label"><i class="fas fa-birthday-cake"></i>Ngày sinh</div>
                <div class="info-value">
                    <input type="date" name="ngaysinh" class="form-control" value="<?= $user ? htmlspecialchars($user->getNgaySinh()) : '' ?>">
                </div>
            </div>

            <!-- Địa chỉ -->
            <div class="info-group">
                <div class="info-label"><i class="fas fa-map-marker-alt"></i>Địa chỉ</div>
                <div class="info-value">
                    <input type="text" name="diachi" class="form-control" value="<?= $user ? htmlspecialchars($user->getDiaChi()) : '' ?>">
                </div>
            </div>

            <!-- Nút lưu -->
            <div class="text-center mt-4">
                <button id="btn-save" class="btn btn-edit"><i class="fas fa-save me-2"></i>Lưu thông tin</button>
                <div id="updateMessage" class="mt-2"></div>
            </div>
        </div>
    </div>
</div>