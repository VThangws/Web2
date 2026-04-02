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

// Tắt warning/notice để không bể JSON (nhưng vẫn bắt Throwable để trả JSON lỗi)
error_reporting(0);
ini_set('display_errors', '0');

try {
    $conn = ConnectDB::getInstance()->getConnection();
    if (!$conn) {
        json_out('error', 'Không kết nối được CSDL.');
    }
    $conn->set_charset('utf8mb4');

    /**
     * Admin session theo admin/login.php:
     * $_SESSION['admin_user'] = ['tendangnhap','manhomquyen','manv','madocgia']
     */
    $admin = $_SESSION['admin_user'] ?? null;
    $manv = is_array($admin) ? trim((string)($admin['manv'] ?? '')) : '';
    if ($manv === '') {
        json_out('error', 'Bạn chưa đăng nhập admin hoặc thiếu mã nhân viên (manv).');
    }

    // 1) Input
    $mamuon = $_POST['mamuon'] ?? '';
    $mamuon = is_string($mamuon) ? trim($mamuon) : '';
    if ($mamuon === '') {
        json_out('error', 'Không tìm thấy mã phiếu mượn!');
    }

    // 2) Lấy info phiếu mượn
    $stmt = $conn->prepare("SELECT trangthai, loaimuon FROM phieumuon WHERE mamuon = ? LIMIT 1");
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
    $type = (string)($result['loaimuon'] ?? '');
    $newStatus = '';

    // 3) Logic chuyển đổi trạng thái
    if ($type === 'ONLINE') {
        if ($currentStatus === 'PENDING') $newStatus = 'ACTIVE';
        elseif ($currentStatus === 'ACTIVE') $newStatus = 'COMPLETED';
    } else { // ON_SITE
        if ($currentStatus === 'ACTIVE') $newStatus = 'COMPLETED';
    }

    if ($newStatus === '') {
        json_out('error', 'Phiếu đang ở trạng thái "' . $currentStatus . '" nên không thể cập nhật.');
    }

    // 4) Transaction
    $conn->begin_transaction();

    // 4.1) Update phieumuon + lưu manv & thời gian xác nhận
    // Chỉ set ngaymuon khi chuyển sang ACTIVE (tránh ghi đè nếu COMPLETED)
    $sqlUpdate = "
        UPDATE phieumuon
        SET trangthai = ?,
            manv = ?,
            thoigian_xacnhan = NOW()
            " . ($newStatus === 'ACTIVE' ? ", ngaymuon = NOW()" : "") . "
        WHERE mamuon = ?
    ";

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

    // 4.2) Nếu COMPLETED thì trả sách về SanSang (nếu có bảng ctphieumuon & cuonsach)
    if ($newStatus === 'COMPLETED') {
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

    json_out('success', 'Cập nhật thành công!', [
        'mamuon' => $mamuon,
        'oldStatus' => $currentStatus,
        'newStatus' => $newStatus,
        'manv' => $manv,
        'type' => $type,
    ]);

} catch (Throwable $e) {
    // Đảm bảo luôn trả JSON thay vì chết 500
    json_out('error', 'Server error: ' . $e->getMessage());
}