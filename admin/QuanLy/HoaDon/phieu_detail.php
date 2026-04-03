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

// 1. HÀM FOMAT TIỀN TỆ
function fmt_money($value): string
{
    if (!is_numeric($value)) {
        return '0 đ';
    }
    return number_format((float)$value, 0, ',', '.') . ' đ';
}

// 2. HÀM GẮN MÀU (BADGE) CHO TRẠNG THÁI
function fmt_status(string $status): string
{
    $s = trim(mb_strtolower($status, 'UTF-8'));
    if (in_array($s, ['chuadong', 'chưa đóng'])) return '<span class="badge bg-danger">Chưa đóng</span>';
    if (in_array($s, ['dadong', 'đã đóng'])) return '<span class="badge bg-success">Đã đóng</span>';
    if (in_array($s, ['dangmuon', 'đang mượn', 'dangmuon'])) return '<span class="badge bg-warning text-dark">Đang mượn</span>';
    if (in_array($s, ['datra', 'đã trả', 'datra'])) return '<span class="badge bg-success">Đã trả</span>';
    if (in_array($s, ['choduyet', 'chờ duyệt'])) return '<span class="badge bg-info text-dark">Chờ duyệt</span>';
    return '<span class="badge bg-secondary">' . h($status) . '</span>';
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
                
                <?php if ($type === 'phat' && isset($header['trangthai']) && in_array(trim(mb_strtolower($header['trangthai'], 'UTF-8')), ['chuadong', 'chưa đóng', 'chua dong'])): ?>
                    <button type="button" class="btn btn-success" onclick="thuTienPhat('<?= h($id) ?>')">
                        <i class="fa-solid fa-money-bill"></i> Xác nhận thu tiền
                    </button>
                <?php endif; ?>

                <?php if ($type === 'muon' && isset($header['trangthai'])): ?>
                    <?php if ($header['trangthai'] === 'ChoDuyet'): ?>
                        <button type="button" class="btn btn-success" onclick="duyetPhieuMuon('<?= h($id) ?>')">
                            <i class="fa-solid fa-check"></i> Duyệt phiếu
                        </button>
                        <a class="btn btn-info text-white" href="/admin/QuanLy/HoaDon/phieu_muon_edit.php?mamuon=<?= rawurlencode($id) ?>">
                            <i class="fa-solid fa-pen"></i> Sửa phiếu
                        </a>
                        <button type="button" class="btn btn-danger" onclick="huyPhieuMuon('<?= h($id) ?>')">
                            <i class="fa-solid fa-trash"></i> Hủy phiếu
                        </button>
                    <?php elseif ($header['trangthai'] === 'DangMuon'): ?>
                        <a class="btn btn-success" href="/admin/QuanLy/HoaDon/lap_phieu_tra.php?mamuon=<?= rawurlencode($id) ?>">
                            <i class="fa-solid fa-right-left"></i> Lập phiếu trả
                        </a>
                    <?php endif; ?>
                <?php endif; ?>

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
                            <div class="col-12 col-md-4"><div class="text-muted small">Trạng thái</div><div class="fw-semibold"><?= fmt_status($header['trangthai'] ?? '') ?></div></div>
                            <div class="col-12 col-md-8"><div class="text-muted small">Ghi chú</div><div class="fw-semibold"><?= h((string)($header['ghichu'] ?? '')) ?></div></div>
                        <?php elseif ($type === 'tra'): ?>
                            <div class="col-12 col-md-4"><div class="text-muted small">Mã mượn</div><div class="fw-semibold"><?= h((string)($header['mamuon'] ?? '')) ?></div></div>
                            <div class="col-12 col-md-4"><div class="text-muted small">Độc giả</div><div class="fw-semibold"><?= h(trim((string)($header['ten_docgia'] ?? ''))) ?></div><div class="text-muted small"><?= h((string)($header['madocgia'] ?? '')) ?></div></div>
                            <div class="col-12 col-md-4"><div class="text-muted small">Nhân viên</div><div class="fw-semibold"><?= h(trim((string)($header['ten_nhanvien'] ?? ''))) ?></div><div class="text-muted small"><?= h((string)($header['manv'] ?? '')) ?></div></div>
                            <div class="col-6 col-md-3"><div class="text-muted small">Ngày trả</div><div class="fw-semibold"><?= h(fmt_dt($header['ngaytra'] ?? null)) ?></div></div>
                            <div class="col-6 col-md-3"><div class="text-muted small">Tổng tiền phạt</div><div class="fw-semibold text-danger"><?= h(fmt_money($header['tongtienphat'] ?? 0)) ?></div></div>
                        <?php elseif ($type === 'phat'): ?>
                            <div class="col-12 col-md-4"><div class="text-muted small">Độc giả</div><div class="fw-semibold"><?= h(trim((string)($header['ten_docgia'] ?? ''))) ?></div><div class="text-muted small"><?= h((string)($header['madocgia'] ?? '')) ?></div></div>
                            <div class="col-12 col-md-4"><div class="text-muted small">Mã trả</div><div class="fw-semibold"><?= h((string)($header['matra'] ?? '')) ?></div></div>
                            <div class="col-6 col-md-2"><div class="text-muted small">Ngày lập</div><div class="fw-semibold"><?= h(fmt_dt($header['ngaylap'] ?? null)) ?></div></div>
                            <div class="col-6 col-md-2"><div class="text-muted small">Tổng tiền</div><div class="fw-semibold text-danger"><?= h(fmt_money($header['tongtienphat'] ?? 0)) ?></div></div>
                            <div class="col-12 col-md-4"><div class="text-muted small">Trạng thái</div><div class="fw-semibold"><?= fmt_status($header['trangthai'] ?? '') ?></div></div>
                            <div class="col-12 col-md-8"><div class="text-muted small">Ghi chú</div><div class="fw-semibold"><?= h((string)($header['ghichu'] ?? '')) ?></div></div>
                        <?php else: ?>
                            <div class="col-12 col-md-5"><div class="text-muted small">Nhà cung cấp</div><div class="fw-semibold"><?= h((string)($header['tenncc'] ?? '')) ?></div><div class="text-muted small"><?= h((string)($header['mancc'] ?? '')) ?></div></div>
                            <div class="col-12 col-md-4"><div class="text-muted small">Nhân viên</div><div class="fw-semibold"><?= h(trim((string)($header['ten_nhanvien'] ?? ''))) ?></div><div class="text-muted small"><?= h((string)($header['manv'] ?? '')) ?></div></div>
                            <div class="col-6 col-md-3"><div class="text-muted small">Thời gian tạo</div><div class="fw-semibold"><?= h(fmt_dt($header['thoigiantao'] ?? null)) ?></div></div>
                            <div class="col-6 col-md-3"><div class="text-muted small">Tổng tiền</div><div class="fw-semibold text-success"><?= h(fmt_money($header['tongtien'] ?? 0)) ?></div></div>
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
                                    <th>Tiền phạt hư hỏng</th>
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
                                            <td><?= h(fmt_money($it['tienphatquahan'] ?? 0)) ?></td>
                                            <td><?= h(fmt_money($it['tienphathuha'] ?? 0)) ?></td>
                                            <td><?= h((string)($it['maphat'] ?? '')) ?></td>
                                        </tr>
                                    <?php elseif ($type === 'phat'): ?>
                                        <tr>
                                            <td class="fw-semibold"><?= h((string)$it['macuonsach']) ?></td>
                                            <td><?= h((string)($it['madausach'] ?? '')) ?></td>
                                            <td><?= h((string)($it['tensach'] ?? '')) ?></td>
                                            <td><?= h((string)($it['lydo'] ?? '')) ?></td>
                                            <td><?= h((string)($it['songayquahan'] ?? '')) ?></td>
                                            <td><?= h(fmt_money($it['sotienphat'] ?? 0)) ?></td>
                                        </tr>
                                    <?php else: ?>
                                        <tr>
                                            <td class="fw-semibold"><?= h((string)$it['madausach']) ?></td>
                                            <td><?= h((string)($it['tensach'] ?? '')) ?></td>
                                            <td><?= h(fmt_money($it['dongianhap'] ?? 0)) ?></td>
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

<script>
function thuTienPhat(maphat) {
    if(confirm('Bạn có chắc chắn đã nhận đủ tiền phạt cho mã phiếu ' + maphat + ' chưa?')) {
        // Ní nhớ tạo file ajax_thu_tien_phat.php để chạy lệnh UPDATE trangthai='Đã đóng' trong CSDL nha
        fetch('/ajax/ajax_thu_tien_phat.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'maphat=' + encodeURIComponent(maphat)
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert('Xác nhận thu tiền thành công!');
                location.reload(); // Tự động load lại trang để cập nhật màu xanh
            } else {
                alert('Có lỗi xảy ra: ' + (data.message || 'Không thể cập nhật trạng thái.'));
            }
        })
        .catch(error => {
            console.error('Lỗi:', error);
            alert('Đã xảy ra lỗi kết nối với máy chủ!');
        });
    }
}

