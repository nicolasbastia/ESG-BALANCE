<?php

session_start();

require_once __DIR__ . '/../repositories/RevisioneRepository.php';
require_once __DIR__ . '/../repositories/NotaRepository.php';
require_once __DIR__ . '/../config/logger.php';
require_once __DIR__ . '/../repositories/GiudizioRepository.php';
require_once __DIR__ . '/../repositories/UtenteRepository.php';

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
    | INDEX ADMIN
    |--------------------------------------------------------------------------
    */

    public function index() {

        $bilanci = $this->repo->getBilanci();

        $revisori = $this->repo->getRevisori();

        $revisioni = $this->repo->getRevisioni();

        require __DIR__ . '/../views/admin/revisioni.php';
    }

    public function assegna() {

    if($_SERVER['REQUEST_METHOD'] === 'POST') {

        $risultato = $this->repo->assegna(

            $_POST['id_bilancio'],
            $_POST['id_revisore']

        );

        /*
        |--------------------------------------------------------------------------
        | ASSEGNAZIONE GIA ESISTENTE
        |--------------------------------------------------------------------------
        */

        if(!$risultato) {

            $_SESSION['errore_revisione'] =
                "Questo revisore è già assegnato a questo bilancio.";

            header('Location: revisioni.php');

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

        header('Location: revisioni.php');

        exit;
    }
}

    /*
    |--------------------------------------------------------------------------
    | AREA REVISORE
    |--------------------------------------------------------------------------
    */

    public function areaRevisore() {

        $utente = $_SESSION['utente'];

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

    $idBilancio = $_GET['id'];

    /*
    |--------------------------------------------------------------------------
    | DATI DEL BILANCIO
    |--------------------------------------------------------------------------
    */

    $dettagli = $this->repo->getDettaglioBilancio(

        $idBilancio

    );

    $utente = $_SESSION['utente'];

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

        $utente = $_SESSION['utente'];

        /*
        |----------------------------------------------------------------------
        | CONTROLLO RUOLO
        |----------------------------------------------------------------------
        */

        if($utente['ruolo'] !== 'amministratore') {

            header('Location: index.php');

            exit;
        }

        $idBilancio = $_GET['id'];

        /*
        |----------------------------------------------------------------------
        | DATI DEL BILANCIO
        |----------------------------------------------------------------------
        */

        $dettagli = $this->repo->getDettaglioBilancio(

            $idBilancio

        );

        /*
        |----------------------------------------------------------------------
        | TUTTE LE NOTE DEL BILANCIO
        |----------------------------------------------------------------------
        */

        $note = $this->notaRepo->getByBilancio(

            $idBilancio

        );

        /*
        |----------------------------------------------------------------------
        | TUTTI I GIUDIZI DEL BILANCIO
        |----------------------------------------------------------------------
        */

        $giudizi = $this->giudizioRepo->getTuttiByBilancio(

            $idBilancio

        );

        /*
        |----------------------------------------------------------------------
        | VIEW ADMIN - SOLA LETTURA
        |----------------------------------------------------------------------
        */

        require __DIR__ . '/../views/admin/dettaglio_revisione.php';
    }

    /*
    |--------------------------------------------------------------------------
    | CREA NOTA
    |--------------------------------------------------------------------------
    */

    public function creaNota() {

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $utente = $_SESSION['utente'];

            $this->notaRepo->create(

                $utente['id'],
                $_POST['id_voce_bilancio'],
                $_POST['testo']

            );

            salvaEvento(
                "Creata nota revisione ESG"
            );

            header(

                'Location: revisione_dettaglio.php?id=' .
                $_POST['id_bilancio']

            );

            exit;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | CREA GIUDIZIO
    |--------------------------------------------------------------------------
    */

    public function creaGiudizio() {

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $utente = $_SESSION['utente'];

            $this->giudizioRepo->create(

                $_POST['id_bilancio'],
                $utente['id'],
                $_POST['esito'],
                $_POST['rilievi']

            );


            /*
            |--------------------------------------------------------------------------
            | AGGIORNA DATI REVISORE IN SESSIONE
            |--------------------------------------------------------------------------
            */

            $utenteRepo = new UtenteRepository();

            $datiRevisore = $utenteRepo->getDatiRevisore(

                $utente['id']

            );

            if($datiRevisore) {

                $_SESSION['utente']['numero_revisioni'] =
                    $datiRevisore['numero_revisioni'];

                $_SESSION['utente']['indice_affidabilita'] =
                    $datiRevisore['indice_affidabilita'];
            }
            

        /*
        |--------------------------------------------------------------------------
        | LOGGER
        |--------------------------------------------------------------------------
        */
            salvaEvento(
                "Creato giudizio revisione ESG"
            );

            header(

                'Location: revisione_dettaglio.php?id=' .
                $_POST['id_bilancio']

            );

            exit;
        }
    }
}

?>