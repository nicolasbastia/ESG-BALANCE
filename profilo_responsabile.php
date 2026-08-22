<?php

require_once __DIR__ . '/controllers/ProfiloResponsabileController.php';

$controller = new ProfiloResponsabileController();

$action = $_GET['action'] ?? 'index';

switch($action) {

    case 'upload':

        $controller->uploadCv();

        break;

    case 'delete':

        $controller->deleteCv();

        break;

    default:

        $controller->index();

        break;
}

?>