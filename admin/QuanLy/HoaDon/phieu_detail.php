<?php
require_once __DIR__ . '/../../login/auth.php';
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
    return trim((string)$_GET[$key]);
}

function fmt_dt(?string $value): string
{
    if ($value === null || $value === '') {
        return '';
    }
    try {
        return (new DateTime($value))->format('d/m/Y H:i');
    } catch (Throwable $e) {
        return $value;
    }
}

$allowedTypes = [
    'muon' => 'Phiếu mượn',
    'tra' => 'Phiếu trả',
    'phat' => 'Phiếu phạt',
    'nhap' => 'Phiếu nhập',
];

$type = get_str('type', 'muon');
$id = get_str('id');

if (!array_key_exists($type, $allowedTypes)) {
    $type = 'muon';
}

if ($id === '') {
    http_response_code(400);
    echo 'Thiếu tham số id';
    exit;
}

$header = null;
$items = [];
$error = '';

try {
    if ($type === 'muon') {
        $stmt = $conn->prepare(
            "SELECT pm.mamuon, pm.ngaymuon, pm.ngayhethan, pm.trangthai, pm.ghichu,
                    pm.madocgia, CONCAT(COALESCE(dg.hodocgia,''),' ',COALESCE(dg.tendocgia,'')) AS ten_docgia,
                    pm.manv, CONCAT(COALESCE(nv.honv,''),' ',COALESCE(nv.tennv,'')) AS ten_nhanvien
             FROM phieumuon pm
             LEFT JOIN docgia dg ON dg.madocgia = pm.madocgia
             LEFT JOIN nhanvien nv ON nv.manv = pm.manv
             WHERE pm.mamuon = ?"
        );
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $header = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$header) {
            throw new RuntimeException('Không tìm thấy phiếu mượn.');
        }

        $stmt = $conn->prepare(
            "SELECT ct.macuonsach, cs.madausach, ds.tensach, ct.tinhtrang_truoc
             FROM ctphieumuon ct
             LEFT JOIN cuonsach cs ON cs.macuonsach = ct.macuonsach
             LEFT JOIN dausach ds ON ds.madausach = cs.madausach
             WHERE ct.mamuon = ?
             ORDER BY ct.macuonsach ASC"
        );
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    } elseif ($type === 'tra') {
        $stmt = $conn->prepare(
            "SELECT pt.matra, pt.mamuon, pt.ngaytra, pt.manv, pt.tongtienphat,
                    CONCAT(COALESCE(nv.honv,''),' ',COALESCE(nv.tennv,'')) AS ten_nhanvien,
                    pm.madocgia, CONCAT(COALESCE(dg.hodocgia,''),' ',COALESCE(dg.tendocgia,'')) AS ten_docgia
             FROM phieutra pt
             LEFT JOIN nhanvien nv ON nv.manv = pt.manv
             LEFT JOIN phieumuon pm ON pm.mamuon = pt.mamuon
             LEFT JOIN docgia dg ON dg.madocgia = pm.madocgia
             WHERE pt.matra = ?"
        );
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $header = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$header) {
            throw new RuntimeException('Không tìm thấy phiếu trả.');
        }

        $stmt = $conn->prepare(
            "SELECT ct.macuonsach, cs.madausach, ds.tensach,
                    ct.tinhtrang_sau, ct.songayquahan, ct.tienphatquahan, ct.tienphathuha, ct.maphat
             FROM ctphieutra ct
             LEFT JOIN cuonsach cs ON cs.macuonsach = ct.macuonsach
             LEFT JOIN dausach ds ON ds.madausach = cs.madausach
             WHERE ct.matra = ?
             ORDER BY ct.macuonsach ASC"
        );
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    } elseif ($type === 'phat') {
        $stmt = $conn->prepare(
            "SELECT pp.maphat, pp.madocgia, CONCAT(COALESCE(dg.hodocgia,''),' ',COALESCE(dg.tendocgia,'')) AS ten_docgia,
                    pp.matra, pp.ngaylap, pp.tongtienphat, pp.trangthai, pp.ghichu
             FROM phieuphat pp
             LEFT JOIN docgia dg ON dg.madocgia = pp.madocgia
             WHERE pp.maphat = ?"
        );
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $header = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$header) {
            throw new RuntimeException('Không tìm thấy phiếu phạt.');
        }

        $stmt = $conn->prepare(
            "SELECT ct.macuonsach, cs.madausach, ds.tensach, ct.lydo, ct.songayquahan, ct.sotienphat
             FROM ctphieuphat ct
             LEFT JOIN cuonsach cs ON cs.macuonsach = ct.macuonsach
             LEFT JOIN dausach ds ON ds.madausach = cs.madausach
             WHERE ct.maphat = ?
             ORDER BY ct.macuonsach ASC"
        );
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    } else {
        $stmt = $conn->prepare(
            "SELECT pn.maphieunhap, pn.thoigiantao, pn.tongtien, pn.mancc, COALESCE(ncc.tenncc,'') AS tenncc,
                    pn.manv, CONCAT(COALESCE(nv.honv,''),' ',COALESCE(nv.tennv,'')) AS ten_nhanvien
             FROM phieunhap pn
             LEFT JOIN nhacungcap ncc ON ncc.mancc = pn.mancc
             LEFT JOIN nhanvien nv ON nv.manv = pn.manv
             WHERE pn.maphieunhap = ?"
        );
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $header = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$header) {
            throw new RuntimeException('Không tìm thấy phiếu nhập.');
        }

        $stmt = $conn->prepare(
            "SELECT ct.madausach, ds.tensach, ct.dongianhap, ct.soluong
             FROM ctphieunhap ct
             LEFT JOIN dausach ds ON ds.madausach = ct.madausach
             WHERE ct.maphieunhap = ?
             ORDER BY ct.madausach ASC"
        );
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }
} catch (Throwable $e) {
    $error = $e->getMessage();
}

