<?php
require 'database/ConnectDB.php';
$conn = ConnectDB::getInstance()->getConnection();
if ($conn->query('ALTER TABLE taikhoan ADD COLUMN trangthai INT DEFAULT 1')) {
    echo "OK";
} else {
    echo "Error: " . $conn->error;
}
