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
    | CONTROLLO ACCESSO REVISORE
    |--------------------------------------------------------------------------
    */

    private function verificaRevisore() {

        if(
            !isset($_SESSION['utente']) ||
            $_SESSION['utente']['ruolo'] !== 'revisore'
        ) {

            header('Location: index.php');
            exit;
        }

        return $_SESSION['utente'];
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    | Mostra le competenze del revisore e quelle disponibili
    |--------------------------------------------------------------------------
    */

    public function index() {

        $utente = $this->verificaRevisore();

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

        $utente = $this->verificaRevisore();

        if($_SERVER['REQUEST_METHOD'] !== 'POST') {

            header('Location: competenza.php');
            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | DATI
        |--------------------------------------------------------------------------
        */

        $idCompetenza = filter_input(
            INPUT_POST,
            'id_competenza',
            FILTER_VALIDATE_INT
        );

        $livello = filter_input(
            INPUT_POST,
            'livello',
            FILTER_VALIDATE_INT
        );

        /*
        |--------------------------------------------------------------------------
        | VALIDAZIONE
        |--------------------------------------------------------------------------
        */

        if(
            !$idCompetenza ||
            $idCompetenza <= 0
        ) {

            $_SESSION['errore_competenza'] =
                "Competenza non valida.";

            header('Location: competenza.php');
            exit;
        }

        if(
            $livello === false ||
            $livello === null ||
            $livello < 0 ||
            $livello > 5
        ) {

            $_SESSION['errore_competenza'] =
                "Livello competenza non valido (0-5).";

            header('Location: competenza.php');
            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | CONTROLLO DUPLICATO
        |--------------------------------------------------------------------------
        */

        if(
            $this->repo->exists(
                $utente['id'],
                $idCompetenza
            )
        ) {

            $_SESSION['errore_competenza'] =
                "Hai già dichiarato questa competenza. Puoi modificarne il livello.";

            header('Location: competenza.php');
            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | CREAZIONE MODEL
        |--------------------------------------------------------------------------
        */

        $competenza = new Competenza(
            $utente['id'],
            $idCompetenza,
            $livello
        );

        /*
        |--------------------------------------------------------------------------
        | INSERIMENTO
        |--------------------------------------------------------------------------
        */

        try {

            $risultato = $this->repo->create(
                $competenza
            );

            if(!$risultato) {

                $_SESSION['errore_competenza'] =
                    "Impossibile aggiungere la competenza.";

                header('Location: competenza.php');
                exit;
            }

            $_SESSION['successo_competenza'] =
                "Competenza aggiunta correttamente.";

        } catch(PDOException $e) {

            if($e->getCode() === '23000') {

                $_SESSION['errore_competenza'] =
                    "Hai già dichiarato questa competenza.";

            } else {

                $_SESSION['errore_competenza'] =
                    "Errore durante l'aggiunta della competenza.";
            }
        }

        header('Location: competenza.php');
        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    | Modifica il livello di una competenza
    |--------------------------------------------------------------------------
    */

    public function update() {

        $utente = $this->verificaRevisore();

        if($_SERVER['REQUEST_METHOD'] !== 'POST') {

            header('Location: competenza.php');
            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | DATI
        |--------------------------------------------------------------------------
        */

        $idCompetenza = filter_input(
            INPUT_POST,
            'id_competenza',
            FILTER_VALIDATE_INT
        );

        $livello = filter_input(
            INPUT_POST,
            'livello',
            FILTER_VALIDATE_INT
        );

        /*
        |--------------------------------------------------------------------------
        | VALIDAZIONE
        |--------------------------------------------------------------------------
        */

        if(
            !$idCompetenza ||
            $idCompetenza <= 0
        ) {

            $_SESSION['errore_competenza'] =
                "Competenza non valida.";

            header('Location: competenza.php');
            exit;
        }

        if(
            $livello === false ||
            $livello === null ||
            $livello < 0 ||
            $livello > 5
        ) {

            $_SESSION['errore_competenza'] =
                "Livello competenza non valido (0-5).";

            header('Location: competenza.php');
            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | CONTROLLO ESISTENZA
        |--------------------------------------------------------------------------
        */

        if(
            !$this->repo->exists(
                $utente['id'],
                $idCompetenza
            )
        ) {

            $_SESSION['errore_competenza'] =
                "La competenza selezionata non appartiene al tuo profilo.";

            header('Location: competenza.php');
            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | CREAZIONE MODEL
        |--------------------------------------------------------------------------
        */

        $competenza = new Competenza(
            $utente['id'],
            $idCompetenza,
            $livello
        );

        /*
        |--------------------------------------------------------------------------
        | AGGIORNAMENTO
        |--------------------------------------------------------------------------
        */

        try {

            $risultato = $this->repo->update(
                $competenza
            );

            if(!$risultato) {

                $_SESSION['errore_competenza'] =
                    "Impossibile aggiornare la competenza.";

                header('Location: competenza.php');
                exit;
            }

            $_SESSION['successo_competenza'] =
                "Competenza aggiornata correttamente.";

        } catch(PDOException $e) {

            $_SESSION['errore_competenza'] =
                "Errore durante l'aggiornamento della competenza.";
        }

        header('Location: competenza.php');
        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    | Elimina una competenza dal revisore
    |--------------------------------------------------------------------------
    */

    public function delete() {

        $utente = $this->verificaRevisore();

        if($_SERVER['REQUEST_METHOD'] !== 'POST') {

            header('Location: competenza.php');
            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | ID COMPETENZA
        |--------------------------------------------------------------------------
        */

        $idCompetenza = filter_input(
            INPUT_POST,
            'id_competenza',
            FILTER_VALIDATE_INT
        );

        if(
            !$idCompetenza ||
            $idCompetenza <= 0
        ) {

            $_SESSION['errore_competenza'] =
                "Competenza non valida.";

            header('Location: competenza.php');
            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | CONTROLLO APPARTENENZA
        |--------------------------------------------------------------------------
        */

        if(
            !$this->repo->exists(
                $utente['id'],
                $idCompetenza
            )
        ) {

            $_SESSION['errore_competenza'] =
                "La competenza selezionata non appartiene al tuo profilo.";

            header('Location: competenza.php');
            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | ELIMINAZIONE
        |--------------------------------------------------------------------------
        */

        try {

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

        } catch(PDOException $e) {

            $_SESSION['errore_competenza'] =
                "Errore durante l'eliminazione della competenza.";
        }

        header('Location: competenza.php');
        exit;
    }
}

?>