<?php
require_once __DIR__ . '/database/ConnectDB.php';
$conn = ConnectDB::getInstance()->getConnection();

$sql = "ALTER TABLE phieumuon ADD COLUMN loaimuon VARCHAR(20) DEFAULT 'ONLINE' AFTER madocgia";
if ($conn->query($sql)) {
    echo "Thêm cột loaimuon thành công!\n";
} else {
    echo "Lỗi: " . $conn->error . "\n";
}
