<?php
require_once __DIR__ . '/../database/ConnectDB.php';

$conn = ConnectDB::getInstance()->getConnection();

// Map slug tren URL sang ma the loai trong DB
$categoryMap = [
    'kinh-te'              => 'TL001',
    'van-hoc-trong-nuoc'   => 'TL002',
    'van-hoc-nuoc-ngoai'   => 'TL003',
    'doi-song'             => 'TL004',
    'thieu-nhi'            => 'TL005',
    'phat-trien-ban-than'  => 'TL006',
    'tin-hoc-ngoai-ngu'    => 'TL007',
    'chuyen-nganh'         => 'TL008'
];

$currentLoai = $_GET['loai'] ?? null;
$currentMaTL = null;

if ($currentLoai) {
    // Nếu URL truyền lên chữ (vd: 'kinh-te'), thì lấy mã từ từ điển
    if (isset($categoryMap[$currentLoai])) {
        $currentMaTL = $categoryMap[$currentLoai];
    }
    // Nếu URL truyền thẳng mã (vd: 'TL001'), thì xài luôn
    else {
        $currentMaTL = $currentLoai;
    }
}
$search = $_GET['search'] ?? '';
$filterTacGia  = $_GET['tacgia']   ?? '';
$filterNXB     = $_GET['nxb']      ?? '';
$filterNamFrom = $_GET['nam_from'] ?? '';
$filterNamTo   = $_GET['nam_to']   ?? '';
// --- LẤY TÊN THỂ LOẠI HOẶC TỪ KHÓA ĐỂ LÀM THANH ĐIỀU HƯỚNG ---
// Lấy danh sách thể loại cho dropdown
$sqlTheLoai = "SELECT matheloai, tentheloai FROM TheLoai ORDER BY tentheloai";
$resTheLoai = $conn->query($sqlTheLoai);
$theLoaiList = [];
while ($tl = $resTheLoai->fetch_assoc()) {
    $theLoaiList[] = $tl;
}
// Lấy danh sách nhà xuất bản từ bảng NhaXuatBan
$sqlNXB = "SELECT manxb, tennxb FROM NhaXuatBan ORDER BY tennxb";
$resNXB = $conn->query($sqlNXB);
$nxbList = [];
while ($nxb = $resNXB->fetch_assoc()) {
    $nxbList[] = $nxb;
}

$breadcrumbText = "Tất cả sách";
if ($currentMaTL) {
    $sqlCat = "SELECT tentheloai FROM TheLoai WHERE matheloai = ?";
    $stmtCat = $conn->prepare($sqlCat);
    $stmtCat->bind_param("s", $currentMaTL);
    $stmtCat->execute();
    $resCat = $stmtCat->get_result();
    if ($cat = $resCat->fetch_assoc()) {
        $breadcrumbText = $cat['tentheloai'];
    }
} elseif ($search != '') {
    $breadcrumbText = "Kết quả tìm kiếm: '" . $search . "'";
}
// -------------------------------------------------------------

// --- 1. CẤU HÌNH PHÂN TRANG CƠ BẢN ---
$limit = 12; // Số lượng sách hiển thị trên 1 trang
$page = isset($_GET['trang']) ? (int)$_GET['trang'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// --- 2. XÂY DỰNG CÂU TRUY VẤN ĐỘNG ---
$whereSql = "1=1"; // Điều kiện mặc định
$params = [];
$types = "";

if ($currentMaTL) {
    $whereSql .= " AND matheloai = ?";
    $params[] = $currentMaTL;
    $types .= "s";
}

if ($search != '') {
    $whereSql .= " AND tensach LIKE ?";
    $params[] = "%$search%";
    $types .= "s";
}

// Lọc theo tác giả – join với bảng TacGia qua matacgia
if ($filterTacGia != '') {
    $whereSql .= " AND matacgia IN (
        SELECT matacgia FROM tacgia WHERE tentacgia LIKE ?
    )";
    $params[] = "%$filterTacGia%";
    $types .= "s";
}

// Lọc theo nhà xuất bản – dùng cột manxb trong dausach
if ($filterMaNXB != '') {
    $whereSql .= " AND manxb = ?";
    $params[] = $filterMaNXB;
    $types .= "s";
}

// Lọc năm xuất bản
if ($filterNamFrom != '') {
    $whereSql .= " AND namxuatban >= ?";
    $params[] = (int)$filterNamFrom;
    $types .= "i";
}

if ($filterNamTo != '') {
    $whereSql .= " AND namxuatban <= ?";
    $params[] = (int)$filterNamTo;
    $types .= "i";
}

