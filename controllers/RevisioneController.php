<?php

session_start();

require_once __DIR__ . '/../repositories/RevisioneRepository.php';
require_once __DIR__ . '/../repositories/NotaRepository.php';
require_once __DIR__ . '/../repositories/GiudizioRepository.php';
require_once __DIR__ . '/../repositories/RevisoreRepository.php';

require_once __DIR__ . '/../models/Revisione.php';
require_once __DIR__ . '/../models/NotaRevisione.php';
require_once __DIR__ . '/../models/GiudizioRevisione.php';

require_once __DIR__ . '/../config/logger.php';

class RevisioneController {

    private $repo;
    private $notaRepo;
    private $giudizioRepo;

    public function __construct() {

        $this->repo = new RevisioneRepository();
        $this->notaRepo = new NotaRepository();
        $this->giudizioRepo = new GiudizioRepository();
    }

    /*
    |--------------------------------------------------------------------------
    | CONTROLLO AMMINISTRATORE
    |--------------------------------------------------------------------------
    */

    private function verificaAmministratore() {

        if(
            !isset($_SESSION['utente']) ||
            $_SESSION['utente']['ruolo'] !== 'amministratore'
        ) {

            header('Location: /esg-balance/index.php');
            exit;
        }

        return $_SESSION['utente'];
    }

    /*
    |--------------------------------------------------------------------------
    | CONTROLLO REVISORE
    |--------------------------------------------------------------------------
    */

