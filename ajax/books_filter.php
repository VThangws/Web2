<?php
// filepath: ajax/books_filter.php
require_once __DIR__ . '/../database/ConnectDB.php';

header('Content-Type: application/json; charset=utf-8');

$conn = ConnectDB::getInstance()->getConnection();

// Lấy tham số
$loaiSlug = isset($_GET['loai']) ? trim($_GET['loai']) : '';
$page     = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit    = 8; // số sách mỗi trang
$offset   = ($page - 1) * $limit;

// Map slug -> mã thể loại/điều kiện DB thực tế
// Bạn chỉnh lại theo cấu trúc DB của mình
$mapLoai = [
    'kinh-te'              => 'TL001',
    'van-hoc-trong-nuoc'   => 'TL002',
    'van-hoc-nuoc-ngoai'   => 'TL003',
    'doi-song'             => 'TL004',
    'thieu-nhi'            => 'TL005',
    'phat-trien-ban-than'  => 'TL006',
    'tin-hoc-ngoai-ngu'    => 'TL007',
    'chuyen-nganh'         => 'TL008',
];

$where  = '';
$params = [];
$types  = '';

if ($loaiSlug !== '' && isset($mapLoai[$loaiSlug])) {
    $where   = 'WHERE matheloai = ?';
    $params[] = $mapLoai[$loaiSlug];
    $types   .= 's';
}

// Đếm tổng
$sqlCount = "SELECT COUNT(*) AS total FROM dausach $where";
$stmt = $conn->prepare($sqlCount);
if ($where !== '') {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$resCount = $stmt->get_result()->fetch_assoc();
$totalRows = (int)$resCount['total'];
$stmt->close();

$totalPages = $totalRows > 0 ? ceil($totalRows / $limit) : 1;

// Lấy dữ liệu trang hiện tại
$sql = "SELECT madausach, tensach, anhbia
        FROM dausach
        $where
        ORDER BY madausach DESC
        LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
if ($where !== '') {
    $typesQuery = $types . 'ii';
    $paramsQuery = array_merge($params, [$limit, $offset]);
    $stmt->bind_param($typesQuery, ...$paramsQuery);
} else {
    $stmt->bind_param('ii', $limit, $offset);
}
$stmt->execute();
$result = $stmt->get_result();

// Render HTML danh sách sách – CHỈNH CHO GIỐNG layout/books.php CỦA BẠN
// Thay thế phần "Render HTML danh sách sách" trong ajax/books_filter.php
ob_start();
if ($totalRows === 0) {
    echo '<p style="color:#000; text-align: center; width: 100%; margin-top: 20px;">Không có sách nào trong thể loại này.</p>';
} else {
    echo '<div class="books-grid">'; // Sử dụng lại class grid của bạn
    while ($row = $result->fetch_assoc()) {
        $madausach = htmlspecialchars($row['madausach'], ENT_QUOTES);
        $tensach   = htmlspecialchars($row['tensach'], ENT_QUOTES);
        $anhbia    = !empty($row['anhbia']) ? "/assets/img/books/" . htmlspecialchars($row['anhbia'], ENT_QUOTES) : "/assets/img/categories/booknew.png";

        echo '
        <div class="book-card">
            <a href="/index.php?page=book_detail&madausach=' . $madausach . '" style="text-decoration: none; color: inherit; display: block;">
                <div class="book-cover">
                    <img src="' . $anhbia . '" alt="' . $tensach . '">
                </div>
                <div class="book-title">' . $tensach . '</div>
            </a>
        </div>';
    }
    echo '</div>';
}
$htmlList = ob_get_clean();
// Thay thế phần "Render HTML phân trang" trong ajax/books_filter.php
ob_start();
if ($totalPages > 1) {
    echo '<div class="pagination-wrapper">
            <div class="pagination-advanced">';
    
    // Nút Trước
    if ($page > 1) {
        $prevPage = $page - 1;
        echo '<a href="#" class="page-link prev-page" data-page="' . $prevPage . '">&larr;</a>';
    }

    // Các số trang
    for ($i = 1; $i <= $totalPages; $i++) {
        $activeClass = ($i == $page) ? 'active' : '';
        echo '<a href="#" class="page-link ' . $activeClass . '" data-page="' . $i . '">' . $i . '</a>';
    }

    // Nút Sau
    if ($page < $totalPages) {
        $nextPage = $page + 1;
        echo '<a href="#" class="page-link next-page" data-page="' . $nextPage . '">&rarr;</a>';
    }

    echo '  </div>
          </div>';
}
$paginationHtml = ob_get_clean();

echo json_encode([
    'html'       => $htmlList,
    'pagination' => $paginationHtml,
]);