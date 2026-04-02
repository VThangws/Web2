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
    <title>Sửa cuốn sách</title>
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
    </style>
</head>
<body>
    <?php
    require_once __DIR__ . '/../../../../database/KetNoiDB.php';
    require_once __DIR__ . '/../../../../model/Sach/CuonSach.php';
    require_once __DIR__ . '/../../../../DAO/Sach/CuonSachDAO.php';

    $dao = new CuonSachDAO();
    $cuonsach = $dao->Lay1CuonSach($conn, $_REQUEST['macuonsach']);
    ?>

    <div class="container">
      <div class="panel">
        <h2>Sửa cuốn sách</h2>
        <form method="get" action="QL_CuonSach.php">
            <div class="form-group">
                <label for="macuonsach">Mã cuốn sách</label>
                <input type="text" id="macuonsach" name="macuonsach" value="<?php echo $cuonsach->getMacuonsach(); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="madausach">Mã đầu sách</label>
                <input type="text" id="madausach" name="madausach" value="<?php echo $cuonsach->getMadausach(); ?>" required>
            </div>
            <div class="form-group">
                <label for="mavitri">Mã vị trí</label>
                <input type="text" id="mavitri" name="mavitri" value="<?php echo $cuonsach->getMavitri(); ?>" required>
            </div>
            <div class="form-group">
                <label for="trangthai">Trạng thái</label>
                <input type="text" id="trangthai" name="trangthai" value="<?php echo $cuonsach->getTrangthai(); ?>" required>
            </div>
            <div class="form-group">
                <label for="tinhtrang">Tình trạng</label>
                <input type="text" id="tinhtrang" name="tinhtrang" value="<?php echo $cuonsach->getTinhtrang(); ?>" required>
            </div>
            <div class="actions">
              <button type="submit" id="luachon" name="luachon" value="Sua" class="btn">Cập nhật</button>
              <a href="QL_CuonSach.php" class="btn-secondary">Hủy</a>
            </div>
        </form>
      </div>
    </div>
</body>
</html>