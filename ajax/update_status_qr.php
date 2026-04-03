<?php
// filepath: d:\web2\Web2\ajax\update_status_qr.php
declare(strict_types=1);

require_once __DIR__ . '/../database/ConnectDB.php';

if (session_status() === PHP_SESSION_NONE) {
    // Đồng bộ cookie toàn site để /admin và /ajax dùng chung session
    session_set_cookie_params([
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

header('Content-Type: application/json; charset=utf-8');

function json_out(string $status, string $message, array $extra = []): void {
    echo json_encode(
        array_merge(['status' => $status, 'message' => $message], $extra),
        JSON_UNESCAPED_UNICODE
    );
    exit;
}

// Tắt warning/notice để không bể JSON
error_reporting(0);
ini_set('display_errors', '0');

try {
    $conn = ConnectDB::getInstance()->getConnection();
    if (!$conn) {
        json_out('error', 'Không kết nối được CSDL.');
    }
    $conn->set_charset('utf8mb4');

    $admin = $_SESSION['admin_user'] ?? null;
    $manv = is_array($admin) ? trim((string)($admin['manv'] ?? '')) : '';
    if ($manv === '') {
        json_out('error', 'Bạn chưa đăng nhập admin hoặc thiếu mã nhân viên (manv).');
    }

    $mamuon = $_POST['mamuon'] ?? '';
    $mamuon = is_string($mamuon) ? trim($mamuon) : '';
    if ($mamuon === '') {
        json_out('error', 'Không tìm thấy mã phiếu mượn!');
    }

    // 1) SỬA LỖI Ở ĐÂY: Select cột ghichu thay vì loaimuon
    $stmt = $conn->prepare("SELECT trangthai, ghichu FROM phieumuon WHERE mamuon = ? LIMIT 1");
    if (!$stmt) {
        json_out('error', 'Lỗi prepare SELECT phieumuon: ' . $conn->error);
    }
    $stmt->bind_param("s", $mamuon);
    if (!$stmt->execute()) {
        $stmt->close();
        json_out('error', 'Lỗi execute SELECT phieumuon: ' . $stmt->error);
    }

    $res = $stmt->get_result();
    $result = $res ? $res->fetch_assoc() : null;
    $stmt->close();

    if (!$result) {
        json_out('error', 'Phiếu mượn ' . $mamuon . ' không tồn tại!');
    }

    $currentStatus = (string)($result['trangthai'] ?? '');
    $ghichu = (string)($result['ghichu'] ?? '');
    $newStatus = '';

    // 2) SỬA LOGIC TRẠNG THÁI CHO KHỚP DB: ChoDuyet -> DangMuon -> DaTra
    if (strpos($ghichu, 'Mượn mang về') !== false) {
        if ($currentStatus === 'ChoDuyet') $newStatus = 'DangMuon'; // Khách đến lấy sách
        elseif ($currentStatus === 'DangMuon') $newStatus = 'DaTra'; // Khách trả sách
    } else { // Đọc tại chỗ
        if ($currentStatus === 'DangMuon') $newStatus = 'DaTra'; // Khách đọc xong trả sách
    }

    if ($newStatus === '') {
        json_out('error', 'Phiếu đang ở trạng thái "' . $currentStatus . '" nên không thể cập nhật.');
    }

    $conn->begin_transaction();

    // 3) BỎ CỘT thoigian_xacnhan (Vì DB của ní không có) để tránh lỗi Unknown Column
    $sqlUpdate = "UPDATE phieumuon SET trangthai = ?, manv = ? WHERE mamuon = ?";
    $up = $conn->prepare($sqlUpdate);
    if (!$up) {
        $conn->rollback();
        json_out('error', 'Lỗi prepare UPDATE phieumuon: ' . $conn->error);
    }

    $up->bind_param("sss", $newStatus, $manv, $mamuon);
    if (!$up->execute()) {
        $err = $up->error;
        $up->close();
        $conn->rollback();
        json_out('error', 'Lỗi execute UPDATE phieumuon: ' . $err);
    }
    $up->close();

    // 4) Đổi chữ COMPLETED thành DaTra
    if ($newStatus === 'DaTra') {
        $checkCt = $conn->query("SHOW TABLES LIKE 'ctphieumuon'");
        $checkCs = $conn->query("SHOW TABLES LIKE 'cuonsach'");
        $hasCt = $checkCt && $checkCt->num_rows > 0;
        $hasCs = $checkCs && $checkCs->num_rows > 0;

        if ($hasCt && $hasCs) {
            $reset = $conn->prepare("
                UPDATE cuonsach c
                JOIN ctphieumuon ct ON c.macuonsach = ct.macuonsach
                SET c.trangthai = 'SanSang'
                WHERE ct.mamuon = ?
            ");

            if (!$reset) {
                $conn->rollback();
                json_out('error', 'Lỗi prepare reset cuonsach: ' . $conn->error);
            }

            $reset->bind_param("s", $mamuon);
            if (!$reset->execute()) {
                $err = $reset->error;
                $reset->close();
                $conn->rollback();
                json_out('error', 'Lỗi execute reset cuonsach: ' . $err);
            }
            $reset->close();
        }
    }

    $conn->commit();

    json_out('success', 'Trạng thái chuyển thành: ' . $newStatus, [
        'mamuon' => $mamuon,
        'oldStatus' => $currentStatus,
        'newStatus' => $newStatus,
        'manv' => $manv
    ]);

} catch (Throwable $e) {
    json_out('error', 'Server error: ' . $e->getMessage());
}