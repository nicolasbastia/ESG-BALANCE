<?php

session_start();

require_once __DIR__ . '/../repositories/CompetenzaRepository.php';

class CompetenzaController {

    private $repo;

    public function __construct() {

        $this->repo = new CompetenzaRepository();
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    | Mostra le competenze del revisore e quelle disponibili
    |--------------------------------------------------------------------------
    */

    public function index() {

        /*
        |----------------------------------------------------------------------
        | Controllo accesso
        |----------------------------------------------------------------------
        */

        if(
            !isset($_SESSION['utente']) ||
            $_SESSION['utente']['ruolo'] !== 'revisore'
        ) {

            header('Location: index.php');

            exit;
        }

        $utente = $_SESSION['utente'];

        /*
        |----------------------------------------------------------------------
        | Recuperiamo le competenze
        |----------------------------------------------------------------------
        */

        $competenze = $this->repo->getAll();

        $mieCompetenze = $this->repo->getByRevisore(
            $utente['id']
        );

        require __DIR__ . '/../views/revisore/competenze.php';
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    | Aggiunge una competenza al revisore
    |--------------------------------------------------------------------------
    */

    public function create() {

        /*
        |----------------------------------------------------------------------
        | Controllo accesso
        |----------------------------------------------------------------------
        */

        if(
            !isset($_SESSION['utente']) ||
            $_SESSION['utente']['ruolo'] !== 'revisore'
        ) {

            header('Location: index.php');

            exit;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $utente = $_SESSION['utente'];

            $idCompetenza = $_POST['id_competenza'];

            $livello = $_POST['livello'];

            /*
            |------------------------------------------------------------------
            | Controllo livello
            |------------------------------------------------------------------
            */

            if($livello < 0 || $livello > 5) {

                header('Location: competenza.php');

                exit;
            }

            /*
            |------------------------------------------------------------------
            | Inserimento
            |------------------------------------------------------------------
            */

            $this->repo->create(

                $utente['id'],
                $idCompetenza,
                $livello

            );

            header('Location: competenza.php');

            exit;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    | Modifica il livello di una competenza
    |--------------------------------------------------------------------------
    */

    public function update() {

        /*
        |----------------------------------------------------------------------
        | Controllo accesso
        |----------------------------------------------------------------------
        */

        if(
            !isset($_SESSION['utente']) ||
            $_SESSION['utente']['ruolo'] !== 'revisore'
        ) {

            header('Location: index.php');

            exit;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $utente = $_SESSION['utente'];

            $idCompetenza = $_POST['id_competenza'];

            $livello = $_POST['livello'];

            /*
            |------------------------------------------------------------------
            | Controllo livello
            |------------------------------------------------------------------
            */

            if($livello < 0 || $livello > 5) {

                header('Location: competenza.php');

                exit;
            }

            /*
            |------------------------------------------------------------------
            | Aggiornamento
            |------------------------------------------------------------------
            */

            $this->repo->update(

                $utente['id'],
                $idCompetenza,
                $livello

            );

            header('Location: competenza.php');

            exit;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    | Elimina una competenza dal revisore
    |--------------------------------------------------------------------------
    */

    public function delete() {

        /*
        |----------------------------------------------------------------------
        | Controllo accesso
        |----------------------------------------------------------------------
        */

        if(
            !isset($_SESSION['utente']) ||
            $_SESSION['utente']['ruolo'] !== 'revisore'
        ) {

            header('Location: index.php');

            exit;
        }

        if(isset($_GET['id'])) {

            $utente = $_SESSION['utente'];

            $idCompetenza = $_GET['id'];

            $this->repo->delete(

                $utente['id'],
                $idCompetenza

            );

            header('Location: competenza.php');

            exit;
        }
    }
}
?>