$printUrl = '/admin/QuanLy/HoaDon/phieu_print.php?type=' . rawurlencode($type) . '&id=' . rawurlencode($id);
$backUrl = '/admin/QuanLy/HoaDon/QL_HoaDon.php?type=' . rawurlencode($type);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết - <?= h($allowedTypes[$type]) ?></title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="icon" type="image/png" href="/assets/img/logo-library/library.png">
    <link rel="stylesheet" href="/assets/css/admin_sidebar.css">
</head>
<body>
<?php require_once __DIR__ . '/../../layout/admin_sidebar.php'; ?>

<main class="container-fluid py-4">
    <div class="container-md">
        <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between mb-3">
            <div>
                <h2 class="fw-bold mb-1"><?= h($allowedTypes[$type]) ?></h2>
                <div class="text-muted">Mã: <span class="fw-semibold"><?= h($id) ?></span></div>
            </div>
            <div class="d-flex gap-2">
                <a class="btn btn-outline-secondary" href="<?= h($backUrl) ?>"><i class="fa-solid fa-arrow-left"></i> Quay lại</a>
                <a class="btn btn-primary" href="<?= h($printUrl) ?>" target="_blank"><i class="fa-solid fa-print"></i> In phiếu</a>
            </div>
        </div>

        <?php if ($error !== ''): ?>
            <div class="alert alert-danger"><?= h($error) ?></div>
        <?php else: ?>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row g-3">
                        <?php if ($type === 'muon'): ?>
                            <div class="col-12 col-md-4"><div class="text-muted small">Độc giả</div><div class="fw-semibold"><?= h(trim((string)($header['ten_docgia'] ?? ''))) ?></div><div class="text-muted small"><?= h((string)($header['madocgia'] ?? '')) ?></div></div>
                            <div class="col-12 col-md-4"><div class="text-muted small">Nhân viên</div><div class="fw-semibold"><?= h(trim((string)($header['ten_nhanvien'] ?? ''))) ?></div><div class="text-muted small"><?= h((string)($header['manv'] ?? '')) ?></div></div>
                            <div class="col-6 col-md-2"><div class="text-muted small">Ngày mượn</div><div class="fw-semibold"><?= h(fmt_dt($header['ngaymuon'] ?? null)) ?></div></div>
                            <div class="col-6 col-md-2"><div class="text-muted small">Hết hạn</div><div class="fw-semibold"><?= h(fmt_dt($header['ngayhethan'] ?? null)) ?></div></div>
                            <div class="col-12 col-md-4"><div class="text-muted small">Trạng thái</div><div class="fw-semibold"><?= h((string)($header['trangthai'] ?? '')) ?></div></div>
                            <div class="col-12 col-md-8"><div class="text-muted small">Ghi chú</div><div class="fw-semibold"><?= h((string)($header['ghichu'] ?? '')) ?></div></div>
                        <?php elseif ($type === 'tra'): ?>
                            <div class="col-12 col-md-4"><div class="text-muted small">Mã mượn</div><div class="fw-semibold"><?= h((string)($header['mamuon'] ?? '')) ?></div></div>
                            <div class="col-12 col-md-4"><div class="text-muted small">Độc giả</div><div class="fw-semibold"><?= h(trim((string)($header['ten_docgia'] ?? ''))) ?></div><div class="text-muted small"><?= h((string)($header['madocgia'] ?? '')) ?></div></div>
                            <div class="col-12 col-md-4"><div class="text-muted small">Nhân viên</div><div class="fw-semibold"><?= h(trim((string)($header['ten_nhanvien'] ?? ''))) ?></div><div class="text-muted small"><?= h((string)($header['manv'] ?? '')) ?></div></div>
                            <div class="col-6 col-md-3"><div class="text-muted small">Ngày trả</div><div class="fw-semibold"><?= h(fmt_dt($header['ngaytra'] ?? null)) ?></div></div>
                            <div class="col-6 col-md-3"><div class="text-muted small">Tổng tiền phạt</div><div class="fw-semibold"><?= h((string)($header['tongtienphat'] ?? '0')) ?></div></div>
                        <?php elseif ($type === 'phat'): ?>
                            <div class="col-12 col-md-4"><div class="text-muted small">Độc giả</div><div class="fw-semibold"><?= h(trim((string)($header['ten_docgia'] ?? ''))) ?></div><div class="text-muted small"><?= h((string)($header['madocgia'] ?? '')) ?></div></div>
                            <div class="col-12 col-md-4"><div class="text-muted small">Mã trả</div><div class="fw-semibold"><?= h((string)($header['matra'] ?? '')) ?></div></div>
                            <div class="col-6 col-md-2"><div class="text-muted small">Ngày lập</div><div class="fw-semibold"><?= h(fmt_dt($header['ngaylap'] ?? null)) ?></div></div>
                            <div class="col-6 col-md-2"><div class="text-muted small">Tổng tiền</div><div class="fw-semibold"><?= h((string)($header['tongtienphat'] ?? '0')) ?></div></div>
                            <div class="col-12 col-md-4"><div class="text-muted small">Trạng thái</div><div class="fw-semibold"><?= h((string)($header['trangthai'] ?? '')) ?></div></div>
                            <div class="col-12 col-md-8"><div class="text-muted small">Ghi chú</div><div class="fw-semibold"><?= h((string)($header['ghichu'] ?? '')) ?></div></div>
                        <?php else: ?>
                            <div class="col-12 col-md-5"><div class="text-muted small">Nhà cung cấp</div><div class="fw-semibold"><?= h((string)($header['tenncc'] ?? '')) ?></div><div class="text-muted small"><?= h((string)($header['mancc'] ?? '')) ?></div></div>
                            <div class="col-12 col-md-4"><div class="text-muted small">Nhân viên</div><div class="fw-semibold"><?= h(trim((string)($header['ten_nhanvien'] ?? ''))) ?></div><div class="text-muted small"><?= h((string)($header['manv'] ?? '')) ?></div></div>
                            <div class="col-6 col-md-3"><div class="text-muted small">Thời gian tạo</div><div class="fw-semibold"><?= h(fmt_dt($header['thoigiantao'] ?? null)) ?></div></div>
                            <div class="col-6 col-md-3"><div class="text-muted small">Tổng tiền</div><div class="fw-semibold"><?= h((string)($header['tongtien'] ?? '0')) ?></div></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header fw-semibold">Danh sách chi tiết</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0 align-middle">
                            <thead>
                            <?php if ($type === 'muon'): ?>
                                <tr>
                                    <th>Mã cuốn</th>
                                    <th>Mã đầu sách</th>
                                    <th>Tên sách</th>
                                    <th>Tình trạng trước</th>
                                </tr>
                            <?php elseif ($type === 'tra'): ?>
                                <tr>
                                    <th>Mã cuốn</th>
                                    <th>Mã đầu sách</th>
                                    <th>Tên sách</th>
                                    <th>Tình trạng sau</th>
                                    <th>Số ngày quá hạn</th>
                                    <th>Tiền phạt quá hạn</th>
                                    <th>Tiền phạt thu</th>
                                    <th>Mã phạt</th>
                                </tr>
                            <?php elseif ($type === 'phat'): ?>
                                <tr>
                                    <th>Mã cuốn</th>
                                    <th>Mã đầu sách</th>
                                    <th>Tên sách</th>
                                    <th>Lý do</th>
                                    <th>Số ngày quá hạn</th>
                                    <th>Số tiền phạt</th>
                                </tr>
                            <?php else: ?>
                                <tr>
                                    <th>Mã đầu sách</th>
                                    <th>Tên sách</th>
                                    <th>Đơn giá nhập</th>
                                    <th>Số lượng</th>
                                </tr>
                            <?php endif; ?>
                            </thead>
                            <tbody>
                            <?php if (!$items): ?>
                                <tr><td colspan="20" class="text-center text-muted py-4">Không có chi tiết</td></tr>
                            <?php else: ?>
                                <?php foreach ($items as $it): ?>
                                    <?php if ($type === 'muon'): ?>
                                        <tr>
                                            <td class="fw-semibold"><?= h((string)$it['macuonsach']) ?></td>
                                            <td><?= h((string)($it['madausach'] ?? '')) ?></td>
                                            <td><?= h((string)($it['tensach'] ?? '')) ?></td>
                                            <td><?= h((string)($it['tinhtrang_truoc'] ?? '')) ?></td>
                                        </tr>
                                    <?php elseif ($type === 'tra'): ?>
                                        <tr>
                                            <td class="fw-semibold"><?= h((string)$it['macuonsach']) ?></td>
                                            <td><?= h((string)($it['madausach'] ?? '')) ?></td>
                                            <td><?= h((string)($it['tensach'] ?? '')) ?></td>
                                            <td><?= h((string)($it['tinhtrang_sau'] ?? '')) ?></td>
                                            <td><?= h((string)($it['songayquahan'] ?? '')) ?></td>
                                            <td><?= h((string)($it['tienphatquahan'] ?? '')) ?></td>
                                            <td><?= h((string)($it['tienphathuha'] ?? '')) ?></td>
                                            <td><?= h((string)($it['maphat'] ?? '')) ?></td>
                                        </tr>
                                    <?php elseif ($type === 'phat'): ?>
                                        <tr>
                                            <td class="fw-semibold"><?= h((string)$it['macuonsach']) ?></td>
                                            <td><?= h((string)($it['madausach'] ?? '')) ?></td>
                                            <td><?= h((string)($it['tensach'] ?? '')) ?></td>
                                            <td><?= h((string)($it['lydo'] ?? '')) ?></td>
                                            <td><?= h((string)($it['songayquahan'] ?? '')) ?></td>
                                            <td><?= h((string)($it['sotienphat'] ?? '')) ?></td>
                                        </tr>
                                    <?php else: ?>
                                        <tr>
                                            <td class="fw-semibold"><?= h((string)$it['madausach']) ?></td>
                                            <td><?= h((string)($it['tensach'] ?? '')) ?></td>
                                            <td><?= h((string)($it['dongianhap'] ?? '')) ?></td>
                                            <td><?= h((string)($it['soluong'] ?? '')) ?></td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<script src="/assets/bootstrap/js/bootstrap.bundle.min.js" defer></script>
</body>
</html>
