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

    public function Sua($conn, $manv, $honv, $tennv, $gioitinh, $sdt, $ngaysinh) {
      $sql = "UPDATE nhanvien
        SET honv = ?, tennv = ?,
          gioitinh = ?, sdt = ?, ngaysinh = ?
        WHERE manv = ?;
      ";
      // khớp thuộc tính
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ssssds", $honv, $tennv, $gioitinh, $sdt, $ngaysinh, $manv);
      // thực hiện truy vấn
      if($stmt->execute()) {
        echo "<script>alert('Đã sửa thông tin nhân viên!');</script>";
      }
      else echo "<script>alert('Sửa thông tin nhân viên không thành công!');</script>";
    }

    // lấy toàn bộ dữ liệu trong danh sách rồi trả về cho
    // giao diện quản lý hiển thị
    public function ToanBoDanhSach($conn) {
      $sql = "SELECT * FROM nhanvien";
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $result = $stmt->get_result();
      return $result;
    }
  }
?>