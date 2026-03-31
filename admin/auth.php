<?php
declare(strict_types=1);

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
