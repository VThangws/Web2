<?php
// Nhớ check lại đường dẫn tới file ConnectDB nha, từ thư mục ajax lùi 1 cấp (..) là ra ngoài root
require_once __DIR__ . '/../database/ConnectDB.php';

header('Content-Type: application/json; charset=utf-8');

// Chỉ nhận request từ POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ.']);
    exit;
}

$maphat = $_POST['maphat'] ?? '';

if ($maphat === '') {
    echo json_encode(['success' => false, 'message' => 'Thiếu mã phiếu phạt.']);
    exit;
}

try {
    $conn = ConnectDB::getInstance()->getConnection();
    $conn->set_charset('utf8mb4');

    // Chạy lệnh update trạng thái
    // LƯU Ý: Nếu trong CSDL của ní lưu chữ không dấu thì sửa 'Đã đóng' thành 'DaDong' nha
    $stmt = $conn->prepare("UPDATE phieuphat SET trangthai = 'Đã đóng' WHERE maphat = ?");
    $stmt->bind_param('s', $maphat);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Đã xác nhận thu tiền thành công.']);
    } else {
        // Trường hợp không có dòng nào được update (có thể do sai mã hoặc đã đóng từ trước rồi)
        echo json_encode(['success' => false, 'message' => 'Không tìm thấy phiếu hoặc phiếu này đã được thu tiền.']);
    }
    
    $stmt->close();
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
}
?>