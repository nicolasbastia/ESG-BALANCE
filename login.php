<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/controllers/AuthController.php';

$controller = new AuthController();

$controller->login();

?>
