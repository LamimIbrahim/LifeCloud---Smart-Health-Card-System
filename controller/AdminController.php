<?php
require_once __DIR__ . '/../auth.php';   // require_role use korar jonne

class AdminController {

    private $conn;

    public function __construct($conn){
        $this->conn = $conn;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        require_role('admin');
    }

    // /admin_dashboard.php
    public function dashboard(){
        $stats = [
            'total_users'    => 0,
            'total_doctors'  => 0,
            'total_patients' => 0,
            'pending_users'  => 0
        ];

        $statSql = "
            SELECT 
                COUNT(*)                              AS total_users,
                SUM(role = 'doctor')                  AS total_doctors,
                SUM(role = 'patient')                 AS total_patients,
                SUM(status = 'pending')               AS pending_users
            FROM users
        ";
        if ($res = $this->conn->query($statSql)) {
            $row = $res->fetch_assoc();
            if ($row) {
                $stats = $row;
            }
            $res->free();
        }

        $recentUsers = [];
        $recentSql = "
            SELECT id, name, email, role, status, created_at 
            FROM users
            ORDER BY created_at DESC
            LIMIT 5
        ";
        if ($res = $this->conn->query($recentSql)) {
            while ($row = $res->fetch_assoc()) {
                $recentUsers[] = $row;
            }
            $res->free();
        }

        require __DIR__ . '/../view/admin/dashboard.php';
    }

    // /admin_users.php
    public function users(){
        $msg = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['action'])) {
            $userId = (int)$_POST['user_id'];
            $action = $_POST['action'];

            if ($action === 'approve') {
                $newStatus = 'approved';
            } elseif ($action === 'reject') {
                $newStatus = 'rejected';
            } else {
                $newStatus = null;
            }

            if ($newStatus !== null) {
                $stmt = $this->conn->prepare("UPDATE users SET status = ? WHERE id = ?");
                $stmt->bind_param("si", $newStatus, $userId);
                if ($stmt->execute()) {
                    $msg = "User status updated to {$newStatus}.";
                } else {
                    $msg = "Failed to update status.";
                }
                $stmt->close();
            }
        }

        $pendingUsers = [];
        $allUsers     = [];

        $sqlPending = "
            SELECT id, name, email, role, status, created_at
            FROM users
            WHERE status = 'pending'
            ORDER BY created_at DESC
        ";
        if ($res = $this->conn->query($sqlPending)) {
            while ($row = $res->fetch_assoc()) {
                $pendingUsers[] = $row;
            }
            $res->free();
        }

        $sqlAll = "
            SELECT id, name, email, role, status, created_at
            FROM users
            ORDER BY created_at DESC
            LIMIT 50
        ";
        if ($res = $this->conn->query($sqlAll)) {
            while ($row = $res->fetch_assoc()) {
                $allUsers[] = $row;
            }
            $res->free();
        }

        require __DIR__ . '/../view/admin/users.php';
    }
}
