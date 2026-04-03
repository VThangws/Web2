<?php
require_once __DIR__ . '/../../login/auth.php';
require_admin_login();
require_admin_permission_for_request('TAIKHOAN');

require_once __DIR__ . '/../../../database/ConnectDB.php';

$conn = ConnectDB::getInstance()->getConnection();
$conn->set_charset('utf8mb4');

function h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function get_str(array $src, string $key, string $default = ''): string
{
    if (!isset($src[$key])) {
        return $default;
    }
    return trim((string)$src[$key]);
}

function redirect_with(array $params): never
{
    $base = '/admin/QuanLy/TaiKhoan/QL_TaiKhoan.php';
    $q = http_build_query($params);
    header('Location: ' . $base . ($q ? ('?' . $q) : ''));
    exit;
}

function nhanvien_exists(mysqli $conn, string $manv): bool
{
    $stmt = $conn->prepare('SELECT 1 FROM nhanvien WHERE manv = ? LIMIT 1');
    if (!$stmt) {
        return false;
    }
    $stmt->bind_param('s', $manv);
    $stmt->execute();
    $ok = $stmt->get_result()->num_rows > 0;
    $stmt->close();
    return $ok;
}

function nhomquyen_exists(mysqli $conn, string $manhomquyen): bool
{
    $stmt = $conn->prepare('SELECT 1 FROM nhomquyen WHERE manhomquyen = ? LIMIT 1');
    if (!$stmt) {
        return false;
    }
    $stmt->bind_param('s', $manhomquyen);
    $stmt->execute();
    $ok = $stmt->get_result()->num_rows > 0;
    $stmt->close();
    return $ok;
}

function column_exists(mysqli $conn, string $table, string $column): bool
{
    try {
        $stmt = $conn->prepare(
            "SELECT 1
             FROM INFORMATION_SCHEMA.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME = ?
               AND COLUMN_NAME = ?
             LIMIT 1"
        );
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param('ss', $table, $column);
        $stmt->execute();
        $ok = $stmt->get_result()->num_rows > 0;
        $stmt->close();
        return $ok;
    } catch (Throwable $e) {
        return false;
    }
}

$msg = get_str($_GET, 'msg');
$err = get_str($_GET, 'err');

$currentUsername = '';
if (isset($_SESSION['admin_user']) && is_array($_SESSION['admin_user'])) {
    $currentUsername = (string)($_SESSION['admin_user']['tendangnhap'] ?? '');
}

// Load roles for dropdown
$roles = [];
try {
    $res = $conn->query('SELECT manhomquyen, tennhomquyen FROM nhomquyen ORDER BY tennhomquyen ASC');
    $roles = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
} catch (Throwable $e) {
    // keep empty
}

$hasMadocgiaCol = column_exists($conn, 'taikhoan', 'madocgia');
$hasTrangthaiCol = column_exists($conn, 'taikhoan', 'trangthai');

