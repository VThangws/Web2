<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quản lý đọc giả</title>
</head>
<body>
  <?php
    require_once '../../database/KetNoiDB.php';
    require_once '../../DAO/DocGiaDAO.php';
    $dao = new DocGiaDAO();
    if($_SERVER['REQUEST_METHOD'] == 'GET') {
      // thêm
      if($_GET['luachon'] == "Them") {
        if(empty($_GET['madocgia']) ||
        empty($_GET['hodocgia'])||
        empty($_GET['tendocgia'])||
        empty($_GET['email'])||
        empty($_GET['sdt'])||
        empty($_GET['ngaysinh'])||
        empty($_GET['diachi'])) {
          echo "<script>alert('Thông tin đọc giả không được bỏ trống!');</script>";
        }
        else {
          // lấy thông tin đọc giả
          $madocgia = $_GET['madocgia'];
          $hodocgia = $_GET['hodocgia'];
          $tendocgia = $_GET['tendocgia'];
          $email = $_GET['email'];
          $sdt = $_GET['sdt'];
          $ngaysinh = $_GET['ngaysinh'];
          $diachi = $_GET['diachi'];

          // thực hiện thêm
          $dao->Them($conn, $madocgia, $hodocgia, $tendocgia, 
          $email, $sdt, $ngaysinh, $diachi);

          // thông báo thành công
          echo "<script>alert('Thêm đọc giả thành công!');</script>";
        }
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
      <label for="madocgia">Mã đọc giả</label>
      <input type="text" id="madocgia" name="madocgia" ><br>
      <label for="hodocgia">Họ</label>
      <input type="text" id="hodocgia" name="hodocgia" ><br>
      <label for="tendocgia">Tên</label>
      <input type="text" id="tendocgia" name="tendocgia" ><br>
      <label for="email">Email</label>
      <input type="text" id="email" name="email" ><br>
      <label for="sdt">Số điện thoại</label>
      <input type="text" id="sdt" name="sdt" ><br>
      <label for="ngaysinh">Ngày sinh</label>
      <input type="date" id="ngaysinh" name="ngaysinh" ><br>
      <label for="diachi">Địa chỉ</label>
      <input type="text" id="diachi" name="diachi" ><br>

      <!-- lựa chọn thêm mới hoặc sửa -->
      <input type="radio" name="luachon" value="Them">Thêm đọc giả
      <input type="radio" name="luachon" value="Sua">Sửa thông tin đọc giả<br>
      <input type="submit" value="OK">
    </form>
  </div>
  <div class="KhungDanhSach">
    <table>
      <tr>
        <th>Mã đọc giả</th>
        <th>Họ</th>
        <th>Tên</th>
        <th>Email</th>
        <th>Số điện thoại</th>
        <th>Ngày sinh</th>
        <th>Địa chỉ</th>
        <th>Xóa đọc giả</th>
      </tr>
      <?php
        // hiển thị dữ liệu trong bảng
        $result = $dao->ToanBoDanhSach($conn);
        while($row = $result->fetch_assoc()) {
          echo "
            <tr>
              <td>" . $row['madocgia'] . "</td>
              <td>" . $row['hodocgia'] . "</td>
              <td>" . $row['tendocgia'] . "</td>
              <td>" . $row['email'] . "</td>
              <td>" . $row['sdt'] . "</td>
              <td>" . $row['ngaysinh'] . "</td>
              <td>" . $row['diachi'] . "</td>
              <td><a href='QL_DocGia.php?luachon=Xoa&
              madocgia=" . $row['madocgia'] . "'>Xóa</a></td>
            </tr>
          ";
        }
      ?>
    </table>
  </div>
</body>
</html>