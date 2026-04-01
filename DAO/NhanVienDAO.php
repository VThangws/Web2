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

    public function TimKiem($conn, $keyword) {
      $sql = "SELECT * FROM nhanvien WHERE manv LIKE ? OR honv LIKE ? OR tennv LIKE ?";
      $stmt = $conn->prepare($sql);
      $term = "%" . $keyword . "%";
      $stmt->bind_param("sss", $term, $term, $term);
      $stmt->execute();
      return $stmt->get_result();
    }

    public function Lay1NhanVien($conn, $manv) {
      $sql = "SELECT * FROM nhanvien WHERE manv=?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("s", $manv);
      $stmt->execute();
      $result = $stmt->get_result();
      if($row = $result->fetch_assoc()) {
        $nv = new NhanVien($row['manv'], $row['honv'], $row['tennv'], $row['gioitinh'], $row['sdt'], $row['ngaysinh']);
        return $nv;
      }
      return null;
    }
  }
?>