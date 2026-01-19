<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/controller/AuthController.php';

$controller = new AuthController($conn);
$controller->register();   // login() না, নতুন register() মেথড
