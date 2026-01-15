<?php
// User account placeholder
$tab = $_GET['page'] ?? 'taikhoan';
$tabs = [
  'taikhoan' => 'Tài khoản',
  'danhsachdiachi' => 'Địa chỉ',
  'donhang' => 'Đơn hàng',
];
if (!isset($tabs[$tab])) { $tab = 'taikhoan'; }
?>
<section class="container py-5">
  <h1 class="h4 mb-4">Tài khoản của tôi</h1>

  <ul class="nav nav-pills mb-3">
    <li class="nav-item"><a class="nav-link <?= $tab==='taikhoan'?'active':'' ?>" href="/index.php?page=taikhoan">Thông tin</a></li>
    <li class="nav-item"><a class="nav-link <?= $tab==='danhsachdiachi'?'active':'' ?>" href="/index.php?page=danhsachdiachi">Địa chỉ</a></li>
    <li class="nav-item"><a class="nav-link <?= $tab==='donhang'?'active':'' ?>" href="/index.php?page=donhang">Đơn hàng</a></li>
  </ul>

  <div class="card">
    <div class="card-body">
      <?php if ($tab === 'taikhoan'): ?>
        <h2 class="h5">Thông tin cá nhân</h2>
        <p class="text-muted">Chức năng cập nhật thông tin sẽ được bổ sung.</p>
      <?php elseif ($tab === 'danhsachdiachi'): ?>
        <h2 class="h5">Sổ địa chỉ</h2>
        <p class="text-muted">Danh sách địa chỉ đang được phát triển.</p>
      <?php elseif ($tab === 'donhang'): ?>
        <h2 class="h5">Đơn hàng của bạn</h2>
        <p class="text-muted">Lịch sử đơn hàng đang được phát triển.</p>
      <?php endif; ?>
    </div>
  </div>
</section>