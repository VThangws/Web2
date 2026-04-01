<?php
require_once __DIR__ . '/../../../auth.php';
require_admin_login();
require_admin_permission('NXB');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý nhà xuất bản</title>
    <link rel="icon" type="image/png" href="/assets/img/logo-library/library.png">
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
</head>
<body>
    <?php require_once __DIR__ . '/../../../layout/admin_sidebar.php'; ?>

    <?php
    require_once dirname(__DIR__, 4) . '/DAO/Sach/NhaXuatBanDAO.php';
    require_once dirname(__DIR__, 4) . '/database/ConnectDB.php';

    $conn = ConnectDB::getInstance()->getConnection();
    $dao = new NhaXuatBanDAO();

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $luachon = $_GET['luachon'] ?? '';

        if ($luachon === 'Them') {
            if (
                empty($_GET['manxb'])
                || empty($_GET['tennxb'])
                || empty($_GET['diachi'])
                || empty($_GET['sdt'])
                || empty($_GET['email'])
            ) {
                echo "<script>alert('Thông tin nhà xuất bản không được để trống!');</script>";
            } else {
                $manxb = $_GET['manxb'];
                $tennxb = $_GET['tennxb'];
                $diachi = $_GET['diachi'];
                $sdt = $_GET['sdt'];
                $email = $_GET['email'];
                $dao->Them($conn, $manxb, $tennxb, $diachi, $sdt, $email);
            }
        } elseif ($luachon === 'Xoa') {
            $manxb = $_GET['manxb'] ?? '';
            $dao->Xoa($conn, $manxb);
        } elseif ($luachon === 'Sua') {
            $manxb = $_GET['manxb'] ?? '';
            $tennxb = $_GET['tennxb'] ?? '';
            $diachi = $_GET['diachi'] ?? '';
            $sdt = $_GET['sdt'] ?? '';
            $email = $_GET['email'] ?? '';
            $dao->Sua($conn, $manxb, $tennxb, $diachi, $sdt, $email);
        }
    }
    ?>

    <div class="KhungThongTin">
        <form method="get">
            <label for="manxb">Mã nhà xuất bản</label>
            <input type="text" id="manxb" name="manxb"><br>
            <label for="tennxb">Tên nhà xuất bản</label>
            <input type="text" id="tennxb" name="tennxb"><br>
            <label for="diachi">Địa chỉ</label>
            <input type="text" id="diachi" name="diachi"><br>
            <label for="sdt">Số điện thoại</label>
            <input type="text" id="sdt" name="sdt"><br>
            <label for="email">Email</label>
            <input type="text" id="email" name="email"><br>

            <input type="radio" id="luachon" name="luachon" value="Them" checked style="display: none;">
            <input type="submit" value="Thêm">
        </form>
    </div>

    <div class="KhungDanhSach">
        <table>
            <tr>
                <th>Mã nhà xuất bản</th>
                <th>Tên nhà xuất bản</th>
                <th>Địa chỉ</th>
                <th>Số điện thoại</th>
                <th>Email</th>
                <th>Hành động</th>
            </tr>
            <?php
            $result = $dao->LayToanBoDanhSach($conn);
            foreach ($result as $item) {
                echo '<tr>';
                echo '<td>' . $item->getManxb() . '</td>';
                echo '<td>' . $item->getTennxb() . '</td>';
                echo '<td>' . $item->getDiachi() . '</td>';
                echo '<td>' . $item->getSdt() . '</td>';
                echo '<td>' . $item->getEmail() . '</td>';
                echo '<td>';
                echo "<a href='Sua_NhaXuatBan.php?manxb=" . $item->getManxb() . "&tennxb=" . $item->getTennxb() . "&diachi=" . $item->getDiachi() . "&sdt=" . $item->getSdt() . "&email=" . $item->getEmail() . "'>Sửa</a> ";
                echo "<a href='QL_NhaXuatBan.php?luachon=Xoa&manxb=" . $item->getManxb() . "'>Xóa</a>";
                echo '</td>';
                echo '</tr>';
            }
            ?>
        </table>
    </div>
</body>
</html>