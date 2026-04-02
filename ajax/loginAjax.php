<?php
require_once __DIR__ . '/../model/DocGia.php';
require_once __DIR__ . '/../model/TaiKhoan.php';  // thêm dòng này

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../database/ConnectDB.php';
require_once __DIR__ . '/../DAO/DocGiaDAO.php';

header('Content-Type: application/json');

$dao = new DocGiaDAO();
$email    = trim($_POST['email']    ?? "");
$matkhau  = trim($_POST['matkhau']  ?? "");

if ($email === "" || $matkhau === "") {
    echo json_encode(["status" => "error", "message" => "Vui lòng nhập đầy đủ email và mật khẩu"]);
    exit;
}

$result = $dao->dangNhap($email, $matkhau);

if ($result) {
    $user     = $result['docgia'];
    $madocgia = $user->getMadocgia();

    // Lưu session docgia
    $_SESSION['docgia'] = $user;

    // Lưu session taikhoan (hash lấy từ DB)
    // Nếu là tài khoản nhân viên/admin đã link qua docgia thì sẽ có manv
    $_SESSION['taikhoan'] = new TaiKhoan(
        $email,
        $result['matkhau'],   // hash từ DB
        $result['quyen'],
        $result['manv'] ?? null,
        $madocgia
    );

    // Xử lý giỏ hàng
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        $dao->mergeCart($madocgia, $_SESSION['cart']);
    }
    $_SESSION['cart'] = $dao->getCartFromDB($madocgia);

    echo json_encode([
        "status"  => "success",
        "message" => "Chào mừng bạn " . $user->getTendocgia() . " đến với thư viện",
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "Sai email hoặc mật khẩu"]);
}
