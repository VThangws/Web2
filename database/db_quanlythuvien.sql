-- Vô hiệu hóa kiểm tra khóa ngoại tạm thời để tránh lỗi thứ tự tạo bảng
SET FOREIGN_KEY_CHECKS = 0;

-- ==========================================
-- 1. NHÓM QUẢN LÝ ĐẦU SÁCH & CUỐN SÁCH
-- ==========================================

-- Lưu thông tin chung của tác phẩm (Metadata)
CREATE TABLE DauSach (
    madausach VARCHAR(50) PRIMARY KEY,
    tensach VARCHAR(255),
    namxuatban INT,
    dongia DECIMAL(10,2), -- Giá bìa chung
    matacgia VARCHAR(50),
    matheloai VARCHAR(50),
    manxb VARCHAR(50),
    mota VARCHAR(255),
    anhbia VARCHAR(255)
);

-- Lưu từng cuốn sách vật lý nằm trên kệ (Physical Copy)
CREATE TABLE CuonSach (
    macuonsach VARCHAR(50) PRIMARY KEY, -- Barcode
    madausach VARCHAR(50),
    mavitri VARCHAR(50), -- FK -> ViTri
    trangthai VARCHAR(50), -- SanSang, DangMuon, Hong, Mat, ThanhLy
    tinhtrang VARCHAR(50) -- Moi, Tot, Cu, Rac, HuHong
);

CREATE TABLE ViTri (
    mavitri VARCHAR(50) PRIMARY KEY,
    khuvuc VARCHAR(100), -- VD: Tang 1, Tang 2
    ke VARCHAR(50), -- VD: Ke A, Ke B
    mota VARCHAR(255)
);

CREATE TABLE TacGia (
    matacgia VARCHAR(50) PRIMARY KEY,
    tentacgia VARCHAR(255)
);

CREATE TABLE TheLoai (
    matheloai VARCHAR(50) PRIMARY KEY,
    tentheloai VARCHAR(255)
);

CREATE TABLE NhaXuatBan (
    manxb VARCHAR(50) PRIMARY KEY,
    tennxb VARCHAR(255),
    diachi VARCHAR(255),
    sdt VARCHAR(20),
    email VARCHAR(100)
);

-- ==========================================
-- 2. NHÓM NHẬP HÀNG (Nhập theo Đầu sách)
-- ==========================================

CREATE TABLE NhaCungCap (
    mancc VARCHAR(50) PRIMARY KEY,
    tenncc VARCHAR(255),
    diachincc VARCHAR(255),
    sdt VARCHAR(20),
    email VARCHAR(100)
);

CREATE TABLE PhieuNhap (
    maphieunhap VARCHAR(50) PRIMARY KEY,
    thoigiantao DATETIME,
    tongtien DECIMAL(10,2),
    manv VARCHAR(50),
    mancc VARCHAR(50)
);

-- Khi nhập, ta nhập số lượng cho Đầu sách.
-- (Logic phần mềm sẽ tự sinh ra n dòng bên bảng CuonSach tương ứng)
CREATE TABLE CTPhieuNhap (
    maphieunhap VARCHAR(50),
    madausach VARCHAR(50),
    dongianhap DECIMAL(10,2),
    soluong INT,
    PRIMARY KEY (maphieunhap, madausach)
);

-- ==========================================
-- 3. NHÓM MƯỢN & TRẢ (Mượn đích danh Cuốn sách)
-- ==========================================

CREATE TABLE DocGia (
    madocgia VARCHAR(50) PRIMARY KEY,
    hodocgia VARCHAR(100),
    tendocgia VARCHAR(100),
    email VARCHAR(100),
    sdt VARCHAR(20),
    ngaysinh DATE,
    diachi VARCHAR(255)
);

CREATE TABLE PhieuMuon (
    mamuon VARCHAR(50) PRIMARY KEY,
    ngaymuon DATETIME,
    ngayhethan DATETIME,
    manv VARCHAR(50),
    madocgia VARCHAR(50),
    trangthai VARCHAR(50), -- DangMuon, DaTraXong, QuaHan
    ghichu VARCHAR(255)
);

