<?php
require_once __DIR__ . "/../model/DocGia.php";
require_once __DIR__ . "/../database/ConnectDB.php";
require_once __DIR__ . "/../DAO/DocGiaDAO.php";

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
$docgia = $_SESSION['docgia'] ?? null;

// Guard: nếu chưa đăng nhập thì redirect
if (!$docgia) {
  header("Location: /index.php?page=dangnhap");
  exit();
}

$conn = ConnectDB::getInstance()->getConnection();
$madocgia = $docgia->getMadocgia();

// Auto-check vi phạm
$tendangnhap = isset($_SESSION['taikhoan']) ? $_SESSION['taikhoan']->getTendangnhap() : $docgia->getEmail();
$daoDocGia = new DocGiaDAO();
$viPham = $daoDocGia->kiemTraViPhamDocGia($tendangnhap, $madocgia);

if ($viPham['locked']) {
    unset($_SESSION['docgia']);
    unset($_SESSION['taikhoan']);
    echo "<script>alert('Tài Khoản Bị Khóa Lỗi Tự Động: " . implode(" ", $viPham['reasons']) . " Vui lòng đến thư viện để giải quyết!'); window.location.href='/';</script>";
    exit();
}
$warnings = $viPham['warnings'] ?? [];

// Phiếu mượn
$stmt = $conn->prepare("SELECT mamuon, ngaymuon, ngayhethan, trangthai FROM phieumuon WHERE madocgia = ? ORDER BY ngaymuon DESC");
$stmt->bind_param('s', $madocgia);
$stmt->execute();
$phieus = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Phiếu trả
$stmtTra = $conn->prepare("SELECT pt.matra, pt.mamuon, pt.ngaytra, pt.tongtienphat FROM phieutra pt JOIN phieumuon pm ON pt.mamuon = pm.mamuon WHERE pm.madocgia = ? ORDER BY pt.ngaytra DESC");
$stmtTra->bind_param('s', $madocgia);
$stmtTra->execute();
$phieutra = $stmtTra->get_result()->fetch_all(MYSQLI_ASSOC);
$stmtTra->close();