// --- 3. ĐẾM TỔNG SỐ LƯỢNG SÁCH ĐỂ TÍNH SỐ TRANG ---
$countSql = "SELECT COUNT(*) as total FROM dausach WHERE $whereSql";
$stmtCount = $conn->prepare($countSql);
if ($types !== "") {
    $stmtCount->bind_param($types, ...$params);
}
$stmtCount->execute();
$totalRows = $stmtCount->get_result()->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit); // Làm tròn lên để lấy tổng số trang

// --- 4. LẤY DỮ LIỆU SÁCH CHO TRANG HIỆN TẠI ---
$sql = "SELECT madausach, tensach, namxuatban, dongia, anhbia 
        FROM dausach 
        WHERE $whereSql 
        LIMIT ? OFFSET ?";

$dataParams = $params;
$dataTypes = $types;
$dataParams[] = $limit;
$dataParams[] = $offset;
$dataTypes .= "ii";

$stmt = $conn->prepare($sql);
$stmt->bind_param($dataTypes, ...$dataParams);
$stmt->execute();
$result = $stmt->get_result();

// --- 5. TẠO HÀM XÂY DỰNG LINK PHÂN TRANG ĐỂ DÙNG LẠI ---
function buildPageUrl($p)
{
    $queryParams = $_GET;
    $queryParams['trang'] = $p;
    return '?' . http_build_query($queryParams);
}

// --- 6. LOGIC PHÂN TRANG THU GỌN (DẤU BA CHẤM) ---
$pagesToDisplay = [];
$range = 1; // Số trang hiển thị xung quanh trang hiện tại

if ($totalPages > 0) {
    // Luôn hiển thị trang đầu tiên
    $pagesToDisplay[] = 1;

    // Chèn dấu ba chấm nếu trang đầu tiên cách quá xa phạm vi trang hiện tại
    if ($page > ($range + 2)) {
        $pagesToDisplay[] = '...';
    }

    // Xác định phạm vi xung quanh trang hiện tại
    $startRange = max(2, $page - $range);
    $endRange = min($totalPages - 1, $page + $range);

    for ($i = $startRange; $i <= $endRange; $i++) {
        $pagesToDisplay[] = $i;
    }

    // Chèn dấu ba chấm nếu trang cuối cùng cách quá xa phạm vi trang hiện tại
    if ($page < ($totalPages - $range - 1)) {
        $pagesToDisplay[] = '...';
    }

    // Luôn hiển thị trang cuối cùng (nếu tổng trang > 1)
    if ($totalPages > 1) {
        $pagesToDisplay[] = $totalPages;
    }
}
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="/assets/css/books.css">

