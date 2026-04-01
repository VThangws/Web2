<?php
require_once __DIR__ . '/../../../auth.php';
require_admin_login();
require_admin_permission('THELOAI');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý thể loại</title>
    <link rel="icon" type="image/png" href="/assets/img/logo-library/library.png">
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
</head>
<body>
    <?php require_once __DIR__ . '/../../../layout/admin_sidebar.php'; ?>

    <?php
    require_once dirname(__DIR__, 4) . '/database/ConnectDB.php';
    require_once dirname(__DIR__, 4) . '/DAO/Sach/TheLoaiDAO.php';

    $conn = ConnectDB::getInstance()->getConnection();
    $dao = new TheLoaiDAO();

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $luachon = $_GET['luachon'] ?? '';

        if ($luachon === 'Them') {
            if (empty($_GET['matheloai']) || empty($_GET['tentheloai'])) {
                echo "<script>alert('Thông tin thể loại không được để trống!');</script>";
            } else {
                $matheloai = $_GET['matheloai'];
                $tentheloai = $_GET['tentheloai'];
                $dao->Them($conn, $matheloai, $tentheloai);
            }
        } elseif ($luachon === 'Xoa') {
            $matheloai = $_GET['matheloai'] ?? '';
            $dao->Xoa($conn, $matheloai);
            echo "<script>alert('Đã xóa thông tin thể loại!');</script>";
        } elseif ($luachon === 'Sua') {
            $matheloai = $_GET['matheloai'] ?? '';
            $tentheloai = $_GET['tentheloai'] ?? '';
            $dao->Sua($conn, $matheloai, $tentheloai);
        }
    }
    ?>

    <div class="KhungThongTin">
        <form method="get">
            <label for="matheloai">Mã thể loại:</label>
            <input type="text" id="matheloai" name="matheloai"><br>
            <label for="tentheloai">Tên thể loại:</label>
            <input type="text" id="tentheloai" name="tentheloai"><br>

            <input type="radio" id="luachon" name="luachon" value="Them" checked style="display: none">
            <input type="submit" value="Thêm">
        </form>
    </div>

    <div class="KhungDanhSach">
        <table>
            <tr>
                <th>Mã thể loại</th>
                <th>Tên thể loại</th>
                <th>Hành động</th>
                <th></th>
            </tr>
            <?php
            $theloais = $dao->LayDanhSachTheLoai($conn);
            foreach ($theloais as $theloai) {
                echo '<tr>';
                echo '<td>' . $theloai->getMatheloai() . '</td>';
                echo '<td>' . $theloai->getTentheloai() . '</td>';
                echo "<td><a href='Sua_TheLoai.php?luachon=Sua&matheloai=" . $theloai->getMatheloai() . "&tentheloai=" . $theloai->getTentheloai() . "'>Sửa</a></td>";
                echo "<td><a href='QL_TheLoai.php?luachon=Xoa&matheloai=" . $theloai->getMatheloai() . "'>Xóa</a></td>";
                echo '</tr>';
            }
            ?>
        </table>
    </div>
</body>
</html>