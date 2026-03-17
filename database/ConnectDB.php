<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_quanlythuvien";

// 1. Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// 2. Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
else echo "Kết nối thành công!";

// 3. Thiết lập font chữ UTF-8 để tránh lỗi tiếng Việt
$conn->set_charset("utf8");

// ... Thực hiện các câu lệnh SQL tại đây ...

// 4. Đóng kết nối (không bắt buộc nhưng nên làm)
// $conn->close();
?>