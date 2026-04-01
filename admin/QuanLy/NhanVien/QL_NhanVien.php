<?php
require_once __DIR__ . '/../../auth.php';
require_admin_login();
require_admin_permission('NHANVIEN');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quản lý nhân viên</title>
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
    .form-grid input[type="date"] {width: 100%; padding: 8px 10px; border: 1px solid #bbb; border-radius: 4px; font-size: .95rem;}
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
  <?php
    require_once '../../../database/ConnectDB.php';
    require_once '../../../DAO/NhanVienDAO.php';
    $dao = new NhanVienDAO();

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $luachon = $_GET['luachon'] ?? '';

        if ($luachon === 'Them') {
            if (
                empty($_GET['manv'])
                || empty($_GET['honv'])
                || empty($_GET['tennv'])
                || empty($_GET['gioitinh'])
                || empty($_GET['sdt'])
                || empty($_GET['ngaysinh'])
            ) {
                echo "<script>alert('Thông tin nhân viên không được để trống!');</script>";
            } else {
                $manv = $_GET['manv'];
                $honv = $_GET['honv'];
                $tennv = $_GET['tennv'];
                $gioitinh = $_GET['gioitinh'];
                $sdt = $_GET['sdt'];
                $ngaysinh = $_GET['ngaysinh'];

                $dao->Them($conn, $manv, $honv, $tennv, $gioitinh, $sdt, $ngaysinh);
                echo "<script>alert('Thêm nhân viên thành công!');</script>";
            }
        } elseif ($luachon === 'Sua') {
            $manv = $_GET['manv'] ?? '';
            $honv = $_GET['honv'] ?? '';
            $tennv = $_GET['tennv'] ?? '';
            $gioitinh = $_GET['gioitinh'] ?? '';
            $sdt = $_GET['sdt'] ?? '';
            $ngaysinh = $_GET['ngaysinh'] ?? '';

            $dao->Sua($conn, $manv, $honv, $tennv, $gioitinh, $sdt, $ngaysinh);
        } elseif ($luachon === 'Xoa') {
            $manv = $_GET['manv'] ?? '';
            $sql = 'DELETE FROM nhanvien WHERE manv=?';
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $manv);
            if ($stmt->execute()) {
                echo "<script>alert('Đã xóa nhân viên!');</script>";
            } else {
                echo "<script>alert('Xóa nhân viên không thành công!');</script>";
            }
        }
    }
    ?>

    <div class="container">
        <div class="panel KhungThongTin">
            <h2>Thêm nhân viên mới</h2>
            <form method="get" class="form-grid">
                <div>
                    <label for="manv">Mã nhân viên</label>
                    <input type="text" id="manv" name="manv" required>
                </div>
                <div>
                    <label for="honv">Họ nhân viên</label>
                    <input type="text" id="honv" name="honv" required>
                </div>
                <div>
                    <label for="tennv">Tên nhân viên</label>
                    <input type="text" id="tennv" name="tennv" required>
                </div>
                <div>
                    <label for="gioitinh">Giới tính</label>
                    <div style="display: flex; gap: 10px; align-items: center; padding-top: 8px;">
                        <label><input type="radio" name="gioitinh" value="Nam" required> Nam</label>
                        <label><input type="radio" name="gioitinh" value="Nữ" required> Nữ</label>
                    </div>
                </div>
                <div>
                    <label for="sdt">Số điện thoại</label>
                    <input type="text" id="sdt" name="sdt" required>
                </div>
                <div>
                    <label for="ngaysinh">Ngày sinh</label>
                    <input type="date" id="ngaysinh" name="ngaysinh" required>
                </div>
                <div class="full form-actions">
                    <label><input type="radio" name="luachon" value="Them" required> Thêm nhân viên mới</label>
                    <label><input type="radio" name="luachon" value="Sua" required> Sửa thông tin nhân viên</label>
                    <button type="submit" class="btn">OK</button>
                </div>
            </form>
        </div>

        <div class="panel KhungTimKiem">
            <form method="get" class="search-form">
                <label for="search">Tìm kiếm:</label>
                <input type="text" id="search" name="search" placeholder="Nhập mã hoặc tên nhân viên..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button type="submit" class="btn">Tìm</button>
            </form>
        </div>

        <div class="panel KhungDanhSach">
            <h2>Danh sách nhân viên</h2>
            <div class="table-responsive">
                <table id="resultTable">
                    <thead>
                        <tr>
                            <th>Mã nhân viên</th>
                            <th>Họ</th>
                            <th>Tên</th>
                            <th>Giới tính</th>
                            <th>Số điện thoại</th>
                            <th>Ngày sinh</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(isset($_GET['search']) && trim($_GET['search']) !== '') {
                            $result = $dao->TimKiem($conn, $_GET['search']);
                        } else {
                            $result = $dao->ToanBoDanhSach($conn);
                        }
                        $hasData = false;
                        while ($row = $result->fetch_assoc()) {
                            $hasData = true;
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['manv']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['honv']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['tennv']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['gioitinh']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['sdt']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['ngaysinh']) . "</td>";
                            echo "<td>" .
                            "<a class='action-link edit' href='Sua_NhanVien.php?manv=" . urlencode($row['manv']) . "'>Sửa</a>" .
                            "<a class='action-link delete' href='QL_NhanVien.php?manv=" . urlencode($row['manv']) . "&luachon=Xoa' onclick='return confirm(\"Bạn có chắc muốn xóa nhân viên này?\")'>Xóa</a>" .
                            "</td>";
                            echo "</tr>";
                        }
                        if (!$hasData) {
                            echo "<tr><td colspan='7' class='empty-state'>Không có dữ liệu phù hợp.</td></tr>";
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