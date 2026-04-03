<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../model/DocGia.php';
require_once __DIR__ . '/../database/ConnectDB.php';

// 1. Lấy thông tin độc giả
$docgia = $_SESSION['docgia'] ?? null;
if (!$docgia) {
    echo "<script>alert('Phiên đăng nhập đã hết hạn!'); window.location.href='/index.php?page=login';</script>";
    exit;
}
$user_id = $docgia->getMadocgia(); 
$user_name = $docgia->getHodocgia() . ' ' . $docgia->getTendocgia();

// =========================================================================
// PHẦN A: XỬ LÝ KHI NGƯỜI DÙNG BẤM NÚT "XÁC NHẬN LẬP PHIẾU"
// =========================================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_confirm'])) {
    $type = $_POST['type'] ?? 'ONLINE';
    $pickup_date = $_POST['pickup_date'] ?? null;
    $checkout_items = $_POST['checkout_items'] ?? [];
    $cart = $_SESSION['cart'] ?? [];

    $selected_cart = [];
    foreach ($checkout_items as $ma) {
        if (isset($cart[$ma])) {
            $selected_cart[$ma] = $cart[$ma];
        }
    }

    if (empty($selected_cart)) {
        echo "<script>alert('Lỗi: Giỏ hàng trống!'); window.location.href='/index.php?page=cart';</script>";
        exit;
    }

    try {
        $conn = ConnectDB::getInstance()->getConnection();
        $conn->begin_transaction();

        // 1. Tùy chỉnh dữ liệu cho khớp với Database
        if ($type === 'ONLINE') {
            $trangthai = 'ChoDuyet'; // Giống trạng thái trong ảnh
            $ngayhethan = date('Y-m-d H:i:s', strtotime($pickup_date . ' + 14 days'));
            $ghichu = 'Mượn mang về (Ngày hẹn lấy: ' . $pickup_date . ')';
        } else {
            $trangthai = 'DangMuon'; // Giống trạng thái trong ảnh
            $ngayhethan = date('Y-m-d 23:59:59'); // Cuối ngày hôm nay
            $ghichu = 'Đọc tại chỗ';
        }

        // TỰ ĐỘNG TẠO MÃ PHIẾU MƯỢN MỚI (Dạng PMxxxx)
        $queryMax = "SELECT mamuon FROM phieumuon ORDER BY mamuon DESC LIMIT 1";
        $resultMax = $conn->query($queryMax);
        $newMaMuon = 'PM0001'; // Giá trị mặc định nếu bảng đang trống
        
        if ($resultMax && $resultMax->num_rows > 0) {
            $rowMax = $resultMax->fetch_assoc();
            $lastMaMuon = $rowMax['mamuon']; // Ví dụ: PM0020
            
            // Tách lấy phần số và cộng thêm 1
            if (preg_match('/^PM(\d+)$/', $lastMaMuon, $matches)) {
                $number = intval($matches[1]) + 1;
                $newMaMuon = 'PM' . str_pad($number, 4, '0', STR_PAD_LEFT);
            }
        }

        // 2. Insert vào bảng phieumuon (THÊM cột mamuon vào câu lệnh)
        $stmt = $conn->prepare("INSERT INTO phieumuon (mamuon, madocgia, ngaymuon, ngayhethan, trangthai, ghichu) VALUES (?, ?, NOW(), ?, ?, ?)");
        
        // Có 5 biến (chuỗi) cần truyền vào nên dùng "sssss"
        $stmt->bind_param("sssss", $newMaMuon, $user_id, $ngayhethan, $trangthai, $ghichu);
        $stmt->execute();
        
        // Gán mã phiếu vừa tạo để đưa qua trang Mượn Thành Công
        $mamuon = $newMaMuon;

        // Trừ kho và xóa khỏi giỏ
        foreach ($selected_cart as $ma => $item) {
            $checkInv = $conn->prepare("SELECT COUNT(*) as stock FROM cuonsach WHERE madausach = ? AND trangthai = 'SanSang'");
            $checkInv->bind_param("i", $ma);
            $checkInv->execute();
            $stock = $checkInv->get_result()->fetch_assoc()['stock'];

            if ($stock < $item['soluong']) {
                throw new Exception("Sách '{$item['tensach']}' đã hết!");
            }

            $getIds = $conn->prepare("SELECT macuonsach FROM cuonsach WHERE madausach = ? AND trangthai = 'SanSang' LIMIT ?");
            $getIds->bind_param("ii", $ma, $item['soluong']);
            $getIds->execute();
            $ids = $getIds->get_result();

            while ($book = $ids->fetch_assoc()) {
                $bookId = $book['macuonsach'];
                // Dùng prepare statement để an toàn tuyệt đối với mọi loại chuỗi
                $updateStmt = $conn->prepare("UPDATE cuonsach SET trangthai = 'DaMuon' WHERE macuonsach = ?");
                $updateStmt->bind_param("s", $bookId); // "s" là string vì mã của ní có chữ CS
                $updateStmt->execute();
            }
            unset($_SESSION['cart'][$ma]); // Xóa sách đã mượn
        }

        $conn->commit();
        unset($_SESSION['checkout_items']);
        
        // CHUYỂN QUA TRANG THÀNH CÔNG
        echo "<script>window.location.href='/index.php?page=borrow_success&id=$mamuon';</script>";
        exit;

    } catch (Exception $e) {
        $conn->rollback();
        echo "<script>alert('Lỗi Database: " . addslashes($e->getMessage()) . "'); window.location.href='/index.php?page=cart';</script>";
        exit;
    }
}

