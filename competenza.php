<?php

require_once __DIR__ . '/controllers/CompetenzaController.php';

$controller = new CompetenzaController();

$action = $_GET['action'] ?? 'index';

switch($action) {

    case 'create':

        $controller->create();

        break;

    case 'update':

        $controller->update();

        break;

    case 'delete':

        $controller->delete();

        break;

    default:

        $controller->index();

        break;
}

?>