// ==========================================
// 1. NHÓM QUẢN LÝ ĐẦU SÁCH & CUỐN SÁCH
// ==========================================

// Lưu thông tin chung của tác phẩm (Metadata)
Table DauSach {
  madausach varchar [pk]
  tensach varchar
  namxuatban int
  dongia decimal // Giá bìa chung
  matacgia varchar 
  matheloai varchar 
  manxb varchar 
  mota varchar
  anhbia varchar
}

// Lưu từng cuốn sách vật lý nằm trên kệ (Physical Copy)
Table CuonSach {
  macuonsach varchar [pk] // Barcode
  madausach varchar
  mavitri varchar         // FK -> ViTri
  trangthai varchar       // SanSang, DangMuon, Hong, Mat, ThanhLy
  tinhtrang varchar       // Moi, Tot, Cu, Rac, HuHong
}

Table ViTri {
  mavitri varchar [pk]
  khuvuc varchar    // VD: Tang 1, Tang 2
  ke varchar        // VD: Ke A, Ke B
  mota varchar
}

Table TacGia {
  matacgia varchar [pk]
  tentacgia varchar
}

Table TheLoai {
  matheloai varchar [pk]
  tentheloai varchar
}

Table NhaXuatBan {
  manxb varchar [pk]
  tennxb varchar
  diachi varchar
  sdt varchar
  email varchar
}

// ==========================================
// 2. NHÓM NHẬP HÀNG (Nhập theo Đầu sách)
// ==========================================

Table NhaCungCap {
  mancc varchar [pk]
  tenncc varchar
  diachincc varchar
  sdt varchar
  email varchar
}

Table PhieuNhap {
  maphieunhap varchar [pk]
  thoigiantao datetime
  tongtien decimal
  manv varchar
  mancc varchar
}

// Khi nhập, ta nhập số lượng cho Đầu sách. 
// (Logic phần mềm sẽ tự sinh ra n dòng bên bảng CuonSach tương ứng)
Table CTPhieuNhap {
  maphieunhap varchar
  madausach varchar
  dongianhap decimal
  soluong int
  
  indexes {
    (maphieunhap, madausach) [pk]
  }
}

// ==========================================
// 3. NHÓM MƯỢN & TRẢ (Mượn đích danh Cuốn sách)
// ==========================================

Table DocGia { 
  madocgia varchar [pk]
  hodocgia varchar
  tendocgia varchar
  email varchar
  sdt varchar
  ngaysinh date
  diachi varchar
}

Table PhieuMuon {
  mamuon varchar [pk]
  ngaymuon datetime
  ngayhethan datetime 
  manv varchar
  madocgia varchar
  trangthai varchar // DangMuon, DaTraXong, QuaHan
  ghichu varchar
}

// Chi tiết mượn chỉ ra rõ là mượn cuốn nào (Barcode nào)
Table CTPhieuMuon {
  mamuon varchar
  macuonsach varchar
  // Không cần cột soluong vì macuonsach là duy nhất (1 dòng = 1 cuốn)
  tinhtrang_truoc varchar // Ghi nhận tình trạng lúc đưa cho khách (Mới/Cũ)
  
  indexes {
    (mamuon, macuonsach) [pk]
  }
}

Table PhieuTra {
  matra varchar [pk]
  mamuon varchar 
  ngaytra datetime 
  manv varchar 
  tongtienphat decimal 
}

Table CTPhieuTra {
  matra varchar
  macuonsach varchar
  maphat varchar
  tinhtrang_sau varchar // Tình trạng khi trả về (Rách/Mất/Nguyên vẹn)
  tienphathuha decimal 
  songayquahan int 
  tienphatquahan decimal 
  
  indexes {
    (matra, macuonsach, maphat) [pk]
  }
}

// Phạt
Table PhieuPhat {
  maphat varchar [pk]
  madocgia varchar
  matra varchar // gắn với phiếu trả
  ngaylap datetime
  tongtienphat decimal
  trangthai varchar // ChuaThu, DaThu
  ghichu varchar
}

Table CTPhieuPhat {
  maphat varchar
  macuonsach varchar
  lydo varchar // QuaHan, HuHong, MatSach
  songayquahan int
  sotienphat decimal
  
  indexes {
    (maphat, macuonsach) [pk]
  }
}

// ==========================================
// 4. NHÓM HỆ THỐNG & PHÂN QUYỀN
// ==========================================

