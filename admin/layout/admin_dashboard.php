<?php
// admin_dashboard.php chỉ chứa NỘI DUNG trang quản trị (không include header/footer)
?>

<?php
require_once __DIR__ . '/../../database/ConnectDB.php';

$stats = [
    'titles' => 0,
    'copies' => 0,
    'borrowedCopies' => 0,
    'readers' => 0,
];

try {
    $conn = ConnectDB::getInstance()->getConnection();
    $conn->set_charset('utf8mb4');

    $countScalar = static function (mysqli $conn, string $sql): int {
        $result = $conn->query($sql);
        if (!$result) {
            return 0;
        }
        $row = $result->fetch_row();
        return isset($row[0]) ? (int) $row[0] : 0;
    };

    $stats['titles'] = $countScalar($conn, 'SELECT COUNT(*) FROM dausach');
    $stats['copies'] = $countScalar($conn, 'SELECT COUNT(*) FROM cuonsach');
    $stats['borrowedCopies'] = $countScalar($conn, "SELECT COUNT(*) FROM cuonsach WHERE trangthai='DangMuon'");
    $stats['readers'] = $countScalar($conn, 'SELECT COUNT(*) FROM docgia');
} catch (Throwable $e) {
    // Nếu DB chưa sẵn sàng, trang vẫn render với số 0.
}
?>

<section class="py-4 bg-light reveal-section">
    <div class="container-md">
        <div class="d-flex flex-wrap gap-2 align-items-end justify-content-between mb-3 section-title">
            <div>
                <h2 class="fw-bold mb-1">Trang quản trị thư viện</h2>
                <p class="text-muted mb-0">Tổng quan nhanh và lối tắt các phân hệ quản lý</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="/admin/QuanLy/ThongKe/thongke.php" class="btn btn-primary">
                    <i class="fa-solid fa-chart-line me-2" aria-hidden="true"></i>
                    <span>Thống kê</span>
                </a>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3 reveal-item section-item">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="text-muted small">Đầu sách</div>
                        <div class="fs-3 fw-bold"><?php echo number_format($stats['titles']); ?></div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 reveal-item section-item">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="text-muted small">Cuốn sách</div>
                        <div class="fs-3 fw-bold"><?php echo number_format($stats['copies']); ?></div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 reveal-item section-item">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="text-muted small">Đang mượn</div>
                        <div class="fs-3 fw-bold"><?php echo number_format($stats['borrowedCopies']); ?></div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 reveal-item section-item">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="text-muted small">Độc giả</div>
                        <div class="fs-3 fw-bold"><?php echo number_format($stats['readers']); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="admin-actions" class="py-5 reveal-section service-section">
    <div class="container-md">
        <div class="row align-items-center">
            <div class="col-lg-8 order-2 order-lg-1">
                <div class="row g-4">
                    <div class="col-md-6 reveal-item section-item">
                        <div class="benefit-card h-100">
                            <div class="service-icon">📚</div>
                            <h5 class="fw-bold">Quản lý đầu sách</h5>
                            <p class="mb-3">Thêm/sửa/xóa đầu sách, ảnh và thông tin mô tả.</p>
                            <a class="btn btn-sm btn-primary" href="/admin/QuanLy/Sach/DauSach/QL_DauSach.php">Mở phân hệ</a>
                        </div>
                    </div>

                    <div class="col-md-6 reveal-item section-item">
                        <div class="benefit-card h-100">
                            <div class="service-icon">🧾</div>
                            <h5 class="fw-bold">Hóa đơn</h5>
                            <p class="mb-3">Quản lý phiếu mượn/trả/phạt/nhập, xem chi tiết và in phiếu.</p>
                            <a class="btn btn-sm btn-primary" href="/admin/QuanLy/HoaDon/QL_HoaDon.php">Mở phân hệ</a>
                        </div>
                    </div>

                    <div class="col-md-6 reveal-item section-item">
                        <div class="benefit-card h-100">
                            <div class="service-icon">👥</div>
                            <h5 class="fw-bold">Quản lý độc giả</h5>
                            <p class="mb-3">Theo dõi và cập nhật thông tin độc giả.</p>
                            <a class="btn btn-sm btn-primary" href="/admin/QuanLy/DocGia/QL_DocGia.php">Mở phân hệ</a>
                        </div>
                    </div>

                    <div class="col-md-6 reveal-item section-item">
                        <div class="benefit-card h-100">
                            <div class="service-icon">🧑‍💼</div>
                            <h5 class="fw-bold">Quản lý nhân viên</h5>
                            <p class="mb-3">Quản lý thông tin nhân viên phục vụ hệ thống.</p>
                            <a class="btn btn-sm btn-primary" href="/admin/QuanLy/NhanVien/QL_NhanVien.php">Mở phân hệ</a>
                        </div>
                    </div>

                    <div class="col-md-6 reveal-item section-item">
                        <div class="benefit-card h-100">
                            <div class="service-icon">👤</div>
                            <h5 class="fw-bold">Tài khoản</h5>
                            <p class="mb-3">Quản lý tài khoản đăng nhập và nhóm quyền.</p>
                            <a class="btn btn-sm btn-primary" href="/admin/QuanLy/TaiKhoan/QL_TaiKhoan.php">Mở phân hệ</a>
                        </div>
                    </div>

                    <div class="col-md-6 reveal-item section-item">
                        <div class="benefit-card h-100">
                            <div class="service-icon">🏷️</div>
                            <h5 class="fw-bold">Danh mục (Tác giả / Thể loại / NXB)</h5>
                            <p class="mb-3">Quản lý dữ liệu nền phục vụ tra cứu và phân loại.</p>
                            <div class="d-flex gap-2 flex-wrap">
                                <a class="btn btn-sm btn-outline-primary" href="/admin/QuanLy/TacGia/QL_TacGia.php">Tác giả</a>
                                <a class="btn btn-sm btn-outline-primary" href="/admin/QuanLy/Sach/TheLoai/QL_TheLoai.php">Thể loại</a>
                                <a class="btn btn-sm btn-outline-primary" href="/admin/QuanLy/Sach/NhaXuatBan/QL_NhaXuatBan.php">NXB</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 reveal-item section-item">
                        <div class="benefit-card h-100">
                            <div class="service-icon">🚚</div>
                            <h5 class="fw-bold">Nhà cung cấp</h5>
                            <p class="mb-3">Quản lý danh sách nhà cung cấp và thông tin liên hệ.</p>
                            <a class="btn btn-sm btn-primary" href="/admin/QuanLy/NhaCungCap/QL_NhaCungCap.php">Mở phân hệ</a>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-lg-4 order-1 order-lg-2 mb-4 mb-lg-0 text-lg-end text-center reveal-item section-title">
                <h2 class="fw-bold mb-3">Chức năng quản trị</h2>
                <p class="text-muted mb-0">
                    Các lối tắt nhanh để bạn gắn thêm nghiệp vụ vào trang admin sau này.
                </p>
            </div>
        </div>
    </div>
</section>

<div id="back-to-top" class="back-to-top-bubble">
    <span class="material-symbols-outlined">chevron_line_up</span>
</div>
<script src="/assets/js/home.js" defer></script>
