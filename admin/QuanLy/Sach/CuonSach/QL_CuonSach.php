<?php
require_once __DIR__ . '/../../../login/auth.php';
require_admin_login();
require_admin_permission('SACH');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quản lý Cuốn sách</title>
  <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
  <style>
    * {box-sizing: border-box;}
    body {font-family: 'Segoe UI', Arial, sans-serif; background: #f3f4f8; margin: 0; padding: 0;}
    .container {width: 98%; max-width: 1300px; margin: 1rem auto;}
    .panel {background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 16px; margin-bottom: 16px; box-shadow: 0 2px 8px rgba(0,0,0,.08);}
    .panel h2 {margin: 0 0 0.8rem; color: #333; font-size: 1.4rem;}

    .form-group {margin-bottom: 14px;}
    .form-group label {display: block; font-weight: 600; margin-bottom: 5px; color: #333;}
    .form-group input[type="text"],
    .form-group input[type="date"],
    .form-group input[type="file"] {width: 100%; padding: 8px 10px; border: 1px solid #bbb; border-radius: 4px; font-size: .95rem;}
    .form-fieldset {display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px;}
    .form-actions {display: flex; gap: 10px; align-items: center; margin-top: 14px;}

    .btn:not(.btn-outline-primary):not(.btn-outline-danger) {display:inline-block; background:#007bff; color:#fff; border:none; border-radius:5px; padding:8px 14px; cursor:pointer; text-decoration:none; transition:all .2s ease;}
    .btn:not(.btn-outline-primary):not(.btn-outline-danger):hover {background:#0056b3;}
    .btn-danger {background:#dc3545;}
    .btn-danger:hover {background:#c82333;}

    .table-responsive {overflow-x:auto;}
    table {width:100%; border-collapse:collapse;}
    th,td {padding:8px 10px; border:1px solid #ddd;}
    th {background:#f7f7f7; font-weight:600;}
    tr:nth-child(even){background:#fbfbfb;}
    tr:hover{background:#f0f0f0;}

    #loadMoreBtn {margin-top:12px;}
  </style>
</head>
<body>
    <?php require_once __DIR__ . '/../../../layout/admin_sidebar.php'; ?>

    <?php
    require_once __DIR__ . '/../../../../database/KetNoiDB.php';
    require_once __DIR__ . '/../../../../model/Sach/CuonSach.php';
    require_once __DIR__ . '/../../../../DAO/Sach/CuonSachDAO.php';

    $dao = new CuonSachDAO();

    if ($_SERVER['REQUEST_METHOD'] === 'GET' || $_SERVER['REQUEST_METHOD'] === 'POST') {
        $luachon = $_REQUEST['luachon'] ?? '';

        if ($luachon === 'Them' || $luachon === 'Sua') {
            $macuonsach = $_REQUEST['macuonsach'] ?? '';
            $madausach = $_REQUEST['madausach'] ?? '';
            $mavitri = $_REQUEST['mavitri'] ?? '';
            $trangthai = $_REQUEST['trangthai'] ?? '';
            $tinhtrang = $_REQUEST['tinhtrang'] ?? '';

            if ($luachon === 'Them') {
                $dao->Them($conn, $macuonsach, $madausach, $mavitri, $trangthai, $tinhtrang);
            } else {
                $dao->Sua($conn, $macuonsach, $madausach, $mavitri, $trangthai, $tinhtrang);
            }
        } elseif ($luachon === 'Xoa') {
            $macuonsach = $_REQUEST['macuonsach'] ?? '';
            if ($macuonsach !== '') {
                $dao->Xoa($conn, $macuonsach);
            }
        }
    }
  ?>
  <div class="container">
    <div class="panel">
      <h2>Thêm / Sửa cuốn sách</h2>
      <form method="get" class="form-fieldset">
        <div class="form-group">
          <label for="macuonsach">Mã cuốn sách</label>
          <input type="text" id="macuonsach" name="macuonsach" required>
        </div>
        <div class="form-group">
          <label for="madausach">Mã đầu sách</label>
          <input type="text" id="madausach" name="madausach" required>
        </div>
        <div class="form-group">
          <label for="mavitri">Mã vị trí</label>
          <input type="text" id="mavitri" name="mavitri" required>
        </div>
        <div class="form-group">
          <label for="trangthai">Trạng thái</label>
          <input type="text" id="trangthai" name="trangthai" required>
        </div>
        <div class="form-group">
          <label for="tinhtrang">Tình trạng</label>
          <input type="text" id="tinhtrang" name="tinhtrang" required>
        </div>
        <div class="form-actions full">
          <button type="submit" name="luachon" value="Them" class="btn">Thêm</button>
          <button type="submit" name="luachon" value="Sua" class="btn">Sửa</button>
        </div>
      </form>
    </div>
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
                 echo "<td class='text-end'>" .
                   "<a class='btn btn-sm btn-outline-primary me-1' href='Sua_CuonSach.php?macuonsach=" . urlencode($item->getMacuonsach()) . "'>Sửa</a>" .
                   "<a class='btn btn-sm btn-outline-danger' href='QL_CuonSach.php?luachon=Xoa&macuonsach=" . urlencode($item->getMacuonsach()) . "' onclick='return confirm(\"Bạn có chắc muốn xóa?\")'>Xóa</a>" .
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