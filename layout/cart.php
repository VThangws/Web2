<?php
// Tính tổng tiền và kiểm tra giỏ rỗng
$total_price = 0;
$cart_empty = empty($_SESSION['cart']);
?>

<div class="container-md mt-3">
    <div class="border rounded py-2 px-4 d-flex align-items-center bg-white shadow-sm">
        <div class="me-auto">
            <p class="mb-0">
                <a href="/index.php" class="text-decoration-none fw-semibold" style="color: #20c997;">Trang chủ</a>
                <span class="mx-2 text-muted"><i class="fa-solid fa-angle-right"></i></span>
                <span class="text-dark fw-bold">Giỏ hàng</span>
            </p>
        </div>
    </div>
</div>

<div class="container my-4">
    <div class="row">
        <div class="col-lg-8 mb-4" id="cart-left">
            <h4 class="mb-4 fw-bold text-dark">GIỎ HÀNG CỦA BẠN</h4>

            <div class="cart-scroll-area border rounded p-4 bg-white shadow-sm">
                <?php if ($cart_empty): ?>
                    <div class="text-center py-5">
                        <i class="fa-solid fa-cart-shopping fs-5 text-dark" style="font-size: 4rem;"></i>
                        <h5 class="text-muted">Giỏ hàng của bạn đang trống!</h5>

                    </div>
                <?php else: ?>
                    <div class="d-flex align-items-center mb-3 border-bottom pb-2 text-muted fw-bold">
                        <div style="width: 50%;">Sản phẩm</div>
                        <div style="width: 20%;" class="text-center">Đơn giá</div>
                        <div style="width: 20%;" class="text-center">Số lượng</div>
                        <div style="width: 10%;" class="text-end">Xóa</div>
                    </div>

                    <?php foreach ($_SESSION['cart'] as $ma => $item): ?>
                        <?php $total_price += ($item['dongia'] * $item['soluong']); ?>
                        <div class="cart-item d-flex align-items-center border-bottom py-3" id="item-<?= $ma ?>">
                            <div class="d-flex align-items-center" style="width: 50%;">
                                <img src="/assets/img/books/<?= htmlspecialchars($item['anhbia'] ?: 'demo.jpg') ?>" class="img-fluid rounded shadow-sm" style="width: 70px; height: 100px; object-fit: cover;" alt="Bìa sách">
                                <div class="ms-3">
                                    <h6 class="mb-1 text-dark fw-bold"><?= htmlspecialchars($item['tensach']) ?></h6>
                                </div>
                            </div>
                            
                            <div style="width: 20%;" class="text-center text-danger fw-bold">
                                <?= number_format($item['dongia'], 0, ',', '.') ?> đ
                            </div>
                            
                            <div style="width: 20%;" class="d-flex justify-content-center">
                                <div class="input-group input-group-sm" style="width: 100px;">
                                    <button class="btn btn-outline-secondary btn-update-cart" data-id="<?= $ma ?>" data-action="minus">-</button>
                                    <input type="text" class="form-control text-center bg-white" id="qty-<?= $ma ?>" value="<?= $item['soluong'] ?>" readonly>
                                    <button class="btn btn-outline-secondary btn-update-cart" data-id="<?= $ma ?>" data-action="plus">+</button>
                                </div>
                            </div>

                            <div style="width: 10%;" class="text-end">
                                <button class="btn btn-link text-danger btn-update-cart p-0" data-id="<?= $ma ?>" data-action="remove">
                                    <i class="fa-solid fa-trash fs-5"></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-4" id="order-summary">
            <div class="border p-4 rounded shadow-sm bg-white position-sticky" style="top: 100px;">
                <h5 class="mb-4 fw-bold border-bottom pb-2">Tóm tắt đơn hàng</h5>
                <div class="d-flex justify-content-between mb-4 fs-5">
                    <span class="text-muted">Tổng cộng:</span>
                    <strong class="text-danger" id="total-price"><?= number_format($total_price, 0, ',', '.') ?> VNĐ</strong>
                </div>
                
                <a href="/index.php?page=pay" class="btn btn-cart-primary w-100 mb-3 py-2 fs-6">
                    THANH TOÁN
                </a>
                <a href="/index.php?page=books" class="btn btn-cart-secondary w-100 py-2 fs-6">
                    TIẾP TỤC TÌM KIẾM SÁCH
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.btn-update-cart').forEach(button => {
    button.addEventListener('click', function() {
        let madausach = this.getAttribute('data-id');
        let action = this.getAttribute('data-action'); // Lấy hành động là 'plus', 'minus' hay 'remove'
        
        let formData = new FormData();
        formData.append('madausach', madausach);
        formData.append('action', action);

        fetch('/ajax/update_cart.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.status === 'success') {
                // Xóa sách khỏi giao diện nếu hành động là Xóa hoặc số lượng rớt về 0
                if (action === 'remove' || data.item_qty === 0) {
                    document.getElementById('item-' + madausach).remove();
                } else {
                    // Cập nhật số lượng trên ô input
                    document.getElementById('qty-' + madausach).value = data.item_qty;
                }

                // Cập nhật cục Tổng tiền bên phải
                document.getElementById('total-price').innerText = data.total_price;

                // Cập nhật số đỏ trên cái Header
                let cartBadge = document.getElementById('cart-badge');
                if(cartBadge) cartBadge.innerText = data.total_items;

                // F5 lại trang để hiện giao diện "Giỏ hàng trống" nếu xóa sạch đồ
                if(data.total_items === 0) location.reload();
            }
        });
    });
});
</script>
<style>
/* CSS NÚT GIỎ HÀNG */
.btn-cart-primary {
    background-color: #20c997;
    color: white;
    border: 1px solid #20c997;
    border-radius: 50px; /* Bo tròn viên thuốc */
    transition: background-color 0.2s ease, border-color 0.2s ease;
    font-weight: 900;
}
.btn-cart-primary:hover {
    background-color: #1aa179;
    border-color: #1aa179;
    color: white;
}

.btn-cart-secondary {
    background-color: white;
    color: #20c997;
    border: 1px solid #20c997;
    border-radius: 50px;
    transition: background-color 0.2s ease, color 0.2s ease;
    font-weight: bold;
}
.btn-cart-secondary:hover {
    background-color: #eafaf5; 
    color: #1aa179;
    border-color: #1aa179;
}
</style>