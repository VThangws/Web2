<?php
require_once __DIR__ . "/../model/DocGia.php";
require_once __DIR__ . "/../model/TaiKhoan.php";
require_once __DIR__ . "/../DAO/DocGiaDAO.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

header("Content-Type: application/json");

if (!isset($_SESSION['taikhoan'])) {
    echo json_encode([
        "success" => false,
        "message" => "Chưa đăng nhập"
    ]);
    exit();
}

$taikhoan = $_SESSION['taikhoan'];
$dao = new DocGiaDAO();

$currentPassword = $_POST['current_password'] ?? '';
$newPassword = $_POST['new_password'] ?? '';

if (empty($currentPassword) || empty($newPassword)) {
    echo json_encode([
        "success" => false,
        "message" => "Thiếu thông tin"
    ]);
    exit();
}

if (!password_verify($currentPassword, $taikhoan->getMatkhau())) {
    echo json_encode([
        "success" => false,
        "message" => "Mật khẩu hiện tại không đúng"
    ]);
    exit();
}

$result = $dao->doiMatKhau($taikhoan, $newPassword);

echo json_encode($result);
