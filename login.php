<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/controller/AuthController.php';

$controller = new AuthController($conn);
$controller->login();  // ei method theke $_SESSION set, redirect, message pathano sob hocche
