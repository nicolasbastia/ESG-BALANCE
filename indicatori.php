<?php

require_once __DIR__ . '/controllers/IndicatoreController.php';

$controller = new IndicatoreController();

$action = $_GET['action'] ?? 'index';

switch($action) {

    case 'create':

        $controller->create();

        break;

    case 'delete':

        $controller->delete();

        break;

    default:

        $controller->index();

        break;
}
?>