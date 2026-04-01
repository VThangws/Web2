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
    <title>Sửa thể loại</title>
    <link rel="icon" type="image/png" href="/assets/img/logo-library/library.png">
</head>
<body>
    <?php
    $matheloai = $_GET['matheloai'] ?? '';
    $tentheloai = $_GET['tentheloai'] ?? '';
    ?>

    <div class="KhungThongTin">
        <form action="QL_TheLoai.php" method="get">
            <label for="matheloai">Mã thể loại: </label>
            <input type="text" id="matheloai" name="matheloai" value="<?php echo $matheloai; ?>"><br>
            <label for="tentheloai">Tên thể loại: </label>
            <input type="text" id="tentheloai" name="tentheloai" value="<?php echo $tentheloai; ?>"><br>

            <input type="radio" id="luachon" name="luachon" value="Sua" checked style="display: none"><br>
            <input type="submit" value="Cập nhật">
        </form>
    </div>
</body>
</html>