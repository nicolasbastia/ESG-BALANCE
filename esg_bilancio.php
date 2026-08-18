<?php

require_once __DIR__ . '/controllers/VoceIndicatoreController.php';

$controller = new VoceIndicatoreController();

$action = $_GET['action'] ?? 'index';

switch($action) {

    case 'create':

        $controller->create();

        break;

    default:

        $controller->index();

        break;
}
?>