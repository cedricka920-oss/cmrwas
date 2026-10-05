<?php
// ============================================================
// api.php - Backend REST / JSON API for CMRWAS
// ============================================================

session_start();
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/db.php';

// Helper to send JSON responses
function json_out($data, $code = 200) {
    http_response_code($code);
    echo json_encode($data);
    exit;
}

// Read JSON input or fallback to $_POST / $_GET
$raw_input = file_get_contents('php://input');
$json_data = json_decode($raw_input, true);
$input = is_array($json_data) ? array_merge($_REQUEST, $json_data) : $_REQUEST;

$action = $input['action'] ?? $_GET['action'] ?? '';

// Helper to get current user from session
function getCurrentUser() {
    return $_SESSION['cmrwas_user'] ?? null;
}

// Helper to enforce authentication
function requireAuth() {
    $user = getCurrentUser();
    if (!$user) {
        json_out(['success' => false, 'error' => 'Unauthorized. Please log in.'], 401);
    }
    return $user;
}

// Helper to enforce admin role
function requireAdmin() {
    $user = requireAuth();
    if ($user['role'] !== 'Administrator') {
        json_out(['success' => false, 'error' => 'Administrator access required.'], 403);
    }
    return $user;
}

// Helper to record transaction history
function recordHistory($pdo, $userId, $action, $requestId, $details) {
    try {
        $stmt = $pdo->prepare("
            INSERT INTO transaction_history (user_id, action, request_id, details)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$userId, $action, $requestId, $details]);
    } catch (Exception $e) {
        // Silently continue or log error
    }
}

// Helper to generate next sequential formatted code
function generateCode($pdo, $table, $idCol, $prefix) {
    $stmt = $pdo->query("SELECT MAX($idCol) AS max_id FROM $table");
    $row = $stmt->fetch();
    $nextId = ($row && $row['max_id']) ? ((int)$row['max_id'] + 1) : 1;
    return $prefix . '-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
}

