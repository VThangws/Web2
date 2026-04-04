<?php
require_once __DIR__ . '/../model/DocGia.php';
require_once __DIR__ . '/../database/ConnectDB.php';

if(session_status() == PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json');

$docgia = $_SESSION['docgia'] ?? null;
if (!$docgia) {
    echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
    exit;
}

$id = $_GET['id'] ?? ($_GET['mamuon'] ?? '');
$type = $_GET['type'] ?? 'muon';

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'Thiếu dữ liệu']);
    exit;
}

try {
    $conn = ConnectDB::getInstance()->getConnection();
    $madocgia = $docgia->getMadocgia();

    if ($type === 'muon') {
        $stmt1 = $conn->prepare("SELECT mamuon FROM phieumuon WHERE mamuon = ? AND madocgia = ?");
        $stmt1->bind_param("ss", $id, $madocgia);
        $stmt1->execute();
        if ($stmt1->get_result()->num_rows === 0) {
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy phiếu mượn']);
            exit;
        }
        $stmt1->close();
        $stmt = $conn->prepare("SELECT ct.macuonsach, ds.tensach, ct.tinhtrang_truoc FROM ctphieumuon ct JOIN cuonsach cs ON cs.macuonsach=ct.macuonsach JOIN dausach ds ON ds.madausach=cs.madausach WHERE ct.mamuon = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        echo json_encode(['success' => true, 'type' => 'muon', 'data' => $items]);

    } elseif ($type === 'tra') {
        $stmt1 = $conn->prepare("SELECT pt.matra FROM phieutra pt JOIN phieumuon pm ON pt.mamuon=pm.mamuon WHERE pt.matra = ? AND pm.madocgia = ?");
        $stmt1->bind_param("ss", $id, $madocgia);
        $stmt1->execute();
        if ($stmt1->get_result()->num_rows === 0) {
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy phiếu trả']);
            exit;
        }
        $stmt1->close();
        $stmt = $conn->prepare("SELECT ct.macuonsach, ds.tensach, ct.tinhtrang_sau, ct.songayquahan, ct.tienphatquahan, ct.tienphathuha FROM ctphieutra ct JOIN cuonsach cs ON cs.macuonsach=ct.macuonsach JOIN dausach ds ON ds.madausach=cs.madausach WHERE ct.matra = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        echo json_encode(['success' => true, 'type' => 'tra', 'data' => $items]);

    } elseif ($type === 'phat') {
        $stmt1 = $conn->prepare("SELECT maphat, trangthai, tongtienphat FROM phieuphat WHERE maphat = ? AND madocgia = ?");
        $stmt1->bind_param("ss", $id, $madocgia);
        $stmt1->execute();
        $phat_data = $stmt1->get_result()->fetch_assoc();
        if (!$phat_data) {
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy phiếu phạt']);
            exit;
        }
        $stmt1->close();
        $stmt = $conn->prepare("SELECT ct.macuonsach, ds.tensach, ct.lydo, ct.songayquahan, ct.sotienphat FROM ctphieuphat ct JOIN cuonsach cs ON cs.macuonsach=ct.macuonsach JOIN dausach ds ON ds.madausach=cs.madausach WHERE ct.maphat = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        echo json_encode(['success' => true, 'type' => 'phat', 'data' => $items, 'header' => $phat_data]);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
