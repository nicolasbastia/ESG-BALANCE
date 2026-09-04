<?php

session_start();

require_once __DIR__ . '/../repositories/IndicatoreRepository.php';
require_once __DIR__ . '/../config/logger.php';

class IndicatoreController {

    private $repo;

    public function __construct() {

        $this->repo = new IndicatoreRepository();
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index() {

        $indicatori = $this->repo->getAll();

        require __DIR__ . '/../views/admin/indicatori.php';
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create() {

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $nome = $_POST['nome'] ?? '';

            $immagine = $_POST['immagine'] ?? null;

            $rilevanza = $_POST['rilevanza'] ?? 0;

            $categoria = $_POST['categoria'] ?? 'nessuna';

            /*
            |--------------------------------------------------------------------------
            | CAMPI AMBIENTALI
            |--------------------------------------------------------------------------
            */

            $codiceNormativa = null;

            if($categoria === 'ambientale') {

                $codiceNormativa =
                    $_POST['codice_normativa'] ?? null;
            }

            /*
            |--------------------------------------------------------------------------
            | CAMPI SOCIALI
            |--------------------------------------------------------------------------
            */

            $ambitoSociale = null;

            $frequenzaRilevazione = null;

            if($categoria === 'sociale') {

                $ambitoSociale =
                    $_POST['ambito_sociale'] ?? null;

                $frequenzaRilevazione =
                    $_POST['frequenza_rilevazione'] ?? null;
            }

            /*
            |--------------------------------------------------------------------------
            | VALIDAZIONE BASE
            |--------------------------------------------------------------------------
            */

            if(
                empty($nome) ||
                $rilevanza < 0 ||
                $rilevanza > 10
            ) {

                header('Location: indicatori.php');

                exit;
            }

            /*
            |--------------------------------------------------------------------------
            | VALIDAZIONE AMBIENTALE
            |--------------------------------------------------------------------------
            */

            if(
                $categoria === 'ambientale' &&
                empty($codiceNormativa)
            ) {

                header('Location: indicatori.php');

                exit;
            }

            /*
            |--------------------------------------------------------------------------
            | VALIDAZIONE SOCIALE
            |--------------------------------------------------------------------------
            */

            if(
                $categoria === 'sociale' &&
                (
                    empty($ambitoSociale) ||
                    empty($frequenzaRilevazione)
                )
            ) {

                header('Location: indicatori.php');

                exit;
            }

            /*
            |--------------------------------------------------------------------------
            | CREATE
            |--------------------------------------------------------------------------
            */

            $this->repo->create(

                $nome,
                $immagine,
                $rilevanza,
                $categoria,
                $codiceNormativa,
                $ambitoSociale,
                $frequenzaRilevazione

            );

            salvaEvento(
                "Creato indicatore ESG: " .
                $nome .
                " - categoria: " .
                $categoria
            );

            header('Location: indicatori.php');

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
                "Eliminato indicatore ESG ID: " . $_GET['id']
            );

            header('Location: indicatori.php');

            exit;
        }
    }
}
?>