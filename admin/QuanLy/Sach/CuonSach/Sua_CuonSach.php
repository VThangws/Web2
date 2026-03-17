<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <?php
    require_once "../../../../database/ConnectDB.php";
    require_once "../../../../model/Sach/CuonSach.php";
    require_once "../../../../DAO/Sach/CuonSachDAO.php";
    $dao = new CuonSachDAO();

    // lấy thông tin cuốn sách cần sửa
    $cuonsach = $dao->Lay1CuonSach($conn, $_REQUEST['macuonsach']);
  ?>
  <div class="KhungThongTin">
    <form method="get" action="QL_CuonSach.php">
      <label for="macuonsach">Mã cuốn sách</label>
      <input type="text" id="macuonsach" name="macuonsach" value="<?php echo $cuonsach->getMacuonsach();?>" readonly/><br>
      <label for="macuonsach">Mã đầu sách</label>
      <input type="text" id="madausach" name="madausach" value="<?php echo $cuonsach->getMadausach();?>" required/><br>
      <label for="macuonsach">Mã vị trí</label>
      <input type="text" id="mavitri" name="mavitri" value="<?php echo $cuonsach->getMavitri();?>" required/><br>
      <label for="macuonsach">Trạng thái</label>
      <input type="text" id="trangthai" name="trangthai" value="<?php echo $cuonsach->getTrangthai();?>" required/><br>
      <label for="macuonsach">Tình trạng</label>
      <input type="text" id="tinhtrang" name="tinhtrang" value="<?php echo $cuonsach->getTinhtrang();?>" required/><br>
      <input type="submit" id="luachon" name="luachon" value="Sua"/>
    </form>
  </div>
</body>
</html>