function huyPhieuMuon(mamuon) {
    if(confirm('Bạn có chắc chắn muốn HỦY phiếu mượn ' + mamuon + ' này không?\n\nSách sẽ được trả về kho tự động và phiếu này sẽ bị xóa.')) {
        fetch('/ajax/process_delete_phieumuon.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'mamuon=' + encodeURIComponent(mamuon)
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert('Khôi phục sách về kho và hủy đơn thành công!');
                window.location.href = '/admin/QuanLy/HoaDon/QL_HoaDon.php?type=muon'; 
            } else {
                alert('Có lỗi xảy ra: ' + (data.message || 'Không thể hủy.'));
            }
        })
        .catch(error => {
            console.error('Lỗi:', error);
            alert('Đã xảy ra lỗi kết nối với máy chủ!');
        });
    }
}

function duyetPhieuMuon(mamuon) {
    if(confirm('Xác nhận: Thủ thư đã giao đủ sách cho độc giả này và Duyệt đơn ' + mamuon + '?')) {
        fetch('/ajax/update_status_qr.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'mamuon=' + encodeURIComponent(mamuon)
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                alert('Duyệt thành công! Phiếu đã chuyển sang Đang Mượn.');
                location.reload();
            } else if (data.status === 'redirect') {
                window.location.href = data.url;
            } else {
                alert('Lỗi: ' + (data.message || 'Không thể duyệt.'));
            }
        })
        .catch(err => {
            console.error('Lỗi:', err);
            alert('Lỗi kết nối máy chủ!');
        });
    }
}
</script>

</body>
</html>