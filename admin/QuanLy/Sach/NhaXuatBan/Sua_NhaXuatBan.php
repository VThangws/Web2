<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <?php
    // lấy thông tin nhà xuất bản
    $manxb = $_GET['manxb'];
    $tennxb = $_GET['tennxb'];
    $diachi = $_GET['diachi'];
    $sdt = $_GET['sdt'];
    $email = $_GET['email'];
  ?>
  <div class="FormThongTin">
    <form action="QL_NhaXuatBan.php" method="get">
      <label for="manxb">Mã nhà xuất bản</label>
      <input type="text" id="manxb" name="manxb" value="<?php echo $manxb?>"><br>
      <label for="tennxb">Tên nhà xuất bản</label>
      <input type="text" id="tennxb" name="tennxb" value="<?php echo $tennxb?>"><br>
      <label for="manxb">Địa chỉ</label>
      <input type="text" id="diachi" name="diachi" value="<?php echo $diachi?>"><br>
      <label for="manxb">Số điện thoại</label>
      <input type="text" id="sdt" name="sdt" value="<?php echo $sdt?>"><br>
      <label for="manxb">Email</label>
      <input type="text" id="email" name="email" value="<?php echo $email?>"><br>

      <!-- đánh dấu lựa chọn là sửa -->
      <input type="radio" id="luachon" name="luachon" value="Sua" checked style="display: none;">
      <input type="submit" value="Cập nhật">
    </form>
  </div>
</body>
</html>