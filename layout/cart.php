<?php
if (!defined('APP_BOOTSTRAPPED')) { http_response_code(404); exit; }

$cart = $_SESSION['cart'] ?? [];
$items = [];
if ($cart) {
  try {
    $db = DBConnect::getInstance();
    $ids = array_keys($cart);
    $ph = implode(',', array_fill(0, count($ids), '?'));
    $rows = $db->select("SELECT book_id, title, author FROM Book WHERE book_id IN ($ph)", $ids);
    foreach ($rows as $r) {
      $id = (string)$r['book_id'];
      $items[] = [
        'id' => $id,
        'title' => $r['title'],
        'author' => $r['author'] ?? '',
        'qty' => (int)$cart[$id],
      ];
    }
  } catch (Throwable $e) {
    // Fallback: render IDs only
    foreach ($cart as $id => $qty) {
      $items[] = ['id' => (string)$id, 'title' => 'Mã: ' . $id, 'author' => '', 'qty' => (int)$qty];
    }
  }
}
?>
<section class="container py-5">
  <h1 class="h4 mb-3">Giỏ hàng</h1>
  <?php if (!$items): ?>
    <div class="alert alert-info">Giỏ hàng trống.</div>
    <a class="btn btn-success" href="index.php">Tiếp tục mua sắm</a>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th style="width:50%">Sách</th>
            <th class="text-center" style="width:140px">Số lượng</th>
            <th class="text-end" style="width:140px">Thao tác</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($items as $it): ?>
            <tr>
              <td>
                <div class="fw-semibold mb-1">
                  <a href="index.php?page=sanpham&amp;id=<?= urlencode($it['id']) ?>" class="text-reset text-decoration-none">
                    <?= htmlspecialchars($it['title']) ?>
                  </a>
                </div>
                <?php if (!empty($it['author'])): ?><div class="text-muted small">Tác giả: <?= htmlspecialchars($it['author']) ?></div><?php endif; ?>
              </td>
              <td class="text-center">
                <form class="d-inline-flex gap-2" method="get" action="index.php">
                  <input type="hidden" name="action" value="update_cart" />
                  <input type="hidden" name="id" value="<?= htmlspecialchars($it['id']) ?>" />
                  <input type="hidden" name="redirect" value="index.php?page=giohang" />
                  <input type="number" name="qty" class="form-control form-control-sm" value="<?= (int)$it['qty'] ?>" min="0" style="width:80px" />
                  <button class="btn btn-sm btn-outline-primary" type="submit">Cập nhật</button>
                </form>
              </td>
              <td class="text-end">
                <a class="btn btn-sm btn-outline-danger" href="index.php?action=remove_from_cart&amp;id=<?= urlencode($it['id']) ?>&amp;redirect=index.php?page=giohang">
                  Xóa
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-3">
      <a class="btn btn-outline-secondary" href="index.php">Tiếp tục mua sắm</a>
      <div class="d-flex gap-2">
        <a class="btn btn-outline-danger" href="index.php?action=clear_cart&amp;redirect=index.php?page=giohang">Xóa giỏ hàng</a>
        <a class="btn btn-success" href="index.php?page=pay">Thanh toán</a>
      </div>
    </div>
  <?php endif; ?>
</section>