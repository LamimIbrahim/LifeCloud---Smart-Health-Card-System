<?php
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    header("Location: dashboard.php");
    exit;
}

if (isset($_COOKIE['email']) && isset($_COOKIE['password'])) {
    $_POST['email'] = $_COOKIE['email'];
    $_POST['password'] = $_COOKIE['password'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($email == "user@example.com" && $password == "password123") {
        $_SESSION['loggedin'] = true;
        $_SESSION['email'] = $email;

        if (isset($_POST['remember'])) {
            setcookie('email', $email, time() + (30 * 24 * 60 * 60), "/");
            setcookie('password', $password, time() + (30 * 24 * 60 * 60), "/");
        }

        header("Location: dashboard.php");
        exit;
    } else {
        $error_message = "Invalid credentials!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Health Card System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #4A90E2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: white;
            border-radius: 10px;
            display: flex;
            width: 80%;
            max-width: 800px;
        }

        .left-side {
            padding: 20px;
            flex: 1;
        }

        .right-side {
            flex: 1;
            background: linear-gradient(135deg, #4A90E2, #50E3C2);
            border-radius: 10px 0 0 10px;
        }

        h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        .input-field {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn {
            background-color: #50E3C2;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        .btn:hover {
            background-color: #4A90E2;
        }

        .login-options {
            text-align: center;
            margin-top: 15px;
        }

        .login-options button {
            margin: 5px;
            padding: 8px 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            cursor: pointer;
        }

        .login-options button:hover {
            background-color: #f1f1f1;
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="left-side">
            <h2>Smart Health Card System</h2>

            <?php if (isset($error_message)) {
                echo "<p class='error-message'>$error_message</p>";
            } ?>

            <form action="index.php" method="POST">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="input-field" value="<?php echo isset($_COOKIE['email']) ? $_COOKIE['email'] : ''; ?>" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="input-field" required>

                <input type="checkbox" id="remember" name="remember" <?php echo isset($_COOKIE['email']) ? 'checked' : ''; ?>>
                <label for="remember">Remember me for 30 days</label><br><br>

                <button type="submit" class="btn">Login</button>
                <p><a href="#">Forgot password?</a></p>
            </form>

            <div class="login-options">
                <p>Or login with</p>
                <button class="btn">Apple</button>
                <button class="btn">Google</button>
            </div>
        </div>

        <div class="right-side"></div>
    </div>

</body>

</html>