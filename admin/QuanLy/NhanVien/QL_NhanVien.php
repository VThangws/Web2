<?php
require_once __DIR__ . '/../../auth.php';
require_admin_login();
require_admin_permission('NHANVIEN');
error_reporting(E_ERROR | E_PARSE);
?>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quản lý nhân viên</title>
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
    require_once '../../../model/NhanVien.php';
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
    }
  ?>
  <div class="panel">
    <h2>Quản lý nhân viên</h2>
    <div class="formThongTin">
      <form method="GET" class="form-grid">
        <div>
          <label for="manv">Mã nhân viên</label>
          <input type="text" id="manv" name="manv" required>
        </div>
        <div>
          <label for="honv">Họ</label>
          <input type="text" id="honv" name="honv" required>
        </div>
        <div>
          <label for="tennv">Tên</label>
          <input type="text" id="tennv" name="tennv" required>
        </div>
        <div>
          <label for="gioitinh">Giới tính</label>
          <select id="gioitinh" name="gioitinh" required>
            <option value="Nam">Nam</option>
            <option value="Nữ">Nữ</option>
          </select>
        </div>
        <div>
          <label for="sdt">Số điện thoại</label>
          <input type="text" id="sdt" name="sdt" required>
        </div>
        <div>
          <label for="ngaysinh">Ngày sinh</label>
          <input type="date" id="ngaysinh" name="ngaysinh" required>
        </div>
        <!-- <input type="text" value="Them" name="luachon" id="luachon" style="display: none"> -->
        <button type="submit" class="btn" value="Them" name="luachon">Thêm</button>
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
            <th>Mã nhân viên</th>
            <th>Họ</th>
            <th>Tên</th>
            <th>Giới tính</th>
            <th>SĐT</th>
            <th>Ngày sinh</th>
            <th>Hành động</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $query = isset($_GET['search']) && trim($_GET['search']) !== '' ? $dao->TimKiem($conn, $_GET['search']) : $dao->ToanBoDanhSach($conn);
            while($row = $query->fetch_assoc()) {
              echo '<tr>';
              echo '<td>'.htmlspecialchars($row['manv']).'</td>';
              echo '<td>'.htmlspecialchars($row['honv']).'</td>';
              echo '<td>'.htmlspecialchars($row['tennv']).'</td>';
              echo '<td>'.htmlspecialchars($row['gioitinh']).'</td>';
              echo '<td>'.htmlspecialchars($row['sdt']).'</td>';
              echo '<td>'.htmlspecialchars($row['ngaysinh']).'</td>';
              echo '<td>';
              echo '<a class="action-link edit" href="Sua_NhanVien.php?manv='.urlencode($row['manv']).'">Sửa</a>';
              echo '<a class="action-link delete" href="QL_NhanVien.php?luachon=Xoa&manv='.urlencode($row['manv']).'" onclick="return confirm(\'Bạn có chắc muốn xóa?\')">Xóa</a>';
              echo '</td>';
              echo '</tr>';
            }
          ?>
        </tbody>
      </table>
      <button id="loadMoreBtn" class="btn" type="button" style="margin-top:10px;">Xem thêm</button>
    </div>
  </div>
  <script>
    const rowsPerPage = 10;
    let currentLimit = rowsPerPage;

    function updateRowsVisibility() {
      const rows = document.querySelectorAll('#resultTable tbody tr');
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

    document.addEventListener('DOMContentLoaded', () => {
      const moreBtn = document.getElementById('loadMoreBtn');
      if (moreBtn) {
        moreBtn.addEventListener('click', () => {
          currentLimit += rowsPerPage;
          updateRowsVisibility();
        });
      }
      updateRowsVisibility();
    });
  </script>
  </div>
</body>
</html>