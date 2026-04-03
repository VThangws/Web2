<?php
$mamuon = $_GET['id'] ?? 'KHONG-XAC-DINH';
// Sử dụng API của QuickChart (ổn định và không bị chặn)
// Chỉ truyền đúng ID số vào mã QR để thủ thư quét cho chuẩn
$qr_api = "https://quickchart.io/qr?text=" . $mamuon . "&size=250";
?>

<div class="container py-5 text-center">
    <div class="card border-0 shadow-sm p-5 benefit-card d-inline-block" style="max-width: 500px;">
        <div class="rounded-circle bg-success bg-opacity-10 p-3 d-inline-block mb-4">
            <span class="material-symbols-outlined text-success" style="font-size: 48px;">check_circle</span>
        </div>
        
        <h2 class="fw-bold mb-2">MƯỢN SÁCH THÀNH CÔNG!</h2>
        <p class="text-muted mb-4">Mã phiếu của bạn là: <strong class="text-dark"><?= htmlspecialchars($mamuon) ?></strong></p>
        
        <div class="bg-white p-3 border rounded-3 mb-4 d-inline-block">
            <img src="<?= $qr_api ?>" alt="Mã QR Phiếu Mượn" class="img-fluid">
        </div>
        
        <div class="alert alert-info py-2 small mb-4">
            <i class="fa-solid fa-circle-info me-2"></i>
            Bạn hãy đưa mã này cho thủ thư tại quầy để nhận/trả sách nhé!
        </div>

        <div class="d-grid gap-2">
            <a href="/index.php" class="btn btn-success rounded-pill fw-bold">VỀ TRANG CHỦ</a>
            <a href="/index.php?page=history" class="btn btn-outline-secondary rounded-pill fw-bold">XEM LỊCH SỬ MƯỢN</a>
        </div>
    </div>
</div>