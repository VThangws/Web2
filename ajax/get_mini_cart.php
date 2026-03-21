<?php
session_start();
$html = '';
$total_items = 0;
$total_price = 0;

if (empty($_SESSION['cart'])) {
    $html .= '<div class="text-center py-5 text-muted">';
    $html .= '<i class="fa-solid fa-cart-arrow-down mb-3" style="font-size: 3rem;"></i><br>Giỏ hàng đang trống</div>';
} else {
    foreach ($_SESSION['cart'] as $ma => $item) {
        $total_items += $item['soluong'];
        $total_price += ($item['dongia'] * $item['soluong']);
        
        $anh = htmlspecialchars($item['anhbia'] ?: 'demo.jpg');
        $ten = htmlspecialchars($item['tensach']);
        $gia = number_format($item['dongia'], 0, ',', '.') . ' đ';
        $sl = $item['soluong'];

        // Khóa mờ nút Trừ nếu số lượng là 1
        $disabled_minus = ($sl <= 1) ? 'disabled' : '';

        $html .= '
        <div class="d-flex align-items-center mb-3 pb-3 border-bottom position-relative">
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
    
    // Khuyến mãi thêm dòng tính Tổng Tiền ở đáy giỏ Mini
    $html .= '<div class="d-flex justify-content-between fw-bold fs-6 mt-3 pt-2">
                <span>Tổng tiền:</span>
                <span class="text-danger">'.number_format($total_price, 0, ',', '.').' đ</span>
              </div>';
}

echo json_encode([
    'html' => $html,
    'total_items' => $total_items
]);
?>