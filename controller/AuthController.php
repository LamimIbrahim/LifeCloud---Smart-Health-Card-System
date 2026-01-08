<?php
require_once __DIR__ . '/../model/User.php';

class AuthController {

    private $conn;

    public function __construct($conn){
        $this->conn = $conn;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login(){
        $msg = "";

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $login    = trim($_POST['uid'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($login === '' || $password === '') {
                $msg = "Please enter email/ID and password.";
            } else {
                $userModel = new User($this->conn);
                $user = $userModel->findByLogin($login);

                if (!$user) {
                    $msg = "Account not found!";
                } elseif (!password_verify($password, $user['password'])) {
                    $msg = "Wrong password!";
                } elseif ($user['status'] !== 'approved') {
                    if ($user['status'] === 'pending') {
                        $msg = "Your account is pending admin approval.";
                    } elseif ($user['status'] === 'rejected') {
                        $msg = "Your registration was rejected. Please contact admin.";
                    } else {
                        $msg = "Your account is not active.";
                    }
                } else {
                    session_regenerate_id(true);
                    $_SESSION['user_id']   = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['role']      = $user['role'];

                    if ($user['role'] === 'admin') {
                        header("Location: admin_dashboard.php");
                    } elseif ($user['role'] === 'doctor') {
                        header("Location: doctor_portal.php");
                    } else {
                        header("Location: dashboard.php");
                    }
                    exit;
                }
            }
        }

        $message = $msg;
        require __DIR__ . '/../view/login.php';
    }

    public function register(){
        $msg = "";
        $msg_type = "";

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $fullName = trim($_POST['fullName'] ?? '');
            $gender   = $_POST['gender'] ?? '';
            $dob      = $_POST['dob'] ?? '';
            $email    = trim($_POST['email'] ?? '');
            $address  = trim($_POST['address'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirmPassword'] ?? '';
            $role     = $_POST['role'] ?? 'patient';

            $validRoles = ['admin','doctor','patient'];
            if (!in_array($role, $validRoles, true)) {
                $role = 'patient';
            }

            if ($fullName === '' || $gender === '' || $dob === '' || $email === '' ||
                $address === '' || $password === '' || $confirmPassword === '' || $role === '') {

                $msg = "Every field needs to be filled!";
                $msg_type = "error";

            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

                $msg = "Enter correct email!";
                $msg_type = "error";

            } elseif ($password !== $confirmPassword) {

                $msg = "Password is not matching!";
                $msg_type = "error";

            } else {

                $checkEmail = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
                if ($checkEmail) {
                    $checkEmail->bind_param("s", $email);
                    $checkEmail->execute();
                    $checkEmail->store_result();

                    if ($checkEmail->num_rows > 0) {
                        $msg = "There is an account using this email!";
                        $msg_type = "error";
                    } else {

                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                        $stmt = $this->conn->prepare(
                            "INSERT INTO users (name, email, password, role, status)
                             VALUES (?, ?, ?, ?, 'pending')"
                        );
                        if ($stmt) {
                            $stmt->bind_param(
                                "ssss",
                                $fullName,
                                $email,
                                $hashed_password,
                                $role
                            );

                            if ($stmt->execute()) {

                                if ($role === 'patient') {
                                    $userId = $stmt->insert_id;

                                    $healthCard = 'LC' . date('ymd') . sprintf('%04d', random_int(0, 9999));
                                    $try = 0;
                                    $maxTry = 5;
                                    do {
                                        $check = $this->conn->prepare("SELECT id FROM patients WHERE health_card_no = ?");
                                        $check->bind_param("s", $healthCard);
                                        $check->execute();
                                        $check->store_result();
                                        if ($check->num_rows > 0) {
                                            $healthCard = 'LC' . date('ymd') . sprintf('%04d', random_int(0, 9999));
                                        }
                                        $try++;
                                        $check->close();
                                    } while ($try < $maxTry);

                                    $patientStmt = $this->conn->prepare(
                                        "INSERT INTO patients (user_id, date_of_birth, gender, address, health_card_no)
                                         VALUES (?, ?, ?, ?, ?)"
                                    );
                                    if ($patientStmt) {
                                        $patientStmt->bind_param(
                                            "issss",
                                            $userId,
                                            $dob,
                                            $gender,
                                            $address,
                                            $healthCard
                                        );
                                        $patientStmt->execute();
                                        $patientStmt->close();
                                    }
                                }

                                $msg = "Registration successful! Wait for admin approval before login.";
                                $msg_type = "success";

                            } else {
                                $msg = "Insert error: " . $stmt->error;
                                $msg_type = "error";
                            }
                            $stmt->close();
                        } else {
                            $msg = "DB prepare error: " . $this->conn->error;
                            $msg_type = "error";
                        }
                    }
                    $checkEmail->close();
                } else {
                    $msg = "DB prepare error: " . $this->conn->error;
                    $msg_type = "error";
                }
            }
        }

        $message      = $msg;
        $message_type = $msg_type;
        require __DIR__ . '/../view/register.php';
    }
}