// Load employees for quick selection (optional)
$employees = [];
$suggestedManv = '';
try {
    $res = $conn->query("SELECT manv, CONCAT(COALESCE(honv,''),' ',COALESCE(tennv,'')) AS hoten FROM nhanvien ORDER BY manv ASC LIMIT 300");
    $employees = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

    // Suggest the first employee (by manv) that hasn't been linked to any staff account yet.
    // This enables auto-filling sequentially when creating multiple accounts.
    $sqlSuggest =
        "SELECT nv.manv
         FROM nhanvien nv
         LEFT JOIN taikhoan tk ON tk.manv = nv.manv" .
        " WHERE tk.manv IS NULL
         ORDER BY nv.manv ASC
         LIMIT 1";
    $resSuggest = $conn->query($sqlSuggest);
    if ($resSuggest) {
        $rowSuggest = $resSuggest->fetch_assoc();
        $suggestedManv = is_array($rowSuggest) ? (string)($rowSuggest['manv'] ?? '') : '';
    }
} catch (Throwable $e) {
    // keep empty
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mode = get_str($_POST, 'mode');

    try {
        if ($mode === 'delete') {
            $tendangnhap = get_str($_POST, 'tendangnhap');
            if ($tendangnhap === '') {
                throw new RuntimeException('Thiếu tên đăng nhập.');
            }
            if ($tendangnhap === 'admin') {
                throw new RuntimeException('Không thể xóa tài khoản admin.');
            }
            if ($currentUsername !== '' && $tendangnhap === $currentUsername) {
                throw new RuntimeException('Không thể xóa tài khoản đang đăng nhập.');
            }

            $stmt = $conn->prepare('DELETE FROM taikhoan WHERE tendangnhap = ?');
            if (!$stmt) {
                throw new RuntimeException('Không thể chuẩn bị câu lệnh xóa.');
            }
            $stmt->bind_param('s', $tendangnhap);
            if (!$stmt->execute()) {
                throw new RuntimeException($stmt->error ?: 'Xóa thất bại.');
            }
            $affected = $stmt->affected_rows;
            $stmt->close();

            if ($affected <= 0) {
                throw new RuntimeException('Không tìm thấy tài khoản để xóa.');
            }

            redirect_with(['msg' => 'Đã xóa tài khoản ' . $tendangnhap]);
        }

        if ($mode === 'toggle_status') {
            if (!$hasTrangthaiCol) {
                throw new RuntimeException('Bảng taikhoan chưa có cột trangthai để khóa/mở tài khoản.');
            }

            $tendangnhap = get_str($_POST, 'tendangnhap');
            $nextStatusRaw = get_str($_POST, 'next_status');
            $nextStatus = ($nextStatusRaw === '0') ? 0 : 1;

            if ($tendangnhap === '') {
                throw new RuntimeException('Thiếu tên đăng nhập.');
            }
            if ($tendangnhap === 'admin' && $nextStatus === 0) {
                throw new RuntimeException('Không thể khóa tài khoản admin.');
            }
            if ($currentUsername !== '' && $tendangnhap === $currentUsername && $nextStatus === 0) {
                throw new RuntimeException('Không thể tự khóa tài khoản đang đăng nhập.');
            }

            $stmt = $conn->prepare('UPDATE taikhoan SET trangthai = ? WHERE tendangnhap = ?');
            if (!$stmt) {
                throw new RuntimeException('Không thể chuẩn bị câu lệnh khóa/mở.');
            }
            $stmt->bind_param('is', $nextStatus, $tendangnhap);
            if (!$stmt->execute()) {
                throw new RuntimeException($stmt->error ?: 'Cập nhật trạng thái thất bại.');
            }
            $stmt->close();

            $label = $nextStatus === 1 ? 'mở khóa' : 'khóa';
            redirect_with(['msg' => 'Đã ' . $label . ' tài khoản ' . $tendangnhap]);
        }

        $tendangnhap = get_str($_POST, 'tendangnhap');
        $matkhau = (string)($_POST['matkhau'] ?? '');
        $manhomquyen = get_str($_POST, 'manhomquyen');
        $manv = get_str($_POST, 'manv');

        if ($tendangnhap === '') {
            throw new RuntimeException('Tên đăng nhập không được để trống.');
        }

        if ($manhomquyen === '') {
            throw new RuntimeException('Vui lòng chọn nhóm quyền.');
        }

        if ($manhomquyen !== '' && !nhomquyen_exists($conn, $manhomquyen)) {
            throw new RuntimeException('Nhóm quyền không tồn tại: ' . $manhomquyen);
        }
        if ($manv !== '' && !nhanvien_exists($conn, $manv)) {
            throw new RuntimeException('Mã nhân viên không tồn tại: ' . $manv);
        }

        $hash = null;
        $matkhauTrim = trim($matkhau);
        if ($matkhauTrim !== '') {
            $hash = password_hash($matkhauTrim, PASSWORD_BCRYPT);
            if (!is_string($hash) || $hash === '') {
                throw new RuntimeException('Không thể tạo hash mật khẩu.');
            }
        }

        if ($mode === 'update') {
            if ($hash === null) {
                $stmt = $conn->prepare("UPDATE taikhoan
                                       SET manhomquyen = NULLIF(?, ''),
                                           manv = NULLIF(?, '')
                                       WHERE tendangnhap = ?");
                if (!$stmt) {
                    throw new RuntimeException('Không thể chuẩn bị câu lệnh cập nhật.');
                }
                $stmt->bind_param('sss', $manhomquyen, $manv, $tendangnhap);
            } else {
                $stmt = $conn->prepare("UPDATE taikhoan
                                       SET matkhau = ?,
                                           manhomquyen = NULLIF(?, ''),
                                           manv = NULLIF(?, '')
                                       WHERE tendangnhap = ?");
                if (!$stmt) {
                    throw new RuntimeException('Không thể chuẩn bị câu lệnh cập nhật.');
                }
                $stmt->bind_param('ssss', $hash, $manhomquyen, $manv, $tendangnhap);
            }

            if (!$stmt->execute()) {
                throw new RuntimeException($stmt->error ?: 'Cập nhật thất bại.');
            }
            $stmt->close();

            redirect_with(['msg' => 'Đã cập nhật tài khoản ' . $tendangnhap]);
        }

        // add
        if ($hash === null) {
            throw new RuntimeException('Mật khẩu không được để trống khi tạo tài khoản mới.');
        }

        if ($hasTrangthaiCol) {
            $stmt = $conn->prepare("INSERT INTO taikhoan (tendangnhap, matkhau, manhomquyen, manv, trangthai)
                                   VALUES (?, ?, NULLIF(?, ''), NULLIF(?, ''), 1)");
        } else {
            $stmt = $conn->prepare("INSERT INTO taikhoan (tendangnhap, matkhau, manhomquyen, manv)
                                   VALUES (?, ?, NULLIF(?, ''), NULLIF(?, ''))");
        }
        if (!$stmt) {
            throw new RuntimeException('Không thể chuẩn bị câu lệnh thêm mới.');
        }
        $stmt->bind_param('ssss', $tendangnhap, $hash, $manhomquyen, $manv);
        if (!$stmt->execute()) {
            $raw = $stmt->error ?: 'Thêm mới thất bại.';
            if (stripos($raw, 'fk_taikhoan_nhanvien') !== false) {
                throw new RuntimeException('Mã nhân viên không hợp lệ (không tồn tại).');
            }
            if (stripos($raw, 'fk_taikhoan_nhomquyen') !== false) {
                throw new RuntimeException('Nhóm quyền không hợp lệ (không tồn tại).');
            }
            throw new RuntimeException($raw);
        }
        $stmt->close();

        redirect_with(['msg' => 'Đã thêm tài khoản ' . $tendangnhap]);
    } catch (Throwable $e) {
        redirect_with(['err' => $e->getMessage()]);
    }
}

$q = get_str($_GET, 'q');
$edit = get_str($_GET, 'edit');
$statusSelect = $hasTrangthaiCol ? 'tk.trangthai' : '1 AS trangthai';

$editingRow = null;
if ($edit !== '') {
    $sqlEdit =
        "SELECT tk.tendangnhap, tk.manhomquyen, tk.manv, {$statusSelect},
                nq.tennhomquyen,
                CONCAT(COALESCE(nv.honv,''),' ',COALESCE(nv.tennv,'')) AS ten_nhanvien
         FROM taikhoan tk
         LEFT JOIN nhomquyen nq ON nq.manhomquyen = tk.manhomquyen
         LEFT JOIN nhanvien nv ON nv.manv = tk.manv
         WHERE tk.tendangnhap = ?";
    $stmt = $conn->prepare($sqlEdit);
    if ($stmt) {
        $stmt->bind_param('s', $edit);
        $stmt->execute();
        $editingRow = $stmt->get_result()->fetch_assoc();
        $stmt->close();
    }
}

$rows = [];
try {
    if ($q !== '') {
        $like = '%' . $q . '%';
        $sql =
                "SELECT tk.tendangnhap, tk.manhomquyen, nq.tennhomquyen, tk.manv, {$statusSelect},
                    CONCAT(COALESCE(nv.honv,''),' ',COALESCE(nv.tennv,'')) AS ten_nhanvien
             FROM taikhoan tk
             LEFT JOIN nhomquyen nq ON nq.manhomquyen = tk.manhomquyen
             LEFT JOIN nhanvien nv ON nv.manv = tk.manv
             WHERE (";
        $sql .=
            "tk.tendangnhap LIKE ?
                OR tk.manv LIKE ?
                OR COALESCE(nq.tennhomquyen,'') LIKE ?
                OR CONCAT(COALESCE(nv.honv,''),' ',COALESCE(nv.tennv,'')) LIKE ?
             )
             ORDER BY tk.tendangnhap ASC
             LIMIT 300";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new RuntimeException('Không thể chuẩn bị câu lệnh tìm kiếm.');
        }
        $stmt->bind_param('ssss', $like, $like, $like, $like);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    } else {
        $sql =
                "SELECT tk.tendangnhap, tk.manhomquyen, nq.tennhomquyen, tk.manv, {$statusSelect},
                    CONCAT(COALESCE(nv.honv,''),' ',COALESCE(nv.tennv,'')) AS ten_nhanvien
             FROM taikhoan tk
             LEFT JOIN nhomquyen nq ON nq.manhomquyen = tk.manhomquyen
             LEFT JOIN nhanvien nv ON nv.manv = tk.manv
             ORDER BY tk.tendangnhap ASC
             LIMIT 300";

        $res = $conn->query($sql);
        $rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }
} catch (Throwable $e) {
    $err = $err !== '' ? $err : $e->getMessage();
}

$showForm = ($editingRow !== null) || ($err !== '');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý tài khoản</title>

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
        <div class="d-flex flex-wrap gap-2 align-items-end justify-content-between mb-3">
            <div>
                <h2 class="fw-bold mb-1">Tài khoản</h2>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#tkFormCollapse" aria-expanded="<?= $showForm ? 'true' : 'false' ?>" aria-controls="tkFormCollapse">
                    Thêm mới
                </button>
            </div>
        </div>

        <?php if ($msg !== ''): ?>
            <div class="alert alert-success"><?= h($msg) ?></div>
        <?php endif; ?>
        <?php if ($err !== ''): ?>
            <div class="alert alert-danger"><?= h($err) ?></div>
        <?php endif; ?>

        <div class="collapse <?= $showForm ? 'show' : '' ?>" id="tkFormCollapse">
            <div class="card shadow-sm mb-3">
                <div class="card-header fw-semibold"><?= $editingRow ? 'Cập nhật tài khoản' : 'Thêm tài khoản' ?></div>
                <div class="card-body">
                    <form class="row g-2" method="post">
                        <input type="hidden" name="mode" value="<?= $editingRow ? 'update' : 'add' ?>">

                        <div class="col-12 col-md-3">
                            <label class="form-label mb-1">Tên đăng nhập</label>
                            <input class="form-control" name="tendangnhap" value="<?= h((string)($editingRow['tendangnhap'] ?? '')) ?>" <?= $editingRow ? 'readonly' : '' ?> placeholder="username">
                        </div>

                        <div class="col-12 col-md-3">
                            <label class="form-label mb-1"><?= $editingRow ? 'Mật khẩu mới (bỏ trống nếu giữ nguyên)' : 'Mật khẩu' ?></label>
                            <input class="form-control" name="matkhau" type="password" placeholder="••••••••">
                        </div>

                        <div class="col-12 col-md-3">
                            <label class="form-label mb-1">Nhóm quyền</label>
                            <select class="form-select" name="manhomquyen">
                                <option value="">-- Chọn nhóm quyền --</option>
                                <?php foreach ($roles as $r): ?>
                                    <?php
                                    $val = (string)($r['manhomquyen'] ?? '');
                                    $label = (string)($r['tennhomquyen'] ?? $val);
                                    $selected = $editingRow && (string)($editingRow['manhomquyen'] ?? '') === $val;
                                    ?>
                                    <option value="<?= h($val) ?>" <?= $selected ? 'selected' : '' ?>><?= h($label) ?> (<?= h($val) ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-12 col-md-3">
                            <label class="form-label mb-1">Nhân viên (tùy chọn)</label>
                            <?php if ($employees): ?>
                                <select class="form-select" name="manv">
                                    <?php
                                    $selectedManv = '';
                                    if ($editingRow) {
                                        $selectedManv = (string)($editingRow['manv'] ?? '');
                                    } else {
                                        $selectedManv = $suggestedManv;
                                    }
                                    ?>
                                    <option value="" <?= $selectedManv === '' ? 'selected' : '' ?>>-- Không gắn nhân viên --</option>
                                    <?php foreach ($employees as $e): ?>
                                        <?php
                                        $manvOption = (string)($e['manv'] ?? '');
                                        $hotenOption = (string)($e['hoten'] ?? '');
                                        $selected = $manvOption !== '' && $selectedManv === $manvOption;
                                        ?>
                                        <option value="<?= h($manvOption) ?>" <?= $selected ? 'selected' : '' ?>><?= h($manvOption) ?><?= $hotenOption !== '' ? (' - ' . h($hotenOption)) : '' ?></option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else: ?>
                                <input class="form-control" name="manv" value="<?= h((string)($editingRow['manv'] ?? '')) ?>" placeholder="VD: NV001">
                            <?php endif; ?>
                            <?php if ($editingRow && (string)($editingRow['ten_nhanvien'] ?? '') !== ''): ?>
                                <div class="text-muted small">Nhân viên: <?= h((string)$editingRow['ten_nhanvien']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-12 d-flex gap-2">
                            <button class="btn btn-primary" type="submit"><?= $editingRow ? 'Lưu cập nhật' : 'Thêm mới' ?></button>
                            <?php if ($editingRow): ?>
                                <a class="btn btn-outline-secondary" href="/admin/QuanLy/TaiKhoan/QL_TaiKhoan.php">Hủy sửa</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header">
                <form class="row g-2 align-items-center" method="get">
                    <div class="col-12">
                        <div class="input-group">
                            <input class="form-control" name="q" value="<?= h($q) ?>" placeholder="Tìm theo username / mã NV / nhóm quyền / tên NV">
                            <button class="btn btn-outline-primary" type="submit" aria-label="Tìm">
                                <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead>
                        <tr>
                            <th>Username</th>
                            <th>Nhóm quyền</th>
                            <th>Trạng thái</th>
                            <th>Mã NV</th>
                            <th>Tên nhân viên</th>
                            <th class="text-end">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (!$rows): ?>
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">Không có dữ liệu</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($rows as $r): ?>
                                <?php
                                $username = (string)($r['tendangnhap'] ?? '');
                                $isActive = (int)($r['trangthai'] ?? 1) === 1;
                                $editUrl = '/admin/QuanLy/TaiKhoan/QL_TaiKhoan.php?edit=' . rawurlencode($username) . ($q !== '' ? ('&q=' . rawurlencode($q)) : '');
                                ?>
                                <tr>
                                    <td class="fw-semibold"><?= h($username) ?><?= ($currentUsername !== '' && $username === $currentUsername) ? ' <span class="badge text-bg-primary">Bạn</span>' : '' ?></td>
                                    <td>
                                        <div><?= h((string)($r['tennhomquyen'] ?? '')) ?></div>
                                        <div class="text-muted small"><?= h((string)($r['manhomquyen'] ?? '')) ?></div>
                                    </td>
                                    <td>
                                        <?php if (!$hasTrangthaiCol): ?>
                                            <span class="badge text-bg-light">Chưa cấu hình</span>
                                        <?php elseif ($isActive): ?>
                                            <span class="badge text-bg-success">Đang hoạt động</span>
                                        <?php else: ?>
                                            <span class="badge text-bg-secondary">Đã khóa</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= h((string)($r['manv'] ?? '')) ?></td>
                                    <td><?= h((string)($r['ten_nhanvien'] ?? '')) ?></td>
                                    <td class="text-end">
                                        <a class="btn btn-sm btn-outline-primary" href="<?= h($editUrl) ?>">Sửa</a>
                                        <?php if ($hasTrangthaiCol): ?>
                                            <form method="post" class="d-inline" onsubmit="return confirm('<?= $isActive ? 'Khóa' : 'Mở khóa' ?> tài khoản <?= h($username) ?>?')">
                                                <input type="hidden" name="mode" value="toggle_status">
                                                <input type="hidden" name="tendangnhap" value="<?= h($username) ?>">
                                                <input type="hidden" name="next_status" value="<?= $isActive ? '0' : '1' ?>">
                                                <button class="btn btn-sm <?= $isActive ? 'btn-outline-warning' : 'btn-outline-success' ?>" type="submit" <?= ($username === 'admin' || ($currentUsername !== '' && $username === $currentUsername)) ? 'disabled' : '' ?>>
                                                    <?= $isActive ? 'Khóa' : 'Mở' ?>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                        <form method="post" class="d-inline" onsubmit="return confirm('Xóa tài khoản <?= h($username) ?>?')">
                                            <input type="hidden" name="mode" value="delete">
                                            <input type="hidden" name="tendangnhap" value="<?= h($username) ?>">
                                            <button class="btn btn-sm btn-outline-danger" type="submit" <?= ($currentUsername !== '' && $username === $currentUsername) ? 'disabled' : '' ?>>Xóa</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="/assets/bootstrap/js/bootstrap.bundle.min.js" defer></script>
</body>
</html>
