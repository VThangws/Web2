<?php
require_once __DIR__ . '/../database/ConnectDB.php';

header('Content-Type: application/json; charset=utf-8');

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
if ($q === '') {
    echo json_encode([]);
    exit;
}

try {
    $conn = ConnectDB::getInstance()->getConnection();
    $like = '%' . $q . '%';
    $sql = 'SELECT madausach, tensach, dongia, anhbia FROM dausach WHERE tensach LIKE ? ORDER BY tensach LIMIT 10';
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode([]);
        exit;
    }
    $stmt->bind_param('s', $like);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                'madausach' => $row['madausach'],
                'tensach'   => $row['tensach'],
                'dongia'    => isset($row['dongia']) ? (int)$row['dongia'] : 0,
                'anhbia'    => $row['anhbia'] ?? '',
            ];
        }
    }
    $stmt->close();
    echo json_encode($data);
} catch (Throwable $e) {
    echo json_encode([]);
}
