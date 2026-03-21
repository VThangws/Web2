<?php
require_once __DIR__ . '/../database/ConnectDB.php';

$conn = ConnectDB::getInstance()->getConnection();

// Map slug tren URL sang ma the loai trong DB
$categoryMap = [
    'kinh-te'              => 'TL001', 
    'van-hoc-trong-nuoc'   => 'TL002',  
    'van-hoc-nuoc-ngoai'   => 'TL003',  
    'doi-song'             => 'TL004',   
    'thieu-nhi'            => 'TL005',  
    'phat-trien-ban-than'  => 'TL006', 
    'tin-hoc-ngoai-ngu'    => 'TL007',       
    'chuyen-nganh'         => 'TL008'       
];

$currentLoai = $_GET['loai'] ?? null;
$currentMaTL = null;

if ($currentLoai) {
    // Nếu URL truyền lên chữ (vd: 'kinh-te'), thì lấy mã từ từ điển
    if (isset($categoryMap[$currentLoai])) {
        $currentMaTL = $categoryMap[$currentLoai]; 
    } 
    // Nếu URL truyền thẳng mã (vd: 'TL001'), thì xài luôn
    else {
        $currentMaTL = $currentLoai; 
    }
}
$search = $_GET['search'] ?? '';
// --- LẤY TÊN THỂ LOẠI HOẶC TỪ KHÓA ĐỂ LÀM THANH ĐIỀU HƯỚNG ---
$breadcrumbText = "Tất cả sách";
if ($currentMaTL) {
    $sqlCat = "SELECT tentheloai FROM TheLoai WHERE matheloai = ?";
    $stmtCat = $conn->prepare($sqlCat);
    $stmtCat->bind_param("s", $currentMaTL);
    $stmtCat->execute();
    $resCat = $stmtCat->get_result();
    if ($cat = $resCat->fetch_assoc()) {
        $breadcrumbText = $cat['tentheloai'];
    }
} elseif ($search != '') {
    $breadcrumbText = "Kết quả tìm kiếm: '" . $search . "'";
}
// -------------------------------------------------------------

// --- 1. CẤU HÌNH PHÂN TRANG CƠ BẢN ---
$limit = 12; // Số lượng sách hiển thị trên 1 trang
$page = isset($_GET['trang']) ? (int)$_GET['trang'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// --- 2. XÂY DỰNG CÂU TRUY VẤN ĐỘNG ---
$whereSql = "1=1"; // Điều kiện mặc định
$params = [];
$types = "";

if ($currentMaTL) {
    $whereSql .= " AND matheloai = ?";
    $params[] = $currentMaTL;
    $types .= "s";
}

if ($search != '') {
    $whereSql .= " AND tensach LIKE ?";
    $params[] = "%$search%";
    $types .= "s";
}

// --- 3. ĐẾM TỔNG SỐ LƯỢNG SÁCH ĐỂ TÍNH SỐ TRANG ---
$countSql = "SELECT COUNT(*) as total FROM dausach WHERE $whereSql";
$stmtCount = $conn->prepare($countSql);
if ($types !== "") {
    $stmtCount->bind_param($types, ...$params); 
}
$stmtCount->execute();
$totalRows = $stmtCount->get_result()->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit); // Làm tròn lên để lấy tổng số trang

// --- 4. LẤY DỮ LIỆU SÁCH CHO TRANG HIỆN TẠI ---
$sql = "SELECT madausach, tensach, namxuatban, dongia, anhbia 
        FROM dausach 
        WHERE $whereSql 
        LIMIT ? OFFSET ?";

$dataParams = $params;
$dataTypes = $types;
$dataParams[] = $limit;
$dataParams[] = $offset;
$dataTypes .= "ii";

$stmt = $conn->prepare($sql);
$stmt->bind_param($dataTypes, ...$dataParams);
$stmt->execute();
$result = $stmt->get_result();

// --- 5. TẠO HÀM XÂY DỰNG LINK PHÂN TRANG ĐỂ DÙNG LẠI ---
function buildPageUrl($p) {
    $queryParams = $_GET;
    $queryParams['trang'] = $p;
    return '?' . http_build_query($queryParams);
}

