<?php
if (!defined('APP_BOOTSTRAPPED')) { http_response_code(404); exit; }

// Load latest books from DB if possible
$booksToShow = [];
try {
  $db = DBConnect::getInstance();
  $rows = $db->select("SELECT book_id, title, author, publisher, publish_year FROM Book ORDER BY publish_year DESC LIMIT 12");
  foreach ($rows as $r) {
    $booksToShow[] = [
      'id' => $r['book_id'],
      'title' => $r['title'] ?: 'Chưa rõ',
      'author' => $r['author'] ?: 'Không rõ tác giả',
      'price' => '-',
      'cover' => 'https://images.unsplash.com/photo-1495446815901-a7297e633e8d?q=80&w=600&auto=format&fit=crop',
    ];
  }
} catch (Throwable $e) {
  // ignore and use fallback
}

if (!$booksToShow) {
  $featuredBooks = [
      [ 'title' => 'Đắc Nhân Tâm', 'author' => 'Dale Carnegie', 'price' => '89.000đ', 'cover' => 'https://images.unsplash.com/photo-1519681393784-d120267933ba?q=80&w=600&auto=format&fit=crop' ],
      [ 'title' => 'Dế Mèn Phiêu Lưu Ký', 'author' => 'Tô Hoài', 'price' => '75.000đ', 'cover' => 'https://images.unsplash.com/photo-1473862170180-5377f99cded0?q=80&w=600&auto=format&fit=crop' ],
      [ 'title' => 'Totto-chan Bên Cửa Sổ', 'author' => 'Tetsuko Kuroyanagi', 'price' => '120.000đ', 'cover' => 'https://images.unsplash.com/photo-1495446815901-a7297e633e8d?q=80&w=600&auto=format&fit=crop' ],
      [ 'title' => 'Thi Nhân Việt Nam', 'author' => 'Hoài Thanh', 'price' => '140.000đ', 'cover' => 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?q=80&w=600&auto=format&fit=crop' ],
      [ 'title' => 'Quẳng Gánh Lo Đi...', 'author' => 'Dale Carnegie', 'price' => '95.000đ', 'cover' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?q=80&w=600&auto=format&fit=crop' ],
      [ 'title' => 'Nhà Giả Kim', 'author' => 'Paulo Coelho', 'price' => '110.000đ', 'cover' => 'https://images.unsplash.com/photo-1495446815901-66e2e1a0a6b5?q=80&w=600&auto=format&fit=crop' ],
  ];
  $booksToShow = $featuredBooks;
}
?>

<!-- Hero -->
<section class="hero text-center text-md-start">
  <div class="container container-narrow py-5">
    <div class="row align-items-center">
      <div class="col-12 col-md-9">
        <h1 class="mb-2">Chào Mừng Bạn Đến Với BOOK LIBRARY</h1>
        <p class="lead mb-0">Mua Sách, Tặng Tri Thức Cho Chính Mình Và Người Thân.</p>
      </div>
    </div>
  </div>
</section>

<!-- Featured Books -->
<section id="sach" class="py-5">
  <div class="container container-narrow">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <h2 class="section-title mb-0">Những Cuốn Sách Bạn Nên Biết</h2>
      <a href="#" class="btn btn-outline-secondary rounded-pill px-4">Xem Tất Cả</a>
    </div>
    <hr class="mb-4">

    <div class="row g-4">
      <?php foreach ($booksToShow as $book): ?>
        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
          <article class="book-card">
            <?php if (!empty($book['id'])): ?>
              <a href="/index.php?page=sanpham&amp;id=<?= urlencode($book['id']) ?>" class="text-decoration-none text-reset">
                <img class="book-thumb" src="<?= htmlspecialchars($book['cover']) ?>" alt="Bìa sách <?= htmlspecialchars($book['title']) ?>">
              </a>
            <?php else: ?>
              <img class="book-thumb" src="<?= htmlspecialchars($book['cover']) ?>" alt="Bìa sách <?= htmlspecialchars($book['title']) ?>">
            <?php endif; ?>
            <div class="mt-2">
              <div class="book-title" title="<?= htmlspecialchars($book['title']) ?>">
                <?php if (!empty($book['id'])): ?>
                  <a href="/index.php?page=sanpham&amp;id=<?= urlencode($book['id']) ?>" class="text-decoration-none text-reset">
                    <?= htmlspecialchars($book['title']) ?>
                  </a>
                <?php else: ?>
                  <?= htmlspecialchars($book['title']) ?>
                <?php endif; ?>
              </div>
              <div class="book-author"><?= htmlspecialchars($book['author']) ?></div>
              <?php if (!empty($book['price'])): ?><div class="price mt-1"><?= htmlspecialchars($book['price']) ?></div><?php endif; ?>
            </div>
          </article>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
