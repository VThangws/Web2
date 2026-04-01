<?php
require_once __DIR__ . '/../../auth.php';
require_admin_login();
require_admin_permission('DOCGIA');
error_reporting(E_ERROR | E_PARSE);
?>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quản lý đọc giả</title>
  <style>
    body{font-family:Arial,Helvetica,sans-serif;background:#f3f4f8;margin:0;padding:0;}
    .container{width:95%;max-width:1100px;margin:1rem auto;}
    .panel{background:#fff;border:1px solid #ddd;border-radius:8px;padding:16px;margin-bottom:16px;box-shadow:0 2px 8px rgba(0,0,0,.08);}
    .panel h2{margin:0 0 .8rem;color:#333;}
    .form-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:12px;}
    .form-grid label{display:block;font-weight:600;margin-bottom:4px;color:#333;}
    .form-grid input[type=text],.form-grid input[type=date]{width:100%;padding:8px 10px;border:1px solid #bbb;border-radius:4px;}
    .form-actions{display:flex;align-items:center;gap:12px;padding-top:8px;}
    .btn{background:#007bff;color:#fff;border:none;padding:8px 16px;border-radius:5px;cursor:pointer;transition:.2s;}
    .btn:hover{background:#0056b3;}
    .table-responsive{overflow-x:auto;}
    table{width:100%;border-collapse:collapse;margin-top:10px;}
    th,td{padding:10px 12px;border:1px solid #ddd;text-align:left;}
    th{background:#f8f9fa;}
    tr:nth-child(even){background:#fbfbfb;}
    a.action-link{display:inline-block;padding:5px 10px;border-radius:4px;color:#fff;text-decoration:none;font-size:.9rem;}
    a.edit{background:#28a745;}
    a.delete{background:#dc3545;}
    #loadMoreBtn{margin-top:10px;}
  </style>
</head>
<body>
  <div class="container">
  <?php
    require_once '../../../database/KetNoiDB.php';
    require_once '../../../model/DocGia.php';
    require_once '../../../DAO/DocGiaDAO.php';

    $conn = ConnectDB::getInstance()->getConnection();
    $dao = new DocGiaDAO();
    if($_SERVER['REQUEST_METHOD'] == 'GET') {
      $luachon = $_GET['luachon'] ?? '';
      // thêm
      if($luachon == "Them") {
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
      else if($luachon == "Sua") {
        // lấy dữ liệu đọc giả từ form
        $madocgia = $_GET['madocgia'];
        $hodocgia = $_GET['hodocgia'];
        $tendocgia = $_GET['tendocgia'];
        $email = $_GET['email'];
        $sdt = $_GET['sdt'];
        $ngaysinh = $_GET['ngaysinh'];
        $diachi = $_GET['diachi'];

        // thực hiện cập nhật
        $dao->Sua($conn, $madocgia, $hodocgia, 
        $tendocgia, $email, $sdt,
        $ngaysinh, $diachi);
      }
      else if($luachon == "Xoa") {
        // lấy mã đọc giả để xóa
        $madocgia = $_GET['madocgia'];
        // thực hiện chức năng xóa đọc giả
        $sql = "DELETE FROM docgia WHERE madocgia=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $madocgia);
        if($stmt->execute()) {
          echo '<script>alert("Đã xóa đọc giả!");</script>';
        }
        else echo '<script>alert("Xóa thông tin đọc giả không thành công!");</script>';
      }
    }
  ?>
  <div class="panel">
    <h2>Quản lý đọc giả</h2>
    <div class="KhungThongTin">
      <form method="GET" class="form-grid">
        <div>
          <label for="madocgia">Mã đọc giả</label>
          <input type="text" id="madocgia" name="madocgia" required>
        </div>
        <div>
          <label for="hodocgia">Họ</label>
          <input type="text" id="hodocgia" name="hodocgia" required>
        </div>
        <div>
          <label for="tendocgia">Tên</label>
          <input type="text" id="tendocgia" name="tendocgia" required>
        </div>
        <div>
          <label for="email">Email</label>
          <input type="text" id="email" name="email" required>
        </div>
        <div>
          <label for="sdt">Số điện thoại</label>
          <input type="text" id="sdt" name="sdt" required>
        </div>
        <div>
          <label for="ngaysinh">Ngày sinh</label>
          <input type="date" id="ngaysinh" name="ngaysinh" required>
        </div>
        <div>
          <label for="diachi">Địa chỉ</label>
          <input type="text" id="diachi" name="diachi" required>
        </div>
        <button type="submit" class="btn" name="luachon" value="Them">Thêm</button>
      </form>
    </div>

    <div class="form-search" style="margin-top:12px;">
      <form method="GET" style="display:flex; gap:10px; align-items:center;">
        <input type="text" name="search" placeholder="Tìm theo mã/họ/tên" style="flex:1;padding:8px;border:1px solid #bbb;border-radius:4px;">
        <button type="submit" class="btn">Tìm kiếm</button>
      </form>
    </div>
  </div>

  <div class="panel">
    <div class="table-responsive">
      <table id="resultTable">
        <thead>
          <tr>
            <th>Mã đọc giả</th>
            <th>Họ</th>
            <th>Tên</th>
            <th>Email</th>
            <th>SĐT</th>
            <th>Ngày sinh</th>
            <th>Địa chỉ</th>
            <th>Hành động</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $query = isset($_GET['search']) && trim($_GET['search']) !== '' ? $dao->TimKiem($conn, $_GET['search']) : $dao->ToanBoDanhSach($conn);
            while($row = $query->fetch_assoc()) {
              echo '<tr>';
              echo '<td>'.htmlspecialchars($row['madocgia']).'</td>';
              echo '<td>'.htmlspecialchars($row['hodocgia']).'</td>';
              echo '<td>'.htmlspecialchars($row['tendocgia']).'</td>';
              echo '<td>'.htmlspecialchars($row['email']).'</td>';
              echo '<td>'.htmlspecialchars($row['sdt']).'</td>';
              echo '<td>'.htmlspecialchars($row['ngaysinh']).'</td>';
              echo '<td>'.htmlspecialchars($row['diachi']).'</td>';
              echo '<td>';
              echo '<a class="action-link edit" href="Sua_DocGia.php?madocgia='.urlencode($row['madocgia']).'">Sửa</a>';
              echo '<a class="action-link delete" href="QL_DocGia.php?luachon=Xoa&madocgia='.urlencode($row['madocgia']).'" onclick="return confirm(\'Bạn có chắc muốn xóa?\')">Xóa</a>';
              echo '</td>';
              echo '</tr>';
            }
          ?>
        </tbody>
      </table>
      <button id="loadMoreBtn" class="btn" type="button" style="margin-top:10px;">Xem thêm</button>
    </div>
  </div>

  </div>

  <script>
    const rowsPerPage = 10;
    let currentLimit = rowsPerPage;
    function updateRowsVisibility() {
      const rows = document.querySelectorAll('#resultTable tbody tr');
      const total = rows.length;
      rows.forEach((row,index)=>row.style.display=index<currentLimit?'table-row':'none');
      const moreBtn=document.getElementById('loadMoreBtn');
      if(!moreBtn) return;
      if(currentLimit>=total){moreBtn.style.display='none';}else{moreBtn.style.display='inline-block';moreBtn.textContent=`Xem thêm ${Math.min(rowsPerPage,total-currentLimit)} mục`;}
    }
    document.addEventListener('DOMContentLoaded',()=>{const moreBtn=document.getElementById('loadMoreBtn');if(moreBtn){moreBtn.addEventListener('click',()=>{currentLimit+=rowsPerPage;updateRowsVisibility();});}updateRowsVisibility();});
  </script>
</body>
</html>