<?php

require_once __DIR__ . '/controllers/ResponsabileController.php';

$controller = new ResponsabileController();

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