<?php
require_once __DIR__ . '/../DAO/DocGiaDAO.php';
require_once __DIR__ . '/../model/DocGia.php';

if (session_status() === PHP_SESSION_NONE) session_start();

header('Content-Type: application/json');

$dao = new DocGiaDAO();

// Kiểm tra dữ liệu thô từ form trước khi tạo object
$hodocgia  = trim($_POST['hodocgia'] ?? '');
$tendocgia = trim($_POST['tendocgia'] ?? '');
$email     = trim($_POST['email'] ?? '');
$matkhau   = $_POST['matkhau'] ?? '';

if (empty($hodocgia) || empty($tendocgia) || empty($email) || empty($matkhau)) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng nhập đầy đủ thông tin!']);
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Email không hợp lệ!']);
    exit;
}

$dg = new DocGia(
    $hodocgia,
    $tendocgia,
    $email,
    null,
    null,
    null
);
$matkhau = $_POST['matkhau'];

$result = $dao->dangKy($dg, $matkhau);
echo json_encode($result);