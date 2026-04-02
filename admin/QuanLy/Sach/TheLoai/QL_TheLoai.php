<?php
require_once __DIR__ . '/../../../login/auth.php';
require_admin_login();
require_admin_permission('THELOAI');
error_reporting(E_ERROR | E_PARSE);
?>

<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quản lý Thể loại</title>
  <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../style.css" />
  <style>
    body {font-family: Arial, Helvetica, sans-serif; background-color: #f2f3f8; margin: 0; padding: 0;}
    .container {width: 95%; max-width: 1100px; margin: 1rem auto;}
    .panel {background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 16px; margin-bottom: 16px; box-shadow: 0 2px 8px rgba(0,0,0,.06);}
    .KhungThongTin form {display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px;}
    .KhungThongTin label {display: block; margin-bottom: 4px; font-weight: 600; color: #333;}
    .KhungThongTin input[type="text"] {width: 100%; padding: 8px 10px; border: 1px solid #bbb; border-radius: 4px;}
    .KhungThongTin input[type="submit"], .btn {background: #007bff; color: #fff; border: none; padding: 8px 16px; border-radius: 5px; cursor: pointer; transition: background .2s ease;}
    .KhungThongTin input[type="submit"]:hover, .btn:hover {background: #0056b3;}
    .table-responsive {overflow-x:auto;}
    table {width: 100%; border-collapse: collapse; margin-top: 8px;}
    th, td {padding: 10px 12px; border: 1px solid #ddd; text-align: left;}
    th {background: #f8f9fa; color: #333;}
    tr:nth-child(even) {background: #fbfbfb;}
    a.action-link {display: inline-block; margin-right: 8px; padding: 4px 10px; border-radius: 4px; color: #fff; text-decoration: none; font-size: 0.9rem;}
    a.action-link.edit {background: #28a745;}
    a.action-link.delete {background: #dc3545;}
    .KhungDanhSach .report-alert {padding: 12px; text-align: center; color: #555;}

    /* Toggle button + slide animation */
    .toggle-btn {display: inline-block; background: #28a745; color: #fff; border: none; border-radius: 5px; padding: 12px 24px; cursor: pointer; text-decoration: none; transition: all .2s ease; font-weight: 500; font-size: 1rem; margin-bottom: 16px;}
    .toggle-btn:hover {background: #218838; transform: translateY(-2px); box-shadow: 0 4px 8px rgba(33,136,56,.3);}
    @keyframes slideDown {from {opacity: 0; max-height: 0; overflow: hidden; transform: translateY(-20px);} to {opacity: 1; max-height: 1000px; overflow: visible; transform: translateY(0);}}
    @keyframes slideUp {from {opacity: 1; max-height: 1000px; overflow: visible; transform: translateY(0);} to {opacity: 0; max-height: 0; overflow: hidden; transform: translateY(-20px);}}
    .form-panel {animation: slideDown 0.4s ease-in-out forwards;}
    .form-panel.hidden {display: none; animation: slideUp 0.4s ease-in-out forwards;}
  </style>
</head>
<body>

  <?php require_once __DIR__ . '/../../../layout/admin_sidebar.php'; ?>

  <div class="container">

    <button class="toggle-btn" id="toggleFormBtn">+ Thêm thể loại</button>

    <div class="panel form-panel hidden" id="formPanel">
      <div class="heading">
        <h2>Quản lý thể loại sách</h2>
      </div>
      <div class="KhungThongTin">
        <form method="GET" class="form-grid">
          <div>
            <label for="matheloai">Mã thể loại</label>
            <input type="text" id="matheloai" name="matheloai" required>
          </div>
          <div>
            <label for="tentheloai">Tên thể loại</label>
            <input type="text" id="tentheloai" name="tentheloai" required>
          </div>
          <div class="full flex-end">
            <input type="radio" id="luachon" name="luachon" value="Them" checked style="display:none;">
            <button type="submit" class="btn">Thêm thể loại</button>
          </div>
        </form>
      </div>
    </div>

    <div class="panel">
      <div class="KhungTimKiem" style="margin-bottom: 12px;">
        <form method="GET" style="display: flex; gap: 10px; align-items: center;">
          <input type="text" name="search" placeholder="Tìm theo mã hoặc tên thể loại..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" style="flex: 1; padding: 8px 12px; border: 1px solid #bbb; border-radius: 4px;">
          <button type="submit" class="btn">Tìm kiếm</button>
        </form>
      </div>
      <div class="KhungDanhSach">
        <div class="table-responsive">
          <table id="dsTheLoai">
            <thead>
              <tr>
                <th style="width: 20%;">Mã thể loại</th>
                <th style="width: 55%;">Tên thể loại</th>
                <th style="width: 25%;">Hành động</th>
              </tr>
            </thead>
            <tbody>
            <?php
              require_once '../../../../model/Sach/TheLoai.php';
              require_once '../../../../database/KetNoiDB.php';
              require_once '../../../../DAO/Sach/TheLoaiDAO.php';
              $dao = new TheLoaiDAO();

              if($_SERVER['REQUEST_METHOD'] == "GET") {
                $luachon = $_GET['luachon'] ?? '';
                if($luachon == "Them") {
                  if(empty($_GET['matheloai']) || empty($_GET['tentheloai'])) {
                    echo "<script>alert('Thông tin thể loại không được để trống!');</script>";
                  }
                  else {
                    $matheloai = $_GET['matheloai'];
                    $tentheloai = $_GET['tentheloai'];
                    $dao->Them($conn, $matheloai, $tentheloai);
                  }
                }
                else if($_GET['luachon'] == "Xoa") {
                  $matheloai = $_GET['matheloai'];
                  $dao->Xoa($conn, $matheloai);
                  echo "<script>alert('Đã xóa thông tin thể loại!');</script>";
                }
                else if($_GET['luachon'] == "Sua") {
                  $matheloai = $_GET['matheloai'];
                  $tentheloai = $_GET['tentheloai'];
                  $dao->Sua($conn, $matheloai, $tentheloai);
                }
              }

              if (isset($_GET['search']) && trim($_GET['search']) !== '') {
                $theloais = $dao->TimKiem($conn, $_GET['search']);
              } else {
                $theloais = $dao->LayDanhSachTheLoai($conn);
              }
              foreach($theloais as $theloai) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($theloai->getMatheloai()) . "</td>";
                echo "<td>" . htmlspecialchars($theloai->getTentheloai()) . "</td>";
                echo "<td>";
                echo "<a class='action-link edit' href='Sua_TheLoai.php?luachon=Sua&matheloai=" . urlencode($theloai->getMatheloai()) . "&tentheloai=" . urlencode($theloai->getTentheloai()) . "'>Sửa</a>";
                echo "<a class='action-link delete' href='QL_TheLoai.php?luachon=Xoa&matheloai=" . urlencode($theloai->getMatheloai()) . "' onclick='return confirm(\"Bạn có chắc muốn xóa?\")'>Xóa</a>";
                echo "</td>";
                echo "</tr>";
              }

              if (count($theloais) === 0) {
                echo "<tr><td colspan='3' style='text-align: center; padding: 12px;'>Chưa có loại sách nào.</td></tr>";
              }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  </div>

  <script>
    const toggleBtn = document.getElementById('toggleFormBtn');
    const formPanel = document.getElementById('formPanel');

    toggleBtn.addEventListener('click', function () {
      formPanel.classList.toggle('hidden');
      if (formPanel.classList.contains('hidden')) {
        toggleBtn.textContent = '+ Thêm thể loại';
      } else {
        toggleBtn.textContent = '✕ Đóng form';
        formPanel.scrollIntoView({behavior: 'smooth', block: 'start'});
      }
    });
  </script>
</body>
</html>