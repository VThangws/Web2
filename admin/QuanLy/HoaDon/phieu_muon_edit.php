<?php
require_once __DIR__ . '/../../login/auth.php';
require_admin_login();
require_admin_permission('HOADON');
require_once __DIR__ . '/../../../database/ConnectDB.php';

$conn = ConnectDB::getInstance()->getConnection();
$conn->set_charset('utf8mb4');

function h(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$mamuon = $_GET['mamuon'] ?? '';
if (!$mamuon) {
    die('Thiếu mã mượn.');
}

$error = '';
$success = '';

// 1. Tải thông tin phiếu mượn gốc
$stmt = $conn->prepare("SELECT mamuon, madocgia, ngaymuon, ngayhethan, trangthai, ghichu FROM phieumuon WHERE mamuon = ?");
$stmt->bind_param("s", $mamuon);
$stmt->execute();
$phieu = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$phieu) {
    die('Không tìm thấy phiếu mượn.');
}
if ($phieu['trangthai'] !== 'ChoDuyet') {
    die('Chỉ được thao tác Sửa trên hóa đơn trạng thái ChoDuyet.');
}

// 2. Xử lý CẬP NHẬT khi submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ngaymuon_new = trim($_POST['ngaymuon'] ?? '');
    $ngayhethan_new = trim($_POST['ngayhethan'] ?? '');
    $ghichu_new = trim($_POST['ghichu'] ?? '');
    
    $madausachArr = $_POST['madausach'] ?? [];
    $soluongArr = $_POST['soluong'] ?? [];

    try {
        if (!$ngaymuon_new || !$ngayhethan_new) {
            throw new Exception("Vui lòng chọn ngày lấy sách và ngày trả dự kiến.");
        }

        // Gom nhóm sách theo đầu sách (tránh trường hợp 1 đầu sách bị chọn ở 2 dòng khác nhau)
        $bookGroups = [];
        $totalItems = 0;
        foreach ($madausachArr as $i => $ma) {
            $ma = trim($ma);
            if (!$ma) continue;
            
            $qty = (int)($soluongArr[$i] ?? 0);
            if ($qty <= 0) continue;

            if (!isset($bookGroups[$ma])) $bookGroups[$ma] = 0;
            $bookGroups[$ma] += $qty;
            $totalItems += $qty;
        }

        if ($totalItems === 0) {
            throw new Exception("Phiếu mượn phải chứa ít nhất 1 cuốn sách.");
        }

        $conn->begin_transaction();

        // 2.1 Cập nhật thông tin trên phieumuon
        $updPm = $conn->prepare("UPDATE phieumuon SET ngaymuon = ?, ngayhethan = ?, ghichu = ? WHERE mamuon = ?");
        $updPm->bind_param("ssss", $ngaymuon_new, $ngayhethan_new, $ghichu_new, $mamuon);
        $updPm->execute();
        $updPm->close();

        // 2.2 Thu hồi sách cũ về lại kho (SanSang)
        $stmtCt = $conn->prepare("SELECT macuonsach FROM ctphieumuon WHERE mamuon = ?");
        $stmtCt->bind_param("s", $mamuon);
        $stmtCt->execute();
        $resCt = $stmtCt->get_result();
        $macuonsachs = [];
        while ($r = $resCt->fetch_assoc()) {
            $macuonsachs[] = $r['macuonsach'];
        }
        $resCt->free();
        $stmtCt->close();

        if (!empty($macuonsachs)) {
            $placeholders = str_repeat('?,', count($macuonsachs) - 1) . '?';
            $updateQuery = "UPDATE cuonsach SET trangthai = 'SanSang' WHERE macuonsach IN ($placeholders)";
            $stmtUpdate = $conn->prepare($updateQuery);
            $types = str_repeat('s', count($macuonsachs));
            $stmtUpdate->bind_param($types, ...$macuonsachs);
            $stmtUpdate->execute();
            $stmtUpdate->close();
        }

        // Xóa chi tiết cũ
        $delCt = $conn->prepare("DELETE FROM ctphieumuon WHERE mamuon = ?");
        $delCt->bind_param("s", $mamuon);
        $delCt->execute();
        $delCt->close();

        // 2.3 Quét kiểm tra kho và Phân bổ sách mới
        // CHÚ Ý: Đưa các Prepare ra ngoài vòng lặp để MySQLi không bị ngộp và out of sync
        $checkInv = $conn->prepare("SELECT COUNT(*) as stock, tensach FROM cuonsach JOIN dausach ON cuonsach.madausach = dausach.madausach WHERE cuonsach.madausach = ? AND cuonsach.trangthai = 'SanSang'");
        $getIds = $conn->prepare("SELECT macuonsach, tinhtrang FROM cuonsach WHERE madausach = ? AND trangthai = 'SanSang' LIMIT ?");
        $insCt = $conn->prepare("INSERT INTO ctphieumuon (mamuon, macuonsach, tinhtrang_truoc) VALUES (?, ?, ?)");
        $updCd = $conn->prepare("UPDATE cuonsach SET trangthai = 'DaMuon' WHERE macuonsach = ?");

        foreach ($bookGroups as $ma => $qty) {
            $checkInv->bind_param("s", $ma);
            $checkInv->execute();
            $resInv = $checkInv->get_result();
            $invRes = $resInv->fetch_assoc();
            $resInv->free();
            
            $stock = (int)($invRes['stock'] ?? 0);
            $tensach = $invRes['tensach'] ?? $ma;
            
            if ($stock < $qty) {
                throw new Exception("Sách '$tensach' chỉ còn $stock cuốn trong kho, không đủ $qty cuốn để mượn.");
            }

            $getIds->bind_param("si", $ma, $qty);
            $getIds->execute();
            $resIds = $getIds->get_result();
            $booksToAssign = $resIds->fetch_all(MYSQLI_ASSOC);
            $resIds->free();

            foreach ($booksToAssign as $book) {
                $bookId = $book['macuonsach'];
                $tt = $book['tinhtrang'] ?? 'Tốt';

                $insCt->bind_param("sss", $mamuon, $bookId, $tt);
                $insCt->execute();

                $updCd->bind_param("s", $bookId);
                $updCd->execute();
            }
        }
        
        // Đóng dọn dẹp bộ nhớ
        $checkInv->close();
        $getIds->close();
        $insCt->close();
        $updCd->close();

        $conn->commit();
        $success = "Lưu cập nhật thành công!";
        
        // Tải lại thông tin mới nhất
        $stmt = $conn->prepare("SELECT mamuon, madocgia, ngaymuon, ngayhethan, trangthai, ghichu FROM phieumuon WHERE mamuon = ?");
        $stmt->bind_param("s", $mamuon);
        $stmt->execute();
        $resPhieu = $stmt->get_result();
        $phieu = $resPhieu->fetch_assoc();
        $resPhieu->free();
        $stmt->close();
        
    } catch (Exception $e) {
        $conn->rollback();
        $error = $e->getMessage();
    }
}

