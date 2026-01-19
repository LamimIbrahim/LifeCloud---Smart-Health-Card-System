<<<<<<< Updated upstream
<?php
require_once __DIR__ . '/../auth.php';   // require_role use korar jonne

class AdminController
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        require_role('admin');
    }

    /* ---------- Dashboard ---------- */

    public function dashboard(): void
    {
        $stats = [
            'total_users'    => 0,
            'total_doctors'  => 0,
            'total_patients' => 0,
            'pending_users'  => 0
        ];

        $statSql = "
            SELECT 
                COUNT(*)                          AS total_users,
                SUM(role = 'doctor')              AS total_doctors,
                SUM(role = 'patient')             AS total_patients,
                SUM(status = 'pending')           AS pending_users
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

    /* ---------- Users page (pending + all) ---------- */

    public function users(): void
    {
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

    /* ---------- Doctors page (approved list + add) ---------- */

    public function doctors(): void
    {
        $message = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $message = $this->handleCreateDoctorPost();
        }

        $doctors = [];
        $sql = "
            SELECT 
                u.id,
                u.name,
                u.email,
                u.created_at,
                d.specialization,
                d.phone,
                d.room_no
            FROM users u
            JOIN doctors d ON d.user_id = u.id
            WHERE u.role = 'doctor' AND u.status = 'approved'
            ORDER BY u.created_at DESC
            LIMIT 100
        ";
        if ($res = $this->conn->query($sql)) {
            while ($row = $res->fetch_assoc()) {
                $doctors[] = $row;
            }
            $res->free();
        }

        require __DIR__ . '/../view/admin/doctors.php';
    }

    private function handleCreateDoctorPost(): string
    {
        $msg = '';

        $name           = trim($_POST['name'] ?? '');
        $email          = trim($_POST['email'] ?? '');
        $specialization = trim($_POST['specialization'] ?? '');
        $phone          = trim($_POST['phone'] ?? '');
        $roomNo         = trim($_POST['room_no'] ?? '');
        $password       = $_POST['password'] ?? '';

        $errors = [];

        if ($name === '') $errors[] = 'Name is required.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email required.';
        if ($specialization === '') $errors[] = 'Specialization is required.';
        if ($phone === '') $errors[] = 'Phone is required.';
        if ($roomNo === '') $errors[] = 'Room no is required.';
        if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';

        if (!empty($errors)) {
            return implode(' ', $errors);
        }

        $check = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param('s', $email);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            $check->close();
            return 'There is already an account with this email.';
        }
        $check->close();

        $this->conn->begin_transaction();
        try {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmtUser = $this->conn->prepare("
                INSERT INTO users (name, email, role, password, status, created_at)
                VALUES (?, ?, 'doctor', ?, 'approved', NOW())
            ");
            $stmtUser->bind_param('sss', $name, $email, $hash);
            $stmtUser->execute();
            $userId = $stmtUser->insert_id;
            $stmtUser->close();

            $stmtDoc = $this->conn->prepare("
                INSERT INTO doctors (user_id, specialization, phone, room_no, created_at)
                VALUES (?, ?, ?, ?, NOW())
            ");
            $stmtDoc->bind_param('isss', $userId, $specialization, $phone, $roomNo);
            $stmtDoc->execute();
            $stmtDoc->close();

            $this->conn->commit();
            $msg = 'Doctor created successfully.';
        } catch (\Throwable $e) {
            $this->conn->rollback();
            $msg = 'Failed to create doctor.';
        }

        return $msg;
    }
}
=======
<?php
require_once __DIR__ . '/../auth.php';   // require_role use korar jonne

class AdminController
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        require_role('admin');
    }

    /* ---------- Dashboard ---------- */

    public function dashboard(): void
    {
        $stats = [
            'total_users'    => 0,
            'total_doctors'  => 0,
            'total_patients' => 0,
            'pending_users'  => 0
        ];

        $statSql = "
            SELECT 
                COUNT(*)                          AS total_users,
                SUM(role = 'doctor')              AS total_doctors,
                SUM(role = 'patient')             AS total_patients,
                SUM(status = 'pending')           AS pending_users
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

    /* ---------- Users page (pending + all) ---------- */

    public function users(): void
    {
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

    /* ---------- Doctors page (approved list + add) ---------- */

    public function doctors(): void
    {
        $message = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $message = $this->handleCreateDoctorPost();
        }

        $doctors = [];
        $sql = "
            SELECT 
                u.id,
                u.name,
                u.email,
                u.created_at,
                d.specialization,
                d.phone,
                d.room_no
            FROM users u
            JOIN doctors d ON d.user_id = u.id
            WHERE u.role = 'doctor' AND u.status = 'approved'
            ORDER BY u.created_at DESC
            LIMIT 100
        ";
        if ($res = $this->conn->query($sql)) {
            while ($row = $res->fetch_assoc()) {
                $doctors[] = $row;
            }
            $res->free();
        }

        require __DIR__ . '/../view/admin/doctors.php';
    }

    private function handleCreateDoctorPost(): string
    {
        $msg = '';

        $name           = trim($_POST['name'] ?? '');
        $email          = trim($_POST['email'] ?? '');
        $specialization = trim($_POST['specialization'] ?? '');
        $phone          = trim($_POST['phone'] ?? '');
        $roomNo         = trim($_POST['room_no'] ?? '');
        $password       = $_POST['password'] ?? '';

        $errors = [];

        if ($name === '') $errors[] = 'Name is required.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email required.';
        if ($specialization === '') $errors[] = 'Specialization is required.';
        if ($phone === '') $errors[] = 'Phone is required.';
        if ($roomNo === '') $errors[] = 'Room no is required.';
        if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';

        if (!empty($errors)) {
            return implode(' ', $errors);
        }

        $check = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param('s', $email);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            $check->close();
            return 'There is already an account with this email.';
        }
        $check->close();

        $this->conn->begin_transaction();
        try {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmtUser = $this->conn->prepare("
                INSERT INTO users (name, email, role, password, status, created_at)
                VALUES (?, ?, 'doctor', ?, 'approved', NOW())
            ");
            $stmtUser->bind_param('sss', $name, $email, $hash);
            $stmtUser->execute();
            $userId = $stmtUser->insert_id;
            $stmtUser->close();

            $stmtDoc = $this->conn->prepare("
                INSERT INTO doctors (user_id, specialization, phone, room_no, created_at)
                VALUES (?, ?, ?, ?, NOW())
            ");
            $stmtDoc->bind_param('isss', $userId, $specialization, $phone, $roomNo);
            $stmtDoc->execute();
            $stmtDoc->close();

            $this->conn->commit();
            $msg = 'Doctor created successfully.';
        } catch (\Throwable $e) {
            $this->conn->rollback();
            $msg = 'Failed to create doctor.';
        }

        return $msg;
    }
}
>>>>>>> Stashed changes
