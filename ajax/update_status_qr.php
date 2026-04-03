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
    $newStatus = '';

    // 3) Logic chuyển đổi trạng thái theo DB hiện tại
    // ChoDuyet -> DangMuon -> DaTra
    if ($currentStatus === 'ChoDuyet') {
        $newStatus = 'DangMuon';
    } elseif ($currentStatus === 'DangMuon') {
        $newStatus = 'DaTra';
    }

    if ($newStatus === '') {
        json_out('error', 'Phiếu đang ở trạng thái "' . $currentStatus . '" nên không thể cập nhật.');
    }

    // 4) Transaction
    $conn->begin_transaction();

    // 4.1) Update phieumuon + lưu manv
    // Nếu chuyển sang DangMuon mà thiếu ngày mượn/hết hạn thì tự set.
    $sqlUpdate = "UPDATE phieumuon SET trangthai = ?, manv = ?";
    if ($newStatus === 'DangMuon') {
        $sqlUpdate .= ", ngaymuon = COALESCE(ngaymuon, NOW()), ngayhethan = COALESCE(ngayhethan, DATE_ADD(NOW(), INTERVAL 14 DAY))";
    }
    $sqlUpdate .= " WHERE mamuon = ?";

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

    // 4.2) Nếu DaTra thì:
    // - tạo phieutra + ctphieutra
    // - nếu trả quá hạn thì tạo phieuphat + ctphieuphat
    // - trả sách về SanSang
    if ($newStatus === 'DaTra') {
        $returnedAt = (new DateTime())->format('Y-m-d H:i:s');
        $overdueDays = calc_overdue_days($ngayHetHan !== '' ? $ngayHetHan : null, $returnedAt);

        // Config: tiền phạt quá hạn / 1 cuốn / 1 ngày
        $FINE_PER_DAY = 5000; // đổi số này nếu bạn muốn mức phạt khác

        $matra = make_id_from_mamuon('PT', $mamuon);
        $maphat = make_id_from_mamuon('PP', $mamuon);
        $maphatForCt = $overdueDays > 0 ? $maphat : 'NONE';

        // Insert phieutra nếu chưa có
        $chkTra = $conn->prepare("SELECT matra FROM phieutra WHERE mamuon = ? LIMIT 1");
        if (!$chkTra) {
            $conn->rollback();
            json_out('error', 'Lỗi prepare check phieutra: ' . $conn->error);
        }
        $chkTra->bind_param('s', $mamuon);
        $chkTra->execute();
        $existingTra = $chkTra->get_result()->fetch_assoc();
        $chkTra->close();
        if ($existingTra && !empty($existingTra['matra'])) {
            $matra = (string)$existingTra['matra'];
        } else {
            $insTra = $conn->prepare("INSERT INTO phieutra (matra, mamuon, ngaytra, manv, tongtienphat) VALUES (?, ?, ?, ?, 0)");
            if (!$insTra) {
                $conn->rollback();
                json_out('error', 'Lỗi prepare INSERT phieutra: ' . $conn->error);
            }
            $insTra->bind_param('ssss', $matra, $mamuon, $returnedAt, $manv);
            if (!$insTra->execute()) {
                $err = $insTra->error;
                $insTra->close();
                $conn->rollback();
                json_out('error', 'Lỗi execute INSERT phieutra: ' . $err);
            }
            $insTra->close();
        }

        // Lấy danh sách cuốn sách của phiếu mượn
        $stmtCt = $conn->prepare("SELECT macuonsach FROM ctphieumuon WHERE mamuon = ?");
        if (!$stmtCt) {
            $conn->rollback();
            json_out('error', 'Lỗi prepare SELECT ctphieumuon: ' . $conn->error);
        }
        $stmtCt->bind_param('s', $mamuon);
        $stmtCt->execute();
        $rsCt = $stmtCt->get_result();
        $cuonList = $rsCt ? $rsCt->fetch_all(MYSQLI_ASSOC) : [];
        $stmtCt->close();

        // Nếu quá hạn, tạo phieuphat + ctphieuphat
        $totalFine = 0;
        if ($overdueDays > 0) {
            $totalFine = $overdueDays * $FINE_PER_DAY * max(1, count($cuonList));

            // Insert phieuphat (nếu chưa có)
            $chkPhat = $conn->prepare("SELECT maphat FROM phieuphat WHERE maphat = ? LIMIT 1");
            if (!$chkPhat) {
                $conn->rollback();
                json_out('error', 'Lỗi prepare check phieuphat: ' . $conn->error);
            }
            $chkPhat->bind_param('s', $maphat);
            $chkPhat->execute();
            $existsPhat = $chkPhat->get_result()->fetch_assoc();
            $chkPhat->close();

            if (!$existsPhat) {
                $insPhat = $conn->prepare("INSERT INTO phieuphat (maphat, madocgia, matra, ngaylap, tongtienphat, trangthai, ghichu) VALUES (?, ?, ?, NOW(), ?, 'ChuaNop', 'Trả quá hạn')");
                if (!$insPhat) {
                    $conn->rollback();
                    json_out('error', 'Lỗi prepare INSERT phieuphat: ' . $conn->error);
                }
                $totalFineDec = (string)$totalFine;
                $insPhat->bind_param('ssss', $maphat, $madocgia, $matra, $totalFineDec);
                if (!$insPhat->execute()) {
                    $err = $insPhat->error;
                    $insPhat->close();
                    $conn->rollback();
                    json_out('error', 'Lỗi execute INSERT phieuphat: ' . $err);
                }
                $insPhat->close();
            }

            // Insert ctphieuphat cho từng cuốn
            $insCtPhat = $conn->prepare("INSERT IGNORE INTO ctphieuphat (maphat, macuonsach, lydo, songayquahan, sotienphat) VALUES (?, ?, 'Trả quá hạn', ?, ?)");
            if (!$insCtPhat) {
                $conn->rollback();
                json_out('error', 'Lỗi prepare INSERT ctphieuphat: ' . $conn->error);
            }
            $finePerCopy = $overdueDays * $FINE_PER_DAY;
            $finePerCopyDec = (string)$finePerCopy;
            foreach ($cuonList as $row) {
                $macuonsach = (string)($row['macuonsach'] ?? '');
                if ($macuonsach === '') continue;
                $insCtPhat->bind_param('ssis', $maphat, $macuonsach, $overdueDays, $finePerCopyDec);
                $insCtPhat->execute();
            }
            $insCtPhat->close();
        }

        // Insert ctphieutra cho từng cuốn (maphat bắt buộc NOT NULL)
        $insCtTra = $conn->prepare("INSERT IGNORE INTO ctphieutra (matra, macuonsach, maphat, tinhtrang_sau, tienphathuha, songayquahan, tienphatquahan) VALUES (?, ?, ?, (SELECT tinhtrang FROM cuonsach WHERE macuonsach = ?), 0, ?, ?)");
        if (!$insCtTra) {
            $conn->rollback();
            json_out('error', 'Lỗi prepare INSERT ctphieutra: ' . $conn->error);
        }
        $finePerCopy = $overdueDays > 0 ? ($overdueDays * $FINE_PER_DAY) : 0;
        $finePerCopyDec = (string)$finePerCopy;
        foreach ($cuonList as $row) {
            $macuonsach = (string)($row['macuonsach'] ?? '');
            if ($macuonsach === '') continue;
            $insCtTra->bind_param('ssssis', $matra, $macuonsach, $maphatForCt, $macuonsach, $overdueDays, $finePerCopyDec);
            $insCtTra->execute();
        }
        $insCtTra->close();

        // Update tổng tiền phạt ở phieutra
        $updTraFine = $conn->prepare("UPDATE phieutra SET tongtienphat = ? WHERE matra = ?");
        if ($updTraFine) {
            $totalFineDec = (string)$totalFine;
            $updTraFine->bind_param('ss', $totalFineDec, $matra);
            $updTraFine->execute();
            $updTraFine->close();
        }

        // Trả sách về SanSang
        $reset = $conn->prepare("UPDATE cuonsach c JOIN ctphieumuon ct ON c.macuonsach = ct.macuonsach SET c.trangthai = 'SanSang' WHERE ct.mamuon = ?");
        if ($reset) {
            $reset->bind_param('s', $mamuon);
            $reset->execute();
            $reset->close();
        }
    }

    $conn->commit();

    json_out('success', 'Cập nhật thành công!', [
        'mamuon' => $mamuon,
        'oldStatus' => $currentStatus,
        'newStatus' => $newStatus,
        'manv' => $manv,
        'madocgia' => $madocgia,
    ]);

} catch (Throwable $e) {
    // Đảm bảo luôn trả JSON thay vì chết 500
    json_out('error', 'Server error: ' . $e->getMessage());
}