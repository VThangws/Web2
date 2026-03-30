<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 1. Kết nối Database
require_once __DIR__ . '/../database/ConnectDB.php';
$db = ConnectDB::getInstance();

// 2. Lấy mã sách từ URL
$madausach = isset($_GET['madausach']) ? $_GET['madausach'] : 'DS001';

// 3. Khởi tạo biến
$book = null;
$related_books = [];
$error_msg = null;

// --- FAKE RATING ---
$fake_ratings = [
    'DS001' => ['sao' => 4.7, 'luot' => 3],
    'DS002' => ['sao' => 5.0, 'luot' => 11], 
    'DS003' => ['sao' => 4.5, 'luot' => 8]
];

$has_rating = isset($fake_ratings[$madausach]);
// Nếu không có trong danh sách fake, mặc định sao = 0, lượt = 0
$sao = $has_rating ? $fake_ratings[$madausach]['sao'] : 0;
$luot = $has_rating ? $fake_ratings[$madausach]['luot'] : 0;

try {
    $conn = $db->getConnection();

    // 3.1. Truy vấn Chi tiết sách
    $sql_book = "SELECT
                    ds.tensach, ds.anhbia, ds.namxuatban, ds.mota, ds.dongia,
                    tg.tentacgia,
                    tl.tentheloai, tl.matheloai,
                    nxb.tennxb
                FROM DauSach ds
                LEFT JOIN TacGia tg ON ds.matacgia = tg.matacgia
                LEFT JOIN TheLoai tl ON ds.matheloai = tl.matheloai
                LEFT JOIN NhaXuatBan nxb ON ds.manxb = nxb.manxb
                WHERE ds.madausach = ?";
    $stmt_book = $conn->prepare($sql_book);
    $stmt_book->bind_param("s", $madausach);
    $stmt_book->execute();
    $result_book = $stmt_book->get_result();
    $book = $result_book->fetch_assoc();

    if ($book) {
        $matheloai = $book['matheloai'];

        // ĐẾM SỐ LƯỢNG SÁCH CÒN SẴN SÀNG ĐỂ MƯỢN
        $sql_stock = "SELECT COUNT(*) as soluong_con FROM cuonsach WHERE madausach = ? AND trangthai = 'SanSang'";
        $stmt_stock = $conn->prepare($sql_stock);
        $stmt_stock->bind_param("s", $madausach);
        $stmt_stock->execute();
        $stock_data = $stmt_stock->get_result()->fetch_assoc();
        $soluong_con = $stock_data ? $stock_data['soluong_con'] : 0;
        
        // 3.2. Truy vấn Sách đề xuất CÙNG THỂ LOẠI
        $sql_related = "SELECT * FROM DauSach WHERE matheloai = ? AND madausach != ? LIMIT 5";
        $stmt_related = $conn->prepare($sql_related);
        $stmt_related->bind_param("ss", $matheloai, $madausach);
        $stmt_related->execute();
        $result_related = $stmt_related->get_result();
        $related_books = $result_related->fetch_all(MYSQLI_ASSOC);
    }

} catch (Exception $e) {
    $error_msg = $e->getMessage();
}
?>

<link rel="stylesheet" href="/assets/css/book_detail.css">

