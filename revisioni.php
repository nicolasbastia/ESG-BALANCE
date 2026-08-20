<?php

require_once __DIR__ . '/controllers/RevisioneController.php';

$controller = new RevisioneController();

$action = $_GET['action'] ?? 'index';

switch($action) {

    case 'assegna':

        $controller->assegna();

        break;

    case 'dettaglio':

        $controller->dettaglio();

        break;

    case 'dettaglioAdmin':

        $controller->dettaglioAdmin();

        break;

    default:

        $controller->index();

        break;
}
?>