<?php
require_once __DIR__ . '/../model/DocGia.php';
require_once __DIR__ . '/../model/TaiKhoan.php';  // thêm dòng này

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../database/ConnectDB.php';
require_once __DIR__ . '/../DAO/DocGiaDAO.php';

header('Content-Type: application/json');

$dao = new DocGiaDAO();
$email    = trim($_POST['email']    ?? "");
$matkhau  = trim($_POST['matkhau']  ?? "");

if ($email === "" || $matkhau === "") {
    echo json_encode(["status" => "error", "message" => "Vui lòng nhập đầy đủ email và mật khẩu"]);
    exit;
}

$result = $dao->dangNhap($email, $matkhau);

if ($result) {
    $user     = $result['docgia'];
    $madocgia = $user->getMadocgia();

    //-----------------Phần Giỏ Hàng-----------------
    // ĐỒNG BỘ GIỎ HÀNG
    $db = ConnectDB::getInstance();
    $conn = $db->getConnection();

    $guest_cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

    // 1. Trộn giỏ hàng khách vào Database
    if (!empty($guest_cart)) {
        foreach ($guest_cart as $ma => $item) {
            // Lấy tồn kho thực tế (đếm số cuốn 'SanSang')
            $sql_stock = "SELECT (SELECT COUNT(*) FROM cuonsach cs WHERE cs.madausach = ds.madausach AND cs.trangthai = 'SanSang') as tonkho 
                          FROM dausach ds WHERE madausach = ?";
            $stmt_stock = $conn->prepare($sql_stock);
            $stmt_stock->bind_param("s", $ma);
            $stmt_stock->execute();
            $tonkho = $stmt_stock->get_result()->fetch_assoc()['tonkho'] ?? 0;

            if ($tonkho > 0) {
                // Check xem user đã có sách này trong DB chưa
                $sql_check = "SELECT soluong FROM giohang WHERE madocgia = ? AND madausach = ?";
                $stmt_check = $conn->prepare($sql_check);
                $stmt_check->bind_param("ss", $madocgia, $ma);
                $stmt_check->execute();
                $res_check = $stmt_check->get_result();

                if ($row = $res_check->fetch_assoc()) {
                    // CÓ RỒI -> CỘNG DỒN VÀ CHẶN TRẦN TỒN KHO
                    $new_qty = min($row['soluong'] + $item['soluong'], $tonkho);
                    $stmt_upd = $conn->prepare("UPDATE giohang SET soluong = ? WHERE madocgia = ? AND madausach = ?");
                    $stmt_upd->bind_param("iss", $new_qty, $madocgia, $ma);
                    $stmt_upd->execute();
                } else {
                    // CHƯA CÓ -> THÊM MỚI VÀ CHẶN TRẦN TỒN KHO
                    $new_qty = min($item['soluong'], $tonkho);
                    $stmt_ins = $conn->prepare("INSERT INTO giohang (madocgia, madausach, soluong) VALUES (?, ?, ?)");
                    $stmt_ins->bind_param("ssi", $madocgia, $ma, $new_qty);
                    $stmt_ins->execute();
                }
            }
        }
    }

    // 2. Xóa giỏ tạm, kéo bản chuẩn từ DB lên Session để đồng bộ 100%
    $_SESSION['cart'] = [];
    $sql_sync = "SELECT gh.madausach, gh.soluong, ds.tensach, ds.anhbia, ds.dongia,
                        (SELECT COUNT(*) FROM cuonsach cs WHERE cs.madausach = ds.madausach AND cs.trangthai = 'SanSang') as tonkho
                 FROM giohang gh
                 JOIN dausach ds ON gh.madausach = ds.madausach
                 WHERE gh.madocgia = ?";
    $stmt_sync = $conn->prepare($sql_sync);
    $stmt_sync->bind_param("s", $madocgia);
    $stmt_sync->execute();
    $res_sync = $stmt_sync->get_result();

    while ($row = $res_sync->fetch_assoc()) {
        if ($row['tonkho'] > 0) {
            // Ép số lượng xuống nếu kho lỡ bị hụt (ai đó vừa mượn mất)
            $final_qty = min($row['soluong'], $row['tonkho']);

            $_SESSION['cart'][$row['madausach']] = [
                'tensach' => $row['tensach'],
                'anhbia'  => $row['anhbia'],
                'dongia'  => $row['dongia'],
                'soluong' => $final_qty,
                'tonkho'  => $row['tonkho']
            ];
        } else {
            // Hết sách thì xóa khỏi giỏ luôn cho sạch
            $conn->query("DELETE FROM giohang WHERE madocgia = '$madocgia' AND madausach = '{$row['madausach']}'");
        }
    }

    //-----------------Phần Tài Khoản-----------------
    // Lưu session docgia
    $_SESSION['docgia'] = $user;

    // Lưu session taikhoan (hash lấy từ DB)
    $_SESSION['taikhoan'] = new TaiKhoan(
        $email,
        $result['matkhau'],   // hash từ DB
        $result['quyen'],
        null,                 // manv (null nếu là docgia)
        $madocgia
    );

    // Xử lý giỏ hàng
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        $dao->mergeCart($madocgia, $_SESSION['cart']);
    }
    $_SESSION['cart'] = $dao->getCartFromDB($madocgia);

    echo json_encode([
        "status"  => "success",
        "message" => "Chào mừng bạn " . $user->getTendocgia() . " đến với thư viện",
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "Sai email hoặc mật khẩu"]);
}
