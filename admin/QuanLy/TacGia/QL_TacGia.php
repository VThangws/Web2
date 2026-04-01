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
    <title>Quản lý tác giả</title>
    <link rel="icon" type="image/png" href="/assets/img/logo-library/library.png">
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
</head>
<body>
    <?php require_once __DIR__ . '/../../layout/admin_sidebar.php'; ?>

    <?php
    require_once __DIR__ . '/../../../database/ConnectDB.php';
    require_once dirname(__DIR__, 3) . '/DAO/Sach/TacGiaDAO.php';

    $conn = ConnectDB::getInstance()->getConnection();
    $dao = new TacGiaDAO();

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $luachon = $_GET['luachon'] ?? '';

        if ($luachon === 'Them') {
            if (empty($_GET['matacgia']) || empty($_GET['tentacgia'])) {
                echo "<script>alert('Thông tin tác giả không được để trống!');</script>";
            } else {
                $matacgia = $_GET['matacgia'];
                $tentacgia = $_GET['tentacgia'];
                $dao->Them($conn, $matacgia, $tentacgia);
            }
        } elseif ($luachon === 'Xoa') {
            $matacgia = $_GET['matacgia'] ?? '';
            $dao->Xoa($conn, $matacgia);
        } elseif ($luachon === 'Sua') {
            $matacgia = $_GET['matacgia'] ?? '';
            $tentacgia = $_GET['tentacgia'] ?? '';
            $dao->Sua($conn, $matacgia, $tentacgia);
        }
    }
    ?>

    <div class="KhungThongTin">
        <form method="get">
            <label for="matacgia">Mã tác giả</label>
            <input type="text" id="matacgia" name="matacgia"><br>
            <label for="tentacgia">Tên tác giả</label>
            <input type="text" id="tentacgia" name="tentacgia"><br>

            <input type="radio" name="luachon" value="Them">Thêm mới tác giả
            <input type="radio" name="luachon" value="Sua">Sửa thông tin tác giả<br>

            <input type="submit" value="OK">
        </form>
    </div>

    <div class="KhungDanhSach">
        <table>
            <tr>
                <th>Mã tác giả</th>
                <th>Tên tác giả</th>
                <th>Cập nhập</th>
                <th>Xóa tác giả</th>
            </tr>
            <?php
            $result = $dao->ToanBoDanhSach($conn);
            while ($row = $result->fetch_assoc()) {
                echo "
                    <tr>
                        <td>" . $row['matacgia'] . "</td>
                        <td>" . $row['tentacgia'] . "</td>
                        <td><a href='Sua_TacGia.php?matacgia=" . $row['matacgia'] . "&tentacgia=" . $row['tentacgia'] . "'>Sửa</a></td>
                        <td><a href='QL_TacGia.php?luachon=Xoa&matacgia=" . $row['matacgia'] . "'>Xóa</a></td>
                    </tr>
                ";
            }
            ?>
        </table>
    </div>
</body>
</html>