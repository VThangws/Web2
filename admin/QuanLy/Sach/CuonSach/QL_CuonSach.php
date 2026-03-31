<?php
require_once __DIR__ . '/../../../auth.php';
require_admin_login();
?>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <?php
    require_once "../../../../database/KetNoiDB.php";
    require_once "../../../../model/Sach/CuonSach.php";
    require_once "../../../../DAO/Sach/CuonSachDAO.php";
    $dao = new CuonSachDAO();
    
    if($_SERVER['REQUEST_METHOD'] == "GET" || $_SERVER['REQUEST_METHOD'] == "POST") {
      // lấy thông tin mới của cuốn sách
      $macuonsach = $_REQUEST['macuonsach'];
      $madausach = $_REQUEST['madausach'];
      $mavitri = $_REQUEST['mavitri'];
      $trangthai = $_REQUEST['trangthai'];
      $tinhtrang = $_REQUEST['tinhtrang'];
      if($_REQUEST['luachon'] == "Them") {
        // thực hiện thêm cuốn sách mới
        $dao->Them($conn, $macuonsach, $madausach, $mavitri,
        $trangthai, $tinhtrang);
      }
      else if($_REQUEST['luachon'] == "Xoa") {
        // thực hiện xóa cuốn sách
        $macuonsach = $_REQUEST['macuonsach'];
        $dao->Xoa($conn, $macuonsach);
      }
      else if($_REQUEST['luachon'] == "Sua") {
        // thực hiện sửa thông tin cuốn sách
        $dao->Sua($conn, $macuonsach, $madausach, $mavitri,
        $trangthai, $tinhtrang);
      }
    }
  ?>
  <div class="KhungMenu">
    <?php
      require_once "../../../Menu/AdminMenu.php";
    ?>
  </div>
  <div class="KhungThongTin">
    <form method="get">
      <label for="macuonsach">Mã cuốn sách</label>
      <input type="text" id="macuonsach" name="macuonsach" /><br>
      <label for="macuonsach">Mã đầu sách</label>
      <input type="text" id="madausach" name="madausach" /><br>
      <label for="macuonsach">Mã vị trí</label>
      <input type="text" id="mavitri" name="mavitri" /><br>
      <label for="macuonsach">Trạng thái</label>
      <input type="text" id="trangthai" name="trangthai" /><br>
      <label for="macuonsach">Tình trạng</label>
      <input type="text" id="tinhtrang" name="tinhtrang" /><br>
      <input type="submit" id="luachon" name="luachon" value="Them"/>
    </form>
  </div>
  <div class="KhungDanhSach">
    <table>
      <tr>
        <th>Mã cuốn sách</th>
        <th>Mã đầu sách</th>
        <th>Mã vị trí</th>
        <th>Trạng thái</th>
        <th>Tình trạng</th>
        <th>Hành động</th>
      </tr>
      <?php
        // hiển thị thông tin cuốn sách vào bảng
        $danhsach = $dao->LayToanBoDanhSach($conn);
        foreach($danhsach as $item) {
          echo "<tr>";
          echo "<td>" . $item->getMacuonsach() ."</td>";
          echo "<td>" . $item->getMadausach() . "</td>";
          echo "<td>" . $item->getMavitri() . "</td>";
          echo "<td>" . $item->getTrangthai() . "</td>";
          echo "<td>" . $item->getTinhtrang() . "</td>";
          echo "<td>".
            "<a href='Sua_CuonSach.php?macuonsach=" . $item->getMacuonsach() . "'>Sửa</a>" . " | " .
            "<a href='QL_CuonSach.php?luachon=Xoa&macuonsach=" . $item->getMacuonsach() . "'>Xóa</a>".
          "</td>";
          echo "</tr>";
        }
      ?>
    </table>
  </div>
</body>
</html>