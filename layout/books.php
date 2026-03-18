<?php
require_once __DIR__ . '/../database/ConnectDB.php';

$conn = ConnectDB::getInstance()->getConnection();


// Map slug tren URL sang ma the loai trong DB (can chinh theo bang theloai cua ban)
$categoryMap = [
    'kinh-te'      => 'TL001',        // Kinh Tế
    'van-hoc-trong-nuoc'  => 'TL002',  // Văn Học Trong Nước
    'van-hoc-nuoc-ngoai'   => 'TL003',  // Văn Học Nước Ngoài
    'doi-song' => 'TL004',   // Đời Sống
    'thieu-nhi'    => 'TL005',  // Thiếu Nhi
    'phat-trien-ban-than'  => 'TL006', // Phát Triển Bản Thân
    'tin-hoc-ngoai-ngu'    => 'TL007',       //Tin học ngoại ngữ
    'chuyen-nganh'    => 'TL008'       //Chuyên Ngành
];

$currentLoai = $_GET['loai'] ?? null;
$currentMaTL = ($currentLoai && isset($categoryMap[$currentLoai])) ? $categoryMap[$currentLoai] : null;
$search = $_GET['search'] ?? '';

if ($search != '') {

    if ($currentMaTL) {
        $sql = "SELECT madausach, tensach, namxuatban, dongia, anhbia 
                FROM dausach 
                WHERE matheloai = ? 
                AND tensach LIKE ?";
        $stmt = $conn->prepare($sql);
        $keyword = "%$search%";
        $stmt->bind_param('ss', $currentMaTL, $keyword);
    } else {
        $sql = "SELECT madausach, tensach, namxuatban, dongia, anhbia 
                FROM dausach 
                WHERE tensach LIKE ?";
        $stmt = $conn->prepare($sql);
        $keyword = "%$search%";
        $stmt->bind_param('s', $keyword);
    }

    $stmt->execute();
    $result = $stmt->get_result();

} else {

    if ($currentMaTL) {
        $sql = 'SELECT madausach, tensach, namxuatban, dongia, anhbia 
                FROM dausach 
                WHERE matheloai = ?';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $currentMaTL);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $sql = 'SELECT madausach, tensach, namxuatban, dongia, anhbia FROM dausach';
        $result = $conn->query($sql);
    }

}
?>

<link rel="stylesheet" href="/assets/css/books.css">

<div class="books-page">
    <div>

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
                <p style="color:#fff">Chưa có sách nào trong hệ thống.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
