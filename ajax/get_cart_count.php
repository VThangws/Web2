<?php
session_start();
$total_items = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total_items += $item['soluong'];
    }
}
echo json_encode(['total_items' => $total_items]);
exit;
?>