<?php
session_start();
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/controller/AdminController.php';

$conn = $conn ?? new mysqli('localhost','root','','lifecloud_db');

$controller = new AdminController($conn);
$controller->users();
