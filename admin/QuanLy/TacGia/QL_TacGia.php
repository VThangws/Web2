<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quản lý tác giả</title>
</head>
<body>
  <?php
    require_once '../../../database/ConnectDB.php';
    require_once '../../../DAO/TacGiaDAO.php';
    $dao = new TacGiaDAO();

    if($_SERVER['REQUEST_METHOD'] == "GET") {
      // chia trường hợp
      if($_GET['luachon'] == "Them") {
        if(empty($_GET['matacgia']) ||
        empty($_GET['tentacgia'])) {
          echo "<script>alert('Thông tin tác giả không được để trống!');</script>";
        }
        else {
          // lấy thông tin tác giả
          $matacgia = $_GET['matacgia'];
          $tentacgia = $_GET['tentacgia'];

          // thực hiện thêm mới tác giả
          $dao->Them($conn, $matacgia, $tentacgia);
        }
      }
      else if($_GET['luachon'] == "Xoa") {
        // lấy mã tác giả
        $matacgia = $_GET['matacgia'];
        $dao->Xoa($conn, $matacgia);
      }
      else if($_GET['luachon'] == "Sua") {
        // lấy thông tin tác giả từ form
        $matacgia = $_GET['matacgia'];
        $tentacgia = $_GET['tentacgia'];
        // thực hiện cập nhật
        $dao->Sua($conn, $matacgia, $tentacgia);
      }
      else echo "<script>alert('Chào mừng đến với trang quản lý tác giả!');</script>";
    }
  ?>
  <div class="KhungMenu">
    <?php
      require_once '../../Menu/AdminMenu.php';
    ?>
  </div>
  <div class="KhungThongTin">
    <form method="GET">
      <label for="matacgia">Mã tác giả</label>
      <input type="text" id="matacgia" name="matacgia" ><br>
      <label for="tentacgia">Tên tác giả</label>
      <input type="text" id="tentacgia" name="tentacgia" ><br>
      
      <input type="radio" name="luachon" value="Them">Thêm mới tác giả
      <input type="radio" name="luachon" value="Sua">Sửa thông tin tác giả<br>

      <input type="submit" value="OK">
    </form>
  </div>
  <div class="KhungDanhSach">
    <table>
      <tr>
        <th>Mã tác giả</th>
        <th>Tên tác giả</th>
        <th>Cập nhập</th>
        <th>Xóa tác giả</th>
      </tr>
      <?php
        // lấy danh sách từ database
        $result = $dao->ToanBoDanhSach($conn);
        // hiển thị danh sách tác tác ở đây
        while($row = $result->fetch_assoc()) {
          echo "
            <tr>
              <td>". $row['matacgia'] . "</td>
              <td>". $row['tentacgia'] . "</td>
              <td><a href='Sua_TacGia.php?matacgia=" . $row['matacgia'] . "&tentacgia=" . $row['tentacgia'] . "'>Sửa</a></td>
              <td><a href='QL_TacGia.php?luachon=Xoa&matacgia=" . $row['matacgia'] . "'>Xóa</a></td>
            </tr>
          ";
        }
      ?>
    </table>
  </div>
</body>
</html>