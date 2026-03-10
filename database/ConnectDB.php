<?php
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "db_quanlythuvien";

  // tạo kết nối
  $conn = new mysqli($servername, $username, $password, $dbname);
  // kiểm tra kết nối
  if($conn->connect_error) {
    die("Kết nối thất bại" . $conn->connect_error);
  }
  else {
    echo "Kết nối thành công";
  }
?>