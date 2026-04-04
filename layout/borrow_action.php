<?php
// BẮT BUỘC require model trước khi gọi session chứa Object
require_once __DIR__ . '/../model/DocGia.php';
session_start();

// Hàm hỗ trợ bật Cửa Sổ Cảnh Báo thay vì âm thầm đá trang
function debugAlert($msg, $url) {
    echo "<script>alert('BÁO CÁO LỖI: $msg'); window.location.href='$url';</script>";
    exit;
}

// 1. KIỂM TRA ĐĂNG NHẬP
$docgia = $_SESSION['docgia'] ?? null;
if (!$docgia) {
    debugAlert('Session [docgia] bị rỗng! Bạn chưa đăng nhập hoặc Session đã chết.', '../index.php?page=login');
}

// 2. KIỂM TRA DỮ LIỆU TỪ FORM
$checkout_items = $_POST['checkout_items'] ?? [];
if (empty($checkout_items)) {
    debugAlert('Dữ liệu POST checkout_items bị rỗng! Form ở trang trước chưa gửi mã sách qua.', '../index.php?page=cart');
}

// 3. KIỂM TRA GIỎ HÀNG GỐC
$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    debugAlert('Giỏ hàng trong Session (cart) đang bị rỗng!', '../index.php?page=cart');
}

// 4. KIỂM TRA XEM MÃ SÁCH TỪ FORM CÓ KHỚP VỚI GIỎ HÀNG KHÔNG
$selected_cart = [];
foreach ($checkout_items as $ma) {
    if (isset($cart[$ma])) {
        $selected_cart[$ma] = $cart[$ma];
    }
}
if (empty($selected_cart)) {
    debugAlert('Lọc sách thất bại! Mã sách gửi qua không tồn tại trong giỏ hàng.', '../index.php?page=cart');
}

// ===============================================
// NẾU QUA HẾT 4 ẢI TRÊN THÌ CODE CHẠY XUỐNG ĐÂY
// ===============================================
require_once __DIR__ . '/../database/ConnectDB.php';
try {
    $conn = ConnectDB::getInstance()->getConnection();
    $user_id = $docgia->getMadocgia(); 
    $type = $_POST['type'] ?? 'ONLINE'; 
    $pickup_date = $_POST['pickup_date'] ?? null;

    $conn->begin_transaction();

    // Tạo Phiếu Mượn chính
    $status = ($type === 'ONLINE') ? 'PENDING' : 'ACTIVE';
    $stmt = $conn->prepare("INSERT INTO phieumuon (madocgia, loaimuon, trangthai, ngayhenlay, ngaymuon) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("isss", $user_id, $type, $status, $pickup_date);
    $stmt->execute();
    $mamuon = $conn->insert_id;

    // Duyệt giỏ hàng để trừ kho
    foreach ($selected_cart as $ma => $item) {
        $checkInv = $conn->prepare("SELECT COUNT(*) as stock FROM cuonsach WHERE madausach = ? AND trangthai = 'SanSang'");
        $checkInv->bind_param("s", $ma);
        $checkInv->execute();
        $stock = $checkInv->get_result()->fetch_assoc()['stock'];

        if ($stock < $item['soluong']) {
            throw new Exception("Sách '" . $item['tensach'] . "' đã hết mất rồi!");
        }

        $getIds = $conn->prepare("SELECT macuonsach FROM cuonsach WHERE madausach = ? AND trangthai = 'SanSang' LIMIT ?");
        $getIds->bind_param("si", $ma, $item['soluong']);
        $getIds->execute();
        $ids = $getIds->get_result();

        while ($book = $ids->fetch_assoc()) {
            $bookId = $book['macuonsach'];
            $updateC = $conn->prepare("UPDATE cuonsach SET trangthai = 'DaMuon' WHERE macuonsach = ?");
            $updateC->bind_param("s", $bookId);
            $updateC->execute();
        }
        
        // Xóa sách ĐÃ MƯỢN ra khỏi giỏ hàng
        unset($_SESSION['cart'][$ma]);
    }

    $conn->commit();
    unset($_SESSION['checkout_items']);
    
    // MƯỢN THÀNH CÔNG (Chuyển trang bằng JS)
    echo "<script>window.location.href='../index.php?page=borrow_success&id=$mamuon';</script>";
    exit;

} catch (Exception $e) {
    $conn->rollback();
    debugAlert('LỖI DATABASE: ' . $e->getMessage(), '../index.php?page=cart');
}