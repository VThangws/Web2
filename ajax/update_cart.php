<?php
header('Content-Type: application/json');
ini_set('display_errors', 0);
error_reporting(E_ALL);

require_once __DIR__ . '/../model/DocGia.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../database/ConnectDB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['madausach']) && isset($_POST['action'])) {
    $madausach = $_POST['madausach'];
    $action = $_POST['action'];

    // Nếu sách không tồn tại trong giỏ thì báo lỗi luôn
    if (!isset($_SESSION['cart'][$madausach])) {
        echo json_encode(['status' => 'error', 'message' => 'Sách không có trong giỏ hàng!']);
        exit;
    }

    $db = ConnectDB::getInstance();
    $conn = $db->getConnection();
    
    // Check xem có đăng nhập không để cập nhật luôn vào DB
    $madocgia = isset($_SESSION['docgia']) ? $_SESSION['docgia']->getMaDocGia() : null;

    // 1. NÚT CỘNG (KÈM CHECK TỒN KHO)
    if ($action === 'plus') {
        $tonkho_hientai = $_SESSION['cart'][$madausach]['tonkho']; // Tồn kho đã lấy lúc add_to_cart
        
        if ($_SESSION['cart'][$madausach]['soluong'] < $tonkho_hientai) {
            $_SESSION['cart'][$madausach]['soluong'] += 1;
            
            // Cập nhật Database nếu đã Login
            if ($madocgia) {
                $stmt = $conn->prepare("UPDATE giohang SET soluong = soluong + 1 WHERE madocgia = ? AND madausach = ?");
                $stmt->bind_param("ss", $madocgia, $madausach);
                $stmt->execute();
            }
        } else {
            // Cửa chặn chốt sổ ở Backend
            echo json_encode(['status' => 'error', 'message' => 'Kho chỉ còn ' . $tonkho_hientai . ' cuốn, không thể thêm!']);
            exit;
        }
    } 
    // 2. NÚT TRỪ
    elseif ($action === 'minus') {
        $_SESSION['cart'][$madausach]['soluong'] -= 1;
        
        // Nếu trừ mà về 0 thì coi như Xóa luôn
        if ($_SESSION['cart'][$madausach]['soluong'] <= 0) {
            unset($_SESSION['cart'][$madausach]);
            if ($madocgia) {
                $stmt = $conn->prepare("DELETE FROM giohang WHERE madocgia = ? AND madausach = ?");
                $stmt->bind_param("ss", $madocgia, $madausach);
                $stmt->execute();
            }
        } else {
            if ($madocgia) {
                $stmt = $conn->prepare("UPDATE giohang SET soluong = soluong - 1 WHERE madocgia = ? AND madausach = ?");
                $stmt->bind_param("ss", $madocgia, $madausach);
                $stmt->execute();
            }
        }
    } 
    // 3. NÚT XÓA (THÙNG RÁC)
    elseif ($action === 'remove') {
        unset($_SESSION['cart'][$madausach]);
        if ($madocgia) {
            $stmt = $conn->prepare("DELETE FROM giohang WHERE madocgia = ? AND madausach = ?");
            $stmt->bind_param("ss", $madocgia, $madausach);
            $stmt->execute();
        }
    }

    // --- TÍNH TOÁN LẠI ĐỂ TRẢ VỀ GIAO DIỆN ---
    $total_items = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'soluong')) : 0;
    $item_qty = isset($_SESSION['cart'][$madausach]) ? $_SESSION['cart'][$madausach]['soluong'] : 0;
    
    // Tính tổng tiền toàn bộ giỏ (Mặc dù UI đã tự tính bằng JS, nhưng trả về cho chắc)
    $total_price = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total_price += $item['dongia'] * $item['soluong'];
        }
    }

    echo json_encode([
        'status' => 'success',
        'item_qty' => $item_qty,
        'total_items' => $total_items,
        'total_price' => number_format($total_price, 0, ',', '.') . ' VNĐ'
    ]);
    exit;
}
?>