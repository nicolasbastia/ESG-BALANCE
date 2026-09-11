<?php

require_once __DIR__ . '/controllers/RevisioneController.php';

$controller = new RevisioneController();

$action = $_GET['action'] ?? 'index';

switch($action) {

    case 'nota':

        $controller->creaNota();

        break;

    case 'giudizio':

        $controller->creaGiudizio();

        break;

    default:

        $controller->areaRevisore();

        break;
}

?>