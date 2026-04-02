<?php
require_once __DIR__ . '/../../../login/auth.php';
require_admin_login();
require_admin_permission('NXB');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa nhà xuất bản</title>
    <link rel="icon" type="image/png" href="/assets/img/logo-library/library.png">
    <style>
      body {font-family: Arial, Helvetica, sans-serif; background: #f3f4f8; margin:0; padding:0;}
      .container {width: 95%; max-width: 700px; margin: 2rem auto;}
      .panel {background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 16px; box-shadow: 0 2px 8px rgba(0,0,0,.08);}
      .panel h2 {margin: 0 0 1rem; color:#333;}
      .form-grid {display:grid; grid-template-columns:1fr; gap:12px;}
      .form-group label {display:block; font-weight:600; margin-bottom:4px;}
      .form-group input[type="text"] {width:100%; padding:8px 10px; border:1px solid #bbb; border-radius:5px;}
      .form-actions {display:flex; gap:10px; margin-top:12px;}
      .btn {background:#007bff; color:#fff; border:none; padding:8px 14px; border-radius:5px; cursor:pointer; text-decoration:none; text-align:center;}
      .btn:hover {background:#0056b3;}
      .btn-secondary {background:#6c757d;}
      .btn-secondary:hover {background:#5a6268;}
    </style>
</head>
<body>
    <div class="container">
      <div class="panel">
        <h2>Sửa nhà xuất bản</h2>
    <?php
    $manxb = $_GET['manxb'] ?? '';
    $tennxb = $_GET['tennxb'] ?? '';
    $diachi = $_GET['diachi'] ?? '';
    $sdt = $_GET['sdt'] ?? '';
    $email = $_GET['email'] ?? '';
    ?>

    <div class="FormThongTin">
        <form action="QL_NhaXuatBan.php" method="get" class="form-grid">
            <div class="form-group">
                <label for="manxb">Mã nhà xuất bản</label>
                <input type="text" id="manxb" name="manxb" value="<?php echo htmlspecialchars($manxb); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="tennxb">Tên nhà xuất bản</label>
                <input type="text" id="tennxb" name="tennxb" value="<?php echo htmlspecialchars($tennxb); ?>" required>
            </div>
            <div class="form-group">
                <label for="diachi">Địa chỉ</label>
                <input type="text" id="diachi" name="diachi" value="<?php echo htmlspecialchars($diachi); ?>" required>
            </div>
            <div class="form-group">
                <label for="sdt">Số điện thoại</label>
                <input type="text" id="sdt" name="sdt" value="<?php echo htmlspecialchars($sdt); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>

            <input type="hidden" name="luachon" value="Sua">

            <div class="form-actions">
                <button type="submit" class="btn">Cập nhật</button>
                <a href="QL_NhaXuatBan.php" class="btn btn-secondary">Hủy</a>
            </div>
        </form>
    </div>
</body>
</html>