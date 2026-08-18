<?php

session_start();

require_once __DIR__ . '/../repositories/AziendaRepository.php';
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

            if(isset($_FILES['logo'])) {

                $nomeFile = time() . '_' . $_FILES['logo']['name'];

                $path = 'uploads/loghi/' . $nomeFile;

                move_uploaded_file(

                    $_FILES['logo']['tmp_name'],
                    $path

                );

                $logo = $path;
            }

            $this->repo->create(

                $nome,
                $ragioneSociale,
                $partitaIva,
                $settore,
                $numeroDipendenti,
                $logo,
                $utente['id']

            );

            salvaEvento(
                "Creata azienda: " . $nome
            );

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

            $this->repo->delete(
                $_GET['id']
            );

            salvaEvento(
                "Eliminata azienda ID: " . $_GET['id']
            );

            header('Location: aziende.php');

            exit;
        }
    }
}
?>