Table NhanVien {
  manv varchar [pk]
  honv varchar
  tennv varchar
  gioitinh varchar
  sdt varchar
  ngaysinh date
}

Table TaiKhoan {
  tendangnhap varchar [pk] 
  matkhau varchar
  manhomquyen varchar
  manv varchar
}

Table NhomQuyen {
  manhomquyen varchar [pk]
  tennhomquyen varchar
}

Table DanhMucChucNang {
  machucnang varchar [pk]
  tenchucnang varchar
}

Table CTQuyen {
  manhomquyen varchar
  machucnang varchar
  hanhdong varchar 
  
  indexes {
    (manhomquyen, machucnang) [pk]
  }
}

// ==========================================
// LIÊN KẾT (RELATIONSHIPS)
// ==========================================


// ---------- SÁCH (ĐẦU SÁCH & CUỐN SÁCH) ----------

// Tác giả — Đầu sách (1 — N)
Ref: TacGia.matacgia < DauSach.matacgia

// Thể loại — Đầu sách (1 — N)
Ref: TheLoai.matheloai < DauSach.matheloai

// NXB — Đầu sách (1 — N)
Ref: NhaXuatBan.manxb < DauSach.manxb

// Đầu sách — Cuốn sách (1 — N)
Ref: DauSach.madausach < CuonSach.madausach

// Vị trí — Cuốn sách (1 — N)
Ref: ViTri.mavitri < CuonSach.mavitri


// ---------- NHẬP HÀNG ----------

// Nhà cung cấp — Phiếu nhập (1 — N)
Ref: NhaCungCap.mancc < PhieuNhap.mancc

// Nhân viên — Phiếu nhập (1 — N)
Ref: NhanVien.manv < PhieuNhap.manv

// Phiếu nhập — CT Phiếu nhập (1 — N)
Ref: PhieuNhap.maphieunhap < CTPhieuNhap.maphieunhap

// Đầu sách — CT Phiếu nhập (1 — N)
Ref: DauSach.madausach < CTPhieuNhap.madausach


// ---------- MƯỢN SÁCH ----------

// Độc giả — Phiếu mượn (1 — N)
Ref: DocGia.madocgia < PhieuMuon.madocgia

// Nhân viên — Phiếu mượn (1 — N)
Ref: NhanVien.manv < PhieuMuon.manv

// Phiếu mượn — CT Phiếu mượn (1 — N)
Ref: PhieuMuon.mamuon < CTPhieuMuon.mamuon

// Cuốn sách — CT Phiếu mượn (1 — N)
Ref: CuonSach.macuonsach < CTPhieuMuon.macuonsach


// ---------- TRẢ SÁCH (1 — N) ----------

// Phiếu mượn — Phiếu trả (1 — N)
Ref: PhieuMuon.mamuon < PhieuTra.mamuon

// Nhân viên — Phiếu trả (1 — N)
Ref: NhanVien.manv < PhieuTra.manv

// Phiếu trả — CT Phiếu trả (1 — N)
Ref: PhieuTra.matra < CTPhieuTra.matra

// Cuốn sách — CT Phiếu trả (1 — N)
Ref: CuonSach.macuonsach < CTPhieuTra.macuonsach


// ---------- PHẠT ----------

// Độc giả — Phiếu phạt (1 — N)
Ref: DocGia.madocgia < PhieuPhat.madocgia

// Phiếu trả — Phiếu phạt (1 — 0..1)
Ref: PhieuTra.matra - PhieuPhat.matra

// Phiếu phạt — CT Phiếu phạt (1 — N)
Ref: PhieuPhat.maphat < CTPhieuPhat.maphat

// Cuốn sách — CT Phiếu phạt (1 — N)
Ref: CuonSach.macuonsach < CTPhieuPhat.macuonsach


// ---------- HỆ THỐNG & PHÂN QUYỀN ----------

// Nhân viên — Tài khoản (1 — 0..1)
Ref: NhanVien.manv - TaiKhoan.manv

// Nhóm quyền — Tài khoản (1 — N)
Ref: NhomQuyen.manhomquyen < TaiKhoan.manhomquyen

// Nhóm quyền — Chức năng (N — N)
Ref: NhomQuyen.manhomquyen < CTQuyen.manhomquyen
Ref: DanhMucChucNang.machucnang < CTQuyen.machucnang
