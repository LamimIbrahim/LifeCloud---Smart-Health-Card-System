<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/controller/PatientController.php';

$controller = new PatientController($conn);
$controller->history();
