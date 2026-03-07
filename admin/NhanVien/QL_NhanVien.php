<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quản lý nhân viên</title>
</head>
<body>
  <?php
    require_once '../../database/KetNoiDB.php';
    require_once '../../DAO/NhanVienDAO.php';
    $dao = new NhanVienDAO();
    if($_SERVER["REQUEST_METHOD"] == "GET") {
      echo "Đã nhận được dữ liệu";
      if(empty($_GET["manv"]) || empty($_GET["honv"]) || empty($_GET["tennv"]) ||
        empty($_GET["gioitinh"]) || empty($_GET["sdt"]) ||
        empty($_GET["ngaysinh"]) || empty($_GET["luachon"])) {
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
        $luachon = $_GET["luachon"];


        // thực hiện thêm nhân viên
        $dao->Them($conn, $manv, $honv, $tennv, $gioitinh, $sdt, $ngaysinh);

        // thông báo thêm thành công
        echo "<script>alert('Thêm nhân viên thành công!');</script>";
      }
    }
  ?>
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
</body>
</html>