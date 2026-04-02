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
    'muon' => 'PHIẾU MƯỢN',
    'tra' => 'PHIẾU TRẢ',
    'phat' => 'PHIẾU PHẠT',
    'nhap' => 'PHIẾU NHẬP',
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
            "SELECT ct.macuonsach, cs.madausach, ds.tensach
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
                    ct.tinhtrang_sau, ct.songayquahan, ct.tienphatquahan, ct.tienphathuha
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

$now = (new DateTime())->format('d/m/Y H:i');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($allowedTypes[$type]) ?> - <?= h($id) ?></title>

    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="icon" type="image/png" href="/assets/img/logo-library/library.png">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
        .page { max-width: 900px; margin: 0 auto; }
        .title { letter-spacing: 0.5px; }
        .mono { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; }
        .muted { color: #6c757d; }
        .kv { display: grid; grid-template-columns: 170px 1fr; gap: 4px 12px; }
        .kv div { padding: 2px 0; }
    </style>
</head>
<body>
<div class="page p-4">
    <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
        <div>
            <div class="h4 fw-bold mb-1 title"><?= h($allowedTypes[$type]) ?></div>
            <div class="muted">In lúc: <?= h($now) ?></div>
        </div>
        <div class="no-print d-flex gap-2">
            <button class="btn btn-primary" onclick="window.print()">In</button>
            <button class="btn btn-outline-secondary" onclick="window.close()">Đóng</button>
        </div>
    </div>

    <?php if ($error !== ''): ?>
        <div class="alert alert-danger"><?= h($error) ?></div>
    <?php else: ?>
        <div class="border rounded p-3 mb-3">
            <div class="kv">
                <?php if ($type === 'muon'): ?>
                    <div class="muted">Mã phiếu</div><div class="mono fw-semibold"><?= h((string)$header['mamuon']) ?></div>
                    <div class="muted">Ngày mượn</div><div><?= h(fmt_dt($header['ngaymuon'] ?? null)) ?></div>
                    <div class="muted">Hạn trả</div><div><?= h(fmt_dt($header['ngayhethan'] ?? null)) ?></div>
                    <div class="muted">Độc giả</div><div><?= h(trim((string)($header['ten_docgia'] ?? ''))) ?> (<?= h((string)($header['madocgia'] ?? '')) ?>)</div>
                    <div class="muted">Nhân viên</div><div><?= h(trim((string)($header['ten_nhanvien'] ?? ''))) ?> (<?= h((string)($header['manv'] ?? '')) ?>)</div>
                    <div class="muted">Trạng thái</div><div><?= h((string)($header['trangthai'] ?? '')) ?></div>
                    <div class="muted">Ghi chú</div><div><?= h((string)($header['ghichu'] ?? '')) ?></div>
                <?php elseif ($type === 'tra'): ?>
                    <div class="muted">Mã phiếu</div><div class="mono fw-semibold"><?= h((string)$header['matra']) ?></div>
                    <div class="muted">Mã mượn</div><div class="mono"><?= h((string)($header['mamuon'] ?? '')) ?></div>
                    <div class="muted">Ngày trả</div><div><?= h(fmt_dt($header['ngaytra'] ?? null)) ?></div>
                    <div class="muted">Độc giả</div><div><?= h(trim((string)($header['ten_docgia'] ?? ''))) ?> (<?= h((string)($header['madocgia'] ?? '')) ?>)</div>
                    <div class="muted">Nhân viên</div><div><?= h(trim((string)($header['ten_nhanvien'] ?? ''))) ?> (<?= h((string)($header['manv'] ?? '')) ?>)</div>
                    <div class="muted">Tổng tiền phạt</div><div><?= h((string)($header['tongtienphat'] ?? '0')) ?></div>
                <?php elseif ($type === 'phat'): ?>
                    <div class="muted">Mã phiếu</div><div class="mono fw-semibold"><?= h((string)$header['maphat']) ?></div>
                    <div class="muted">Ngày lập</div><div><?= h(fmt_dt($header['ngaylap'] ?? null)) ?></div>
                    <div class="muted">Độc giả</div><div><?= h(trim((string)($header['ten_docgia'] ?? ''))) ?> (<?= h((string)($header['madocgia'] ?? '')) ?>)</div>
                    <div class="muted">Mã trả</div><div class="mono"><?= h((string)($header['matra'] ?? '')) ?></div>
                    <div class="muted">Tổng tiền</div><div><?= h((string)($header['tongtienphat'] ?? '0')) ?></div>
                    <div class="muted">Trạng thái</div><div><?= h((string)($header['trangthai'] ?? '')) ?></div>
                    <div class="muted">Ghi chú</div><div><?= h((string)($header['ghichu'] ?? '')) ?></div>
                <?php else: ?>
                    <div class="muted">Mã phiếu</div><div class="mono fw-semibold"><?= h((string)$header['maphieunhap']) ?></div>
                    <div class="muted">Thời gian tạo</div><div><?= h(fmt_dt($header['thoigiantao'] ?? null)) ?></div>
                    <div class="muted">Nhà cung cấp</div><div><?= h((string)($header['tenncc'] ?? '')) ?> (<?= h((string)($header['mancc'] ?? '')) ?>)</div>
                    <div class="muted">Nhân viên</div><div><?= h(trim((string)($header['ten_nhanvien'] ?? ''))) ?> (<?= h((string)($header['manv'] ?? '')) ?>)</div>
                    <div class="muted">Tổng tiền</div><div><?= h((string)($header['tongtien'] ?? '0')) ?></div>
                <?php endif; ?>
            </div>
        </div>

        <div class="border rounded">
            <div class="p-3 border-bottom fw-semibold">Chi tiết</div>
            <div class="table-responsive">
                <table class="table mb-0 table-sm align-middle">
                    <thead>
                    <?php if ($type === 'muon'): ?>
                        <tr>
                            <th>#</th>
                            <th>Mã cuốn</th>
                            <th>Mã đầu sách</th>
                            <th>Tên sách</th>
                        </tr>
                    <?php elseif ($type === 'tra'): ?>
                        <tr>
                            <th>#</th>
                            <th>Mã cuốn</th>
                            <th>Mã đầu sách</th>
                            <th>Tên sách</th>
                            <th>Tình trạng sau</th>
                            <th>Quá hạn</th>
                            <th>Phạt quá hạn</th>
                            <th>Phạt thu</th>
                        </tr>
                    <?php elseif ($type === 'phat'): ?>
                        <tr>
                            <th>#</th>
                            <th>Mã cuốn</th>
                            <th>Mã đầu sách</th>
                            <th>Tên sách</th>
                            <th>Lý do</th>
                            <th>Quá hạn</th>
                            <th>Số tiền phạt</th>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <th>#</th>
                            <th>Mã đầu sách</th>
                            <th>Tên sách</th>
                            <th>Đơn giá nhập</th>
                            <th>Số lượng</th>
                            <th>Thành tiền</th>
                        </tr>
                    <?php endif; ?>
                    </thead>
                    <tbody>
                    <?php if (!$items): ?>
                        <tr><td colspan="20" class="text-center muted py-4">Không có chi tiết</td></tr>
                    <?php else: ?>
                        <?php $i = 0; $sum = 0.0; ?>
                        <?php foreach ($items as $it): $i++; ?>
                            <?php if ($type === 'muon'): ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td class="mono"><?= h((string)$it['macuonsach']) ?></td>
                                    <td class="mono"><?= h((string)($it['madausach'] ?? '')) ?></td>
                                    <td><?= h((string)($it['tensach'] ?? '')) ?></td>
                                </tr>
                            <?php elseif ($type === 'tra'): ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td class="mono"><?= h((string)$it['macuonsach']) ?></td>
                                    <td class="mono"><?= h((string)($it['madausach'] ?? '')) ?></td>
                                    <td><?= h((string)($it['tensach'] ?? '')) ?></td>
                                    <td><?= h((string)($it['tinhtrang_sau'] ?? '')) ?></td>
                                    <td><?= h((string)($it['songayquahan'] ?? '')) ?></td>
                                    <td><?= h((string)($it['tienphatquahan'] ?? '')) ?></td>
                                    <td><?= h((string)($it['tienphathuha'] ?? '')) ?></td>
                                </tr>
                            <?php elseif ($type === 'phat'): ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td class="mono"><?= h((string)$it['macuonsach']) ?></td>
                                    <td class="mono"><?= h((string)($it['madausach'] ?? '')) ?></td>
                                    <td><?= h((string)($it['tensach'] ?? '')) ?></td>
                                    <td><?= h((string)($it['lydo'] ?? '')) ?></td>
                                    <td><?= h((string)($it['songayquahan'] ?? '')) ?></td>
                                    <td><?= h((string)($it['sotienphat'] ?? '')) ?></td>
                                </tr>
                            <?php else: ?>
                                <?php
                                $dongia = (float)($it['dongianhap'] ?? 0);
                                $soluong = (int)($it['soluong'] ?? 0);
                                $thanhtien = $dongia * $soluong;
                                $sum += $thanhtien;
                                ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td class="mono"><?= h((string)$it['madausach']) ?></td>
                                    <td><?= h((string)($it['tensach'] ?? '')) ?></td>
                                    <td><?= h((string)($it['dongianhap'] ?? '')) ?></td>
                                    <td><?= h((string)($it['soluong'] ?? '')) ?></td>
                                    <td><?= h(number_format($thanhtien, 0, ',', '.')) ?></td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <?php if ($type === 'nhap'): ?>
                            <tr>
                                <td colspan="5" class="text-end fw-semibold">Tổng</td>
                                <td class="fw-semibold"><?= h(number_format($sum, 0, ',', '.')) ?></td>
                            </tr>
                        <?php endif; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-between">
            <div class="muted">Ký xác nhận (nhân viên)</div>
            <div class="muted">Ký xác nhận (độc giả/nhà cung cấp)</div>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
