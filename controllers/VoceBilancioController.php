<?php

session_start();

require_once __DIR__ . '/../repositories/VoceBilancioRepository.php';
require_once __DIR__ . '/../config/logger.php';

class VoceBilancioController {

    private $repo;

    public function __construct() {

        $this->repo = new VoceBilancioRepository();
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index() {

        $idBilancio = $_GET['id'];

        $template = $this->repo->getTemplate();

        $vociBilancio = $this->repo->getByBilancio(
            $idBilancio
        );

        /*
        |--------------------------------------------------------------------------
        | MAP VALORI
        |--------------------------------------------------------------------------
        */

        $valori = [];

        foreach($vociBilancio as $v) {

            $idVoce = (int)$v['id_voce'];

            $valori[$idVoce] = $v['valore'];
        }

        require __DIR__ . '/../views/responsabile/voci_bilancio.php';
    }

    /*
    |--------------------------------------------------------------------------
    | SAVE
    |--------------------------------------------------------------------------
    */

public function save() {

    if($_SERVER['REQUEST_METHOD'] === 'POST') {

        $idBilancio = $_POST['id_bilancio'];

        foreach($_POST['valori'] as $idVoce => $valore) {

            if($valore !== '') {

                $this->repo->salvaValore(

                    $idBilancio,
                    $idVoce,
                    $valore

                );
            }
        }

        salvaEvento(
            "Aggiornate voci bilancio ID: " . $idBilancio
        );

        header(

            'Location: voci_bilancio.php?id=' . $idBilancio

        );

        exit;
    }
}
}
?>