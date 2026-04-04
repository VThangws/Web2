<?php
require_once __DIR__ . '/../database/ConnectDB.php';

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(['path' => '/', 'httponly' => true, 'samesite' => 'Lax']);
    session_start();
}

header('Content-Type: application/json; charset=utf-8');

function json_out(string $status, string $message): void {
    echo json_encode(['status' => $status, 'message' => $message], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $conn = ConnectDB::getInstance()->getConnection();
    if (!$conn) json_out('error', 'Lỗi kết nối database.');
    $conn->set_charset('utf8mb4');

    // Tự động vá lỗi CSDL: Cho phép maphat trong ctphieutra được phép rỗng
    try {
         $conn->query("ALTER TABLE ctphieutra MODIFY maphat VARCHAR(50) NULL");
         // Nếu vướng khóa chính, gỡ khóa chính cũ và gán lại khóa chính mới không chứa maphat
         $conn->query("ALTER TABLE ctphieutra DROP PRIMARY KEY, ADD PRIMARY KEY(matra, macuonsach)");
         $conn->query("ALTER TABLE ctphieutra MODIFY maphat VARCHAR(50) NULL");
    } catch(Exception $e) {}

    $admin = $_SESSION['admin_user'] ?? null;
    $manv = is_array($admin) ? trim((string)($admin['manv'] ?? '')) : '';
    if (!$manv) json_out('error', 'Vui lòng đăng nhập.');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        json_out('error', 'Invalid request method');
    }

    $mamuon = trim($_POST['mamuon'] ?? '');
    $madocgia = trim($_POST['madocgia'] ?? '');
    $songayquahan = (int)($_POST['songayquahan'] ?? 0);
    
    $tinhtrang_sau = $_POST['tinhtrang_sau'] ?? [];
    $lydo = $_POST['lydo'] ?? [];
    $tienphat = $_POST['tienphat'] ?? [];

    if (!$mamuon) json_out('error', 'Thiếu mã mượn.');

    // Kiểm tra phiếu mượn
    $stmt = $conn->prepare("SELECT trangthai FROM phieumuon WHERE mamuon = ?");
    $stmt->bind_param("s", $mamuon);
    $stmt->execute();
    $pm = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$pm) {
        json_out('error', 'Không tìm thấy phiếu mượn.');
    }
    
    if ($pm['trangthai'] !== 'DangMuon') {
        json_out('error', 'Lỗi tính toàn vẹn: Chỉ có thể thu hồi/trả sách đối với phiếu đang ở trạng thái Đang Mượn (Tiến trình 1 chiều). Trạng thái phiếu hiện tại: ' . $pm['trangthai']);
    }

    // ID generation
    $matra = 'PT' . substr(md5(microtime() . rand()), 0, 8);
    $maphat = 'PP' . substr(md5(microtime() . rand()), 0, 8);
    $ngaytra = (new DateTime())->format('Y-m-d H:i:s');
    
    $FINE_PER_DAY = 5000;
    
    // Tính tổng tiền phạt
    $totalLateFine = 0;
    $totalDamageFine = 0;
    
    $bookFines = []; // Lưu chi tiết phạt của từng cuốn sách

    foreach ($tinhtrang_sau as $macuonsach => $statusText) {
        $lateFineForThis = $songayquahan * $FINE_PER_DAY;
        $damageFineForThis = (int)($tienphat[$macuonsach] ?? 0);
        $reason = trim($lydo[$macuonsach] ?? '');

        $totalLateFine += $lateFineForThis;
        $totalDamageFine += $damageFineForThis;

        $bookFines[$macuonsach] = [
            'tinhtrang' => $statusText,
            'lydo' => $reason,
            'tienphat_late' => $lateFineForThis,
            'tienphat_damage' => $damageFineForThis,
            'total' => $lateFineForThis + $damageFineForThis
        ];
    }

    $grandTotalPenalty = $totalLateFine + $totalDamageFine;
    $hasPenalty = ($grandTotalPenalty > 0);

    $conn->begin_transaction();

    // 1. Insert Phieu Tra (tongtienphat = grandTotalPenalty)
    $stmtTra = $conn->prepare("INSERT INTO phieutra (matra, mamuon, ngaytra, manv, tongtienphat) VALUES (?, ?, ?, ?, ?)");
    $stmtTra->bind_param("ssssd", $matra, $mamuon, $ngaytra, $manv, $grandTotalPenalty);
    if (!$stmtTra->execute()) {
        throw new Exception("Lỗi tạo phiếu trả: " . $stmtTra->error);
    }
    $stmtTra->close();

    // 2. Insert Phieu Phat CHỈ KHI CÓ PHẠT
    if ($hasPenalty) {
        $ghichu = "Phạt trả sách đơn $mamuon";
        $trangthaiPhat = 'ChuaNop';
        $stmtPhat = $conn->prepare("INSERT INTO phieuphat (maphat, madocgia, matra, ngaylap, tongtienphat, trangthai, ghichu) VALUES (?, ?, ?, NOW(), ?, ?, ?)");
        $stmtPhat->bind_param("sssdss", $maphat, $madocgia, $matra, $grandTotalPenalty, $trangthaiPhat, $ghichu);
        
        if (!$stmtPhat->execute()) {
            throw new Exception("Lỗi tạo hóa đơn phạt: " . $stmtPhat->error);
        }
        $stmtPhat->close();
    }

    $maphat_for_ct = $hasPenalty ? $maphat : null;

    // 3. Insert ctphieutra và ctphieuphat
    $stmtCtTra = $conn->prepare("INSERT INTO ctphieutra (matra, macuonsach, maphat, tinhtrang_sau, tienphathuha, songayquahan, tienphatquahan) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmtCtPhat = $conn->prepare("INSERT INTO ctphieuphat (maphat, macuonsach, lydo, songayquahan, sotienphat) VALUES (?, ?, ?, ?, ?)");
    
    $stmtUpdateSach = $conn->prepare("UPDATE cuonsach SET trangthai = ?, tinhtrang = ? WHERE macuonsach = ?");

    foreach ($bookFines as $macs => $f) {
        // ctphieutra
        $stmtCtTra->bind_param("ssssdid", 
            $matra, $macs, $maphat_for_ct, 
            $f['tinhtrang'], $f['tienphat_damage'], 
            $songayquahan, $f['tienphat_late']
        );
        if (!$stmtCtTra->execute()) {
             throw new Exception("Lỗi tạo chi tiết phiếu trả: " . $stmtCtTra->error);
        }

        // ctphieuphat
        if ($f['total'] > 0) {
            $reasonText = [];
            if ($f['tienphat_late'] > 0) $reasonText[] = "Quá hạn $songayquahan ngày";
            if ($f['tienphat_damage'] > 0) {
                if ($f['lydo']) $reasonText[] = "Hư hỏng: " . $f['lydo'];
                else $reasonText[] = "Hư hỏng: " . $f['tinhtrang'];
            }
            $finalReason = implode(', ', $reasonText);

            $stmtCtPhat->bind_param("sssid", $maphat, $macs, $finalReason, $songayquahan, $f['total']);
            if (!$stmtCtPhat->execute()) {
                 throw new Exception("Lỗi tạo chi tiết phiếu phạt: " . $stmtCtPhat->error);
            }
        }

        // Cập nhật lại sách
        $sachTrangThai = 'SanSang';
        $sachTinhTrang = $f['tinhtrang']; // Ghi thẳng chữ Tiếng Việt (Tốt, Hư hỏng, Hư hỏng nặng, Mất sách) vào database

        if ($f['tinhtrang'] === 'Hư hỏng' || $f['tinhtrang'] === 'Hư hỏng nặng') {
             // Sách hư hỏng nhẹ hoặc nặng đều tạm ngưng cho mượn (để thủ thư cân nhắc)
             // Bạn có thể tùy biến: nếu hư nhẹ thì vẫn cho mượn -> SanSang, nhưng mặc định để ngưng thì an toàn
        } else if ($f['tinhtrang'] === 'Mất sách') {
             $sachTrangThai = 'Hong'; // Mất coi như hỏng không cho mượn được nữa
        }
        
        $stmtUpdateSach->bind_param("sss", $sachTrangThai, $sachTinhTrang, $macs);
        $stmtUpdateSach->execute();
    }

    $stmtCtTra->close();
    $stmtCtPhat->close();
    $stmtUpdateSach->close();

    // 4. Update phieumuon state
    $stmtPm = $conn->prepare("UPDATE phieumuon SET trangthai = 'DaTra' WHERE mamuon = ?");
    $stmtPm->bind_param("s", $mamuon);
    $stmtPm->execute();
    $stmtPm->close();

    $conn->commit();
    json_out('success', 'Xử lý trả sách và ghi nhận phạt thành công.');

} catch (Throwable $e) {
    if (isset($conn) && $conn->ping()) {
        $conn->rollback();
    }
    json_out('error', 'Exception: ' . $e->getMessage());
}
