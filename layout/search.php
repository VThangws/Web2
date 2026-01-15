<?php
if (!defined('APP_BOOTSTRAPPED')) { http_response_code(404); exit; }

$q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
$results = [];
$error = '';
$perPage = 12;
$p = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
$total = 0;
$pages = 1;

if ($q !== '' && mb_strlen($q) >= 2) {
  try {
    $db = DBConnect::getInstance();
    $like = '%' . $q . '%';
    $total = (int)$db->count(
      "SELECT COUNT(*) FROM Book WHERE title LIKE ? OR author LIKE ?",
      [$like, $like]
    );
    $pages = max(1, (int)ceil($total / $perPage));
    $offset = max(0, ($p - 1) * $perPage);

    $results = $db->select(
      "SELECT b.book_id, b.title, b.author, b.publisher, b.publish_year, c.category_name
       FROM Book b
       LEFT JOIN Category c ON c.category_id = b.category
       WHERE b.title LIKE ? OR b.author LIKE ?
       ORDER BY b.publish_year DESC, b.title ASC
       LIMIT $perPage OFFSET $offset",
      [$like, $like]
    );
  } catch (Throwable $e) {
    $error = 'Không thể kết nối CSDL lúc này.';
  }
}
?>
<section class="container py-5">
  <h1 class="h4 mb-3">Tìm kiếm</h1>
  <form class="row gy-2 gx-2 align-items-center mb-4" method="get" action="/index.php">
    <input type="hidden" name="page" value="search" />
    <div class="col-auto">
      <input type="text" class="form-control" name="q" placeholder="Nhập từ khóa..." value="<?= htmlspecialchars($q) ?>" />
    </div>
    <div class="col-auto">
      <button class="btn btn-success" type="submit">Tìm</button>
    </div>
  </form>

  <?php if ($q === '' || mb_strlen($q) < 2): ?>
    <p class="text-muted">Hãy nhập ít nhất 2 ký tự để tìm sách.</p>
  <?php elseif ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php else: ?>
    <p class="text-muted">Kết quả cho: <strong><?= htmlspecialchars($q) ?></strong> (<?= (int)$total ?>)</p>
    <?php if (!$results): ?>
      <div class="alert alert-warning">Không tìm thấy sách phù hợp.</div>
    <?php else: ?>
      <div class="row g-4">
        <?php foreach ($results as $book): ?>
          <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <div class="card h-100 shadow-sm">
              <div class="ratio bg-light" style="--bs-aspect-ratio: 150%;"></div>
              <div class="card-body">
                <h2 class="h6 mb-1" title="<?= htmlspecialchars($book['title']) ?>"><?= htmlspecialchars($book['title']) ?></h2>
                <div class="small text-muted"><?= htmlspecialchars($book['author'] ?? 'Không rõ tác giả') ?></div>
                <?php if (!empty($book['category_name'])): ?><div class="small">Danh mục: <?= htmlspecialchars($book['category_name']) ?></div><?php endif; ?>
                <?php if (!empty($book['publish_year'])): ?><div class="small">Năm: <?= (int)$book['publish_year'] ?></div><?php endif; ?>
              </div>
              <div class="card-footer bg-white border-0 pt-0">
                <a class="btn btn-sm btn-outline-success" href="/index.php?page=sanpham&id=<?= urlencode($book['book_id']) ?>">Xem chi tiết</a>
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
              <a class="page-link" href="/index.php?page=search&amp;q=<?= urlencode($q) ?>&amp;p=<?= $prev ?>">Trước</a>
            </li>
            <?php for ($i = 1; $i <= $pages && $i <= 7; $i++): ?>
              <li class="page-item <?= $i === $p ? 'active' : '' ?>">
                <a class="page-link" href="/index.php?page=search&amp;q=<?= urlencode($q) ?>&amp;p=<?= $i ?>"><?= $i ?></a>
              </li>
            <?php endfor; ?>
            <li class="page-item <?= $p >= $pages ? 'disabled' : '' ?>">
              <a class="page-link" href="/index.php?page=search&amp;q=<?= urlencode($q) ?>&amp;p=<?= $next ?>">Sau</a>
            </li>
          </ul>
        </nav>
      <?php endif; ?>
    <?php endif; ?>
  <?php endif; ?>
</section>