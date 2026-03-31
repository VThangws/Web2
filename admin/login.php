<?php
declare(strict_types=1);

require_once __DIR__ . '/../database/ConnectDB.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['admin_user'])) {
    header('Location: /admin/adminMenu.php');
    exit;
}

$error = '';
$next = $_GET['next'] ?? '/admin/adminMenu.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tendangnhap = trim((string)($_POST['tendangnhap'] ?? ''));
    $matkhau = (string)($_POST['matkhau'] ?? '');
    $next = (string)($_POST['next'] ?? $next);

    if ($tendangnhap === '' || $matkhau === '') {
        $error = 'Vui lòng nhập tên đăng nhập và mật khẩu.';
    } else {
        $conn = ConnectDB::getInstance()->getConnection();

        $sql = 'SELECT tendangnhap, matkhau, manhomquyen, manv FROM taikhoan WHERE tendangnhap = ? LIMIT 1';
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            $error = 'Không thể kết nối đăng nhập (prepare failed).';
        } else {
            $stmt->bind_param('s', $tendangnhap);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result ? $result->fetch_assoc() : null;

            $dbPassword = $row['matkhau'] ?? null;
            $ok = false;

            // Hỗ trợ cả mật khẩu hash (password_hash) và dạng plain text (demo/đồ án).
            if (is_string($dbPassword) && $dbPassword !== '') {
                if (password_verify($matkhau, $dbPassword)) {
                    $ok = true;
                } elseif (hash_equals($dbPassword, $matkhau)) {
                    $ok = true;
                }
            }

            if ($ok && is_array($row)) {
                session_regenerate_id(true);
                $_SESSION['admin_user'] = [
                    'tendangnhap' => $row['tendangnhap'],
                    'manhomquyen' => $row['manhomquyen'],
                    'manv' => $row['manv'],
                ];

                // Chặn redirect ra ngoài domain
                if ($next === '' || str_starts_with($next, 'http://') || str_starts_with($next, 'https://')) {
                    $next = '/admin/adminMenu.php';
                }
                header('Location: ' . $next);
                exit;
            }

            $error = 'Tên đăng nhập hoặc mật khẩu không đúng.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Hệ thống quản lý thư viện</title>

    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="icon" type="image/png" href="/assets/img/logo-library/library.png">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-7 col-lg-5">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h1 class="h4 mb-3 text-center">Đăng nhập Admin</h1>

                        <?php if ($error !== ''): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="/admin/login.php" autocomplete="off">
                            <input type="hidden" name="next" value="<?= htmlspecialchars((string)$next, ENT_QUOTES, 'UTF-8') ?>">

                            <div class="mb-3">
                                <label class="form-label" for="tendangnhap">Tên đăng nhập</label>
                                <input class="form-control" id="tendangnhap" name="tendangnhap" type="text" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="matkhau">Mật khẩu</label>
                                <input class="form-control" id="matkhau" name="matkhau" type="password" required>
                            </div>

                            <button class="btn btn-primary w-100" type="submit">Đăng nhập</button>
                        </form>

                        <div class="text-center mt-3">
                            <a class="link-secondary" href="/index.php">Quay về trang chủ</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/assets/bootstrap/js/bootstrap.bundle.min.js" defer></script>
</body>
</html>
