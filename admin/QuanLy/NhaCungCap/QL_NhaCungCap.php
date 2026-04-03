<?php
require_once __DIR__ . '/../../login/auth.php';
require_admin_login();
require_admin_permission('NHACUNGCAP');

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
    $base = '/admin/QuanLy/NhaCungCap/QL_NhaCungCap.php';
    $q = http_build_query($params);
    header('Location: ' . $base . ($q ? ('?' . $q) : ''));
    exit;
}

$msg = get_str($_GET, 'msg');
$err = get_str($_GET, 'err');

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mode = get_str($_POST, 'mode');

    try {
        if ($mode === 'delete') {
            $mancc = get_str($_POST, 'mancc');
            if ($mancc === '') {
                throw new RuntimeException('Thiếu mã nhà cung cấp.');
            }

            $stmt = $conn->prepare('DELETE FROM nhacungcap WHERE mancc = ?');
            if (!$stmt) {
                throw new RuntimeException('Không thể chuẩn bị câu lệnh xóa.');
            }
            $stmt->bind_param('s', $mancc);
            $stmt->execute();
            $affected = $stmt->affected_rows;
            $stmt->close();

            if ($affected <= 0) {
                throw new RuntimeException('Không tìm thấy nhà cung cấp để xóa.');
            }

            redirect_with(['msg' => 'Đã xóa nhà cung cấp ' . $mancc]);
        }

        $mancc = get_str($_POST, 'mancc');
        $tenncc = get_str($_POST, 'tenncc');
        $diachincc = get_str($_POST, 'diachincc');
        $sdt = get_str($_POST, 'sdt');
        $email = get_str($_POST, 'email');

        if ($mancc === '' || $tenncc === '') {
            throw new RuntimeException('Mã NCC và Tên NCC không được để trống.');
        }
        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('Email không hợp lệ.');
        }

        if ($mode === 'update') {
            $stmt = $conn->prepare('UPDATE nhacungcap SET tenncc = ?, diachincc = ?, sdt = ?, email = ? WHERE mancc = ?');
            if (!$stmt) {
                throw new RuntimeException('Không thể chuẩn bị câu lệnh cập nhật.');
            }
            $stmt->bind_param('sssss', $tenncc, $diachincc, $sdt, $email, $mancc);
            $stmt->execute();
            $stmt->close();
            redirect_with(['msg' => 'Đã cập nhật nhà cung cấp ' . $mancc]);
        }

        // add
        $stmt = $conn->prepare('INSERT INTO nhacungcap (mancc, tenncc, diachincc, sdt, email) VALUES (?, ?, ?, ?, ?)');
        if (!$stmt) {
            throw new RuntimeException('Không thể chuẩn bị câu lệnh thêm mới.');
        }
        $stmt->bind_param('sssss', $mancc, $tenncc, $diachincc, $sdt, $email);
        $stmt->execute();
        $stmt->close();
        redirect_with(['msg' => 'Đã thêm nhà cung cấp ' . $mancc]);
    } catch (Throwable $e) {
        redirect_with(['err' => $e->getMessage()]);
    }
}

$q = get_str($_GET, 'q');
$edit = get_str($_GET, 'edit');
$openForm = get_str($_GET, 'openForm');

$editingRow = null;
if ($edit !== '') {
    $stmt = $conn->prepare('SELECT mancc, tenncc, diachincc, sdt, email FROM nhacungcap WHERE mancc = ?');
    if ($stmt) {
        $stmt->bind_param('s', $edit);
        $stmt->execute();
        $editingRow = $stmt->get_result()->fetch_assoc();
        $stmt->close();
    }
}

$showForm = ($editingRow !== null) || ($err !== '') || ($openForm === '1');

