<?php
if (!defined('APP_BOOTSTRAPPED')) { http_response_code(404); exit; }

$currentPage = $_GET['page'] ?? '';
$navCategories = [];
$cartCount = 0;
if (!empty($_SESSION['cart']) && is_array($_SESSION['cart'])) {
  foreach ($_SESSION['cart'] as $q) { $cartCount += (int)$q; }
}
try {
  $db = DBConnect::getInstance();
  $navCategories = $db->select("SELECT category_id, category_name FROM Category ORDER BY category_name ASC LIMIT 10");
} catch (Throwable $e) {
  // ignore
}
?>
<header class="py-3 border-bottom">
  <div class="container d-flex align-items-center justify-content-between">
    <a href="index.php" class="text-decoration-none d-flex align-items-center gap-2">
      <i class="fa-solid fa-book-open text-success"></i>
      <span class="fw-bold">Book Library</span>
    </a>
    <nav class="d-flex gap-3 align-items-center">
      <a class="text-secondary text-decoration-none <?= $currentPage === '' ? 'fw-bold text-dark' : '' ?>" href="index.php">Trang chủ</a>
      <a class="text-secondary text-decoration-none <?= $currentPage === 'search' ? 'fw-bold text-dark' : '' ?>" href="index.php?page=search">Tìm kiếm</a>
      <div class="dropdown">
        <a class="text-secondary text-decoration-none dropdown-toggle <?= ($currentPage === 'sanpham' && isset($_GET['phanloai'])) ? 'fw-bold text-dark' : '' ?>" href="#" data-bs-toggle="dropdown" aria-expanded="false">Danh mục</a>
        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
          <?php if ($navCategories): ?>
            <?php foreach ($navCategories as $c): ?>
              <li>
                <a class="dropdown-item" href="index.php?page=sanpham&amp;phanloai=<?= urlencode($c['category_id']) ?>">
                  <?= htmlspecialchars($c['category_name']) ?>
                </a>
              </li>
            <?php endforeach; ?>
            <li><hr class="dropdown-divider"></li>
          <?php endif; ?>
          <li><a class="dropdown-item" href="index.php?page=sanpham">Tất cả danh mục</a></li>
        </ul>
      </div>
      <a class="position-relative text-secondary text-decoration-none <?= $currentPage === 'giohang' ? 'fw-bold text-dark' : '' ?>" href="index.php?page=giohang" aria-label="Giỏ hàng">
        <i class="fa-solid fa-cart-shopping"></i>
        <?php if ($cartCount > 0): ?>
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?= (int)$cartCount ?></span>
        <?php endif; ?>
      </a>
    </nav>
  </div>
</header>