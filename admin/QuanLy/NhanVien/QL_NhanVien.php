<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quản lý nhân viên</title>
</head>
<body>
  <?php
    require_once '../../../database/ConnectDB.php';
    require_once '../../../DAO/NhanVienDAO.php';
    $dao = new NhanVienDAO();
    if($_SERVER["REQUEST_METHOD"] == "GET") {
      echo "Đã nhận được dữ liệu";

      // chia trường hợp
      // thêm
      if($_GET["luachon"]=="Them") {
        if((empty($_GET["manv"]) || empty($_GET["honv"]) || empty($_GET["tennv"]) ||
          empty($_GET["gioitinh"]) || empty($_GET["sdt"]) ||
          empty($_GET["ngaysinh"]))) {
            echo "<script>alert('Thông tin nhân viên không được để trống!');</script>";
        }
        else {
          // lấy dữ liệu về thông tin nhân viên
          $manv = $_GET["manv"];
          $honv = $_GET["honv"];
          $tennv = $_GET["tennv"];
          $gioitinh = $_GET["gioitinh"];
          $sdt = $_GET["sdt"];
          $ngaysinh = $_GET["ngaysinh"];

          // thực hiện thêm nhân viên
          $dao->Them($conn, $manv, $honv, $tennv, $gioitinh, $sdt, $ngaysinh);

          // thông báo thêm thành công
          echo "<script>alert('Thêm nhân viên thành công!');</script>";
        }
      }
      else if($_GET["luachon"]=="Sua") {
        // lấy dữ liệu về thông tin nhân viên
        $manv = $_GET["manv"];
        $honv = $_GET["honv"];
        $tennv = $_GET["tennv"];
        $gioitinh = $_GET["gioitinh"];
        $sdt = $_GET["sdt"];
        $ngaysinh = $_GET["ngaysinh"];
        // thực hiện update
        $dao->Sua($conn, $manv, $honv, $tennv, $gioitinh, $sdt, $ngaysinh);
      }
      else if($_GET["luachon"]=="Xoa") {
        $manv = $_GET["manv"];
        $sql = "DELETE FROM nhanvien WHERE manv=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $manv);
        if($stmt->execute()) {
          echo "<script>alert('Đã xóa nhân viên!');</script>";
        }
        else echo "<script>alert('Xóa nhân viên không thành công!');</script>";
      }
      else echo '<script>alert("Chào mừng đến với trang quản lý nhân viên!");</script>';
    }
  ?>
  <div class="KhungMenu">
    <?php
      require_once '../../Menu/AdminMenu.php';
    ?>
  </div>
  <div class="formThongTin">
    <form method="GET">
      <label for="manv">Mã nhân viên</label>
      <input type="text" id="manv" name="manv"><br>

      <label for="honv">Họ nhân viên</label>
      <input type="text" id="honv" name="honv"><br>

      <label for="tennv">Tên nhân viên</label>
      <input type="text" id="tennv" name="tennv"><br>

      <label for="gioitinh">Giới tính</label>
      <input type="radio" name="gioitinh" value="Nam">Nam
      <input type="radio" name="gioitinh" value="Nữ">Nữ<br>

      <label for="sdt">Số điện thoại</label>
      <input type="text" id="sdt" name="sdt"><br>

      <label for="ngaysinh">Ngày sinh</label>
      <input type="date" id="ngaysinh" name="ngaysinh"><br>

      <label for="luachon">Lựa chọn</label>
      <input type="radio" name="luachon" value="Them">Thêm nhân viên mới
      <input type="radio" name="luachon" value="Sua">Sửa thông tinh nhân viên<br>

      <input type="submit" value="OK">
    </form>
  </div>
  <div class="KhungDanhSach">
    <table>
      <tr>
        <th>Mã nhân viên</th>
        <th>Họ</th>
        <th>Tên</th>
        <th>Giới tính</th>
        <th>Số điện thoại</th>
        <th>Ngày sinh</th>
        <th>Xóa nhân viên</th>
      </tr>
      <?php
        $result = $dao->ToanBoDanhSach($conn);
        while($row = $result->fetch_assoc()) {
          echo "
            <tr>
              <td>" . $row['manv'] . "</td>
              <td>" . $row['honv'] . "</td>
              <td>" . $row['tennv'] . "</td>
              <td>" . $row['gioitinh'] . "</td>
              <td>" . $row['sdt'] . "</td>
              <td>" . $row['ngaysinh'] . "</td>
              <td><a href=". "QL_NhanVien.php" ."?manv=". $row['manv'] . "&luachon=Xoa" .
               ">Xóa</a></td>
            </tr>
          ";
        }
      ?>
    </table>
  </div>
</body>
</html>