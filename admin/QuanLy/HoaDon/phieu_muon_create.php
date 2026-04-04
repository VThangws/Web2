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

function post_str(string $key, string $default = ''): string
{
    $val = $_POST[$key] ?? $default;
    return trim((string) $val);
}

function next_prefixed_id(mysqli $conn, string $table, string $column, string $prefix, int $pad = 4): string
{
    $like = $prefix . '%';
    $sql = "SELECT $column AS id FROM $table WHERE $column LIKE ? ORDER BY $column DESC LIMIT 1 FOR UPDATE";
    $stmt = $conn->prepare($sql);
    if (!$stmt)
        return $prefix . '0001';
    $stmt->bind_param('s', $like);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $lastId = $row['id'] ?? '';
    $num = 0;
    if ($lastId !== '' && preg_match('/' . preg_quote($prefix, '/') . '(\d+)$/', $lastId, $m)) {
        $num = (int) $m[1];
    }
    return $prefix . str_pad((string) ($num + 1), $pad, '0', STR_PAD_LEFT);
}

$error = '';
$successId = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $madocgia = post_str('madocgia');
    $loaimuon = post_str('loaimuon', 'ON_SITE');
    $ngayhethan = post_str('ngayhethan');
    $ghichu = post_str('ghichu');

    $madausachArr = $_POST['madausach'] ?? [];
    $soluongArr = $_POST['soluong'] ?? [];

    try {
        if ($madocgia === '')
            throw new Exception("Vui lòng chọn độc giả.");
        if ($ngayhethan === '')
            throw new Exception("Vui lòng chọn ngày trả dự kiến.");

        $validBooks = [];
        foreach ($madausachArr as $i => $ma) {
            $ma = trim((string) $ma);
            $qty = (int) ($soluongArr[$i] ?? 0);
            if ($ma === '' || $qty <= 0)
                continue;

            if (isset($validBooks[$ma]))
                $validBooks[$ma] += $qty;
            else
                $validBooks[$ma] = $qty;
        }
        if (empty($validBooks))
            throw new Exception("Vui lòng chọn ít nhất 1 cuốn sách.");

        $conn->begin_transaction();

        // 1. Kiểm tra độc giả có tồn tại không
        $stmtDg = $conn->prepare("SELECT madocgia FROM docgia WHERE madocgia = ?");
        $stmtDg->bind_param('s', $madocgia);
        $stmtDg->execute();
        if ($stmtDg->get_result()->num_rows === 0)
            throw new Exception("Mã độc giả không tồn tại.");
        $stmtDg->close();

        // 2. Tạo mã phiếu mượn
        $mamuon = next_prefixed_id($conn, 'phieumuon', 'mamuon', 'PM', 4);
        $user = admin_current_user();
        $manv = $user['manv'] ?? null;
        $trangthai = ($loaimuon === 'ONLINE') ? 'ChoDuyet' : 'DangMuon';

        // Gộp hình thức vào ghi chú nếu bảng phieumuon chưa hỗ trợ cột loaimuon
        $formattedGhichu = "[Hình thức: " . ($loaimuon === 'ONLINE' ? 'Mượn về' : 'Đọc tại chỗ') . "] " . $ghichu;

        $stmtIns = $conn->prepare("INSERT INTO phieumuon (mamuon, madocgia, manv, ngaymuon, ngayhethan, trangthai, ghichu) VALUES (?, ?, ?, NOW(), ?, ?, ?)");
        $stmtIns->bind_param('ssssss', $mamuon, $madocgia, $manv, $ngayhethan, $trangthai, $formattedGhichu);

        if (!$stmtIns->execute())
            throw new Exception("Lỗi tạo phiếu mượn: " . $stmtIns->error);
        $stmtIns->close();

        // 3. Phân bổ sách
        $getAvailable = $conn->prepare("SELECT macuonsach, tinhtrang FROM cuonsach WHERE madausach = ? AND trangthai = 'SanSang' LIMIT ?");
        $insCt = $conn->prepare("INSERT INTO ctphieumuon (mamuon, macuonsach, tinhtrang_truoc) VALUES (?, ?, ?)");
        $updBook = $conn->prepare("UPDATE cuonsach SET trangthai = 'DaMuon' WHERE macuonsach = ?");

        foreach ($validBooks as $ma => $qty) {
            $getAvailable->bind_param('si', $ma, $qty);
            $getAvailable->execute();
            $books = $getAvailable->get_result()->fetch_all(MYSQLI_ASSOC);

            if (count($books) < $qty) {
                // Lấy tên sách để báo lỗi
                $stmtDs = $conn->prepare("SELECT tensach FROM dausach WHERE madausach = ?");
                $stmtDs->bind_param('s', $ma);
                $stmtDs->execute();
                $dsRow = $stmtDs->get_result()->fetch_assoc();
                $ten = $dsRow['tensach'] ?? $ma;
                throw new Exception("Sách '$ten' không đủ số lượng (Chỉ còn " . count($books) . " cuốn rảnh).");
            }

            foreach ($books as $b) {
                $macuon = $b['macuonsach'];
                $tt = $b['tinhtrang'] ?? 'Tốt';

                $insCt->bind_param('sss', $mamuon, $macuon, $tt);
                $insCt->execute();

                $updBook->bind_param('s', $macuon);
                $updBook->execute();
            }
        }

        $conn->commit();
        $successId = $mamuon;
    } catch (Throwable $e) {
        $conn->rollback();
        $error = $e->getMessage();
    }
}

