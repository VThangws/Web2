<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $madausach = $_POST['madausach'] ?? '';
    $action = $_POST['action'] ?? '';

    // Mở Balo ra để tùy chỉnh số lượng
    if (isset($_SESSION['cart'][$madausach])) {
        if ($action === 'plus') {
            $_SESSION['cart'][$madausach]['soluong'] += 1;
        } elseif ($action === 'minus') {
            // YÊU CẦU MỚI: Chỉ giảm khi số lượng lớn hơn 1. Xóa thì phải dùng nút Xóa.
            if ($_SESSION['cart'][$madausach]['soluong'] > 1) {
                $_SESSION['cart'][$madausach]['soluong'] -= 1;
            }
        } elseif ($action === 'remove') {
            unset($_SESSION['cart'][$madausach]); // Vứt luôn không nói nhiều
        }
    }

    // Đếm lại tổng số lượng và tính lại tổng tiền
    $total_price = 0;
    $total_items = 0;
    $item_qty = 0;

    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $id => $item) {
            $total_items += $item['soluong'];
            $total_price += ($item['dongia'] * $item['soluong']);
            
            // Lưu lại số lượng của cái cuốn sách đang bấm để báo về cho JS
            if ($id === $madausach) {
                $item_qty = $item['soluong'];
            }
        }
    }

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