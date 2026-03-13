<?php
  require_once '../../../model/TheLoai.php';

  class TheLoaiDAO {
    public function Them($conn, $matheloai, $tentheloai) {
      $sql = "INSERT INTO theloai (matheloai, tentheloai) VALUES (?, ?)";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ss", $matheloai, $tentheloai);
      if ($stmt->execute()) {
        echo "<script>alert('Thêm thể loại thành công!');</script>";
      } else {
        echo "<script>alert('Lỗi: " . $sql . "<br>" . $conn->error . "');</script>";
      }
    }

    public function LayDanhSachTheLoai($conn) {
      $sql = "SELECT * FROM theloai";
      $result = $conn->query($sql);
      $theloais = [];
      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          $theloai = new TheLoai($row['matheloai'], $row['tentheloai']);
          $theloais[] = $theloai;
        }
      }
      return $theloais;
    }

    public function Xoa($conn, $matheloai) {
      $sql = "DELETE FROM theloai WHERE matheloai = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("s", $matheloai);
      if ($stmt->execute()) {
        echo "<script>alert('Xóa thể loại thành công!');</script>";
      } else {
        echo "<script>alert('Lỗi: " . $sql . "<br>" . $conn->error . "');</script>";
      }
    }

    public function Sua($conn, $matheloai, $tentheloai) {
      $sql = "UPDATE theloai SET tentheloai=? WHERE matheloai=?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ss", $tentheloai, $matheloai);
      if($stmt->execute()) {
        echo "<script>alert('Đã cập nhật thông tin thể loại!');</script>";
      }
      else echo "<script>alert('Cập nhật thông tin thể loại không thành công!');</script>";
    }
  }
?>