<?php

session_start();

require_once __DIR__ . '/../repositories/VoceTemplateRepository.php';
require_once __DIR__ . '/../models/VoceTemplate.php';
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

            /*
            |--------------------------------------------------------------------------
            | CREAZIONE MODEL
            |--------------------------------------------------------------------------
            */

            $voce = new VoceTemplate(

                null,
                $nome,
                $descrizione

            );

            /*
            |--------------------------------------------------------------------------
            | SALVATAGGIO
            |--------------------------------------------------------------------------
            */

            $this->repo->create(

                $voce,
                $utente['id']

            );

            /*
            |--------------------------------------------------------------------------
            | LOGGER
            |--------------------------------------------------------------------------
            */

            salvaEvento(
                "Creata voce template: " . $nome
            );

            $_SESSION['successo_template'] =
                "Voce template creata correttamente.";

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

            $idVoce = $_GET['id'];

            /*
            |--------------------------------------------------------------------------
            | TENTATIVO ELIMINAZIONE
            |--------------------------------------------------------------------------
            */

            $risultato = $this->repo->delete(

                $idVoce

            );

            /*
            |--------------------------------------------------------------------------
            | VOCE UTILIZZATA IN UN BILANCIO
            |--------------------------------------------------------------------------
            */

            if(!$risultato) {

                $_SESSION['errore_template'] =
                    "Impossibile eliminare la voce: è già utilizzata in uno o più bilanci.";

                header('Location: template.php');

                exit;
            }

            /*
            |--------------------------------------------------------------------------
            | LOGGER
            |--------------------------------------------------------------------------
            */

            salvaEvento(
                "Eliminata voce template ID: " . $idVoce
            );

            $_SESSION['successo_template'] =
                "Voce template eliminata correttamente.";

            header('Location: template.php');

            exit;
        }
    }
}
?>