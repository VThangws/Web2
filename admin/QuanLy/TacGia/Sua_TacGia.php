<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quản lý tác giả</title>
</head>
<body>
  <?php
    // lấy thông tin tác giả muốn sửa
    $matacgia = $_GET['matacgia'];
    $tentacgia = $_GET['tentacgia'];

  ?>
  <div class="KhungThongTin">
    <form action="QL_TacGia.php?" method="GET">
      <label for="matacgia">Mã tác giả</label>
      <input type="text" id="matacgia" name="matacgia" value="<?php echo $matacgia?>"><br>
      <label for="tentacgia">Tên tác giả</label>
      <input type="text" id="tentacgia" name="tentacgia" value="<?php echo $tentacgia?>"><br>
      
      <!-- đánh dấu lựa chọn là sửa -->
      <input type="radio" id="luachon" name="luachon" value="Sua" checked style="display: none;">

      
      <input type="submit" value="Cập nhật">
    </form>
  </div>
</body>
</html>