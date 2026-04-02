<?php
require_once __DIR__ . '/../model/DocGia.php';
session_start();

// Gọi kết nối DB
require_once __DIR__ . '/../database/ConnectDB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $madausach = $_POST['madausach'] ?? '';
    $action = $_POST['action'] ?? '';

    $db = ConnectDB::getInstance();
    $conn = $db->getConnection();

    $madocgia = isset($_SESSION['docgia']) ? $_SESSION['docgia']->getMadocgia() : null;

    // Mở Balo ra để tùy chỉnh số lượng
    if (isset($_SESSION['cart'][$madausach])) {
        if ($action === 'plus') {
            $_SESSION['cart'][$madausach]['soluong'] += 1;
            
            // CẬP NHẬT XUỐNG DB NẾU ĐÃ LOGIN
            if ($madocgia) {
                $sql = "UPDATE giohang SET soluong = soluong + 1 WHERE madocgia = ? AND madausach = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $madocgia, $madausach);
                $stmt->execute();
            }

        } elseif ($action === 'minus') {
            if ($_SESSION['cart'][$madausach]['soluong'] > 1) {
                $_SESSION['cart'][$madausach]['soluong'] -= 1;
                
                // CẬP NHẬT XUỐNG DB NẾU ĐÃ LOGIN
                if ($madocgia) {
                    $sql = "UPDATE giohang SET soluong = soluong - 1 WHERE madocgia = ? AND madausach = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ss", $madocgia, $madausach);
                    $stmt->execute();
                }
            }
        } elseif ($action === 'remove') {
            unset($_SESSION['cart'][$madausach]); // Vứt luôn trên RAM
            
            // XÓA TRONG DB NẾU ĐÃ LOGIN
            if ($madocgia) {
                $sql = "DELETE FROM giohang WHERE madocgia = ? AND madausach = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $madocgia, $madausach);
                $stmt->execute();
            }
        }
    }

    // Đếm lại tổng số lượng và tính lại tổng tiền
    $total_price = 0;
    $item_qty = 0;

    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $id => $item) {
            $total_price += ($item['dongia'] * $item['soluong']);
            
            // Lưu lại số lượng của cái cuốn sách đang bấm để báo về cho JS
            if ($id === $madausach) {
                $item_qty = $item['soluong'];
            }
        }
    }

    $total_items = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
    
    // Gói ghém gửi về cho giao diện cập nhật
    echo json_encode([
        'status' => 'success',
        'item_qty' => $item_qty,
        'total_items' => $total_items,
        'total_price' => number_format($total_price, 0, ',', '.') . ' VNĐ'
    ]);
    exit;
}
?>