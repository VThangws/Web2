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
</head>
<body>
    <?php
    require_once __DIR__ . '/../../../../database/KetNoiDB.php';
    require_once __DIR__ . '/../../../../model/Sach/CuonSach.php';
    require_once __DIR__ . '/../../../../DAO/Sach/CuonSachDAO.php';

    $dao = new CuonSachDAO();
    $cuonsach = $dao->Lay1CuonSach($conn, $_REQUEST['macuonsach']);
    ?>

    <div class="KhungThongTin">
        <form method="get" action="QL_CuonSach.php">
            <label for="macuonsach">Mã cuốn sách</label>
            <input type="text" id="macuonsach" name="macuonsach" value="<?php echo $cuonsach->getMacuonsach(); ?>" readonly/><br>
            <label for="madausach">Mã đầu sách</label>
            <input type="text" id="madausach" name="madausach" value="<?php echo $cuonsach->getMadausach(); ?>" required/><br>
            <label for="mavitri">Mã vị trí</label>
            <input type="text" id="mavitri" name="mavitri" value="<?php echo $cuonsach->getMavitri(); ?>" required/><br>
            <label for="trangthai">Trạng thái</label>
            <input type="text" id="trangthai" name="trangthai" value="<?php echo $cuonsach->getTrangthai(); ?>" required/><br>
            <label for="tinhtrang">Tình trạng</label>
            <input type="text" id="tinhtrang" name="tinhtrang" value="<?php echo $cuonsach->getTinhtrang(); ?>" required/><br>
            <input type="submit" id="luachon" name="luachon" value="Sua"/>
        </form>
    </div>
</body>
</html>