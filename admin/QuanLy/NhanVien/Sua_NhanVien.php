<?php
require_once __DIR__ . '/../../login/auth.php';
require_admin_login();
require_admin_permission('NHANVIEN');
?>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sửa thông tin nhân viên</title>
  <style>
    body {font-family: Arial, Helvetica, sans-serif; background: #f3f4f8; margin: 0; padding: 0;}
    .container {width: 95%; max-width: 700px; margin: 2rem auto;}
    .panel {background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,.08);}
    .panel h2 {margin: 0 0 1rem; color: #333; font-size: 1.3rem;}
    .form-group {margin-bottom: 12px;}
    .form-group label {display: block; font-weight: 600; margin-bottom: 4px; color: #333;}
    .form-group input {width: 100%; padding: 8px 10px; border: 1px solid #bbb; border-radius: 4px; font-size: .95rem;}
    .form-group input[readonly] {background: #f0f0f0; cursor: not-allowed;}
    .form-group input:focus {outline: none; border-color: #007bff; box-shadow: 0 0 4px rgba(0,123,255,.3);}
    .form-actions {display: flex; gap: 10px; margin-top: 20px;}
    .btn {display: inline-block; background: #007bff; color: #fff; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; text-decoration: none; font-weight: 500; transition: all .2s;}
    .btn:hover {background: #0056b3; transform: translateY(-1px);}
    .btn-secondary {background: #6c757d;}
    .btn-secondary:hover {background: #5a6268;}
  </style>
</head>
<body>
  <div class="container">
    <div class="panel">
      <h2>Sửa thông tin nhân viên</h2>
      <?php
        require_once '../../../database/KetNoiDB.php';
        require_once '../../../model/NhanVien.php';
        require_once '../../../DAO/NhanVienDAO.php';
        $dao = new NhanVienDAO();

        // lấy thông tin nhân viên cần sửa
        if(isset($_REQUEST['manv'])) {
          $nv = $dao->Lay1NhanVien($conn, $_REQUEST['manv']);
          if($nv) {
      ?>
            <form method="GET" action="QL_NhanVien.php">
              <div class="form-group">
                <label for="manv">Mã nhân viên</label>
                <input type="text" id="manv" name="manv" value="<?php echo htmlspecialchars($nv->getManv()); ?>" readonly>
              </div>
              <div class="form-group">
                <label for="honv">Họ</label>
                <input type="text" id="honv" name="honv" value="<?php echo htmlspecialchars($nv->getHonv()); ?>" required>
              </div>
              <div class="form-group">
                <label for="tennv">Tên</label>
                <input type="text" id="tennv" name="tennv" value="<?php echo htmlspecialchars($nv->getTennv()); ?>" required>
              </div>
              <div class="form-group">
                <label for="gioitinh">Giới tính</label>
                <select id="gioitinh" name="gioitinh" required style="width:100%; padding: 8px 10px; border: 1px solid #bbb; border-radius: 4px;">
                  <option value="Nam" <?php echo $nv->getGioitinh() === 'Nam' ? 'selected' : ''; ?>>Nam</option>
                  <option value="Nữ" <?php echo $nv->getGioitinh() === 'Nữ' ? 'selected' : ''; ?>>Nữ</option>
                </select>
              </div>
              <div class="form-group">
                <label for="sdt">Số điện thoại</label>
                <input type="text" id="sdt" name="sdt" value="<?php echo htmlspecialchars($nv->getSdt()); ?>" required>
              </div>
              <div class="form-group">
                <label for="ngaysinh">Ngày sinh</label>
                <input type="date" id="ngaysinh" name="ngaysinh" value="<?php echo htmlspecialchars($nv->getNgaysinh()); ?>" required>
              </div>
              <div class="form-actions">
                <button type="submit" name="luachon" value="Sua" class="btn">Lưu thay đổi</button>
                <a href="QL_NhanVien.php" class="btn btn-secondary">Hủy</a>
              </div>
            </form>
      <?php
          } else {
            echo "<p style='color: #dc3545;'>Không tìm thấy nhân viên này.</p>";
          }
        } else {
          echo "<p style='color: #dc3545;'>Vui lòng chọn nhân viên để sửa.</p>";
        }
      ?>
    </div>
  </div>
</body>
</html>
