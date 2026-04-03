<?php
require_once __DIR__ . '/../../login/auth.php';
require_admin_login();
require_admin_permission_for_request('HOADON');

require_once __DIR__ . '/../../../database/ConnectDB.php';

$conn = ConnectDB::getInstance()->getConnection();
$conn->set_charset('utf8mb4');

function h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function post_str(string $key, string $default = ''): string
{
    if (!isset($_POST[$key])) {
        return $default;
    }
    return trim((string)$_POST[$key]);
}

function post_arr(string $key): array
{
    $value = $_POST[$key] ?? [];
    return is_array($value) ? $value : [];
}

function next_prefixed_id(mysqli $conn, string $table, string $column, string $prefix, int $pad = 4): string
{
    $like = $prefix . '%';
    $sql = "SELECT $column AS id FROM $table WHERE $column LIKE ? ORDER BY $column DESC LIMIT 1 FOR UPDATE";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        return $prefix . substr(md5((string)microtime(true)), 0, 10);
    }
    $stmt->bind_param('s', $like);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $lastId = is_array($row) ? (string)($row['id'] ?? '') : '';
    $num = 0;

    $pattern = '/^' . preg_quote($prefix, '/') . '(\d+)$/';
    if ($lastId !== '' && preg_match($pattern, $lastId, $m)) {
        $num = (int)$m[1];
    }

    $next = $num + 1;
    $digits = str_pad((string)$next, $pad, '0', STR_PAD_LEFT);
    $id = $prefix . $digits;
    return strlen($id) <= 50 ? $id : substr($id, 0, 50);
}

$successId = '';
$error = '';

