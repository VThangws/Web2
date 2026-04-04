<?php
declare(strict_types=1);

require_once __DIR__ . '/../../database/ConnectDB.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function admin_is_logged_in(): bool
{
    return isset($_SESSION['admin_user']) && is_array($_SESSION['admin_user']);
}

function require_admin_login(): void
{
    if (admin_is_logged_in()) {
        return;
    }

    $next = $_SERVER['REQUEST_URI'] ?? '/admin/adminMenu.php';
    header('Location: /admin/login?next=' . urlencode($next));
    exit;
}

function admin_current_user(): array
{
    if (!admin_is_logged_in()) {
        return [];
    }
    return is_array($_SESSION['admin_user']) ? $_SESSION['admin_user'] : [];
}

function admin_current_role(): ?string
{
    $u = admin_current_user();
    $role = $u['manhomquyen'] ?? null;
    if (!is_string($role)) {
        return null;
    }
    $role = trim($role);
    return $role === '' ? null : $role;
}

function admin_is_super_admin(): bool
{
    $u = admin_current_user();
    return isset($u['tendangnhap']) && is_string($u['tendangnhap']) && $u['tendangnhap'] === 'admin';
}

function admin_has_permission(string $machucnang, ?string $hanhdong = null): bool
{
    if (!admin_is_logged_in()) {
        return false;
    }

    $machucnang = trim($machucnang);
    if ($machucnang === '') {
        return true;
    }

    if (admin_is_super_admin()) {
        return true;
    }

    $role = admin_current_role();

    try {
        $conn = ConnectDB::getInstance()->getConnection();
        $conn->set_charset('utf8mb4');

        $res = $conn->query('SELECT 1 FROM ctquyen LIMIT 1');
        if (!$res || $res->num_rows === 0) {
            return true;
        }

        if ($role === null) {
            return false;
        }

        if ($hanhdong !== null) {
            $hanhdong = trim($hanhdong);
            $upper = strtoupper($hanhdong);

            // READ should be satisfied by READ/WRITE/DELETE/ALL.
            if ($upper === 'READ') {
                $stmt = $conn->prepare(
                    "SELECT 1 FROM ctquyen
                     WHERE manhomquyen = ?
                       AND machucnang = ?
                       AND (
                             hanhdong IS NULL
                             OR hanhdong = ''
                             OR UPPER(hanhdong) = 'ALL'
                             OR UPPER(hanhdong) IN ('READ','WRITE','DELETE')
                           )
                     LIMIT 1"
                );
                if ($stmt === false) {
                    return false;
                }
                $stmt->bind_param('ss', $role, $machucnang);
            } else {
                $stmt = $conn->prepare(
                    "SELECT 1 FROM ctquyen
                     WHERE manhomquyen = ?
                       AND machucnang = ?
                       AND (hanhdong IS NULL OR hanhdong = '' OR hanhdong = ? OR UPPER(hanhdong) = 'ALL')
                     LIMIT 1"
                );
                if ($stmt === false) {
                    return false;
                }
                $stmt->bind_param('sss', $role, $machucnang, $hanhdong);
            }
        } else {
            $stmt = $conn->prepare(
                "SELECT 1 FROM ctquyen
                 WHERE manhomquyen = ?
                   AND machucnang = ?
                 LIMIT 1"
            );
            if ($stmt === false) {
                return false;
            }
            $stmt->bind_param('ss', $role, $machucnang);
        }

        $stmt->execute();
        $ok = $stmt->get_result()->num_rows > 0;
        $stmt->close();
        return $ok;
    } catch (Throwable $e) {
        return false;
    }
}

function require_admin_permission(string $machucnang, ?string $hanhdong = null): void
{
    if (admin_has_permission($machucnang, $hanhdong)) {
        return;
    }

    http_response_code(403);
    echo '<!doctype html><html lang="vi"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>403</title>';
    echo '<link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css"></head><body class="bg-light">';
    echo '<div class="container py-5"><div class="alert alert-danger">Bạn không có quyền truy cập chức năng này.</div>';
    echo '<a class="btn btn-outline-secondary" href="/admin/adminMenu.php">Về trang chủ</a></div>';
    echo '</body></html>';
    exit;
}

/**
 * Enforce permissions for CRUD-like admin pages.
 *
 * Conventions used across this project:
 * - ctquyen.hanhdong values: READ | WRITE | DELETE | ALL
 * - Many legacy admin pages perform mutations via:
 *   - GET param: luachon = Them | Sua | Xoa
 *   - POST param: mode = add | update | delete
 */
function require_admin_permission_for_request(string $machucnang): void
{
    // Always require read access to view the page.
    require_admin_permission($machucnang, 'READ');

    $action = null;

    $method = strtoupper((string)($_SERVER['REQUEST_METHOD'] ?? 'GET'));
    if ($method === 'POST') {
        $mode = isset($_POST['mode']) ? strtolower(trim((string)$_POST['mode'])) : '';
        if ($mode === 'delete' || $mode === 'remove') {
            $action = 'DELETE';
        } elseif ($mode === 'update' || $mode === 'add' || $mode === 'create' || $mode === 'save') {
            $action = 'WRITE';
        }
    } else {
        $luachon = isset($_GET['luachon']) ? strtolower(trim((string)$_GET['luachon'])) : '';
        if ($luachon === 'xoa' || $luachon === 'delete' || $luachon === 'remove') {
            $action = 'DELETE';
        } elseif ($luachon === 'sua' || $luachon === 'them' || $luachon === 'update' || $luachon === 'add' || $luachon === 'create') {
            $action = 'WRITE';
        }
    }

    if ($action !== null) {
        require_admin_permission($machucnang, $action);
    }
}
