<?php
session_start();
$total_items = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

echo json_encode(['total_items' => $total_items]);
exit;
?>