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
    
    /* Toggle Button */
    .toggle-btn {display: inline-block; background: #28a745; color: #fff; border: none; border-radius: 5px; padding: 12px 24px; cursor: pointer; text-decoration: none; transition: all .2s ease; font-weight: 500; font-size: 1rem; margin-bottom: 16px;}
    .toggle-btn:hover {background: #218838; transform: translateY(-2px); box-shadow: 0 4px 8px rgba(33,136,56,.3);}
    
    /* Form Animation */
    @keyframes slideDown {
      from {
        opacity: 0;
        max-height: 0;
        overflow: hidden;
        transform: translateY(-20px);
      }
      to {
        opacity: 1;
        max-height: 1000px;
        overflow: visible;
        transform: translateY(0);
      }
    }
    
    @keyframes slideUp {
      from {
        opacity: 1;
        max-height: 1000px;
        overflow: visible;
        transform: translateY(0);
      }
      to {
        opacity: 0;
        max-height: 0;
        overflow: hidden;
        transform: translateY(-20px);
      }
    }
    
    .form-panel {
      animation: slideDown 0.4s ease-in-out forwards;
    }
    
    .form-panel.hidden {
      display: none;
      animation: slideUp 0.4s ease-in-out forwards;
    }
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
        $luachon = $_GET['luachon'] ?? '';

        if ($luachon === 'Them') {
            if (empty($_GET['matacgia']) || empty($_GET['tentacgia'])) {
                echo "<script>alert('Thông tin tác giả không được để trống!');</script>";
            } else {
                $matacgia = $_GET['matacgia'];
                $tentacgia = $_GET['tentacgia'];
                $dao->Them($conn, $matacgia, $tentacgia);
            }
        } elseif ($luachon === 'Xoa') {
            $matacgia = $_GET['matacgia'] ?? '';
            if (!empty($matacgia)) {
                $dao->Xoa($conn, $matacgia);
            }
        } elseif ($luachon === 'Sua') {
            $matacgia = $_GET['matacgia'] ?? '';
            $tentacgia = $_GET['tentacgia'] ?? '';
            if (!empty($matacgia) && !empty($tentacgia)) {
                $dao->Sua($conn, $matacgia, $tentacgia);
            }
        }
    }
  ?>
  <button class="toggle-btn" id="toggleFormBtn">+ Thêm tác giả</button>
  
  <div class="panel form-panel hidden" id="formPanel">
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
    <h2>Tìm kiếm tác giả</h2>
    <form method="GET" style="display: flex; gap: 10px; align-items: center;">
      <input type="text" name="search" placeholder="Tìm theo mã hoặc tên tác giả..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" style="flex: 1; padding: 8px 12px; border: 1px solid #bbb; border-radius: 4px;">
      <button type="submit" class="btn">Tìm kiếm</button>
    </form>
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
          if(isset($_GET['search']) && trim($_GET['search']) !== '') {
            $result = $dao->TimKiem($conn, $_GET['search']);
          } else {
            $result = $dao->ToanBoDanhSach($conn);
          }
          // hiển thị danh sách tác giả ở đây
          $hasData = false;
          while($row = $result->fetch_assoc()) {
            $hasData = true;
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['matacgia']) . "</td>";
            echo "<td>" . htmlspecialchars($row['tentacgia']) . "</td>";
            echo "<td><a class='action-link edit' href='Sua_TacGia.php?matacgia=" . urlencode($row['matacgia']) . "&tentacgia=" . urlencode($row['tentacgia']) . "'>Sửa</a></td>";
            echo "<td><a class='action-link delete' href='QL_TacGia.php?luachon=Xoa&matacgia=" . urlencode($row['matacgia']) . "' onclick='return confirm(\"Bạn có chắc muốn xóa?\")'>Xóa</a></td>";
            echo "</tr>";
          }
          if (!$hasData) {
            echo "<tr><td colspan='4' style='text-align:center; padding:14px;'>Không có dữ liệu phù hợp.</td></tr>";
          }
        ?>
        </tbody>
      </table>
    </div>
  </div>

  </div>

  <script>
    // Toggle Form Animation
    document.addEventListener('DOMContentLoaded', function () {
      const toggleBtn = document.getElementById('toggleFormBtn');
      const formPanel = document.getElementById('formPanel');

      toggleBtn.addEventListener('click', function () {
        formPanel.classList.toggle('hidden');
        
        // Change button text
        if (formPanel.classList.contains('hidden')) {
          toggleBtn.textContent = '+ Thêm tác giả';
        } else {
          toggleBtn.textContent = '✕ Đóng form';
          // Scroll to form
          formPanel.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      });
    });
  </script>
</body>
</html>