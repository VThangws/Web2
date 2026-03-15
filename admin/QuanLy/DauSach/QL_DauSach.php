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

    if($_SERVER['REQUEST_METHOD'] == "POST") {
      if($_POST['luachon'] == "Them") {
        // Lấy thông tin đầu sách
        $madausach = $_POST['madausach'];
        $tensach = $_POST['tensach'];
        $namsanxuat = $_POST['namxuatban'];
        $dongia = $_POST['dongia'];
        $matacgia = $_POST['matacgia'];
        $matheloai = $_POST['matheloai'];
        $manxb = $_POST['manxb'];
        $mota = $_POST['mota'];
        // sử lý file ảnh bìa
        $target_dir = "img/";
        $filename = pathinfo($_FILES['anhbia']['name'], PATHINFO_FILENAME);
        $target_file = $target_dir . $filename . $madausach . ".png";
        echo $target_file;
        // lưu hình ảnh
        if(move_uploaded_file($_FILES['anhbia']['tmp_name'], $target_file)) {
          echo "Đã tải ảnh bìa lên!";
        }
        else {
          echo "Có lỗi trong quá trình tải ảnh bìa!";
        }
        // thực hiện thêm vào database
        $dao->Them($conn, $madausach, $tensach, $namsanxuat, $dongia,
         $matacgia, $matheloai, $manxb, $mota, $target_file);
      }
      else if($_POST['luachon'] == "Xoa") {

      }
      else if($_POST['luachon'] == "Sua") {

      }
    }
  ?>
  <div class="KhungMenu"></div>
  <div class="KhungThongTin">
    <form method="post" enctype="multipart/form-data">
      <label for="madausach">Mã đầu sách</label>
      <input type="text" id="madausach" name="madausach" required><br>
      <label for="tensach">Tên sách</label>
      <input type="text" id="tensach" name="tensach" required><br>
      <label for="namxuatban">Năm xuất bản</label>
      <input type="text" id="namxuatban" name="namxuatban" required><br>
      <label for="dongia">Đơn giá</label>
      <input type="text" id="dongia" name="dongia" required><br>
      <label for="matacgia">Mã tác giả</label>
      <input type="text" id="matacgia" name="matacgia" required><br>
      <label for="matheloai">Mã thể loại</label>
      <input type="text" id="matheloai" name="matheloai" required><br>
      <label for="manxb">Mã nhà xuất bản</label>
      <input type="text" id="manxb" name="manxb" required><br>
      <label for="mota">Mô tả</label>
      <input type="text" id="mota" name="mota" required><br>
      <label for="anhbia">Ảnh bìa</label>
      <input type="file" id="anhbia" name="anhbia" accept="image/*" required><br>
      <input type="submit" name="luachon" value="Them">
    </form>
  </div>
  <div class="KhungDanhSach">
    <table>
      <tr>
        <th>Mã đầu sách</th>
        <th>Tên sách</th>
        <th>Năm xuất bản</th>
        <th>Đơn giá</th>
        <th>Mã tác giả</th>
        <th>Mã thể loại</th>
        <th>Mã nhà xuất bản</th>
        <th>Mô tả</th>
        <th>Ảnh bìa</th>
        <th>Hành động</th>
      </tr>
      <?php
        $danhsach = $dao->LayToanBoDanhSach($conn);
        foreach($danhsach as $item) {
          echo "<tr>";
          echo "<td>". $item->getMadausach() ."</td>";
          echo "<td>". $item->getTensach() ."</td>";
          echo "<td>". $item->getNamxuatban() ."</td>";
          echo "<td>". $item->getDongia() ."</td>";
          echo "<td>". $item->getMatacgia() ."</td>";
          echo "<td>". $item->getMatheloai() ."</td>";
          echo "<td>". $item->getManxb() ."</td>";
          echo "<td>". $item->getMota() ."</td>";
          echo "<td><img width='50px' src='". $item->getAnhbia() ."' /></td>";
          echo "<td>" .
          "<a href='Sua_DauSach?madausach=" . $item->getMadausach() . "'>Sửa</a>" .
          "<a href='QL_DauSach.php?luachon=Xoa&madausach=" . $item->getMadausach() . "'>Xóa</a>" . "</td>";
          echo "</tr>";
        }
        
      ?>
    </table>
  </div>
</body>
</html>