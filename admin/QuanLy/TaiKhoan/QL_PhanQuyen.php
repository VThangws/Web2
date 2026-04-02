<?php
require_once __DIR__ . '/../../login/auth.php';
require_admin_login();
require_admin_permission('TAIKHOAN');

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

$msg = get_str($_GET, 'msg');
$err = get_str($_GET, 'err');

$roles = [];
try {
    $res = $conn->query("SELECT manhomquyen, tennhomquyen FROM nhomquyen WHERE manhomquyen <> 'DG' ORDER BY tennhomquyen ASC");
    $roles = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
} catch (Throwable $e) {
    $roles = [];
}

$selectedRole = get_str($_GET, 'role');
if ($selectedRole === '' && count($roles) > 0) {
    $selectedRole = (string)($roles[0]['manhomquyen'] ?? '');
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý phân quyền</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="icon" type="image/png" href="/assets/img/logo-library/library.png">

    <link rel="stylesheet" href="/assets/css/admin_sidebar.css">

    <style>
        .readonly-permission input[type="checkbox"] {
            pointer-events: none;
            opacity: 0.7;
        }
        .permission-table th,
        .permission-table td {
            vertical-align: middle;
        }
    </style>
</head>
<body>
<?php
require_once __DIR__ . '/../../layout/admin_sidebar.php';
?>

<main class="container-fluid py-4">
    <div class="container-md">
        <div class="d-flex flex-wrap gap-2 align-items-end justify-content-between mb-3">
            <div>
                <h2 class="fw-bold mb-1">Phân quyền</h2>
            </div>
        </div>

        <?php if ($msg !== ''): ?>
            <div class="alert alert-success"><?php echo h($msg); ?></div>
        <?php endif; ?>
        <?php if ($err !== ''): ?>
            <div class="alert alert-danger"><?php echo h($err); ?></div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-body">
                <form id="formPermission" class="readonly-permission" onsubmit="return handlePermissionSubmit(event)">
                    <div class="row g-2 align-items-end mb-3">
                        <div class="col-12 col-md-4">
                            <label class="form-label mb-1" for="roleSelect">Chọn nhóm quyền</label>
                            <select id="roleSelect" class="form-select">
                                <?php foreach ($roles as $r): ?>
                                    <?php
                                    $val = (string)($r['manhomquyen'] ?? '');
                                    $label = (string)($r['tennhomquyen'] ?? $val);
                                    $sel = $val !== '' && $val === $selectedRole;
                                    ?>
                                    <option value="<?php echo h($val); ?>" <?php echo $sel ? 'selected' : ''; ?>><?php echo h($label); ?> (<?php echo h($val); ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-12 col-md-auto ms-md-auto" id="actionButtons">
                            <button type="button" id="btnEdit" class="btn btn-warning">
                                <i class="fa-solid fa-pen-to-square me-1"></i>Thay đổi
                            </button>
                            <button type="submit" id="btnSave" class="btn btn-primary d-none">
                                <i class="fa-solid fa-floppy-disk me-1"></i>Lưu phân quyền
                            </button>
                            <button type="button" id="btnCancel" class="btn btn-secondary d-none">Hủy</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered text-center align-middle permission-table mb-0">
                            <thead class="table-secondary">
                                <tr>
                                    <th class="text-start">Chức năng</th>
                                    <th>Đọc</th>
                                    <th>Sửa</th>
                                    <th>Xóa</th>
                                </tr>
                            </thead>
                            <tbody id="permissionTable">
                                <tr>
                                    <td colspan="4" class="text-muted">Đang tải dữ liệu...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </form>
            </div>
        </div>
    </div>
</main>

<script>
    const formPermission = document.getElementById('formPermission');
    const roleSelect = document.getElementById('roleSelect');
    const permissionTable = document.getElementById('permissionTable');

    const btnEdit = document.getElementById('btnEdit');
    const btnSave = document.getElementById('btnSave');
    const btnCancel = document.getElementById('btnCancel');

    function disableAllCheckboxes() {
        formPermission.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.disabled = true);
        formPermission.classList.add('readonly-permission');
    }

    function enableAllCheckboxes() {
        formPermission.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.disabled = false);
        formPermission.classList.remove('readonly-permission');
    }

    function loadPermissions() {
        const roleId = roleSelect.value;
        fetch('ajax/load_permissions.php?role=' + encodeURIComponent(roleId))
            .then(res => res.json())
            .then(data => {
                if (!data || !data.success) {
                    permissionTable.innerHTML = '<tr><td colspan="4" class="text-danger">Không tải được phân quyền.</td></tr>';
                    disableAllCheckboxes();
                    return;
                }
                permissionTable.innerHTML = data.html || '';
                disableAllCheckboxes();

                // reset UI state
                btnEdit.classList.remove('d-none');
                btnSave.classList.add('d-none');
                btnCancel.classList.add('d-none');
            })
            .catch(() => {
                permissionTable.innerHTML = '<tr><td colspan="4" class="text-danger">Không tải được phân quyền.</td></tr>';
                disableAllCheckboxes();
            });
    }

    roleSelect.addEventListener('change', loadPermissions);

    btnEdit.addEventListener('click', () => {
        enableAllCheckboxes();
        btnEdit.classList.add('d-none');
        btnSave.classList.remove('d-none');
        btnCancel.classList.remove('d-none');
    });

    btnCancel.addEventListener('click', () => {
        loadPermissions();
    });

    function handlePermissionSubmit(e) {
        e.preventDefault();

        const roleId = roleSelect.value;
        const formData = new FormData();
        formData.append('role', roleId);

        formPermission.querySelectorAll('input[type="checkbox"]:checked').forEach(cb => {
            const func = cb.dataset.func;
            const action = cb.dataset.action;
            if (!func || !action) return;
            formData.append('permissions[' + func + '][]', action);
        });

        fetch('ajax/save_permissions.php', {
            method: 'POST',
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                alert((data && data.message) ? data.message : 'Đã lưu phân quyền.');
                loadPermissions();
            })
            .catch(() => {
                alert('Lỗi khi lưu phân quyền.');
            });

        return false;
    }

    loadPermissions();
</script>

<script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
