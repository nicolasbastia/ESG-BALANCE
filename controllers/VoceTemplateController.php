<?php

session_start();

require_once __DIR__ . '/../repositories/VoceTemplateRepository.php';
require_once __DIR__ . '/../config/logger.php';

class VoceTemplateController {

    private $repo;

    public function __construct() {

        $this->repo = new VoceTemplateRepository();
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index() {

        $voci = $this->repo->getAll();

        require __DIR__ . '/../views/admin/template.php';
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create() {

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $utente = $_SESSION['utente'];

            $nome = $_POST['nome'];
            $descrizione = $_POST['descrizione'];

            $this->repo->create(

                $nome,
                $descrizione,
                $utente['id']

            );

            salvaEvento(
                "Creata voce template: " . $nome
            );

            header('Location: template.php');

            exit;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function delete() {

        if(isset($_GET['id'])) {

            $this->repo->delete(
                $_GET['id']
            );

            salvaEvento(
                "Eliminata voce template ID: " . $_GET['id']
            );

            header('Location: template.php');

            exit;
        }
    }
}
?>