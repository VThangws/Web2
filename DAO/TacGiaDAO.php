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

    public function Xoa($conn, $matacgia) {
      $sql = "DELETE FROM tacgia WHERE matacgia=?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("s", $matacgia);
      if($stmt->execute()) {
        echo "<script>alert('Đã xóa tác giả!');</script>";
      }
      else echo "<script>alert('Xóa tác giả không thành công!');</script>";
    }

    public function Sua($conn, $matacgia, $tentacgia) {
      $sql = "UPDATE tacgia SET tentacgia=? WHERE matacgia=?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ss", $tentacgia, $matacgia);
      if($stmt->execute()) {
        echo "<script>alert('Đã cập nhật thông tin tác giả!');</script>";
      }
      else echo "<script>alert('Cập nhật thông tin tác giả không thành công!');</script>";
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