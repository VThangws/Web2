<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <?php
    require_once "../../../model/DauSach.php";
    require_once "../../../DAO/DauSachDAO.php";
    require_once "../../../database/ConnectDB.php";
    $dao = new DauSachDAO();
    // lấy thông tin đầu sách
    $dausach = $dao->getDauSach($conn, $_REQUEST['madausach']);
  ?>
  <div class="KhungThongTin">
    <form action="QL_DauSach.php" method="post" enctype="multipart/form-data">
      <label for="madausach">Mã đầu sách</label>
      <input type="text" id="madausach" name="madausach" value="<?php echo $dausach->getMadausach();?>" required><br>
      <label for="tensach">Tên sách</label>
      <input type="text" id="tensach" name="tensach" value="<?php echo $dausach->getTensach();?>" required><br>
      <label for="namxuatban">Năm xuất bản</label>
      <input type="text" id="namxuatban" name="namxuatban" value="<?php echo $dausach->getNamxuatban();?>" required><br>
      <label for="dongia">Đơn giá</label>
      <input type="text" id="dongia" name="dongia" value="<?php echo $dausach->getDongia();?>" required><br>
      <label for="matacgia">Mã tác giả</label>
      <input type="text" id="matacgia" name="matacgia" value="<?php echo $dausach->getMatacgia();?>" required><br>
      <label for="matheloai">Mã thể loại</label>
      <input type="text" id="matheloai" name="matheloai" value="<?php echo $dausach->getMatheloai();?>" required><br>
      <label for="manxb">Mã nhà xuất bản</label>
      <input type="text" id="manxb" name="manxb" value="<?php echo $dausach->getManxb();?>" required><br>
      <label for="mota">Mô tả</label>
      <input type="text" id="mota" name="mota" value="<?php echo $dausach->getMota();?>" required><br>
      <label for="anhbia">Ảnh bìa</label>
      <input type="file" id="anhbia" name="anhbia" accept="image/*"><br>
      <img alt="Ảnh xem trước" width="100px" src="<?php echo $dausach->getAnhbia();?>"/>
      <input type="submit" name="luachon" value="Sua">
    </form>
  </div>
</body>
</html>