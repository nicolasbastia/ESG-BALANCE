<?php

session_start();

require_once __DIR__ . '/../repositories/VoceIndicatoreRepository.php';
require_once __DIR__ . '/../repositories/BilancioRepository.php';
require_once __DIR__ . '/../models/VoceIndicatore.php';
require_once __DIR__ . '/../config/logger.php';

class VoceIndicatoreController {

    private $repo;
    private $bilancioRepo;

    public function __construct() {

        $this->repo = new VoceIndicatoreRepository();
        $this->bilancioRepo = new BilancioRepository();
    }

    /*
    |--------------------------------------------------------------------------
    | CONTROLLO ACCESSO RESPONSABILE
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | CONTROLLO BILANCIO DEL RESPONSABILE
    |--------------------------------------------------------------------------
    */

    private function bilancioAppartieneAlResponsabile(
        $idBilancio,
        $idResponsabile
    ) {

        $bilanci = $this->bilancioRepo->getByResponsabile(
            $idResponsabile
        );

        foreach($bilanci as $bilancio) {

            if(
                (int) $bilancio->id_bilancio ===
                (int) $idBilancio
            ) {

                return true;
            }
        }

        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | CONTROLLO VOCE APPARTENENTE AL BILANCIO
    |--------------------------------------------------------------------------
    */

    private function voceAppartieneAlBilancio(
        $idVoceBilancio,
        $idBilancio
    ) {

        $voci = $this->repo->getVociBilancio(
            $idBilancio
        );

        foreach($voci as $voce) {

            if(
                (int) $voce['id_voce_bilancio'] ===
                (int) $idVoceBilancio
            ) {

                return true;
            }
        }

        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index() {

        $utente = $this->verificaResponsabile();

        $idBilancio = filter_input(
            INPUT_GET,
            'id',
            FILTER_VALIDATE_INT
        );

        if(!$idBilancio || $idBilancio <= 0) {

            $_SESSION['errore_esg'] =
                "Bilancio non valido.";

            header('Location: bilanci.php');
            exit;
        }

        if(
            !$this->bilancioAppartieneAlResponsabile(
                $idBilancio,
                $utente['id']
            )
        ) {

            $_SESSION['errore_esg'] =
                "Non sei autorizzato a gestire gli indicatori ESG di questo bilancio.";

            header('Location: bilanci.php');
            exit;
        }

        $indicatori = $this->repo->getIndicatori();

        $voci = $this->repo->getVociBilancio(
            $idBilancio
        );

        $collegamenti = $this->repo->getCollegamenti(
            $idBilancio
        );

        require __DIR__ . '/../views/responsabile/esg_bilancio.php';
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create() {

        $utente = $this->verificaResponsabile();

        if($_SERVER['REQUEST_METHOD'] !== 'POST') {

            header('Location: bilanci.php');
            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | ID BILANCIO
        |--------------------------------------------------------------------------
        */

        $idBilancio = filter_input(
            INPUT_POST,
            'id_bilancio',
            FILTER_VALIDATE_INT
        );

        /*
        |--------------------------------------------------------------------------
        | ID VOCE BILANCIO
        |--------------------------------------------------------------------------
        */

        $idVoceBilancio = filter_input(
            INPUT_POST,
            'id_voce_bilancio',
            FILTER_VALIDATE_INT
        );

        /*
        |--------------------------------------------------------------------------
        | ID INDICATORE
        |--------------------------------------------------------------------------
        */

        $idIndicatore = filter_input(
            INPUT_POST,
            'id_indicatore',
            FILTER_VALIDATE_INT
        );

        if(
            !$idBilancio ||
            $idBilancio <= 0 ||
            !$idVoceBilancio ||
            $idVoceBilancio <= 0 ||
            !$idIndicatore ||
            $idIndicatore <= 0
        ) {

            $_SESSION['errore_esg'] =
                "Dati ESG non validi.";

            header('Location: bilanci.php');
            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | CONTROLLO BILANCIO
        |--------------------------------------------------------------------------
        */

        if(
            !$this->bilancioAppartieneAlResponsabile(
                $idBilancio,
                $utente['id']
            )
        ) {

            $_SESSION['errore_esg'] =
                "Non sei autorizzato a modificare questo bilancio.";

            header('Location: bilanci.php');
            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | CONTROLLO VOCE
        |--------------------------------------------------------------------------
        */

        if(
            !$this->voceAppartieneAlBilancio(
                $idVoceBilancio,
                $idBilancio
            )
        ) {

            $_SESSION['errore_esg'] =
                "La voce selezionata non appartiene a questo bilancio.";

            header(
                'Location: esg_bilancio.php?id=' . $idBilancio
            );

            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | ALTRI DATI
        |--------------------------------------------------------------------------
        */

        $valore = trim(
            $_POST['valore'] ?? ''
        );

        $fonte = trim(
            $_POST['fonte'] ?? ''
        );

        $dataRilevazione = trim(
            $_POST['data_rilevazione'] ?? ''
        );

        /*
        |--------------------------------------------------------------------------
        | VALIDAZIONE VALORE
        |--------------------------------------------------------------------------
        */

        if(
            $valore === '' ||
            !is_numeric($valore)
        ) {

            $_SESSION['errore_esg'] =
                "Il valore dell'indicatore deve essere numerico.";

            header(
                'Location: esg_bilancio.php?id=' . $idBilancio
            );

            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDAZIONE DATA
        |--------------------------------------------------------------------------
        */

        if($dataRilevazione === '') {

            $_SESSION['errore_esg'] =
                "Inserisci la data di rilevazione.";

            header(
                'Location: esg_bilancio.php?id=' . $idBilancio
            );

            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | CREAZIONE MODEL
        |--------------------------------------------------------------------------
        */

        $voceIndicatore = new VoceIndicatore(
            $idVoceBilancio,
            $idIndicatore,
            $valore,
            $fonte,
            $dataRilevazione
        );

        /*
        |--------------------------------------------------------------------------
        | SALVATAGGIO
        |--------------------------------------------------------------------------
        */

        try {

            $risultato = $this->repo->create(
                $voceIndicatore
            );

            if(!$risultato) {

                $_SESSION['errore_esg'] =
                    "Impossibile collegare l'indicatore ESG.";

                header(
                    'Location: esg_bilancio.php?id=' . $idBilancio
                );

                exit;
            }

            salvaEvento(
                "Collegato indicatore ESG al bilancio ID: " .
                $idBilancio
            );

            $_SESSION['successo_esg'] =
                "Indicatore ESG collegato correttamente.";

        } catch(PDOException $e) {

            if($e->getCode() === '23000') {

                $_SESSION['errore_esg'] =
                    "Questo indicatore è già collegato alla voce selezionata.";

            } else {

                $_SESSION['errore_esg'] =
                    "Errore durante il collegamento dell'indicatore ESG.";
            }
        }

        header(
            'Location: esg_bilancio.php?id=' . $idBilancio
        );

        exit;
    }
}

?>