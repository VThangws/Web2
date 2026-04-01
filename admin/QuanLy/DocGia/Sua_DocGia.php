<?php
require_once __DIR__ . '/../../auth.php';
require_admin_login();
require_admin_permission('DOCGIA');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa đọc giả</title>
    <link rel="icon" type="image/png" href="/assets/img/logo-library/library.png">
    <style>
        body {font-family: Arial, Helvetica, sans-serif; background: #f3f4f8; margin:0; padding:0;}
        .container {width: 95%; max-width: 700px; margin: 2rem auto;}
        .panel {background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 16px; box-shadow:0 2px 8px rgba(0,0,0,.08);}
        .panel h2 {font-size: 1.3rem; margin-bottom: 1rem;}
        .form-group {margin-bottom:12px;}
        .form-group label {display:block;margin-bottom:4px;font-weight:600;}
        .form-group input {width:100%;padding:8px 10px;border:1px solid #bbb;border-radius:4px;}
        .actions {margin-top:14px; display:flex; gap:10px;}
        .btn, .btn-secondary {background:#007bff;color:#fff;border:none;padding:8px 14px;border-radius:5px;cursor:pointer;text-decoration:none;}
        .btn-secondary {background:#6c757d;}
        .btn:hover {background:#0056b3;}
        .btn-secondary:hover {background:#5a6268;}
    </style>
</head>
<body>
  <div class="container">
    <div class="panel">
      <h2>Sửa thông tin đọc giả</h2>
      <?php
      require_once '../../../database/KetNoiDB.php';
      require_once '../../../model/DocGia.php';
      require_once '../../../DAO/DocGiaDAO.php';
      $dao = new DocGiaDAO();
      $madocgia = $_GET['madocgia'] ?? '';
      $docgia = $dao->Lay1DocGia($madocgia);
      ?>
      <form action="QL_DocGia.php" method="get" class="form-grid">
        <div class="form-group">
          <label for="madocgia">Mã đọc giả</label>
          <input type="text" id="madocgia" name="madocgia" value="<?php echo $docgia->getMadocgia(); ?>" readonly>
        </div>
        <div class="form-group">
          <label for="hodocgia">Họ</label>
          <input type="text" id="hodocgia" name="hodocgia" value="<?php echo $docgia->getHodocgia(); ?>" required>
        </div>
        <div class="form-group">
          <label for="tendocgia">Tên</label>
          <input type="text" id="tendocgia" name="tendocgia" value="<?php echo $docgia->getTendocgia() ?>" required>
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" value="<?php echo $docgia->getEmail(); ?>" required>
        </div>
        <div class="form-group">
          <label for="sdt">Số điện thoại</label>
          <input type="text" id="sdt" name="sdt" value="<?php echo $docgia->getSdt(); ?>" required>
        </div>
        <div class="form-group">
          <label for="ngaysinh">Ngày sinh</label>
          <input type="date" id="ngaysinh" name="ngaysinh" value="<?php echo $docgia->getNgaysinh(); ?>" required>
        </div>
        <div class="form-group">
          <label for="diachi">Địa chỉ</label>
          <input type="text" id="diachi" name="diachi" value="<?php echo $docgia->getDiachi(); ?>" required>
        </div>

        <input type="hidden" name="luachon" value="Sua">

        <div class="actions">
          <button type="submit" class="btn">Cập nhật</button>
          <a href="QL_DocGia.php" class="btn-secondary">Hủy</a>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
