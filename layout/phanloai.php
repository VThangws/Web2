<?php
if (!defined('APP_BOOTSTRAPPED')) { http_response_code(404); exit; }
// Category listing (DB-backed)
$categoryId = isset($_GET['phanloai']) ? (string)$_GET['phanloai'] : '';
// Pagination params
$perPage = 24;
$p = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
$total = 0;
$pages = 1;
$dbError = '';
$category = null;
$books = [];
$categories = [];

try {
  $db = DBConnect::getInstance();
  if ($categoryId !== '') {
    $category = $db->selectOne("SELECT category_id, category_name FROM Category WHERE category_id = ?", [$categoryId]);
    if ($category) {
      $total = (int)$db->count("SELECT COUNT(*) FROM Book WHERE category = ?", [$categoryId]);
      $pages = max(1, (int)ceil($total / $perPage));
      $offset = max(0, ($p - 1) * $perPage);
      $books = $db->select(
        "SELECT book_id, title, author, publish_year FROM Book WHERE category = ? ORDER BY title ASC LIMIT $perPage OFFSET $offset",
        [$categoryId]
      );
    }
  } else {
    $categories = $db->select("SELECT category_id, category_name FROM Category ORDER BY category_name ASC LIMIT 50");
  }
} catch (Throwable $e) {
  $dbError = 'Không thể tải dữ liệu danh mục lúc này.';
}
?>
<section class="container py-5">
  <?php if ($dbError): ?>
    <div class="alert alert-danger mb-4"><?= htmlspecialchars($dbError) ?></div>
  <?php endif; ?>

  <?php if ($categoryId === '' || !$category): ?>
    <h1 class="h4 mb-3">Danh mục sách</h1>
    <?php if (!$categories): ?>
      <p class="text-muted">Chưa có danh mục hoặc lỗi kết nối.</p>
    <?php else: ?>
      <div class="row g-3">
        <?php foreach ($categories as $c): ?>
          <div class="col-6 col-sm-4 col-md-3 col-lg-2">
            <a class="btn btn-outline-success w-100" href="/index.php?page=sanpham&amp;phanloai=<?= urlencode($c['category_id']) ?>">
              <?= htmlspecialchars($c['category_name']) ?>
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  <?php else: ?>
    <h1 class="h4 mb-3">Danh mục: <?= htmlspecialchars($category['category_name']) ?></h1>
    <?php if (!$books): ?>
      <p class="text-muted">Chưa có sách trong danh mục này.</p>
    <?php else: ?>
      <p class="text-muted">Tổng: <?= (int)$total ?> sách</p>
      <div class="row g-4">
        <?php foreach ($books as $book): ?>
          <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <div class="card h-100 shadow-sm">
              <div class="ratio bg-light" style="--bs-aspect-ratio: 150%;"></div>
              <div class="card-body">
                <h2 class="h6 mb-1" title="<?= htmlspecialchars($book['title']) ?>"><?= htmlspecialchars($book['title']) ?></h2>
                <div class="small text-muted"><?= htmlspecialchars($book['author'] ?? 'Không rõ tác giả') ?></div>
                <?php if (!empty($book['publish_year'])): ?><div class="small">Năm: <?= (int)$book['publish_year'] ?></div><?php endif; ?>
              </div>
              <div class="card-footer bg-white border-0 pt-0">
                <a class="btn btn-sm btn-outline-success" href="/index.php?page=sanpham&amp;id=<?= urlencode($book['book_id']) ?>">Xem chi tiết</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <?php if ($pages > 1): ?>
        <nav class="mt-4" aria-label="Pagination">
          <ul class="pagination">
            <?php $prev = max(1, $p - 1); $next = min($pages, $p + 1); ?>
            <li class="page-item <?= $p <= 1 ? 'disabled' : '' ?>">
              <a class="page-link" href="/index.php?page=sanpham&amp;phanloai=<?= urlencode($categoryId) ?>&amp;p=<?= $prev ?>">Trước</a>
            </li>
            <?php for ($i = 1; $i <= $pages && $i <= 7; $i++): ?>
              <li class="page-item <?= $i === $p ? 'active' : '' ?>">
                <a class="page-link" href="/index.php?page=sanpham&amp;phanloai=<?= urlencode($categoryId) ?>&amp;p=<?= $i ?>"><?= $i ?></a>
              </li>
            <?php endfor; ?>
            <li class="page-item <?= $p >= $pages ? 'disabled' : '' ?>">
              <a class="page-link" href="/index.php?page=sanpham&amp;phanloai=<?= urlencode($categoryId) ?>&amp;p=<?= $next ?>">Sau</a>
            </li>
          </ul>
        </nav>
      <?php endif; ?>
    <?php endif; ?>
  <?php endif; ?>
</section>