<?php
// Lấy thông tin từ Session và POST (từ trang checkout gởi qua)
$user_id = $_SESSION['user_id'] ?? null;
$user_name = $_SESSION['user_name'] ?? 'Khách hàng'; // Giả sử ní lưu tên trong session
$borrow_type = $_POST['type'] ?? 'ONLINE';
$pickup_date = $_POST['pickup_date'] ?? null;
$cart = $_SESSION['cart'] ?? [];

// Tính toán hạn trả mặc định (ví dụ 14 ngày cho mượn mang về)
$due_date = ($borrow_type === 'ONLINE') ? date('d/m/Y', strtotime($pickup_date . ' + 14 days')) : 'Cuối ngày hôm nay';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm benefit-card p-4">
                <h3 class="fw-bold text-center mb-4" style="color: #20c997;">THÔNG TIN PHIẾU MƯỢN</h3>
                
                <form action="ajax/borrow_action.php" method="POST">
                    <input type="hidden" name="type" value="<?= $borrow_type ?>">
                    <input type="hidden" name="pickup_date" value="<?= $pickup_date ?>">

                    <div class="row g-4">
                        <div class="col-md-5 border-end">
                            <h5 class="fw-bold mb-3 border-bottom pb-2"><i class="fa-solid fa-user-tag me-2"></i>Người mượn</h5>
                            <div class="mb-3">
                                <label class="small text-muted">Họ và tên:</label>
                                <p class="fw-bold mb-0"><?= htmlspecialchars($user_name) ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="small text-muted">Mã độc giả:</label>
                                <p class="fw-bold mb-0">DG-<?= str_pad($user_id, 5, '0', STR_PAD_LEFT) ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="small text-muted">Loại hình mượn:</label>
                                <p class="mb-0">
                                    <span class="badge <?= $borrow_type === 'ONLINE' ? 'bg-primary' : 'bg-success' ?>">
                                        <?= $borrow_type === 'ONLINE' ? 'Mang về' : 'Đọc tại chỗ' ?>
                                    </span>
                                </p>
                            </div>
                            <div class="bg-light p-3 rounded-3 mt-3">
                                <label class="small text-muted d-block">Dự kiến trả sách:</label>
                                <strong class="text-danger"><?= $due_date ?></strong>
                            </div>
                        </div>

                        <div class="col-md-7">
                            <h5 class="fw-bold mb-3 border-bottom pb-2"><i class="fa-solid fa-book-bookmark me-2"></i>Sách mượn</h5>
                            <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                                <table class="table table-borderless align-middle">
                                    <tbody>
                                        <?php foreach ($cart as $ma => $item): ?>
                                        <tr>
                                            <td width="60">
                                                <img src="/assets/img/books/<?= $item['anhbia'] ?>" class="rounded" width="50" alt="Bìa">
                                            </td>
                                            <td>
                                                <div class="fw-bold small"><?= $item['tensach'] ?></div>
                                                <div class="text-muted" style="font-size: 0.75rem;">Số lượng: <?= $item['soluong'] ?></div>
                                            </td>
                                            <td class="text-end fw-bold text-danger">
                                                <?= number_format($item['dongia'] * $item['soluong'], 0, ',', '.') ?> đ
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <hr>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="fw-bold">Tiền cọc ước tính:</span>
                                <h4 class="fw-bold text-danger mb-0"><?= number_format($total_price ?? 0, 0, ',', '.') ?> VNĐ</h4>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-5">
                        <button type="submit" class="btn btn-lg btn-success px-5 rounded-pill fw-bold shadow">
                            XÁC NHẬN LẬP PHIẾU
                        </button>
                        <p class="small text-muted mt-2">Bằng cách nhấn xác nhận, ní đồng ý với nội quy mượn trả của ASAG Library.</p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>