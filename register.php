<?php
session_start();
include 'db_connect.php';

$msg = "";
$msg_type = "";

/* show errors while developing (optional) */
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $fullName = trim($_POST['fullName'] ?? '');
    $gender   = $_POST['gender'] ?? '';
    $dob      = $_POST['dob'] ?? '';
    $email    = trim($_POST['email'] ?? '');
    $address  = trim($_POST['address'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';
    $role     = $_POST['role'] ?? 'patient';   // NEW

    // role নিরাপদ
    $validRoles = ['admin','doctor','patient'];
    if (!in_array($role, $validRoles, true)) {
        $role = 'patient';
    }

    // PHP validation
    if ($fullName === '' || $gender === '' || $dob === '' || $email === '' ||
        $address === '' || $password === '' || $confirmPassword === '' || $role === '') {

        $msg = "Every field needs to be filled!";
        $msg_type = "error";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $msg = "enter correct email!";
        $msg_type = "error";

    } elseif ($password !== $confirmPassword) {

        $msg = "password is not matching!";
        $msg_type = "error";

    } else {

        // email already exists?
        $checkEmail = $conn->prepare("SELECT id FROM users WHERE email = ?");
        if (!$checkEmail) {
            $msg = "DB prepare error: " . $conn->error;
            $msg_type = "error";
        } else {
            $checkEmail->bind_param("s", $email);
            $checkEmail->execute();
            $checkEmail->store_result();

            if ($checkEmail->num_rows > 0) {
                $msg = "there is a account using this email!";
                $msg_type = "error";
            } else {

                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $conn->prepare(
                    "INSERT INTO users (full_name, gender, dob, email, address, password, role)
                     VALUES (?, ?, ?, ?, ?, ?, ?)"
                );
                if (!$stmt) {
                    $msg = "DB prepare error: " . $conn->error;
                    $msg_type = "error";
                } else {
                    $stmt->bind_param(
                        "sssssss",
                        $fullName,
                        $gender,
                        $dob,
                        $email,
                        $address,
                        $hashed_password,
                        $role
                    );

                    if ($stmt->execute()) {
                        $msg = "Registration Successful! Now LOGIN!";
                        $msg_type = "success";
                    } else {
                        $msg = "Insert error: " . $stmt->error;
                        $msg_type = "error";
                    }
                    $stmt->close();
                }
            }
            $checkEmail->close();
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>LifeCloud | Registration</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    .alert {
      padding: 10px;
      margin: 12px 22px 0;
      border-radius: 8px;
      text-align: center;
      font-weight: 700;
    }
    .alert.error {
      background-color: rgba(255,0,0,.12);
      color: #ffd1d1;
      border:1px solid rgba(255,255,255,.15);
    }
    .alert.success {
      background-color: rgba(0,255,120,.12);
      color: #c8ffde;
      border:1px solid rgba(255,255,255,.15);
    }
  </style>
</head>
<body>
  <main class="wrap">
    <section class="card">
      <header class="hero">
  <div class="brand">
    <img class="brand-logo" src="logo.png" alt="LifeCloud logo">
    <div class="brand-text">
      <h1>LifeCloud</h1>
      <p>Health on Cloud</p>
    </div>
  </div>
  <h2 class="title">Registration</h2>
</header>


      <?php if ($msg !== ""): ?>
        <div class="alert <?php echo $msg_type; ?>"><?php echo $msg; ?></div>
      <?php endif; ?>

      <form method="POST" action="" id="regForm" novalidate>
        <div class="grid">
          <div class="field full">
            <label for="fullName">Full Name</label>
            <input id="fullName" name="fullName" type="text" required minlength="3" placeholder="Enter full name" />
            <small class="error" id="err-fullName"></small>
          </div>

          <div class="field">
            <label for="gender">Gender</label>
            <select id="gender" name="gender" required>
              <option value="" selected disabled>Select</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
              <option value="other">Other</option>
            </select>
            <small class="error" id="err-gender"></small>
          </div>

          <div class="field">
            <label for="dob">Date of Birth</label>
            <input id="dob" name="dob" type="date" required />
            <small class="error" id="err-dob"></small>
          </div>

          <div class="field full">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" required placeholder="example@email.com" />
            <small class="error" id="err-email"></small>
          </div>

          <div class="field full">
            <label for="address">Address</label>
            <input id="address" name="address" type="text" required placeholder="Your address" />
            <small class="error" id="err-address"></small>
          </div>

          <!-- NEW: Role select -->
          <div class="field">
            <label for="role">Role</label>
            <select id="role" name="role" required>
              <option value="" disabled selected>Select role</option>
              <option value="patient">Patient</option>
              <option value="doctor">Doctor</option>
              <option value="admin">Admin</option>
            </select>
            <small class="error" id="err-role"></small>
          </div>

          <div class="field">
            <label for="password">Password</label>
            <input id="password" name="password" type="password" required minlength="6" placeholder="Min 6 chars" />
            <small class="error" id="err-password"></small>
          </div>

          <div class="field">
            <label for="confirmPassword">Confirm Password</label>
            <input id="confirmPassword" name="confirmPassword" type="password" required placeholder="Re-type password" />
            <small class="error" id="err-confirmPassword"></small>
          </div>
        </div>

        <button class="btn primary" type="submit">Registration</button>
        <a class="btn link" href="login.php">Already Have Account? Login Here</a>
      </form>
    </section>
  </main>

  <script src="script.js"></script>
</body>
</html>
