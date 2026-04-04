<?php
require_once __DIR__ . '/../../login/auth.php';
require_admin_login();
require_admin_permission('DASHBOARD');
require_once __DIR__ . '/../../../database/ConnectDB.php';

function get_date_param(string $key, string $default): string
{
    $raw = trim((string)($_GET[$key] ?? ''));
    if ($raw === '') {
        return $default;
    }
    $dt = DateTime::createFromFormat('Y-m-d', $raw);
    if ($dt === false) {
        return $default;
    }
    return $dt->format('Y-m-d');
}

function get_top_param(string $key, int $default): int
{
    $raw = trim((string)($_GET[$key] ?? ''));
    if ($raw === '') {
        return $default;
    }

    if (!ctype_digit($raw)) {
        return $default;
    }

    $value = (int)$raw;
    if ($value < 1) {
        return 1;
    }
    if ($value > 50) {
        return 50;
    }

    return $value;
}

$today = new DateTime('today');
$defaultTo = $today->format('Y-m-d');
$defaultFrom = (clone $today)->modify('-29 days')->format('Y-m-d');

$statsFrom = get_date_param('stats_from', $defaultFrom);
$statsTo = get_date_param('stats_to', $defaultTo);
$sharedTop = get_top_param('top', 10);
$topBooksLimit = get_top_param('top_books', $sharedTop);
$topReadersLimit = get_top_param('top_readers', $sharedTop);

try {
    $fromDt = new DateTime($statsFrom);
    $toDt = new DateTime($statsTo);
    if ($fromDt > $toDt) {
        [$statsFrom, $statsTo] = [$statsTo, $statsFrom];
    }
} catch (Throwable $e) {
    $statsFrom = $defaultFrom;
    $statsTo = $defaultTo;
}

$exportFormat = strtolower(trim((string)($_GET['format'] ?? 'pdf')));
if (!in_array($exportFormat, ['pdf', 'excel'], true)) {
    $exportFormat = 'pdf';
}

$stats = [
    'titles' => 0,
    'copies' => 0,
    'borrowedCopies' => 0,
    'readers' => 0,
];

$borrowStatusOrder = [
    'ChoDuyet' => 'Chờ duyệt',
    'DangMuon' => 'Đang mượn',
    'DaTra' => 'Đã trả',
    'Huy' => 'Hủy',
];
$borrowStatusCounts = array_fill_keys(array_keys($borrowStatusOrder), 0);
$borrowDailyLabels = [];
$borrowDailyCounts = [];
$topBorrowedBooks = [];
$topBorrowingReaders = [];

