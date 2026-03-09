<?php
  class TacGiaDAO {
    public function Them($conn, $matacgia, $tentacgia) {
      $sql = "INSERT INTO tacgia(matacgia, tentacgia) VALUES (?,?)";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ss", $matacgia, $tentacgia);

      // thực hiện thêm mới tác giả
      if($stmt->execute()) {
        echo "<script>alert('Đã thêm mới tác giả!');</script>";
      }
      else {
        echo "<script>alert('Thêm mới tác giả không thành công!');</script>";
      }
    }

    public function ToanBoDanhSach($conn) {
      $sql = "SELECT * FROM tacgia";
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $result = $stmt->get_result();
      return $result;
    }
  }
?>