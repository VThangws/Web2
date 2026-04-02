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

                <div class="sidebar-item">
                    <span><i class="bi bi-box-arrow-right"></i> Đăng xuất</span>
                    <i class="bi bi-chevron-right"></i>
                </div>

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

                                <div class="col-12">
                                    <label class="form-label">Địa chỉ</label>
                                    <input class="form-control" id="diachi" value="<?= $docgia->getDiachi() ?>">
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
                        <span class="info-value">********</span>
                    </div>

                    <button class="btn-update mt-4">Thay đổi mật khẩu</button>

                </div>

            </div>
        </div>

    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    document.querySelectorAll('.sidebar-item').forEach(item => {
        item.addEventListener('click', function() {
            document.querySelectorAll('.sidebar-item').forEach(i => i.classList.remove('active'));
            this.classList.add('active');
        });
    });

    function updateInfo() {
        const btn = $("#btnSave");
        // disable nút
        btn.prop("disabled", true);
        btn.html('<span class="spinner-border spinner-border-sm me-2"></span>Đang lưu...');

        $.ajax({
            url: "../ajax/updateProfile.php",
            type: "POST",
            dataType: "json",
            data: {
                hodocgia: $("#hodocgia").val(),
                tendocgia: $("#tendocgia").val(),
                sdt: $("#sdt_input").val(),
                ngaysinh: $("#ngaysinh").val(),
                diachi: $("#diachi").val()
            },
            success: function(data) {
                if (data.success) {
                    showModal(true, data.message);
                    $("#fullname").text(data.user.hodocgia + " " + data.user.tendocgia);
                    $("#sdt").text(data.user.sdt || "Chưa có thông tin");
                    $("#hodocgia").val(data.user.hodocgia);
                    $("#tendocgia").val(data.user.tendocgia);
                    $("#sdt_input").val(data.user.sdt);
                    setTimeout(function() {
                        const collapse = bootstrap.Collapse.getOrCreateInstance(
                            document.getElementById('updateBox')
                        );
                        collapse.hide();
                    }, 2000);
                } else {
                    showModal(false, data.message);
                }
            },
            error: function() {
                showModal(false, "Không thể kết nối server");
            },
            complete: function() {
                // bật lại nút
                btn.prop("disabled", false);
                btn.html("Lưu");
            }
        });
    }

    // ===== MODAL NOTICE =====
    function showModal(isSuccess, message) {
        const modalContent = document.getElementById("modalContent");
        const loginMessage = document.getElementById("loginMessage");

        if (isSuccess) {
            modalContent.className = "modal-content border-0 shadow-lg modal-success";
            loginMessage.innerHTML = `<h4>${message}</h4>`;
        } else {
            modalContent.className = "modal-content border-0 shadow-lg modal-error";
            loginMessage.innerHTML = `<h4>${message}</h4>`;
        }

        const modalInstance = bootstrap.Modal.getOrCreateInstance(document.getElementById('loginModal'), {
            backdrop: false
        });
        modalInstance.show();

        // Tự động đóng sau X giây
        setTimeout(() => {
            modalInstance.hide();
        }, 2000);
    }
</script>