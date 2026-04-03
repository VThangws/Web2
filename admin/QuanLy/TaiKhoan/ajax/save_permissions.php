<?php
require_once __DIR__ . '/../../../login/auth.php';
require_admin_login();
require_admin_permission('TAIKHOAN');

require_once __DIR__ . '/../../../../database/ConnectDB.php';

header('Content-Type: application/json; charset=utf-8');

$conn = ConnectDB::getInstance()->getConnection();
$conn->set_charset('utf8mb4');

$role = isset($_POST['role']) ? trim((string)$_POST['role']) : '';
if ($role === '') {
    echo json_encode(['success' => false, 'message' => 'Thiếu role.']);
    exit;
}

$allowedActions = ['READ' => true, 'WRITE' => true, 'DELETE' => true];
$input = $_POST['permissions'] ?? [];
if (!is_array($input)) {
    $input = [];
}

try {
    // Role must exist
    $stmtRole = $conn->prepare('SELECT 1 FROM nhomquyen WHERE manhomquyen = ? LIMIT 1');
    if (!$stmtRole) {
        throw new RuntimeException('Không thể kiểm tra nhóm quyền.');
    }
    $stmtRole->bind_param('s', $role);
    $stmtRole->execute();
    $roleOk = $stmtRole->get_result()->num_rows > 0;
    $stmtRole->close();
    if (!$roleOk) {
        throw new RuntimeException('Nhóm quyền không tồn tại: ' . $role);
    }

    // Load all function codes (to do a full replace)
    $funcCodes = [];
    $res = $conn->query('SELECT machucnang FROM danhmucchucnang');
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $c = trim((string)($row['machucnang'] ?? ''));
            if ($c !== '') {
                $funcCodes[] = $c;
            }
        }
    }

    $conn->begin_transaction();

    // Delete old permissions for this role (only for known functions)
    if (count($funcCodes) > 0) {
        $placeholders = implode(',', array_fill(0, count($funcCodes), '?'));
        $sqlDel = 'DELETE FROM ctquyen WHERE manhomquyen = ? AND machucnang IN (' . $placeholders . ')';
        $stmtDel = $conn->prepare($sqlDel);
        if (!$stmtDel) {
            throw new RuntimeException('Không thể chuẩn bị câu lệnh xóa quyền.');
        }

        // build dynamic params
        $types = 's' . str_repeat('s', count($funcCodes));
        $params = array_merge([$role], $funcCodes);

        $bindNames = [];
        $bindNames[] = $types;
        for ($i = 0; $i < count($params); $i++) {
            $bindNames[] = &$params[$i];
        }
        call_user_func_array([$stmtDel, 'bind_param'], $bindNames);
        if (!$stmtDel->execute()) {
            throw new RuntimeException($stmtDel->error ?: 'Xóa quyền thất bại.');
        }
        $stmtDel->close();
    }

    $stmtIns = $conn->prepare('INSERT INTO ctquyen (manhomquyen, machucnang, hanhdong) VALUES (?, ?, ?)');
    if (!$stmtIns) {
        throw new RuntimeException('Không thể chuẩn bị câu lệnh thêm quyền.');
    }

    // Insert new permissions
    foreach ($input as $machucnang => $actions) {
        $machucnang = trim((string)$machucnang);
        if ($machucnang === '') {
            continue;
        }
        if (!is_array($actions)) {
            continue;
        }

        $set = [];
        foreach ($actions as $a) {
            $a = strtoupper(trim((string)$a));
            if (isset($allowedActions[$a])) {
                $set[$a] = true;
            }
        }

        if (count($set) === 0) {
            continue;
        }

        // If all 3 actions => store ALL
        $toInsert = [];
        if (isset($set['READ'], $set['WRITE'], $set['DELETE'])) {
            $toInsert = ['ALL'];
        } else {
            $toInsert = array_keys($set);
        }

        foreach ($toInsert as $hanhdong) {
            $stmtIns->bind_param('sss', $role, $machucnang, $hanhdong);
            if (!$stmtIns->execute()) {
                throw new RuntimeException($stmtIns->error ?: 'Lưu quyền thất bại.');
            }
        }
    }

    $stmtIns->close();

    $conn->commit();

    echo json_encode(['success' => true, 'message' => 'Đã lưu phân quyền cho nhóm ' . $role]);
} catch (Throwable $e) {
    try {
        if ($conn->errno === 0) {
            // noop
        }
        $conn->rollback();
    } catch (Throwable $e2) {
        // ignore
    }

    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