$rows = [];
try {
    if ($q !== '') {
        $like = '%' . $q . '%';
        $stmt = $conn->prepare(
            'SELECT mancc, tenncc, diachincc, sdt, email '
            . 'FROM nhacungcap '
            . 'WHERE mancc LIKE ? OR tenncc LIKE ? '
            . 'ORDER BY tenncc ASC '
            . 'LIMIT 300'
        );
        if (!$stmt) {
            throw new RuntimeException('Không thể chuẩn bị câu lệnh tìm kiếm.');
        }
        $stmt->bind_param('ss', $like, $like);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    } else {
        $result = $conn->query('SELECT mancc, tenncc, diachincc, sdt, email FROM nhacungcap ORDER BY tenncc ASC LIMIT 300');
        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
} catch (Throwable $e) {
    $err = $err !== '' ? $err : $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý nhà cung cấp</title>

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
                <h2 class="fw-bold mb-1">Nhà cung cấp</h2>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#nccFormCollapse" aria-expanded="<?= $showForm ? 'true' : 'false' ?>" aria-controls="nccFormCollapse">
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

        <div class="collapse <?= $showForm ? 'show' : '' ?>" id="nccFormCollapse">
            <div class="card shadow-sm mb-3">
                <div class="card-header fw-semibold"><?= $editingRow ? 'Cập nhật nhà cung cấp' : 'Thêm nhà cung cấp' ?></div>
                <div class="card-body">
                    <form class="row g-2" method="post">
                        <input type="hidden" name="mode" value="<?= $editingRow ? 'update' : 'add' ?>">

                        <div class="col-12 col-md-3">
                            <label class="form-label mb-1">Mã NCC</label>
                            <input class="form-control" name="mancc" value="<?= h((string)($editingRow['mancc'] ?? '')) ?>" <?= $editingRow ? 'readonly' : '' ?> placeholder="VD: NCC_ABC">
                        </div>
                        <div class="col-12 col-md-5">
                            <label class="form-label mb-1">Tên NCC</label>
                            <input class="form-control" name="tenncc" value="<?= h((string)($editingRow['tenncc'] ?? '')) ?>" placeholder="Tên nhà cung cấp">
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label mb-1">Email</label>
                            <input class="form-control" name="email" value="<?= h((string)($editingRow['email'] ?? '')) ?>" placeholder="email@domain.com">
                        </div>
                        <div class="col-12 col-md-8">
                            <label class="form-label mb-1">Địa chỉ</label>
                            <input class="form-control" name="diachincc" value="<?= h((string)($editingRow['diachincc'] ?? '')) ?>" placeholder="Địa chỉ nhà cung cấp">
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label mb-1">SĐT</label>
                            <input class="form-control" name="sdt" value="<?= h((string)($editingRow['sdt'] ?? '')) ?>" placeholder="Số điện thoại">
                        </div>
                        <div class="col-12 d-flex gap-2">
                            <button class="btn btn-primary" type="submit"><?= $editingRow ? 'Lưu cập nhật' : 'Thêm mới' ?></button>
                            <?php if ($editingRow): ?>
                                <a class="btn btn-outline-secondary" href="/admin/QuanLy/NhaCungCap/QL_NhaCungCap.php">Hủy sửa</a>
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
                            <input class="form-control" name="q" value="<?= h($q) ?>" placeholder="Tìm theo mã NCC hoặc tên NCC">
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
                            <th>Mã NCC</th>
                            <th>Tên NCC</th>
                            <th>Địa chỉ</th>
                            <th>SĐT</th>
                            <th>Email</th>
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
                                $mancc = (string)($r['mancc'] ?? '');
                                $editUrl = '/admin/QuanLy/NhaCungCap/QL_NhaCungCap.php?edit=' . rawurlencode($mancc) . ($q !== '' ? ('&q=' . rawurlencode($q)) : '');
                                ?>
                                <tr>
                                    <td class="fw-semibold"><?= h($mancc) ?></td>
                                    <td><?= h((string)($r['tenncc'] ?? '')) ?></td>
                                    <td><?= h((string)($r['diachincc'] ?? '')) ?></td>
                                    <td><?= h((string)($r['sdt'] ?? '')) ?></td>
                                    <td><?= h((string)($r['email'] ?? '')) ?></td>
                                    <td class="text-end">
                                        <a class="btn btn-sm btn-outline-primary" href="<?= h($editUrl) ?>">Sửa</a>
                                        <form method="post" class="d-inline" onsubmit="return confirm('Xóa nhà cung cấp <?= h($mancc) ?>?')">
                                            <input type="hidden" name="mode" value="delete">
                                            <input type="hidden" name="mancc" value="<?= h($mancc) ?>">
                                            <button class="btn btn-sm btn-outline-danger" type="submit">Xóa</button>
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
