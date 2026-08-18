<?php

require_once __DIR__ . '/controllers/BilancioController.php';

$controller = new BilancioController();

$action = $_GET['action'] ?? 'index';

switch($action) {

    case 'create':

        $controller->create();

        break;

    case 'delete':

        $controller->delete();

        break;

    case 'dettaglio':

        $controller->dettaglio();

        break;

    default:

        $controller->index();

        break;
}
?>