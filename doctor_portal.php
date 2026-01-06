<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/controller/DoctorController.php';

$controller = new DoctorController($conn);
$controller->portal();
