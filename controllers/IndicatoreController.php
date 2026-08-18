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

            $nome = $_POST['nome'];
            $immagine = $_POST['immagine'];
            $rilevanza = $_POST['rilevanza'];

            $this->repo->create(

                $nome,
                $immagine,
                $rilevanza

            );

            salvaEvento(
                "Creato indicatore ESG: " . $nome
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