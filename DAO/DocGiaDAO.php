<?php
require_once __DIR__ . '/../database/ConnectDB.php';
require_once __DIR__ . '/../model/DocGia.php';
class DocGiaDAO {
    private $conn;
    public function __construct() {
        $this->conn = ConnectDB::getInstance()->getConnection();
    }

    private function taikhoanHasTrangThai(): bool {
        $sql = "SELECT 1
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_SCHEMA = DATABASE()
                  AND TABLE_NAME = 'taikhoan'
                  AND COLUMN_NAME = 'trangthai'
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->execute();
        $ok = $stmt->get_result()->num_rows > 0;
        $stmt->close();
        return $ok;
    }


// =========================== DAO ADMIN =============================== // 
    public function Them($conn, $madocgia, $hodocgia, 
        $tendocgia, $email, $sdt, $diachi) {
        $sql = "INSERT INTO docgia(madocgia, hodocgia, tendocgia,
        email, sdt, diachi) VALUES(?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $madocgia, $hodocgia, $tendocgia,
        $email, $sdt, $diachi);

        // thực hiện thêm
        if($stmt->execute()) {
            echo "<script>alert('Đã thêm thông tin 
            đọc giả');</script>";
        }
        else echo "<script>alert('Thêm thông tin đọc giả 
        không thành công');</script>";
    }

    public function Sua($conn, $madocgia, $hodocgia, $tendocgia, $email, $sdt, $diachi) {
        $sql = "UPDATE docgia
        SET hodocgia=?, tendocgia=?,
        email=?, sdt=?, diachi=?
        WHERE madocgia=?";

        // thực hiện cập nhật
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $hodocgia, $tendocgia, $email, 
        $sdt, $diachi, $madocgia);

        // thông báo tính trạng cập nhật
        if($stmt->execute()) {
        echo '<script>alert("Đã cập nhật thông tin đọc giả!");</script>';
        }
        else echo '<script>alert("Cập nhật thông tin đọc giả không thành công!");</script>';
    }

    public function ToanBoDanhSach($conn) {
        $sql = "SELECT * FROM docgia WHERE (diachi IS NULL OR diachi <> 'Nhân viên') ORDER BY madocgia DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

    public function Xoa($conn, $madocgia) {
        $sql = "DELETE FROM docgia WHERE madocgia=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $madocgia);
        if ($stmt->execute()) {
            echo '<script>alert("Đã xóa đọc giả!");</script>';
            return true;
        } else {
            echo '<script>alert("Xóa đọc giả không thành công!");</script>';
            return false;
        }
    }



    public function TimKiem($conn, $keyword) {
        $sql = "SELECT * FROM docgia WHERE (diachi IS NULL OR diachi <> 'Nhân viên') AND (madocgia LIKE ? OR hodocgia LIKE ? OR tendocgia LIKE ?) ORDER BY madocgia DESC";
        $stmt = $conn->prepare($sql);
        $like = '%' . $keyword . '%';
        $stmt->bind_param('sss', $like, $like, $like);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }



// =========================== DAO USERS =============================== // 
    // ==================== SINH MÃ TỰ ĐỘNG ====================
   private function generateMa() {
        $sql  = "SELECT MAX(madocgia) as maxMa FROM docgia";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $row  = $stmt->get_result()->fetch_assoc();

        $soTiepTheo = isset($row['maxMa']) ? (int)substr($row['maxMa'], 2) + 1 : 1;
        return 'DG' . str_pad($soTiepTheo, 3, '0', STR_PAD_LEFT);
    }

    // ==================== ĐĂNG KÝ ====================
    public function dangKy(DocGia $dg, $matkhau) {
        $ho = trim($dg->getHodocgia());
        $ten = trim($dg->getTendocgia());
        $email = trim($dg->getEmail());
        $quyen = "DG";
        
        if ($this->emailTonTai($dg->getEmail())) {
            return ['success' => false, 'message' => 'Email đã được sử dụng!'];
        }
        if (strlen($matkhau) < 6) {
            return ['success' => false, 'message' => 'Mật khẩu phải có ít nhất 6 ký tự!'];
        }

        $ma = $this->generateMa();
        
        // BẮT ĐẦU TRANSACTION
        $this->conn->begin_transaction();
        try {
            // Insert vào bảng docgia
            $sql = "INSERT INTO docgia (madocgia, hodocgia, tendocgia, email)
                    VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Lỗi prepare docgia: " . $this->conn->error);
            }
            $stmt->bind_param("ssss", $ma, $ho, $ten, $email);
            if (!$stmt->execute()) {
                throw new Exception("Lỗi insert docgia: " . $stmt->error);
            }
            // Insert vào bảng taikhoan
            $sqlTaiKhoan = "INSERT INTO taikhoan (tendangnhap, matkhau, manhomquyen, madocgia)
                            VALUES (?, ?, ?, ?)";
            $stmtTaiKhoan = $this->conn->prepare($sqlTaiKhoan);
            if (!$stmtTaiKhoan) {
                throw new Exception("Lỗi prepare taikhoan: " . $this->conn->error);
            }
            $matkhauHash = password_hash($matkhau, PASSWORD_DEFAULT);
            $stmtTaiKhoan->bind_param("ssss", $email, $matkhauHash, $quyen, $ma);

            if (!$stmtTaiKhoan->execute()) {
                throw new Exception("Lỗi insert taikhoan: " . $stmtTaiKhoan->error);
            }
            // COMMIT nếu cả 2 insert thành công
            $this->conn->commit();
            $stmt->close();
            $stmtTaiKhoan->close();
            return ['success' => true, 'madocgia' => $ma];
        } catch (Exception $e) {
            // ROLLBACK nếu có lỗi
            $this->conn->rollback();
            return ['success' => false, 'message' => 'Đăng ký thất bại: ' . $e->getMessage()];
        }
    }
    // ==================== CẬP NHẬT THÔNG TIN ====================
    public function capNhatThongTin(DocGia $dg) {

        $hodocgia = $dg->getHodocgia();
        $tendocgia = $dg->getTendocgia();
        $sdt = $dg->getSdt();
        // $ngaysinh = $dg->getNgaysinh();
        $diachi = $dg->getDiachi();
        $ma = $dg->getMadocgia();

        $sql = "UPDATE docgia SET hodocgia=?, tendocgia=?, sdt=?, diachi=? WHERE madocgia=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssss",
            $hodocgia,
            $tendocgia,
            $sdt,
            // $ngaysinh,
            $diachi,
            $ma
        );
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Cập nhật thành công!'];
        }
        return ['success' => false, 'message' => 'Cập nhật thất bại!'];
    }

    // ==================== ĐĂNG NHẬP ====================
    public function dangNhap($email, $matkhau)
    {
        $hasTrangThai = $this->taikhoanHasTrangThai();
        $sql  = "SELECT tk.matkhau, tk.manhomquyen, tk.manv, tk.trangthai, dg.*
            FROM taikhoan tk
            JOIN docgia dg ON tk.madocgia = dg.madocgia
            WHERE tk.tendangnhap = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $row  = $stmt->get_result()->fetch_assoc();

        if ($row && password_verify($matkhau, $row['matkhau'])) {
            // Kiểm tra Auto-Lock/Unlock và cảnh báo trước
            if ($hasTrangThai) {
                $viPham = $this->kiemTraViPhamDocGia($email, $row['madocgia']);
                if ($viPham['locked']) {
                    return [
                        'locked'  => true,
                        'reasons' => $viPham['reasons']
                    ];
                }
            }

            // Nếu admin khóa thủ công trước đó (mà không do vi phạm tự động mở khóa) 
            // Ở đây vì không do vi phạm, $viPham['locked'] = false, nên sẽ tới đây.
            if ($hasTrangThai && isset($row['trangthai']) && (int)$row['trangthai'] === 0) {
                 return [
                    'locked'  => true,
                    'reasons' => ['Tài khoản đã bị Admin khóa tạm thời.']
                 ];
            }

            $docgia = new DocGia(
                $row['hodocgia'],
                $row['tendocgia'],
                $row['email'],
                $row['sdt'],
                // $row['ngaysinh'],
                $row['diachi'],
                $row['madocgia']
            );
            // Trả về cả docgia lẫn hash mật khẩu
            return [
                'docgia'   => $docgia,
                'matkhau'  => $row['matkhau'],      // hash từ DB
                'quyen'    => $row['manhomquyen'],
                'manv'     => $row['manv'] ?? null,
            ];
        }
        return null;
    }

    // ==================== LẤY THEO MÃ ====================
    public function getByMa($madocgia) {
        $sql  = "SELECT * FROM docgia WHERE madocgia = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $madocgia);
        $stmt->execute();
        $row  = $stmt->get_result()->fetch_assoc();

        if ($row) {
            return new DocGia(
                $row['hodocgia'],
                $row['tendocgia'],
                $row['email'],
                $row['sdt'],
                // $row['ngaysinh'],
                $row['diachi'],
                $row['madocgia']
            );
        }
        return null;
    }

    // wrapper tiếng Việt cho getByMa
    public function Lay1DocGia($madocgia) {
        return $this->getByMa($madocgia);
    }

    // ==================== HELPER ====================
    private function emailTonTai($email) {
        $sql  = "SELECT COUNT(*) as total FROM docgia WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $row  = $stmt->get_result()->fetch_assoc();
        return $row['total'] > 0;
    }

    public function mergeCart($madocgia, $sessionCart) {
    if (empty($sessionCart)) return;

    foreach ($sessionCart as $ma => $item) {
        $soluong = $item['soluong'];
        $sql = "INSERT INTO giohang (madocgia, madausach, soluong) 
                VALUES (?, ?, ?) 
                ON DUPLICATE KEY UPDATE soluong = soluong + ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssii", $madocgia, $ma, $soluong, $soluong);
        $stmt->execute();
    }
    }

    // LẤY GIỎ HÀNG TỪ DB
    public function getCartFromDB($madocgia) {
        $sql = "SELECT gh.madausach, gh.soluong, ds.tensach, ds.dongia, ds.anhbia 
                FROM giohang gh 
                JOIN dausach ds ON gh.madausach = ds.madausach 
                WHERE gh.madocgia = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $madocgia);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $cart = [];
        while ($row = $result->fetch_assoc()) {
            $cart[$row['madausach']] = [
                'tensach' => $row['tensach'],
                'dongia'  => $row['dongia'],
                'anhbia'  => $row['anhbia'],
                'soluong' => $row['soluong']
            ];
        }
        return $cart;
    }

    // Lưu ý: việc kiểm tra mật khẩu cũ đã được xử lý bên changePassAjax.php
    // Method này chỉ đơn giản UPDATE mật khẩu mới vào DB
    public function doiMatKhau(TaiKhoan $taikhoan, string $newPassword): array
    {
        $passwordToSave = password_hash($newPassword, PASSWORD_DEFAULT);
        $username = $taikhoan->getTendangnhap();   // tách ra biến
        $sql = "UPDATE taikhoan SET matkhau = ? WHERE tendangnhap = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $passwordToSave, $username);
        if ($stmt->execute()) {
            return ["success" => true, "message" => "Đổi mật khẩu thành công"];
        } else {
            return ["success" => false, "message" => "Lỗi khi cập nhật mật khẩu"];
        }
    }
    // ==================== AUTO CHECK VI PHẠM MƯỢN TRẢ ====================
    public function kiemTraViPhamDocGia($tendangnhap, $madocgia) {
        $sqlMuon = "SELECT MAX(DATEDIFF(NOW(), ngayhethan)) as tre_muon 
                    FROM phieumuon 
                    WHERE madocgia = ? AND trangthai IN ('DangMuon', 'Đang mượn') 
                    AND DATEDIFF(NOW(), ngayhethan) > 0";
        $stmtM = $this->conn->prepare($sqlMuon);
        $stmtM->bind_param("s", $madocgia);
        $stmtM->execute();
        $treMuon = (int)($stmtM->get_result()->fetch_assoc()['tre_muon'] ?? 0);
        $stmtM->close();

        $sqlPhat = "SELECT MAX(DATEDIFF(NOW(), ngaylap)) as tre_phat 
                    FROM phieuphat 
                    WHERE madocgia = ? AND trangthai IN ('ChuaDong', 'Chưa đóng', 'chuadong', 'ChuaNop', 'Chưa nộp', 'chuanop') 
                    AND DATEDIFF(NOW(), ngaylap) > 0";
        $stmtP = $this->conn->prepare($sqlPhat);
        $stmtP->bind_param("s", $madocgia);
        $stmtP->execute();
        $trePhat = (int)($stmtP->get_result()->fetch_assoc()['tre_phat'] ?? 0);
        $stmtP->close();

        $warnings = [];
        $isLocked = false;
        $lockReasons = [];

        if ($treMuon > 2) {
            $isLocked = true;
            $lockReasons[] = "sách trễ hạn trả hơn 2 ngày (chậm $treMuon ngày)";
        } elseif ($treMuon > 0) {
            $warnings[] = "Bạn có sách đã trễ hạn trả $treMuon ngày. Phí phạt là 5.000đ/cuốn cho mỗi ngày trễ. Tài khoản sẽ bị khóa nếu trễ hơn 2 ngày!";
        }

        if ($trePhat > 7) {
            $isLocked = true;
            $lockReasons[] = "phiếu phạt nợ chưa đóng quá 7 ngày (chậm $trePhat ngày)";
        } elseif ($trePhat > 0) {
            $warnings[] = "Bạn có tiền phạt bảo lãnh chưa đóng ($trePhat ngày). Tài khoản sẽ bị khóa nếu trễ hạn quá 7 ngày đóng phạt.";
        }

        $hasTrangThai = $this->taikhoanHasTrangThai();
        $currentStatus = 1;

        if ($hasTrangThai) {
            $sqlStatus = "SELECT trangthai FROM taikhoan WHERE tendangnhap = ?";
            $stmtS = $this->conn->prepare($sqlStatus);
            if ($stmtS) {
                $stmtS->bind_param("s", $tendangnhap);
                $stmtS->execute();
                $rowS = $stmtS->get_result()->fetch_assoc();
                $currentStatus = $rowS ? (int)$rowS['trangthai'] : 1;
                $stmtS->close();
            }
        }

        if ($isLocked) {
            if ($hasTrangThai && $currentStatus !== 0) {
                // Nếu chưa khóa thì Khóa tài khoản
                $sqlUpdate = "UPDATE taikhoan SET trangthai = 0 WHERE tendangnhap = ?";
                $stmtU = $this->conn->prepare($sqlUpdate);
                if ($stmtU) {
                    $stmtU->bind_param("s", $tendangnhap);
                    $stmtU->execute();
                    $stmtU->close();
                }
            }
            return ['locked' => true, 'reasons' => $lockReasons, 'warnings' => []];
        } else {
            // Tự động mở khóa nếu trước đó bị khóa (mà ko còn vi phạm nào gây locked)
            if ($hasTrangThai && $currentStatus === 0) {
                $sqlUpdate = "UPDATE taikhoan SET trangthai = 1 WHERE tendangnhap = ?";
                $stmtU = $this->conn->prepare($sqlUpdate);
                if ($stmtU) {
                    $stmtU->bind_param("s", $tendangnhap);
                    $stmtU->execute();
                    $stmtU->close();
                }
            }
            return ['locked' => false, 'reasons' => [], 'warnings' => $warnings];
        }
    }
}
?>