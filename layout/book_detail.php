<?php
// Bật thông báo lỗi để rà lỗi cho dễ (chỉ dùng lúc code)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 1. Kết nối Database 
require_once __DIR__ . '/../database/ConnectDB.php';
$db = ConnectDB::getInstance();

// 2. Lấy mã sách. Đang gán mặc định luôn là DS01 cho dễ test.
$madausach = isset($_GET['madausach']) ? $_GET['madausach'] : 'DS001';

// 3. Kéo dữ liệu từ DB
$sql = "SELECT 
            ds.tensach, ds.anhbia, ds.namxuatban, ds.mota, ds.dongia,
            tg.tentacgia, 
            tl.tentheloai, 
            nxb.tennxb
        FROM DauSach ds
        LEFT JOIN TacGia tg ON ds.matacgia = tg.matacgia
        LEFT JOIN TheLoai tl ON ds.matheloai = tl.matheloai
        LEFT JOIN NhaXuatBan nxb ON ds.manxb = nxb.manxb
        WHERE ds.madausach = ?";

try {
    // 3.1. Lấy đối tượng kết nối mysqli gốc từ class ConnectDB
    $conn = $db->getConnection();
    
    // 3.2. Chuẩn bị câu lệnh (Prepared Statement) để chống SQL Injection
    $stmt = $conn->prepare($sql);
    
    // 3.3. Gắn biến $madausach vào dấu chấm hỏi (?) ở trên ("s" nghĩa là kiểu String)
    $stmt->bind_param("s", $madausach); 
    
    // 3.4. Thực thi và lấy kết quả
    $stmt->execute();
    $result = $stmt->get_result();
    
    // 3.5. Rút trích 1 dòng dữ liệu dưới dạng mảng (nếu không có thì trả về null)
    $book = $result->fetch_assoc(); 
    
} catch (Exception $e) {
    $book = null;
    $error_msg = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết sách - Dev Mode</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    
    <link rel="stylesheet" href="/assets/css/book_detail.css">
</head>
<body style="background-color: #f0f2f5;"> <div class="container py-5">
    <?php if (isset($error_msg)): ?>
        <div class="alert alert-danger text-center">Lỗi Database: <?= $error_msg ?></div>
    <?php elseif (!$book): ?>
        <div class="alert alert-warning text-center">
            <h3>Không tìm thấy cuốn sách nào có mã: <strong><?= htmlspecialchars($madausach) ?></strong></h3>
            <p>Ông kiểm tra lại trong phpMyAdmin xem đã chạy lệnh INSERT dữ liệu chưa nhé!</p>
        </div>
    <?php else: ?>

    <div class="waka-wrapper shadow-sm">
        <div class="waka-left">
            <img src="/assets/img/books/<?= htmlspecialchars($book['anhbia'] ?: 'demo.jpg') ?>" alt="Bìa sách" class="waka-cover">
        </div>

        <div class="waka-right">
            <h1 class="waka-title"><?= htmlspecialchars($book['tensach']) ?></h1>
            
            <div class="waka-rating">
                5.0 ★★★★★ <span>11 Đánh giá</span>
            </div>

            <div class="waka-info-grid">
                <div class="info-group">
                    <div class="lbl">Tác giả</div>
                    <div class="val text-waka-green"><?= htmlspecialchars($book['tentacgia'] ?: 'Đang cập nhật') ?></div>
                </div>
                <div class="info-group">
                    <div class="lbl">Thể loại</div>
                    <div class="val"><?= htmlspecialchars($book['tentheloai'] ?: 'Đang cập nhật') ?></div>
                </div>
                <div class="info-group">
                    <div class="lbl">Nhà xuất bản</div>
                    <div class="val"><?= htmlspecialchars($book['tennxb'] ?: 'Đang cập nhật') ?></div>
                </div>
                <div class="info-group">
                    <div class="lbl">Năm phát hành</div>
                    <div class="val"><?= htmlspecialchars($book['namxuatban'] ?: 'Đang cập nhật') ?></div>
                </div>
                <div class="info-group">
                    <div class="lbl">Giá cọc</div>
                    <div class="val text-danger"><?= number_format($book['dongia'], 0, ',', '.') ?> VNĐ</div>
                </div>
            </div>

            <button class="waka-btn-read">
                <i class="fa-solid fa-cart-plus me-2"></i> Thêm vào giỏ
            </button>

            <div class="waka-desc">
                <h3>Giới thiệu sách</h3>
                <p><?= nl2br(htmlspecialchars($book['mota'] ?: 'Chưa có thông tin giới thiệu.')) ?></p>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

</body>
</html>