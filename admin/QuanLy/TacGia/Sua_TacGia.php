<?php
require_once __DIR__ . '/../../auth.php';
require_admin_login();
require_admin_permission('TACGIA');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa tác giả</title>
    <link rel="icon" type="image/png" href="/assets/img/logo-library/library.png">
    <style>
      body {font-family: Arial, sans-serif; background: #f3f4f8; margin:0; padding:0;}
      .container {width:95%;max-width:700px;margin:2rem auto;}
      .panel {background:#fff;border:1px solid #ddd;border-radius:8px;padding:20px;box-shadow:0 2px 8px rgba(0,0,0,.08);}
      .panel h2 {margin-top:0;color:#333;}
      .form-grid {display:grid;grid-template-columns:1fr;gap:12px;}
      .form-group label {display:block;font-weight:600;margin-bottom:4px;}
      .form-group input[type=text] {width:100%;padding:8px 10px;border:1px solid #bbb;border-radius:5px;}
      .form-actions {display:flex;gap:10px;margin-top:10px;}
      .btn {background:#007bff;color:#fff;border:none;padding:8px 14px;border-radius:5px;cursor:pointer;text-decoration:none;text-align:center;}
      .btn:hover {background:#0056b3;}
      .btn-secondary {background:#6c757d;}
      .btn-secondary:hover {background:#5a6268;}
    </style>
</head>
<body>
    <?php
    $matacgia = $_GET['matacgia'] ?? '';
    $tentacgia = $_GET['tentacgia'] ?? '';
    ?>

    <div class="container">
      <div class="panel">
        <h2>Sửa thông tin tác giả</h2>
        <form action="QL_TacGia.php" method="get" class="form-grid">
          <div class="form-group">
            <label for="matacgia">Mã tác giả</label>
            <input type="text" id="matacgia" name="matacgia" value="<?php echo htmlspecialchars($matacgia); ?>" readonly>
          </div>
          <div class="form-group">
            <label for="tentacgia">Tên tác giả</label>
            <input type="text" id="tentacgia" name="tentacgia" value="<?php echo htmlspecialchars($tentacgia); ?>" required>
          </div>

          <input type="hidden" name="luachon" value="Sua">

          <div class="form-actions">
            <button type="submit" class="btn">Cập nhật</button>
            <a class="btn btn-secondary" href="QL_TacGia.php">Hủy</a>
          </div>
        </form>
      </div>
    </div>
</body>
</html>