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
    <title>Quản lý cuốn sách</title>
    <link rel="icon" type="image/png" href="/assets/img/logo-library/library.png">
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
</head>
<body>
    <?php require_once __DIR__ . '/../../../layout/admin_sidebar.php'; ?>

    <?php
    require_once __DIR__ . '/../../../../database/KetNoiDB.php';
    require_once __DIR__ . '/../../../../model/Sach/CuonSach.php';
    require_once __DIR__ . '/../../../../DAO/Sach/CuonSachDAO.php';

    $dao = new CuonSachDAO();

    if ($_SERVER['REQUEST_METHOD'] === 'GET' || $_SERVER['REQUEST_METHOD'] === 'POST') {
        $luachon = $_REQUEST['luachon'] ?? '';

        if ($luachon === 'Them' || $luachon === 'Sua') {
            $macuonsach = $_REQUEST['macuonsach'] ?? '';
            $madausach = $_REQUEST['madausach'] ?? '';
            $mavitri = $_REQUEST['mavitri'] ?? '';
            $trangthai = $_REQUEST['trangthai'] ?? '';
            $tinhtrang = $_REQUEST['tinhtrang'] ?? '';

            if ($luachon === 'Them') {
                $dao->Them($conn, $macuonsach, $madausach, $mavitri, $trangthai, $tinhtrang);
            } else {
                $dao->Sua($conn, $macuonsach, $madausach, $mavitri, $trangthai, $tinhtrang);
            }
        } elseif ($luachon === 'Xoa') {
            $macuonsach = $_REQUEST['macuonsach'] ?? '';
            if ($macuonsach !== '') {
                $dao->Xoa($conn, $macuonsach);
            }
        }
    }
    ?>

    <div class="KhungThongTin">
        <form method="get">
            <label for="macuonsach">Mã cuốn sách</label>
            <input type="text" id="macuonsach" name="macuonsach"/><br>
            <label for="madausach">Mã đầu sách</label>
            <input type="text" id="madausach" name="madausach"/><br>
            <label for="mavitri">Mã vị trí</label>
            <input type="text" id="mavitri" name="mavitri"/><br>
            <label for="trangthai">Trạng thái</label>
            <input type="text" id="trangthai" name="trangthai"/><br>
            <label for="tinhtrang">Tình trạng</label>
            <input type="text" id="tinhtrang" name="tinhtrang"/><br>
            <input type="submit" id="luachon" name="luachon" value="Them"/>
        </form>
    </div>

    <div class="KhungDanhSach">
        <table>
            <tr>
                <th>Mã cuốn sách</th>
                <th>Mã đầu sách</th>
                <th>Mã vị trí</th>
                <th>Trạng thái</th>
                <th>Tình trạng</th>
                <th>Hành động</th>
            </tr>
            <?php
            $danhsach = $dao->LayToanBoDanhSach($conn);
            foreach ($danhsach as $item) {
                echo '<tr>';
                echo '<td>' . $item->getMacuonsach() . '</td>';
                echo '<td>' . $item->getMadausach() . '</td>';
                echo '<td>' . $item->getMavitri() . '</td>';
                echo '<td>' . $item->getTrangthai() . '</td>';
                echo '<td>' . $item->getTinhtrang() . '</td>';
                echo "<td><a href='Sua_CuonSach.php?macuonsach=" . $item->getMacuonsach() . "'>Sửa</a> | <a href='QL_CuonSach.php?luachon=Xoa&macuonsach=" . $item->getMacuonsach() . "'>Xóa</a></td>";
                echo '</tr>';
            }
            ?>
        </table>
    </div>
</body>
</html>