// =========================================================================
// PHẦN B: CODE HIỂN THỊ TRANG THÔNG TIN BÌNH THƯỜNG
// =========================================================================
$borrow_type = $_POST['type'] ?? 'ONLINE';
$pickup_date = $_POST['pickup_date'] ?? null;

$cart = $_SESSION['cart'] ?? [];
$checkout_items = $_SESSION['checkout_items'] ?? [];

$selected_cart = [];
$total_price = 0;
foreach ($checkout_items as $ma) {
    if (isset($cart[$ma])) {
        $selected_cart[$ma] = $cart[$ma];
        $total_price += ($cart[$ma]['dongia'] * $cart[$ma]['soluong']);
    }
}

$due_date = ($borrow_type === 'ONLINE') ? date('d/m/Y', strtotime($pickup_date . ' + 14 days')) : 'Cuối ngày hôm nay';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm benefit-card p-4">
                <h3 class="fw-bold text-center mb-4" style="color: #20c997;">THÔNG TIN PHIẾU MƯỢN</h3>
                
                <form action="" method="POST">
                    
                    <input type="hidden" name="action_confirm" value="1">
                    
                    <input type="hidden" name="type" value="<?= $borrow_type ?>">
                    <input type="hidden" name="pickup_date" value="<?= $pickup_date ?>">
                    
                    <?php foreach ($checkout_items as $ma): ?>
                        <input type="hidden" name="checkout_items[]" value="<?= $ma ?>">
                    <?php endforeach; ?>

                    <div class="row g-4">
                        <div class="col-md-5 border-end">
                            <h5 class="fw-bold mb-3 border-bottom pb-2"><i class="fa-solid fa-user-tag me-2"></i>Người mượn</h5>
                            <div class="mb-3">
                                <label class="small text-muted">Họ và tên:</label>
                                <p class="fw-bold mb-0"><?= htmlspecialchars($user_name) ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="small text-muted">Mã độc giả:</label>
                                <p class="fw-bold mb-0">DG-<?= str_pad($user_id, 5, '0', STR_PAD_LEFT) ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="small text-muted">Loại hình mượn:</label>
                                <p class="mb-0">
                                    <span class="badge <?= $borrow_type === 'ONLINE' ? 'bg-primary' : 'bg-success' ?>">
                                        <?= $borrow_type === 'ONLINE' ? 'Mang về' : 'Đọc tại chỗ' ?>
                                    </span>
                                </p>
                            </div>
                            <div class="bg-light p-3 rounded-3 mt-3">
                                <label class="small text-muted d-block">Dự kiến trả sách:</label>
                                <strong class="text-danger"><?= $due_date ?></strong>
                            </div>
                        </div>

                        <div class="col-md-7">
                            <h5 class="fw-bold mb-3 border-bottom pb-2"><i class="fa-solid fa-book-bookmark me-2"></i>Sách mượn</h5>
                            <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                                <table class="table table-borderless align-middle">
                                    <tbody>
                                        <?php foreach ($selected_cart as $ma => $item): ?>
                                        <tr>
                                            <td width="60">
                                                <img src="/assets/img/books/<?= $item['anhbia'] ?>" class="rounded" width="50" alt="Bìa">
                                            </td>
                                            <td>
                                                <div class="fw-bold small"><?= $item['tensach'] ?></div>
                                                <div class="text-muted" style="font-size: 0.75rem;">Số lượng: <?= $item['soluong'] ?></div>
                                            </td>
                                            <td class="text-end fw-bold text-danger">
                                                <?= number_format($item['dongia'] * $item['soluong'], 0, ',', '.') ?> đ
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <hr>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="fw-bold">Tiền cọc ước tính:</span>
                                <h4 class="fw-bold text-danger mb-0"><?= number_format($total_price ?? 0, 0, ',', '.') ?> VNĐ</h4>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-5">
                        <button type="submit" class="btn btn-lg btn-success px-5 rounded-pill fw-bold shadow">
                            XÁC NHẬN LẬP PHIẾU
                        </button>
                        <p class="small text-muted mt-2">Bằng cách nhấn xác nhận, bạn đồng ý với nội quy mượn trả của ASAG Library.</p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>