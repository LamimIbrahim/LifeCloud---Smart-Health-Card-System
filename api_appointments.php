<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/config/db.php';

$conn = $conn ?? new mysqli('localhost', 'root', '', 'lifecloud_db');
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'DB connection failed']);
    exit;
}

// Only logged-in patient / doctor
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$user = $_SESSION['user'];
$userId = (int)$user['id'];
$role   = $user['role']; // patient / doctor / admin

// Simple: patient dekhe nijer appointment, doctor dekhe tar patient der appointment
if ($role === 'patient') {
    $stmt = $conn->prepare("SELECT a.id, a.date, a.title, d.name AS doctor_name 
                            FROM appointments a
                            LEFT JOIN users d ON a.doctor_id = d.id
                            WHERE a.patient_id = ?
                            ORDER BY a.date DESC");
    $stmt->bind_param('i', $userId);
} elseif ($role === 'doctor') {
    $stmt = $conn->prepare("SELECT a.id, a.date, a.title, p.name AS patient_name 
                            FROM appointments a
                            LEFT JOIN users p ON a.patient_id = p.id
                            WHERE a.doctor_id = ?
                            ORDER BY a.date DESC");
    $stmt->bind_param('i', $userId);
} else {
    // admin – show last 50
    $stmt = $conn->prepare("SELECT a.id, a.date, a.title,
                                   CONCAT(p.name,' → ',d.name) AS relation
                            FROM appointments a
                            LEFT JOIN users p ON a.patient_id = p.id
                            LEFT JOIN users d ON a.doctor_id = d.id
                            ORDER BY a.date DESC
                            LIMIT 50");
}

$stmt->execute();
$result = $stmt->get_result();

$rows = [];
while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
}
$stmt->close();

echo json_encode([
    'success' => true,
    'count'   => count($rows),
    'data'    => $rows
]);
