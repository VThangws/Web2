<?php
require_once __DIR__ . '/../../../auth.php';
require_admin_login();
require_admin_permission('SACH');
?>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quản lý Cuốn sách</title>
  <style>
    body {font-family: Arial, sans-serif; background: #f2f3f8; margin: 0;}
    .container {width: 98%; max-width: 1300px; margin: 1rem auto;}
    .panel {background: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 14px; margin-bottom: 16px; box-shadow: 0 1px 5px rgba(0,0,0,.08);}
    .panel h2 {margin-top: 0; margin-bottom: .7rem; color: #333;}
    .form-grid {display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px;}
    .form-grid label {font-weight: 500; margin-bottom: 4px; display: block;}
    .form-grid input[type="text"], .form-grid input[type="date"], .form-grid input[type="file"] {width: 100%; padding: 6px 8px; border: 1px solid #aaa; border-radius: 4px;}
    .form-grid .full {grid-column: 1 / -1;}
    .btn {display: inline-block; background: #007bff; color: #fff; border: 0; border-radius: 5px; padding: 8px 14px; cursor: pointer; text-decoration: none;}
    .btn:hover {background: #0056b3;}
    .table-responsive {overflow-x: auto;}
    table {width: 100%; border-collapse: collapse;}
    th, td {padding: 8px 10px; border: 1px solid #ddd;}
    th {background: #f7f7f7;}
    tr:nth-child(even) {background: #fbfbfb;}
    #loadMoreBtn {margin-top: 12px;}
  </style>
</head>
<body>
  <?php require_once __DIR__ . '/../../../layout/admin_sidebar.php'; ?>
  <?php
    require_once "../../../../database/KetNoiDB.php";
    require_once "../../../../model/Sach/CuonSach.php";
    require_once "../../../../DAO/Sach/CuonSachDAO.php";
    $dao = new CuonSachDAO();
    
    if($_SERVER['REQUEST_METHOD'] == "GET" || $_SERVER['REQUEST_METHOD'] == "POST") {
      $luachon = $_REQUEST['luachon'] ?? '';
      if($luachon == "Them" || $luachon == "Sua") {
        // lấy thông tin mới của cuốn sách
        $macuonsach = $_REQUEST['macuonsach'] ?? '';
        $madausach = $_REQUEST['madausach'] ?? '';
        $mavitri = $_REQUEST['mavitri'] ?? '';
        $trangthai = $_REQUEST['trangthai'] ?? '';
        $tinhtrang = $_REQUEST['tinhtrang'] ?? '';

        if($luachon == "Them") {
          // thực hiện thêm cuốn sách mới
          $dao->Them($conn, $macuonsach, $madausach, $mavitri,
          $trangthai, $tinhtrang);
        }
        else {
          // thực hiện sửa thông tin cuốn sách
          $dao->Sua($conn, $macuonsach, $madausach, $mavitri,
          $trangthai, $tinhtrang);
        }
      }
      else if($luachon == "Xoa") {
        // thực hiện xóa cuốn sách
        $macuonsach = $_REQUEST['macuonsach'] ?? '';
        if($macuonsach !== '') {
          $dao->Xoa($conn, $macuonsach);
        }
      }
    }
  ?>
  <div class="KhungThongTin">
    <form method="get">
      <label for="macuonsach">Mã cuốn sách</label>
      <input type="text" id="macuonsach" name="macuonsach" /><br>
      <label for="macuonsach">Mã đầu sách</label>
      <input type="text" id="madausach" name="madausach" /><br>
      <label for="macuonsach">Mã vị trí</label>
      <input type="text" id="mavitri" name="mavitri" /><br>
      <label for="macuonsach">Trạng thái</label>
      <input type="text" id="trangthai" name="trangthai" /><br>
      <label for="macuonsach">Tình trạng</label>
      <input type="text" id="tinhtrang" name="tinhtrang" /><br>
      <input type="submit" id="luachon" name="luachon" value="Them"/>
    </form>
    </div>

    <div class="panel KhungTimKiem">
      <form method="get" class="form-grid" style="grid-template-columns: 1fr auto; align-items: end; gap: 10px;">
        <div>
          <label for="search">Tìm kiếm theo mã cuốn hoặc mã đầu sách</label>
          <input type="text" id="search" name="search" placeholder="Nhập mã cuốn / mã đầu sách" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        </div>
        <button type="submit" class="btn">Tìm kiếm</button>
      </form>
    </div>

    <div class="panel KhungDanhSach">
      <div class="table-responsive">
        <table id="resultTable">
          <thead>
            <tr>
              <th>Mã cuốn sách</th>
              <th>Mã đầu sách</th>
              <th>Mã vị trí</th>
              <th>Trạng thái</th>
              <th>Tình trạng</th>
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody>
            <?php
              if (isset($_GET['search']) && trim($_GET['search']) !== '') {
                $danhsach = $dao->TimKiem($conn, $_GET['search']);
              } else {
                $danhsach = $dao->LayToanBoDanhSach($conn);
              }

              foreach($danhsach as $item) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($item->getMacuonsach()) ."</td>";
                echo "<td>" . htmlspecialchars($item->getMadausach()) . "</td>";
                echo "<td>" . htmlspecialchars($item->getMavitri()) . "</td>";
                echo "<td>" . htmlspecialchars($item->getTrangthai()) . "</td>";
                echo "<td>" . htmlspecialchars($item->getTinhtrang()) . "</td>";
                echo "<td>" .
                     "<a class='btn' href='Sua_CuonSach.php?macuonsach=" . urlencode($item->getMacuonsach()) . "'>Sửa</a> " .
                     "<a class='btn' style='background:#dc3545;' href='QL_CuonSach.php?luachon=Xoa&macuonsach=" . urlencode($item->getMacuonsach()) . "'>Xóa</a>" .
                     "</td>";
                echo "</tr>";
              }

              if (empty($danhsach)) {
                echo "<tr><td colspan='6' style='text-align:center; padding:14px;'>Không có dữ liệu phù hợp.</td></tr>";
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