<?php
require_once __DIR__ . '/../../../login/auth.php';
require_admin_login();
require_admin_permission('THELOAI');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa thể loại</title>
    <link rel="icon" type="image/png" href="/assets/img/logo-library/library.png">
    <style>
      body { font-family: Arial, Helvetica, sans-serif; background: #f3f4f8; margin: 0; padding: 0; }
      .container { width: 95%; max-width: 650px; margin: 2rem auto; }
      .panel { background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 16px; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
      .panel h2 { margin-top: 0; color: #333; font-size: 1.3rem; }
      .form-grid { display: grid; grid-template-columns: 1fr; gap: 12px; }
      .form-grid label { font-weight: 600; margin-bottom: 4px; display: block; }
      .form-grid input[type='text'] { width: 100%; padding: 8px 10px; border: 1px solid #bbb; border-radius: 5px; }
      .form-actions { margin-top: 14px; display: flex; gap: 10px; }
      .btn { background: #007bff; color: #fff; border: none; padding: 8px 14px; border-radius: 5px; cursor: pointer; text-decoration:none; text-align:center; }
      .btn:hover { background: #0056b3; }
      .btn-secondary { background: #6c757d; }
      .btn-secondary:hover { background: #5a6268; }
    </style>
</head>
<body>
    <div class="container">
      <div class="panel">
        <h2>Sửa thể loại</h2>
    <?php
    $matheloai = $_GET['matheloai'] ?? '';
    $tentheloai = $_GET['tentheloai'] ?? '';
    ?>

    <div class="KhungThongTin">
        <form action="QL_TheLoai.php" method="get" class="form-grid">
            <div class="form-group">
                <label for="matheloai">Mã thể loại</label>
                <input type="text" id="matheloai" name="matheloai" value="<?php echo htmlspecialchars($matheloai); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="tentheloai">Tên thể loại</label>
                <input type="text" id="tentheloai" name="tentheloai" value="<?php echo htmlspecialchars($tentheloai); ?>" required>
            </div>

            <input type="hidden" name="luachon" value="Sua">

            <div class="form-actions">
                <button type="submit" class="btn">Cập nhật</button>
                <a href="QL_TheLoai.php" class="btn btn-secondary">Hủy</a>
            </div>
        </form>
    </div>
</body>
</html>