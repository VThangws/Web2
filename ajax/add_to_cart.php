<?php
require_once __DIR__ . '/../model/DocGia.php';
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

    $db = ConnectDB::getInstance();
    $conn = $db->getConnection();

    // 1. CỘNG DỒN VÀO RAM
    if (isset($_SESSION['cart'][$madausach])) {
        $_SESSION['cart'][$madausach]['soluong'] += 1;
    } 
    // Nếu CHƯA CÓ, vào kho (Database) lấy thông tin sách ra thêm vào giỏ
    else {
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

    // 2. LƯU XUỐNG DATABASE (CHỈ KHI LOGIN)
    if (isset($_SESSION['docgia'])) {
        $docgia = $_SESSION['docgia'];
        $madocgia = $docgia->getMadocgia();

        // Lệnh SQL ma thuật: Chưa có sách trong DB thì Thêm mới 1 cuốn. Có rồi thì Tự cộng dồn 1.
        $sql_db = "INSERT INTO giohang (madocgia, madausach, soluong) 
                   VALUES (?, ?, 1) 
                   ON DUPLICATE KEY UPDATE soluong = soluong + 1";
        $stmt_db = $conn->prepare($sql_db);
        $stmt_db->bind_param("ss", $madocgia, $madausach);
        $stmt_db->execute();
    }

    // 3. ĐẾM SỐ ĐẦU SÁCH ĐỂ HIỆN THỊ LÊN GIỎ HÀNG
    $total_items = count($_SESSION['cart']);

    // Trả kết quả về cho giao diện
    echo json_encode([
        'status' => 'success', 
        'message' => 'Đã thêm thành công vào giỏ!',
        'total_items' => $total_items
    ]);
    exit;
}
?>