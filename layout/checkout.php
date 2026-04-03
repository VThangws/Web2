<?php
// Nhận mảng các mã sách được chọn từ cart gửi qua
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_items'])) {
    $_SESSION['checkout_items'] = $_POST['selected_items'];
}

// Nếu người dùng vào thẳng link checkout mà chưa chọn sách nào
if (empty($_SESSION['checkout_items'])) {
    echo "<script>alert('Bạn chưa chọn sách nào để mượn!'); window.location.href='/index.php?page=cart';</script>";
    exit;
}
?>
<div class="container py-5">
    <div class="text-center mb-5 reveal-item">
        <h2 class="fw-bold" style="color: #20c997;">XÁC NHẬN MƯỢN SÁCH</h2>
        <p class="text-muted">Ní vui lòng chọn hình thức phù hợp để ASAG Library hỗ trợ tốt nhất nhé!</p>
    </div>

    <div class="row g-4 justify-content-center">
        <div class="col-lg-5 col-md-6 reveal-item">
            <div class="card h-100 border-0 shadow-sm benefit-card p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                        <span class="material-symbols-outlined text-primary" style="font-size: 32px;">auto_stories</span>
                    </div>
                    <h4 class="fw-bold mb-0">Mượn mang về</h4>
                </div>
                <p class="text-muted small mb-4">Hệ thống sẽ trừ kho và giữ sách cho ní trong vòng 24h kể từ thời điểm hẹn lấy.</p>
                
                <form action="/index.php?page=create_ticket" method="POST">
                    <input type="hidden" name="type" value="ONLINE">
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-dark">Ngày ní định ghé lấy sách:</label>
                        <input type="date" name="pickup_date" class="form-control border-2" 
                               style="border-color: #eee;" required min="<?= date('Y-m-d') ?>">
                        <div class="form-text mt-2" style="font-size: 0.75rem;">
                            <i class="fa-solid fa-circle-info me-1"></i> Trạng thái phiếu: <b>PENDING</b> (Chờ lấy)
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-pill shadow-sm">
                        XÁC NHẬN GIỮ SÁCH
                    </button>
                </form>
            </div>
        </div>

        <div class="col-lg-5 col-md-6 reveal-item">
            <div class="card h-100 border-0 shadow-sm benefit-card p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                        <span class="material-symbols-outlined text-success" style="font-size: 32px;">chair_alt</span>
                    </div>
                    <h4 class="fw-bold mb-0">Đọc tại chỗ</h4>
                </div>
                <p class="text-muted small mb-4">Dành cho ní đang có mặt tại thư viện. Sách sẽ được kích hoạt sử dụng ngay lập tức.</p>
                
                <form action="/index.php?page=create_ticket" method="POST" class="h-100 d-flex flex-column">
                    <input type="hidden" name="type" value="ON_SITE">
                    <div class="bg-light p-3 rounded-3 mb-4 flex-grow-1">
                        <ul class="list-unstyled mb-0 small">
                            <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> Trạng thái: <b>ACTIVE</b></li>
                            <li class="mb-2"><i class="fa-solid fa-clock text-danger me-2"></i> Hạn trả: <b>Trước giờ đóng cửa</b></li>
                            <li><i class="fa-solid fa-qrcode text-dark me-2"></i> Xuất trình mã cho thủ thư quét</li>
                        </ul>
                    </div>
                    <button type="submit" class="btn btn-success w-100 py-3 fw-bold rounded-pill shadow-sm">
                        XÁC NHẬN ĐỌC TẠI QUẦY
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>