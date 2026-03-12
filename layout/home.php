<?php

// home.php chỉ chứa NỘI DUNG TRANG CHỦ, không include header/footer nữa
?>
<section class="d-md-block d-none">
    <div class="slide position-relative">
        <div class="banner-slider">
            <img src="/assets/img/banner/banner1.jpg" alt="Banner 1" class="img-fluid slide-img">
            <img src="/assets/img/banner/banner2.jpg" alt="Banner 2" class="img-fluid slide-img">
            <img src="/assets/img/banner/banner3.jpg" alt="Banner 3" class="img-fluid slide-img">
        </div>
        <div class="position-absolute centerbutton">
            <a href="/index.php?page=books"
                class="btn btn-outline-light rounded-0 fs-5 d-flex align-items-center justify-content-center effect-theloai rounded-1 fw-bold"
                style="width:150px;height: 50px;">
                Khám phá
            </a>
        </div>
    </div>
    <!-- Mũi tên hướng dẫn cuộn xuống: NẰM DƯỚI banner, không đè lên -->
    <a href="#library-intro" class="scroll-down-indicator d-flex flex-column align-items-center justify-content-center">
        <span class="material-symbols-outlined">keyboard_double_arrow_down</span>
    </a>
</section>

<!-- GIỚI THIỆU TỔNG QUAN THƯ VIỆN -->
<section id="library-intro" class="pt-3 pg-5 bg-light reveal-section library-intro">
    <div class="container-md">
        <div class="row align-items-center section-title">

            <!-- TEXT -->
            <div class="col-md-6 mb-4 mb-md-0">

                <h2 class="fw-bold mb-3 type-title">
                    Thư viện điện tử ASAG Library
                </h2>

                <p class="intro-text intro-1">
                    ASAG Library là hệ thống thư viện điện tử hỗ trợ bạn đọc tra cứu, đọc và mượn sách mọi lúc mọi nơi.
                    Với kho tài liệu đa dạng từ sách tham khảo, giáo trình, tài liệu nghiên cứu đến tạp chí và báo điện tử,
                    thư viện mong muốn đồng hành cùng bạn trong hành trình học tập và nghiên cứu.
                </p>

                <p class="intro-text intro-2">
                    Bạn đọc có thể dễ dàng tìm kiếm đầu sách theo tên, tác giả, chủ đề; quản lý phiếu mượn,
                    lịch sử mượn trả và nhận các khuyến nghị đọc phù hợp với nhu cầu cá nhân.
                </p>

            </div>

            <!-- IMAGE -->
            <div class="col-md-6">
                <img src="/assets/img/banner/library_intro.jpg"
                     class="img-fluid rounded-3 intro-image"
                     alt="Giới thiệu thư viện">
            </div>

        </div>
    </div>
</section>
<!-- CÁC DỊCH VỤ CHÍNH CỦA THƯ VIỆN -->
<section class="py-5 reveal-section service-section">
    <div class="container-md">
        <div class="row align-items-center">
            <!-- Cột tiện ích bên trái -->
            <div class="col-lg-8 order-2 order-lg-1">
                <div class="row g-4">
                    <div class="col-md-6 reveal-item section-item">
                        <div class="benefit-card h-100">
                            <div class="service-icon">📚</div>
                            <h5 class="fw-bold">Tra cứu & đọc trực tuyến</h5>
                            <p>
                                Tìm kiếm nhanh theo tiêu đề, tác giả, chủ đề.
                                Đọc trước trích đoạn và xem chi tiết tài liệu.
                            </p>
                        </div>
                    </div>

                    <div class="col-md-6 reveal-item section-item">
                        <div class="benefit-card h-100">
                            <div class="service-icon">📝</div>
                            <h5 class="fw-bold">Quản lý mượn trả</h5>
                            <p>
                                Theo dõi tài liệu đang mượn, lịch sử mượn trả
                                và nhận thông báo nhắc trả sách.
                            </p>
                        </div>
                    </div>

                    <div class="col-md-6 reveal-item section-item">
                        <div class="benefit-card h-100">
                            <div class="service-icon">💻</div>
                            <h5 class="fw-bold">Tài liệu số</h5>
                            <p>
                                Truy cập ebook, luận văn, tạp chí khoa học
                                hỗ trợ học tập và nghiên cứu.
                            </p>
                        </div>
                    </div>

                    <div class="col-md-6 reveal-item section-item">
                        <div class="benefit-card h-100">
                            <div class="service-icon">🔎</div>
                            <h5 class="fw-bold">Gợi ý tài liệu</h5>
                            <p>
                                Hệ thống đề xuất sách theo chuyên ngành
                                và nhu cầu đọc của bạn.
                            </p>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Cột tiêu đề bên phải -->
            <div class="col-lg-4 order-1 order-lg-2 mb-4 mb-lg-0 text-lg-end text-center reveal-item section-title">
                <h2 class="fw-bold mb-3">Dịch vụ thư viện</h2>
                <p class="text-muted mb-0">
                    Những tiện ích nổi bật giúp bạn đọc
                    dễ dàng tiếp cận tri thức mọi lúc mọi nơi.
                </p>
            </div>
        </div>
    </div>
