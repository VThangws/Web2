<?php
session_start();
if (!empty($_SESSION['cart'])) {
    require_once __DIR__ . '/../database/ConnectDB.php'; // Đảm bảo đường dẫn đúng
    $conn = ConnectDB::getInstance()->getConnection();
    
    // Lấy danh sách các mã sách đang có trong giỏ hàng
    $ma_sach_arr = array_keys($_SESSION['cart']);
    // Ép kiểu để bỏ vào câu SQL: 'DS01', 'DS02'...
    $ma_sach_string = "'" . implode("','", $ma_sach_arr) . "'"; 

    // CHÚ Ý: Đổi tên bảng 'dausach', cột mã 'madausach', cột giá 'giamuon' cho khớp CSDL của ông
    $sql_update_price = "SELECT madausach, giamuon FROM dausach WHERE madausach IN ($ma_sach_string)";
    $result_update = $conn->query($sql_update_price);

    if ($result_update && $result_update->num_rows > 0) {
        while ($row = $result_update->fetch_assoc()) {
            $ma = $row['madausach'];
            if (isset($_SESSION['cart'][$ma])) {
                // Đè giá mới từ DB vào cái giá cũ mèm trong Session
                $_SESSION['cart'][$ma]['dongia'] = $row['giamuon']; 
            }
        }
    }
}
$html = '';
$total_items = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'soluong')) : 0;

if (empty($_SESSION['cart'])) {
    $html .= '<div class="text-center py-5 text-muted">';
    $html .= '<i class="fa-solid fa-cart-arrow-down mb-3" style="font-size: 3rem;"></i><br>Giỏ hàng đang trống</div>';
} else {
    $html .= '<div class="custom-cart-scroll" style="max-height: calc(100vh - 195px) !important; overflow-y: auto; overflow-x: hidden;">';

    $count = 0;
    $total_cart = count($_SESSION['cart']);

    foreach ($_SESSION['cart'] as $ma => $item) {
        $count++;
        $anh = htmlspecialchars($item['anhbia'] ?: 'demo.jpg');
        $ten = htmlspecialchars($item['tensach']);
        $gia = number_format($item['dongia'], 0, ',', '.') . ' đ';
        $sl = $item['soluong'];

        // Khóa mờ nút Trừ nếu số lượng là 1
        $disabled_minus = ($sl <= 1) ? 'disabled' : '';

        $border_class = ($count === $total_cart) ? 'mb-1' : 'mb-3 pb-3 border-bottom';

        $html .= '
        <div class="d-flex align-items-center position-relative ' . $border_class . '">
            <img src="/assets/img/books/'.$anh.'" alt="Bìa sách" class="rounded border shadow-sm" style="width: 60px; height: 85px; object-fit: cover;">
            <div class="ms-3 flex-grow-1">
                <h6 class="mb-1 text-dark fw-bold text-truncate" style="font-size: 0.9rem; max-width: 170px;">'.$ten.'</h6>
                <div class="text-danger fw-bold small mb-2">'.$gia.'</div>
                
                <div class="input-group input-group-sm" style="width: 90px;">
                    <button class="btn btn-outline-secondary btn-mini-update" data-id="'.$ma.'" data-action="minus" '.$disabled_minus.'>-</button>
                    <input type="text" class="form-control text-center bg-white px-1" value="'.$sl.'" readonly>
                    <button class="btn btn-outline-secondary btn-mini-update" data-id="'.$ma.'" data-action="plus">+</button>
                </div>
            </div>
            
            <button class="btn btn-link text-danger p-0 position-absolute top-0 end-0 mt-1 btn-mini-update" data-id="'.$ma.'" data-action="remove">
                <i class="fa-solid fa-trash-can fs-6"></i>
            </button>
        </div>';
    }

    $html .= '</div>';

}

echo json_encode([
    'html' => $html,
    'total_items' => $total_items
]);
?>