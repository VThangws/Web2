<?php
require_once __DIR__ . '/../../login/auth.php';
require_admin_login(); 
?>

<?php include __DIR__ . '/../../layout/admin_sidebar.php'; ?>

<script src="https://unpkg.com/html5-qrcode"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --primary-color: #20c997;
        --bg-glass: rgba(255, 255, 255, 0.9);
    }

    .admin-main-content {
        background: #f0f2f5;
        min-height: 100vh;
        padding: 40px 20px;
    }

    /* Bo tròn và làm đẹp khung quét */
    #reader {
        border: none !important;
        border-radius: 24px !important;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        background: white;
    }

    /* Căn chỉnh lại các nút bấm mặc định của thư viện */
    #reader__dashboard_section_csr button, 
    #reader__status_span + button {
        background: var(--primary-color) !important;
        color: white !important;
        border: none !important;
        padding: 12px 25px !important;
        border-radius: 12px !important;
        font-weight: 600 !important;
        margin-top: 10px !important;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(32, 201, 151, 0.3);
    }

    #reader__dashboard_section_csr button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(32, 201, 151, 0.4);
    }

    /* Khung quét ảo (Overlay) */
    .scanner-overlay {
        position: relative;
    }

    .scanner-info {
        background: white;
        border-radius: 20px;
        padding: 25px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    .status-badge {
        display: inline-block;
        padding: 6px 16px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 15px;
    }

    .status-waiting { background: #fff3cd; color: #856404; }
    .status-processing { background: #cce5ff; color: #004085; }

    /* Hiệu ứng viền chạy khi quét */
    .scan-line {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 2px;
        background: var(--primary-color);
        box-shadow: 0 0 10px var(--primary-color);
        animation: scan 2s infinite linear;
        z-index: 10;
        display: none;
    }

    @keyframes scan {
        0% { top: 0; }
        100% { top: 100%; }
    }
</style>

<div class="admin-main-content">
    <div class="container">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-10">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-success text-white p-3 rounded-3 me-3">
                        <i class="fa-solid fa-bolt-lightning fa-xl"></i>
                    </div>
                    <div>
                        <h2 class="fw-bold mb-0">Xác Nhận Nhanh</h2>
                        <p class="text-muted mb-0">Hệ thống quét QR thời gian thực cho ASAG Library</p>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-7">
                        <div class="scanner-overlay">
                            <div id="reader"></div>
                            <div id="scan-line" class="scan-line"></div>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="scanner-info h-100 d-flex flex-column justify-content-between">
                            <div>
                                <div id="badge-status" class="status-badge status-waiting">
                                    <i class="fa-solid fa-circle-dot me-1"></i> Đang chờ tín hiệu
                                </div>
                                <h4 class="fw-bold mb-3">Quét Phiếu Mượn</h4>
                                <p class="text-muted small">Đưa mã QR từ ứng dụng của độc giả vào khung hình để hệ thống tự động nhận diện và cập nhật trạng thái.</p>
                                
                                <hr class="my-4">
                                
                                <div id="result-box" class="d-none">
                                    <div class="p-3 border rounded-3 bg-light">
                                        <label class="small text-muted d-block">Mã phiếu nhận diện:</label>
                                        <span id="result-id" class="h5 fw-bold text-success"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <div class="d-flex align-items-center p-3 rounded-3" style="background: #f8f9fa;">
                                    <i class="fa-solid fa-shield-halved text-success me-3 fa-lg"></i>
                                    <span class="small text-muted">Dữ liệu được mã hóa và xác thực an toàn bởi hệ thống Admin.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function onScanSuccess(decodedText, decodedResult) {
    let mamuon = decodedText.trim();
    
    // Cập nhật UI ngay lập tức
    document.getElementById('scan-line').style.display = 'block';
    document.getElementById('badge-status').className = 'status-badge status-processing';
    document.getElementById('badge-status').innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Đang xử lý...';
    document.getElementById('result-box').classList.remove('d-none');
    document.getElementById('result-id').innerText = mamuon;

    let formData = new FormData();
    formData.append('mamuon', mamuon);

    fetch('/ajax/update_status_qr.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            // Hiệu ứng thành công
            alert("✅ " + data.message);
            location.reload();
        } else {
            alert("❌ Lỗi: " + data.message);
            resetUI();
        }
    })
    .catch(err => {
        console.error(err);
        alert("❌ Lỗi kết nối server");
    });
}

function resetUI() {
    document.getElementById('scan-line').style.display = 'none';
    document.getElementById('badge-status').className = 'status-badge status-waiting';
    document.getElementById('badge-status').innerHTML = '<i class="fa-solid fa-circle-dot me-1"></i> Đang chờ tín hiệu';
}

let html5QrcodeScanner = new Html5QrcodeScanner(
    "reader", { 
        fps: 25, 
        qrbox: { width: 280, height: 280 },
        aspectRatio: 1.0
    }
);
html5QrcodeScanner.render(onScanSuccess);
</script>