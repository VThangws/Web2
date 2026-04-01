<?php
// filepath: d:\web2\Web2\ajax\borrow_action.php

declare(strict_types=1);

// IMPORTANT: load class definitions BEFORE session_start() to avoid __PHP_Incomplete_Class
require_once __DIR__ . '/../model/DocGia.php';
require_once __DIR__ . '/../model/TaiKhoan.php';
require_once __DIR__ . '/../database/ConnectDB.php';

session_start();

$conn = ConnectDB::getInstance()->getConnection(); // mysqli
$conn->set_charset('utf8mb4');

function redirect(string $url): void {
    header('Location: ' . $url);
    exit;
}

/**
 * Sinh mã mượn dạng VARCHAR
 * Ví dụ: MU2026040215301012
 */
function generateMaMuon(): string {
    // ymd => 6 ký tự, random => 4 ký tự
    $date = date('ymd'); // 250402
    $rand = strtoupper(substr(bin2hex(random_bytes(3)), 0, 4)); // 4 hex chars
    return "MU{$date}{$rand}";
}
/**
 * Chuẩn hoá type
 */
function normalizeType(?string $type): string {
    $type = strtoupper(trim((string)$type));
    return in_array($type, ['ONLINE', 'ON_SITE'], true) ? $type : 'ONLINE';
}

/**
 * Validate pickup_date cho ONLINE
 */
function validatePickupDate(?string $pickup_date): ?string {
    if ($pickup_date === null || $pickup_date === '') return null;
    $d = DateTime::createFromFormat('Y-m-d', $pickup_date);
    if (!$d) return null;
    // min: today
    $today = new DateTime('today');
    if ($d < $today) return null;
    return $d->format('Y-m-d');
}

// 1) Auth + cart
$docgia = $_SESSION['docgia'] ?? null;
if (!$docgia || !is_object($docgia) || !method_exists($docgia, 'getMadocgia')) {
    redirect('../index.php?page=login');
}

$madocgia = (string)$docgia->getMadocgia();
if ($madocgia === '') {
    redirect('../index.php?page=login');
}

$cart = $_SESSION['cart'] ?? [];
if (!is_array($cart) || empty($cart)) {
    redirect('../index.php?page=cart');
}

// 2) Read POST
$type = normalizeType($_POST['type'] ?? 'ONLINE');
$status = ($type === 'ONLINE') ? 'PENDING' : 'ACTIVE';

$pickup_date = validatePickupDate($_POST['pickup_date'] ?? null);
if ($type === 'ONLINE' && !$pickup_date) {
    $_SESSION['error'] = 'Ngày hẹn lấy không hợp lệ.';
    redirect('../index.php?page=checkout');
}
// ON_SITE: không cần pickup_date
if ($type !== 'ONLINE') {
    $pickup_date = null;
}

$conn->begin_transaction();

try {
    // 3) Tạo phiếu mượn (mamuon là VARCHAR)
    $mamuon = generateMaMuon();

    // NOTE: câu INSERT này giả định bảng phieumuon có đúng các cột dưới đây.
    // Nếu DB bạn khác tên cột, gửi CREATE TABLE phieumuon để mình map lại.
    $stmt = $conn->prepare("
        INSERT INTO phieumuon (mamuon, madocgia, loaimuon, trangthai, ngayhenlay, ngaymuon)
        VALUES (?, ?, ?, ?, ?, NOW())
    ");
    if (!$stmt) {
        throw new Exception('Prepare phieumuon failed: ' . $conn->error);
    }

    // pickup_date có thể null => bind vẫn là string, pass null OK với mysqli (sẽ thành NULL)
    $stmt->bind_param("sssss", $mamuon, $madocgia, $type, $status, $pickup_date);
    if (!$stmt->execute()) {
        throw new Exception("Không thể tạo phiếu mượn: " . $stmt->error);
    }
    $stmt->close();

    // 4) Prepare statement dùng lại
    $insertCT = $conn->prepare("
        INSERT INTO ctphieumuon (mamuon, macuonsach, tinhtrang_truoc)
        VALUES (?, ?, ?)
    ");
    if (!$insertCT) {
        throw new Exception('Prepare ctphieumuon failed: ' . $conn->error);
    }

    // Update dùng lại, tránh prepare trong loop
    $updateBook = $conn->prepare("
        UPDATE cuonsach
        SET trangthai = 'DangMuon'
        WHERE macuonsach = ? AND trangthai = 'SanSang'
    ");
    if (!$updateBook) {
        throw new Exception('Prepare update cuonsach failed: ' . $conn->error);
    }

    // 5) Duyệt giỏ và “giữ” cuốn sách (chọn đủ số lượng cuốn sẵn sàng)
    foreach ($cart as $madausach => $item) {
        $madausach = (string)$madausach;

        $soluong_muon = (int)($item['soluong'] ?? 1);
        if ($soluong_muon <= 0) continue;

        $limit = max(1, $soluong_muon);

        // Lấy danh sách cuốn sách sẵn sàng
        // NOTE: limit là int đã cast nên an toàn để nội suy.
        $checkInv = $conn->prepare("
            SELECT macuonsach, tinhtrang
            FROM cuonsach
            WHERE madausach = ? AND trangthai = 'SanSang'
            LIMIT $limit
        ");
        if (!$checkInv) {
            throw new Exception('Prepare check inventory failed: ' . $conn->error);
        }

        $checkInv->bind_param("s", $madausach);
        if (!$checkInv->execute()) {
            throw new Exception("Lỗi kiểm tra tồn kho: " . $checkInv->error);
        }

        $res = $checkInv->get_result();
        if (!$res) {
            throw new Exception("Lỗi lấy kết quả tồn kho: " . $checkInv->error);
        }

        if ($res->num_rows < $soluong_muon) {
            $ten = $item['tensach'] ?? $madausach;
            throw new Exception("Sách '{$ten}' không đủ số lượng sẵn sàng!");
        }

        while ($row = $res->fetch_assoc()) {
            $macuonsach = (string)($row['macuonsach'] ?? '');
            if ($macuonsach === '') {
                throw new Exception('Thiếu macuonsach khi kiểm tra tồn kho.');
            }
            $tinhtrang_truoc = (string)($row['tinhtrang'] ?? '');

            // Update trạng thái cuốn sách => DangMuon
            $updateBook->bind_param("s", $macuonsach);
            if (!$updateBook->execute()) {
                throw new Exception("Không thể cập nhật trạng thái cuốn sách {$macuonsach}: " . $updateBook->error);
            }
            if ($updateBook->affected_rows !== 1) {
                // Trường hợp race condition: có người khác mượn trước
                throw new Exception("Cuốn sách {$macuonsach} không còn sẵn sàng để mượn. Vui lòng thử lại.");
            }

            // Insert CT (tất cả đều varchar => sss)
            $insertCT->bind_param("sss", $mamuon, $macuonsach, $tinhtrang_truoc);
            if (!$insertCT->execute()) {
                throw new Exception("Không thể lưu chi tiết phiếu mượn: " . $insertCT->error);
            }
        }

        $checkInv->close();
    }

    $insertCT->close();
    $updateBook->close();

    $conn->commit();

    // Clear cart after success
    unset($_SESSION['cart']);

    redirect("../index.php?page=borrow_success&id=" . urlencode($mamuon));

} catch (Throwable $e) {
    $conn->rollback();
    // Ghi rõ để bạn thấy nguyên nhân quay về cart
    $_SESSION['error'] = $e->getMessage();
    redirect('../index.php?page=cart');
}