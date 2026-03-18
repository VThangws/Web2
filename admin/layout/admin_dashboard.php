<?php
// admin_dashboard.php chỉ chứa NỘI DUNG trang admin (không include header/footer)
?>

<section class="d-md-block d-none">
    <div class="slide position-relative">
        <div class="banner-slider">
            <img src="/assets/img/banner/banner1.jpg" alt="Banner 1" class="img-fluid slide-img">
            <img src="/assets/img/banner/banner2.jpg" alt="Banner 2" class="img-fluid slide-img">
            <img src="/assets/img/banner/banner3.jpg" alt="Banner 3" class="img-fluid slide-img">
        </div>
        <div class="position-absolute centerbutton">
            <a href="#admin-actions"
               class="btn btn-outline-light rounded-0 fs-5 d-flex align-items-center justify-content-center effect-theloai rounded-1 fw-bold"
               style="width:180px;height: 50px;">
                Vào quản trị
            </a>
        </div>
    </div>

    <a href="#library-intro" class="scroll-down-indicator d-flex flex-column align-items-center justify-content-center">
        <span class="material-symbols-outlined">keyboard_double_arrow_down</span>
    </a>
</section>

<section id="library-intro" class="pt-3 pg-5 bg-light reveal-section library-intro">
    <div class="container-md">
        <div class="row align-items-center section-title">
            <div class="col-md-6 mb-4 mb-md-0">
                <h2 class="fw-bold mb-3 type-title">Trang quản trị Quản lý thư viện</h2>

                <p class="intro-text intro-1">
                    Đây là khu vực quản trị dành cho thủ thư/nhân viên: quản lý danh mục sách, tác giả, thể loại,
                    nhà xuất bản, bạn đọc và các nghiệp vụ liên quan.
                </p>

                <p class="intro-text intro-2">
                    Hiện tại trang này là khung giao diện (placeholder). Bạn có thể thêm chức năng sau theo từng phân hệ
                    bằng cách gắn link/điều hướng hoặc nhúng các màn hình quản lý hiện có.
                </p>
            </div>

            <div class="col-md-6">
                <img src="/assets/img/banner/library_intro.jpg"
                     class="img-fluid rounded-3 intro-image"
                     alt="Quản trị thư viện">
            </div>
        </div>
    </div>
</section>

