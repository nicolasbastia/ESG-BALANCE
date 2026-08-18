<?php

session_start();

require_once __DIR__ . '/../repositories/VoceIndicatoreRepository.php';
require_once __DIR__ . '/../config/logger.php';

class VoceIndicatoreController {

    private $repo;

    public function __construct() {

        $this->repo = new VoceIndicatoreRepository();
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index() {

        $idBilancio = $_GET['id'];

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

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

           $this->repo->create(

                $_POST['id_voce_bilancio'],
                $_POST['id_indicatore'],
                $_POST['valore'],
                $_POST['fonte'],
                $_POST['data_rilevazione']

            );

            salvaEvento(
                "Collegato indicatore ESG a bilancio"
            );

            header(

                'Location: esg_bilancio.php?id=' .
                $_POST['id_bilancio']

            );

            exit;
        }
    }
}
?>