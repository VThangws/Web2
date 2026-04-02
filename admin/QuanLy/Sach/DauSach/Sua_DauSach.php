<?php
require_once __DIR__ . '/../../../auth.php';
require_admin_login();
require_admin_permission('SACH');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa đầu sách</title>
    <link rel="icon" type="image/png" href="/assets/img/logo-library/library.png">
    <style>
        body {font-family: Arial, Helvetica, sans-serif; background: #f3f4f8; margin: 0; padding: 0;}
        .container {width: 95%; max-width: 760px; margin: 2rem auto;}
        .panel {background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; box-shadow:0 2px 8px rgba(0,0,0,.08);}
        .panel h2 {font-size: 1.5rem; margin-bottom: 1rem;}
        .form-group {margin-bottom: 14px;}
        .form-group label {display: block; margin-bottom: 5px; font-weight: 600;}
        .form-group input[type="text"],
        .form-group input[type="date"],
        .form-group input[type="email"],
        .form-group input[type="number"],
        .form-group input[type="file"] {width: 100%; padding: 9px 10px; border: 1px solid #bbb; border-radius: 4px; box-sizing: border-box;}
        .actions {margin-top: 16px; display: flex; gap: 10px; align-items: center;}
        .btn, .btn-secondary {background: #007bff; color: #fff; border: none; padding: 10px 16px; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block;}
        .btn-secondary {background: #6c757d;}
        .btn:hover {background: #0056b3;}
        .btn-secondary:hover {background: #5a6268;}
        .thumbnail {margin-top: 10px; max-width: 140px; border: 1px solid #ddd; border-radius: 4px;}
    </style>
</head>
<body>
  <?php
    require_once "../../../../model/Sach/DauSach.php";
    require_once "../../../../DAO/Sach/DauSachDAO.php";
    require_once "../../../../database/KetNoiDB.php";
    $dao = new DauSachDAO();

    $madausach = $_REQUEST['madausach'] ?? '';
    if ($madausach === '') {
        die('Thiếu mã đầu sách');
    }
    $dausach = $dao->getDauSach($conn, $madausach);
    ?>

    <div class="container">
      <div class="panel">
        <h2>Sửa đầu sách</h2>
        <form action="QL_DauSach.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="madausach">Mã đầu sách</label>
                <input type="text" id="madausach" name="madausach" value="<?php echo $dausach->getMadausach(); ?>" required readonly>
            </div>
            <div class="form-group">
                <label for="tensach">Tên sách</label>
                <input type="text" id="tensach" name="tensach" value="<?php echo $dausach->getTensach(); ?>" required>
            </div>
            <div class="form-group">
                <label for="namxuatban">Năm xuất bản</label>
                <input type="text" id="namxuatban" name="namxuatban" value="<?php echo $dausach->getNamxuatban(); ?>" required>
            </div>
            <div class="form-group">
                <label for="dongia">Đơn giá</label>
                <input type="text" id="dongia" name="dongia" value="<?php echo $dausach->getDongia(); ?>" required>
            </div>
            <div class="form-group">
                <label for="matacgia">Mã tác giả</label>
                <input type="text" id="matacgia" name="matacgia" value="<?php echo $dausach->getMatacgia(); ?>" required>
            </div>
            <div class="form-group">
                <label for="matheloai">Mã thể loại</label>
                <input type="text" id="matheloai" name="matheloai" value="<?php echo $dausach->getMatheloai(); ?>" required>
            </div>
            <div class="form-group">
                <label for="manxb">Mã nhà xuất bản</label>
                <input type="text" id="manxb" name="manxb" value="<?php echo $dausach->getManxb(); ?>" required>
            </div>
            <div class="form-group">
                <label for="mota">Mô tả</label>
                <input type="text" id="mota" name="mota" value="<?php echo $dausach->getMota(); ?>" required>
            </div>
            <div class="form-group">
                <label for="anhbia">Ảnh bìa</label>
                <input type="file" id="anhbia" name="anhbia" accept="image/*">
            </div>
            <div class="form-group">
                <label>Ảnh hiện tại</label>
                <img class="thumbnail" alt="Ảnh xem trước" src="<?php echo $dausach->getAnhbia(); ?>"/>
            </div>
            <div class="actions">
              <button type="submit" class="btn">Cập nhật</button>
              <a href="QL_DauSach.php" class="btn-secondary">Hủy</a>
            </div>
        </form>
      </div>
    </div>
</body>
</html>