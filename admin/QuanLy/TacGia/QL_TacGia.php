<?php
require_once __DIR__ . '/../../auth.php';
require_admin_login();
require_admin_permission('TACGIA');
error_reporting(E_ERROR | E_PARSE);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quản lý tác giả</title>
  <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
  <style>
    body {font-family: Arial, Helvetica, sans-serif; background: #f3f4f8; margin: 0; padding: 0;}
    .container {width: 95%; max-width: 1100px; margin: 1rem auto;}
    .panel {background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 14px; margin-bottom: 15px; box-shadow: 0 2px 8px rgba(0,0,0,.08);}
    h2 {margin-top:0; color: #333;}
    .form-grid {display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px;}
    .form-grid label {display:block; font-weight:600; margin-bottom:4px;}
    .form-grid input[type="text"] {width:100%; padding: 8px 10px; border:1px solid #bbb; border-radius:4px;}
    .actions {margin-top: 12px;}
    .btn {background:#007bff; color:#fff; border:none; border-radius:5px; padding:8px 16px; cursor:pointer; text-transform: uppercase; letter-spacing: .5px;}
    .btn:hover {background:#0056b3;}
    .table-responsive {overflow-x:auto;}
    table {width:100%; border-collapse:collapse; margin-top:12px;}
    th, td {padding:10px 12px; border:1px solid #ddd; text-align:left;}
    th {background:#f8f9fa;}
    tr:nth-child(even) {background:#fbfbfb;}
    a.action-link {display:inline-block; margin-right:8px; padding:5px 10px; border-radius:4px; color:#fff; text-decoration:none; font-size:.9rem;}
    a.edit {background:#28a745;}
    a.delete {background:#dc3545;}
  </style>
</head>
<body>
  <?php require_once __DIR__ . '/../../layout/admin_sidebar.php'; ?>
  <div class="container">
  <?php
    require_once '../../../database/KetNoiDB.php';
    require_once '../../../model/Sach/TacGia.php';
    require_once '../../../DAO/Sach/TacGiaDAO.php';
    $dao = new TacGiaDAO();

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $luachon = $_GET['luachon'];

        if ($luachon === 'Them') {
            if (empty($_GET['matacgia']) || empty($_GET['tentacgia'])) {
                echo "<script>alert('Thông tin tác giả không được để trống!');</script>";
            } else {
                $matacgia = $_GET['matacgia'];
                $tentacgia = $_GET['tentacgia'];
                $dao->Them($conn, $matacgia, $tentacgia);
            }
        }
      }
      else if($luachon == "Xoa") {
        // lấy mã tác giả
        $matacgia = $_GET['matacgia'];
        $dao->Xoa($conn, $matacgia);
      }
      else if($luachon == "Sua") {
        // lấy thông tin tác giả từ form
        $matacgia = $_GET['matacgia'];
        $tentacgia = $_GET['tentacgia'];
        // thực hiện cập nhật
        $dao->Sua($conn, $matacgia, $tentacgia);
      }
  ?>
  <div class="panel">
    <h2>Thông tin tác giả</h2>
    <div class="KhungThongTin">
      <form method="GET" class="form-grid">
        <div>
          <label for="matacgia">Mã tác giả</label>
          <input type="text" id="matacgia" name="matacgia" required>
        </div>
        <div>
          <label for="tentacgia">Tên tác giả</label>
          <input type="text" id="tentacgia" name="tentacgia" required>
        </div>
        <button type="submit" class="btn" value="Them" name="luachon">Thêm</button>
      </form>
    </div>
  </div>

  <div class="panel">
    <h2>Danh sách tác giả</h2>
    <div class="table-responsive">
      <table>
        <thead>
          <tr>
            <th>Mã tác giả</th>
            <th>Tên tác giả</th>
            <th>Cập nhật</th>
            <th>Xóa</th>
          </tr>
        </thead>
        <tbody>
        <?php
          // lấy danh sách từ database
          $result = $dao->ToanBoDanhSach($conn);
          // hiển thị danh sách tác giả ở đây
          while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['matacgia']) . "</td>";
            echo "<td>" . htmlspecialchars($row['tentacgia']) . "</td>";
            echo "<td><a class='action-link edit' href='Sua_TacGia.php?matacgia=" . urlencode($row['matacgia']) . "&tentacgia=" . urlencode($row['tentacgia']) . "'>Sửa</a></td>";
            echo "<td><a class='action-link delete' href='QL_TacGia.php?luachon=Xoa&matacgia=" . urlencode($row['matacgia']) . "' onclick='return confirm(\"Bạn có chắc muốn xóa?\")'>Xóa</a></td>";
            echo "</tr>";
          }
        ?>
        </tbody>
      </table>
    </div>
  </div>

  </div>
</body>
</html>