<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/database/DBConnection.php';
// Mark app as bootstrapped to prevent direct access to partials
if (!defined('APP_BOOTSTRAPPED')) {
    define('APP_BOOTSTRAPPED', true);
}
// Environment-based settings
$tz = getenv('APP_TIMEZONE') ?: 'Asia/Ho_Chi_Minh';
@date_default_timezone_set($tz);
$env = getenv('APP_ENV') ?: 'production';
if ($env === 'development') {
    @ini_set('display_errors', '1');
    @error_reporting(E_ALL);
}
if (!headers_sent()) {
    header('Content-Type: text/html; charset=UTF-8');
}

// Global exception handler
set_exception_handler(function ($e) use ($env) {
    http_response_code(500);
    if ($env === 'development') {
        echo '<pre style="padding:16px">' . htmlspecialchars((string)$e) . '</pre>';
    } else {
        include __DIR__ . '/layout/error500.php';
    }
    exit;
});

// Cart actions
$action = isset($_GET['action']) ? (string)$_GET['action'] : null;
if ($action) {
    $_SESSION['cart'] = $_SESSION['cart'] ?? [];
    $redirect = isset($_GET['redirect']) ? (string)$_GET['redirect'] : null;
    // Only allow relative redirects
    if ($redirect && !preg_match('~^/|^index\\.php~i', $redirect)) {
        $redirect = null;
    }

    switch ($action) {
        case 'add_to_cart':
            $id = isset($_GET['id']) ? (string)$_GET['id'] : '';
            if ($id !== '') {
                $qty = isset($_GET['qty']) ? max(1, (int)$_GET['qty']) : 1;
                $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + $qty;
            }
            break;
        case 'update_cart':
            $id = isset($_GET['id']) ? (string)$_GET['id'] : '';
            if ($id !== '') {
                $qty = isset($_GET['qty']) ? (int)$_GET['qty'] : 0;
                if ($qty <= 0) {
                    unset($_SESSION['cart'][$id]);
                } else {
                    $_SESSION['cart'][$id] = $qty;
                }
            }
            break;
        case 'remove_from_cart':
            $id = isset($_GET['id']) ? (string)$_GET['id'] : '';
            if ($id !== '' && isset($_SESSION['cart'][$id])) {
                unset($_SESSION['cart'][$id]);
            }
            break;
        case 'clear_cart':
            $_SESSION['cart'] = [];
            break;
    }

    header('Location: ' . ($redirect ?: 'index.php?page=giohang'));
    exit;
}

echo '<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/site.css" rel="stylesheet">
    <title>Book Library</title>
    <link rel="icon" type="image/png" href="assets/img/logo_favicon/favicon.png">
</head>
<body>';

include("./layout/header.php");

echo '<main class="min-vh-100">';
if (isset($_GET['page'])) {
    $page = $_GET['page'];
    if (is_numeric($page)) {
        include('./layout/product.php');
    } else {
        switch ($page) {
            case 'ao':
            case 'quan':
            case 'aopolo':
            case 'aosomi':
            case 'aokhoac':
                include('./layout/phanloai.php');
                break;
            case 'sanpham':
                if (isset($_GET['phanloai'])) {
                    include('./layout/phanloai.php');
                } else {
                    include('./layout/product.php');
                }
                break;
            case 'giohang':
                include('./layout/cart.php');
                break;
            case 'pay':
                include('./layout/pay.php');
                break;
            case 'taikhoan':
            case 'danhsachdiachi':
            case 'donhang':
                include('./layout/info_user.php');
                break;
            case 'search':
                include('./layout/search.php');
                break;
            case 'error':
                include('./layout/error404.php');
                break;
            default:
                include('./layout/error404.php');
                break;
        }
    }
} else {
    include('./layout/home.php');
}

echo '</main>';

include("./layout/footer.php");
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script>
<script src="assets/js/site.js" defer></script>

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
