<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);
require_once __DIR__ . '/../DAO/DocGiaDAO.php';
require_once __DIR__ . '/../model/DocGia.php';

if (session_status() === PHP_SESSION_NONE) session_start();
header('Content-Type: application/json');

// Bắt tất cả lỗi PHP và trả về JSON
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    echo json_encode([
        'success' => false,
        'message' => "Lỗi PHP: $errstr tại $errfile dòng $errline"
    ]);
    exit;
});

// ✅ Lấy từ session
$user = $_SESSION['docgia'] ?? null;

if (!$user) {
    echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập!']);
    exit;
}

// ✅ Chỉ cho phép sửa các field không phải mã và email
$user->setHodocgia(trim($_POST['hodocgia'] ?? $user->getHodocgia()));
$user->setTendocgia(trim($_POST['tendocgia'] ?? $user->getTendocgia()));
$user->setSdt(trim($_POST['sdt'] ?? $user->getSdt()));
$user->setNgaysinh($_POST['ngaysinh'] ?? $user->getNgaysinh());
$user->setDiachi(trim($_POST['diachi'] ?? $user->getDiachi()));

$dao    = new DocGiaDAO();
$result = $dao->capNhatThongTin($user);

if ($result['success']) {
    $_SESSION['docgia'] = $user; // ✅ Cập nhật lại session
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => $result['message']]);
}

if (!headers_sent()) {
    echo json_encode(['success' => false, 'message' => 'Lỗi không xác định']);
}