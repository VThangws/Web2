<?php
// Tính tổng tiền và kiểm tra giỏ rỗng
$total_price = 0;
$cart_empty = empty($_SESSION['cart']);
?>

<div class="container-md mt-4">
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

<?php if (!isset($docgia)): ?>
    <div class="container text-center py-5" style="min-height: 50vh; margin-top: 50px;">
        <i class="fa-solid fa-cart-shopping text-muted" style="font-size: 5rem; margin-bottom: 20px;"></i>
        <h3 class="text-danger mb-3">Bạn chưa đăng nhập!</h3>
        <p class="text-muted fs-5">Vui lòng đăng nhập tài khoản để xem chi tiết giỏ hàng và tiến hành thanh toán nhé.</p>
        <button class="btn btn-cart-primary mt-3" style="border-radius: 8px; padding: 10px 20px;" onclick="document.getElementById('openLogin').click()">
            Mở form Đăng Nhập ngay
        </button>
    </div>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(() => {
                const btnOpen = document.getElementById("openLogin");
                if (btnOpen) {
                    btnOpen.click();
                }
            }, 400); 
        });
    </script>

<?php else: ?>

<div class="container my-4">
    <div class="row">
        <div class="col-lg-8 mb-4" id="cart-left">
            <h4 class="mb-4 fw-bold text-dark">GIỎ HÀNG CỦA BẠN</h4>

            <div class="cart-scroll-area border rounded p-4 bg-white shadow-sm" style="max-height: 65vh; overflow-y: auto;">
                <?php if ($cart_empty): ?>
                    <div class="text-center py-5">
                        <i class="fa-solid fa-cart-shopping fs-5 text-dark" style="font-size: 4rem;"></i>
                        <h5 class="text-muted mt-3">Giỏ hàng của bạn đang trống!</h5>
                    </div>
                <?php else: ?>
                    <div class="d-flex align-items-center mb-3 border-bottom pb-2 text-muted fw-bold">
                        <div style="width: 5%;" class="text-center">
                            <input class="form-check-input custom-checkbox" type="checkbox" id="check-all" style="width: 18px; height: 18px; cursor: pointer;">
                        </div>
                        <div style="width: 45%;">Chọn tất cả</div>
                        <div style="width: 20%;" class="text-center">Đơn giá</div>
                        <div style="width: 20%;" class="text-center">Số lượng</div>
                        <div style="width: 10%;" class="text-end">Xóa</div>
                    </div>

                    <?php foreach ($_SESSION['cart'] as $ma => $item): ?>
                        <?php 
                            // Lấy tồn kho (nếu add_to_cart chưa lưu thì để 0)
                            $tonkho = $item['tonkho'] ?? 0; 
                            
                            // CHỐT CHẶN 1 (PHP): Nếu số lượng <= 1 thì gán chữ 'disabled' vào nút trừ
                            $disabled_minus = ($item['soluong'] <= 1) ? 'disabled' : '';
                        ?>
                        <div class="cart-item d-flex align-items-center border-bottom py-3" id="item-<?= $ma ?>">
                            
                            <div style="width: 5%;" class="text-center">
                                <input class="form-check-input custom-checkbox item-checkbox" type="checkbox" value="<?= $ma ?>" data-price="<?= $item['dongia'] ?>" style="width: 18px; height: 18px; cursor: pointer;">
                            </div>

                            <div class="d-flex align-items-center" style="width: 45%;">
                                <img src="/assets/img/books/<?= htmlspecialchars($item['anhbia'] ?: 'demo.jpg') ?>" class="img-fluid rounded shadow-sm" style="width: 70px; height: 100px; object-fit: cover;" alt="Bìa sách">
                                <div class="ms-3">
                                    <h6 class="mb-1 text-dark fw-bold"><?= htmlspecialchars($item['tensach']) ?></h6>
                                    <small class="text-muted">Kho còn: <?= $tonkho ?> cuốn</small>
                                </div>
                            </div>
                            
                            <div style="width: 20%;" class="text-center text-danger fw-bold">
                                <?= number_format($item['dongia'], 0, ',', '.') ?> đ
                            </div>
                            
                            <div style="width: 20%;" class="d-flex justify-content-center">
                                <div class="input-group input-group-sm" style="width: 100px;">
                                    <button class="btn btn-outline-secondary btn-update-cart" data-id="<?= $ma ?>" data-action="minus" <?= $disabled_minus ?>>-</button>
                                    
                                    <input type="text" class="form-control text-center bg-white qty-input" id="qty-<?= $ma ?>" value="<?= $item['soluong'] ?>" data-max="<?= $tonkho ?>" readonly>
                                    
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
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Đã chọn:</span>
                    <strong class="text-dark"><span id="total-checked-count">0</span> sản phẩm</strong>
                </div>
        
                <div class="d-flex justify-content-between mb-4 fs-5">
            <span class="text-muted">Tổng cộng:</span>
            <strong class="text-danger" id="total-price">0 VNĐ</strong>
                </div>

                <a href="/index.php?page=checkout" class="btn btn-cart-primary w-100 mb-3 py-2 fs-6 <?= $cart_empty ? 'disabled' : '' ?>">
                    XÁC NHẬN MƯỢN SÁCH
                </a>
                <a href="/index.php?page=books" class="btn btn-cart-secondary w-100 py-2 fs-6">
                    TIẾP TỤC TÌM KIẾM SÁCH
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // =====================================
    // 1. LOGIC CHECKBOX & TÍNH TIỀN
    // =====================================
    const checkAll = document.getElementById('check-all');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    const totalPriceEl = document.getElementById('total-price');
    const totalCountEl = document.getElementById('total-checked-count');

    function calculateTotal() {
        let total = 0;
        let count = 0;
        itemCheckboxes.forEach(cb => {
            if (cb.checked) {
                let qtyInput = cb.closest('.cart-item').querySelector('.qty-input');
                let qty = parseInt(qtyInput.value);
                let price = parseInt(cb.getAttribute('data-price'));
                total += (qty * price);
                count += qty;
            }
        });
        if(totalPriceEl) totalPriceEl.innerText = new Intl.NumberFormat('vi-VN').format(total) + ' VNĐ';
        if(totalCountEl) totalCountEl.innerText = count;
    }

    if (checkAll) {
        checkAll.addEventListener('change', function() {
            itemCheckboxes.forEach(cb => cb.checked = this.checked);
            calculateTotal();
        });
    }

    itemCheckboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            const allChecked = document.querySelectorAll('.item-checkbox:checked').length === itemCheckboxes.length;
            if(checkAll) checkAll.checked = allChecked;
            calculateTotal();
        });
    });

    // =====================================
    // 2. LOGIC TĂNG GIẢM SỐ LƯỢNG (CÓ CHẶN TỒN KHO & CHẶN SỐ 1)
    // =====================================
    document.querySelectorAll('.btn-update-cart').forEach(button => {
        button.addEventListener('click', function() {
            let madausach = this.getAttribute('data-id');
            let action = this.getAttribute('data-action'); 
            
            // CHỐT CHẶN 2 (JAVASCRIPT): Chặn ngay từ client
            let inputField = document.getElementById('qty-' + madausach);
            if (inputField) {
                let currentQty = parseInt(inputField.value);
                
                if (action === 'plus') {
                    let maxStock = parseInt(inputField.getAttribute('data-max'));
                    if (currentQty >= maxStock) {
                        alert('Sách này trong kho chỉ còn ' + maxStock + ' cuốn Sẵn Sàng!');
                        return; 
                    }
                } else if (action === 'minus') {
                    if (currentQty <= 1) {
                        // Nếu đang là 1 mà cố tình bấm trừ (hoặc xóa code disabled) thì chặn luôn, ko gửi fetch
                        return; 
                    }
                }
            }

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
                    if (action === 'remove' || data.item_qty === 0) {
                        document.getElementById('item-' + madausach).remove();
                    } else {
                        document.getElementById('qty-' + madausach).value = data.item_qty;
                        
                        // Cập nhật lại trạng thái của nút "Trừ"
                        let minusBtn = document.querySelector(`.btn-update-cart[data-id="${madausach}"][data-action="minus"]`);
                        if(minusBtn) {
                            if (data.item_qty <= 1) {
                                minusBtn.disabled = true; // Rớt về 1 thì khóa nút lại
                            } else {
                                minusBtn.disabled = false; // Lên 2 thì mở khóa nút ra
                            }
                        }
                    }
                    
                    calculateTotal(); // Tự tính lại tiền nếu sách đang được tick

                    let cartBadge = document.getElementById('cart-badge');
                    if(cartBadge) cartBadge.innerText = data.total_items;
                    if(data.total_items === 0) location.reload();
                } else {
                    alert('LỖI: ' + data.message);
                }
            })
            .catch(err => console.error(err));
        });
    });
});
</script>
<?php endif; ?>