<div class="container pt-4 pb-5 book-container">
    <?php if ($error_msg): ?>
        <div class="alert alert-danger text-center">Lỗi: <?= $error_msg ?></div>
    <?php elseif (!$book): ?>
        <div class="alert alert-warning text-center">
            <h3>Không tìm thấy cuốn sách nào có mã: <strong><?= htmlspecialchars($madausach) ?></strong></h3>
        </div>
    <?php else: ?>

        <div class="mb-4">
            <div class="border rounded bg-white d-flex align-items-center shadow-sm" style="padding: 12px 20px; font-size: 15px;">
                <a href="/index.php" class="text-decoration-none fw-semibold" style="color: #20c997;">Trang chủ</a>
                
                <i class="fa-solid fa-angle-right text-muted" style="margin: 0 8px; font-size: 12px;"></i>
                
                <a href="/index.php?page=books&loai=<?= htmlspecialchars($book['matheloai']) ?>" class="text-decoration-none fw-semibold" style="color: #20c997;">
                    <?= htmlspecialchars($book['tentheloai'] ?: 'Danh mục') ?>
                </a>
                
                <i class="fa-solid fa-angle-right text-muted" style="margin: 0 8px; font-size: 12px;"></i>
                
                <span class="text-dark fw-bold"><?= htmlspecialchars($book['tensach']) ?></span>
            </div>
        </div>

    <div class="book-details-container" style="margin-top: 40px;">
        
        <div class="book-cover-column">
            <img src="/assets/img/books/<?= htmlspecialchars($book['anhbia'] ?: 'demo.jpg') ?>" alt="Bìa sách" class="book-cover rounded shadow">
        </div>

        <div class="book-info-column">
            <div class="shadow-sm bg-white p-4 rounded mb-4 border border-light">
                <h1 class="book-title fw-bold text-dark"><?= htmlspecialchars($book['tensach']) ?></h1>
                
                <div class="average-rating-container my-3">
                    <span class="average-rating-number book-rating fs-4 fw-bold text-warning"><?= number_format($sao, 1) ?></span>
                    <span class="average-rating-stars text-warning fs-5">
                        <?php
                        for ($i = 1; $i <= 5; $i++) {
                            echo ($i <= round($sao)) ? '★' : '<span style="color: #e4e5e9;">★</span>';
                        }
                        ?>
                    </span>
                    <span class="average-rating-text text-muted ms-2 border-start ps-2"><?= $luot ?> Đánh giá</span>

                    <span class="text-muted ms-2 border-start ps-2">
                        <?php if ($soluong_con > 0): ?>
                            Còn <strong class="text-success"><?= $soluong_con ?></strong> cuốn
                        <?php else: ?>
                            <strong class="text-danger">Tạm hết sách</strong>
                        <?php endif; ?>
                    </span>
                </div>

                <div class="book-info-grid mt-4">
                    <div class="info-group">
                        <div class="lbl text-muted small">Tác giả</div>
                        <div class="val fw-bold text-dark"><?= htmlspecialchars($book['tentacgia'] ?: 'Đang cập nhật') ?></div>
                    </div>
                    <div class="info-group">
                        <div class="lbl text-muted small">Thể loại</div>
                        <div class="val fw-bold text-dark"><?= htmlspecialchars($book['tentheloai'] ?: 'Đang cập nhật') ?></div>
                    </div>
                    <div class="info-group">
                        <div class="lbl text-muted small">Nhà xuất bản</div>
                        <div class="val fw-bold text-dark"><?= htmlspecialchars($book['tennxb'] ?: 'Đang cập nhật') ?></div>
                    </div>
                    <div class="info-group">
                        <div class="lbl text-muted small">Năm phát hành</div>
                        <div class="val fw-bold text-dark"><?= htmlspecialchars($book['namxuatban'] ?: 'Đang cập nhật') ?></div>
                    </div>
                </div>
                
                <div class="mt-3 mb-4">
                    <div class="lbl text-muted small mb-1">Giá mượn</div>
                    <div class="val text-danger fw-bold fs-4"><?= number_format($book['dongia'], 0, ',', '.') ?> VNĐ</div>
                </div>

                <button id="btn-add-cart" data-id="<?= htmlspecialchars($madausach) ?>" class="btn-cart-custom">
                    <i class="fa-solid fa-cart-shopping me-2"></i> Thêm vào giỏ hàng
                </button>

                <div class="book-desc-section book-desc mt-5">
                    <h5 class="fw-bold border-bottom pb-2 text-dark">Giới thiệu sách</h5>
                    <div class="mt-3 text-secondary lh-lg">
                        <?= nl2br(htmlspecialchars($book['mota'] ?: 'Chưa có thông tin giới thiệu.')) ?>
                    </div>
                </div>
            </div>
        </div>
    </div> 

    <?php if (!empty($related_books)): ?>
    <div class="related-books-section shadow-sm bg-white p-4 rounded mt-4 border border-light">
        <h5 class="section-title fw-bold mb-4 text-dark text-center">Sản phẩm liên quan</h5>
        <div class="book-thumbnail-grid d-flex gap-4 overflow-auto pb-2 justify-content-center">
            <?php foreach ($related_books as $related_book): ?>
            <a href="/index.php?page=book_detail&madausach=<?= htmlspecialchars($related_book['madausach']) ?>" class="book-thumbnail-card text-decoration-none text-dark flex-shrink-0" style="width: 160px; border: none; box-shadow: none;">
                <img src="/assets/img/books/<?= htmlspecialchars($related_book['anhbia'] ?: 'demo.jpg') ?>" alt="Bìa sách" class="img-fluid rounded mb-3 w-100 shadow-sm" style="height: 220px; object-fit: cover;">
                <h6 class="text-center text-truncate fw-semibold" style="max-width: 160px; font-size: 0.9rem;"><?= htmlspecialchars($related_book['tensach']) ?></h6>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php endif; ?>
</div>

<div id="toast-cart" class="notice-add-to-cart opacity-0">
    <span class="checkmark"><i class="fa-solid fa-check"></i></span>
    <span>Đã thêm vào giỏ hàng</span>
</div>

<script>
document.getElementById('btn-add-cart').addEventListener('click', function() {
    var madausach = this.getAttribute('data-id');
    var btn = this;

    // FIX LỖI GIẬT NÚT: 
    var currentWidth = btn.offsetWidth;
    btn.style.width = currentWidth + 'px';
    
    var originalHTML = btn.innerHTML;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Đang thêm...';
    btn.disabled = true;

    var formData = new FormData();
    formData.append('madausach', madausach);

    fetch('/ajax/add_to_cart.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        btn.innerHTML = originalHTML;
        btn.disabled = false;
        btn.style.width = ''; 

        if(data.status === 'success') {
            
            // Hiện thông báo Toast nảy từ giữa màn hình
            var toast = document.getElementById('toast-cart');
            if(toast) {
                toast.classList.remove('opacity-0');
                toast.classList.add('opacity-100');

                setTimeout(function() {
                    toast.classList.remove('opacity-100');
                    toast.classList.add('opacity-0');
                }, 2000); // xuất hiện 2 giây 
            }

            // Cập nhật số đỏ trên Header
            var cartBadge = document.getElementById('cart-badge');
            if (cartBadge) {
                cartBadge.innerText = data.total_items;
                cartBadge.classList.remove('d-none'); 
                
                cartBadge.style.transform = 'translate(-50%, -50%) scale(1.3)';
                setTimeout(function() {
                    cartBadge.style.transform = 'translate(-50%, -50%) scale(1)';
                }, 200);
            }
        } else {
            alert('Lỗi: ' + data.message);
        }
    })
    .catch(error => {
        btn.innerHTML = originalHTML;
        btn.disabled = false;
        btn.style.width = '';
        alert('Đứt kết nối với máy chủ!');
    });
});
</script>