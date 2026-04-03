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

/**
 * ID helpers: map PMxxxx -> PTxxxx / PPxxxx, fallback to hash.
 */
function make_id_from_mamuon(string $prefix, string $mamuon): string {
    $mamuon = trim($mamuon);
    if ($mamuon === '') {
        return $prefix . substr(md5((string)microtime(true)), 0, 10);
    }
    if (str_starts_with($mamuon, 'PM')) {
        $id = $prefix . substr($mamuon, 2);
        return strlen($id) <= 50 ? $id : substr($id, 0, 50);
    }

    $hash = substr(sha1($mamuon), 0, 12);
    $id = $prefix . $hash;
    return strlen($id) <= 50 ? $id : substr($id, 0, 50);
}

function calc_overdue_days(?string $dueAt, string $returnedAt): int {
    if (!$dueAt) return 0;
    try {
        $due = new DateTime($dueAt);
        $ret = new DateTime($returnedAt);
        if ($ret <= $due) return 0;
        $seconds = $ret->getTimestamp() - $due->getTimestamp();
        return (int)ceil($seconds / 86400);
    } catch (Throwable $e) {
        return 0;
    }
}

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

    // 2) Lấy info phiếu mượn (schema hiện tại)
    $stmt = $conn->prepare("SELECT trangthai, ngaymuon, ngayhethan, madocgia FROM phieumuon WHERE mamuon = ? LIMIT 1");
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
    $ngayMuon = (string)($result['ngaymuon'] ?? '');
    $ngayHetHan = (string)($result['ngayhethan'] ?? '');
    $madocgia = (string)($result['madocgia'] ?? '');
    // 3) Logic điều hướng hoặc chuyển đổi trạng thái
    if ($currentStatus === 'ChoDuyet') {
        $newStatus = 'DangMuon';
        
        $conn->begin_transaction();
        
        // Cập nhật trạng thái phiếu
        $sqlUpdate = "UPDATE phieumuon SET trangthai = ?, manv = ?, ngaymuon = COALESCE(ngaymuon, NOW()), ngayhethan = COALESCE(ngayhethan, DATE_ADD(NOW(), INTERVAL 14 DAY)) WHERE mamuon = ?";
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

        // Đổi trạng thái các sách thành DaMuon (đồng bộ với trạng thái lúc lập phiếu)
        $reset = $conn->prepare("UPDATE cuonsach c JOIN ctphieumuon ct ON c.macuonsach = ct.macuonsach SET c.trangthai = 'DaMuon' WHERE ct.mamuon = ?");
        if ($reset) {
            $reset->bind_param('s', $mamuon);
            $reset->execute();
            $reset->close();
        }

        $conn->commit();

        json_out('success', 'Đã duyệt phiếu mượn thành công. Các cuốn sách đang được mượn!', [
            'mamuon' => $mamuon,
            'oldStatus' => $currentStatus,
            'newStatus' => $newStatus,
            'manv' => $manv,
            'madocgia' => $madocgia,
        ]);

    } elseif ($currentStatus === 'DangMuon') {
        // Redirect client sang màn hình lập phiếu trả (lap_phieu_tra.php)
        json_out('redirect', 'Đang chuyển sang màn hình lập phiếu trả...', [
            'url' => '/admin/QuanLy/HoaDon/lap_phieu_tra.php?mamuon=' . urlencode($mamuon)
        ]);
        
    } else {
        // Trạng thái DaTra hoặc khác
        json_out('error', 'Phiếu mượn đang ở trạng thái "' . $currentStatus . '", không thể thao tác chức năng này!');
    }

} catch (Throwable $e) {
    json_out('error', 'Server error: ' . $e->getMessage());
}