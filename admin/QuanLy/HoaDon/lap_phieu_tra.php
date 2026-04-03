<?php
require_once __DIR__ . '/../../login/auth.php';
require_admin_login();
require_once __DIR__ . '/../../../database/ConnectDB.php';

$mamuon = $_GET['mamuon'] ?? '';

if (empty($mamuon)) {
    die("Thiếu mã phiếu mượn.");
}

$conn = ConnectDB::getInstance()->getConnection();

// Lọc phiếu mượn
$stmt = $conn->prepare("
    SELECT p.mamuon, p.ngaymuon, p.ngayhethan, p.madocgia, d.tendocgia
    FROM phieumuon p
    LEFT JOIN docgia d ON p.madocgia = d.madocgia
    WHERE p.mamuon = ?
");
$stmt->bind_param("s", $mamuon);
$stmt->execute();
$phieu = $stmt->get_result()->fetch_assoc();

if (!$phieu) {
    die("Trong cơ sở dữ liệu không tìm thấy phiếu mượn này.");
}

$stmtCuonSach = $conn->prepare("
    SELECT pt.macuonsach, ds.tensach, ds.dongia, c.tinhtrang, pt.tinhtrang_truoc
    FROM ctphieumuon pt
    JOIN cuonsach c ON pt.macuonsach = c.macuonsach
    JOIN dausach ds ON c.madausach = ds.madausach
    WHERE pt.mamuon = ?
");
$stmtCuonSach->bind_param("s", $mamuon);
$stmtCuonSach->execute();
$cuonSachList = $stmtCuonSach->get_result()->fetch_all(MYSQLI_ASSOC);

$ngayhethan = $phieu['ngayhethan'];
$lateDays = 0;
if ($ngayhethan) {
    $dateHethan = new DateTime(substr($ngayhethan, 0, 10)); // chỉ lấy YYYY-MM-DD
    $dateNow = new DateTime(date('Y-m-d'));
    if ($dateNow > $dateHethan) {
        $lateDays = $dateNow->diff($dateHethan)->days;
    }
}

// Cấu hình phạt trễ
$FINE_PER_DAY = 5000;
$totalLateFine = $lateDays * $FINE_PER_DAY * count($cuonSachList);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lập Phiếu Trả Sách</title>
    <!-- Include Bootstrap locally or CDN -->
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    
<style>
    .admin-main {
        background: #f4f6f9;
        min-height: 100vh;
        padding: 30px;
    }
    .card-wrap {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border: none;
        margin-bottom: 25px;
    }
    .card-header-custom {
        background: #20c997;
        color: white;
        border-radius: 12px 12px 0 0 !important;
        padding: 15px 25px;
        font-weight: 600;
        font-size: 1.1rem;
    }
    .book-item-box {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        background: #fbfbfc;
    }
    .input-fine {
        background-color: #fff !important;
    }
    .total-footer {
        background: #fff3cd;
        border-radius: 8px;
        padding: 20px;
        color: #856404;
        font-size: 1.2rem;
        font-weight: 600;
        border: 1px dashed #ffeeba;
    }
    .btn-finish {
        background: #20c997;
        color: white;
        padding: 12px 30px;
        font-weight: 600;
        font-size: 1.1rem;
        border-radius: 8px;
        border: none;
        transition: 0.3s;
    }
    .btn-finish:hover {
        background: #1ba87e;
        transform: translateY(-2px);
    }
</style>
</head>
<body>
<?php include __DIR__ . '/../../layout/admin_sidebar.php'; ?>

<div class="admin-main">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h2 class="fw-bold">LẬP PHIẾU TRẢ SÁCH</h2>
                <p class="text-muted">Đang xử lý đơn mượn <strong><?= htmlspecialchars($phieu['mamuon']) ?></strong></p>
            </div>
        </div>

        <form id="frmTraSach">
            <input type="hidden" name="mamuon" value="<?= htmlspecialchars($phieu['mamuon']) ?>">
            <input type="hidden" name="madocgia" value="<?= htmlspecialchars($phieu['madocgia']) ?>">

            <!-- Thông tin chung -->
            <div class="card card-wrap">
                <div class="card-header card-header-custom">
                    <i class="fa-solid fa-circle-info me-2"></i> THÔNG TIN ĐƠN MƯỢN
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="text-muted small">Mã Phiếu Mượn</label>
                            <h5 class="fw-bold text-dark"><?= htmlspecialchars($phieu['mamuon']) ?></h5>
                        </div>
                        <div class="col-md-3">
                            <label class="text-muted small">Người Mượn (MĐG)</label>
                            <h5 class="fw-bold text-dark"><?= htmlspecialchars($phieu['tendocgia']) ?> (<?= htmlspecialchars($phieu['madocgia']) ?>)</h5>
                        </div>
                        <div class="col-md-3">
                            <label class="text-muted small">Ngày Trả Dự Kiến</label>
                            <h5 class="fw-bold text-dark"><?= date('d/m/Y', strtotime($ngayhethan)) ?></h5>
                        </div>
                        <div class="col-md-3">
                            <label class="text-muted small">Số Ngày Trễ</label>
                            <?php if ($lateDays > 0): ?>
                                <h5 class="fw-bold text-danger"><?= $lateDays ?> ngày</h5>
                                <input type="hidden" name="songayquahan" value="<?= $lateDays ?>">
                            <?php else: ?>
                                <h5 class="fw-bold text-success">Đúng Hạn</h5>
                                <input type="hidden" name="songayquahan" value="0">
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Phạt Trễ Hạn Tổng -->
            <?php if ($lateDays > 0): ?>
            <div class="alert alert-danger d-flex align-items-center mb-4" role="alert" style="border-radius:12px;">
                <i class="fa-solid fa-triangle-exclamation fa-2x me-3"></i>
                <div>
                    <h5 class="alert-heading mb-1 fw-bold">Phát sinh Phạt Trễ Hạn!</h5>
                    <div>Hệ thống phát hiện trả trễ <strong><?= $lateDays ?></strong> ngày cho <strong><?= count($cuonSachList) ?></strong> cuốn sách. Tiền phạt: <?= number_format($FINE_PER_DAY) ?>đ / cuốn / ngày.</div>
                    <div class="mt-2 text-danger h4 fw-bold">
                        <span id="txtTotalLateFine"><?= number_format($totalLateFine) ?></span> đ
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <input type="hidden" id="inTotalLateFine" value="<?= $totalLateFine ?>">

            <!-- Tình trạng từng cuốn sách -->
            <div class="card card-wrap">
                <div class="card-header card-header-custom" style="background:#4e73df;">
                    <i class="fa-solid fa-book me-2"></i> TÌNH TRẠNG SÁCH & PHẠT HƯ HỎNG
                </div>
                <div class="card-body">
                    <?php if (empty($cuonSachList)): ?>
                        <div class="text-center text-muted py-4">Không tìm thấy sách trong phiếu mượn này.</div>
                    <?php endif; ?>

                    <?php foreach ($cuonSachList as $idx => $sach): ?>
                    <div class="book-item-box">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <h6 class="fw-bold text-primary mb-1"><?= htmlspecialchars($sach['tensach']) ?></h6>
                                <p class="text-muted small mb-0">Mã sách: <strong><?= htmlspecialchars($sach['macuonsach']) ?></strong></p>
                                <p class="text-muted small mb-0">Tình trạng ghi nhận lúc mượn: <strong><?= htmlspecialchars($sach['tinhtrang_truoc'] ?? 'Không rõ') ?></strong></p>
                            </div>

                            <div class="col-md-2">
                                <label class="small text-muted mb-1">Kiểm tra sau mượn</label>
                                <select class="form-select form-select-sm condition-select" name="tinhtrang_sau[<?= htmlspecialchars($sach['macuonsach']) ?>]" data-price="<?= htmlspecialchars($sach['dongia'] ?? 0) ?>" data-target-input="fine-input-<?= htmlspecialchars($sach['macuonsach']) ?>">
                                    <option value="Tốt">Sách tốt</option>
                                    <option value="Hư hỏng">Hư hỏng nhẹ (Rách, bẩn)</option>
                                    <option value="Hư hỏng nặng">Hư hỏng nặng (Mất trang, nát)</option>
                                    <option value="Mất sách">Mất sách</option>
                                </select>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="small text-muted mb-1">Ghi chú lý do</label>
                                <input type="text" class="form-control form-control-sm" name="lydo[<?= htmlspecialchars($sach['macuonsach']) ?>]" placeholder="VD: Rách 5 trang">
                            </div>

                            <div class="col-md-3">
                                <label class="small text-muted mb-1">Tiền phạt hư hỏng (đ)</label>
                                <input type="number" class="form-control form-control-sm input-fine book-fine" id="fine-input-<?= htmlspecialchars($sach['macuonsach']) ?>" name="tienphat[<?= htmlspecialchars($sach['macuonsach']) ?>]" value="0" min="0" step="1000">
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Tổng kết & Khóa sổ -->
            <div class="d-flex justify-content-between align-items-center total-footer">
                <div>
                    <div>Tổng tiền trễ hạn: <span id="summaryLate" class="fw-bold"><?= number_format($totalLateFine) ?> đ</span></div>
                    <div>Tổng phạt hư hỏng: <span id="summaryDamage" class="fw-bold">0 đ</span></div>
                </div>
                <div class="text-end">
                    <div class="text-muted small">TỔNG CỘNG HÓA ĐƠN PHẠT SINH</div>
                    <div class="display-6 fw-bold text-danger" id="summaryTotal"><?= number_format($totalLateFine) ?> đ</div>
                </div>
            </div>

            <div class="text-end mt-4 mb-5">
                <button type="button" class="btn btn-secondary me-2 rounded-3" onclick="history.back()">
                    <i class="fa-solid fa-arrow-left"></i> Quay Lại
                </button>
                <button type="submit" class="btn btn-finish" id="btnSubmit">
                    <i class="fa-solid fa-check"></i> Hoàn Tất Trả Sách
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const totalLateFine = parseInt(document.getElementById('inTotalLateFine').value) || 0;
    const inputsFine = document.querySelectorAll('.book-fine');
    const summaryDamage = document.getElementById('summaryDamage');
    const summaryTotal = document.getElementById('summaryTotal');

    function calculateTotal() {
        let damageFine = 0;
        inputsFine.forEach(input => {
            let val = parseInt(input.value);
            if (!isNaN(val) && val > 0) damageFine += val;
        });

        summaryDamage.innerText = new Intl.NumberFormat('vi-VN').format(damageFine) + " đ";
        summaryTotal.innerText = new Intl.NumberFormat('vi-VN').format(totalLateFine + damageFine) + " đ";
    }

    inputsFine.forEach(input => {
        input.addEventListener('input', calculateTotal);
    });

    const conditionSelects = document.querySelectorAll('.condition-select');
    conditionSelects.forEach(select => {
        select.addEventListener('change', function() {
            const targetId = this.getAttribute('data-target-input');
            const inputFine = document.getElementById(targetId);
            if (this.value === 'Mất sách') {
                const price = this.getAttribute('data-price');
                if (inputFine) {
                    inputFine.value = price;
                    calculateTotal(); 
                }
            } else {
                if (inputFine && inputFine.value === this.getAttribute('data-price')) {
                    inputFine.value = 0;
                    calculateTotal();
                }
            }
        });
    });

    document.getElementById('frmTraSach').addEventListener('submit', function(e) {
        e.preventDefault();
        
        let fd = new FormData(this);
        let btnClick = document.getElementById('btnSubmit');
        
        btnClick.disabled = true;
        btnClick.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Đang xử lý...';

        fetch('/ajax/process_tra_sach.php', {
            method: 'POST',
            body: fd
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Đã lưu Phiếu Trả',
                    text: data.message,
                    confirmButtonText: 'Tuyệt vời'
                }).then(() => {
                    // Chuyển hướng về trang danh sách hóa đơn sau khi hoàn tất
                    window.location.href = '/admin/QuanLy/HoaDon/QL_HoaDon.php';
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: data.message
                });
                btnClick.disabled = false;
                btnClick.innerHTML = '<i class="fa-solid fa-check"></i> Hoàn Tất Trả Sách';
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Lỗi kết nối',
                text: 'Không thể kết nối tới server!'
            });
            btnClick.disabled = false;
            btnClick.innerHTML = '<i class="fa-solid fa-check"></i> Hoàn Tất Trả Sách';
        });
    });
</script>
</body>
</html>