<div class="books-page">

    <div class="mb-4" style="max-width: 1200px; margin: 25px auto 20px auto; position: relative;">
        <div class="border rounded bg-white d-flex align-items-center justify-content-between shadow-sm"
            style="padding: 12px 20px; font-size: 15px;">

            <div class="d-flex align-items-center">
                <a href="/index.php" class="text-decoration-none fw-semibold" style="color: #20c997;">Trang chủ</a>
                <i class="bi bi-chevron-right text-muted" style="margin: 0 8px; font-size: 12px;"></i>
                <span class="text-dark fw-bold"><?= htmlspecialchars($breadcrumbText) ?></span>
            </div>

            <button id="filterToggleBtn" class="filter-btn" onclick="toggleFilterPanel()">
                <i class="bi bi-funnel me-1"></i>
                Bộ lọc
            </button>
        </div>

        <div id="advancedFilterPanel" class="filter-panel">

            <div class="filter-panel-header">
                <span class="filter-panel-title">Bộ lọc nâng cao</span>
                <button class="filter-panel-close" onclick="toggleFilterPanel()">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <hr style="margin: 0 0 16px; border-color: #f0f0f0;">

            <form method="GET" action="/index.php" id="advancedFilterForm">
                <input type="hidden" name="page" value="books">
                <?php if ($currentLoai): ?>
                    <input type="hidden" name="loai" value="<?= htmlspecialchars($currentLoai) ?>">
                <?php endif; ?>

                <div class="filter-field mb-3">
                    <label>Tên sách</label>
                    <input type="text" name="search"
                        value="<?= htmlspecialchars($search) ?>"
                        placeholder="Nhập tên sách...">
                </div>

                <div class="filter-field mb-3">
                    <label>Tác giả</label>
                    <input type="text" name="tacgia"
                        value="<?= htmlspecialchars($filterTacGia) ?>"
                        placeholder="Nhập tên tác giả...">
                </div>

                <div class="filter-field mb-3">
                    <label>Thể loại</label>
                    <div class="select-wrap">
                        <select name="loai">
                            <option value="">-- Tất cả thể loại --</option>
                            <?php foreach ($theLoaiList as $tl): ?>
                                <option value="<?= htmlspecialchars($tl['matheloai']) ?>"
                                    <?= ($currentMaTL === $tl['matheloai']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($tl['tentheloai']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <i class="bi bi-chevron-down" style="color: #888; font-size: 13px;"></i>
                    </div>
                </div>

                <div class="filter-field mb-3">
                    <label>Nhà xuất bản</label>
                    <div class="select-wrap">
                        <select name="manxb">
                            <option value="">-- Tất cả NXB --</option>
                            <?php foreach ($nxbList as $nxb): ?>
                                <option value="<?= htmlspecialchars($nxb['manxb']) ?>"
                                    <?= ($filterMaNXB === $nxb['manxb']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($nxb['tennxb']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <i class="bi bi-chevron-down" style="color: #888; font-size: 13px;"></i>
                    </div>
                </div>

                <div class="filter-field mb-4">
                    <label>Năm xuất bản</label>
                    <div class="filter-year-range">
                        <input type="number" name="nam_from"
                            value="<?= htmlspecialchars($filterNamFrom) ?>"
                            placeholder="Từ năm" min="1900" max="2099">
                        <span>—</span>
                        <input type="number" name="nam_to"
                            value="<?= htmlspecialchars($filterNamTo) ?>"
                            placeholder="Đến năm" min="1900" max="2099">
                    </div>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="filter-btn-submit">Tìm kiếm</button>
                    <button type="button" class="filter-btn-reset" onclick="clearFilter()">Xóa lọc</button>
                </div>
            </form>
        </div>
    </div>

    <div class="books-grid-wrapper">
        <div id="books-list">
            <?php if ($result && $result->num_rows > 0): ?>
                <div class="books-grid">
                    <?php while ($book = $result->fetch_assoc()): ?>
                        <div class="book-card">
                            <a href="/index.php?page=book_detail&madausach=<?= htmlspecialchars($book['madausach'], ENT_QUOTES) ?>"
                                style="text-decoration: none; color: inherit; display: block;">
                                <div class="book-cover">
                                    <?php if (!empty($book['anhbia'])): ?>
                                        <img src="/assets/img/books/<?= htmlspecialchars($book['anhbia'], ENT_QUOTES) ?>"
                                            alt="<?= htmlspecialchars($book['tensach'], ENT_QUOTES) ?>">
                                    <?php else: ?>
                                        <img src="/assets/img/categories/booknew.png"
                                            alt="<?= htmlspecialchars($book['tensach'], ENT_QUOTES) ?>">
                                    <?php endif; ?>
                                </div>
                                <div class="book-title"><?= htmlspecialchars($book['tensach'], ENT_QUOTES) ?></div>
                            </a>
                        </div>
                    <?php endwhile; ?>
                </div> <?php else: ?>
                <div class="error-wrapper-full">
                    <?php include __DIR__ . '/error404.php'; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div id="pagination-content">
        <?php if ($totalPages > 1): ?>
            <div class="pagination-wrapper">
                <div class="pagination-advanced">
                    <?php if ($page > 1): ?>
                        <a href="<?= buildPageUrl($page - 1) ?>" class="page-link prev-page" data-page="<?= $page - 1 ?>">&larr;</a>
                    <?php endif; ?>

                    <?php foreach ($pagesToDisplay as $i): ?>
                        <?php if ($i === '...'): ?>
                            <span class="page-link ellipsis">...</span>
                        <?php else: ?>
                            <a href="<?= buildPageUrl($i) ?>"
                                class="page-link <?= ($i == $page) ? 'active' : '' ?>" data-page="<?= $i ?>">
                                <?= $i ?>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <?php if ($page < $totalPages): ?>
                        <a href="<?= buildPageUrl($page + 1) ?>" class="page-link next-page" data-page="<?= $page + 1 ?>">&rarr;</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
</div>

<script>
        function toggleFilterPanel() {
            const panel = document.getElementById('advancedFilterPanel');
            const btn = document.getElementById('filterToggleBtn');
            const isOpen = panel.classList.contains('show');
            panel.classList.toggle('show', !isOpen);
            btn.classList.toggle('active', !isOpen);
        }

        function clearFilter() {
            document.querySelectorAll('#advancedFilterForm input[type="text"], #advancedFilterForm input[type="number"]')
                .forEach(el => el.value = '');
            document.querySelectorAll('#advancedFilterForm select')
                .forEach(el => el.selectedIndex = 0);
        }

        document.addEventListener('click', function(e) {
            const panel = document.getElementById('advancedFilterPanel');
            const btn = document.getElementById('filterToggleBtn');
            if (panel && btn && !panel.contains(e.target) && !btn.contains(e.target)) {
                panel.classList.remove('show');
                btn.classList.remove('active');
            }
        });
</script>