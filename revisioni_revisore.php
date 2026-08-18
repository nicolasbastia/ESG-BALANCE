<?php

require_once __DIR__ . '/controllers/RevisioneController.php';

$controller = new RevisioneController();

$action = $_GET['action'] ?? 'index';

switch($action) {

    case 'nota':

        $controller->creaNota();

        break;

    default:

        $controller->areaRevisore();

        break;
        
    case 'giudizio':

        $controller->creaGiudizio();

        break;
}
?>