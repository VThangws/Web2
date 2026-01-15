<?php
if (!defined('APP_BOOTSTRAPPED')) { http_response_code(404); exit; }

$id = isset($_GET['id']) ? (string)$_GET['id'] : null;
if (!$id && isset($_GET['page']) && is_numeric($_GET['page'])) {
  $id = (string)$_GET['page'];
}

$book = null;
$error = '';
if ($id) {
  try {
    $db = DBConnect::getInstance();
    $book = $db->selectOne(
      "SELECT b.book_id, b.title, b.author, b.publisher, b.publish_year, b.category, c.category_name
       FROM Book b
       LEFT JOIN Category c ON c.category_id = b.category
       WHERE b.book_id = ?",
      [$id]
    );
  } catch (Throwable $e) {
    $error = 'Không thể tải chi tiết sách lúc này.';
  }
}
?>
<section class="container py-5">
  <h1 class="h4 mb-3">Chi tiết Sách</h1>
  <?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php elseif (!$book): ?>
    <div class="alert alert-warning">Không tìm thấy sách hoặc thiếu mã sách.</div>
  <?php else: ?>
    <div class="row g-4 align-items-start">
      <div class="col-12 col-md-4">
        <div class="ratio bg-light rounded" style="--bs-aspect-ratio: 150%;"></div>
      </div>
      <div class="col-12 col-md-8">
        <h2 class="h5 mb-2"><?= htmlspecialchars($book['title']) ?></h2>
        <div class="mb-2"><span class="text-muted">Tác giả:</span> <?= htmlspecialchars($book['author'] ?? 'Không rõ') ?></div>
        <?php if (!empty($book['publisher'])): ?><div class="mb-2"><span class="text-muted">NXB:</span> <?= htmlspecialchars($book['publisher']) ?></div><?php endif; ?>
        <?php if (!empty($book['publish_year'])): ?><div class="mb-2"><span class="text-muted">Năm XB:</span> <?= (int)$book['publish_year'] ?></div><?php endif; ?>
        <?php if (!empty($book['category_name'])): ?><div class="mb-2"><span class="text-muted">Danh mục:</span> <?= htmlspecialchars($book['category_name']) ?></div><?php endif; ?>
        <div class="mt-3 d-flex gap-2">
          <a class="btn btn-success" href="/index.php?action=add_to_cart&amp;id=<?= urlencode($book['book_id']) ?>&amp;redirect=<?= urlencode('/index.php?page=sanpham&id=' . $book['book_id']) ?>">Thêm vào giỏ</a>
          <a class="btn btn-outline-secondary" href="javascript:history.back()">Quay lại</a>
        </div>
      </div>
    </div>
  <?php endif; ?>
</section>