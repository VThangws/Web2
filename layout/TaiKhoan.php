<?php
require_once __DIR__ . "/../model/DocGia.php";
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
$docgia = $_SESSION['docgia'] ?? null;

// Guard: nếu chưa đăng nhập thì redirect
if (!$docgia) {
  header("Location: /index.php?page=dangnhap");
  exit();
}
?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<link rel="stylesheet" href="assets/css/taikhoan.css">

<div class="modal fade" id="loginModal">
  <div class="modal-dialog modal-top-right">
    <div class="modal-content border-0 shadow-lg" id="modalContent">
      <div class="modal-body text-center p-3" id="loginMessage">
        <!-- Nội dung sẽ được thêm bằng JS -->
      </div>
    </div>
  </div>
</div>

<div class="profile-wrapper">

  <div class="container profile-container">

    <div class="row g-4">

      <!-- SIDEBAR -->
      <div class="col-lg-3">

        <div class="sidebar">

          <div class="list-group">

            <a href="#" class="list-group-item sidebar-item active" data-target="account">
              <span class="d-flex align-items-center gap-2">
                <i class="bi bi-person"></i> Thông tin tài khoản
              </span>
              <i class="bi bi-chevron-right"></i>
            </a>

            <a href="#" class="list-group-item sidebar-item" data-target="personal">
              <span class="d-flex align-items-center gap-2">
                <i class="bi bi-person-lines-fill"></i> Thông tin cá nhân
              </span>
              <i class="bi bi-chevron-right"></i>
            </a>

            <a href="#" class="list-group-item sidebar-item" data-target="orders">
              <span class="d-flex align-items-center gap-2">
                <i class="bi bi-bag"></i> Đơn hàng của bạn
              </span>
              <i class="bi bi-chevron-right"></i>
            </a>

            <a href="/ajax/logout.php" class="list-group-item">
              <span class="d-flex align-items-center gap-2">
                <i class="bi bi-box-arrow-right"></i> Đăng xuất
              </span>
              <i class="bi bi-chevron-right"></i>
            </a>

          </div>

        </div>

      </div>


      <!-- MAIN CONTENT -->
      <div class="col-lg-9">
        <!-- ACCOUNT -->
        <div class="info-card info-section" id="account">

          <h5>Thông tin tài khoản</h5>

          <div class="info-row">
            <span class="info-label">Email</span>
            <span class="info-value"><?= htmlspecialchars($docgia->getEmail()) ?></span>
          </div>

          <div class="info-row">
            <span class="info-label">Mật khẩu</span>
            <span class="info-value">••••••••</span>
          </div>

          <button class="btn btn-outline-dark mt-3"
            data-bs-toggle="collapse"
            data-bs-target="#changePasswordBox">
            Thay đổi mật khẩu
          </button>

          <!-- FORM CHANGE PASSWORD -->
          <div class="collapse mt-4" id="changePasswordBox">
            <div class="card card-body border-0 shadow-sm">
              <h6 class="mb-3">Đổi mật khẩu</h6>
              <div class="row g-3">
                <div class="col-12">
                  <label class="form-label">Mật khẩu hiện tại</label>
                  <input type="password"
                    class="form-control"
                    id="currentPassword">
                </div>

                <div class="col-md-6">
                  <label class="form-label">Mật khẩu mới</label>
                  <input type="password"
                    class="form-control"
                    id="newPassword">
                </div>

                <div class="col-md-6">
                  <label class="form-label">Nhập lại mật khẩu mới</label>
                  <input type="password"
                    class="form-control"
                    id="confirmPassword">
                </div>

                <div class="col-12 text-end">
                  <button id="btnSavePassword"
                    onclick="changePassword()"
                    class="btn btn-success">
                    Lưu mật khẩu
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- PERSONAL -->
        <div class="info-card info-section d-none" id="personal">

          <h5 class="mb-4">Thông tin cá nhân</h5>

          <div class="row">

            <!-- Mã độc giả -->
            <div class="col-md-6">
              <div class="info-row">
                <span class="info-label">Mã độc giả</span>
                <span class="info-value" id="madocgia"><?= htmlspecialchars($docgia->getMadocgia()) ?></span>
              </div>
            </div>

            <!-- Họ tên -->
            <div class="col-md-6">
              <div class="info-row">
                <span class="info-label">Họ và tên</span>
                <span class="info-value" id="fullname"><?= htmlspecialchars($docgia->getHodocgia() . ' ' . $docgia->getTendocgia()) ?></span>
              </div>
            </div>

            <!-- SĐT -->
            <div class="col-md-6">
              <div class="info-row">
                <span class="info-label">Số điện thoại</span>
                <span class="info-value" id="sdt"><?= htmlspecialchars($docgia->getSdt() ?: 'Chưa có thông tin') ?></span>
              </div>
            </div>

            <!-- Email -->
            <div class="col-md-6">
              <div class="info-row">
                <span class="info-label">Email</span>
                <span class="info-value" id="email"><?= htmlspecialchars($docgia->getEmail()) ?></span>
              </div>
            </div>

            <!-- Địa chỉ -->
            <div class="col-12">
              <div class="info-row">
                <span class="info-label">Địa chỉ</span>
                <span class="info-value" id="diachi_show"><?= htmlspecialchars($docgia->getDiachi()) ?></span>
              </div>
            </div>

          </div>

          <!-- Thêm vào button "Cập nhật thông tin", truyền data từ PHP session -->
          <button class="btn btn-outline-dark mt-4"
            data-bs-toggle="collapse"
            data-bs-target="#updateBox"
            id="btnOpenUpdate"
            data-ho="<?= htmlspecialchars($docgia->getHodocgia()) ?>"
            data-ten="<?= htmlspecialchars($docgia->getTendocgia()) ?>"
            data-sdt="<?= htmlspecialchars($docgia->getSdt()) ?>"
            data-diachi="<?= htmlspecialchars($docgia->getDiachi()) ?>">
            Cập nhật thông tin
          </button>

          <!-- Form update info -->
          <div class="collapse mt-4" id="updateBox">

            <div class="card card-body border-0 shadow-sm">

              <h6 class="mb-3">Cập nhật thông tin</h6>

              <div class="row g-3">

                <!-- Mã độc giả (không cho sửa) -->
                <div class="col-md-6">
                  <label class="form-label">Mã độc giả</label>
                  <input type="text" class="form-control"
                    value="<?= htmlspecialchars($docgia->getMadocgia()) ?>"
                    onclick="showModal(false,'Mã độc giả không thể thay đổi')"
                    readonly>
                </div>

                <!-- Email (không cho sửa) -->
                <div class="col-md-6">
                  <label class="form-label">Email</label>
                  <input type="text" class="form-control"
                    value="<?= htmlspecialchars($docgia->getEmail()) ?>"
                    onclick="showModal(false,'Email không thể thay đổi')"
                    readonly>
                </div>

                <!-- Họ -->
                <div class="col-md-6">
                  <label class="form-label">Họ</label>
                  <input type="text" class="form-control" id="hodocgia">
                </div>

                <!-- Tên -->
                <div class="col-md-6">
                  <label class="form-label">Tên</label>
                  <input type="text" class="form-control" id="tendocgia">
                </div>

                <!-- SĐT -->
                <div class="col-md-6">
                  <label class="form-label">SĐT</label>
                  <input type="text" class="form-control" id="sdt_input">
                </div>

                <!-- Tỉnh — chiếm nửa hàng, nằm cạnh SĐT -->
                <div class="col-md-6">
                  <label class="form-label">Tỉnh/Thành</label>
                  <select id="tinh" class="form-select" onchange="loadQuan(this.value)">
                    <option>-- Chọn tỉnh/thành --</option>
                  </select>
                </div>

                <!-- Quận — col-md-6 -->
                <div class="col-md-6">
                  <label class="form-label">Quận/Huyện</label>
                  <select id="quan" class="form-select" onchange="loadPhuong(this.value)">
                    <option>-- Chọn quận/huyện --</option>
                  </select>
                </div>

                <!-- Phường — col-md-6 -->
                <div class="col-md-6">
                  <label class="form-label">Phường/Xã</label>
                  <select id="phuong" class="form-select">
                    <option>-- Chọn phường/xã --</option>
                  </select>
                </div>

                <!-- Địa chỉ cụ thể -->
                <div class="col-12">
                  <label class="form-label">Địa chỉ cụ thể</label>
                  <input type="text" class="form-control" id="diachi">
                </div>

                <div class="col-12 text-end">
                  <button id="btnSave"
                    onclick="updateInfo()"
                    class="btn btn-success">
                    Lưu thông tin
                  </button>
                </div>
              </div>
            </div>
          </div>
          <!-- kết thúc form -->
        </div>
        <!-- ORDERS -->
        <div class="info-card info-section d-none" id="orders">

          <h5>Đơn hàng của bạn</h5>

          <p class="text-muted">
            Bạn chưa có đơn hàng nào.
          </p>

        </div>

      </div>

    </div>

  </div>

</div>

<script src="assets/js/profileAcc.js"></script>