// Phiếu phạt
$stmtPhat = $conn->prepare("SELECT maphat, matra, ngaylap, tongtienphat, trangthai FROM phieuphat WHERE madocgia = ? ORDER BY ngaylap DESC");
$stmtPhat->bind_param('s', $madocgia);
$stmtPhat->execute();
$phieuphat = $stmtPhat->get_result()->fetch_all(MYSQLI_ASSOC);
$stmtPhat->close();
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

        <?php if (!empty($warnings)): ?>
          <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" style="font-size: 1.5rem;"></i>
            <div>
              <h6 class="alert-heading fw-bold mb-1">Cảnh báo hệ thống!</h6>
              <ul class="mb-0 ps-3">
                <?php foreach ($warnings as $w): ?>
                  <li><?= htmlspecialchars($w) ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
        <?php endif; ?>

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

          <h5>Lịch sử hoạt động</h5>

          <ul class="nav nav-tabs mt-4" id="orderTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link text-success fw-semibold active" data-bs-toggle="tab" data-bs-target="#tab-muon" type="button">Phiếu mượn</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link text-info fw-semibold" data-bs-toggle="tab" data-bs-target="#tab-tra" type="button">Phiếu trả</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link text-danger fw-semibold" data-bs-toggle="tab" data-bs-target="#tab-phat" type="button">Phiếu phạt</button>
            </li>
          </ul>

          <div class="tab-content border border-top-0 p-3 bg-white rounded-bottom">
            <!-- TAB MƯỢN -->
            <div class="tab-pane fade show active" id="tab-muon">
              <?php if (empty($phieus)): ?>
                <p class="text-muted mt-2">Bạn chưa mượn cuốn sách nào.</p>
              <?php else: ?>
                <div class="table-responsive">
                  <table class="table table-hover align-middle">
                    <thead class="table-light">
                      <tr>
                        <th>Mã phiếu</th>
                        <th>Ngày mượn</th>
                        <th>Ngày đến hạn</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($phieus as $p): ?>
                        <tr>
                          <td class="fw-bold text-success">#<?= htmlspecialchars($p['mamuon']) ?></td>
                          <td><?= htmlspecialchars($p['ngaymuon'] ? date('d/m/Y', strtotime($p['ngaymuon'])) : '') ?></td>
                          <td><?= htmlspecialchars($p['ngayhethan'] ? date('d/m/Y', strtotime($p['ngayhethan'])) : '') ?></td>
                          <td>
                            <?php
                            $st = mb_strtolower(trim($p['trangthai']), 'UTF-8');
                            if (in_array($st, ['choduyet', 'chờ duyệt'])) echo '<span class="badge bg-info text-dark">Chờ duyệt</span>';
                            elseif (in_array($st, ['dangmuon', 'đang mượn', 'dangmuon'])) echo '<span class="badge bg-warning text-dark">Đang mượn</span>';
                            elseif (in_array($st, ['datra', 'đã trả', 'datra'])) echo '<span class="badge bg-success">Đã trả</span>';
                            elseif (in_array($st, ['dahuy', 'đã hủy', 'huy'])) echo '<span class="badge bg-danger">Đã hủy</span>';
                            else echo '<span class="badge bg-secondary">' . htmlspecialchars($p['trangthai']) . '</span>';
                            ?>
                          </td>
                          <td>
                            <button class="btn btn-sm btn-outline-success text-nowrap" onclick="viewTicket('muon','<?= htmlspecialchars($p['mamuon']) ?>')">
                              <i class="bi bi-eye"></i> Chi tiết
                            </button>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              <?php endif; ?>
            </div>

            <!-- TAB TRẢ -->
            <div class="tab-pane fade" id="tab-tra">
              <?php if (empty($phieutra)): ?>
                <p class="text-muted mt-2">Bạn chưa có phiếu trả nào.</p>
              <?php else: ?>
                <div class="table-responsive">
                  <table class="table table-hover align-middle">
                    <thead class="table-light">
                      <tr>
                        <th>Mã trả</th>
                        <th>Mã mượn</th>
                        <th>Ngày trả</th>
                        <th>Tổng tiền phạt</th>
                        <th>Thao tác</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($phieutra as $t): ?>
                        <tr>
                          <td class="fw-bold text-info">#<?= htmlspecialchars($t['matra']) ?></td>
                          <td><?= htmlspecialchars($t['mamuon']) ?></td>
                          <td><?= htmlspecialchars($t['ngaytra'] ? date('d/m/Y', strtotime($t['ngaytra'])) : '') ?></td>
                          <td class="text-danger fw-semibold"><?= number_format($t['tongtienphat'], 0, ',', '.') ?> đ</td>
                          <td>
                            <button class="btn btn-sm btn-outline-info text-nowrap" onclick="viewTicket('tra','<?= htmlspecialchars($t['matra']) ?>')">
                              <i class="bi bi-eye"></i> Chi tiết
                            </button>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              <?php endif; ?>
            </div>

            <!-- TAB PHẠT -->
            <div class="tab-pane fade" id="tab-phat">
              <?php if (empty($phieuphat)): ?>
                <p class="text-muted mt-2">Bạn chưa có phiếu phạt nào.</p>
              <?php else: ?>
                <div class="table-responsive">
                  <table class="table table-hover align-middle">
                    <thead class="table-light">
                      <tr>
                        <th>Mã phạt</th>
                        <th>Mã trả</th>
                        <th>Ngày lập</th>
                        <th>Tiền phạt</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($phieuphat as $pf): ?>
                        <tr>
                          <td class="fw-bold text-danger">#<?= htmlspecialchars($pf['maphat']) ?></td>
                          <td><?= htmlspecialchars($pf['matra']) ?></td>
                          <td><?= htmlspecialchars($pf['ngaylap'] ? date('d/m/Y', strtotime($pf['ngaylap'])) : '') ?></td>
                          <td class="text-danger fw-semibold"><?= number_format($pf['tongtienphat'], 0, ',', '.') ?> đ</td>
                          <td>
                            <?php
                            $st = mb_strtolower(trim($pf['trangthai']), 'UTF-8');
                            if (in_array($st, ['chuadong', 'chưa đóng'])) echo '<span class="badge bg-danger">Chưa đóng</span>';
                            elseif (in_array($st, ['dadong', 'đã đóng'])) echo '<span class="badge bg-success">Đã đóng</span>';
                            else echo '<span class="badge bg-secondary">' . htmlspecialchars($pf['trangthai']) . '</span>';
                            ?>
                          </td>
                          <td>
                            <button class="btn btn-sm btn-outline-danger text-nowrap" onclick="viewTicket('phat','<?= htmlspecialchars($pf['maphat']) ?>')">
                              <i class="bi bi-eye"></i> Chi tiết
                            </button>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

</div>

<!-- Modal Chi Tiết Phiếu -->
<div class="modal fade" id="ticketDetailModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Chi tiết phiếu mượn: <span id="td_mamuon" class="text-primary fw-bold"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-bordered align-middle mb-0" id="ticketDetailTable">
            <thead class="table-light" id="ticketDetailHead">
              <!-- Render logic -->
            </thead>
            <tbody id="ticketDetailBody">
              <!-- Dữ liệu render qua JS -->
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
      </div>
    </div>
  </div>
</div>

<script src="assets/js/profileAcc.js"></script>