</section>
<!-- LỢI ÍCH KHI SỬ DỤNG THƯ VIỆN -->
<section class="py-5 bg-white reveal-section">
    <div class="container-md">
        <div class="row align-items-start">
            <!-- Tiêu đề sát bên trái -->
            <div class="col-lg-4 col-md-5 mb-4 mb-md-0 text-md-start text-center reveal-item section-title">
                <h3 class="fw-bold">Vì sao nên sử dụng ASAG Library?</h3>
                <p class="text-muted mb-0">
                    Mang lại trải nghiệm đọc và học tập toàn diện cho mọi đối tượng.
                </p>
            </div>

            <!-- Các tiện ích trải dài bên phải -->
            <div class="col-lg-8 col-md-7">
                <div class="row g-4">
                    <div class="col-sm-6 reveal-item section-item">
                        <div class="benefit-card h-100">
                            <h6 class="fw-bold">Tiết kiệm thời gian</h6>
                            <p class="small mb-0">
                                Tra cứu và mượn sách online mà không cần xếp hàng tại quầy.
                            </p>
                        </div>
                    </div>
                    <div class="col-sm-6 reveal-item section-item">
                        <div class="benefit-card h-100">
                            <h6 class="fw-bold">Kho tài liệu phong phú</h6>
                            <p class="small mb-0">
                                Hàng nghìn đầu sách và tài liệu số ở nhiều lĩnh vực.
                            </p>
                        </div>
                    </div>
                    <div class="col-sm-6 reveal-item section-item">
                        <div class="benefit-card h-100">
                            <h6 class="fw-bold">Truy cập mọi lúc</h6>
                            <p class="small mb-0">
                                Đọc tài liệu trên nhiều thiết bị mọi lúc mọi nơi.
                            </p>
                        </div>
                    </div>
                    <div class="col-sm-6 reveal-item section-item">
                    <div class="benefit-card h-100">
                            <h6 class="fw-bold">Hỗ trợ học tập</h6>
                            <p class="small mb-0">
                                ĐGợi ý tài liệu theo chuyên ngành và nhu cầu đọc.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- NHÓM ĐỐI TƯỢNG PHÙ HỢP -->
<section class="py-5 bg-light reveal-section">
    <div class="container-md">
        <div class="row mb-4 text-center reveal-item section-title">
            <div class="col">
                <h3 class="fw-bold">Dành cho ai?</h3>
                <p class="text-muted mb-0">ASAG  Library phù hợp với nhiều nhóm bạn đọc khác nhau</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-4 reveal-item section-item">
                <div class="h-100 p-3 border rounded-3 bg-white">
                    <h5 class="fw-bold mb-2">Sinh viên & học sinh</h5>
                    <p class="mb-0">Tài liệu tham khảo, giáo trình, sách kỹ năng mềm hỗ trợ học tập và định hướng nghề nghiệp.</p>
                </div>
            </div>
            <div class="col-md-4 reveal-item section-item">
                <div class="h-100 p-3 border rounded-3 bg-white">
                    <h5 class="fw-bold mb-2">Giảng viên & nhà nghiên cứu</h5>
                    <p class="mb-0">Kho luận văn, tài liệu chuyên ngành, tạp chí khoa học phục vụ nghiên cứu và giảng dạy.</p>
                </div>
            </div>
            <div class="col-md-4 reveal-item section-item">
                <div class="h-100 p-3 border rounded-3 bg-white">
                    <h5 class="fw-bold mb-2">Bạn đọc yêu sách</h5>
                    <p class="mb-0">Sách văn học, kỹ năng, kinh tế, công nghệ, đời sống giúp mở rộng hiểu biết và thư giãn.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- HƯỚNG DẪN 3 BƯỚC SỬ DỤNG THƯ VIỆN -->
