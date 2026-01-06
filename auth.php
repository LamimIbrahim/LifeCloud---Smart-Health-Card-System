<?php
// auth.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Require a single role to access the page.
 * Example: require_role('admin');
 */
function require_role(string $role): void {
    if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== $role) {
        header("Location: login.php");
        exit;
    }
}

/**
 * Require any one of the given roles.
 * Example: require_any_role(['admin','doctor']);
 */
function require_any_role(array $roles): void {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
    $current = $_SESSION['role'] ?? '';
    if (!in_array($current, $roles, true)) {
        header("Location: login.php");
        exit;
    }
}
