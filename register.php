<?php

require_once __DIR__ . '/controllers/RegisterController.php';

$controller = new RegisterController();

$action = $_GET['action'] ?? 'index';

switch($action) {

    case 'register':

        $controller->register();

        break;

    default:

        $controller->index();

        break;
}
?>