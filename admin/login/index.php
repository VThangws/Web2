<?php
declare(strict_types=1);

require_once __DIR__ . '/../../database/ConnectDB.php';

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

		$hasMadocgiaCol = false;
		$hasTrangthaiCol = false;
		try {
			$check = $conn->prepare(
				"SELECT 1
				 FROM INFORMATION_SCHEMA.COLUMNS
				 WHERE TABLE_SCHEMA = DATABASE()
				   AND TABLE_NAME = 'taikhoan'
				   AND COLUMN_NAME = 'madocgia'
				 LIMIT 1"
			);
			if ($check) {
				$check->execute();
				$hasMadocgiaCol = ($check->get_result()->num_rows > 0);
				$check->close();
			}
		} catch (Throwable $e) {
			$hasMadocgiaCol = false;
		}

		try {
			$check = $conn->prepare(
				"SELECT 1
				 FROM INFORMATION_SCHEMA.COLUMNS
				 WHERE TABLE_SCHEMA = DATABASE()
				   AND TABLE_NAME = 'taikhoan'
				   AND COLUMN_NAME = 'trangthai'
				 LIMIT 1"
			);
			if ($check) {
				$check->execute();
				$hasTrangthaiCol = ($check->get_result()->num_rows > 0);
				$check->close();
			}
		} catch (Throwable $e) {
			$hasTrangthaiCol = false;
		}

		if ($hasMadocgiaCol && $hasTrangthaiCol) {
			$sql = 'SELECT tendangnhap, matkhau, manhomquyen, manv, madocgia, trangthai FROM taikhoan WHERE tendangnhap = ? LIMIT 1';
		} elseif ($hasMadocgiaCol) {
			$sql = 'SELECT tendangnhap, matkhau, manhomquyen, manv, madocgia FROM taikhoan WHERE tendangnhap = ? LIMIT 1';
		} elseif ($hasTrangthaiCol) {
			$sql = 'SELECT tendangnhap, matkhau, manhomquyen, manv, trangthai FROM taikhoan WHERE tendangnhap = ? LIMIT 1';
		} else {
			$sql = 'SELECT tendangnhap, matkhau, manhomquyen, manv FROM taikhoan WHERE tendangnhap = ? LIMIT 1';
		}
		$stmt = $conn->prepare($sql);
		if ($stmt === false) {
			$error = 'Không thể kết nối đăng nhập (prepare failed).';
		} else {
			$stmt->bind_param('s', $tendangnhap);
			$stmt->execute();
			$result = $stmt->get_result();
			$row = $result ? $result->fetch_assoc() : null;

			$isLocked = $hasTrangthaiCol && is_array($row) && (int)($row['trangthai'] ?? 1) === 0;
			$dbPassword = $row['matkhau'] ?? null;
			$ok = false;

			if ($isLocked) {
				$error = 'Tài khoản đã bị khóa.';
			} elseif (is_string($dbPassword) && $dbPassword !== '') {
				if (password_verify($matkhau, $dbPassword)) {
					$ok = true;
				} elseif (hash_equals($dbPassword, $matkhau)) {
					$ok = true;
				}
			}

			if ($ok && is_array($row)) {
				$role = isset($row['manhomquyen']) && is_string($row['manhomquyen']) ? trim($row['manhomquyen']) : '';
				if ($hasMadocgiaCol && strtoupper($role) === 'DG') {
					$error = 'Tài khoản này không có quyền quản trị.';
				} else {
					session_regenerate_id(true);
					$_SESSION['admin_user'] = [
						'tendangnhap' => $row['tendangnhap'],
						'manhomquyen' => $row['manhomquyen'],
						'manv' => $row['manv'],
						'madocgia' => $hasMadocgiaCol ? ($row['madocgia'] ?? null) : null,
					];

					if ($next === '' || str_starts_with($next, 'http://') || str_starts_with($next, 'https://')) {
						$next = '/admin/adminMenu.php';
					}
					header('Location: ' . $next);
					exit;
				}
			} elseif ($error === '') {
				$error = 'Tên đăng nhập hoặc mật khẩu không đúng.';
			}
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
	<link rel="stylesheet" href="/assets/fonts/font.css">
	<link rel="icon" type="image/png" href="/assets/img/logo-library/library.png">
</head>
<body class="bg-light">
	<div class="container py-5">
		<div class="row justify-content-center">
			<div class="col-12 col-md-7 col-lg-5">
				<div class="card shadow-sm">
					<div class="card-body p-4">
						<h1 class="h4 mb-3 text-center">Đăng nhập</h1>

						<?php if ($error !== ''): ?>
							<div class="alert alert-danger" role="alert">
								<?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
							</div>
						<?php endif; ?>

						<form method="post" action="/admin/login" autocomplete="off">
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
