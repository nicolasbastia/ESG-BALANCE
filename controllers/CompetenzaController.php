<?php

session_start();

require_once __DIR__ . '/../repositories/CompetenzaRepository.php';
require_once __DIR__ . '/../models/Competenza.php';

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

        if(
            !isset($_SESSION['utente']) ||
            $_SESSION['utente']['ruolo'] !== 'revisore'
        ) {

            header('Location: index.php');

            exit;
        }

        $utente = $_SESSION['utente'];

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
            | Creazione model per validazione
            |------------------------------------------------------------------
            */

            $competenza = new Competenza(
                $utente['id'],
                $idCompetenza,
                $livello
            );

            if(!$competenza->isValid()) {

                $_SESSION['errore_competenza'] =
                    "Livello competenza non valido (0-5).";

                header('Location: competenza.php');

                exit;
            }

            /*
            |------------------------------------------------------------------
            | Inserimento
            |------------------------------------------------------------------
            */

            $risultato = $this->repo->create(

                $utente['id'],
                $idCompetenza,
                $livello

            );

            /*
            |------------------------------------------------------------------
            | Competenza già presente
            |------------------------------------------------------------------
            */

            if(!$risultato) {

                $_SESSION['errore_competenza'] =
                    "Hai già dichiarato questa competenza. Puoi modificarne il livello.";

                header('Location: competenza.php');

                exit;
            }

            /*
            |------------------------------------------------------------------
            | Inserimento riuscito
            |------------------------------------------------------------------
            */

            $_SESSION['successo_competenza'] =
                "Competenza aggiunta correttamente.";

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

            $competenza = new Competenza(
                $utente['id'],
                $idCompetenza,
                $livello
            );

            if(!$competenza->isValid()) {

                $_SESSION['errore_competenza'] =
                    "Livello competenza non valido (0-5).";

                header('Location: competenza.php');

                exit;
            }

            $risultato = $this->repo->update(

                $utente['id'],
                $idCompetenza,
                $livello

            );

            if(!$risultato) {

                $_SESSION['errore_competenza'] =
                    "Impossibile aggiornare la competenza.";

                header('Location: competenza.php');

                exit;
            }

            $_SESSION['successo_competenza'] =
                "Competenza aggiornata correttamente.";

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

            $risultato = $this->repo->delete(

                $utente['id'],
                $idCompetenza

            );

            if(!$risultato) {

                $_SESSION['errore_competenza'] =
                    "Impossibile eliminare la competenza.";

                header('Location: competenza.php');

                exit;
            }

            $_SESSION['successo_competenza'] =
                "Competenza eliminata correttamente.";

            header('Location: competenza.php');

            exit;
        }
    }
}
?>