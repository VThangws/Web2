<?php
require_once __DIR__ . "/../model/DocGia.php";
require_once __DIR__ . "/../model/TaiKhoan.php";
require_once __DIR__ . "/../DAO/DocGiaDAO.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

header("Content-Type: application/json");

if (!isset($_SESSION['docgia'])) {
    echo json_encode(["success" => false, "message" => "Chưa đăng nhập"]);
    exit();
}

$docgia = $_SESSION['docgia'];
$taikhoan = $_SESSION['taikhoan'];
$dao = new DocGiaDAO();
$action = $_POST['action'] ?? '';

switch ($action) {

    case 'change':
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword     = $_POST['new_password'] ?? '';

        if (empty($currentPassword) || empty($newPassword)) {
            echo json_encode(["success" => false, "message" => "Thiếu thông tin"]);
            break;
        }

        if (strlen($newPassword) < 6) {
            echo json_encode(["success" => false, "message" => "Mật khẩu mới phải có ít nhất 6 ký tự"]);
            break;
        }

        // Kiểm tra mật khẩu hiện tại
        if (!password_verify($currentPassword, $taikhoan->getMatkhau())) {
            echo json_encode(["success" => false, "message" => "Mật khẩu hiện tại không đúng"]);
            break;
        }

        $result = $dao->doiMatKhau($taikhoan, $newPassword);

        // Nếu thành công thì cập nhật lại session
        if ($result['success']) {
            $newTaiKhoan = new TaiKhoan(
                $taikhoan->getTendangnhap(),
                password_hash($newPassword, PASSWORD_DEFAULT),
                $taikhoan->getManhomquyen(),
                $taikhoan->getManv(),
                $taikhoan->getMadocgia()
            );
            $_SESSION['taikhoan'] = $newTaiKhoan; // cập nhật lại session taikhoan
        }
        echo json_encode($result);
        break;
    default:
        echo json_encode(["success" => false, "message" => "Action không hợp lệ"]);
        break;
}