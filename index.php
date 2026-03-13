<?php
session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống quản lý thư viện</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="icon" type="image/png" href="./assets/img/logo-library/library.png">
    <!-- Material Symbols dùng chung cho nhiều icon -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,300,0,200" />

    <!-- CSS chung -->
    <link rel="stylesheet" href="./assets/css/header.css">
    <link rel="stylesheet" href="./assets/css/slide.css">
    <link rel="stylesheet" href="./assets/css/footer.css">
    <link rel="stylesheet" href="./assets/fonts/font.css">
    <link rel="stylesheet" href="./assets/css/books.css">
</head>
<body>
<?php
// Header luôn xuất hiện trên mọi trang
include __DIR__ . '/layout/header.php';

// Điều hướng trang dựa trên tham số 'page'
if (isset($_GET['page'])) {
    $page = $_GET['page'];

    switch ($page) {
        case 'books':
            include __DIR__ . '/layout/books.php';
            break;
        // ...các trang khác nếu có...
        default:
            include __DIR__ . '/layout/home.php';
            break;
    }
} else {
    include __DIR__ . '/layout/home.php';
}

// Footer cố định cuối trang
include __DIR__ . '/layout/footer.php';
?>

<script src="/assets/bootstrap/js/bootstrap.bundle.min.js" defer></script>

<?php if (!empty($_SESSION['user_id'])): ?>
<script>
window.addEventListener('load', () => {
  if (typeof syncCartAfterLogin === 'function' && !sessionStorage.getItem('cart_merge_prompted')) {
    syncCartAfterLogin();
  }
});
</script>
<?php endif; ?>
</body>
</html>