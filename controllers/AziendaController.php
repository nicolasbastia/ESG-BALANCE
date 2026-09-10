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

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index() {

        $utente = $_SESSION['utente'];

        $aziende = $this->repo->getByResponsabile(
            $utente['id']
        );

        require __DIR__ . '/../views/responsabile/aziende.php';
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create() {

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $utente = $_SESSION['utente'];

            $nome = $_POST['nome'];
            $ragioneSociale = $_POST['ragione_sociale'];
            $partitaIva = $_POST['partita_iva'];
            $settore = $_POST['settore'];
            $numeroDipendenti = $_POST['numero_dipendenti'];

            /*
            |--------------------------------------------------------------------------
            | UPLOAD LOGO
            |--------------------------------------------------------------------------
            */

            $logo = '';

            if(
                isset($_FILES['logo']) &&
                $_FILES['logo']['error'] === UPLOAD_ERR_OK
            ) {

                $nomeFile = time() . '_' . basename($_FILES['logo']['name']);

                $path = 'uploads/loghi/' . $nomeFile;

                move_uploaded_file(

                    $_FILES['logo']['tmp_name'],
                    $path

                );

                $logo = $path;
            }

            /*
            |--------------------------------------------------------------------------
            | CREA MODEL AZIENDA
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

            $this->repo->create(
                $azienda
            );

            salvaEvento(
                "Creata azienda: " . $nome
            );

            $_SESSION['successo_azienda'] =
                "Azienda creata correttamente.";

            header('Location: aziende.php');

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

            $idAzienda = $_GET['id'];

            $risultato = $this->repo->delete(
                $idAzienda
            );

            /*
            |--------------------------------------------------------------------------
            | AZIENDA NON ELIMINABILE
            |--------------------------------------------------------------------------
            */

            if(!$risultato) {

                $_SESSION['errore_azienda'] =
                    "Impossibile eliminare l'azienda perché uno o più bilanci sono coinvolti in revisioni ESG.";

                header('Location: aziende.php');

                exit;
            }

            /*
            |--------------------------------------------------------------------------
            | ELIMINAZIONE RIUSCITA
            |--------------------------------------------------------------------------
            */

            salvaEvento(
                "Eliminata azienda ID: " . $idAzienda
            );

            $_SESSION['successo_azienda'] =
                "Azienda eliminata correttamente.";

            header('Location: aziende.php');

            exit;
        }
    }
}
?>