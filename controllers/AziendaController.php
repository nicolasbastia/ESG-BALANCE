<?php

session_start();

require_once __DIR__ . '/../repositories/AziendaRepository.php';
require_once __DIR__ . '/../models/Azienda.php';
require_once __DIR__ . '/../config/logger.php';

class AziendaController {

    private $repo;

    public function __construct() {

        $this->repo = new AziendaRepository();
    }

    private function verificaResponsabile() {

        if(
            !isset($_SESSION['utente']) ||
            $_SESSION['utente']['ruolo'] !== 'responsabile'
        ) {

            header('Location: login.php');
            exit;
        }

        return $_SESSION['utente'];
    }

    public function index() {

        $utente = $this->verificaResponsabile();

        $aziende = $this->repo->getByResponsabile(
            $utente['id']
        );

        require __DIR__ . '/../views/responsabile/aziende.php';
    }


    public function create() {

        $utente = $this->verificaResponsabile();

        if($_SERVER['REQUEST_METHOD'] !== 'POST') {

            header('Location: aziende.php');
            exit;
        }

        /*DATI FORM*/

        $nome = trim($_POST['nome'] ?? '');
        $ragioneSociale = trim($_POST['ragione_sociale'] ?? '');
        $partitaIva = trim($_POST['partita_iva'] ?? '');
        $settore = trim($_POST['settore'] ?? '');
        $numeroDipendenti = $_POST['numero_dipendenti'] ?? '';

        if(
            $nome === '' ||
            $ragioneSociale === '' ||
            $partitaIva === '' ||
            $settore === ''
        ) {

            $_SESSION['errore_azienda'] =
                "Compila tutti i campi obbligatori.";

            header('Location: aziende.php');
            exit;
        }

        if(
            $numeroDipendenti === '' ||
            filter_var(
                $numeroDipendenti,
                FILTER_VALIDATE_INT
            ) === false ||
            (int) $numeroDipendenti < 0
        ) {

            $_SESSION['errore_azienda'] =
                "Il numero dei dipendenti deve essere un numero valido.";

            header('Location: aziende.php');
            exit;
        }

        $numeroDipendenti = (int) $numeroDipendenti;

        /*UPLOAD LOGO*/

        $logo = '';

        if(
            isset($_FILES['logo']) &&
            $_FILES['logo']['error'] !== UPLOAD_ERR_NO_FILE
        ) {

            if($_FILES['logo']['error'] !== UPLOAD_ERR_OK) {

                $_SESSION['errore_azienda'] =
                    "Errore durante il caricamento del logo.";

                header('Location: aziende.php');
                exit;
            }


            $tipiConsentiti = [
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/webp' => 'webp'
            ];

            $finfo = new finfo(FILEINFO_MIME_TYPE);

            $mimeType = $finfo->file(
                $_FILES['logo']['tmp_name']
            );

            if(!isset($tipiConsentiti[$mimeType])) {

                $_SESSION['errore_azienda'] =
                    "Il logo deve essere un'immagine JPG, PNG o WEBP.";

                header('Location: aziende.php');
                exit;
            }

            /*CARTELLA UPLOAD*/

            $directoryAssoluta =
                __DIR__ . '/../uploads/loghi/';

            if(!is_dir($directoryAssoluta)) {

                mkdir(
                    $directoryAssoluta,
                    0775,
                    true
                );
            }


            $nomeFile =
                uniqid('logo_', true) .
                '.' .
                $tipiConsentiti[$mimeType];

            $pathAssoluto =
                $directoryAssoluta . $nomeFile;

            $pathDatabase =
                'uploads/loghi/' . $nomeFile;

            if(
                !move_uploaded_file(
                    $_FILES['logo']['tmp_name'],
                    $pathAssoluto
                )
            ) {

                $_SESSION['errore_azienda'] =
                    "Impossibile salvare il logo.";

                header('Location: aziende.php');
                exit;
            }

            $logo = $pathDatabase;
        }

        /*
        |--------------------------------------------------------------------------
        | CREAZIONE MODEL
        |--------------------------------------------------------------------------
        */

        $azienda = new Azienda(

            null,
            $nome,
            $ragioneSociale,
            $partitaIva,
            $settore,
            $numeroDipendenti,
            $logo,
            0,
            $utente['id']

        );

        /*
        |--------------------------------------------------------------------------
        | CREAZIONE AZIENDA
        |--------------------------------------------------------------------------
        */

        try {

            $risultato = $this->repo->create(
                $azienda
            );

            if(!$risultato) {

                $_SESSION['errore_azienda'] =
                    "Impossibile creare l'azienda.";

                header('Location: aziende.php');
                exit;
            }

            salvaEvento(
                "Creata azienda: " . $nome
            );

            $_SESSION['successo_azienda'] =
                "Azienda creata correttamente.";

        } catch(PDOException $e) {

            /*
            |--------------------------------------------------------------------------
            | VINCOLI UNIQUE
            |--------------------------------------------------------------------------
            */

            if($e->getCode() === '23000') {

                $_SESSION['errore_azienda'] =
                    "Esiste già un'azienda con questa ragione sociale o partita IVA.";

            } else {

                $_SESSION['errore_azienda'] =
                    "Errore durante la creazione dell'azienda.";
            }
        }

        header('Location: aziende.php');
        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function delete() {

        $utente = $this->verificaResponsabile();

        /*
        |--------------------------------------------------------------------------
        | SOLO POST
        |--------------------------------------------------------------------------
        */

        if($_SERVER['REQUEST_METHOD'] !== 'POST') {

            header('Location: aziende.php');
            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | ID AZIENDA
        |--------------------------------------------------------------------------
        */

        $idAzienda = filter_input(
            INPUT_POST,
            'id',
            FILTER_VALIDATE_INT
        );

        if(!$idAzienda || $idAzienda <= 0) {

            $_SESSION['errore_azienda'] =
                "Azienda non valida.";

            header('Location: aziende.php');
            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | CONTROLLO CHE L'AZIENDA APPARTENGA AL RESPONSABILE
        |--------------------------------------------------------------------------
        */

        $aziendeResponsabile =
            $this->repo->getByResponsabile(
                $utente['id']
            );

        $aziendaTrovata = false;

        foreach($aziendeResponsabile as $azienda) {

            if(
                (int) $azienda->id_azienda ===
                (int) $idAzienda
            ) {

                $aziendaTrovata = true;

                break;
            }
        }

        if(!$aziendaTrovata) {

            $_SESSION['errore_azienda'] =
                "Non sei autorizzato a eliminare questa azienda.";

            header('Location: aziende.php');
            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | ELIMINAZIONE
        |--------------------------------------------------------------------------
        */

        try {

            $risultato = $this->repo->delete(
                $idAzienda
            );

            if(!$risultato) {

                $_SESSION['errore_azienda'] =
                    "Impossibile eliminare l'azienda.";

                header('Location: aziende.php');
                exit;
            }

            salvaEvento(
                "Eliminata azienda ID: " . $idAzienda
            );

            $_SESSION['successo_azienda'] =
                "Azienda eliminata correttamente.";

        } catch(PDOException $e) {

            $_SESSION['errore_azienda'] =
                "Errore durante l'eliminazione dell'azienda.";
        }

        header('Location: aziende.php');
        exit;
    }
}

?>