<?php
require_once __DIR__ . '/../../../login/auth.php';
require_admin_login();
require_admin_permission('TAIKHOAN');

require_once __DIR__ . '/../../../../database/ConnectDB.php';

header('Content-Type: application/json; charset=utf-8');

$conn = ConnectDB::getInstance()->getConnection();
$conn->set_charset('utf8mb4');

function h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$role = isset($_GET['role']) ? trim((string)$_GET['role']) : '';
if ($role === '') {
    echo json_encode(['success' => false, 'message' => 'Thiếu role.']);
    exit;
}

// Load all functions
$funcs = [];
try {
    $res = $conn->query('SELECT machucnang, tenchucnang FROM danhmucchucnang ORDER BY tenchucnang ASC');
    $funcs = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
} catch (Throwable $e) {
    $funcs = [];
}

// Load permissions for role
$perm = []; // [machucnang] => set(action)
try {
    $stmt = $conn->prepare('SELECT machucnang, COALESCE(hanhdong, "") AS hanhdong FROM ctquyen WHERE manhomquyen = ?');
    if ($stmt) {
        $stmt->bind_param('s', $role);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        foreach ($rows as $r) {
            $m = trim((string)($r['machucnang'] ?? ''));
            $a = strtoupper(trim((string)($r['hanhdong'] ?? '')));
            if ($m === '') {
                continue;
            }
            if (!isset($perm[$m])) {
                $perm[$m] = [];
            }
            if ($a === '' || $a === 'ALL') {
                $perm[$m]['READ'] = true;
                $perm[$m]['WRITE'] = true;
                $perm[$m]['DELETE'] = true;
            } else {
                $perm[$m][$a] = true;
            }
        }
    }
} catch (Throwable $e) {
    // ignore
}

$actions = [
    'READ' => 'Đọc',
    'WRITE' => 'Sửa',
    'DELETE' => 'Xóa',
];

$html = '';
foreach ($funcs as $f) {
    $code = trim((string)($f['machucnang'] ?? ''));
    if ($code === '') {
        continue;
    }
    $name = (string)($f['tenchucnang'] ?? $code);

    $html .= '<tr>';
    $html .= '<td class="text-start">' . h($name) . ' <span class="text-muted">(' . h($code) . ')</span></td>';

    foreach ($actions as $key => $_label) {
        $checked = isset($perm[$code][$key]) ? 'checked' : '';
        $html .= '<td>';
        $html .= '<input type="checkbox" class="form-check-input" data-func="' . h($code) . '" data-action="' . h($key) . '" ' . $checked . '>'; 
        $html .= '</td>';
    }

    $html .= '</tr>';
}

if ($html === '') {
    $html = '<tr><td colspan="4" class="text-muted">Chưa có chức năng nào trong danhmucchucnang.</td></tr>';
}

echo json_encode(['success' => true, 'html' => $html]);