// --- 6. LOGIC PHÂN TRANG THU GỌN (DẤU BA CHẤM) ---
$pagesToDisplay = [];
$range = 1; // Số trang hiển thị xung quanh trang hiện tại

if ($totalPages > 0) {
    // Luôn hiển thị trang đầu tiên
    $pagesToDisplay[] = 1;

    // Chèn dấu ba chấm nếu trang đầu tiên cách quá xa phạm vi trang hiện tại
    if ($page > ($range + 2)) {
        $pagesToDisplay[] = '...';
    }

    // Xác định phạm vi xung quanh trang hiện tại
    $startRange = max(2, $page - $range);
    $endRange = min($totalPages - 1, $page + $range);

    for ($i = $startRange; $i <= $endRange; $i++) {
        $pagesToDisplay[] = $i;
    }

    // Chèn dấu ba chấm nếu trang cuối cùng cách quá xa phạm vi trang hiện tại
    if ($page < ($totalPages - $range - 1)) {
        $pagesToDisplay[] = '...';
    }

    // Luôn hiển thị trang cuối cùng (nếu tổng trang > 1)
    if ($totalPages > 1) {
        $pagesToDisplay[] = $totalPages;
    }
}
?>
<link rel="stylesheet" href="/assets/css/books.css">

<div class="books-page">
    
    <div class="mb-4" style="max-width: 1200px; margin: 25px auto 20px auto;">
        <div class="border rounded bg-white d-flex align-items-center shadow-sm" style="padding: 12px 20px; font-size: 15px;">
            <a href="/index.php" class="text-decoration-none fw-semibold" style="color: #20c997;">Trang chủ</a>
            
            <i class="fa-solid fa-angle-right text-muted" style="margin: 0 8px; font-size: 12px;"></i>
            
            <span class="text-dark fw-bold"><?= htmlspecialchars($breadcrumbText) ?></span>
        </div>
    </div>
    <div class="books-grid-wrapper">
        <div class="books-grid">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($book = $result->fetch_assoc()): ?>
                    <div class="book-card">
                        <a href="/index.php?page=book_detail&madausach=<?= htmlspecialchars($book['madausach'], ENT_QUOTES) ?>" style="text-decoration: none; color: inherit; display: block;">
                            <div class="book-cover">
                                <?php if (!empty($book['anhbia'])): ?>
                                    <img src="/assets/img/books/<?= htmlspecialchars($book['anhbia'], ENT_QUOTES) ?>"
                                         alt="<?= htmlspecialchars($book['tensach'], ENT_QUOTES) ?>">
                                <?php else: ?>
                                    <img src="/assets/img/categories/booknew.png" alt="<?= htmlspecialchars($book['tensach'], ENT_QUOTES) ?>">
                                <?php endif; ?>
                            </div>
                            
                            <div class="book-title">
                                <?= htmlspecialchars($book['tensach'], ENT_QUOTES) ?>
                            </div>
                        </a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="color:#fff; text-align: center; width: 100%;">Chưa có sách nào trong hệ thống hoặc không tìm thấy kết quả phù hợp.</p>
            <?php endif; ?>
        </div>

        <?php if ($totalPages > 1): ?>
            <div class="pagination-wrapper">
                <div class="pagination-advanced">
                    
                    <?php if ($page > 1): ?>
                        <a href="<?= buildPageUrl($page - 1) ?>" class="page-link prev-page" title="Trang trước">&larr;</a>
                    <?php endif; ?>

                    <?php foreach ($pagesToDisplay as $i): ?>
                        <?php if ($i === '...'): ?>
                            <span class="page-link ellipsis">...</span>
                        <?php else: ?>
                            <a href="<?= buildPageUrl($i) ?>" 
                               class="page-link <?= ($i == $page) ? 'active' : '' ?>">
                                <?= $i ?>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <?php if ($page < $totalPages): ?>
                        <a href="<?= buildPageUrl($page + 1) ?>" class="page-link next-page" title="Trang sau">&rarr;</a>
                    <?php endif; ?>

                </div>
            </div>
        <?php endif; ?>
    </div>
</div>