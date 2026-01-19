<?php
require_once __DIR__ . '/../auth.php';

class DoctorController {

    private $conn;

    public function __construct($conn){
        $this->conn = $conn;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        require_role('doctor');
    }

    // /doctor_portal.php
    public function portal(){
        $doctorId   = (int)($_SESSION['user_id'] ?? 0);
        $doctorName = $_SESSION['user_name'] ?? 'Doctor';

        $q = trim($_GET['q'] ?? '');

        // recent patients (only approved)
        $recent = [];
        $r = $this->conn->query("
            SELECT u.id, u.name, p.id AS patient_id, p.health_card_no
            FROM users u
            JOIN patients p ON p.user_id = u.id
            WHERE u.status = 'approved'
            ORDER BY p.id DESC
            LIMIT 5
        ");
        if ($r) {
            $recent = $r->fetch_all(MYSQLI_ASSOC);
        }

        $patient       = null;
        $history       = [];
        $full_history  = [];
        $msg_action    = "";
        $pid_for_full  = null;

        // search only approved patients
        if ($q !== '') {
            $stmt = $this->conn->prepare("
                SELECT 
                    u.id AS user_id,
                    u.name,
                    u.email,
                    p.id AS patient_id,
                    p.health_card_no,
                    p.date_of_birth,
                    p.gender,
                    p.address
                FROM users u
                JOIN patients p ON p.user_id = u.id
                WHERE u.status = 'approved'
                  AND (p.health_card_no = ?
                       OR u.name LIKE CONCAT('%', ?, '%'))
                LIMIT 1
            ");
            $stmt->bind_param('ss', $q, $q);
            $stmt->execute();
            $res     = $stmt->get_result();
            $patient = $res->fetch_assoc();
            $stmt->close();

            if ($patient) {
                $pid = (int)$patient['patient_id'];

                $stmt2 = $this->conn->prepare("
                    SELECT title, visit_date, doctor_name
                    FROM medical_history
                    WHERE patient_id = ?
                    ORDER BY visit_date DESC, id DESC
                    LIMIT 5
                ");
                $stmt2->bind_param('i', $pid);
                $stmt2->execute();
                $history = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
                $stmt2->close();
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['patient_id'])) {

            $pid = (int)$_POST['patient_id'];

            $chk = $this->conn->prepare("SELECT id FROM patients WHERE id = ?");
            $chk->bind_param("i", $pid);
            $chk->execute();
            $chk->store_result();

            if ($chk->num_rows === 0) {
                $msg_action = "Invalid patient.";
            } else {
                $action = $_POST['action'];

                if ($action === 'add_prescription') {

                    $title      = trim($_POST['title'] ?? 'Prescription');
                    $notes      = trim($_POST['notes'] ?? '');
                    $visit_date = date('Y-m-d');

                    $stmt = $this->conn->prepare("
                        INSERT INTO medical_history
                            (patient_id, doctor_id, title, notes, visit_date, entry_type, doctor_name)
                        VALUES (?, ?, ?, ?, ?, 'prescription', ?)
                    ");
                    $stmt->bind_param("iissss", $pid, $doctorId, $title, $notes, $visit_date, $doctorName);
                    $stmt->execute();
                    $stmt->close();
                    $msg_action = "Prescription added.";

                } elseif ($action === 'upload_report') {

                    $title       = trim($_POST['title'] ?? 'Report');
                    $visit_date  = date('Y-m-d');
                    $report_path = null;

                    if (!empty($_FILES['report_file']['name'])) {
                        $uploadDir = 'uploads/';
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }
                        $fileName   = time() . '_' . basename($_FILES['report_file']['name']);
                        $targetPath = $uploadDir . $fileName;
                        if (move_uploaded_file($_FILES['report_file']['tmp_name'], $targetPath)) {
                            $report_path = $targetPath;
                        }
                    }

                    $stmt = $this->conn->prepare("
                        INSERT INTO medical_history
                            (patient_id, doctor_id, title, notes, visit_date, report_file, entry_type, doctor_name)
                        VALUES (?, ?, ?, '', ?, ?, 'test', ?)
                    ");
                    $stmt->bind_param("iissss", $pid, $doctorId, $title, $visit_date, $report_path, $doctorName);
                    $stmt->execute();
                    $stmt->close();
                    $msg_action = "Test report uploaded.";

                } elseif ($action === 'schedule_appointment') {

                    $title = trim($_POST['title'] ?? 'Appointment');
                    $date  = $_POST['visit_date'] ?? date('Y-m-d');

                    $stmt = $this->conn->prepare("
                        INSERT INTO medical_history
                            (patient_id, doctor_id, title, notes, visit_date, entry_type, doctor_name)
                        VALUES (?, ?, ?, 'Scheduled appointment', ?, 'appointment', ?)
                    ");
                    $stmt->bind_param("iisss", $pid, $doctorId, $title, $date, $doctorName);
                    $stmt->execute();
                    $stmt->close();
                    $msg_action = "Appointment scheduled.";

                } elseif ($action === 'view_full') {

                    $pid_for_full = $pid;
                }
            }
            $chk->close();
        }

        if (!empty($pid_for_full)) {
            $stmt3 = $this->conn->prepare("
                SELECT title, notes, visit_date, doctor_name, report_file
                FROM medical_history
                WHERE patient_id = ?
                ORDER BY visit_date DESC, id DESC
            ");
            $stmt3->bind_param("i", $pid_for_full);
            $stmt3->execute();
            $full_history = $stmt3->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt3->close();
        }

        require __DIR__ . '/../view/doctor/portal.php';
    }
}