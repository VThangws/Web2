<?php
require_once __DIR__ . '/../database/ConnectDB.php';

header('Content-Type: application/json; charset=utf-8');

$matheloai = isset($_GET['matheloai']) ? trim($_GET['matheloai']) : '';

try {
    $conn = ConnectDB::getInstance()->getConnection();

    if ($matheloai !== '') {
        $sql = 'SELECT madausach, tensach, anhbia FROM dausach WHERE matheloai = ? LIMIT 10';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $matheloai);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $sql = 'SELECT madausach, tensach, anhbia FROM dausach ORDER BY RAND() LIMIT 10';
        $result = $conn->query($sql);
    }

    $books = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $books[] = [
                'madausach' => $row['madausach'],
                'tensach'   => $row['tensach'],
                'anhbia'    => $row['anhbia'] ?? '',
            ];
        }
    }

    echo json_encode($books);
} catch (Throwable $e) {
    echo json_encode([]);
}
