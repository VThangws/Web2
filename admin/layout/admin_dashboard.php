<?php
?>

<?php
require_once __DIR__ . '/../../database/ConnectDB.php';

$canManageAccounts = true;
if (function_exists('admin_has_permission')) {
    $canManageAccounts = admin_has_permission('TAIKHOAN');
}

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
}
?>

<section class="py-4 bg-light reveal-section admin-dashboard">
    <style>
        /* slide.css sets reveal-section to min-height:100vh + flex centering.
           For admin dashboard we want a single compact section without scroll-snap behavior. */
        .admin-dashboard {
            min-height: 100vh;
            display: block;
            align-items: stretch;
            scroll-snap-align: none;
        }

        .admin-dashboard .benefit-card {
            padding: 14px;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .admin-dashboard .dashboard-card-col {
            display: flex;
        }

        .admin-dashboard .benefit-actions {
            margin-top: auto;
        }

        .admin-dashboard .benefit-card .service-icon {
            font-size: 18px;
            line-height: 1;
            margin-bottom: 6px;
        }

        .admin-dashboard .benefit-card h5 {
            font-size: 1rem;
            margin-bottom: 6px;
        }

        .admin-dashboard .benefit-card p {
            font-size: 0.9rem;
            margin-bottom: 10px;
        }

        .admin-dashboard .stat-card .card-body {
            padding: 10px;
        }

        .admin-dashboard .stat-card .fs-3 {
            font-size: 1.5rem !important;
            line-height: 1.1;
        }

        .admin-dashboard .admin-side-title h2 {
            font-size: 1.6rem;
        }
    </style>

    <div class="container">
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

        <div class="row g-3 align-items-start">
            <div class="col-12 order-1 reveal-item section-title">
                <div class="text-lg-end text-center mb-3 admin-side-title">
                    <h2 class="fw-bold mb-2">Chức năng quản trị</h2>
                </div>

                <div class="row g-2 row-cols-2 row-cols-lg-4">
                    <div class="col reveal-item section-item">
                        <div class="card h-100 shadow-sm stat-card">
                            <div class="card-body">
                                <div class="text-muted small">Đầu sách</div>
                                <div class="fs-3 fw-bold"><?php echo number_format($stats['titles']); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col reveal-item section-item">
                        <div class="card h-100 shadow-sm stat-card">
                            <div class="card-body">
                                <div class="text-muted small">Cuốn sách</div>
                                <div class="fs-3 fw-bold"><?php echo number_format($stats['copies']); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col reveal-item section-item">
                        <div class="card h-100 shadow-sm stat-card">
                            <div class="card-body">
                                <div class="text-muted small">Đang mượn</div>
                                <div class="fs-3 fw-bold"><?php echo number_format($stats['borrowedCopies']); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col reveal-item section-item">
                        <div class="card h-100 shadow-sm stat-card">
                            <div class="card-body">
                                <div class="text-muted small">Độc giả</div>
                                <div class="fs-3 fw-bold"><?php echo number_format($stats['readers']); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 order-2">
                <div class="row g-3 row-cols-1 row-cols-sm-2 row-cols-lg-4 justify-content-lg-center">
                    <div class="col reveal-item section-item dashboard-card-col">
                        <div class="benefit-card h-100">
                            <div class="service-icon">📚</div>
                            <h5 class="fw-bold">Quản lý đầu sách</h5>
                            <p class="mb-3">Thêm/sửa/xóa đầu sách, ảnh và thông tin mô tả.</p>
                            <div class="benefit-actions">
                                <a class="btn btn-sm btn-primary" href="/admin/QuanLy/Sach/DauSach/QL_DauSach.php">Mở phân hệ</a>
                            </div>
                        </div>
                    </div>

                    <div class="col reveal-item section-item dashboard-card-col">
                        <div class="benefit-card h-100">
                            <div class="service-icon">🧾</div>
                            <h5 class="fw-bold">Hóa đơn</h5>
                            <p class="mb-3">Quản lý phiếu mượn/trả/phạt/nhập, xem chi tiết và in phiếu.</p>
                            <div class="benefit-actions">
                                <a class="btn btn-sm btn-primary" href="/admin/QuanLy/HoaDon/QL_HoaDon.php">Mở phân hệ</a>
                            </div>
                        </div>
                    </div>

                    <div class="col reveal-item section-item dashboard-card-col">
                        <div class="benefit-card h-100">
                            <div class="service-icon">👥</div>
                            <h5 class="fw-bold">Quản lý độc giả</h5>
                            <p class="mb-3">Theo dõi và cập nhật thông tin độc giả.</p>
                            <div class="benefit-actions">
                                <a class="btn btn-sm btn-primary" href="/admin/QuanLy/DocGia/QL_DocGia.php">Mở phân hệ</a>
                            </div>
                        </div>
                    </div>

                    <div class="col reveal-item section-item dashboard-card-col">
                        <div class="benefit-card h-100">
                            <div class="service-icon">🧑‍💼</div>
                            <h5 class="fw-bold">Quản lý nhân viên</h5>
                            <p class="mb-3">Quản lý thông tin nhân viên phục vụ hệ thống.</p>
                            <div class="benefit-actions">
                                <a class="btn btn-sm btn-primary" href="/admin/QuanLy/NhanVien/QL_NhanVien.php">Mở phân hệ</a>
                            </div>
                        </div>
                    </div>

                    <div class="col reveal-item section-item dashboard-card-col">
                        <div class="benefit-card h-100">
                            <div class="service-icon">👤</div>
                            <h5 class="fw-bold">Tài khoản</h5>
                            <p class="mb-3">Quản lý tài khoản đăng nhập và nhóm quyền.</p>
                            <div class="benefit-actions">
                                <a class="btn btn-sm btn-primary" href="/admin/QuanLy/TaiKhoan/QL_TaiKhoan.php">Mở phân hệ</a>
                            </div>
                        </div>
                    </div>

                    <div class="col reveal-item section-item dashboard-card-col">
                        <div class="benefit-card h-100">
                            <div class="service-icon">🛡️</div>
                            <h5 class="fw-bold">Phân quyền</h5>
                            <p class="mb-3">Thiết lập quyền theo nhóm chức năng quản trị.</p>
                            <div class="benefit-actions">
                                <a
                                    class="btn btn-sm <?php echo $canManageAccounts ? 'btn-primary' : 'btn-outline-secondary disabled'; ?>"
                                    href="<?php echo $canManageAccounts ? '/admin/QuanLy/TaiKhoan/QL_PhanQuyen.php' : '#'; ?>"
                                    <?php echo $canManageAccounts ? '' : 'tabindex="-1" aria-disabled="true"'; ?>
                                >Mở phân hệ</a>
                            </div>
                        </div>
                    </div>

                    <div class="col reveal-item section-item dashboard-card-col">
                        <div class="benefit-card h-100">
                            <div class="service-icon">🏷️</div>
                            <h5 class="fw-bold">Danh mục (Tác giả / Thể loại / NXB)</h5>
                            <p class="mb-3">Quản lý dữ liệu nền phục vụ tra cứu và phân loại.</p>
                            <div class="benefit-actions">
                                <div class="d-flex gap-2 flex-wrap">
                                    <a class="btn btn-sm btn-outline-primary" href="/admin/QuanLy/TacGia/QL_TacGia.php">Tác giả</a>
                                    <a class="btn btn-sm btn-outline-primary" href="/admin/QuanLy/Sach/TheLoai/QL_TheLoai.php">Thể loại</a>
                                    <a class="btn btn-sm btn-outline-primary" href="/admin/QuanLy/Sach/NhaXuatBan/QL_NhaXuatBan.php">NXB</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col reveal-item section-item dashboard-card-col">
                        <div class="benefit-card h-100">
                            <div class="service-icon">🚚</div>
                            <h5 class="fw-bold">Nhà cung cấp</h5>
                            <p class="mb-3">Quản lý danh sách nhà cung cấp và thông tin liên hệ.</p>
                            <div class="benefit-actions">
                                <a class="btn btn-sm btn-primary" href="/admin/QuanLy/NhaCungCap/QL_NhaCungCap.php">Mở phân hệ</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div id="back-to-top" class="back-to-top-bubble">
    <span class="material-symbols-outlined">chevron_line_up</span>
</div>
<script src="/assets/js/home.js" defer></script>
