<?php
  // require_once "../database/KetNoiDB.php";
  class NhanVienDAO {
    public function Them($conn, $manv, $honv, $tennv, $gioitinh, $sdt, $ngaysinh) {
      // chuẩn bị lệnh truy vấn
      $sql = "INSERT INTO nhanvien(manv, honv, tennv
      , gioitinh, sdt, ngaysinh)
       VALUES (?, ?, ?, ?, ?, ?)";
      
      // khớp thuộc tính
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("sssssd", $manv, $honv, $tennv, $gioitinh, $sdt, $ngaysinh);

      // thực hiện truy vấn
      if($stmt->execute()) {
        echo "Đã thêm nhân viên mới";
      }
      else echo "Thêm nhân viên không thành công";
    }
  }
?>