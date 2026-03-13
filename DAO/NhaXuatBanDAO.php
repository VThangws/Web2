<?php
  require_once '../../../model/NhaXuatBan.php';

  class NhaXuatBanDAO {
    public function Them($conn, $manxb, $tennxb, $diachi, $sdt, $email) {
      $sql = "INSERT INTO nhaxuatban(manxb, tennxb, diachi, sdt, email)
      VALUES(?, ?, ?, ?, ?)";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("sssss", $manxb, $tennxb, $diachi, $sdt, $email);
      if($stmt->execute()) {
        echo "<script>alert('Đã thêm thông tin nhà xuất bản!');</script>";
      }
      else echo "<script>alert('Thêm thông tin nhà xuất bản không thành công!');</script>";
    }

    public function Xoa($conn, $manxb) {
      $sql = "DELETE FROM nhaxuatban WHERE manxb=?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("s", $manxb);
      if($stmt->execute()) {
        echo "<script>alert('Đã xóa thông tin nhà xuất bản!');</script>";
      }
      else echo "<script>alert('Xóa thông tin nhà xuất bản không thành công!');</script>";
    }

    public function Sua($conn, $manxb) {
      $sql = "UPDATE nhaxuatban SET tennxb=?,
      diachi=?,
      sdt=?,
      email=?
      WHERE manxb=?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("sssss", $tennxb, $diachi, $sdt, $email, $manxb);
      if($stmt->execute()) {
        echo "<script>alert('Đã cập nhật thông tin nhà xuất bản!');</script>";
      }
      else echo "<script>alert('Cập nhật thông tin nhà xuất bản không thành công! ');</script>";
    }

    public function LayToanBoDanhSach($conn) {
      $sql = 'SELECT * FROM nhaxuatban';
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $result = $stmt->get_result();
      // tạo mảng lưu danh sách
      $ls = [];
      while($row = $result->fetch_assoc()) {
        $nxb = new NhaXuatBan($row['manxb'], $row['tennxb'], $row['diachi'],
        $row['sdt'], $row['email'], $row['manxb']);

        $ls[] = $nxb;
      }
      return $ls;
    }
  }
?>