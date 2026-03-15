<?php
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
  }
?>