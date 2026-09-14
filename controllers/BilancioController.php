<?php

session_start();

require_once __DIR__ . '/../repositories/BilancioRepository.php';
require_once __DIR__ . '/../repositories/NotaRepository.php';
require_once __DIR__ . '/../repositories/GiudizioRepository.php';
require_once __DIR__ . '/../models/Bilancio.php';
require_once __DIR__ . '/../config/logger.php';

class BilancioController {

    private $repo;

    public function __construct() {

        $this->repo = new BilancioRepository();
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

    private function aziendaAppartieneAlResponsabile(
        $idAzienda,
        $idResponsabile
    ) {

        $aziende = $this->repo->getAziendeResponsabile(
            $idResponsabile
        );

        foreach($aziende as $azienda) {

            if(
                (int) $azienda['id_azienda'] ===
                (int) $idAzienda
            ) {

                return true;
            }
        }

        return false;
    }

    private function bilancioAppartieneAlResponsabile(
        $idBilancio,
        $idResponsabile
    ) {

        $bilanci = $this->repo->getByResponsabile(
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

        $bilanci = $this->repo->getByResponsabile(
            $utente['id']
        );

        $aziende = $this->repo->getAziendeResponsabile(
            $utente['id']
        );

        require __DIR__ . '/../views/responsabile/bilanci.php';
    }

    /*DETTAGLIO*/

    public function dettaglio() {

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
                "Non sei autorizzato a visualizzare questo bilancio.";

            header('Location: bilanci.php');
            exit;
        }

        /*DATI BILANCIO*/

        $dettagli = $this->repo->getDettaglioBilancio(
            $idBilancio
        );

        $notaRepo = new NotaRepository();

        $note = $notaRepo->getByBilancio(
            $idBilancio
        );

        $giudizioRepo = new GiudizioRepository();

        $giudizi = $giudizioRepo->getTuttiByBilancio(
            $idBilancio
        );

        require __DIR__ . '/../views/responsabile/dettaglio.php';
    }

    public function create() {

        $utente = $this->verificaResponsabile();

        if($_SERVER['REQUEST_METHOD'] !== 'POST') {

            header('Location: bilanci.php');
            exit;
        }

        $idAzienda = filter_input(
            INPUT_POST,
            'id_azienda',
            FILTER_VALIDATE_INT
        );

        if(!$idAzienda || $idAzienda <= 0) {

            $_SESSION['errore_bilancio'] =
                "Azienda non valida.";

            header('Location: bilanci.php');
            exit;
        }

        if(
            !$this->aziendaAppartieneAlResponsabile(
                $idAzienda,
                $utente['id']
            )
        ) {

            $_SESSION['errore_bilancio'] =
                "Non sei autorizzato a creare un bilancio per questa azienda.";

            header('Location: bilanci.php');
            exit;
        }

        /*CREAZIONE MODEL BILANCIO*/

        $bilancio = new Bilancio(

            null,
            $idAzienda,
            date('Y-m-d'),
            'bozza'

        );

        try {

            $risultato = $this->repo->create(
                $bilancio
            );

            if(!$risultato) {

                $_SESSION['errore_bilancio'] =
                    "Impossibile creare il bilancio.";

                header('Location: bilanci.php');
                exit;
            }

            salvaEvento(
                "Creato bilancio azienda ID: " . $idAzienda
            );

            $_SESSION['successo_bilancio'] =
                "Bilancio creato correttamente.";

        } catch(PDOException $e) {

            $_SESSION['errore_bilancio'] =
                "Errore durante la creazione del bilancio.";
        }

        header('Location: bilanci.php');
        exit;
    }

    /*DELETE*/

    public function delete() {

        $utente = $this->verificaResponsabile();

        if($_SERVER['REQUEST_METHOD'] !== 'POST') {

            header('Location: bilanci.php');
            exit;
        }

        $idBilancio = filter_input(
            INPUT_POST,
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
                "Non sei autorizzato a eliminare questo bilancio.";

            header('Location: bilanci.php');
            exit;
        }

        try {

            $risultato = $this->repo->delete(
                $idBilancio
            );

            if(!$risultato) {

                $_SESSION['errore_bilancio'] =
                    "Impossibile eliminare il bilancio.";

                header('Location: bilanci.php');
                exit;
            }

            salvaEvento(
                "Eliminato bilancio ID: " . $idBilancio
            );

            $_SESSION['successo_bilancio'] =
                "Bilancio eliminato correttamente.";

        } catch(PDOException $e) {

            $_SESSION['errore_bilancio'] =
                "Errore durante l'eliminazione del bilancio.";
        }

        header('Location: bilanci.php');
        exit;
    }
}

?>