<section class="py-5 bg-white reveal-section">
    <div class="container-md">
        <div class="row mb-4 text-center reveal-item section-title">
            <div class="col">
                <h3 class="fw-bold">Bắt đầu với thư viện trong 3 bước</h3>
            </div>
        </div>
        <div class="row g-4 text-center">
            <div class="col-md-4 reveal-item section-item">
                <div class="p-3 border rounded-3 h-100">
                    <h1 class="fw-bold text-primary mb-2">1</h1>
                    <h6 class="fw-bold mb-2">Đăng ký tài khoản</h6>
                    <p class="small mb-0">Tạo tài khoản bạn đọc bằng email hoặc mã số sinh viên để sử dụng đầy đủ chức năng.</p>
                </div>
            </div>
            <div class="col-md-4 reveal-item section-item">
                <div class="p-3 border rounded-3 h-100">
                    <h1 class="fw-bold text-primary mb-2">2</h1>
                    <h6 class="fw-bold mb-2">Tra cứu tài liệu</h6>
                    <p class="small mb-0">Sử dụng thanh tìm kiếm hoặc bộ lọc để tìm sách, tài liệu phù hợp với nhu cầu.</p>
                </div>
            </div>
            <div class="col-md-4 reveal-item section-item">
                <div class="p-3 border rounded-3 h-100">
                    <h1 class="fw-bold text-primary mb-2">3</h1>
                    <h6 class="fw-bold mb-2">Mượn và theo dõi</h6>
                    <p class="small mb-0">Gửi yêu cầu mượn, theo dõi tình trạng và nhận thông báo nhắc trả sách đúng hạn.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- BỘ SƯU TẬP HÌNH ẢNH THƯ VIỆN -->
<section class="py-5 bg-white reveal-section">
    <div class="container-md">
        <div class="row mb-4 text-center reveal-item section-title">
            <div class="col">
                <h3 class="fw-bold">Góc nhìn từ thư viện</h3>
                <p class="text-muted mb-0">Một vài hình ảnh về không gian và hoạt động tại thư viện</p>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-md-4 col-12 reveal-item section-item">
                <img src="/assets/img/banner/library_intro1.jpg" alt="Khu vực đọc sách" class="img-fluid rounded-3 shadow-sm h-100 w-100 object-fit-cover">
            </div>
            <div class="col-md-4 col-6 reveal-item section-item">
                <img src="/assets/img/banner/library_intro2.jpg" alt="Kệ sách thư viện" class="img-fluid rounded-3 shadow-sm mb-3 object-fit-cover" style="height:160px; width:100%;">
                <img src="/assets/img/banner/library_intro3.jpg" alt="Hoạt động bạn đọc" class="img-fluid rounded-3 shadow-sm object-fit-cover" style="height:160px; width:100%;">
            </div>
            <div class="col-md-4 col-6 reveal-item section-item">
                <img src="/assets/img/banner/library_intro4.jpg" alt="Góc học tập yên tĩnh" class="img-fluid rounded-3 shadow-sm mb-3 object-fit-cover" style="height:160px; width:100%;">
                <img src="/assets/img/banner/library_intro5.jpg" alt="Nhóm bạn đọc thảo luận" class="img-fluid rounded-3 shadow-sm object-fit-cover" style="height:160px; width:100%;">
            </div>
        </div>
    </div>
</section>

<!-- THỐNG KÊ NGẮN VỀ THƯ VIỆN -->
<section class="py-5 bg-light reveal-section stats-section">
    <div class="container-md">
        <div class="row text-center reveal-item section-title">
        </div>
        <div class="row mb-4 text-center reveal-item section-title">
            <div class="col">
                <h3 class="fw-bold">Thống kê hiện tại của thư viện</h3>
                <p class="text-muted mb-0">Thông tin & sức chứa hiện tại của thư viện</p>
            </div>
        </div>
        <div class="row text-center">
            <div class="col-md-3 col-6 mb-3 mb-md-0 reveal-item section-item">
                <h3 class="fw-bold mb-1" data-target="10000">10.000+</h3>
                <p class="text-muted mb-0">Đầu sách</p>
            </div>
            <div class="col-md-3 col-6 mb-3 mb-md-0 reveal-item section-item">
                <h3 class="fw-bold mb-1" data-target="3000">3.000+</h3>
                <p class="text-muted mb-0">Bạn đọc</p>
            </div>
            <div class="col-md-3 col-6 mb-3 mb-md-0 reveal-item section-item">
                <h3 class="fw-bold mb-1" data-target="500">500+</h3>
                <p class="text-muted mb-0">Tài liệu số</p>
            </div>
            <div class="col-md-3 col-6 reveal-item section-item">
                <h3 class="fw-bold mb-1" data-target="20">20+</h3>
                <p class="text-muted mb-0">Chủ đề chuyên ngành</p>
            </div>
        </div>
    </div>
</section>

<!-- Nút quay lại đầu trang -->
<div id="back-to-top" class="back-to-top-bubble">
    <span class="material-symbols-outlined">chevron_line_up</span>
</div>

<script src="/assets/js/home.js" defer></script>