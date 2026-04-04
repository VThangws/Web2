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

// Thiết lập múi giờ chuẩn Việt Nam
date_default_timezone_set('Asia/Ho_Chi_Minh');
$conn->query("SET time_zone = '+07:00'");
$conn->set_charset('utf8mb4');
?>