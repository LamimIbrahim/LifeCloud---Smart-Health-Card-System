<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/controller/AdminController.php';

$controller = new AdminController($conn);
$controller->users();
