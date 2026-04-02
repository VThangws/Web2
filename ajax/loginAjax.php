<?php
require_once __DIR__. '/../model/DocGia.php';

// Bắt đầu session nếu chưa có
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__. '/../database/ConnectDB.php';
require_once __DIR__. '/../DAO/DocGiaDAO.php';
// Set header JSON (QUAN TRỌNG!)
header('Content-Type: application/json');

$dao = new DocGiaDAO();
$email = trim($_POST['email'] ?? "");
$matkhau = trim($_POST['matkhau'] ?? "");

// Kiểm tra rỗng
if ($email === "" || $matkhau === "") {
    echo json_encode([
        "status" => "error",
        "message" => "Vui lòng nhập đầy đủ email và mật khẩu"
    ]);
    exit;
}
$user = $dao->dangNhap($email, $matkhau);
if ($user) {
    $_SESSION['docgia'] = $user;
    
    // XỬ LÝ GIỎ HÀNG KHI LOGIN
    $madocgia = $user->getMadocgia();
    
    // 1. Trộn giỏ hàng hiện tại (chưa login) vào Database
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        $dao->mergeCart($madocgia, $_SESSION['cart']);
    }
    // 2. Kéo toàn bộ giỏ hàng từ Database ra và gán đè lại vào Session
    $_SESSION['cart'] = $dao->getCartFromDB($madocgia);

    echo json_encode([
        "status" => "success",
        "message" => "Chào mừng bạn " . $user->getTendocgia() . " đến với thư viện",
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Sai email hoặc mật khẩu"
    ]);
}

?>