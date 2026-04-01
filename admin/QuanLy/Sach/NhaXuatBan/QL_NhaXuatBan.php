<?php
require_once __DIR__ . '/../../../auth.php';
require_admin_login();
require_admin_permission('NXB');
error_reporting(E_ERROR | E_PARSE);
?>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quản lý Nhà xuất bản</title>
  <link rel="stylesheet" href="../../style.css" />
</head>
<body>
  <div class="container">
  <?php
    require_once '../../../../model/Sach/NhaXuatBan.php';
    require_once '../../../../DAO/Sach/NhaXuatBanDAO.php';
    require_once '../../../../database/KetNoiDB.php';
    $dao = new NhaXuatBanDAO();

    $manxb = $_GET['manxb'] ?? '';
    $tennxb = $_GET['tennxb'] ?? '';
    $diachi = $_GET['diachi'] ?? '';
    $sdt = $_GET['sdt'] ?? '';
    $email = $_GET['email'] ?? '';

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      $luachon = $_GET['luachon'] ?? '';

      if ($luachon === 'Them') {
        if (empty($manxb) || empty($tennxb) || empty($diachi) || empty($sdt) || empty($email)) {
          echo "<script>alert('Vui lòng điền đầy đủ thông tin nhà xuất bản.');</script>";
        } else {
          $dao->Them($conn, $manxb, $tennxb, $diachi, $sdt, $email);
          // reset các giá trị để tránh lặp lại khi reload
          $manxb = $tennxb = $diachi = $sdt = $email = '';
        }
      } elseif ($luachon === 'Xoa') {
        if (!empty($manxb)) {
          $dao->Xoa($conn, $manxb);
          $manxb = $tennxb = $diachi = $sdt = $email = '';
        }
      } elseif ($luachon === 'Sua') {
        if (empty($manxb) || empty($tennxb) || empty($diachi) || empty($sdt) || empty($email)) {
          echo "<script>alert('Vui lòng điền đầy đủ thông tin khi sửa nhà xuất bản.');</script>";
        } else {
          $dao->Sua($conn, $manxb, $tennxb, $diachi, $sdt, $email);
        }
      }
    }
  ?>
    <div class="panel KhungThongTin">
      <form method="GET" class="form-grid">
        <div>
          <label for="manxb">Mã nhà xuất bản</label>
          <input type="text" id="manxb" name="manxb" required value="<?php echo htmlspecialchars($manxb); ?>">
        </div>
        <div>
          <label for="tennxb">Tên nhà xuất bản</label>
          <input type="text" id="tennxb" name="tennxb" required value="<?php echo htmlspecialchars($tennxb); ?>">
        </div>
        <div>
          <label for="diachi">Địa chỉ</label>
          <input type="text" id="diachi" name="diachi" required value="<?php echo htmlspecialchars($diachi); ?>">
        </div>
        <div>
          <label for="sdt">Số điện thoại</label>
          <input type="text" id="sdt" name="sdt" required value="<?php echo htmlspecialchars($sdt); ?>">
        </div>
        <div>
          <label for="email">Email</label>
          <input type="text" id="email" name="email" required value="<?php echo htmlspecialchars($email); ?>">
        </div>
        <button class="btn" type="submit" name="luachon" value="Them">Thêm</button>
      </form>
    </div>

    <div class="panel KhungTimKiem">
      <form method="GET" class="form-grid" style="grid-template-columns: 1fr auto; align-items: end; gap: 10px;">
        <div>
          <label for="search">Tìm kiếm theo mã hoặc tên nhà xuất bản</label>
          <input type="text" id="search" name="search" placeholder="Nhập mã / tên" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        </div>
        <button class="btn" type="submit">Tìm kiếm</button>
      </form>
    </div>

    <div class="panel KhungDanhSach">
      <div class="table-responsive">
        <table id="resultTable">
          <thead>
            <tr>
              <th>Mã nhà xuất bản</th>
              <th>Tên nhà xuất bản</th>
              <th>Địa chỉ</th>
              <th>Số điện thoại</th>
              <th>Email</th>
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody>
            <?php
              if(isset($_GET['search']) && trim($_GET['search']) !== '') {
                $result = $dao->TimKiem($conn, $_GET['search']);
              } else {
                $result = $dao->LayToanBoDanhSach($conn);
              }

              foreach($result as $item) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($item->getManxb()) . "</td>";
                echo "<td>" . htmlspecialchars($item->getTennxb()) . "</td>";
                echo "<td>" . htmlspecialchars($item->getDiachi()) . "</td>";
                echo "<td>" . htmlspecialchars($item->getSdt()) . "</td>";
                echo "<td>" . htmlspecialchars($item->getEmail()) . "</td>";
                echo "<td>" .
                  "<a class='btn' href='Sua_NhaXuatBan.php?manxb=" . urlencode($item->getManxb()) . "&tennxb=" . urlencode($item->getTennxb()) . "&diachi=" . urlencode($item->getDiachi()) . "&sdt=" . urlencode($item->getSdt()) . "&email=" . urlencode($item->getEmail()) . "'>Sửa</a> " .
                  "<a class='btn' style='background:#dc3545;' href='QL_NhaXuatBan.php?luachon=Xoa&manxb=" . urlencode($item->getManxb()) . "'>Xóa</a>" .
                  "</td>";
                echo "</tr>";
              }

              if (empty($result)) {
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
      const rows = document.querySelectorAll('#resultTable tbody tr');
      const total = rows.length;
      rows.forEach((row,index) => { row.style.display = index < currentLimit ? 'table-row' : 'none'; });
      const moreBtn = document.getElementById('loadMoreBtn');
      if (!moreBtn) return;
      if (currentLimit >= total) { moreBtn.style.display = 'none'; }
      else { moreBtn.style.display = 'inline-block'; moreBtn.textContent = `Xem thêm ${Math.min(rowsPerPage, total-currentLimit)} mục`; }
    }

    document.addEventListener('DOMContentLoaded', () => {
      document.getElementById('loadMoreBtn').addEventListener('click', () => { currentLimit += rowsPerPage; updateRowsVisibility(); });
      updateRowsVisibility();
    });
  </script>
</body>
</html>