$mancc = '';
$mavitri = '';
$lines = [
    ['madausach' => '', 'soluong' => '1', 'dongianhap' => '0'],
    ['madausach' => '', 'soluong' => '1', 'dongianhap' => '0'],
    ['madausach' => '', 'soluong' => '1', 'dongianhap' => '0'],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mancc = post_str('mancc');
    $mavitri = post_str('mavitri');

    $madausachArr = post_arr('madausach');
    $soluongArr = post_arr('soluong');
    $dongiaArr = post_arr('dongianhap');

    $lines = [];
    $rawCount = max(count($madausachArr), count($soluongArr), count($dongiaArr));
    for ($i = 0; $i < $rawCount; $i++) {
        $lines[] = [
            'madausach' => trim((string)($madausachArr[$i] ?? '')),
            'soluong' => trim((string)($soluongArr[$i] ?? '')),
            'dongianhap' => trim((string)($dongiaArr[$i] ?? '')),
        ];
    }

    try {
        if ($mancc === '') {
            throw new RuntimeException('Vui lòng chọn nhà cung cấp.');
        }
        if ($mavitri === '') {
            throw new RuntimeException('Vui lòng chọn vị trí nhập (kệ).');
        }

        $validItems = [];
        foreach ($lines as $ln) {
            $madausach = trim((string)($ln['madausach'] ?? ''));
            if ($madausach === '') {
                continue;
            }
            $qty = (int)($ln['soluong'] ?? 0);
            $price = (float)($ln['dongianhap'] ?? 0);
            if ($qty <= 0) {
                continue;
            }
            if ($price < 0) {
                throw new RuntimeException('Đơn giá nhập không hợp lệ.');
            }

            if (!isset($validItems[$madausach])) {
                $validItems[$madausach] = ['soluong' => 0, 'dongianhap' => $price];
            }
            $validItems[$madausach]['soluong'] += $qty;
            $validItems[$madausach]['dongianhap'] = $price;
        }

        if (!$validItems) {
            throw new RuntimeException('Vui lòng nhập ít nhất 1 dòng đầu sách có số lượng hợp lệ.');
        }

        $user = admin_current_user();
        $manv = isset($user['manv']) && is_string($user['manv']) ? trim($user['manv']) : '';
        if ($manv === '') {
            throw new RuntimeException('Không xác định được nhân viên đang đăng nhập.');
        }

        $conn->begin_transaction();

        // Validate foreign keys early for clearer error messages.
        $stmt = $conn->prepare('SELECT 1 FROM nhacungcap WHERE mancc = ? LIMIT 1');
        $stmt->bind_param('s', $mancc);
        $stmt->execute();
        if ($stmt->get_result()->num_rows === 0) {
            $stmt->close();
            throw new RuntimeException('Nhà cung cấp không tồn tại.');
        }
        $stmt->close();

        $stmt = $conn->prepare('SELECT 1 FROM vitri WHERE mavitri = ? LIMIT 1');
        $stmt->bind_param('s', $mavitri);
        $stmt->execute();
        if ($stmt->get_result()->num_rows === 0) {
            $stmt->close();
            throw new RuntimeException('Vị trí không tồn tại.');
        }
        $stmt->close();

        $checkDausach = $conn->prepare('SELECT 1 FROM dausach WHERE madausach = ? LIMIT 1');
        if ($checkDausach === false) {
            throw new RuntimeException('Không thể kiểm tra đầu sách.');
        }
        foreach (array_keys($validItems) as $madausach) {
            $checkDausach->bind_param('s', $madausach);
            $checkDausach->execute();
            if ($checkDausach->get_result()->num_rows === 0) {
                $checkDausach->close();
                throw new RuntimeException('Đầu sách không tồn tại: ' . $madausach);
            }
        }
        $checkDausach->close();

        // Generate IDs under transaction.
        $maphieunhap = '';
        for ($attempt = 0; $attempt < 5; $attempt++) {
            $candidate = next_prefixed_id($conn, 'phieunhap', 'maphieunhap', 'PN', 4);

            $tongtien = 0.0;
            foreach ($validItems as $it) {
                $tongtien += ((float)$it['dongianhap']) * ((int)$it['soluong']);
            }
            $tongtien = round($tongtien, 2);

            $ins = $conn->prepare('INSERT INTO phieunhap (maphieunhap, thoigiantao, tongtien, manv, mancc) VALUES (?, NOW(), ?, ?, ?)');
            if ($ins === false) {
                throw new RuntimeException('Không thể tạo phiếu nhập (prepare failed).');
            }
            $ins->bind_param('sdss', $candidate, $tongtien, $manv, $mancc);
            $ok = $ins->execute();
            $errNo = $conn->errno;
            $ins->close();

            if ($ok) {
                $maphieunhap = $candidate;
                break;
            }

            // Duplicate key, retry.
            if ($errNo !== 1062) {
                throw new RuntimeException('Không thể tạo phiếu nhập.');
            }
        }

        if ($maphieunhap === '') {
            throw new RuntimeException('Không thể sinh mã phiếu nhập, vui lòng thử lại.');
        }

        $insCt = $conn->prepare('INSERT INTO ctphieunhap (maphieunhap, madausach, dongianhap, soluong) VALUES (?, ?, ?, ?)');
        if ($insCt === false) {
            throw new RuntimeException('Không thể tạo chi tiết phiếu nhập.');
        }

        // Lock the latest copy id then generate sequentially.
        $lastCopyId = '';
        $stmt = $conn->prepare("SELECT macuonsach FROM cuonsach WHERE macuonsach LIKE 'CS%' ORDER BY macuonsach DESC LIMIT 1 FOR UPDATE");
        if ($stmt) {
            $stmt->execute();
            $r = $stmt->get_result()->fetch_assoc();
            $lastCopyId = is_array($r) ? (string)($r['macuonsach'] ?? '') : '';
            $stmt->close();
        }
        $copyNum = 0;
        if ($lastCopyId !== '' && preg_match('/^CS(\d+)$/', $lastCopyId, $m)) {
            $copyNum = (int)$m[1];
        }

        $insCopy = $conn->prepare("INSERT INTO cuonsach (macuonsach, madausach, mavitri, trangthai, tinhtrang) VALUES (?, ?, ?, 'SanSang', 'Moi')");
        if ($insCopy === false) {
            throw new RuntimeException('Không thể tạo cuốn sách.');
        }

        foreach ($validItems as $madausach => $it) {
            $qty = (int)$it['soluong'];
            $price = (float)$it['dongianhap'];

            $insCt->bind_param('ssdi', $maphieunhap, $madausach, $price, $qty);
            if (!$insCt->execute()) {
                throw new RuntimeException('Không thể lưu chi tiết phiếu nhập.');
            }

            for ($j = 0; $j < $qty; $j++) {
                $copyNum++;
                $macuonsach = 'CS' . str_pad((string)$copyNum, 4, '0', STR_PAD_LEFT);
                if (strlen($macuonsach) > 50) {
                    $macuonsach = substr($macuonsach, 0, 50);
                }

                $insCopy->bind_param('sss', $macuonsach, $madausach, $mavitri);
                if (!$insCopy->execute()) {
                    throw new RuntimeException('Không thể tạo cuốn sách mới.');
                }
            }
        }

        $insCt->close();
        $insCopy->close();

        $conn->commit();

        $successId = $maphieunhap;
    } catch (Throwable $e) {
        try {
            if ($conn->errno === 0) {
                // no-op
            }
            if ($conn->ping()) {
                $conn->rollback();
            }
        } catch (Throwable $ignored) {
        }
        $error = $e->getMessage();
    }
}

