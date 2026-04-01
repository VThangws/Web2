<?php
$servername = "localhost";
$username = "root"; // Mặc định của XAMPP là root
$password = "";     // Mặc định của XAMPP là để trống
$dbname = "db_quanlythuvien";

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
  die("Kết nối thất bại: " . $conn->connect_error);
}
// echo "Kết nối thành công!";
?>