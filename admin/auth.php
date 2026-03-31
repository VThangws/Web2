<?php
declare(strict_types=1);

require_once __DIR__ . '/../database/ConnectDB.php';

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
    header('Location: /admin/login.php?next=' . urlencode($next));
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

/**
 * Permission model:
 * - If table ctquyen has no rows: allow all (no permissions configured yet)
 * - If user has no manhomquyen: allow all (treat as super-admin)
 * - Else: allow only if ctquyen contains (manhomquyen, machucnang) and optional hanhdong match.
 */
function admin_has_permission(string $machucnang, ?string $hanhdong = null): bool
{
    if (!admin_is_logged_in()) {
        return false;
    }

    $machucnang = trim($machucnang);
    if ($machucnang === '') {
        return true;
    }

    $role = admin_current_role();
    if ($role === null) {
        return true;
    }

    try {
        $conn = ConnectDB::getInstance()->getConnection();
        $conn->set_charset('utf8mb4');

        $res = $conn->query('SELECT 1 FROM ctquyen LIMIT 1');
        if (!$res || $res->num_rows === 0) {
            return true;
        }

        if ($hanhdong !== null) {
            $hanhdong = trim($hanhdong);
            $stmt = $conn->prepare(
                "SELECT 1 FROM ctquyen
                 WHERE manhomquyen = ?
                   AND machucnang = ?
                   AND (hanhdong IS NULL OR hanhdong = '' OR hanhdong = ? OR UPPER(hanhdong) = 'ALL')
                 LIMIT 1"
            );
            if ($stmt === false) {
                return true; // fail-open for school project stability
            }
            $stmt->bind_param('sss', $role, $machucnang, $hanhdong);
        } else {
            $stmt = $conn->prepare(
                "SELECT 1 FROM ctquyen
                 WHERE manhomquyen = ?
                   AND machucnang = ?
                 LIMIT 1"
            );
            if ($stmt === false) {
                return true;
            }
            $stmt->bind_param('ss', $role, $machucnang);
        }

        $stmt->execute();
        $ok = $stmt->get_result()->num_rows > 0;
        $stmt->close();
        return $ok;
    } catch (Throwable $e) {
        return true; // fail-open
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
