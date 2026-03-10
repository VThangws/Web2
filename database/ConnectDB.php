<?php
$servername = "localhost"; 
$username = "root";        // XAMPP mặc định luôn là root
$password = "";            // XAMPP mặc định mật khẩu để trống
$dbname = "db_quanlythuvien"; 

try {
    // Tạo chuỗi kết nối PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    // Cấu hình để PDO báo lỗi dạng Exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Cấu hình fetch data mặc định thành dạng mảng kết hợp (associative array) cho dễ code
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Nếu chỉ test kết nối thì để dòng echo này, khi code thật thì nên xóa/comment lại
    // echo "Kết nối XAMPP thành công!"; 
} catch(PDOException $e) {
    die("Kết nối DB thất bại: " . $e->getMessage());
}
?>