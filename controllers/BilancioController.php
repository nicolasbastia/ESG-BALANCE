<?php

session_start();

require_once __DIR__ . '/../repositories/BilancioRepository.php';
require_once __DIR__ . '/../repositories/NotaRepository.php';
require_once __DIR__ . '/../repositories/GiudizioRepository.php';
require_once __DIR__ . '/../config/logger.php';

class BilancioController {

    private $repo;

    public function __construct() {

        $this->repo = new BilancioRepository();
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index() {

        $utente = $_SESSION['utente'];

        $bilanci = $this->repo->getByResponsabile(
            $utente['id']
        );

        $aziende = $this->repo->getAziendeResponsabile(
            $utente['id']
        );

        require __DIR__ . '/../views/responsabile/bilanci.php';
    }

    /*
    |--------------------------------------------------------------------------
    | DETTAGLIO
    |--------------------------------------------------------------------------
    */

    public function dettaglio() {

        $idBilancio = $_GET['id'];

        /*
        |--------------------------------------------------------------------------
        | DATI BILANCIO
        |--------------------------------------------------------------------------
        */

        $dettagli = $this->repo->getDettaglioBilancio(
            $idBilancio
        );

        /*
        |--------------------------------------------------------------------------
        | NOTE
        |--------------------------------------------------------------------------
        */

        $notaRepo = new NotaRepository();

        $note = $notaRepo->getByBilancio(
            $idBilancio
        );

        /*
        |--------------------------------------------------------------------------
        | GIUDIZIO
        |--------------------------------------------------------------------------
        */

        $giudizioRepo = new GiudizioRepository();

        $giudizi = $giudizioRepo->getTuttiByBilancio(
            $idBilancio
        );

        require __DIR__ . '/../views/responsabile/dettaglio.php';
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create() {

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $idAzienda = $_POST['id_azienda'];

            $dataCreazione = date('Y-m-d');

            $this->repo->create(

                $idAzienda,
                $dataCreazione

            );

            salvaEvento(
                "Creato bilancio azienda ID: " . $idAzienda
            );

            header('Location: bilanci.php');

            exit;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function delete() {

        if(isset($_GET['id'])) {

            $this->repo->delete(
                $_GET['id']
            );

            salvaEvento(
                "Eliminato bilancio ID: " . $_GET['id']
            );

            header('Location: bilanci.php');

            exit;
        }
    }
}
?>