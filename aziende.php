<?php

require_once __DIR__ . '/controllers/AziendaController.php';

$controller = new AziendaController();

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