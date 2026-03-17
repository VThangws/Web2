<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <?php
    require_once '../../../DAO/NhaXuatBanDAO.php';
    require_once '../../../database/ConnectDB.php';
    $dao = new NhaXuatBanDAO();

    if($_SERVER['REQUEST_METHOD'] == "GET") {
      if($_GET['luachon'] == "Them") {
        if(empty($_GET['manxb']) ||
        empty($_GET['tennxb']) ||
        empty($_GET['diachi']) ||
        empty($_GET['sdt']) ||
        empty($_GET['email'])) {
          echo "<script>alert('Thông tin nhà xuất bản không được để trống!');</script>";
        }
        else {
          // lấy thông tin nhà xuất bản từ form
          $manxb = $_GET['manxb'];
          $tennxb = $_GET['tennxb'];
          $diachi = $_GET['diachi'];
          $sdt = $_GET['sdt'];
          $email = $_GET['email'];
          
          // thực hiện thêm thông tin nhà xuất bản
          $dao->Them($conn, $manxb, $tennxb, $diachi, $sdt, $email);
        }
      }
      else if($_GET['luachon'] == "Xoa") {
        // lấy mã nhà xuất bản
        $manxb = $_GET['manxb'];
        // thực hiện xóa thông tin nhà xuất bản
        $dao->Xoa($conn, $manxb);
      }
      else if($_GET['luachon'] == "Sua") {
        // lấy thông tin sửa
        $manxb = $_GET['manxb'];
        $tennxb = $_GET['tennxb'];
        $diachi = $_GET['diachi'];
        $sdt = $_GET['sdt'];
        $email = $_GET['email'];

        // thực hiện sửa
        $dao->Sua($conn, $manxb, $tennxb, $diachi, $sdt, $email);
      }
      else echo "<script>alert('Chào mừng đến với trang quản lý nhà xuất bản!');</script>";
    }
  ?>
  <div class="KhungMenu">
    <?php
      require_once '../../Menu/AdminMenu.php';
    ?>
  </div>
  <div class="KhungThongTin">
    <form method="GET">
      <label for="manxb">Mã nhà xuất bản</label>
      <input type="text" id="manxb" name="manxb"><br>
      <label for="tennxb">Tên nhà xuất bản</label>
      <input type="text" id="tennxb" name="tennxb"><br>
      <label for="diachi">Địa chỉ</label>
      <input type="text" id="diachi" name="diachi"><br>
      <label for="sdt">Số điện thoại</label>
      <input type="text" id="sdt" name="sdt"><br>
      <label for="email">Email</label>
      <input type="text" id="email" name="email"><br>

      <!-- đánh dấu lựa chọn là thêm -->
      <input type="radio" id="luachon" name="luachon" value="Them" checked style="display: none;">

      <input type="submit" value="Thêm">
    </form>
  </div>
  <div class="KhungDanhSach">
    <table>
      <tr>
        <th>Mã nhà xuất bản</th>
        <th>Tên nhà xuất bản</th>
        <th>Địa chỉ</th>
        <th>Số điện thoại</th>
        <th>Email</th>
        <th>Hành động</th>
      </tr>
      <?php
        // nơi để hiển thị danh sách
        $result = $dao->LayToanBoDanhSach($conn);
        foreach($result as $item) {
          echo "<tr>";
          echo "<td>". $item->getManxb() ."</td>";
          echo "<td>". $item->getTennxb() ."</td>";
          echo "<td>". $item->getDiachi() ."</td>";
          echo "<td>". $item->getSdt() ."</td>";
          echo "<td>". $item->getEmail() ."</td>";
          echo "<td>";
          echo "<a href='Sua_NhaXuatBan.php?" .
          "manxb=" . $item->getManxb() .
          "&tennxb=" . $item->getTennxb() .
          "&diachi=" . $item->getDiachi() .
          "&sdt=" . $item->getSdt() .
          "&email=" . $item->getEmail() .
           "'>Sửa</a>";
          echo "<a href='QL_NhaXuatBan.php?luachon=Xoa&manxb=" . $item->getManxb() . "'>Xóa</a>";
          echo "</td>";
          echo "</tr>";
        }
      ?>
    </table>
  </div>
</body>
</html>