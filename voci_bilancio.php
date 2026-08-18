<?php

require_once __DIR__ . '/controllers/VoceBilancioController.php';

$controller = new VoceBilancioController();

$action = $_GET['action'] ?? 'index';

switch($action) {

    case 'save':

        $controller->save();

        break;

    default:

        $controller->index();

        break;
}
?>