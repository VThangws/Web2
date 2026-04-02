<?php
require_once __DIR__ . '/../../login/auth.php';
require_admin_login();
require_admin_permission('DOCGIA');
error_reporting(E_ERROR | E_PARSE);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quản lý đọc giả</title>
  <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
  <style>
    body{font-family:Arial,Helvetica,sans-serif;background:#f3f4f8;margin:0;padding:0;}
    .container{width:95%;max-width:1100px;margin:1rem auto;}
    .panel{background:#fff;border:1px solid #ddd;border-radius:8px;padding:16px;margin-bottom:16px;box-shadow:0 2px 8px rgba(0,0,0,.08);}
    .panel h2{margin:0 0 .8rem;color:#333;}
    .form-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:12px;}
    .form-grid label{display:block;font-weight:600;margin-bottom:4px;color:#333;}
    .form-grid input[type=text],.form-grid input[type=date]{width:100%;padding:8px 10px;border:1px solid #bbb;border-radius:4px;}
    .form-actions{display:flex;align-items:center;gap:12px;padding-top:8px;}
    .btn:not(.btn-outline-primary):not(.btn-outline-danger){background:#007bff;color:#fff;border:none;padding:8px 16px;border-radius:5px;cursor:pointer;transition:.2s;}
    .btn:not(.btn-outline-primary):not(.btn-outline-danger):hover{background:#0056b3;}
    .table-responsive{overflow-x:auto;}
    table{width:100%;border-collapse:collapse;margin-top:10px;}
    th,td{padding:10px 12px;border:1px solid #ddd;text-align:left;}
    th{background:#f8f9fa;}
    tr:nth-child(even){background:#fbfbfb;}
    a.action-link{display:inline-block;padding:5px 10px;border-radius:4px;color:#fff;text-decoration:none;font-size:.9rem;}
    a.edit{background:#28a745;}
    a.delete{background:#dc3545;}
    #loadMoreBtn{margin-top:10px;}
    .toggle-btn{display:inline-block;background:#28a745;color:#fff;border:none;border-radius:5px;padding:12px 24px;cursor:pointer;text-decoration:none;transition:all .2s ease;font-weight:500;font-size:1rem;margin-bottom:16px;}
    .toggle-btn:hover{background:#218838;transform:translateY(-2px);box-shadow:0 4px 8px rgba(33,136,56,.3);}
    @keyframes slideDown{from{opacity:0;max-height:0;overflow:hidden;transform:translateY(-20px);}to{opacity:1;max-height:1000px;overflow:visible;transform:translateY(0);}}
    @keyframes slideUp{from{opacity:1;max-height:1000px;overflow:visible;transform:translateY(0);}to{opacity:0;max-height:0;overflow:hidden;transform:translateY(-20px);}}
    .form-panel{animation:slideDown .4s ease-in-out forwards;}
    .form-panel.hidden{display:none;animation:slideUp .4s ease-in-out forwards;}
  </style>
</head>
<body>
  <?php require_once __DIR__ . '/../../layout/admin_sidebar.php'; ?>
  <div class="container">
  <?php
    require_once '../../../database/KetNoiDB.php';
    require_once '../../../model/DocGia.php';
    require_once '../../../DAO/DocGiaDAO.php';

    $conn = ConnectDB::getInstance()->getConnection();
    $dao = new DocGiaDAO();

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $luachon = $_GET['luachon'] ?? '';

        if ($luachon === 'Them') {
            if (
                empty($_GET['madocgia'])
                || empty($_GET['hodocgia'])
                || empty($_GET['tendocgia'])
                || empty($_GET['email'])
                || empty($_GET['sdt'])
                || empty($_GET['ngaysinh'])
                || empty($_GET['diachi'])
            ) {
                echo "<script>alert('Thông tin đọc giả không được bỏ trống!');</script>";
            } else {
                $madocgia = $_GET['madocgia'];
                $hodocgia = $_GET['hodocgia'];
                $tendocgia = $_GET['tendocgia'];
                $email = $_GET['email'];
                $sdt = $_GET['sdt'];
                $ngaysinh = $_GET['ngaysinh'];
                $diachi = $_GET['diachi'];

                $dao->Them($conn, $madocgia, $hodocgia, $tendocgia, $email, $sdt, $ngaysinh, $diachi);
            }
        } elseif ($luachon === 'Sua') {
            $madocgia = $_GET['madocgia'] ?? '';
            $hodocgia = $_GET['hodocgia'] ?? '';
            $tendocgia = $_GET['tendocgia'] ?? '';
            $email = $_GET['email'] ?? '';
            $sdt = $_GET['sdt'] ?? '';
            $ngaysinh = $_GET['ngaysinh'] ?? '';
            $diachi = $_GET['diachi'] ?? '';

            if (
                empty($madocgia)
                || empty($hodocgia)
                || empty($tendocgia)
                || empty($email)
                || empty($sdt)
                || empty($ngaysinh)
                || empty($diachi)
            ) {
                echo "<script>alert('Thông tin đọc giả không được bỏ trống!');</script>";
            } else {
                $dao->Sua($conn, $madocgia, $hodocgia, $tendocgia, $email, $sdt, $ngaysinh, $diachi);
            }
        } elseif ($luachon === 'Xoa') {
            $madocgia = $_GET['madocgia'] ?? '';
            if (!empty($madocgia)) {
                $dao->Xoa($conn, $madocgia);
            }
        }
    }

    $query = isset($_GET['search']) && trim($_GET['search']) !== '' ? $dao->TimKiem($conn, $_GET['search']) : $dao->ToanBoDanhSach($conn);
    ?>
  <button class="toggle-btn" id="toggleFormBtn">+ Thêm đọc giả</button>
  
  <div class="panel form-panel hidden" id="formPanel">
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
  </div>

  <div class="form-search" style="margin-top:12px;">
    <form method="GET" style="display:flex; gap:10px; align-items:center;">
      <input type="text" name="search" placeholder="Tìm theo mã/họ/tên" style="flex:1;padding:8px;border:1px solid #bbb;border-radius:4px;">
      <button type="submit" class="btn">Tìm kiếm</button>
    </form>
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
            <th class="text-end">Thao tác</th>
          </tr>
        </thead>
        <tbody>
          <?php
            while($row = $query->fetch_assoc()) {
              echo '<tr>';
              echo '<td>'.htmlspecialchars($row['madocgia']).'</td>';
              echo '<td>'.htmlspecialchars($row['hodocgia']).'</td>';
              echo '<td>'.htmlspecialchars($row['tendocgia']).'</td>';
              echo '<td>'.htmlspecialchars($row['email']).'</td>';
              echo '<td>'.htmlspecialchars($row['sdt']).'</td>';
              echo '<td>'.htmlspecialchars($row['ngaysinh']).'</td>';
              echo '<td>'.htmlspecialchars($row['diachi']).'</td>';
              echo '<td class="text-end">';
              echo '<a class="btn btn-sm btn-outline-primary me-1" href="Sua_DocGia.php?madocgia='.urlencode($row['madocgia']).'">Sửa</a>';
              echo '<a class="btn btn-sm btn-outline-danger" href="QL_DocGia.php?luachon=Xoa&madocgia='.urlencode($row['madocgia']).'" onclick="return confirm(\'Bạn có chắc muốn xóa?\')">Xóa</a>';
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
    document.addEventListener('DOMContentLoaded',function(){const toggleBtn=document.getElementById('toggleFormBtn');const formPanel=document.getElementById('formPanel');toggleBtn.addEventListener('click',function(){formPanel.classList.toggle('hidden');if(formPanel.classList.contains('hidden')){toggleBtn.textContent='+ Thêm đọc giả';}else{toggleBtn.textContent='✕ Đóng form';formPanel.scrollIntoView({behavior:'smooth',block:'start'});}});});
  </script>
</body>
</html>