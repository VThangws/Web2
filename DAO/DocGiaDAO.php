<?php
  class DocGiaDAO {
    public function Them($conn, $madocgia, $hodocgia, 
    $tendocgia, $email, $sdt, $ngaysinh, $diachi) {
      $sql = "INSERT INTO docgia(madocgia, hodocgia, tendocgia,
      email, sdt, ngaysinh, diachi) VALUES(?, ?, ?, ?, ?, ?, ?)";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("sssssds", $madocgia, $hodocgia, $tendocgia,
      $email, $sdt, $ngaysinh, $diachi);

      // thực hiện thêm
      if($stmt->execute()) {
        echo "<script>alert('Đã thêm thông tin 
        đọc giả');</script>";
      }
      else echo "<script>alert('Thêm thông tin đọc giả 
      không thành công');</script>";
    }

    public function Sua($conn, $madocgia, $hodocgia, $tendocgia, $email, $sdt, $ngaysinh, $diachi) {
      $sql = "UPDATE docgia
      SET hodocgia=?, tendocgia=?,
      email=?, sdt=?, ngaysinh=?, diachi=?
      WHERE madocgia=?";

      // thực hiện cập nhật
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ssssdss", $hodocgia, $tendocgia, $email, 
      $sdt, $ngaysinh, $diachi, $madocgia);

      // thông báo tính trạng cập nhật
      if($stmt->execute()) {
        echo '<script>alert("Đã cập nhật thông tin đọc giả!");</script>';
      }
      else echo '<script>alert("Cập nhật thông tin đọc giả không thành công!");</script>';
    }

    public function ToanBoDanhSach($conn) {
      $sql = "SELECT * FROM docgia";
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $result = $stmt->get_result();
      return $result;
    }
  }
?>