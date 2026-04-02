<?php
session_start();
require_once __DIR__ . '/../database/ConnectDB.php';

// Kiểm tra đăng nhập
if (empty($_SESSION['user_id'])) {
    header('Location: /index.php?page=login');
    exit;
}

$conn = ConnectDB::getInstance()->getConnection();
$user_id = $_SESSION['user_id'];
$type = $_POST['type'] ?? 'ONLINE'; // ONLINE hoặc ON_SITE
$pickup_date = $_POST['pickup_date'] ?? null;
$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    header('Location: /index.php?page=cart');
    exit;
}

// Bắt đầu Transaction để đảm bảo dữ liệu đồng bộ
$conn->begin_transaction();

try {
    // 1. Tạo Phiếu Mượn chính
    // Trạng thái ban đầu: PENDING (Online) hoặc ACTIVE (Tại chỗ)
    $status = ($type === 'ONLINE') ? 'PENDING' : 'ACTIVE';
    $stmt = $conn->prepare("INSERT INTO phieumuon (madocgia, loaimuon, trangthai, ngayhenlay, ngaymuon) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("isss", $user_id, $type, $status, $pickup_date);
    $stmt->execute();
    $mamuon = $conn->insert_id;

    // 2. Duyệt giỏ hàng để xử lý từng cuốn sách
    foreach ($cart as $ma => $item) {
        // Kiểm tra tồn kho thực tế (Sách phải ở trạng thái 'SanSang')
        $checkInv = $conn->prepare("SELECT COUNT(*) as stock FROM cuonsach WHERE madausach = ? AND trangthai = 'SanSang'");
        $checkInv->bind_param("i", $ma);
        $checkInv->execute();
        $stock = $checkInv->get_result()->fetch_assoc()['stock'];

        if ($stock < $item['soluong']) {
            throw new Exception("Sách '" . $item['tensach'] . "' đã hết mất rồi ní ơi!");
        }

        // 3. Trừ kho (Trọng tâm: Chuyển trạng thái cuốn sách cụ thể sang 'DaMuon')
        // Lấy danh sách ID cuốn sách đang sẵn sàng để mượn
        $getIds = $conn->prepare("SELECT macuonsach FROM cuonsach WHERE madausach = ? AND trangthai = 'SanSang' LIMIT ?");
        $getIds->bind_param("ii", $ma, $item['soluong']);
        $getIds->execute();
        $ids = $getIds->get_result();

        while ($book = $ids->fetch_assoc()) {
            $bookId = $book['macuonsach'];
            // Cập nhật trạng thái cuốn sách để giữ chỗ
            $conn->query("UPDATE cuonsach SET trangthai = 'DaMuon' WHERE macuonsach = $bookId");
            
            // Lưu vào bảng chi tiết phiếu mượn (nếu ní có bảng này)
            // $conn->query("INSERT INTO chitietphieumuon (mamuon, macuonsach) VALUES ($mamuon, $bookId)");
        }
    }

    // Nếu mọi thứ ok, xác nhận lưu vào DB
    $conn->commit();
    
    // Xóa giỏ hàng sau khi mượn thành công
    unset($_SESSION['cart']);
    
    // Chuyển về trang thông báo thành công (có thể kèm mã QR)
    header("Location: /index.php?page=borrow_success&id=$mamuon");

} catch (Exception $e) {
    // Nếu lỗi (hết sách), hủy bỏ mọi thay đổi
    $conn->rollback();
    die($e->getMessage());
}