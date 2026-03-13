<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <?php
    // lấy thông tin đổ vào form
    $matheloai = $_GET['matheloai'];
    $tentheloai = $_GET['tentheloai'];
  ?>
  <div class="KhungThongTin">
    <form action="QL_TheLoai.php" method="GET">
      <label for="matheloai">Mã thể loại: </label>
      <input type="text" id="matheloai" name="matheloai" value="<?php echo $matheloai?>"><br>
      <label for="tentheloai">Tên thể loại: </label>
      <input type="text" id="tentheloai" name="tentheloai" value="<?php echo $tentheloai?>"><br>

      <!-- đánh dấu lựa chọn là sửa -->
      <input type="radio" id="luachon" name="luachon" value="Sua" checked style="display: none"><br>

      <input type="submit" value="Cập nhật">
    </form>
  </div>
</body>
</html>