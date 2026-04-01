<?php
require_once __DIR__ . '/../../auth.php';
require_admin_login();
require_admin_permission('DOCGIA');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý độc giả</title>
    <link rel="icon" type="image/png" href="/assets/img/logo-library/library.png">
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
</head>
<body>
    <?php require_once __DIR__ . '/../../layout/admin_sidebar.php'; ?>

    <?php
    require_once __DIR__ . '/../../../database/ConnectDB.php';
    require_once __DIR__ . '/../../../DAO/DocGiaDAO.php';

    $conn = ConnectDB::getInstance()->getConnection();
    $dao = new DocGiaDAO();

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $luachon = $_GET['luachon'] ?? '';

        if ($luachon === 'Them') {
            if (
                empty($_GET['madocgia'])
                || empty($_GET['hodocgia'])
                || empty($_GET['tendocgia'])
                || empty($_GET['email'])
                || empty($_GET['sdt'])
                || empty($_GET['ngaysinh'])
                || empty($_GET['diachi'])
            ) {
                echo "<script>alert('Thông tin đọc giả không được bỏ trống!');</script>";
            } else {
                $madocgia = $_GET['madocgia'];
                $hodocgia = $_GET['hodocgia'];
                $tendocgia = $_GET['tendocgia'];
                $email = $_GET['email'];
                $sdt = $_GET['sdt'];
                $ngaysinh = $_GET['ngaysinh'];
                $diachi = $_GET['diachi'];

                $ok = $dao->Them($conn, $madocgia, $hodocgia, $tendocgia, $email, $sdt, $ngaysinh, $diachi);
                if ($ok) {
                    echo "<script>alert('Thêm đọc giả thành công!');</script>";
                } else {
                    echo "<script>alert('Thêm đọc giả không thành công!');</script>";
                }
            }
        } elseif ($luachon === 'Sua') {
            $madocgia = $_GET['madocgia'] ?? '';
            $hodocgia = $_GET['hodocgia'] ?? '';
            $tendocgia = $_GET['tendocgia'] ?? '';
            $email = $_GET['email'] ?? '';
            $sdt = $_GET['sdt'] ?? '';
            $ngaysinh = $_GET['ngaysinh'] ?? '';
            $diachi = $_GET['diachi'] ?? '';

            $ok = $dao->Sua($conn, $madocgia, $hodocgia, $tendocgia, $email, $sdt, $ngaysinh, $diachi);
            if ($ok) {
                echo "<script>alert('Cập nhật đọc giả thành công!');</script>";
            } else {
                echo "<script>alert('Cập nhật đọc giả không thành công!');</script>";
            }
        } elseif ($luachon === 'Xoa') {
            $madocgia = $_GET['madocgia'] ?? '';
            $sql = 'DELETE FROM docgia WHERE madocgia=?';
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $madocgia);
            if ($stmt->execute()) {
                echo '<script>alert("Đã xóa đọc giả!");</script>';
            } else {
                echo '<script>alert("Xóa thông tin đọc giả không thành công!");</script>';
            }
        }
    }
    ?>

    <div class="KhungThongTin">
        <form method="get">
            <label for="madocgia">Mã đọc giả</label>
            <input type="text" id="madocgia" name="madocgia"><br>
            <label for="hodocgia">Họ</label>
            <input type="text" id="hodocgia" name="hodocgia"><br>
            <label for="tendocgia">Tên</label>
            <input type="text" id="tendocgia" name="tendocgia"><br>
            <label for="email">Email</label>
            <input type="text" id="email" name="email"><br>
            <label for="sdt">Số điện thoại</label>
            <input type="text" id="sdt" name="sdt"><br>
            <label for="ngaysinh">Ngày sinh</label>
            <input type="date" id="ngaysinh" name="ngaysinh"><br>
            <label for="diachi">Địa chỉ</label>
            <input type="text" id="diachi" name="diachi"><br>

            <input type="radio" name="luachon" value="Them">Thêm đọc giả
            <input type="radio" name="luachon" value="Sua">Sửa thông tin đọc giả<br>
            <input type="submit" value="OK">
        </form>
    </div>

    <div class="KhungDanhSach">
        <table>
            <tr>
                <th>Mã đọc giả</th>
                <th>Họ</th>
                <th>Tên</th>
                <th>Email</th>
                <th>Số điện thoại</th>
                <th>Ngày sinh</th>
                <th>Địa chỉ</th>
                <th>Xóa đọc giả</th>
            </tr>
            <?php
            $result = $dao->ToanBoDanhSach($conn);
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    echo "
                        <tr>
                            <td>" . $row['madocgia'] . "</td>
                            <td>" . $row['hodocgia'] . "</td>
                            <td>" . $row['tendocgia'] . "</td>
                            <td>" . $row['email'] . "</td>
                            <td>" . $row['sdt'] . "</td>
                            <td>" . $row['ngaysinh'] . "</td>
                            <td>" . $row['diachi'] . "</td>
                            <td><a href='QL_DocGia.php?luachon=Xoa&madocgia=" . $row['madocgia'] . "'>Xóa</a></td>
                        </tr>
                    ";
                }
            }
            ?>
        </table>
    </div>
</body>
</html>