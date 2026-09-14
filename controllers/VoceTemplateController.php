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


    private function verificaAmministratore() {

        if(
            !isset($_SESSION['utente']) ||
            $_SESSION['utente']['ruolo'] !== 'amministratore'
        ) {

            header('Location: login.php');
            exit;
        }

        return $_SESSION['utente'];
    }


    public function index() {

        $this->verificaAmministratore();

        $voci = $this->repo->getAll();

        require __DIR__ . '/../views/admin/template.php';
    }


    public function create() {

        $utente = $this->verificaAmministratore();

        if($_SERVER['REQUEST_METHOD'] !== 'POST') {

            header('Location: template.php');
            exit;
        }


        $nome = trim(
            $_POST['nome'] ?? ''
        );

        $descrizione = trim(
            $_POST['descrizione'] ?? ''
        );


        if($nome === '') {

            $_SESSION['errore_template'] =
                "Il nome della voce template è obbligatorio.";

            header('Location: template.php');
            exit;
        }

        /* CREAZIONE MODEL */

        $voce = new VoceTemplate(
            null,
            $nome,
            $descrizione !== '' ? $descrizione : null,
            $utente['id']
        );


        try {

            $risultato = $this->repo->create(
                $voce
            );

            if(!$risultato) {

                $_SESSION['errore_template'] =
                    "Impossibile creare la voce template.";

                header('Location: template.php');
                exit;
            }


            salvaEvento(
                "Creata voce template: " . $nome
            );

            $_SESSION['successo_template'] =
                "Voce template creata correttamente.";

        } catch(PDOException $e) {

            if($e->getCode() === '23000') {

                $_SESSION['errore_template'] =
                    "Esiste già una voce template con questo nome.";

            } else {

                $_SESSION['errore_template'] =
                    "Errore durante la creazione della voce template.";
            }
        }

        header('Location: template.php');
        exit;
    }

    /* DELETE */

    public function delete() {

        $this->verificaAmministratore();

        if($_SERVER['REQUEST_METHOD'] !== 'POST') {

            header('Location: template.php');
            exit;
        }


        $idVoce = filter_input(
            INPUT_POST,
            'id',
            FILTER_VALIDATE_INT
        );

        if(!$idVoce || $idVoce <= 0) {

            $_SESSION['errore_template'] =
                "Voce template non valida.";

            header('Location: template.php');
            exit;
        }


        try {

            $risultato = $this->repo->delete(
                $idVoce
            );


            if(!$risultato) {

                $_SESSION['errore_template'] =
                    "Impossibile eliminare la voce: è già utilizzata in uno o più bilanci.";

                header('Location: template.php');
                exit;
            }


            salvaEvento(
                "Eliminata voce template ID: " . $idVoce
            );

            $_SESSION['successo_template'] =
                "Voce template eliminata correttamente.";

        } catch(PDOException $e) {

            $_SESSION['errore_template'] =
                "Errore durante l'eliminazione della voce template.";
        }

        header('Location: template.php');
        exit;
    }
}

?>