<section id="admin-actions" class="py-5 reveal-section service-section">
    <div class="container-md">
        <div class="row align-items-center">
            <div class="col-lg-8 order-2 order-lg-1">
                <div class="row g-4">
                    <div class="col-md-6 reveal-item section-item">
                        <div class="benefit-card h-100">
                            <div class="service-icon">📚</div>
                            <h5 class="fw-bold">Quản lý đầu sách</h5>
                            <p class="mb-3">Thêm/sửa/xóa đầu sách, ảnh và thông tin mô tả.</p>
                            <a class="btn btn-sm btn-primary" href="/admin/QuanLy/DauSach/QL_DauSach.php">Mở phân hệ</a>
                        </div>
                    </div>

                    <div class="col-md-6 reveal-item section-item">
                        <div class="benefit-card h-100">
                            <div class="service-icon">👥</div>
                            <h5 class="fw-bold">Quản lý bạn đọc</h5>
                            <p class="mb-3">Theo dõi và cập nhật thông tin độc giả.</p>
                            <a class="btn btn-sm btn-primary" href="/admin/QuanLy/DocGia/QL_DocGia.php">Mở phân hệ</a>
                        </div>
                    </div>

                    <div class="col-md-6 reveal-item section-item">
                        <div class="benefit-card h-100">
                            <div class="service-icon">🧑‍💼</div>
                            <h5 class="fw-bold">Quản lý nhân viên</h5>
                            <p class="mb-3">Quản lý tài khoản/nhân sự phục vụ hệ thống.</p>
                            <a class="btn btn-sm btn-primary" href="/admin/QuanLy/NhanVien/QL_NhanVien.php">Mở phân hệ</a>
                        </div>
                    </div>

                    <div class="col-md-6 reveal-item section-item">
                        <div class="benefit-card h-100">
                            <div class="service-icon">🏷️</div>
                            <h5 class="fw-bold">Danh mục (Tác giả / Thể loại / NXB)</h5>
                            <p class="mb-3">Quản lý dữ liệu nền phục vụ tra cứu và phân loại.</p>
                            <div class="d-flex gap-2 flex-wrap">
                                <a class="btn btn-sm btn-outline-primary" href="/admin/QuanLy/TacGia/QL_TacGia.php">Tác giả</a>
                                <a class="btn btn-sm btn-outline-primary" href="/admin/QuanLy/TheLoai/QL_TheLoai.php">Thể loại</a>
                                <a class="btn btn-sm btn-outline-primary" href="/admin/QuanLy/NhaXuatBan/QL_NhaXuatBan.php">NXB</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-lg-4 order-1 order-lg-2 mb-4 mb-lg-0 text-lg-end text-center reveal-item section-title">
                <h2 class="fw-bold mb-3">Chức năng quản trị</h2>
                <p class="text-muted mb-0">
                    Các lối tắt nhanh để bạn gắn thêm nghiệp vụ vào trang admin sau này.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-white reveal-section">
    <div class="container-md">
        <div class="row align-items-start">
            <div class="col-lg-4 col-md-5 mb-4 mb-md-0 text-md-start text-center reveal-item section-title">
                <h3 class="fw-bold">Gợi ý cấu trúc mở rộng</h3>
                <p class="text-muted mb-0">Placeholder để bạn thêm tính năng theo nhu cầu.</p>
            </div>

            <div class="col-lg-8 col-md-7">
                <div class="row g-4">
                    <div class="col-sm-6 reveal-item section-item">
                        <div class="benefit-card h-100">
                            <h6 class="fw-bold">Thống kê & báo cáo</h6>
                            <p class="small mb-0">Số lượng sách, lượt mượn, tình trạng tồn kho…</p>
                        </div>
                    </div>
                    <div class="col-sm-6 reveal-item section-item">
                        <div class="benefit-card h-100">
                            <h6 class="fw-bold">Quyền & phân vai</h6>
                            <p class="small mb-0">Phân quyền theo vai trò (admin, thủ thư, nhân viên…).</p>
                        </div>
                    </div>
                    <div class="col-sm-6 reveal-item section-item">
                        <div class="benefit-card h-100">
                            <h6 class="fw-bold">Duyệt yêu cầu</h6>
                            <p class="small mb-0">Duyệt đăng ký, duyệt mượn, xử lý hoàn/trả…</p>
                        </div>
                    </div>
                    <div class="col-sm-6 reveal-item section-item">
                        <div class="benefit-card h-100">
                            <h6 class="fw-bold">Nhật ký hệ thống</h6>
                            <p class="small mb-0">Theo dõi thao tác quan trọng và lịch sử thay đổi dữ liệu.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light reveal-section stats-section">
    <div class="container-md">
        <div class="row mb-4 text-center reveal-item section-title">
            <div class="col">
                <h3 class="fw-bold">Tổng quan nhanh</h3>
                <p class="text-muted mb-0">Các chỉ số minh hoạ (placeholder)</p>
            </div>
        </div>

        <div class="row text-center">
            <div class="col-md-3 col-6 mb-3 mb-md-0 reveal-item section-item">
                <h3 class="fw-bold mb-1" data-target="6">6+</h3>
                <p class="text-muted mb-0">Phân hệ quản lý</p>
            </div>
            <div class="col-md-3 col-6 mb-3 mb-md-0 reveal-item section-item">
                <h3 class="fw-bold mb-1" data-target="3">3+</h3>
                <p class="text-muted mb-0">Nhóm danh mục</p>
            </div>
            <div class="col-md-3 col-6 mb-3 mb-md-0 reveal-item section-item">
                <h3 class="fw-bold mb-1" data-target="1">1+</h3>
                <p class="text-muted mb-0">Dashboard</p>
            </div>
            <div class="col-md-3 col-6 reveal-item section-item">
                <h3 class="fw-bold mb-1" data-target="2026">2026+</h3>
                <p class="text-muted mb-0">Phiên bản demo</p>
            </div>
        </div>
    </div>
</section>

<div id="back-to-top" class="back-to-top-bubble">
    <span class="material-symbols-outlined">chevron_line_up</span>
</div>

<script src="/assets/js/home.js" defer></script>
