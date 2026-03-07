CREATE DATABASE IF NOT EXISTS `db_quanlythuvien`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE `db_quanlythuvien`;
CREATE TABLE `DauSach` (
  `madausach` varchar(255) PRIMARY KEY,
  `tensach` varchar(255),
  `namxuatban` int,
  `dongia` decimal,
  `matacgia` varchar(255),
  `matheloai` varchar(255),
  `manxb` varchar(255),
  `mota` varchar(255),
  `anhbia` varchar(255)
);

CREATE TABLE `CuonSach` (
  `macuonsach` varchar(255) PRIMARY KEY,
  `madausach` varchar(255),
  `mavitri` varchar(255),
  `trangthai` varchar(255),
  `tinhtrang` varchar(255)
);

CREATE TABLE `ViTri` (
  `mavitri` varchar(255) PRIMARY KEY,
  `khuvuc` varchar(255),
  `ke` varchar(255),
  `mota` varchar(255)
);

CREATE TABLE `TacGia` (
  `matacgia` varchar(255) PRIMARY KEY,
  `tentacgia` varchar(255)
);

CREATE TABLE `TheLoai` (
  `matheloai` varchar(255) PRIMARY KEY,
  `tentheloai` varchar(255)
);

CREATE TABLE `NhaXuatBan` (
  `manxb` varchar(255) PRIMARY KEY,
  `tennxb` varchar(255),
  `diachi` varchar(255),
  `sdt` varchar(255),
  `email` varchar(255)
);

CREATE TABLE `NhaCungCap` (
  `mancc` varchar(255) PRIMARY KEY,
  `tenncc` varchar(255),
  `diachincc` varchar(255),
  `sdt` varchar(255),
  `email` varchar(255)
);

CREATE TABLE `PhieuNhap` (
  `maphieunhap` varchar(255) PRIMARY KEY,
  `thoigiantao` datetime,
  `tongtien` decimal,
  `manv` varchar(255),
  `mancc` varchar(255)
);

CREATE TABLE `CTPhieuNhap` (
  `maphieunhap` varchar(255),
  `madausach` varchar(255),
  `dongianhap` decimal,
  `soluong` int,
  PRIMARY KEY (`maphieunhap`, `madausach`)
);

CREATE TABLE `DocGia` (
  `madocgia` varchar(255) PRIMARY KEY,
  `hodocgia` varchar(255),
  `tendocgia` varchar(255),
  `email` varchar(255),
  `sdt` varchar(255),
  `ngaysinh` date,
  `diachi` varchar(255)
);

CREATE TABLE `PhieuMuon` (
  `mamuon` varchar(255) PRIMARY KEY,
  `ngaymuon` datetime,
  `ngayhethan` datetime,
  `manv` varchar(255),
  `madocgia` varchar(255),
  `trangthai` varchar(255),
  `ghichu` varchar(255)
);

CREATE TABLE `CTPhieuMuon` (
  `mamuon` varchar(255),
  `macuonsach` varchar(255),
  `tinhtrang_truoc` varchar(255),
  PRIMARY KEY (`mamuon`, `macuonsach`)
);

CREATE TABLE `PhieuTra` (
  `matra` varchar(255) PRIMARY KEY,
  `mamuon` varchar(255),
  `ngaytra` datetime,
  `manv` varchar(255),
  `tongtienphat` decimal
);

CREATE TABLE `CTPhieuTra` (
  `matra` varchar(255),
  `macuonsach` varchar(255),
  `maphat` varchar(255),
  `tinhtrang_sau` varchar(255),
  `tienphathuha` decimal,
  `songayquahan` int,
  `tienphatquahan` decimal,
  PRIMARY KEY (`matra`, `macuonsach`, `maphat`)
);

CREATE TABLE `PhieuPhat` (
  `maphat` varchar(255) PRIMARY KEY,
  `madocgia` varchar(255),
  `matra` varchar(255),
  `ngaylap` datetime,
  `tongtienphat` decimal,
  `trangthai` varchar(255),
  `ghichu` varchar(255)
);

CREATE TABLE `CTPhieuPhat` (
  `maphat` varchar(255),
  `macuonsach` varchar(255),
  `lydo` varchar(255),
  `songayquahan` int,
  `sotienphat` decimal,
  PRIMARY KEY (`maphat`, `macuonsach`)
);

CREATE TABLE `NhanVien` (
  `manv` varchar(255) PRIMARY KEY,
  `honv` varchar(255),
  `tennv` varchar(255),
  `gioitinh` varchar(255),
  `sdt` varchar(255),
  `ngaysinh` date
);

