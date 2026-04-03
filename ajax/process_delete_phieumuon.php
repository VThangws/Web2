<?php
require_once __DIR__ . '/../admin/login/auth.php';
require_admin_login();

// Chỉ trả JSON
function json_out($status, $message = '') {
    echo json_encode(['success' => ($status === 'success'), 'message' => $message]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_out('error', 'Phương thức không được hỗ trợ.');
}

require_once __DIR__ . '/../database/ConnectDB.php';
$conn = ConnectDB::getInstance()->getConnection();
$conn->set_charset('utf8mb4');

$mamuon = trim($_POST['mamuon'] ?? '');
if (!$mamuon) {
    json_out('error', 'Thiếu mã phiếu mượn.');
}

try {
    $conn->begin_transaction();

    // 1. Kiểm tra trạng thái phiếu
    $stmt = $conn->prepare("SELECT trangthai FROM phieumuon WHERE mamuon = ? FOR UPDATE");
    $stmt->bind_param("s", $mamuon);
    $stmt->execute();
    $result = $stmt->get_result();
    $pm = $result->fetch_assoc();
    $stmt->close();

    if (!$pm) {
        throw new Exception("Không tìm thấy phiếu mượn này.");
    }

    if ($pm['trangthai'] !== 'ChoDuyet') {
        throw new Exception("Chỉ được hủy các phiếu đang ở trạng thái Chờ Duyệt (PENDING). Phiếu này đang là: " . $pm['trangthai']);
    }

    // 2. Lấy danh sách mã cuốn sách và cập nhật lại trạng thái thành SanSang
    $stmtCt = $conn->prepare("SELECT macuonsach FROM ctphieumuon WHERE mamuon = ?");
    $stmtCt->bind_param("s", $mamuon);
    $stmtCt->execute();
    $booksResult = $stmtCt->get_result();
    $macuonsachs = [];
    while ($row = $booksResult->fetch_assoc()) {
        $macuonsachs[] = $row['macuonsach'];
    }
    $stmtCt->close();

    if (!empty($macuonsachs)) {
        $placeholders = str_repeat('?,', count($macuonsachs) - 1) . '?';
        $updateQuery = "UPDATE cuonsach SET trangthai = 'SanSang' WHERE macuonsach IN ($placeholders)";
        $stmtUpdate = $conn->prepare($updateQuery);
        $types = str_repeat('s', count($macuonsachs));
        $stmtUpdate->bind_param($types, ...$macuonsachs);
        $stmtUpdate->execute();
        $stmtUpdate->close();
    }

    // 3. Xóa chi tiết phiếu mượn
    $delCt = $conn->prepare("DELETE FROM ctphieumuon WHERE mamuon = ?");
    $delCt->bind_param("s", $mamuon);
    $delCt->execute();
    $delCt->close();

    // 4. Xóa phiếu mượn
    $delPm = $conn->prepare("DELETE FROM phieumuon WHERE mamuon = ?");
    $delPm->bind_param("s", $mamuon);
    $delPm->execute();
    $delPm->close();

    $conn->commit();
    json_out('success');

} catch (Exception $e) {
    $conn->rollback();
    json_out('error', $e->getMessage());
}
