<?php
session_start();
// Gọi kết nối Database
require_once __DIR__ . '/../database/ConnectDB.php';

// Kiểm tra xem có nhận được mã sách gửi lên không
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['madausach'])) {
    $madausach = $_POST['madausach'];

    // Nếu giỏ hàng chưa tồn tại, tạo mới một cái giỏ rỗng
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Nếu cuốn sách này ĐÃ CÓ trong giỏ, cộng thêm 1 vào số lượng
    if (isset($_SESSION['cart'][$madausach])) {
        $_SESSION['cart'][$madausach]['soluong'] += 1;
    } 
    // Nếu CHƯA CÓ, phi vào kho (Database) lấy thông tin sách ra ném vào giỏ
    else {
        $db = ConnectDB::getInstance();
        $conn = $db->getConnection();
        $sql = "SELECT tensach, anhbia, dongia FROM DauSach WHERE madausach = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $madausach);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            // Bỏ thông tin sách vào "Balo"
            $_SESSION['cart'][$madausach] = [
                'tensach' => $row['tensach'],
                'anhbia' => $row['anhbia'],
                'dongia' => $row['dongia'],
                'soluong' => 1
            ];
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Sách không tồn tại!']);
            exit;
        }
    }

    // Đếm xem trong giỏ đang có tổng cộng mấy cuốn
    $tong_so_luong = 0;
    foreach ($_SESSION['cart'] as $item) {
        $tong_so_luong += $item['soluong'];
    }

    // Trả kết quả về cho giao diện
    echo json_encode([
        'status' => 'success', 
        'message' => 'Đã thêm thành công vào giỏ!',
        'total_items' => $tong_so_luong
    ]);
    exit;
}
?>