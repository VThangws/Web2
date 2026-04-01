<?php
require_once __DIR__ . '/../../../auth.php';
require_admin_login();
require_admin_permission('SACH');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quản lý Đầu sách</title>
  <link rel="stylesheet" href="../../style.css" />
  <style>
    * {box-sizing: border-box;}
    body {font-family: 'Segoe UI', Arial, sans-serif; background: #f3f4f8; margin: 0; padding: 0;}
    .container {width: 95%; max-width: 1300px; margin: 1rem auto;}
    .panel {background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 16px; margin-bottom: 16px; box-shadow: 0 2px 8px rgba(0,0,0,.08);}
    .panel h2 {margin: 0 0 .8rem; color: #333; font-size: 1.4rem;}
    
    /* Form Styling */
    .panel form {width: 100%;}
    .form-grid {display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px;}
    .form-grid label {display: block; font-weight: 600; margin-bottom: 4px; color: #333; font-size: .95rem;}
    .form-grid input[type="text"],
    .form-grid input[type="date"],
    .form-grid input[type="file"] {width: 100%; padding: 8px 10px; border: 1px solid #bbb; border-radius: 4px; font-size: .95rem;}
    .form-grid input:focus {outline: none; border-color: #007bff; box-shadow: 0 0 4px rgba(0,123,255,.3);}
    .form-grid .full {grid-column: 1 / -1;}
    .form-actions {display: flex; gap: 10px; align-items: center; padding-top: 8px;}
    .form-actions label {margin: 0; font-weight: 500; display: inline-flex; align-items: center; gap: 6px;}
    
    /* Button Styling */
    .btn {display: inline-block; background: #007bff; color: #fff; border: none; border-radius: 5px; padding: 8px 16px; cursor: pointer; text-decoration: none; transition: all .2s ease; font-weight: 500;}
    .btn:hover {background: #0056b3; transform: translateY(-1px); box-shadow: 0 2px 6px rgba(0,86,179,.3);}
    .btn.btn-danger {background: #dc3545;}
    .btn.btn-danger:hover {background: #c82333;}
    button[type="submit"] {min-width: 100px;}
    
    /* Search Box */
    .search-form {display: flex; gap: 10px; align-items: center; padding: 12px 0;}
    .search-form input {flex: 1; padding: 8px 12px; border: 1px solid #bbb; border-radius: 4px; font-size: .95rem;}
    .search-form label {margin: 0; font-weight: 600; white-space: nowrap;}
    
    /* Table Styling */
    .table-responsive {overflow-x: auto; margin-top: 12px;}
    table {width: 100%; border-collapse: collapse; font-size: .95rem;}
    thead {background: #f8f9fa;}
    th {padding: 10px 12px; border: 1px solid #ddd; text-align: left; font-weight: 600; color: #333;}
    td {padding: 10px 12px; border: 1px solid #ddd;}
    tr:nth-child(even) tbody tr{background: #fbfbfb;}
    tbody tr:hover {background: #f0f0f0;}
    img {max-height: 120px; object-fit: contain;}
    
    /* Action Links */
    .action-link {display: inline-block; padding: 5px 10px; border-radius: 4px; color: #fff; text-decoration: none; font-size: .85rem; margin-right: 6px; transition: all .2s;}
    .action-link.edit {background: #28a745;}
    .action-link.edit:hover {background: #218838; transform: translateY(-1px);}
    .action-link.delete {background: #dc3545;}
    .action-link.delete:hover {background: #c82333; transform: translateY(-1px);}
    
    /* Load More Button */
    #loadMoreBtn {margin-top: 12px; width: 100%; padding: 10px 16px; font-size: 1rem;}
    
    /* Empty State */
    .empty-state {text-align: center; padding: 14px; color: #666;}
  </style>
</head>
<body>
  <?php require_once __DIR__ . '/../../../layout/admin_sidebar.php'; ?>
  <?php
    // Tắt tất cả báo cáo lỗi
    error_reporting(0);
    require_once "../../../../model/Sach/DauSach.php";
    require_once "../../../../DAO/Sach/DauSachDAO.php";
    require_once "../../../../database/KetNoiDB.php";
    $dao = new DauSachDAO();

    if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
        $luachon = $_REQUEST['luachon'] ?? '';

        if ($luachon === 'Them') {
            $madausach = $_POST['madausach'];
            $tensach = $_POST['tensach'];
            $namsanxuat = $_POST['namxuatban'];
            $dongia = $_POST['dongia'];
            $matacgia = $_POST['matacgia'];
            $matheloai = $_POST['matheloai'];
            $manxb = $_POST['manxb'];
            $mota = $_POST['mota'];

            $target_dir = '../../../../assets/img/books/';
            $filename = pathinfo($_FILES['anhbia']['name'], PATHINFO_FILENAME);
            $target_file = $target_dir . $filename . $madausach . '.png';
            $filename_to_save = $filename . $madausach . '.png';

            if (move_uploaded_file($_FILES['anhbia']['tmp_name'], $target_file)) {
                echo 'Đã tải ảnh bìa lên!';
            } else {
                echo 'Có lỗi trong quá trình tải ảnh bìa!';
            }

            $dao->Them($conn, $madausach, $tensach, $namsanxuat, $dongia, $matacgia, $matheloai, $manxb, $mota, $filename_to_save);
        } elseif ($luachon === 'Xoa') {
            $madausach = $_REQUEST['madausach'];
            $dao->Xoa($conn, $madausach);
        } elseif ($luachon === 'Sua') {
            $dausach = $dao->getDauSach($conn, $_REQUEST['madausach']);

            $madausach = $_REQUEST['madausach'];
            $tensach = $_REQUEST['tensach'];
            $namxuatban = $_REQUEST['namxuatban'];
            $dongia = $_REQUEST['dongia'];
            $matacgia = $_REQUEST['matacgia'];
            $matheloai = $_REQUEST['matheloai'];
            $manxb = $_REQUEST['manxb'];
            $mota = $_REQUEST['mota'];

            $changeImg = false;
            if (isset($_FILES['anhbia'])) {
                $changeImg = true;
                $_FILES['anhbia']['name'];
            }

            if ($changeImg === true) {
                $linkAnhBiaCu = $dausach->getAnhbia();
                unlink($linkAnhBiaCu);

                $target_dir = '../../../../assets/img/books/';
                $filename = pathinfo($_FILES['anhbia']['name'], PATHINFO_FILENAME);
                $target_file = $target_dir . $filename . $madausach . '.png';
                $filename_to_save = $filename . $madausach . '.png';
                move_uploaded_file($_FILES['anhbia']['tmp_name'], $target_file);

                $dao->Sua($conn, $madausach, $tensach, $namxuatban, $dongia, $matacgia, $matheloai, $manxb, $mota, $filename_to_save);
            } else {
                $dao->Sua_Khong_AnhBia($conn, $madausach, $tensach, $namxuatban, $dongia, $matacgia, $matheloai, $manxb, $mota);
            }
        }
    }
  ?>
  <div class="container">
    <div class="panel KhungThongTin">
      <h2>Thêm đầu sách mới</h2>
      <form method="post" enctype="multipart/form-data" class="form-grid">
        <div>
          <label for="madausach">Mã đầu sách</label>
          <input type="text" id="madausach" name="madausach" required>
        </div>
        <div>
          <label for="tensach">Tên sách</label>
          <input type="text" id="tensach" name="tensach" required>
        </div>
        <div>
          <label for="namxuatban">Năm xuất bản</label>
          <input type="text" id="namxuatban" name="namxuatban" required>
        </div>
        <div>
          <label for="dongia">Đơn giá</label>
          <input type="text" id="dongia" name="dongia" required>
        </div>
        <div>
          <label for="matacgia">Mã tác giả</label>
          <input type="text" id="matacgia" name="matacgia" required>
        </div>
        <div>
          <label for="matheloai">Mã thể loại</label>
          <input type="text" id="matheloai" name="matheloai" required>
        </div>
        <div>
          <label for="manxb">Mã nhà xuất bản</label>
          <input type="text" id="manxb" name="manxb" required>
        </div>
        <div>
          <label for="mota">Mô tả</label>
          <input type="text" id="mota" name="mota" required>
        </div>
        <div>
          <label for="anhbia">Ảnh bìa</label>
          <input type="file" id="anhbia" name="anhbia" accept="image/*" required>
        </div>
        <div class="full form-actions">
          <button type="submit" name="luachon" value="Them" class="btn" name="luachon">Thêm sách</button>
        </div>
      </form>
    </div>

    <div class="panel KhungTimKiem">
      <form method="get" class="search-form">
        <label for="search">Tìm kiếm:</label>
        <input type="text" id="search" name="search" placeholder="Nhập mã hoặc tên sách..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button type="submit" class="btn">Tìm</button>
      </form>
    </div>
    <div class="panel KhungDanhSach">
      <h2>Danh sách đầu sách</h2>
      <div class="table-responsive">
        <table id="resultTable">
          <thead>
            <tr>
              <th>Mã</th>
              <th>Tên sách</th>
              <th>Năm XB</th>
              <th>Đơn giá</th>
              <th>Tác giả</th>
              <th>Thể loại</th>
              <th>NXB</th>
              <th>Mô tả</th>
              <th>Ảnh bìa</th>
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody>
            <?php
              if(isset($_GET['search']) && trim($_GET['search']) !== '') {
                $danhsach = $dao->TimKiem($conn, $_GET['search']);
              } else {
                $danhsach = $dao->LayToanBoDanhSach($conn);
              }
              foreach($danhsach as $item) {
                echo "<tr>";
                echo "<td>". htmlspecialchars($item->getMadausach()) ."</td>";
                echo "<td>". htmlspecialchars($item->getTensach()) ."</td>";
                echo "<td>". htmlspecialchars($item->getNamxuatban()) ."</td>";
                echo "<td>". htmlspecialchars($item->getDongia()) ."</td>";
                echo "<td>". htmlspecialchars($item->getMatacgia()) ."</td>";
                echo "<td>". htmlspecialchars($item->getMatheloai()) ."</td>";
                echo "<td>". htmlspecialchars($item->getManxb()) ."</td>";
                echo "<td>". htmlspecialchars($item->getMota()) ."</td>";
                echo "<td><img src='../../../../assets/img/books/". htmlspecialchars($item->getAnhbia()) ."' alt='Ảnh bìa'></td>";
                echo "<td>" .
                "<a class='action-link edit' href='Sua_DauSach.php?madausach=" . urlencode($item->getMadausach()) . "'>Sửa</a>" .
                "<a class='action-link delete' href='QL_DauSach.php?luachon=Xoa&madausach=" . urlencode($item->getMadausach()) . "' onclick='return confirm(\"Bạn có chắc muốn xóa?\")'>Xóa</a>" .
                "</td>";
                echo "</tr>";
              }
              if (empty($danhsach)) {
                echo "<tr><td colspan='10' class='empty-state'>Không có dữ liệu phù hợp.</td></tr>";
              }
            ?>
          </tbody>
        </table>
      </div>
      <button id="loadMoreBtn" class="btn" type="button">Xem thêm</button>
    </div>
  </div>
  <script>
    const rowsPerPage = 10;
    let currentLimit = rowsPerPage;

    function updateRowsVisibility() {
      const table = document.getElementById('resultTable');
      if (!table) return;
      const rows = table.querySelectorAll('tbody tr');
      const total = rows.length;

      rows.forEach((row, index) => {
        row.style.display = index < currentLimit ? 'table-row' : 'none';
      });

      const moreBtn = document.getElementById('loadMoreBtn');
      if (!moreBtn) return;

      if (currentLimit >= total) {
        moreBtn.style.display = 'none';
      } else {
        moreBtn.style.display = 'inline-block';
        moreBtn.textContent = `Xem thêm ${Math.min(rowsPerPage, total - currentLimit)} mục`;
      }
    }

    document.addEventListener('DOMContentLoaded', function () {
      const moreBtn = document.getElementById('loadMoreBtn');
      if (moreBtn) {
        moreBtn.addEventListener('click', function () {
          currentLimit += rowsPerPage;
          updateRowsVisibility();
        });
      }
      updateRowsVisibility();
    });
  </script>
</body>
</html>