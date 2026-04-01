<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Xóa toàn bộ session
session_unset();
session_destroy();

// Xóa cookie session nếu có
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Redirect về trang chủ
header("Location: /index.php");
exit();
?>