$nccList = [];
$vitriList = [];
$dausachList = [];

try {
    $rs = $conn->query('SELECT mancc, COALESCE(tenncc,\'\') AS tenncc FROM nhacungcap ORDER BY tenncc ASC');
    if ($rs) {
        $nccList = $rs->fetch_all(MYSQLI_ASSOC);
    }

    $rs = $conn->query("SELECT mavitri, COALESCE(khuvuc,'') AS khuvuc, COALESCE(ke,'') AS ke, COALESCE(mota,'') AS mota FROM vitri ORDER BY mavitri ASC");
    if ($rs) {
        $vitriList = $rs->fetch_all(MYSQLI_ASSOC);
    }

    $rs = $conn->query("SELECT madausach, COALESCE(tensach,'') AS tensach FROM dausach ORDER BY tensach ASC");
    if ($rs) {
        $dausachList = $rs->fetch_all(MYSQLI_ASSOC);
    }
} catch (Throwable $e) {
    if ($error === '') {
        $error = $e->getMessage();
    }
}

if ($mavitri === '' && $vitriList) {
    $mavitri = (string)($vitriList[0]['mavitri'] ?? '');
}

$backUrl = '/admin/QuanLy/HoaDon/QL_HoaDon.php?type=nhap';
$detailUrl = $successId !== ''
    ? ('/admin/QuanLy/HoaDon/phieu_detail.php?type=nhap&id=' . rawurlencode($successId))
    : '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo phiếu nhập</title>

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
                <h2 class="fw-bold mb-1">Tạo phiếu nhập</h2>
                <div class="text-muted">Nhập hàng từ nhà cung cấp</div>
            </div>
            <div>
                <a class="btn btn-outline-secondary" href="<?= h($backUrl) ?>"><i class="fa-solid fa-arrow-left"></i> Quay lại</a>
            </div>
        </div>

        <?php if ($successId !== ''): ?>
            <div class="alert alert-success d-flex flex-wrap gap-2 align-items-center">
                <div>Tạo phiếu nhập thành công: <span class="fw-semibold"><?= h($successId) ?></span></div>
                <a class="btn btn-sm btn-success" href="<?= h($detailUrl) ?>">Xem chi tiết</a>
            </div>
        <?php endif; ?>

        <?php if ($error !== ''): ?>
            <div class="alert alert-danger"><?= h($error) ?></div>
        <?php endif; ?>

        <form method="post" class="card">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label">Nhà cung cấp</label>
                        <select class="form-select" name="mancc" required>
                            <option value="">-- Chọn nhà cung cấp --</option>
                            <?php foreach ($nccList as $ncc): ?>
                                <?php $val = (string)($ncc['mancc'] ?? ''); ?>
                                <option value="<?= h($val) ?>" <?= $val === $mancc ? 'selected' : '' ?>>
                                    <?= h($val . ' - ' . (string)($ncc['tenncc'] ?? '')) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">Vị trí (kệ) cho sách nhập</label>
                        <select class="form-select" name="mavitri" required>
                            <option value="">-- Chọn vị trí --</option>
                            <?php foreach ($vitriList as $vt): ?>
                                <?php
                                $val = (string)($vt['mavitri'] ?? '');
                                $label = trim((string)($vt['khuvuc'] ?? ''));
                                $ke = trim((string)($vt['ke'] ?? ''));
                                $mota = trim((string)($vt['mota'] ?? ''));
                                $text = $val;
                                if ($label !== '' || $ke !== '') {
                                    $text .= ' - ' . trim($label . ' ' . $ke);
                                }
                                if ($mota !== '') {
                                    $text .= ' (' . $mota . ')';
                                }
                                ?>
                                <option value="<?= h($val) ?>" <?= $val === $mavitri ? 'selected' : '' ?>><?= h($text) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text">Tất cả cuốn sách được tạo sẽ gán cùng vị trí này.</div>
                    </div>

                    <div class="col-12">
                        <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between">
                            <div class="fw-semibold">Chi tiết nhập</div>
                            <button class="btn btn-sm btn-outline-primary" type="button" id="addLineBtn">
                                <i class="fa-solid fa-plus"></i> Thêm dòng
                            </button>
                        </div>

                        <div class="table-responsive mt-2">
                            <table class="table table-striped align-middle" id="linesTable">
                                <thead>
                                <tr>
                                    <th style="min-width: 260px;">Đầu sách</th>
                                    <th style="width: 140px;">Số lượng</th>
                                    <th style="width: 180px;">Đơn giá nhập</th>
                                    <th style="width: 60px;"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($lines as $idx => $ln): ?>
                                    <tr>
                                        <td>
                                            <select class="form-select" name="madausach[]">
                                                <option value="">-- Chọn đầu sách --</option>
                                                <?php foreach ($dausachList as $ds): ?>
                                                    <?php
                                                    $val = (string)($ds['madausach'] ?? '');
                                                    $text = $val . ' - ' . (string)($ds['tensach'] ?? '');
                                                    $selected = $val !== '' && $val === (string)($ln['madausach'] ?? '') ? 'selected' : '';
                                                    ?>
                                                    <option value="<?= h($val) ?>" <?= $selected ?>><?= h($text) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input class="form-control" name="soluong[]" type="number" min="1" step="1" value="<?= h((string)($ln['soluong'] ?? '1')) ?>">
                                        </td>
                                        <td>
                                            <input class="form-control" name="dongianhap[]" type="number" min="0" step="0.01" value="<?= h((string)($ln['dongianhap'] ?? '0')) ?>">
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
                        <div class="text-muted small">Lưu ý: Các dòng trống hoặc số lượng không hợp lệ sẽ bị bỏ qua.</div>
                    </div>

                    <div class="col-12 d-grid d-md-flex gap-2 justify-content-md-end">
                        <button class="btn btn-primary" type="submit"><i class="fa-solid fa-floppy-disk"></i> Tạo phiếu nhập</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

<script>
    const dsOptionsHtml = <?php
        $opts = '<option value="">-- Chọn đầu sách --</option>';
        foreach ($dausachList as $ds) {
            $val = (string)($ds['madausach'] ?? '');
            $text = $val . ' - ' . (string)($ds['tensach'] ?? '');
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
                <select class="form-select" name="madausach[]">${dsOptionsHtml}</select>
            </td>
            <td>
                <input class="form-control" name="soluong[]" type="number" min="1" step="1" value="1">
            </td>
            <td>
                <input class="form-control" name="dongianhap[]" type="number" min="0" step="0.01" value="0">
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