// Lấy danh sách nguồn
$docgiaList = $conn->query("SELECT madocgia, CONCAT(hodocgia, ' ', tendocgia) as hoten FROM docgia ORDER BY tendocgia ASC")->fetch_all(MYSQLI_ASSOC);
$dausachList = $conn->query("SELECT madausach, tensach, (SELECT COUNT(*) FROM cuonsach WHERE madausach = dausach.madausach AND trangthai = 'SanSang') as available FROM dausach ORDER BY tensach ASC")->fetch_all(MYSQLI_ASSOC);

$backUrl = '/admin/QuanLy/HoaDon/QL_HoaDon.php?type=muon';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Tạo phiếu mượn</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/admin_sidebar.css">
</head>

<body>
    <?php require_once __DIR__ . '/../../layout/admin_sidebar.php'; ?>
    <main class="container-fluid py-4">
        <div class="container-md">
            <div class="d-flex justify-content-between mb-3 align-items-center">
                <h2 class="fw-bold m-0 text-success"><i class="fa-solid fa-plus-circle"></i> Tạo phiếu mượn mới</h2>
                <a class="btn btn-outline-secondary" href="<?= $backUrl ?>"><i class="fa-solid fa-arrow-left"></i> Quay
                    lại</a>
            </div>

            <?php if ($successId): ?>
                <div class="alert alert-success">
                    Tạo phiếu thành công mã: <strong><?= $successId ?></strong>.
                    <a href="/admin/QuanLy/HoaDon/phieu_detail.php?type=muon&id=<?= $successId ?>" class="alert-link">Xem
                        chi tiết phiếu</a>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-danger"><i class="fa-solid fa-circle-exclamation"></i> <?= h($error) ?></div>
            <?php endif; ?>

            <form method="post" class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Chọn Độc giả</label>
                            <input class="form-control" list="dgData" name="madocgia"
                                placeholder="Nhập mã hoặc tên để tìm..." required>
                            <datalist id="dgData">
                                <?php foreach ($docgiaList as $dg): ?>
                                    <option value="<?= h($dg['madocgia']) ?>"><?= h($dg['hoten']) ?></option>
                                <?php endforeach; ?>
                            </datalist>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Hình thức mượn</label>
                            <select class="form-select" name="loaimuon">
                                <option value="ON_SITE">Đọc tại chỗ (Sách lấy ngay)</option>
                                <option value="ONLINE">Mượn về (Hẹn lấy)</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Hạn trả dự kiến</label>
                            <input type="date" class="form-control" name="ngayhethan"
                                value="<?= date('Y-m-d', strtotime('+14 days')) ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Ghi chú</label>
                            <textarea class="form-control" name="ghichu" rows="1"
                                placeholder="Ví dụ: Mượn làm đồ án..."></textarea>
                        </div>
                    </div>

                    <hr>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="fw-bold text-primary m-0"><i class="fa-solid fa-book"></i> Danh sách sách mượn</h5>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="addBookBtn"><i
                                class="fa-solid fa-plus"></i> Thêm sách</button>
                    </div>

                    <div class="table-responsive">
                        <table class="table border" id="bookTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Đầu sách (Tồn rảnh)</th>
                                    <th style="width: 150px;">Số lượng</th>
                                    <th style="width: 50px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="book-row">
                                    <td>
                                        <select class="form-select" name="madausach[]" required>
                                            <option value="">-- Chọn đầu sách --</option>
                                            <?php foreach ($dausachList as $ds): ?>
                                                <option value="<?= h($ds['madausach']) ?>"><?= h($ds['tensach']) ?> (Còn:
                                                    <?= $ds['available'] ?>)</option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td><input type="number" class="form-control" name="soluong[]" value="1" min="1"
                                            required></td>
                                    <td><button type="button" class="btn btn-sm text-danger"
                                            onclick="this.closest('tr').remove()"><i
                                                class="fa-solid fa-trash"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-success px-5 fw-bold"><i
                                class="fa-solid fa-floppy-disk"></i> Tạo phiếu mượn</button>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <script>
        const dsParsed = <?= json_encode($dausachList, JSON_UNESCAPED_UNICODE) ?>;
        let optionsHtml = '<option value="">-- Chọn đầu sách --</option>';
        dsParsed.forEach(ds => {
            optionsHtml += `<option value="${ds.madausach}">${ds.tensach} (Còn: ${ds.available})</option>`;
        });

        document.getElementById('addBookBtn').addEventListener('click', () => {
            const tr = document.createElement('tr');
            tr.className = 'book-row';
            tr.innerHTML = `
            <td><select class="form-select" name="madausach[]" required>${optionsHtml}</select></td>
            <td><input type="number" class="form-control" name="soluong[]" value="1" min="1" required></td>
            <td><button type="button" class="btn btn-sm text-danger" onclick="this.closest('tr').remove()"><i class="fa-solid fa-trash"></i></button></td>
        `;
            document.querySelector('#bookTable tbody').appendChild(tr);
        });
    </script>
    <script src="/assets/bootstrap/js/bootstrap.bundle.min.js" defer></script>
</body>

</html>