CREATE TABLE `TaiKhoan` (
  `tendangnhap` varchar(255) PRIMARY KEY,
  `matkhau` varchar(255),
  `manhomquyen` varchar(255),
  `manv` varchar(255)
);

CREATE TABLE `NhomQuyen` (
  `manhomquyen` varchar(255) PRIMARY KEY,
  `tennhomquyen` varchar(255)
);

CREATE TABLE `DanhMucChucNang` (
  `machucnang` varchar(255) PRIMARY KEY,
  `tenchucnang` varchar(255)
);

CREATE TABLE `CTQuyen` (
  `manhomquyen` varchar(255),
  `machucnang` varchar(255),
  `hanhdong` varchar(255),
  PRIMARY KEY (`manhomquyen`, `machucnang`)
);

ALTER TABLE `DauSach` ADD FOREIGN KEY (`matacgia`) REFERENCES `TacGia` (`matacgia`);

ALTER TABLE `DauSach` ADD FOREIGN KEY (`matheloai`) REFERENCES `TheLoai` (`matheloai`);

ALTER TABLE `DauSach` ADD FOREIGN KEY (`manxb`) REFERENCES `NhaXuatBan` (`manxb`);

ALTER TABLE `CuonSach` ADD FOREIGN KEY (`madausach`) REFERENCES `DauSach` (`madausach`);

ALTER TABLE `CuonSach` ADD FOREIGN KEY (`mavitri`) REFERENCES `ViTri` (`mavitri`);

ALTER TABLE `PhieuNhap` ADD FOREIGN KEY (`mancc`) REFERENCES `NhaCungCap` (`mancc`);

ALTER TABLE `PhieuNhap` ADD FOREIGN KEY (`manv`) REFERENCES `NhanVien` (`manv`);

ALTER TABLE `CTPhieuNhap` ADD FOREIGN KEY (`maphieunhap`) REFERENCES `PhieuNhap` (`maphieunhap`);

ALTER TABLE `CTPhieuNhap` ADD FOREIGN KEY (`madausach`) REFERENCES `DauSach` (`madausach`);

ALTER TABLE `PhieuMuon` ADD FOREIGN KEY (`madocgia`) REFERENCES `DocGia` (`madocgia`);

ALTER TABLE `PhieuMuon` ADD FOREIGN KEY (`manv`) REFERENCES `NhanVien` (`manv`);

ALTER TABLE `CTPhieuMuon` ADD FOREIGN KEY (`mamuon`) REFERENCES `PhieuMuon` (`mamuon`);

ALTER TABLE `CTPhieuMuon` ADD FOREIGN KEY (`macuonsach`) REFERENCES `CuonSach` (`macuonsach`);

ALTER TABLE `PhieuTra` ADD FOREIGN KEY (`mamuon`) REFERENCES `PhieuMuon` (`mamuon`);

ALTER TABLE `PhieuTra` ADD FOREIGN KEY (`manv`) REFERENCES `NhanVien` (`manv`);

ALTER TABLE `CTPhieuTra` ADD FOREIGN KEY (`matra`) REFERENCES `PhieuTra` (`matra`);

ALTER TABLE `CTPhieuTra` ADD FOREIGN KEY (`macuonsach`) REFERENCES `CuonSach` (`macuonsach`);

ALTER TABLE `PhieuPhat` ADD FOREIGN KEY (`madocgia`) REFERENCES `DocGia` (`madocgia`);

ALTER TABLE `PhieuPhat` ADD FOREIGN KEY (`matra`) REFERENCES `PhieuTra` (`matra`);

ALTER TABLE `CTPhieuPhat` ADD FOREIGN KEY (`maphat`) REFERENCES `PhieuPhat` (`maphat`);

ALTER TABLE `CTPhieuPhat` ADD FOREIGN KEY (`macuonsach`) REFERENCES `CuonSach` (`macuonsach`);

ALTER TABLE `TaiKhoan` ADD FOREIGN KEY (`manv`) REFERENCES `NhanVien` (`manv`);

ALTER TABLE `TaiKhoan` ADD FOREIGN KEY (`manhomquyen`) REFERENCES `NhomQuyen` (`manhomquyen`);

ALTER TABLE `CTQuyen` ADD FOREIGN KEY (`manhomquyen`) REFERENCES `NhomQuyen` (`manhomquyen`);

ALTER TABLE `CTQuyen` ADD FOREIGN KEY (`machucnang`) REFERENCES `DanhMucChucNang` (`machucnang`);
