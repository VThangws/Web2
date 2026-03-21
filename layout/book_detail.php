<?php
// Bật thông báo lỗi để rà lỗi (Tắt đi khi đưa lên host thật)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 1. Kết nối Database
require_once __DIR__ . '/../database/ConnectDB.php';
$db = ConnectDB::getInstance();

// 2. Lấy mã sách từ URL (Lưu ý: DB mới dùng mã DS001, DS002...)
$madausach = isset($_GET['madausach']) ? $_GET['madausach'] : 'DS001';

// 3. Khởi tạo biến
$book = null;
$related_books = [];
$error_msg = null;

// --- THỦ THUẬT FAKE RATING ---
// Chỉ cấp sao cho những mã sách được khai báo ở đây.
$fake_ratings = [
    'DS001' => ['sao' => 4.7, 'luot' => 3],
    'DS002' => ['sao' => 5.0, 'luot' => 11], 
    'DS003' => ['sao' => 4.5, 'luot' => 8]
];

$has_rating = isset($fake_ratings[$madausach]);
// Nếu không có trong danh sách fake, mặc định sao = 0, lượt = 0
$sao = $has_rating ? $fake_ratings[$madausach]['sao'] : 0;
$luot = $has_rating ? $fake_ratings[$madausach]['luot'] : 0;
// -----------------------------

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

<div class="container py-5 book-container">
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
                
                <a href="/index.php?page=sach&loai=<?= htmlspecialchars($book['matheloai']) ?>" class="text-decoration-none fw-semibold" style="color: #20c997;">
                    <?= htmlspecialchars($book['tentheloai'] ?: 'Danh mục') ?>
                </a>
                
                <i class="fa-solid fa-angle-right text-muted" style="margin: 0 8px; font-size: 12px;"></i>
                
                <span class="text-dark fw-bold"><?= htmlspecialchars($book['tensach']) ?></span>
            </div>
        </div>

    <div class="book-details-container">
        
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

<style>
/* ====================================
   1. CSS CHO NÚT THÊM VÀO GIỎ HÀNG (HÌNH VUÔNG)
   ==================================== */
.btn-cart-custom {
    background-color: #20c997; /* Xanh ngọc bích */
    color: white;
    border: none;
    padding: 12px 32px;
    font-weight: 900;
    font-size: 18px;
    border-radius: 8px; /* Ép về hình vuông, bo góc cực nhẹ */
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 220px;
    cursor: pointer;
    box-shadow: 0 4px 10px rgba(32, 201, 151, 0.3);
}

.btn-cart-custom:hover {
    background-color: #1aa179;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(32, 201, 151, 0.4);
}

.btn-cart-custom:disabled {
    background-color: #8ce1c6;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

/* ====================================
   2. CSS CHO TOAST (VUÔNG, ICON TRÊN, CHỮ DƯỚI)
   ==================================== */
.notice-add-to-cart {
    position: fixed; 
    top: 50%;     
    left: 50%;    
    transform: translate(-50%, -50%) scale(0.9); 
    transition: opacity 0.4s ease, transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); 
    
    border-radius: 16px; /* Bo góc mượt cho khối vuông */
    box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.15); 
    background-color: #20c997; /* Đồng bộ màu xanh */
    color: white; 
    
    /* ÉP 2 DÒNG BẰNG FLEXBOX */
    display: flex;
    flex-direction: column; 
    align-items: center;    
    justify-content: center; 
    
    padding: 30px 20px;
    width: 220px; 
    text-align: center; 
    font-size: 18px;
    font-weight: 900;
    
    z-index: 10000; 
    opacity: 0; 
    pointer-events: none; 
}

/* Vòng tròn trắng chứa icon */
.notice-add-to-cart .checkmark {
    margin-right: 0; 
    margin-bottom: 16px; /* Đẩy chữ xích xuống dưới */
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: white;
    color: #20c997; 
    border-radius: 50%;
    width: 48px; 
    height: 48px;
}

.notice-add-to-cart .checkmark i {
    font-size: 26px;
}

.notice-add-to-cart.opacity-100 {
    opacity: 1 !important;
    transform: translate(-50%, -50%) scale(1) !important;
}
.notice-add-to-cart.opacity-0 {
    opacity: 0 !important;
    z-index: -1;
}
</style>

<button id="btn-add-cart" data-id="<?= htmlspecialchars($madausach) ?>" class="btn-cart-custom">
    <i class="fa-solid fa-cart-shopping me-2"></i> Thêm vào giỏ hàng
</button>

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