<?php
require_once __DIR__. '/../model/TaiKhoan.php';
require_once __DIR__. '/../database/ConnectDB.php';
require_once __DIR__. '/../model/DocGia.php';

class DocGiaDAO {
    private $conn;

    public function __construct() {
        $this->conn = ConnectDB::getInstance()->getConnection();
    }

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
        if ($this->emailTonTai($dg->getEmail())) {
            return ['success' => false, 'message' => 'Email đã được sử dụng!'];
        }

        $ho = $dg->getHodocgia();
        $ten = $dg->getTendocgia();
        $email = $dg->getEmail();
        $quyen = "DG"; // Mã nhóm quyền cho Độc giả

        $ma  = $this->generateMa();
        $sql = "INSERT INTO docgia (madocgia, hodocgia, tendocgia, email)
                VALUES (?, ?, ?, ?)";
        $sqlTaiKhoan = "INSERT INTO taikhoan (tendangnhap, matkhau, manhomquyen, madocgia)
                        VALUES (?, ?, ?, ?)";     
        $stmt = $this->conn->prepare($sql);
        $stmtTaiKhoan = $this->conn->prepare($sqlTaiKhoan);
        $matkhauHash = password_hash($matkhau, PASSWORD_DEFAULT);
        $stmt->bind_param("ssss",
            $ma,
            $ho,
            $ten,
            $email
        );

        $stmtTaiKhoan->bind_param("ssss",
            $email,
            $matkhauHash,
            $quyen,
            $ma
        );

        if ($stmt->execute()) {
            $stmtTaiKhoan->execute();
            return ['success' => true, 'madocgia' => $ma];
        }
        return ['success' => false, 'message' => 'Đăng ký thất bại!'];
    }

    // ==================== CẬP NHẬT THÔNG TIN ====================
    public function capNhatThongTin(DocGia $dg) {

        $sdt = $dg->getSdt();
        $ngaysinh = $dg->getNgaysinh();
        $diachi = $dg->getDiachi();
        $ma = $dg->getMadocgia();

        $sql  = "UPDATE docgia SET sdt=?, ngaysinh=?, diachi=? WHERE madocgia=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sss",
            $sdt,
            $ngaysinh,
            $diachi,
        );

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Cập nhật thành công!'];
        }
        return ['success' => false, 'message' => 'Cập nhật thất bại!'];
    }

    // ==================== ĐĂNG NHẬP ====================
    public function dangNhap($email, $matkhau) {
        $sql  = "SELECT tk.matkhau, dg.*
                FROM taikhoan tk
                JOIN docgia dg ON tk.madocgia = dg.madocgia
                WHERE tk.tendangnhap = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $row  = $stmt->get_result()->fetch_assoc();

        if ($row && password_verify($matkhau, $row['matkhau'])) {
            return new DocGia(
                $row['hodocgia'],
                $row['tendocgia'],
                $row['email'],
                $row['sdt'],
                $row['ngaysinh'],
                $row['diachi'],
                $row['madocgia']
            );
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
                $row['ngaysinh'],
                $row['diachi'],
                $row['madocgia']
            );
        }
        return null;
    }

    // ==================== LẤY TOÀN BỘ ====================
    public function getAll() {
        $sql  = "SELECT * FROM docgia";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // ==================== XÓA ====================
    public function xoa($madocgia) {
        $sql  = "DELETE FROM docgia WHERE madocgia = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $madocgia);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Xóa thành công!'];
        }
        return ['success' => false, 'message' => 'Xóa thất bại!'];
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
}
?>