try {
    $conn = ConnectDB::getInstance()->getConnection();
    $conn->set_charset('utf8mb4');

    $countScalar = static function (mysqli $conn, string $sql): int {
        $result = $conn->query($sql);
        if (!$result) {
            return 0;
        }
        $row = $result->fetch_row();
        $result->free();
        return isset($row[0]) ? (int) $row[0] : 0;
    };

    $stats['titles'] = $countScalar($conn, 'SELECT COUNT(*) FROM dausach');
    $stats['copies'] = $countScalar($conn, 'SELECT COUNT(*) FROM cuonsach');
    $stats['borrowedCopies'] = $countScalar($conn, "SELECT COUNT(*) FROM cuonsach WHERE trangthai='DangMuon'");
    $stats['readers'] = $countScalar($conn, 'SELECT COUNT(*) FROM docgia');

    $fromTs = $statsFrom . ' 00:00:00';
    $toTs = $statsTo . ' 23:59:59';

    if ($stmt = $conn->prepare('SELECT trangthai, COUNT(*) AS cnt FROM phieumuon WHERE ngaymuon BETWEEN ? AND ? GROUP BY trangthai')) {
        $stmt->bind_param('ss', $fromTs, $toTs);
        $stmt->execute();
        if ($res = $stmt->get_result()) {
            while ($row = $res->fetch_assoc()) {
                $key = (string)($row['trangthai'] ?? '');
                if ($key !== '' && array_key_exists($key, $borrowStatusCounts)) {
                    $borrowStatusCounts[$key] = (int)($row['cnt'] ?? 0);
                }
            }
            $res->free();
        }
        $stmt->close();
    }

    $dailyMap = [];
    if ($stmt = $conn->prepare('SELECT DATE(ngaymuon) AS d, COUNT(*) AS cnt FROM phieumuon WHERE ngaymuon BETWEEN ? AND ? GROUP BY DATE(ngaymuon) ORDER BY d ASC')) {
        $stmt->bind_param('ss', $fromTs, $toTs);
        $stmt->execute();
        if ($res = $stmt->get_result()) {
            while ($row = $res->fetch_assoc()) {
                $d = (string)($row['d'] ?? '');
                if ($d !== '') {
                    $dailyMap[$d] = (int)($row['cnt'] ?? 0);
                }
            }
            $res->free();
        }
        $stmt->close();
    }

    $fromIter = new DateTime($statsFrom);
    $toIter = new DateTime($statsTo);
    $days = (int)$fromIter->diff($toIter)->days;
    if ($days > 365) {
        $fromIter = (clone $toIter)->modify('-365 days');
        $statsFrom = $fromIter->format('Y-m-d');
        $fromTs = $statsFrom . ' 00:00:00';
    }

    $cursor = clone $fromIter;
    while ($cursor <= $toIter) {
        $d = $cursor->format('Y-m-d');
        $borrowDailyLabels[] = $d;
        $borrowDailyCounts[] = (int)($dailyMap[$d] ?? 0);
        $cursor->modify('+1 day');
    }

    $sqlTopBooks = "SELECT ds.madausach, ds.tensach, COUNT(*) AS cnt
                    FROM ctphieumuon ct
                    JOIN phieumuon pm ON pm.mamuon = ct.mamuon
                    JOIN cuonsach cs ON cs.macuonsach = ct.macuonsach
                    JOIN dausach ds ON ds.madausach = cs.madausach
                    WHERE pm.ngaymuon BETWEEN ? AND ?
                    GROUP BY ds.madausach, ds.tensach
                    ORDER BY cnt DESC
                    LIMIT {$topBooksLimit}";
    if ($stmt = $conn->prepare($sqlTopBooks)) {
        $stmt->bind_param('ss', $fromTs, $toTs);
        $stmt->execute();
        $res = $stmt->get_result();
        $topBorrowedBooks = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
        if ($res) {
            $res->free();
        }
        $stmt->close();
    }

    $sqlTopReaders = "SELECT pm.madocgia,
                             CONCAT(COALESCE(dg.hodocgia,''),' ',COALESCE(dg.tendocgia,'')) AS ten_docgia,
                             COUNT(*) AS cnt
                      FROM phieumuon pm
                      LEFT JOIN docgia dg ON dg.madocgia = pm.madocgia
                      WHERE pm.ngaymuon BETWEEN ? AND ?
                      GROUP BY pm.madocgia, dg.hodocgia, dg.tendocgia
                      ORDER BY cnt DESC
                      LIMIT {$topReadersLimit}";
    if ($stmt = $conn->prepare($sqlTopReaders)) {
        $stmt->bind_param('ss', $fromTs, $toTs);
        $stmt->execute();
        $res = $stmt->get_result();
        $topBorrowingReaders = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
        if ($res) {
            $res->free();
        }
        $stmt->close();
    }

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
        $result->free();
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
        $result->free();
    }
} catch (Throwable $e) {
}
?>