-- Chi tiết mượn chỉ ra rõ là mượn cuốn nào (Barcode nào)
CREATE TABLE CTPhieuMuon (
    mamuon VARCHAR(50),
    macuonsach VARCHAR(50),
    tinhtrang_truoc VARCHAR(50), -- Ghi nhận tình trạng lúc đưa cho khách (Mới/Cũ)
    PRIMARY KEY (mamuon, macuonsach)
);

CREATE TABLE PhieuTra (
    matra VARCHAR(50) PRIMARY KEY,
    mamuon VARCHAR(50),
    ngaytra DATETIME,
    manv VARCHAR(50),
    tongtienphat DECIMAL(10,2)
);

CREATE TABLE CTPhieuTra (
    matra VARCHAR(50),
    macuonsach VARCHAR(50),
    maphat VARCHAR(50),
    tinhtrang_sau VARCHAR(50), -- Tình trạng khi trả về (Rách/Mất/Nguyên vẹn)
    tienphathuha DECIMAL(10,2),
    songayquahan INT,
    tienphatquahan DECIMAL(10,2),
    PRIMARY KEY (matra, macuonsach, maphat)
);

-- Phạt
CREATE TABLE PhieuPhat (
    maphat VARCHAR(50) PRIMARY KEY,
    madocgia VARCHAR(50),
    matra VARCHAR(50), -- gắn với phiếu trả
    ngaylap DATETIME,
    tongtienphat DECIMAL(10,2),
    trangthai VARCHAR(50), -- ChuaThu, DaThu
    ghichu VARCHAR(255)
);

CREATE TABLE CTPhieuPhat (
    maphat VARCHAR(50),
    macuonsach VARCHAR(50),
    lydo VARCHAR(255), -- QuaHan, HuHong, MatSach
    songayquahan INT,
    sotienphat DECIMAL(10,2),
    PRIMARY KEY (maphat, macuonsach)
);

-- ==========================================
-- 4. NHÓM HỆ THỐNG & PHÂN QUYỀN
-- ==========================================

CREATE TABLE NhanVien (
    manv VARCHAR(50) PRIMARY KEY,
    honv VARCHAR(100),
    tennv VARCHAR(100),
    gioitinh VARCHAR(10),
    sdt VARCHAR(20),
    ngaysinh DATE
);

CREATE TABLE TaiKhoan (
    tendangnhap VARCHAR(50) PRIMARY KEY,
    matkhau VARCHAR(255),
    manhomquyen VARCHAR(50),
    manv VARCHAR(50)
);

CREATE TABLE NhomQuyen (
    manhomquyen VARCHAR(50) PRIMARY KEY,
    tennhomquyen VARCHAR(100)
);

CREATE TABLE DanhMucChucNang (
    machucnang VARCHAR(50) PRIMARY KEY,
    tenchucnang VARCHAR(100)
);

CREATE TABLE CTQuyen (
    manhomquyen VARCHAR(50),
    machucnang VARCHAR(50),
    hanhdong VARCHAR(100),
    PRIMARY KEY (manhomquyen, machucnang)
);

-- ==========================================
-- THIẾT LẬP KHÓA NGOẠI (FOREIGN KEYS)
-- ==========================================

-- SÁCH
ALTER TABLE DauSach ADD CONSTRAINT fk_dausach_tacgia FOREIGN KEY (matacgia) REFERENCES TacGia(matacgia);
ALTER TABLE DauSach ADD CONSTRAINT fk_dausach_theloai FOREIGN KEY (matheloai) REFERENCES TheLoai(matheloai);
ALTER TABLE DauSach ADD CONSTRAINT fk_dausach_nxb FOREIGN KEY (manxb) REFERENCES NhaXuatBan(manxb);
ALTER TABLE CuonSach ADD CONSTRAINT fk_cuonsach_dausach FOREIGN KEY (madausach) REFERENCES DauSach(madausach);
ALTER TABLE CuonSach ADD CONSTRAINT fk_cuonsach_vitri FOREIGN KEY (mavitri) REFERENCES ViTri(mavitri);

