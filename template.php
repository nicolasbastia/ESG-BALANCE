<?php

require_once __DIR__ . '/controllers/VoceTemplateController.php';

$controller = new VoceTemplateController();

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