// 3. Tải danh sách sách HIỆN TẠI đang có trong phiếu để hiển thị ban đầu
$stmtLines = $conn->prepare("
    SELECT c.madausach, COUNT(ct.macuonsach) as soluong 
    FROM ctphieumuon ct
    JOIN cuonsach c ON ct.macuonsach = c.macuonsach
    WHERE ct.mamuon = ?
    GROUP BY c.madausach
");
$stmtLines->bind_param("s", $mamuon);
$stmtLines->execute();
$currentLines = $stmtLines->get_result()->fetch_all(MYSQLI_ASSOC);
$stmtLines->close();

// 4. Danh sách Nguồn (Tất cả Đầu Sách + SL Tồn)
try {
    // Chỉ cần lấy các Đầu Sách nào đang có SanSang hoặc chính các cuốn sách của phiếu này cũng được tính là SanSang
    $rs = $conn->query("
        SELECT ds.madausach, ds.tensach,
             (SELECT COUNT(*) FROM cuonsach WHERE madausach = ds.madausach AND trangthai = 'SanSang') as available
        FROM dausach ds
        ORDER BY ds.tensach ASC
    ");
    $dausachList = $rs ? $rs->fetch_all(MYSQLI_ASSOC) : [];
} catch (Throwable $e) {}

$backUrl = '/admin/QuanLy/HoaDon/phieu_detail.php?type=muon&id=' . rawurlencode($mamuon);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Phiếu Mượn PENDING</title>
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
                <h2 class="fw-bold mb-1">Sửa Phiếu Mượn <span class="text-primary"><?= h($mamuon) ?></span></h2>
                <div class="text-muted">Chỉ áp dụng thu hồi & phân bổ lại sách trên phiếu chưa quét giao.</div>
            </div>
            <div>
                <a class="btn btn-outline-secondary" href="<?= h($backUrl) ?>"><i class="fa-solid fa-arrow-left"></i> Quay lại</a>
            </div>
        </div>

        <?php if ($success !== ''): ?>
            <div class="alert alert-success"><i class="fa-solid fa-check"></i> <?= h($success) ?></div>
        <?php endif; ?>

        <?php if ($error !== ''): ?>
            <div class="alert alert-danger"><i class="fa-solid fa-triangle-exclamation"></i> <?= h($error) ?></div>
        <?php endif; ?>

        <form method="post" class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="fw-bold text-success mb-3"><i class="fa-regular fa-calendar"></i> Thông tin thời gian & Ghi chú</h5>
                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-4">
                        <label class="form-label text-muted small fw-bold">Người mượn (Mã ĐG)</label>
                        <input class="form-control bg-light" type="text" value="<?= h($phieu['madocgia']) ?>" readonly>
                    </div>
                    <?php
                        // Normalize datetime for input type="datetime-local"
                        $muonDate = ($phieu['ngaymuon']) ? date('Y-m-d\TH:i', strtotime($phieu['ngaymuon'])) : '';
                        $hetDate = ($phieu['ngayhethan']) ? date('Y-m-d\TH:i', strtotime($phieu['ngayhethan'])) : '';
                    ?>
                    <div class="col-12 col-md-4">
                        <label class="form-label text-muted small fw-bold">Ngày Hẹn Lấy Sách (Mượn)</label>
                        <input class="form-control" name="ngaymuon" type="datetime-local" value="<?= h($muonDate) ?>" required>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label text-muted small fw-bold">Ngày Hẹn Trả Dự Kiến</label>
                        <input class="form-control" name="ngayhethan" type="datetime-local" value="<?= h($hetDate) ?>" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label text-muted small fw-bold">Ghi chú phiếu</label>
                        <input class="form-control" name="ghichu" type="text" value="<?= h((string)$phieu['ghichu']) ?>" placeholder="Ví dụ: Độc giả báo tự đến lấy...">
                    </div>
                </div>

                <hr class="mb-4">

                <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between mb-3">
                    <h5 class="fw-bold text-primary mb-0"><i class="fa-solid fa-book"></i> Danh Mục Đầu Sách</h5>
                    <button class="btn btn-sm btn-outline-primary" type="button" id="addLineBtn">
                        <i class="fa-solid fa-plus"></i> Thêm sách
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle border" id="linesTable">
                        <thead class="table-light">
                        <tr>
                            <th>Tên Đầu Sách (Số lượng đang còn trên kệ)</th>
                            <th style="width: 150px;">SL Yêu Cầu</th>
                            <th style="width: 80px;" class="text-end">Xóa</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($currentLines as $idx => $line): ?>
                            <tr>
                                <td>
                                    <select class="form-select" name="madausach[]" required>
                                        <option value="">-- Chọn danh mục sách --</option>
                                        <?php foreach ($dausachList as $ds): ?>
                                            <?php
                                            $val = (string)($ds['madausach'] ?? '');
                                            // Ảo hóa: Khi sửa cái mã hiện tại đang mượn thì Tồn Kho thực tế của nó sẽ là:
                                            // (Tồn SanSang trong báo cáo) + số lượng nó đang mượn trong hóa đơn này!
                                            // Lý do: Nếu kho báo 0 nhưng hóa đơn này đang giữ 2 cuốn, nó hoàn toàn có quyền giữ lại 2 cuốn đó!
                                            $avail = (int)$ds['available'];
                                            if ($val === $line['madausach']) {
                                                $avail += (int)$line['soluong'];
                                            }
                                            $text = $val . ' - ' . (string)($ds['tensach'] ?? '') . ' (Rảnh trong kho + hóa đơn: ' . $avail . ')';
                                            $selected = ($val === $line['madausach']) ? 'selected' : '';
                                            ?>
                                            <option value="<?= h($val) ?>" <?= $selected ?>><?= h($text) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <input class="form-control" name="soluong[]" type="number" min="1" step="1" value="<?= h((string)$line['soluong']) ?>" required>
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-danger" type="button" onclick="removeLine(this)">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="alert alert-warning small mt-3">
                    <i class="fa-solid fa-circle-info"></i> Hệ thống sẽ tự động thu hồi toàn bộ số sách cũ trên phiếu này đưa lại vào kho chung, sau đó lấy thông số cấu hình mới nhất này tự động chọn ngẫu nhiên các mã cuốn sách (CSxxx) tương ứng còn "Sẵn Sàng" dán lại vào phiếu.
                </div>

                <div class="text-end mt-4">
                    <button class="btn btn-success px-4" type="submit"><i class="fa-solid fa-floppy-disk"></i> Lưu & Quét Lại Kho Sách</button>
                </div>
            </div>
        </form>
    </div>
</main>

<script>
    const dsOptionsHtml = <?php
        $opts = '<option value="">-- Chọn danh mục sách --</option>';
        foreach ($dausachList as $ds) {
            $val = (string)($ds['madausach'] ?? '');
            $text = $val . ' - ' . (string)($ds['tensach'] ?? '') . ' (Kệ rảnh: ' . $ds['available'] . ')';
            $opts .= '<option value="' . htmlspecialchars($val, ENT_QUOTES) . '">' . htmlspecialchars($text, ENT_QUOTES) . '</option>';
        }
        echo json_encode($opts, JSON_UNESCAPED_UNICODE);
    ?>;

    function removeLine(btn) {
        const tr = btn.closest('tr');
        if (tr) tr.remove();
    }

    document.getElementById('addLineBtn')?.addEventListener('click', () => {
        const tbody = document.querySelector('#linesTable tbody');
        if (!tbody) return;

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>
                <select class="form-select" name="madausach[]" required>${dsOptionsHtml}</select>
            </td>
            <td>
                <input class="form-control" name="soluong[]" type="number" min="1" step="1" value="1" required>
            </td>
            <td class="text-end">
                <button class="btn btn-sm btn-outline-danger" type="button" onclick="removeLine(this)">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    });
</script>
<script src="/assets/bootstrap/js/bootstrap.bundle.min.js" defer></script>
</body>
</html>
