<?php

session_start();

require_once __DIR__ . '/../repositories/ResponsabileRepository.php';
require_once __DIR__ . '/../models/Responsabile.php';
require_once __DIR__ . '/../config/logger.php';

class ResponsabileController {

    private $repo;

    public function __construct() {

        $this->repo = new ResponsabileRepository();
    }

    /*
    |--------------------------------------------------------------------------
    | CONTROLLO ACCESSO
    |--------------------------------------------------------------------------
    */

    private function checkAccess() {

        if(
            !isset($_SESSION['utente']) ||
            $_SESSION['utente']['ruolo'] !== 'responsabile'
        ) {

            header('Location: /esg-balance/index.php');
            exit;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index() {

        $this->checkAccess();

        $utente = $_SESSION['utente'];

        $profilo = $this->repo->getByUtente(
            $utente['id']
        );

        require __DIR__ . '/../views/responsabile/profilo.php';
    }

    /*
    |--------------------------------------------------------------------------
    | UPLOAD / SOSTITUZIONE CV
    |--------------------------------------------------------------------------
    */

    public function uploadCv() {

        $this->checkAccess();

        if($_SERVER['REQUEST_METHOD'] !== 'POST') {

            header('Location: /esg-balance/responsabile.php');
            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | CONTROLLO FILE
        |--------------------------------------------------------------------------
        */

        if(
            !isset($_FILES['cv_pdf']) ||
            $_FILES['cv_pdf']['error'] !== UPLOAD_ERR_OK
        ) {

            $_SESSION['errore_cv'] = "Errore durante il caricamento del file.";

            header('Location: /esg-balance/responsabile.php');
            exit;
        }

        $file = $_FILES['cv_pdf'];

        /*
        |--------------------------------------------------------------------------
        | CONTROLLO DIMENSIONE
        |--------------------------------------------------------------------------
        */

        $maxSize = 5 * 1024 * 1024; // 5 MB

        if($file['size'] > $maxSize) {

            $_SESSION['errore_cv'] =
                "Il file PDF non può superare i 5 MB.";

            header('Location: /esg-balance/responsabile.php');
            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | CONTROLLO ESTENSIONE
        |--------------------------------------------------------------------------
        */

        if(!Responsabile::isValidCvExtension($file['name'])) {

            $_SESSION['errore_cv'] =
                "Il file deve essere in formato PDF.";

            header('Location: /esg-balance/responsabile.php');
            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | CONTROLLO MIME
        |--------------------------------------------------------------------------
        */

        if(!Responsabile::isValidCvMimeType($file['tmp_name'])) {

            $_SESSION['errore_cv'] =
                "Il file non è un PDF valido.";

            header('Location: /esg-balance/responsabile.php');
            exit;
        }

        $utente = $_SESSION['utente'];

        /*
        |--------------------------------------------------------------------------
        | RECUPERA EVENTUALE CV PRECEDENTE
        |--------------------------------------------------------------------------
        */

        $profilo = $this->repo->getByUtente(
            $utente['id']
        );

        /*
        |--------------------------------------------------------------------------
        | GENERA NOME FILE
        |--------------------------------------------------------------------------
        */

        $nomeFile =
            'cv_' .
            $utente['id'] .
            '_' .
            time() .
            '.pdf';

        $cartella =
            __DIR__ .
            '/../uploads/cv/';

        if(!is_dir($cartella)) {

            mkdir(
                $cartella,
                0755,
                true
            );
        }

        $destinazione =
            $cartella .
            $nomeFile;

        $pathDb =
            'uploads/cv/' .
            $nomeFile;

        /*
        |--------------------------------------------------------------------------
        | SALVA NUOVO FILE
        |--------------------------------------------------------------------------
        */

        if(
            !move_uploaded_file(
                $file['tmp_name'],
                $destinazione
            )
        ) {

            $_SESSION['errore_cv'] =
                "Impossibile salvare il file.";

            header('Location: /esg-balance/responsabile.php');
            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | AGGIORNA DATABASE
        |--------------------------------------------------------------------------
        */

        try {

            $this->repo->updateCv(
                $utente['id'],
                $pathDb
            );

        } catch(Throwable $e) {

            /*
            |----------------------------------------------------------------------
            | SE IL DB FALLISCE, ELIMINA IL NUOVO FILE
            |----------------------------------------------------------------------
            */

            if(file_exists($destinazione)) {

                unlink($destinazione);
            }

            $_SESSION['errore_cv'] =
                "Errore durante l'aggiornamento del Curriculum Vitae.";

            header('Location: /esg-balance/responsabile.php');
            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | ELIMINA VECCHIO FILE SOLO DOPO UPDATE DB
        |--------------------------------------------------------------------------
        */

        if(
            $profilo &&
            $profilo->hasCv()
        ) {

            $vecchioFile =
                __DIR__ .
                '/../' .
                $profilo->cv_pdf;

            if(
                file_exists($vecchioFile) &&
                $vecchioFile !== $destinazione
            ) {

                unlink($vecchioFile);
            }
        }

        salvaEvento(
            "Aggiornato Curriculum Vitae responsabile ID: " .
            $utente['id']
        );

        $_SESSION['successo_cv'] =
            "Curriculum Vitae aggiornato correttamente.";

        header('Location: /esg-balance/responsabile.php');
        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | ELIMINA CV
    |--------------------------------------------------------------------------
    */

    public function deleteCv() {

        $this->checkAccess();

        /*
        |--------------------------------------------------------------------------
        | SOLO POST
        |--------------------------------------------------------------------------
        */

        if($_SERVER['REQUEST_METHOD'] !== 'POST') {

            header('Location: /esg-balance/responsabile.php');
            exit;
        }

        $utente = $_SESSION['utente'];

        $profilo = $this->repo->getByUtente(
            $utente['id']
        );

        if(
            $profilo &&
            $profilo->hasCv()
        ) {

            /*
            |----------------------------------------------------------------------
            | PRIMA AGGIORNA DATABASE
            |----------------------------------------------------------------------
            */

            try {

                $this->repo->deleteCv(
                    $utente['id']
                );

            } catch(Throwable $e) {

                $_SESSION['errore_cv'] =
                    "Errore durante l'eliminazione del Curriculum Vitae.";

                header('Location: /esg-balance/responsabile.php');
                exit;
            }

            /*
            |----------------------------------------------------------------------
            | POI ELIMINA FILE FISICO
            |----------------------------------------------------------------------
            */

            $filePath =
                __DIR__ .
                '/../' .
                $profilo->cv_pdf;

            if(file_exists($filePath)) {

                unlink($filePath);
            }

            salvaEvento(
                "Eliminato Curriculum Vitae responsabile ID: " .
                $utente['id']
            );

            $_SESSION['successo_cv'] =
                "Curriculum Vitae eliminato correttamente.";
        }

        header('Location: /esg-balance/responsabile.php');
        exit;
    }
}

?>