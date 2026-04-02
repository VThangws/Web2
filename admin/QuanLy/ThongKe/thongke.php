<?php
require_once __DIR__ . '/../../login/auth.php';
require_admin_login();
// Tạm thời dùng cùng quyền với Dashboard để không làm gián đoạn RBAC hiện tại.
require_admin_permission('DASHBOARD');
?>

<!DOCTYPE html>
<html lang="vi">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống kê - Hệ thống quản lý thư viện</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="icon" type="image/png" href="/assets/img/logo-library/library.png">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,300,0,200" />

    <link rel="stylesheet" href="/assets/css/header.css">
    <link rel="stylesheet" href="/assets/css/slide.css">
    <link rel="stylesheet" href="/assets/fonts/font.css">
    <link rel="stylesheet" href="/assets/css/books.css">
</head>
<body>
<?php
require_once __DIR__ . '/../../layout/admin_sidebar.php';
require_once __DIR__ . '/../../layout/admin_statistics.php';
?>

<script src="/assets/bootstrap/js/bootstrap.bundle.min.js" defer></script>
</body>
</html>
