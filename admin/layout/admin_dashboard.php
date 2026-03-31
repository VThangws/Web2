<?php
// admin_dashboard.php chỉ chứa NỘI DUNG trang admin (không include header/footer)
?>

<?php
$stats = [
    'titles' => 0,
    'copies' => 0,
    'borrowedCopies' => 0,
    'readers' => 0,
];

$chartCategoryLabels = [];
$chartCategoryCounts = [];

$chartStatusLabels = [];
$chartStatusCounts = [];

try {
    $conn = @new mysqli('localhost', 'root', '', 'db_quanlythuvien');
    if ($conn->connect_error) {
        throw new RuntimeException('DB connect failed');
    }

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

    $sqlCategory = "
        SELECT tl.tentheloai AS label, COUNT(ds.madausach) AS cnt
        FROM theloai tl
        LEFT JOIN dausach ds ON ds.matheloai = tl.matheloai
        GROUP BY tl.matheloai, tl.tentheloai
        ORDER BY cnt DESC, tl.tentheloai ASC
    ";
    if ($result = $conn->query($sqlCategory)) {
        while ($row = $result->fetch_assoc()) {
            $chartCategoryLabels[] = (string) ($row['label'] ?? '');
            $chartCategoryCounts[] = (int) ($row['cnt'] ?? 0);
        }
    }

    $sqlStatus = "
        SELECT COALESCE(trangthai, 'Khác') AS label, COUNT(*) AS cnt
        FROM cuonsach
        GROUP BY trangthai
        ORDER BY cnt DESC
    ";
    if ($result = $conn->query($sqlStatus)) {
        while ($row = $result->fetch_assoc()) {
            $chartStatusLabels[] = (string) ($row['label'] ?? '');
            $chartStatusCounts[] = (int) ($row['cnt'] ?? 0);
        }
    }
} catch (Throwable $e) {
    // Nếu DB chưa sẵn sàng, dashboard vẫn render với số 0.
}
?>

<section class="py-4 bg-light reveal-section">
    <div class="container-md">
        <div class="d-flex flex-wrap gap-2 align-items-end justify-content-between mb-3 section-title">
            <div>
                <h2 class="fw-bold mb-1">Thống kê</h2>
                <p class="text-muted mb-0">Số liệu tổng hợp từ hệ thống thư viện</p>
            </div>
            <div>
                <a href="#admin-actions" class="btn btn-primary">Vào phân hệ quản lý</a>
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

        <div class="row g-3">
            <div class="col-lg-7 reveal-item section-item">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h5 class="fw-bold mb-0">Đầu sách theo thể loại</h5>
                            <span class="text-muted small">(theo số lượng đầu sách)</span>
                        </div>
                        <div style="height: 320px;">
                            <canvas id="adminChartCategory" aria-label="Biểu đồ thể loại"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 reveal-item section-item">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h5 class="fw-bold mb-0">Trạng thái cuốn sách</h5>
                            <span class="text-muted small">(tổng hợp)</span>
                        </div>
                        <div style="height: 320px;">
                            <canvas id="adminChartCopyStatus" aria-label="Biểu đồ trạng thái"></canvas>
                        </div>
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
                            <p class="mb-3">Trang giao diện (backend sẽ bổ sung sau).</p>
                            <a class="btn btn-sm btn-primary" href="/admin/QuanLy/HoaDon/QL_HoaDon.php">Mở phân hệ</a>
                        </div>
                    </div>

                    <div class="col-md-6 reveal-item section-item">
                        <div class="benefit-card h-100">
                            <div class="service-icon">👥</div>
                            <h5 class="fw-bold">Quản lý bạn đọc</h5>
                            <p class="mb-3">Theo dõi và cập nhật thông tin độc giả.</p>
                            <a class="btn btn-sm btn-primary" href="/admin/QuanLy/DocGia/QL_DocGia.php">Mở phân hệ</a>
                        </div>
                    </div>

                    <div class="col-md-6 reveal-item section-item">
                        <div class="benefit-card h-100">
                            <div class="service-icon">🧑‍💼</div>
                            <h5 class="fw-bold">Quản lý nhân viên</h5>
                            <p class="mb-3">Quản lý tài khoản/nhân sự phục vụ hệ thống.</p>
                            <a class="btn btn-sm btn-primary" href="/admin/QuanLy/NhanVien/QL_NhanVien.php">Mở phân hệ</a>
                        </div>
                    </div>

                    <div class="col-md-6 reveal-item section-item">
                        <div class="benefit-card h-100">
                            <div class="service-icon">👤</div>
                            <h5 class="fw-bold">Tài khoản</h5>
                            <p class="mb-3">Trang giao diện (backend sẽ bổ sung sau).</p>
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
                            <p class="mb-3">Trang giao diện (backend sẽ bổ sung sau).</p>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js" defer></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof Chart === 'undefined') return;

    const categoryLabels = <?php echo json_encode($chartCategoryLabels, JSON_UNESCAPED_UNICODE); ?>;
    const categoryCounts = <?php echo json_encode($chartCategoryCounts, JSON_UNESCAPED_UNICODE); ?>;

    const statusLabels = <?php echo json_encode($chartStatusLabels, JSON_UNESCAPED_UNICODE); ?>;
    const statusCounts = <?php echo json_encode($chartStatusCounts, JSON_UNESCAPED_UNICODE); ?>;

    const categoryCanvas = document.getElementById('adminChartCategory');
    if (categoryCanvas) {
        new Chart(categoryCanvas, {
            type: 'bar',
            data: {
                labels: categoryLabels,
                datasets: [{
                    label: 'Số đầu sách',
                    data: categoryCounts,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                },
                scales: {
                    x: { ticks: { autoSkip: false } },
                    y: { beginAtZero: true, precision: 0 },
                },
            },
        });
    }

    const statusCanvas = document.getElementById('adminChartCopyStatus');
    if (statusCanvas) {
        new Chart(statusCanvas, {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusCounts,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' },
                },
            },
        });
    }
});
</script>

<script src="/assets/js/home.js" defer></script>
