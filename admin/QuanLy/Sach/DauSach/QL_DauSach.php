<?php
require_once __DIR__ . '/../../../auth.php';
require_admin_login();
require_admin_permission('SACH');
?>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quản lý đầu sách</title>
  <link rel="icon" type="image/png" href="/assets/img/logo-library/library.png">
  <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
</head>
<body>
  <?php require_once __DIR__ . '/../../../layout/admin_sidebar.php'; ?>
  <?php
    require_once "../../../../model/Sach/DauSach.php";
    require_once "../../../../DAO/Sach/DauSachDAO.php";
    require_once "../../../../database/KetNoiDB.php";
    $dao = new DauSachDAO();

    if($_SERVER['REQUEST_METHOD'] == "POST" || $_SERVER['REQUEST_METHOD'] == "GET") {
      $luachon = $_REQUEST['luachon'] ?? '';

      if($luachon == "Them") {
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
        $target_dir = "../../../../assets/img/books/";
        $filename = pathinfo($_FILES['anhbia']['name'], PATHINFO_FILENAME);
        $target_file = $target_dir . $filename . $madausach . ".png";
        $filename_to_save = $filename . $madausach . ".png";
        // echo $target_file;
        // lưu hình ảnh
        if(move_uploaded_file($_FILES['anhbia']['tmp_name'], $target_file)) {
          echo "Đã tải ảnh bìa lên!";
        }
        else {
          echo "Có lỗi trong quá trình tải ảnh bìa!";
        }
        // thực hiện thêm vào database
        $dao->Them($conn, $madausach, $tensach, $namsanxuat, $dongia,
         $matacgia, $matheloai, $manxb, $mota, $filename_to_save);
      }
      else if($luachon == "Xoa") {
        // lấy mã đầu sách để xóa sách
        $madausach = $_REQUEST['madausach'];
        // echo $madausach;
        // thực hiện xóa sách
        $dao->Xoa($conn, $madausach);
      }
      else if($luachon == "Sua") {
        // Lấy thông tin ảnh bìa cũ
        $dausach = $dao->getDauSach($conn, $_REQUEST['madausach']);
        // lấy thông tin mới của đầu sách
        $madausach = $_REQUEST['madausach'];
        $tensach = $_REQUEST['tensach'];
        $namxuatban = $_REQUEST['namxuatban'];
        $dongia = $_REQUEST['dongia'];
        $matacgia = $_REQUEST['matacgia'];
        $matheloai = $_REQUEST['matheloai'];
        $manxb = $_REQUEST['manxb'];
        $mota = $_REQUEST['mota'];
        $anhbia = null;

        // kiểm tra có ảnh bìa mới không
        $changeImg = false;
        if(isset($_FILES['anhbia'])){
          $changeImg = true;
          $anhbia = $_FILES['anhbia']['name'];
        }
        // nếu có thì xóa ảnh bìa rồi thêm ảnh bìa mới
        // nếu không thì không đụng đến link ảnh bìa
        if($changeImg == true) {
          // xóa ảnh bìa cũ
          $linkAnhBiaCu = $dausach->getAnhbia();
          unlink($linkAnhBiaCu);
          // thêm ảnh bìa mới
          $target_dir = "../../../../assets/img/books/";
          $filename = pathinfo($_FILES['anhbia']['name'], PATHINFO_FILENAME);
          $target_file = $target_dir . $filename . $madausach . ".png";
          $filename_to_save = $filename . $madausach . ".png";
          move_uploaded_file($_FILES['anhbia']['tmp_name'], $target_file);
          // cập nhật thông tin trong database
          $dao->Sua($conn, $madausach,
          $tensach,
          $namxuatban,
          $dongia,
          $matacgia,
          $matheloai,
          $manxb,
          $mota,
          $filename_to_save);
        }
        else {
          // chỉ thực hiện sửa database mà không sửa link ảnh bìa
          $dao->Sua_Khong_AnhBia($conn, $madausach,
          $tensach,
          $namxuatban,
          $dongia,
          $matacgia,
          $matheloai,
          $manxb,
          $mota);
        }
      }
    }
  ?>
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
          echo "<td><img width='150px' src='../../../../assets/img/books/". $item->getAnhbia() ."' /></td>";
          echo "<td>" .
          "<a href='Sua_DauSach.php?madausach=" . $item->getMadausach() . "'>Sửa</a>" . " | " .
          "<a href='QL_DauSach.php?luachon=Xoa&madausach=" . $item->getMadausach() . "'>Xóa</a>" . "</td>";
          echo "</tr>";
        }
        
      ?>
    </table>
  </div>
</body>
</html>