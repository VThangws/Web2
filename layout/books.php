<?php
require_once __DIR__ . '/../database/ConnectDB.php';

$conn = ConnectDB::getInstance()->getConnection();

// Lấy tất cả đầu sách từ database
$sql = 'SELECT madausach, tensach, namxuatban, dongia, anhbia FROM dausach';
$result = $conn->query($sql);
?>

<link rel="stylesheet" href="/assets/css/books.css">

<div class="books-page">

    <div class="books-grid-wrapper">
        <div class="books-grid">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($book = $result->fetch_assoc()): ?>
                    <div class="book-card">
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
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="color:#fff">Chưa có sách nào trong hệ thống.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