<section class="py-4 bg-light reveal-section">
    <div class="container-md">
        <div class="d-flex flex-wrap gap-2 align-items-end justify-content-between mb-3 section-title">
            <div>
                <h2 class="fw-bold mb-1">Thống kê</h2>
                <p class="text-muted mb-0">Số liệu tổng hợp từ hệ thống thư viện</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="/admin/QuanLy/ThongKe/export.php?format=pdf&amp;<?php echo htmlspecialchars(http_build_query(['stats_from' => $statsFrom, 'stats_to' => $statsTo, 'top_books' => $topBooksLimit, 'top_readers' => $topReadersLimit]), ENT_QUOTES); ?>" class="btn btn-outline-danger">
                    <i class="fa-solid fa-file-pdf me-1"></i>Xuất PDF
                </a>
                <a href="/admin/QuanLy/ThongKe/export.php?format=excel&amp;<?php echo htmlspecialchars(http_build_query(['stats_from' => $statsFrom, 'stats_to' => $statsTo, 'top_books' => $topBooksLimit, 'top_readers' => $topReadersLimit]), ENT_QUOTES); ?>" class="btn btn-outline-success">
                    <i class="fa-solid fa-file-excel me-1"></i>Xuất Excel
                </a>
                <a href="/admin/adminMenu.php" class="btn btn-outline-secondary">Trang quản trị</a>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form class="row g-2 align-items-end" method="get" action="/admin/QuanLy/ThongKe/thongke.php">
                    <div class="col-6 col-md-3">
                        <label class="form-label mb-1">Từ ngày</label>
                        <input class="form-control" type="date" name="stats_from" value="<?php echo htmlspecialchars($statsFrom, ENT_QUOTES); ?>">
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label mb-1">Đến ngày</label>
                        <input class="form-control" type="date" name="stats_to" value="<?php echo htmlspecialchars($statsTo, ENT_QUOTES); ?>">
                    </div>
                    <input type="hidden" name="top_books" value="<?php echo (int)$topBooksLimit; ?>">
                    <input type="hidden" name="top_readers" value="<?php echo (int)$topReadersLimit; ?>">
                    <div class="col-12 col-md-3 d-grid">
                        <button class="btn btn-outline-primary" type="submit">Áp dụng</button>
                    </div>
                </form>
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

        <div class="row g-3 mt-1">
            <div class="col-lg-5 reveal-item section-item">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Phiếu mượn theo trạng thái</h5>
                        <div class="row g-2">
                            <?php foreach ($borrowStatusOrder as $code => $label): ?>
                                <div class="col-6">
                                    <div class="border rounded p-3 bg-light">
                                        <div class="text-muted small"><?php echo htmlspecialchars($label, ENT_QUOTES); ?></div>
                                        <div class="fs-4 fw-bold"><?php echo number_format((int)($borrowStatusCounts[$code] ?? 0)); ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7 reveal-item section-item">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h5 class="fw-bold mb-0">Phiếu mượn theo ngày</h5>
                        </div>
                        <div style="height: 320px;">
                            <canvas id="adminChartBorrowDaily" aria-label="Biểu đồ phiếu mượn theo ngày"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mt-1">
            <div class="col-lg-6 reveal-item section-item">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-end gap-2 mb-3">
                            <h5 class="fw-bold mb-0">Top <?php echo $topBooksLimit; ?> sách được mượn</h5>
                            <form class="d-flex align-items-end gap-2" method="get" action="/admin/QuanLy/ThongKe/thongke.php">
                                <input type="hidden" name="stats_from" value="<?php echo htmlspecialchars($statsFrom, ENT_QUOTES); ?>">
                                <input type="hidden" name="stats_to" value="<?php echo htmlspecialchars($statsTo, ENT_QUOTES); ?>">
                                <input type="hidden" name="top_readers" value="<?php echo (int)$topReadersLimit; ?>">
                                <div>
                                    <label class="form-label mb-1">Top sách</label>
                                    <input class="form-control" type="number" name="top_books" min="1" max="50" step="1" value="<?php echo (int)$topBooksLimit; ?>" onchange="this.form.requestSubmit();">
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead>
                                <tr>
                                    <th style="width: 52px">#</th>
                                    <th>Đầu sách</th>
                                    <th class="text-end" style="width: 120px">Lượt mượn</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (!$topBorrowedBooks): ?>
                                    <tr>
                                        <td colspan="10" class="text-center text-muted py-3">Chưa có dữ liệu</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($topBorrowedBooks as $i => $r): ?>
                                        <tr>
                                            <td class="text-muted"><?php echo (int)$i + 1; ?></td>
                                            <td>
                                                <div class="fw-semibold"><?php echo htmlspecialchars((string)($r['tensach'] ?? ''), ENT_QUOTES); ?></div>
                                                <div class="text-muted small"><?php echo htmlspecialchars((string)($r['madausach'] ?? ''), ENT_QUOTES); ?></div>
                                            </td>
                                            <td class="text-end fw-semibold"><?php echo number_format((int)($r['cnt'] ?? 0)); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 reveal-item section-item">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-end gap-2 mb-3">
                            <h5 class="fw-bold mb-0">Top <?php echo $topReadersLimit; ?> độc giả mượn nhiều</h5>
                            <form class="d-flex align-items-end gap-2" method="get" action="/admin/QuanLy/ThongKe/thongke.php">
                                <input type="hidden" name="stats_from" value="<?php echo htmlspecialchars($statsFrom, ENT_QUOTES); ?>">
                                <input type="hidden" name="stats_to" value="<?php echo htmlspecialchars($statsTo, ENT_QUOTES); ?>">
                                <input type="hidden" name="top_books" value="<?php echo (int)$topBooksLimit; ?>">
                                <div>
                                    <label class="form-label mb-1">Top độc giả</label>
                                    <input class="form-control" type="number" name="top_readers" min="1" max="50" step="1" value="<?php echo (int)$topReadersLimit; ?>" onchange="this.form.requestSubmit();">
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead>
                                <tr>
                                    <th style="width: 52px">#</th>
                                    <th>Độc giả</th>
                                    <th class="text-end" style="width: 120px">Số phiếu</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (!$topBorrowingReaders): ?>
                                    <tr>
                                        <td colspan="10" class="text-center text-muted py-3">Chưa có dữ liệu</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($topBorrowingReaders as $i => $r): ?>
                                        <tr>
                                            <td class="text-muted"><?php echo (int)$i + 1; ?></td>
                                            <td>
                                                <div class="fw-semibold"><?php echo htmlspecialchars(trim((string)($r['ten_docgia'] ?? '')), ENT_QUOTES); ?></div>
                                                <div class="text-muted small"><?php echo htmlspecialchars((string)($r['madocgia'] ?? ''), ENT_QUOTES); ?></div>
                                            </td>
                                            <td class="text-end fw-semibold"><?php echo number_format((int)($r['cnt'] ?? 0)); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>
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
                animation: {
                    duration: 1400,
                    easing: 'easeOutQuart',
                    delay: (ctx) => {
                        if (ctx.type !== 'data' || ctx.mode !== 'default') return 0;
                        return ctx.dataIndex * 80;
                    },
                },
                animations: {
                    y: {
                        from: 0,
                    },
                },
                plugins: {
                    legend: { display: false },
                },
                scales: {
                    x: {
                        ticks: {
                            autoSkip: false,
                            minRotation: 0,
                            maxRotation: 0,
                            font: { size: 10 },
                            padding: 6,
                            callback: function (value) {
                                const label = String(this.getLabelForValue(value) ?? '');
                                return label.length > 16 ? (label.slice(0, 16) + '…') : label;
                            },
                        },
                    },
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
                animation: {
                    duration: 1200,
                    easing: 'easeOutQuart',
                    animateRotate: true,
                    animateScale: true,
                },
                plugins: {
                    legend: { position: 'bottom' },
                },
            },
        });
    }

    const borrowDailyCanvas = document.getElementById('adminChartBorrowDaily');
    if (borrowDailyCanvas) {
        const borrowDailyLabels = <?php echo json_encode($borrowDailyLabels, JSON_UNESCAPED_UNICODE); ?>;
        const borrowDailyCounts = <?php echo json_encode($borrowDailyCounts, JSON_UNESCAPED_UNICODE); ?>;

        new Chart(borrowDailyCanvas, {
            type: 'line',
            data: {
                labels: borrowDailyLabels,
                datasets: [{
                    label: 'Số phiếu mượn',
                    data: borrowDailyCounts,
                    tension: 0,
                    stepped: true,
                    fill: false,
                    pointRadius: 2,
                    pointHoverRadius: 4,
                    borderWidth: 2,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                },
                scales: {
                    x: {
                        ticks: {
                            autoSkip: true,
                            maxTicksLimit: 10,
                            minRotation: 0,
                            maxRotation: 0,
                            font: { size: 10 },
                            callback: function (value) {
                                const label = String(this.getLabelForValue(value) ?? '');
                                return label.length >= 10 ? label.slice(5) : label;
                            },
                        },
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            stepSize: 1,
                        },
                        suggestedMax: Math.max(3, ...borrowDailyCounts) + 1,
                    },
                },
            },
        });
    }
});
</script>

<script src="/assets/js/home.js" defer></script>
