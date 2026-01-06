<?php
session_start();          // Resume current session

// 1) Clear all session variables
$_SESSION = [];

// 2) Destroy session data on server
session_destroy();

// 3) Optionally clear the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// 4) Redirect back to login page
header("Location: login.php");
exit;

