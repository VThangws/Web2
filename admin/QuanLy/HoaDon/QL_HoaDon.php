<?php
require_once __DIR__ . '/../../auth.php';
require_admin_login();
require_admin_permission('HOADON');

require_once __DIR__ . '/../../../database/ConnectDB.php';

$conn = ConnectDB::getInstance()->getConnection();
$conn->set_charset('utf8mb4');

function h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function get_str(string $key, string $default = ''): string
{
    if (!isset($_GET[$key])) {
        return $default;
    }
    $value = trim((string)$_GET[$key]);
    return $value;
}

function fmt_dt(?string $value): string
{
    if ($value === null || $value === '') {
        return '';
    }
    try {
        $dt = new DateTime($value);
        return $dt->format('d/m/Y H:i');
    } catch (Throwable $e) {
        return $value;
    }
}

$allowedTypes = [
    'muon' => 'Phiếu mượn (phiếu xuất sách)',
    'tra' => 'Phiếu trả',
    'phat' => 'Phiếu phạt',
    'nhap' => 'Phiếu nhập',
];

$type = get_str('type', 'muon');
if (!array_key_exists($type, $allowedTypes)) {
    $type = 'muon';
}

$q = get_str('q');
$from = get_str('from');
$to = get_str('to');
$status = get_str('status');

$rows = [];
$error = '';