    private function verificaRevisore() {

        if(
            !isset($_SESSION['utente']) ||
            $_SESSION['utente']['ruolo'] !== 'revisore'
        ) {

            header('Location: /esg-balance/index.php');
            exit;
        }

        return $_SESSION['utente'];
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX ADMIN
    |--------------------------------------------------------------------------
    */

    public function index() {

        $this->verificaAmministratore();

        $bilanci = $this->repo->getBilanci();

        $revisori = $this->repo->getRevisori();

        $revisioni = $this->repo->getRevisioni();

        require __DIR__ . '/../views/admin/revisioni.php';
    }

    /*
    |--------------------------------------------------------------------------
    | ASSEGNA REVISIONE
    |--------------------------------------------------------------------------
    */

    public function assegna() {

        $this->verificaAmministratore();

        if($_SERVER['REQUEST_METHOD'] !== 'POST') {

            header('Location: /esg-balance/revisioni.php');
            exit;
        }

        $idBilancio = filter_input(
            INPUT_POST,
            'id_bilancio',
            FILTER_VALIDATE_INT
        );

        $idRevisore = filter_input(
            INPUT_POST,
            'id_revisore',
            FILTER_VALIDATE_INT
        );

        if(
            !$idBilancio ||
            !$idRevisore ||
            $idBilancio <= 0 ||
            $idRevisore <= 0
        ) {

            $_SESSION['errore_revisione'] =
                "Bilancio o revisore non valido.";

            header('Location: /esg-balance/revisioni.php');
            exit;
        }

        try {

            $risultato = $this->repo->assegna(
                $idBilancio,
                $idRevisore
            );

            /*
            |--------------------------------------------------------------------------
            | ASSEGNAZIONE GIA ESISTENTE
            |--------------------------------------------------------------------------
            */

            if(!$risultato) {

                $_SESSION['errore_revisione'] =
                    "Questo revisore è già assegnato a questo bilancio.";

                header('Location: /esg-balance/revisioni.php');
                exit;
            }

            /*
            |--------------------------------------------------------------------------
            | ASSEGNAZIONE RIUSCITA
            |--------------------------------------------------------------------------
            */

            salvaEvento(
                "Assegnato revisore ESG"
            );

            $_SESSION['successo_revisione'] =
                "Revisore assegnato correttamente.";

        } catch(PDOException $e) {

            $_SESSION['errore_revisione'] =
                "Impossibile assegnare il revisore al bilancio.";
        }

        header('Location: /esg-balance/revisioni.php');
        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | AREA REVISORE
    |--------------------------------------------------------------------------
    */

    public function areaRevisore() {

        $utente = $this->verificaRevisore();

        $revisioni = $this->repo->getRevisioniRevisore(
            $utente['id']
        );

        require __DIR__ . '/../views/revisore/revisioni.php';
    }

    /*
    |--------------------------------------------------------------------------
    | DETTAGLIO REVISIONE REVISORE
    |--------------------------------------------------------------------------
    */

    public function dettaglio() {

        $utente = $this->verificaRevisore();

        $idBilancio = filter_input(
            INPUT_GET,
            'id',
            FILTER_VALIDATE_INT
        );

        if(!$idBilancio || $idBilancio <= 0) {

            $_SESSION['errore_revisione'] =
                "Bilancio non valido.";

            header('Location: /esg-balance/revisioni_revisore.php');
            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | CONTROLLO ASSEGNAZIONE
        |--------------------------------------------------------------------------
        */

        if(
            !$this->repo->isAssegnato(
                $idBilancio,
                $utente['id']
            )
        ) {

            $_SESSION['errore_revisione'] =
                "Non sei assegnato a questo bilancio.";

            header('Location: /esg-balance/revisioni_revisore.php');
            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | DATI DEL BILANCIO
        |--------------------------------------------------------------------------
        */

        $dettagli = $this->repo->getDettaglioBilancio(
            $idBilancio
        );

        /*
        |--------------------------------------------------------------------------
        | NOTE DEL REVISORE PER QUESTO BILANCIO
        |--------------------------------------------------------------------------
        */

        $note = $this->notaRepo->getByRevisoreEBilancio(
            $utente['id'],
            $idBilancio
        );

        /*
        |--------------------------------------------------------------------------
        | GIUDIZIO DEL REVISORE PER QUESTO BILANCIO
        |--------------------------------------------------------------------------
        */

        $giudizio = $this->giudizioRepo->getByBilancio(
            $idBilancio,
            $utente['id']
        );

        /*
        |--------------------------------------------------------------------------
        | VIEW REVISORE
        |--------------------------------------------------------------------------
        */

        require __DIR__ . '/../views/revisore/dettaglio.php';
    }

    /*
    |--------------------------------------------------------------------------
    | DETTAGLIO REVISIONE ADMIN
    |--------------------------------------------------------------------------
    */

    public function dettaglioAdmin() {

        $this->verificaAmministratore();

        $idBilancio = filter_input(
            INPUT_GET,
            'id',
            FILTER_VALIDATE_INT
        );

        if(!$idBilancio || $idBilancio <= 0) {

            $_SESSION['errore_revisione'] =
                "Bilancio non valido.";

            header('Location: /esg-balance/revisioni.php');
            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | DATI DEL BILANCIO
        |--------------------------------------------------------------------------
        */

        $dettagli = $this->repo->getDettaglioBilancio(
            $idBilancio
        );

        /*
        |--------------------------------------------------------------------------
        | TUTTE LE NOTE DEL BILANCIO
        |--------------------------------------------------------------------------
        */

        $note = $this->notaRepo->getByBilancio(
            $idBilancio
        );

        /*
        |--------------------------------------------------------------------------
        | TUTTI I GIUDIZI DEL BILANCIO
        |--------------------------------------------------------------------------
        */

        $giudizi = $this->giudizioRepo->getTuttiByBilancio(
            $idBilancio
        );

        /*
        |--------------------------------------------------------------------------
        | VIEW ADMIN - SOLA LETTURA
        |--------------------------------------------------------------------------
        */

        require __DIR__ . '/../views/admin/dettaglio_revisione.php';
    }

    /*
    |--------------------------------------------------------------------------
    | CREA NOTA
    |--------------------------------------------------------------------------
    */

    public function creaNota() {

        $utente = $this->verificaRevisore();

        if($_SERVER['REQUEST_METHOD'] !== 'POST') {

            header('Location: /esg-balance/revisioni_revisore.php');
            exit;
        }

        $idBilancio = filter_input(
            INPUT_POST,
            'id_bilancio',
            FILTER_VALIDATE_INT
        );

        $idVoceBilancio = filter_input(
            INPUT_POST,
            'id_voce_bilancio',
            FILTER_VALIDATE_INT
        );

        $testo = trim($_POST['testo'] ?? '');

        if(
            !$idBilancio ||
            !$idVoceBilancio ||
            $idBilancio <= 0 ||
            $idVoceBilancio <= 0
        ) {

            $_SESSION['errore_revisione'] =
                "Dati della nota non validi.";

            header('Location: /esg-balance/revisioni_revisore.php');
            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | CONTROLLO ASSEGNAZIONE
        |--------------------------------------------------------------------------
        */

        if(
            !$this->repo->isAssegnato(
                $idBilancio,
                $utente['id']
            )
        ) {

            $_SESSION['errore_revisione'] =
                "Non sei assegnato a questo bilancio.";

            header('Location: /esg-balance/revisioni_revisore.php');
            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | CONTROLLO VOCE DEL BILANCIO
        |--------------------------------------------------------------------------
        */

        if(
            !$this->repo->voceAppartieneAlBilancio(
                $idVoceBilancio,
                $idBilancio
            )
        ) {

            $_SESSION['errore_revisione'] =
                "La voce selezionata non appartiene a questo bilancio.";

            header(
                'Location: /esg-balance/revisione_dettaglio.php?id=' .
                $idBilancio
            );

            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | CONTROLLO TESTO
        |--------------------------------------------------------------------------
        */

        if($testo === '') {

            $_SESSION['errore_revisione'] =
                "Il testo della nota non può essere vuoto.";

            header(
                'Location: /esg-balance/revisione_dettaglio.php?id=' .
                $idBilancio
            );

            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | CONTROLLO REVISIONE CONCLUSA
        |--------------------------------------------------------------------------
        */

        if(
            $this->giudizioRepo->esisteGiudizio(
                $idBilancio,
                $utente['id']
            )
        ) {

            $_SESSION['errore_revisione'] =
                "La revisione è già conclusa. Non è possibile aggiungere nuove note.";

            header(
                'Location: /esg-balance/revisione_dettaglio.php?id=' .
                $idBilancio
            );

            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | CREA MODEL NOTA
        |--------------------------------------------------------------------------
        */

        $nota = new NotaRevisione(
            null,
            $utente['id'],
            $idVoceBilancio,
            date('Y-m-d'),
            $testo
        );

        /*
        |--------------------------------------------------------------------------
        | SALVA NOTA
        |--------------------------------------------------------------------------
        */

        try {

            $this->notaRepo->create(
                $nota
            );

            salvaEvento(
                "Creata nota revisione ESG"
            );

            $_SESSION['successo_revisione'] =
                "Nota inserita correttamente.";

        } catch(PDOException $e) {

            $_SESSION['errore_revisione'] =
                "Impossibile inserire la nota.";
        }

        header(
            'Location: /esg-balance/revisione_dettaglio.php?id=' .
            $idBilancio
        );

        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | CREA GIUDIZIO
    |--------------------------------------------------------------------------
    */

    public function creaGiudizio() {

        $utente = $this->verificaRevisore();

        if($_SERVER['REQUEST_METHOD'] !== 'POST') {

            header('Location: /esg-balance/revisioni_revisore.php');
            exit;
        }

        $idBilancio = filter_input(
            INPUT_POST,
            'id_bilancio',
            FILTER_VALIDATE_INT
        );

        $esito = trim($_POST['esito'] ?? '');

        $rilievi = trim($_POST['rilievi'] ?? '');

        /*
        |--------------------------------------------------------------------------
        | VALIDAZIONE BILANCIO
        |--------------------------------------------------------------------------
        */

        if(!$idBilancio || $idBilancio <= 0) {

            $_SESSION['errore_revisione'] =
                "Bilancio non valido.";

            header('Location: /esg-balance/revisioni_revisore.php');
            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | CONTROLLO ASSEGNAZIONE
        |--------------------------------------------------------------------------
        */

        if(
            !$this->repo->isAssegnato(
                $idBilancio,
                $utente['id']
            )
        ) {

            $_SESSION['errore_revisione'] =
                "Non sei assegnato a questo bilancio.";

            header('Location: /esg-balance/revisioni_revisore.php');
            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDAZIONE ESITO
        |--------------------------------------------------------------------------
        */

        $esitiConsentiti = [
            'approvazione',
            'approvazione con rilievi',
            'respingimento'
        ];

        if(!in_array($esito, $esitiConsentiti, true)) {

            $_SESSION['errore_revisione'] =
                "Esito del giudizio non valido.";

            header(
                'Location: /esg-balance/revisione_dettaglio.php?id=' .
                $idBilancio
            );

            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | CONTROLLO RILIEVI
        |--------------------------------------------------------------------------
        */

        if(
            $esito === 'approvazione con rilievi' &&
            $rilievi === ''
        ) {

            $_SESSION['errore_revisione'] =
                "Inserisci i rilievi per l'approvazione con rilievi.";

            header(
                'Location: /esg-balance/revisione_dettaglio.php?id=' .
                $idBilancio
            );

            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | CONTROLLO GIUDIZIO GIA PRESENTE
        |--------------------------------------------------------------------------
        */

        if(
            $this->giudizioRepo->esisteGiudizio(
                $idBilancio,
                $utente['id']
            )
        ) {

            $_SESSION['errore_revisione'] =
                "Hai già inviato il giudizio finale per questa revisione.";

            header(
                'Location: /esg-balance/revisione_dettaglio.php?id=' .
                $idBilancio
            );

            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | CREA MODEL GIUDIZIO
        |--------------------------------------------------------------------------
        */

        $giudizio = new GiudizioRevisione(
            null,
            $idBilancio,
            $utente['id'],
            $esito,
            date('Y-m-d'),
            $rilievi !== '' ? $rilievi : null
        );

        /*
        |--------------------------------------------------------------------------
        | SALVA GIUDIZIO
        |--------------------------------------------------------------------------
        */

        try {

            $this->giudizioRepo->create(
                $giudizio
            );

            /*
            |--------------------------------------------------------------------------
            | AGGIORNA DATI REVISORE IN SESSIONE
            |--------------------------------------------------------------------------
            */

            $revisoreRepo = new RevisoreRepository();

            $datiRevisore = $revisoreRepo->getByUtente(
                $utente['id']
            );

            if($datiRevisore) {

                $_SESSION['utente']['numero_revisioni'] =
                    $datiRevisore->numero_revisioni;

                $_SESSION['utente']['indice_affidabilita'] =
                    $datiRevisore->indice_affidabilita;

                $_SESSION['utente']['livello_affidabilita'] =
                    $datiRevisore->getLivelloAffidabilita();
            }

            /*
            |--------------------------------------------------------------------------
            | LOGGER
            |--------------------------------------------------------------------------
            */

            salvaEvento(
                "Creato giudizio revisione ESG"
            );

            $_SESSION['successo_revisione'] =
                "Giudizio finale inviato correttamente. La revisione è ora conclusa.";

        } catch(PDOException $e) {

            if($e->getCode() === '23000') {

                $_SESSION['errore_revisione'] =
                    "Hai già inviato il giudizio finale per questa revisione.";

            } else {

                $_SESSION['errore_revisione'] =
                    "Impossibile salvare il giudizio finale.";
            }
        }

        header(
            'Location: /esg-balance/revisione_dettaglio.php?id=' .
            $idBilancio
        );

        exit;
    }
}

?>