// Route handlers
switch ($action) {

    // =========================================================
    // AUTHENTICATION
    // =========================================================

    case 'check_session':
        $user = getCurrentUser();
        if ($user) {
            json_out(['success' => true, 'user' => $user]);
        } else {
            json_out(['success' => false, 'user' => null]);
        }
        break;

    case 'login':
        $username = trim($input['username'] ?? '');
        $password = $input['password'] ?? '';

        if (empty($username) || empty($password)) {
            json_out(['success' => false, 'error' => 'Username and password are required.']);
        }

        $stmt = $pdo->prepare("
            SELECT * FROM users 
            WHERE (LOWER(username) = LOWER(?) OR LOWER(email) = LOWER(?)) 
              AND status = 'active' 
            LIMIT 1
        ");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();

        if (!$user) {
            json_out(['success' => false, 'error' => 'Invalid username or password.']);
        }

        // Verify password (supports hashed or plain text)
        $passwordValid = false;
        if (password_verify($password, $user['password'])) {
            $passwordValid = true;
        } elseif ($password === $user['password']) {
            $passwordValid = true;
        }

        if (!$passwordValid) {
            json_out(['success' => false, 'error' => 'Invalid username or password.']);
        }

        $userData = [
            'id' => (int)$user['id'],
            'name' => $user['full_name'],
            'username' => $user['username'],
            'email' => $user['email'],
            'role' => ($user['role'] === 'admin') ? 'Administrator' : 'User'
        ];

        $_SESSION['cmrwas_user'] = $userData;

        json_out([
            'success' => true,
            'message' => 'Login successful',
            'user' => $userData
        ]);
        break;

    case 'register':
        $name = trim($input['name'] ?? '');
        $username = trim($input['username'] ?? '');
        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';

        if (empty($name) || empty($username) || empty($email) || empty($password)) {
            json_out(['success' => false, 'error' => 'Please complete all fields.']);
        }

        if (strlen($username) < 4) {
            json_out(['success' => false, 'error' => 'Username must be at least 4 characters.']);
        }

        if (strlen($password) < 6) {
            json_out(['success' => false, 'error' => 'Password must be at least 6 characters.']);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            json_out(['success' => false, 'error' => 'Please enter a valid email address.']);
        }

        // Check duplicates
        $checkStmt = $pdo->prepare("SELECT id FROM users WHERE LOWER(username) = LOWER(?) LIMIT 1");
        $checkStmt->execute([$username]);
        if ($checkStmt->fetch()) {
            json_out(['success' => false, 'error' => 'Username already exists.']);
        }

        $checkEmail = $pdo->prepare("SELECT id FROM users WHERE LOWER(email) = LOWER(?) LIMIT 1");
        $checkEmail->execute([$email]);
        if ($checkEmail->fetch()) {
            json_out(['success' => false, 'error' => 'Email address already exists.']);
        }

        // Insert new user
        $insert = $pdo->prepare("
            INSERT INTO users (full_name, username, email, password, role, status)
            VALUES (?, ?, ?, ?, 'user', 'active')
        ");
        $insert->execute([$name, $username, $email, $password]);

        json_out([
            'success' => true,
            'message' => 'User account created successfully!'
        ]);
        break;

    case 'logout':
        $_SESSION = [];
        if (session_id() !== '') {
            session_destroy();
        }
        json_out(['success' => true, 'message' => 'Logged out successfully.']);
        break;

    // =========================================================
    // DASHBOARD
    // =========================================================

    case 'get_dashboard':
        $user = requireAuth();

        $isUser = ($user['role'] === 'User');
        $params = [];
        $whereSql = '';

        if ($isUser) {
            $whereSql = 'WHERE mr.user_id = ?';
            $params[] = $user['id'];
        }

        // Total, pending, in progress, completed counts
        $countQuery = "
            SELECT 
                COUNT(*) AS total,
                SUM(CASE WHEN mr.status = 'Pending' THEN 1 ELSE 0 END) AS pending,
                SUM(CASE WHEN mr.status = 'In Progress' THEN 1 ELSE 0 END) AS progress,
                SUM(CASE WHEN mr.status = 'Completed' THEN 1 ELSE 0 END) AS completed
            FROM maintenance_requests mr
            $whereSql
        ";
        $stmt = $pdo->prepare($countQuery);
        $stmt->execute($params);
        $stats = $stmt->fetch();

        // 5 most recent requests
        $recentQuery = "
            SELECT 
                mr.id,
                mr.request_code,
                mr.requester_name,
                mr.location,
                mr.category,
                mr.priority,
                mr.status,
                mr.created_at
            FROM maintenance_requests mr
            $whereSql
            ORDER BY mr.id DESC
            LIMIT 5
        ";
        $stmtRecent = $pdo->prepare($recentQuery);
        $stmtRecent->execute($params);
        $recent = $stmtRecent->fetchAll();

        json_out([
            'success' => true,
            'stats' => [
                'total' => (int)($stats['total'] ?? 0),
                'pending' => (int)($stats['pending'] ?? 0),
                'progress' => (int)($stats['progress'] ?? 0),
                'completed' => (int)($stats['completed'] ?? 0)
            ],
            'recent' => $recent
        ]);
        break;

    // =========================================================
    // MAINTENANCE REQUESTS
    // =========================================================

    case 'get_requests':
        $user = requireAuth();
        $isUser = ($user['role'] === 'User');

        $search = trim($input['search'] ?? '');
        $status = trim($input['status'] ?? 'All');
        $priority = trim($input['priority'] ?? 'All');

        $where = [];
        $params = [];

        if ($isUser) {
            $where[] = 'mr.user_id = ?';
            $params[] = $user['id'];
        }

        if (!empty($search)) {
            $where[] = '(LOWER(mr.request_code) LIKE ? OR LOWER(mr.requester_name) LIKE ? OR LOWER(mr.location) LIKE ? OR LOWER(mr.category) LIKE ?)';
            $like = '%' . strtolower($search) . '%';
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }

        if ($status !== 'All' && !empty($status)) {
            $where[] = 'mr.status = ?';
            $params[] = $status;
        }

        if ($priority !== 'All' && !empty($priority)) {
            $where[] = 'mr.priority = ?';
            $params[] = $priority;
        }

        $whereClause = !empty($where) ? ('WHERE ' . implode(' AND ', $where)) : '';

        $sql = "
            SELECT 
                mr.id,
                mr.request_code,
                mr.requester_name,
                mr.location,
                mr.category,
                mr.priority,
                mr.description,
                mr.status,
                mr.technician_id,
                COALESCE(t.name, 'Unassigned') AS technician,
                mr.user_id,
                u.username AS created_by,
                mr.created_at
            FROM maintenance_requests mr
            LEFT JOIN technicians t ON mr.technician_id = t.id
            LEFT JOIN users u ON mr.user_id = u.id
            $whereClause
            ORDER BY mr.id DESC
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $requests = $stmt->fetchAll();

        json_out([
            'success' => true,
            'requests' => $requests
        ]);
        break;

    case 'save_request':
        $user = requireAuth();

        $editId = trim($input['editRequestId'] ?? '');
        $requester = trim($input['requester'] ?? '');
        $location = trim($input['location'] ?? '');
        $category = trim($input['category'] ?? '');
        $priority = trim($input['priority'] ?? 'Medium');
        $description = trim($input['description'] ?? '');
        $technicianId = !empty($input['technician_id']) ? (int)$input['technician_id'] : null;

        if (empty($requester) || empty($location) || empty($description)) {
            json_out(['success' => false, 'error' => 'Please complete all required fields.']);
        }

        if (!empty($editId)) {
            // Edit existing request (Admin only)
            requireAdmin();

            // Find request
            $stmt = $pdo->prepare("SELECT * FROM maintenance_requests WHERE request_code = ? OR id = ? LIMIT 1");
            $stmt->execute([$editId, $editId]);
            $req = $stmt->fetch();

            if (!$req) {
                json_out(['success' => false, 'error' => 'Request not found.']);
            }

            $update = $pdo->prepare("
                UPDATE maintenance_requests 
                SET requester_name = ?, location = ?, category = ?, priority = ?, description = ?, technician_id = ?
                WHERE id = ?
            ");
            $update->execute([$requester, $location, $category, $priority, $description, $technicianId, $req['id']]);

            recordHistory(
                $pdo,
                $user['id'],
                'Updated Request',
                $req['id'],
                "Maintenance request {$req['request_code']} updated."
            );

            json_out(['success' => true, 'message' => 'Maintenance request updated successfully.']);
        } else {
            // Create new request
            $requestCode = generateCode($pdo, 'maintenance_requests', 'id', 'MR');

            $insert = $pdo->prepare("
                INSERT INTO maintenance_requests 
                (request_code, user_id, requester_name, location, category, priority, description, status, technician_id)
                VALUES (?, ?, ?, ?, ?, ?, ?, 'Pending', NULL)
            ");
            $insert->execute([
                $requestCode,
                $user['id'],
                $requester,
                $location,
                $category,
                $priority,
                $description
            ]);

            $newId = (int)$pdo->lastInsertId();
            if (!$newId) {
                $seqStmt = $pdo->query("SELECT MAX(id) AS mid FROM maintenance_requests");
                $newId = (int)($seqStmt->fetch()['mid'] ?? 0);
            }

            recordHistory(
                $pdo,
                $user['id'],
                'Created Request',
                $newId,
                "New maintenance request {$requestCode} created."
            );

            json_out(['success' => true, 'message' => 'Maintenance request saved successfully.', 'id' => $newId, 'request_code' => $requestCode]);
        }
        break;

    case 'update_status':
        $user = requireAdmin();

        $idOrCode = trim($input['id'] ?? '');
        $newStatus = trim($input['status'] ?? '');

        $validStatuses = ['Pending', 'Approved', 'Rejected', 'In Progress', 'Completed'];
        if (!in_array($newStatus, $validStatuses)) {
            json_out(['success' => false, 'error' => 'Invalid status value.']);
        }

        $stmt = $pdo->prepare("SELECT id, request_code FROM maintenance_requests WHERE request_code = ? OR id = ? LIMIT 1");
        $stmt->execute([$idOrCode, $idOrCode]);
        $req = $stmt->fetch();

        if (!$req) {
            json_out(['success' => false, 'error' => 'Request not found.']);
        }

        $update = $pdo->prepare("UPDATE maintenance_requests SET status = ? WHERE id = ?");
        $update->execute([$newStatus, $req['id']]);

        $actionName = match ($newStatus) {
            'Approved' => 'Approved Request',
            'Rejected' => 'Rejected Request',
            'In Progress' => 'Started Maintenance',
            'Completed' => 'Completed Request',
            default => 'Status Changed'
        };

        recordHistory(
            $pdo,
            $user['id'],
            $actionName,
            $req['id'],
            "Maintenance request status changed to {$newStatus}."
        );

        json_out(['success' => true, 'message' => "Request status updated to {$newStatus}."]);
        break;

    case 'delete_request':
        $user = requireAdmin();
        $idOrCode = trim($input['id'] ?? '');

        $stmt = $pdo->prepare("SELECT id, request_code FROM maintenance_requests WHERE request_code = ? OR id = ? LIMIT 1");
        $stmt->execute([$idOrCode, $idOrCode]);
        $req = $stmt->fetch();

        if (!$req) {
            json_out(['success' => false, 'error' => 'Request not found.']);
        }

        // Record history before deleting (request_id will become NULL due to ON DELETE SET NULL)
        recordHistory(
            $pdo,
            $user['id'],
            'Deleted Request',
            $req['id'],
            "Maintenance request {$req['request_code']} deleted."
        );

        $del = $pdo->prepare("DELETE FROM maintenance_requests WHERE id = ?");
        $del->execute([$req['id']]);

        json_out(['success' => true, 'message' => 'Maintenance request deleted successfully.']);
        break;

    // =========================================================
    // TECHNICIANS
    // =========================================================

    case 'get_technicians':
        requireAuth();
        $stmt = $pdo->query("SELECT * FROM technicians ORDER BY id ASC");
        $technicians = $stmt->fetchAll();
        json_out(['success' => true, 'technicians' => $technicians]);
        break;

    case 'save_technician':
        $user = requireAdmin();

        $name = trim($input['name'] ?? '');
        $specialization = trim($input['specialization'] ?? '');
        $contact = trim($input['contact'] ?? '');

        if (empty($name) || empty($contact)) {
            json_out(['success' => false, 'error' => 'Please complete all fields.']);
        }

        $code = generateCode($pdo, 'technicians', 'id', 'TECH');

        $stmt = $pdo->prepare("
            INSERT INTO technicians (technician_code, name, specialization, contact, status)
            VALUES (?, ?, ?, ?, 'Available')
        ");
        $stmt->execute([$code, $name, $specialization, $contact]);

        recordHistory(
            $pdo,
            $user['id'],
            'Added Technician',
            null,
            "New technician {$name} ({$code}) added."
        );

        json_out(['success' => true, 'message' => 'Technician added successfully.']);
        break;

    // =========================================================
    // EQUIPMENT
    // =========================================================

    case 'get_equipment':
        requireAuth();
        $stmt = $pdo->query("SELECT * FROM equipment ORDER BY id ASC");
        $equipment = $stmt->fetchAll();
        json_out(['success' => true, 'equipment' => $equipment]);
        break;

    case 'save_equipment':
        $user = requireAdmin();

        $name = trim($input['name'] ?? '');
        $category = trim($input['category'] ?? '');
        $location = trim($input['location'] ?? '');
        $condition = trim($input['condition'] ?? 'Good');

        if (empty($name) || empty($category) || empty($location)) {
            json_out(['success' => false, 'error' => 'Please complete all fields.']);
        }

        $code = generateCode($pdo, 'equipment', 'id', 'EQ');
        $status = ($condition === 'Good') ? 'Operational' : 'Under Maintenance';

        $stmt = $pdo->prepare("
            INSERT INTO equipment (equipment_code, equipment_name, category, location, condition_status, equipment_status)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$code, $name, $category, $location, $condition, $status]);

        recordHistory(
            $pdo,
            $user['id'],
            'Added Equipment',
            null,
            "New equipment {$name} ({$code}) added."
        );

        json_out(['success' => true, 'message' => 'Equipment added successfully.']);
        break;

    // =========================================================
    // TRANSACTION HISTORY
    // =========================================================

    case 'get_history':
        requireAdmin();

        $sql = "
            SELECT 
                th.id,
                th.created_at,
                COALESCE(u.username, 'admin') AS admin,
                th.action,
                COALESCE(mr.request_code, 'N/A') AS requestID,
                th.details
            FROM transaction_history th
            LEFT JOIN users u ON th.user_id = u.id
            LEFT JOIN maintenance_requests mr ON th.request_id = mr.id
            ORDER BY th.id DESC
        ";
        $stmt = $pdo->query($sql);
        $history = $stmt->fetchAll();

        // Format dates nicely
        foreach ($history as &$item) {
            $item['date'] = date('n/j/Y, g:i:s A', strtotime($item['created_at']));
        }

        json_out(['success' => true, 'history' => $history]);
        break;

    // =========================================================
    // REPORTS
    // =========================================================

    case 'get_reports':
        $user = requireAuth();
        $isUser = ($user['role'] === 'User');

        $whereSql = $isUser ? 'WHERE user_id = ?' : '';
        $params = $isUser ? [$user['id']] : [];

        $stmt = $pdo->prepare("
            SELECT 
                COUNT(*) AS total,
                SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) AS completed,
                SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) AS pending,
                SUM(CASE WHEN status = 'In Progress' THEN 1 ELSE 0 END) AS progress
            FROM maintenance_requests
            $whereSql
        ");
        $stmt->execute($params);
        $report = $stmt->fetch();

        json_out([
            'success' => true,
            'report' => [
                'total' => (int)($report['total'] ?? 0),
                'completed' => (int)($report['completed'] ?? 0),
                'pending' => (int)($report['pending'] ?? 0),
                'progress' => (int)($report['progress'] ?? 0)
            ]
        ]);
        break;

    default:
        json_out(['success' => false, 'error' => 'Invalid action: ' . htmlspecialchars($action)], 400);
        break;
}
