<?php
session_start();
$total_items = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'soluong')) : 0;

echo json_encode(['total_items' => $total_items]);
exit;
?>