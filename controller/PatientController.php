<?php
require_once __DIR__ . '/../auth.php';

class PatientController {

    private $conn;

    public function __construct($conn){
        $this->conn = $conn;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        require_role('patient');
    }

    // /dashboard.php
    public function dashboard(){
        $userId = (int)($_SESSION['user_id'] ?? 0);

        // patient profile
        $profile = null;
        $sql = "
            SELECT 
                u.name,
                u.email,
                p.id         AS patient_id,
                p.health_card_no,
                p.date_of_birth,
                p.gender,
                p.address,
                u.created_at
            FROM users u
            JOIN patients p ON p.user_id = u.id
            WHERE u.id = ?
            LIMIT 1
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $profile = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        // recent visits
        $recentVisits = [];
        if ($profile) {
            $patientId = (int)$profile['patient_id'];
            $sql = "
                SELECT 
                    mh.entry_type,
                    mh.title,
                    mh.visit_date,
                    d.name AS doctor_name
                FROM medical_history mh
                JOIN users d ON d.id = mh.doctor_id
                WHERE mh.patient_id = ?
                ORDER BY mh.visit_date DESC, mh.id DESC
                LIMIT 3
            ";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $patientId);
            $stmt->execute();
            $recentVisits = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
        }

        require __DIR__ . '/../view/patient/dashboard.php';
    }

    // common helper: get patient id from logged in user
    private function getPatientIdFromUser($userId){
        $patientId = null;
        $stmt = $this->conn->prepare("
            SELECT p.id
            FROM patients p
            JOIN users u ON p.user_id = u.id
            WHERE u.id = ?
            LIMIT 1
        ");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        if ($res) {
            $patientId = (int)$res['id'];
        }
        $stmt->close();
        return $patientId;
    }

    // /patient_history.php
    public function history(){
        $userId    = (int)($_SESSION['user_id'] ?? 0);
        $patientId = $this->getPatientIdFromUser($userId);

        $history = [];
        if ($patientId) {
            $stmt = $this->conn->prepare("
                SELECT title, notes, visit_date, entry_type, doctor_name, report_file
                FROM medical_history
                WHERE patient_id = ?
                ORDER BY visit_date DESC, id DESC
            ");
            $stmt->bind_param("i", $patientId);
            $stmt->execute();
            $history = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
        }

        require __DIR__ . '/../view/patient/history.php';
    }

    // /patient_appointments.php
    public function appointments(){
        $userId    = (int)($_SESSION['user_id'] ?? 0);
        $patientId = $this->getPatientIdFromUser($userId);

        $appointments = [];
        if ($patientId) {
            $stmt = $this->conn->prepare("
                SELECT title, visit_date, doctor_name
                FROM medical_history
                WHERE patient_id = ? AND entry_type = 'appointment'
                ORDER BY visit_date DESC, id DESC
            ");
            $stmt->bind_param("i", $patientId);
            $stmt->execute();
            $appointments = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
        }

        require __DIR__ . '/../view/patient/appointments.php';
    }

    // /patient_tests.php
    public function tests(){
        $userId    = (int)($_SESSION['user_id'] ?? 0);
        $patientId = $this->getPatientIdFromUser($userId);

        $tests = [];
        if ($patientId) {
            $stmt = $this->conn->prepare("
                SELECT title, visit_date, doctor_name, report_file
                FROM medical_history
                WHERE patient_id = ? AND entry_type = 'test'
                ORDER BY visit_date DESC, id DESC
            ");
            $stmt->bind_param("i", $patientId);
            $stmt->execute();
            $tests = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
        }

        require __DIR__ . '/../view/patient/tests.php';
    }

    // /patient_prescriptions.php
    public function prescriptions(){
        $userId    = (int)($_SESSION['user_id'] ?? 0);
        $patientId = $this->getPatientIdFromUser($userId);

        $prescriptions = [];
        if ($patientId) {
            $stmt = $this->conn->prepare("
                SELECT title, notes, visit_date, doctor_name
                FROM medical_history
                WHERE patient_id = ? AND entry_type = 'prescription'
                ORDER BY visit_date DESC, id DESC
            ");
            $stmt->bind_param("i", $patientId);
            $stmt->execute();
            $prescriptions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
        }

        require __DIR__ . '/../view/patient/prescriptions.php';
    }
}