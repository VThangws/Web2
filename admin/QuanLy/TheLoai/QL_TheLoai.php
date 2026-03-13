<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <?php
    require_once '../../../database/ConnectDB.php';
    require_once '../../../DAO/TheLoaiDAO.php';
    $dao = new TheLoaiDAO();

    if($_SERVER['REQUEST_METHOD'] == "GET") {
      if($_GET['luachon'] == "Them") {
        if(empty($_GET['matheloai']) ||
        empty($_GET['tentheloai'])) {
          echo "<script>alert('Thông tin thể loại không được để trống!');</script>";
        }
        else {
          // lấy thông tin thể loại
          $matheloai = $_GET['matheloai'];
          $tentheloai = $_GET['tentheloai'];

          // thực hiện thêm mới thể loại
          $dao->Them($conn, $matheloai, $tentheloai);
        }
      }
      else if($_GET['luachon'] == "Xoa") {
        // lấy thông tin để xóa
        $matheloai = $_GET['matheloai'];

        // thực hiện xóa
        $dao->Xoa($conn, $matheloai);

        // thông báo
        echo "<script>alert('Đã xóa thông tin thể loại!');</script>";
      }
      else if($_GET['luachon'] == "Sua") {
        // lấy thông tin thể loại
        $matheloai = $_GET['matheloai'];
        $tentheloai = $_GET['tentheloai'];

        // thực hiện cập nhật
        $dao->Sua($conn, $matheloai, $tentheloai);
      }
    }
  ?>
  <div class="KhungMenu">
    <?php
      require_once '../Menu/AdminMenu.php';
    ?>
  </div>
  <div class="KhungThongTin">
    <form method="GET">
      <label for="matheloai">Mã thể loại:</label>
      <input type="text" id="matheloai" name="matheloai"><br>
      <label for="tentheloai">Tên thể loại:</label>
      <input type="text" id="tentheloai" name="tentheloai"><br>

      <!-- đánh dấu lựa chọn thêm -->
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
      </tr>
      <!-- Dữ liệu thể loại sẽ được hiển thị ở đây -->
      <?php
        $theloais = $dao->LayDanhSachTheLoai($conn);
        foreach($theloais as $theloai) {
          echo "<tr>";
          echo "<td>" . $theloai->getMatheloai() . "</td>";
          echo "<td>" . $theloai->getTentheloai() . "</td>";
          echo "<td><a href='Sua_TheLoai.php?luachon=Sua&matheloai=" . $theloai->getMatheloai() .
          "&tentheloai=" . $theloai->getTentheloai() . "'>Sửa</a></td>";
          echo "<td><a href='QL_TheLoai.php?luachon=Xoa&matheloai=" . $theloai->getMatheloai() . "'>Xóa</xa></td>";
          echo "</tr>";
        }
      ?>
    </table>
  </div>
</body>
</html>