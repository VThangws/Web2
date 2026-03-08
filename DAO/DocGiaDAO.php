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

    public function Xoa($conn, $madocgia) {

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