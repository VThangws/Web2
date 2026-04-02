<?php
require_once __DIR__ . '/../model/DocGia.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$docgia = $_SESSION['docgia'] ?? null;

if (!$docgia) {
    header("Location: index.php");
    exit();
}
?>

<link rel="stylesheet" href="assets/css/taikhoan.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<div class="container-main">
    <div class="row g-0">
        <!-- Sidebar -->
        <div class="col-lg-3 col-md-4">
            <div class="sidebar">
                <div class="sidebar-item active">
                    <span><i class="bi bi-person"></i> Thông tin tài khoản</span>
                    <i class="bi bi-chevron-right"></i>
                </div>
                <div class="sidebar-item">
                    <span><i class="bi bi-receipt"></i> Đơn hàng của bạn</span>
                    <i class="bi bi-chevron-right"></i>
                </div>
                <div class="sidebar-item">
                    <span><i class="bi bi-geo-alt"></i> Danh sách địa chỉ</span>
                    <i class="bi bi-chevron-right"></i>
                </div>
                <a href="/ajax/logout.php">
                    <div class="sidebar-item">
                        <span><i class="bi bi-box-arrow-right"></i> Đăng xuất</span>
                        <i class="bi bi-chevron-right"></i>
                    </div>
                </a>
            </div>
        </div>

        <!-- Content -->
        <div class="col-lg-9 col-md-8">
            <div class="content-area">

                <!-- Account Information Section -->
                <div class="info-section">
                    <h2 class="section-title">Thông tin tài khoản</h2>

                    <div class="info-row">
                        <span class="info-label">Họ và tên</span>
                        <span class="info-value" id="fullname">
                            <?= $docgia->getHodocgia() . ' ' . $docgia->getTendocgia() ?>
                        </span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Số điện thoại</span>
                        <span class="info-value" id="sdt">
                            <?= $docgia->getSdt() ?: "Chưa có thông tin" ?>
                        </span>
                    </div>

                    <button class="btn-update mt-4" data-bs-toggle="collapse" data-bs-target="#updateBox">
                        Cập nhật thông tin
                    </button>

                    <!-- FORM UPDATE ẨN -->
                    <div class="collapse mt-3" id="updateBox">
                        <div class="card card-body shadow-sm">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Họ</label>
                                    <input class="form-control" id="hodocgia" value="<?= $docgia->getHodocgia() ?>">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Tên</label>
                                    <input class="form-control" id="tendocgia" value="<?= $docgia->getTendocgia() ?>">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Số điện thoại</label>
                                    <input class="form-control" id="sdt_input" value="<?= $docgia->getSdt() ?>">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Ngày sinh</label>
                                    <input type="date" class="form-control" id="ngaysinh" value="<?= $docgia->getNgaysinh() ?>">
                                </div>

                                <!-- 3 Ô TỈNH / QUẬN / PHƯỜNG -->
                                <div class="col-md-4">
                                    <label class="form-label">Tỉnh / Thành phố</label>
                                    <select class="form-select" id="tinh" onchange="loadQuan(this.value)">
                                        <option value="">-- Chọn tỉnh/thành --</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Quận / Huyện</label>
                                    <select class="form-select" id="quan" onchange="loadPhuong(this.value)" disabled>
                                        <option value="">-- Chọn quận/huyện --</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Phường / Xã</label>
                                    <select class="form-select" id="phuong" disabled>
                                        <option value="">-- Chọn phường/xã --</option>
                                    </select>
                                </div>
                                <!-- END 3 Ô -->

                                <div class="col-12">
                                    <label class="form-label">Địa chỉ (số nhà, tên đường)</label>
                                    <input class="form-control" id="diachi" value="<?= $docgia->getDiachi() ?>" placeholder="VD: 123 Nguyễn Trãi">
                                </div>

                                <div class="col-12 text-end">
                                    <button type="button" id="btnSave" class="btn btn-primary" onclick="updateInfo()">Lưu</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Login Information -->
                <div class="info-section">
                    <h2 class="section-title">Thông tin đăng nhập</h2>

                    <div class="info-row">
                        <span class="info-label">Email</span>
                        <span class="info-value">
                            <?= $docgia->getEmail() ?>
                        </span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Mật khẩu</span>
                        <span class="info-value">••••••••</span>
                    </div>

                    <button class="btn-update mt-4" data-bs-toggle="collapse" data-bs-target="#changePasswordBox">
                        Thay đổi mật khẩu
                    </button>

                    <!-- FORM ĐỔI MẬT KHẨU ẨN -->
                    <div class="collapse mt-3" id="changePasswordBox">
                        <div class="card card-body shadow-sm">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label">Mật khẩu hiện tại</label>
                                    <input type="password" class="form-control" id="currentPassword" placeholder="Nhập mật khẩu hiện tại">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Mật khẩu mới</label>
                                    <input type="password" class="form-control" id="newPassword" placeholder="Nhập mật khẩu mới">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Xác nhận mật khẩu mới</label>
                                    <input type="password" class="form-control" id="confirmPassword" placeholder="Nhập lại mật khẩu mới">
                                </div>

                                <div class="col-12 text-end">
                                    <button type="button" id="btnSavePassword" class="btn btn-primary" onclick="changePassword()">Lưu mật khẩu</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="/assets/js/profileAcc.js" defer></script>
