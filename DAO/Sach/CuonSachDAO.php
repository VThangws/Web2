<?php
  class CuonSachDAO {
    public function Them($conn, $macuonsach,
    $madausach, $mavitri,
    $trangthai, $tinhtrang) {
      $sql = "INSERT INTO cuonsach(macuonsach, madausach, mavitri,
      trangthai, tinhtrang) VALUES(?, ?, ?, ?, ?)";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("sssss", $macuonsach, $madausach,
      $mavitri, $trangthai, $tinhtrang);
      if($stmt->execute()) {
        echo "<script>alert('Đã thêm thông tin cuốn sách ". $macuonsach . "');</script>";
      }
      else {
        echo "<script>alert('Thêm thông tin cuốn sách không thành công!');</script>";
      }
    }

    public function Xoa($conn, $macuonsach) {
      $sql = "DELETE FROM cuonsach WHERE macuonsach=?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("s", $macuonsach);
      if($stmt->execute()) {
        echo "<script>alert('Đã xóa thông tin cuốn sách ".$macuonsach."');</script>";
      }
      else echo "<script>alert('Xóa thông tin cuốn sách không thành công!');</script>";
    }

    public function Sua($conn, $macuonsach,
    $madausach, $mavitri,
    $trangthai, $tinhtrang) {
      $sql = "UPDATE cuonsach SET
      madausach=?,
      mavitri=?,
      trangthai=?,
      tinhtrang=?
      WHERE macuonsach=?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("sssss", $madausach, $mavitri, $trangthai, $tinhtrang, $macuonsach);
      if($stmt->execute()) {
        echo "<script>alert('Đã cập nhật thông tin cuốn sách ".$macuonsach."');</script>";
      }
      else echo "<script>alert('Cập nhật thông tin cuốn sách không thành công!');</script>";
    }

    public function TimKiem($conn, $keyword) {
      $danhsach = [];
      $sql = "SELECT * FROM cuonsach WHERE macuonsach LIKE ? OR madausach LIKE ?";
      $stmt = $conn->prepare($sql);
      $searchTerm = "%" . $keyword . "%";
      $stmt->bind_param("ss", $searchTerm, $searchTerm);
      $stmt->execute();
      $result = $stmt->get_result();
      while($row = $result->fetch_assoc()) {
        $cuonsach = new CuonSach($row['macuonsach'],
        $row['madausach'], $row['mavitri'], $row['trangthai'],
        $row['tinhtrang']);
        $danhsach[] = $cuonsach;
      }
      return $danhsach;
    }

    public function LayToanBoDanhSach($conn) {
      $danhsach = [];
      $sql = "SELECT * FROM cuonsach";
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $result = $stmt->get_result();
      while($row = $result->fetch_assoc()) {
        $cuonsach = new CuonSach($row['macuonsach'],
        $row['madausach'], $row['mavitri'], $row['trangthai'],
        $row['tinhtrang']);

        $danhsach[] = $cuonsach;
      }

      return $danhsach;
    }

    public function Lay1CuonSach($conn, $macuonsach) {
      $sql = "SELECT * FROM cuonsach WHERE macuonsach=?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("s", $macuonsach);
      $stmt->execute();
      $result = $stmt->get_result();
      $result = $result->fetch_assoc();
      $cuonsach = new CuonSach($result['macuonsach'], $result['madausach'],
      $result['mavitri'], $result['trangthai'], $result['tinhtrang']);
      return $cuonsach;
    }
  }
?>