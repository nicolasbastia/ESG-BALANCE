<?php

session_start();

require_once __DIR__ . '/../repositories/VoceBilancioRepository.php';
require_once __DIR__ . '/../repositories/BilancioRepository.php';
require_once __DIR__ . '/../models/VoceBilancio.php';
require_once __DIR__ . '/../config/logger.php';

class VoceBilancioController {

    private $repo;
    private $bilancioRepo;

    public function __construct() {

        $this->repo = new VoceBilancioRepository();
        $this->bilancioRepo = new BilancioRepository();
    }


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


    public function index() {

        $utente = $this->verificaResponsabile();

        $idBilancio = filter_input(
            INPUT_GET,
            'id',
            FILTER_VALIDATE_INT
        );

        if(!$idBilancio || $idBilancio <= 0) {

            $_SESSION['errore_bilancio'] =
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

            $_SESSION['errore_bilancio'] =
                "Non sei autorizzato a gestire questo bilancio.";

            header('Location: bilanci.php');
            exit;
        }

        $template = $this->repo->getTemplate();


        $vociBilancio = $this->repo->getByBilancio(
            $idBilancio
        );


        $valori = [];

        foreach($vociBilancio as $v) {

            $idVoce = (int) $v->id_voce;

            $valori[$idVoce] = $v->valore;
        }

        require __DIR__ . '/../views/responsabile/voci_bilancio.php';
    }


    public function save() {

        $utente = $this->verificaResponsabile();

        if($_SERVER['REQUEST_METHOD'] !== 'POST') {

            header('Location: bilanci.php');
            exit;
        }


        $idBilancio = filter_input(
            INPUT_POST,
            'id_bilancio',
            FILTER_VALIDATE_INT
        );

        if(!$idBilancio || $idBilancio <= 0) {

            $_SESSION['errore_bilancio'] =
                "Bilancio non valido.";

            header('Location: bilanci.php');
            exit;
        }

        /* CONTROLLI */

        if(
            !$this->bilancioAppartieneAlResponsabile(
                $idBilancio,
                $utente['id']
            )
        ) {

            $_SESSION['errore_bilancio'] =
                "Non sei autorizzato a modificare questo bilancio.";

            header('Location: bilanci.php');
            exit;
        }

        if(
            !isset($_POST['valori']) ||
            !is_array($_POST['valori'])
        ) {

            $_SESSION['errore_bilancio'] =
                "Nessun valore da salvare.";

            header(
                'Location: voci_bilancio.php?id=' . $idBilancio
            );

            exit;
        }

        /* SALVATAGGIO */

        try {

            foreach($_POST['valori'] as $idVoce => $valore) {


                $idVoce = filter_var(
                    $idVoce,
                    FILTER_VALIDATE_INT
                );

                if(!$idVoce || $idVoce <= 0) {
                    continue;
                }


                $valore = trim((string) $valore);

                if($valore === '') {
                    continue;
                }

                if(!is_numeric($valore)) {
                    continue;
                }

                /* MODEL */

                $voceBilancio = new VoceBilancio(
                    null,
                    $idBilancio,
                    $idVoce,
                    $valore
                );


                $this->repo->salvaValore(
                    $voceBilancio
                );
            }

            salvaEvento(
                "Aggiornate voci bilancio ID: " . $idBilancio
            );

            $_SESSION['successo_bilancio'] =
                "Voci del bilancio aggiornate correttamente.";

        } catch(PDOException $e) {

            $_SESSION['errore_bilancio'] =
                "Errore durante il salvataggio delle voci del bilancio.";
        }

        header(
            'Location: voci_bilancio.php?id=' . $idBilancio
        );

        exit;
    }
}

?>