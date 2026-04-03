<?php
require_once __DIR__ . '/../model/DocGia.php';
require_once __DIR__ . '/../model/TaiKhoan.php';

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

    // Lưu thông tin đăng nhập vào Session trước
    $_SESSION['docgia'] = $user;
    $_SESSION['taikhoan'] = new TaiKhoan(
        $email,
        $result['matkhau'], 
        $result['quyen'],
        null,
        $madocgia
    );

    // XỬ LÝ GIỎ HÀNG: ĐỒNG BỘ & CHẶN QUOTA
    $db = ConnectDB::getInstance();
    $conn = $db->getConnection();

    // 1. Lấy giỏ hàng khách đang chọn (nếu có)
    $guest_cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

    if (!empty($guest_cart)) {
        foreach ($guest_cart as $ma => $item) {
            // Lấy tồn kho thực tế ngay lúc này
            $sql_stock = "SELECT (SELECT COUNT(*) FROM cuonsach cs WHERE cs.madausach = ds.madausach AND cs.trangthai = 'SanSang') as tonkho 
                          FROM dausach ds WHERE madausach = ?";
            $stmt_stock = $conn->prepare($sql_stock);
            $stmt_stock->bind_param("s", $ma);
            $stmt_stock->execute();
            $tonkho = $stmt_stock->get_result()->fetch_assoc()['tonkho'] ?? 0;

            if ($tonkho > 0) {
                // Kiểm tra xem trong DB của user này đã có cuốn này chưa
                $sql_check = "SELECT soluong FROM giohang WHERE madocgia = ? AND madausach = ?";
                $stmt_check = $conn->prepare($sql_check);
                $stmt_check->bind_param("ss", $madocgia, $ma);
                $stmt_check->execute();
                $res_check = $stmt_check->get_result();

                if ($row = $res_check->fetch_assoc()) {
                    // CÓ RỒI -> CỘNG DỒN VÀ ÉP KHÔNG VƯỢT QUÁ TỒN KHO
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

    // 2. LẤY BẢN CHUẨN TỪ DB LÊN SESSION (Đảm bảo có biến 'tonkho')
    $_SESSION['cart'] = []; // Xóa giỏ tạm
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
            // Ép lại số lượng lần cuối đề phòng có người vừa mượn mất sách
            $final_qty = min($row['soluong'], $row['tonkho']);

            $_SESSION['cart'][$row['madausach']] = [
                'tensach' => $row['tensach'],
                'anhbia'  => $row['anhbia'],
                'dongia'  => $row['dongia'],
                'soluong' => $final_qty,
                'tonkho'  => $row['tonkho'] // QUAN TRỌNG: Lưu lại tồn kho để cart.php hiển thị
            ];
        } else {
            // Kho hết sạch thì dọn rác luôn
            $conn->query("DELETE FROM giohang WHERE madocgia = '$madocgia' AND madausach = '{$row['madausach']}'");
        }
    }

    //-----------------Phần Tài Khoản-----------------
    // Lưu session docgia
    $_SESSION['docgia'] = $user;

    // Lưu session taikhoan (hash lấy từ DB)
    // Nếu là tài khoản nhân viên/admin đã link qua docgia thì sẽ có manv
    $_SESSION['taikhoan'] = new TaiKhoan(
        $email,
        $result['matkhau'],   // hash từ DB
        $result['quyen'],
        $result['manv'] ?? null,
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