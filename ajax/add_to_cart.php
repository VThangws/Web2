<?php
require_once __DIR__ . '/../model/DocGia.php';
session_start();
// Gọi kết nối Database
require_once __DIR__ . '/../database/ConnectDB.php';

// Kiểm tra xem có nhận được mã sách gửi lên không
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['madausach'])) {
    $madausach = $_POST['madausach'];

    // Nếu giỏ hàng chưa tồn tại, tạo mới một cái giỏ rỗng
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $db = ConnectDB::getInstance();
    $conn = $db->getConnection();

    // 1. CỘNG DỒN VÀO RAM
    if (isset($_SESSION['cart'][$madausach])) {
        if ($_SESSION['cart'][$madausach]['soluong'] < $_SESSION['cart'][$madausach]['tonkho']) {
            $_SESSION['cart'][$madausach]['soluong'] += 1;
        } else {
            // Chặn đứng lại, báo lỗi hết quota
            echo json_encode([
                'status' => 'error', 
                'message' => 'Sách này trong kho chỉ còn ' . $_SESSION['cart'][$madausach]['tonkho'] . ' cuốn, không thể mượn thêm!'
            ]);
            exit;
        }
    }
    // Nếu CHƯA CÓ, vào kho (Database) lấy thông tin sách ra thêm vào giỏ
    else {
        $sql = "SELECT ds.tensach, ds.anhbia, ds.dongia, 
                           (SELECT COUNT(*) 
                            FROM cuonsach cs 
                            WHERE cs.madausach = ds.madausach AND cs.trangthai = 'SanSang') as tonkho 
                    FROM dausach ds 
                    WHERE ds.madausach = ?";
            
            $stmt = $conn->prepare($sql);
            
            // THÊM ĐOẠN NÀY ĐỂ BẮT LỖI MYSQL:
            if (!$stmt) {
                echo json_encode([
                    'status' => 'error', 
                    'message' => 'Lỗi SQL: ' . $conn->error
                ]);
                exit;
            }
        $stmt->bind_param("s", $madausach);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            // Check trường hợp hiếm: Mới bấm vào đã thấy kho bằng 0
            if ($row['tonkho'] < 1) {
                echo json_encode(['status' => 'error', 'message' => 'Sách này hiện đã hết!']);
                exit;
            }

            // Bỏ thông tin sách + TỒN KHO vào "Balo"
            $_SESSION['cart'][$madausach] = [
                'tensach' => $row['tensach'],
                'anhbia' => $row['anhbia'],
                'dongia' => $row['dongia'],
                'soluong' => 1,
                'tonkho' => $row['tonkho'] // LƯU LẠI TỒN KHO VÀO ĐÂY NÈ
            ];
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Sách không tồn tại!']);
            exit;
        }
    }


    // 2. LƯU XUỐNG DATABASE (CHỈ KHI LOGIN)
    if (isset($_SESSION['docgia'])) {
        $docgia = $_SESSION['docgia'];
        $madocgia = $docgia->getMadocgia();

        // Lệnh SQL ma thuật: Chưa có sách trong DB thì Thêm mới 1 cuốn. Có rồi thì Tự cộng dồn 1.
        $sql_db = "INSERT INTO giohang (madocgia, madausach, soluong) 
                   VALUES (?, ?, 1) 
                   ON DUPLICATE KEY UPDATE soluong = soluong + 1";
        $stmt_db = $conn->prepare($sql_db);
        $stmt_db->bind_param("ss", $madocgia, $madausach);
        $stmt_db->execute();
    }

    // 3. ĐẾM SỐ ĐẦU SÁCH ĐỂ HIỆN THỊ LÊN GIỎ HÀNG
    $total_items = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'soluong')) : 0;

    // Trả kết quả về cho giao diện
    echo json_encode([
        'status' => 'success', 
        'message' => 'Đã thêm thành công vào giỏ!',
        'total_items' => $total_items
    ]);
    exit;
}
?>