<?php

session_start();

require_once __DIR__ . '/../repositories/ProfiloResponsabileRepository.php';
require_once __DIR__ . '/../models/ProfiloResponsabile.php';
require_once __DIR__ . '/../config/logger.php';

class ProfiloResponsabileController {

    private $repo;

    public function __construct() {

        $this->repo = new ProfiloResponsabileRepository();
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

            header('Location: index.php');

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

            header('Location: profilo_responsabile.php');

            exit;
        }

        if(
            !isset($_FILES['cv_pdf']) ||
            $_FILES['cv_pdf']['error'] !== UPLOAD_ERR_OK
        ) {

            header('Location: profilo_responsabile.php');

            exit;
        }

        $file = $_FILES['cv_pdf'];

        /*
        |--------------------------------------------------------------------------
        | CONTROLLO ESTENSIONE (VALIDAZIONE MODEL)
        |--------------------------------------------------------------------------
        */

        if(!ProfiloResponsabile::isValidCvExtension($file['name'])) {

            $_SESSION['errore_cv'] = "Il file deve essere in formato PDF";

            header('Location: profilo_responsabile.php');

            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | CONTROLLO MIME (VALIDAZIONE MODEL)
        |--------------------------------------------------------------------------
        */

        if(!ProfiloResponsabile::isValidCvMimeType($file['tmp_name'])) {

            $_SESSION['errore_cv'] = "Il file non è un PDF valido";

            header('Location: profilo_responsabile.php');

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

        $cartella = __DIR__ . '/../uploads/cv/';

        if(!is_dir($cartella)) {

            mkdir($cartella, 0777, true);
        }

        $destinazione = $cartella . $nomeFile;

        /*
        |--------------------------------------------------------------------------
        | SALVA FILE
        |--------------------------------------------------------------------------
        */

        if(
            move_uploaded_file(
                $file['tmp_name'],
                $destinazione
            )
        ) {

            /*
            |--------------------------------------------------------------------------
            | ELIMINA VECCHIO FILE
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

                if(file_exists($vecchioFile)) {

                    unlink($vecchioFile);
                }
            }

            $pathDb =
                'uploads/cv/' .
                $nomeFile;

            $this->repo->updateCv(
                $utente['id'],
                $pathDb
            );

            salvaEvento(
                "Aggiornato Curriculum Vitae responsabile ID: " .
                $utente['id']
            );
        }

        header('Location: profilo_responsabile.php');

        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | ELIMINA CV
    |--------------------------------------------------------------------------
    */

    public function deleteCv() {

        $this->checkAccess();

        $utente = $_SESSION['utente'];

        $profilo = $this->repo->getByUtente(
            $utente['id']
        );

        if(
            $profilo &&
            $profilo->hasCv()
        ) {

            $filePath =
                __DIR__ .
                '/../' .
                $profilo->cv_pdf;

            if(file_exists($filePath)) {

                unlink($filePath);
            }

            $this->repo->deleteCv(
                $utente['id']
            );

            salvaEvento(
                "Eliminato Curriculum Vitae responsabile ID: " .
                $utente['id']
            );
        }

        header('Location: profilo_responsabile.php');

        exit;
    }
}
?>