<?php
session_start();
file_put_contents(__DIR__ . '/debug_session_admin.txt', print_r($_SESSION, true));

header('Content-Type: application/json');

require_once __DIR__ . '/config/db.php';

$conn = $conn ?? new mysqli('localhost', 'root', '', 'lifecloud_db');
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'DB connection failed']);
    exit;
}

// Session structure: [user_id], [user_name], [role]
if (!isset($_SESSION['role']) || !isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized: no session']);
    exit;
}

$role = strtolower($_SESSION['role']);

if ($role !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized: admin only']);
    exit;
}

$action = $_POST['action'] ?? '';
$userId = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;

if (!$action || !$userId) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

// Approve
if ($action === 'approve') {
    $stmt = $conn->prepare("UPDATE users SET status = 'approved' WHERE id = ?");
    $stmt->bind_param('i', $userId);
    $ok = $stmt->execute();
    $stmt->close();

    echo json_encode([
        'success'    => $ok,
        'message'    => $ok ? 'User approved successfully' : 'Failed to approve user',
        'user_id'    => $userId,
        'new_status' => 'approved'
    ]);
    exit;
}

// Reject
if ($action === 'reject') {
    $stmt = $conn->prepare("UPDATE users SET status = 'rejected' WHERE id = ?");
    $stmt->bind_param('i', $userId);
    $ok = $stmt->execute();
    $stmt->close();

    echo json_encode([
        'success'    => $ok,
        'message'    => $ok ? 'User rejected successfully' : 'Failed to reject user',
        'user_id'    => $userId,
        'new_status' => 'rejected'
    ]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Unknown action']);
