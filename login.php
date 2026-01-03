<?php
session_start();
include 'db_connect.php';

$msg = "";

// এখানে চেক করা হচ্ছে ফর্ম সাবমিট হয়েছে কিনা
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // isset() দিয়ে চেক করা হচ্ছে ফিল্ডগুলো আছে কিনা
    if (isset($_POST['uid']) && isset($_POST['password'])) {
        $email = trim($_POST['uid']);
        $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            $msg = "ইমেইল এবং পাসওয়ার্ড দিন।";
        } else {
            // ডাটাবেস কোয়েরি
            $stmt = $conn->prepare("SELECT id, full_name, password FROM users WHERE email = ?");
            if ($stmt) {
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    $stmt->bind_result($id, $full_name, $hashed_password);
                    $stmt->fetch();

                    if (password_verify($password, $hashed_password)) {
                        $_SESSION['user_id'] = $id;
                        $_SESSION['user_name'] = $full_name;
                        header("Location: dashboard.php");
                        exit();
                    } else {
                        $msg = "Wrong Password!";
                    }
                } else {
                    $msg = "Account Not Found!";
                }
                $stmt->close();
            } else {
                $msg = "Database Error!" . $conn->error;
            }
        }
    }
}
?>


<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>LifeCloud | Login</title>
  <link rel="stylesheet" href="style.css" />
  <style>
      .error-msg { color: red; text-align: center; margin-bottom: 10px; font-weight: bold; }
  </style>
</head>
<body>
  <main class="wrap">
    <section class="card login-card">
      <header class="hero">
        <div class="brand">
          <div class="logo"><img src="logo.png" ></div>
          <div class="brand-text">
            <h1>LifeCloud</h1>
            <p>Health on Cloud</p>
          </div>
        </div>
      </header>

      <form method="POST" action="" class="login-form" novalidate>
        <div class="login-header"> <span class="icon-user">👤</span> Login </div>

        <?php if(!empty($msg)): ?>
            <div class="error-msg"><?php echo $msg; ?></div>
        <?php endif; ?>

        <div class="field-group">
          <label for="uid"> <span class="icon">💳</span> Email </label>
          <div class="input-wrapper">
             <input id="uid" name="uid" type="email" required placeholder="Enter your email" />
          </div>
        </div>

        <div class="field-group">
          <label for="password"> <span class="icon">🔒</span> Password </label>
          <div class="input-wrapper">
            <input id="password" name="password" type="password" required placeholder="Enter password" />
          </div>
        </div>

        <div class="actions">
           <button class="btn primary" type="submit">Login</button>
           <a class="btn link" href="register.php">Create New Account</a>
        </div>
      </form>
    </section>
  </main>
</body>
</html>