-- NHẬP HÀNG
ALTER TABLE PhieuNhap ADD CONSTRAINT fk_phieunhap_nhacungcap FOREIGN KEY (mancc) REFERENCES NhaCungCap(mancc);
ALTER TABLE PhieuNhap ADD CONSTRAINT fk_phieunhap_nhanvien FOREIGN KEY (manv) REFERENCES NhanVien(manv);
ALTER TABLE CTPhieuNhap ADD CONSTRAINT fk_ctpn_phieunhap FOREIGN KEY (maphieunhap) REFERENCES PhieuNhap(maphieunhap);
ALTER TABLE CTPhieuNhap ADD CONSTRAINT fk_ctpn_dausach FOREIGN KEY (madausach) REFERENCES DauSach(madausach);

-- MƯỢN SÁCH
ALTER TABLE PhieuMuon ADD CONSTRAINT fk_phieumuon_docgia FOREIGN KEY (madocgia) REFERENCES DocGia(madocgia);
ALTER TABLE PhieuMuon ADD CONSTRAINT fk_phieumuon_nhanvien FOREIGN KEY (manv) REFERENCES NhanVien(manv);
ALTER TABLE CTPhieuMuon ADD CONSTRAINT fk_ctpm_phieumuon FOREIGN KEY (mamuon) REFERENCES PhieuMuon(mamuon);
ALTER TABLE CTPhieuMuon ADD CONSTRAINT fk_ctpm_cuonsach FOREIGN KEY (macuonsach) REFERENCES CuonSach(macuonsach);

-- TRẢ SÁCH
ALTER TABLE PhieuTra ADD CONSTRAINT fk_phieutra_phieumuon FOREIGN KEY (mamuon) REFERENCES PhieuMuon(mamuon);
ALTER TABLE PhieuTra ADD CONSTRAINT fk_phieutra_nhanvien FOREIGN KEY (manv) REFERENCES NhanVien(manv);
ALTER TABLE CTPhieuTra ADD CONSTRAINT fk_ctpt_phieutra FOREIGN KEY (matra) REFERENCES PhieuTra(matra);
ALTER TABLE CTPhieuTra ADD CONSTRAINT fk_ctpt_cuonsach FOREIGN KEY (macuonsach) REFERENCES CuonSach(macuonsach);

-- PHẠT
ALTER TABLE PhieuPhat ADD CONSTRAINT fk_phieuphat_docgia FOREIGN KEY (madocgia) REFERENCES DocGia(madocgia);
ALTER TABLE PhieuPhat ADD CONSTRAINT fk_phieuphat_phieutra FOREIGN KEY (matra) REFERENCES PhieuTra(matra);
ALTER TABLE CTPhieuPhat ADD CONSTRAINT fk_ctpp_phieuphat FOREIGN KEY (maphat) REFERENCES PhieuPhat(maphat);
ALTER TABLE CTPhieuPhat ADD CONSTRAINT fk_ctpp_cuonsach FOREIGN KEY (macuonsach) REFERENCES CuonSach(macuonsach);

-- HỆ THỐNG
ALTER TABLE TaiKhoan ADD CONSTRAINT fk_taikhoan_nhanvien FOREIGN KEY (manv) REFERENCES NhanVien(manv);
ALTER TABLE TaiKhoan ADD CONSTRAINT fk_taikhoan_nhomquyen FOREIGN KEY (manhomquyen) REFERENCES NhomQuyen(manhomquyen);
ALTER TABLE CTQuyen ADD CONSTRAINT fk_ctq_nhomquyen FOREIGN KEY (manhomquyen) REFERENCES NhomQuyen(manhomquyen);
ALTER TABLE CTQuyen ADD CONSTRAINT fk_ctq_chucnang FOREIGN KEY (machucnang) REFERENCES DanhMucChucNang(machucnang);

-- Bật lại kiểm tra khóa ngoại sau khi tạo xong
SET FOREIGN_KEY_CHECKS = 1;