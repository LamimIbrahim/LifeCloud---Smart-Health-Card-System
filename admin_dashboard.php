<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/controller/AdminController.php';

$conn = $conn ?? new mysqli('localhost','root','','lifecloud_db'); // jeta tomer db.php te ache

$controller = new AdminController($conn);
$controller->dashboard();   // admin_dashboard.php te