try {
    $where = [];
    $types = '';
    $params = [];

    $dateField = match ($type) {
        'muon' => 'pm.ngaymuon',
        'tra' => 'pt.ngaytra',
        'phat' => 'pp.ngaylap',
        'nhap' => 'pn.thoigiantao',
        default => '',
    };

    if ($q !== '') {
        if ($type === 'muon') {
            $where[] = '(pm.mamuon LIKE ? OR pm.madocgia LIKE ? OR CONCAT(COALESCE(dg.hodocgia,\'\'),\' \',COALESCE(dg.tendocgia,\'\')) LIKE ?)';
            $types .= 'sss';
            $like = '%' . $q . '%';
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        } elseif ($type === 'tra') {
            $where[] = '(pt.matra LIKE ? OR pt.mamuon LIKE ? OR pm.madocgia LIKE ? OR CONCAT(COALESCE(dg.hodocgia,\'\'),\' \',COALESCE(dg.tendocgia,\'\')) LIKE ?)';
            $types .= 'ssss';
            $like = '%' . $q . '%';
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        } elseif ($type === 'phat') {
            $where[] = '(pp.maphat LIKE ? OR pp.matra LIKE ? OR pp.madocgia LIKE ? OR CONCAT(COALESCE(dg.hodocgia,\'\'),\' \',COALESCE(dg.tendocgia,\'\')) LIKE ?)';
            $types .= 'ssss';
            $like = '%' . $q . '%';
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        } else {
            $where[] = '(pn.maphieunhap LIKE ? OR pn.mancc LIKE ? OR COALESCE(ncc.tenncc,\'\') LIKE ?)';
            $types .= 'sss';
            $like = '%' . $q . '%';
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }
    }

    if ($from !== '' && $dateField !== '') {
        $where[] = $dateField . ' >= ?';
        $types .= 's';
        $params[] = $from . ' 00:00:00';
    }
    if ($to !== '' && $dateField !== '') {
        $where[] = $dateField . ' <= ?';
        $types .= 's';
        $params[] = $to . ' 23:59:59';
    }

    if ($status !== '' && ($type === 'muon' || $type === 'phat')) {
        $where[] = ($type === 'muon' ? 'pm.trangthai = ?' : 'pp.trangthai = ?');
        $types .= 's';
        $params[] = $status;
    }

    $whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

    if ($type === 'muon') {
        $sql = "SELECT pm.mamuon, pm.ngaymuon, pm.ngayhethan, pm.madocgia,
                       CONCAT(COALESCE(dg.hodocgia,''),' ',COALESCE(dg.tendocgia,'')) AS ten_docgia,
                       pm.manv, CONCAT(COALESCE(nv.honv,''),' ',COALESCE(nv.tennv,'')) AS ten_nhanvien,
                       pm.trangthai,
                       (SELECT COUNT(*) FROM ctphieumuon ct WHERE ct.mamuon = pm.mamuon) AS so_cuon
                FROM phieumuon pm
                LEFT JOIN docgia dg ON dg.madocgia = pm.madocgia
                LEFT JOIN nhanvien nv ON nv.manv = pm.manv
                $whereSql
                ORDER BY pm.ngaymuon DESC
                LIMIT 300";
    } elseif ($type === 'tra') {
        $sql = "SELECT pt.matra, pt.mamuon, pt.ngaytra, pt.manv,
                       CONCAT(COALESCE(nv.honv,''),' ',COALESCE(nv.tennv,'')) AS ten_nhanvien,
                       pm.madocgia, CONCAT(COALESCE(dg.hodocgia,''),' ',COALESCE(dg.tendocgia,'')) AS ten_docgia,
                       pt.tongtienphat,
                       (SELECT COUNT(*) FROM ctphieutra ct WHERE ct.matra = pt.matra) AS so_cuon
                FROM phieutra pt
                LEFT JOIN phieumuon pm ON pm.mamuon = pt.mamuon
                LEFT JOIN docgia dg ON dg.madocgia = pm.madocgia
                LEFT JOIN nhanvien nv ON nv.manv = pt.manv
                $whereSql
                ORDER BY pt.ngaytra DESC
                LIMIT 300";
    } elseif ($type === 'phat') {
        $sql = "SELECT pp.maphat, pp.madocgia, CONCAT(COALESCE(dg.hodocgia,''),' ',COALESCE(dg.tendocgia,'')) AS ten_docgia,
                       pp.matra, pp.ngaylap, pp.tongtienphat, pp.trangthai
                FROM phieuphat pp
                LEFT JOIN docgia dg ON dg.madocgia = pp.madocgia
                $whereSql
                ORDER BY pp.ngaylap DESC
                LIMIT 300";
    } else {
        $sql = "SELECT pn.maphieunhap, pn.thoigiantao, pn.tongtien, pn.mancc, COALESCE(ncc.tenncc,'') AS tenncc,
                       pn.manv, CONCAT(COALESCE(nv.honv,''),' ',COALESCE(nv.tennv,'')) AS ten_nhanvien,
                       (SELECT SUM(COALESCE(ct.soluong,0)) FROM ctphieunhap ct WHERE ct.maphieunhap = pn.maphieunhap) AS tong_soluong
                FROM phieunhap pn
                LEFT JOIN nhacungcap ncc ON ncc.mancc = pn.mancc
                LEFT JOIN nhanvien nv ON nv.manv = pn.manv
                $whereSql
                ORDER BY pn.thoigiantao DESC
                LIMIT 300";
    }

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        throw new RuntimeException('Không thể chuẩn bị câu lệnh SQL.');
    }
    if ($params) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    $stmt->close();
} catch (Throwable $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý hóa đơn</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="icon" type="image/png" href="/assets/img/logo-library/library.png">

    <link rel="stylesheet" href="/assets/css/admin_sidebar.css">
</head>
<body>
<?php
require_once __DIR__ . '/../../layout/admin_sidebar.php';
?>

<main class="container-fluid py-4">
    <div class="container-md">
        <h2 class="fw-bold mb-2">Quản lý phiếu</h2>
        <p class="text-muted mb-3">Danh sách/chi tiết/in phiếu theo database hiện tại.</p>

        <ul class="nav nav-pills gap-2 mb-3">
            <?php foreach ($allowedTypes as $k => $label): ?>
                <li class="nav-item">
                    <a class="nav-link <?= $type === $k ? 'active' : '' ?>" href="?type=<?= h($k) ?>">
                        <?= h($label) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <form class="row g-2 align-items-end mb-3" method="get">
            <input type="hidden" name="type" value="<?= h($type) ?>">

            <div class="col-12 col-md-4">
                <label class="form-label mb-1">Tìm kiếm</label>
                <input class="form-control" name="q" value="<?= h($q) ?>" placeholder="Mã phiếu / mã độc giả / tên ...">
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label mb-1">Từ ngày</label>
                <input class="form-control" type="date" name="from" value="<?= h($from) ?>">
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label mb-1">Đến ngày</label>
                <input class="form-control" type="date" name="to" value="<?= h($to) ?>">
            </div>
            <?php if ($type === 'muon' || $type === 'phat'): ?>
                <div class="col-12 col-md-2">
                    <label class="form-label mb-1">Trạng thái</label>
                    <input class="form-control" name="status" value="<?= h($status) ?>" placeholder="Ví dụ: DangMuon">
                </div>
            <?php endif; ?>
            <div class="col-12 col-md-2 d-grid">
                <button class="btn btn-primary" type="submit">Lọc</button>
            </div>
        </form>

        <?php if ($error !== ''): ?>
            <div class="alert alert-danger"><?= h($error) ?></div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                <?php if ($type === 'muon'): ?>
                    <tr>
                        <th>Mã mượn</th>
                        <th>Độc giả</th>
                        <th>Nhân viên</th>
                        <th>Ngày mượn</th>
                        <th>Hết hạn</th>
                        <th>Trạng thái</th>
                        <th>Số cuốn</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                <?php elseif ($type === 'tra'): ?>
                    <tr>
                        <th>Mã trả</th>
                        <th>Mã mượn</th>
                        <th>Độc giả</th>
                        <th>Nhân viên</th>
                        <th>Ngày trả</th>
                        <th>Tổng tiền phạt</th>
                        <th>Số cuốn</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                <?php elseif ($type === 'phat'): ?>
                    <tr>
                        <th>Mã phạt</th>
                        <th>Độc giả</th>
                        <th>Mã trả</th>
                        <th>Ngày lập</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                <?php else: ?>
                    <tr>
                        <th>Mã nhập</th>
                        <th>Nhà cung cấp</th>
                        <th>Nhân viên</th>
                        <th>Thời gian tạo</th>
                        <th>Tổng số lượng</th>
                        <th>Tổng tiền</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                <?php endif; ?>
                </thead>
                <tbody>
                <?php if (!$rows): ?>
                    <tr>
                        <td colspan="20" class="text-center text-muted py-4">Không có dữ liệu</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($rows as $r): ?>
                        <?php
                        $id = match ($type) {
                            'muon' => (string)$r['mamuon'],
                            'tra' => (string)$r['matra'],
                            'phat' => (string)$r['maphat'],
                            'nhap' => (string)$r['maphieunhap'],
                        };
                        $detailUrl = '/admin/QuanLy/HoaDon/phieu_detail.php?type=' . rawurlencode($type) . '&id=' . rawurlencode($id);
                        $printUrl = '/admin/QuanLy/HoaDon/phieu_print.php?type=' . rawurlencode($type) . '&id=' . rawurlencode($id);
                        ?>

                        <?php if ($type === 'muon'): ?>
                            <tr>
                                <td class="fw-semibold"><?= h($r['mamuon']) ?></td>
                                <td>
                                    <div><?= h(trim((string)$r['ten_docgia'])) ?></div>
                                    <div class="text-muted small"><?= h((string)$r['madocgia']) ?></div>
                                </td>
                                <td><?= h(trim((string)$r['ten_nhanvien'])) ?></td>
                                <td><?= h(fmt_dt($r['ngaymuon'] ?? null)) ?></td>
                                <td><?= h(fmt_dt($r['ngayhethan'] ?? null)) ?></td>
                                <td><?= h((string)($r['trangthai'] ?? '')) ?></td>
                                <td><?= h((string)($r['so_cuon'] ?? '0')) ?></td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-primary" href="<?= h($detailUrl) ?>">Chi tiết</a>
                                    <a class="btn btn-sm btn-outline-secondary" href="<?= h($printUrl) ?>" target="_blank">In</a>
                                </td>
                            </tr>
                        <?php elseif ($type === 'tra'): ?>
                            <tr>
                                <td class="fw-semibold"><?= h($r['matra']) ?></td>
                                <td><?= h((string)($r['mamuon'] ?? '')) ?></td>
                                <td>
                                    <div><?= h(trim((string)$r['ten_docgia'])) ?></div>
                                    <div class="text-muted small"><?= h((string)($r['madocgia'] ?? '')) ?></div>
                                </td>
                                <td><?= h(trim((string)$r['ten_nhanvien'])) ?></td>
                                <td><?= h(fmt_dt($r['ngaytra'] ?? null)) ?></td>
                                <td><?= h((string)($r['tongtienphat'] ?? '0')) ?></td>
                                <td><?= h((string)($r['so_cuon'] ?? '0')) ?></td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-primary" href="<?= h($detailUrl) ?>">Chi tiết</a>
                                    <a class="btn btn-sm btn-outline-secondary" href="<?= h($printUrl) ?>" target="_blank">In</a>
                                </td>
                            </tr>
                        <?php elseif ($type === 'phat'): ?>
                            <tr>
                                <td class="fw-semibold"><?= h($r['maphat']) ?></td>
                                <td>
                                    <div><?= h(trim((string)$r['ten_docgia'])) ?></div>
                                    <div class="text-muted small"><?= h((string)($r['madocgia'] ?? '')) ?></div>
                                </td>
                                <td><?= h((string)($r['matra'] ?? '')) ?></td>
                                <td><?= h(fmt_dt($r['ngaylap'] ?? null)) ?></td>
                                <td><?= h((string)($r['tongtienphat'] ?? '0')) ?></td>
                                <td><?= h((string)($r['trangthai'] ?? '')) ?></td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-primary" href="<?= h($detailUrl) ?>">Chi tiết</a>
                                    <a class="btn btn-sm btn-outline-secondary" href="<?= h($printUrl) ?>" target="_blank">In</a>
                                </td>
                            </tr>
                        <?php else: ?>
                            <tr>
                                <td class="fw-semibold"><?= h($r['maphieunhap']) ?></td>
                                <td>
                                    <div><?= h((string)($r['tenncc'] ?? '')) ?></div>
                                    <div class="text-muted small"><?= h((string)($r['mancc'] ?? '')) ?></div>
                                </td>
                                <td><?= h(trim((string)($r['ten_nhanvien'] ?? ''))) ?></td>
                                <td><?= h(fmt_dt($r['thoigiantao'] ?? null)) ?></td>
                                <td><?= h((string)($r['tong_soluong'] ?? '0')) ?></td>
                                <td><?= h((string)($r['tongtien'] ?? '0')) ?></td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-primary" href="<?= h($detailUrl) ?>">Chi tiết</a>
                                    <a class="btn btn-sm btn-outline-secondary" href="<?= h($printUrl) ?>" target="_blank">In</a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<script src="/assets/bootstrap/js/bootstrap.bundle.min.js" defer></script>
</body>
</html>
