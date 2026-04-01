<?php
  require_once __DIR__ . '/../../model/Sach/DauSach.php';

  class DauSachDAO {
    public function Them($conn, $madausach, $tensach,
    $namxuatban, $donggia, $matacgia,
    $matheloai, $manxb, $mota, $anhbia) {
      $sql = "INSERT INTO dausach(madausach,
      tensach, namxuatban, dongia, matacgia,
      matheloai, manxb, mota, anhbia) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("sssssssss", $madausach,
      $tensach,
      $namxuatban,
      $donggia,
      $matacgia,
      $matheloai,
      $manxb,
      $mota,
      $anhbia);

      if($stmt->execute()) {
        echo "Thêm đầu sách thành công!";
      }
      else {
        echo "Lỗi: " . $stmt->error;
      }
    }

    public function Xoa($conn, $madausach) {
      // lấy đường dẫn file ảnh bìa
      $sql = "SELECT anhbia FROM dausach WHERE madausach=?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("s", $madausach);
      $stmt->execute();
      $anhbia = $stmt->get_result();
      $anhbia = $anhbia->fetch_assoc();
      $anhbia = $anhbia['anhbia'];

      // thực hiện xóa trong database
      $sql = "DELETE FROM dausach WHERE madausach=?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("s", $madausach);
      // xóa trong database và xóa ảnh bìa trong server gộp chung luôn
      if($stmt->execute() && unlink($anhbia)) {
        echo "<script>alert('Đã xóa thông tin đầu sách " . $madausach . "');</script>";
      }
      else {
        echo "<script>alert('Xóa thông tin đầu sách không thành công!');</script>";
      }

      // thực hiện xóa file ảnh bìa trên server
    }

    public function Sua($conn, $madausach, $tensach,
    $namxuatban, $dongia, $matacgia,
    $matheloai, $manxb, $mota, $anhbia) {
      $sql = "UPDATE dausach SET
      tensach=?,
      namxuatban=?,
      dongia=?,
      matacgia=?,
      matheloai=?,
      manxb=?,
      mota=?,
      anhbia=?
      WHERE madausach=?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("sssssssss", $tensach, $namxuatban, $dongia, $matacgia,
      $matheloai, $manxb, $mota, $anhbia, $madausach);

      if($stmt->execute()) {
        echo "<script>alert('Đã cập nhật thông tin đầu sách " . $madausach . "');</script>";
      }
      else echo "<script>alert('Cập nhật thông tin đầu sách không thành công!');</script>";
    }

    public function Sua_Khong_AnhBia($conn, $madausach, $tensach,
    $namxuatban, $dongia, $matacgia,
    $matheloai, $manxb, $mota) {
      $sql = "UPDATE dausach SET
      tensach=?,
      namxuatban=?,
      dongia=?,
      matacgia=?,
      matheloai=?,
      manxb=?,
      mota=?
      WHERE madausach=?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ssssssss", $tensach, $namxuatban, $donggia, $matacgia,
      $matheloai, $manxb, $mota, $madausach);

      if($stmt->execute()) {
        echo "<script>alert('Đã cập nhật thông tin đầu sách " . $madausach . "');</script>";
      }
      else echo "<script>alert('Cập nhật thông tin đầu sách không thành công!');</script>";

    }

    public function LayToanBoDanhSach($conn) {
      $danhsach = [];
      $sql = "SELECT * FROM dausach";
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $result = $stmt->get_result();
      while($row = $result->fetch_assoc()) {
        $item = new DauSach($row['madausach'],
        $row['tensach'], 
        $row['namxuatban'],
        $row['dongia'],
        $row['matacgia'],
        $row['matheloai'],
        $row['manxb'],
        $row['mota'],
        $row['anhbia']);
        $danhsach[] = $item;
      }
      return $danhsach;
    }

    public function getDauSach($conn, $madausach) {
      $sql = "SELECT * FROM dausach WHERE madausach=?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("s", $madausach);
      $stmt->execute();
      $result = $stmt->get_result();
      $result = $result->fetch_assoc();
      $dausach = new DauSach($result['madausach'],
      $result['tensach'],
      $result['namxuatban'],
      $result['dongia'],
      $result['matacgia'],
      $result['matheloai'],
      $result['manxb'],
      $result['mota'],
      $result['anhbia']);
      return $dausach;
    }

    public function TimKiem($conn, $keyword) {
      $danhsach = [];
      $sql = "SELECT * FROM dausach WHERE madausach LIKE ? OR tensach LIKE ?";
      $stmt = $conn->prepare($sql);
      $searchTerm = "%" . $keyword . "%";
      $stmt->bind_param("ss", $searchTerm, $searchTerm);
      $stmt->execute();
      $result = $stmt->get_result();
      while($row = $result->fetch_assoc()) {
        $item = new DauSach($row['madausach'],
        $row['tensach'], 
        $row['namxuatban'],
        $row['dongia'],
        $row['matacgia'],
        $row['matheloai'],
        $row['manxb'],
        $row['mota'],
        $row['anhbia']);
        $danhsach[] = $item;
      }
      return $danhsach;
    }
  }
?>