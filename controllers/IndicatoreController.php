<?php

session_start();

require_once __DIR__ . '/../repositories/IndicatoreRepository.php';
require_once __DIR__ . '/../models/IndicatoreESG.php';
require_once __DIR__ . '/../config/logger.php';

class IndicatoreController {

    private $repo;

    public function __construct() {

        $this->repo = new IndicatoreRepository();
    }

    private function verificaAmministratore() {

        if(
            !isset($_SESSION['utente']) ||
            $_SESSION['utente']['ruolo'] !== 'amministratore'
        ) {

            header('Location: login.php');
            exit;
        }

        return $_SESSION['utente'];
    }


    public function index() {

        $this->verificaAmministratore();

        $indicatori = $this->repo->getAll();

        require __DIR__ . '/../views/admin/indicatori.php';
    }

    public function create() {

        $this->verificaAmministratore();

        if($_SERVER['REQUEST_METHOD'] !== 'POST') {

            header('Location: indicatori.php');
            exit;
        }

        $nome = trim(
            $_POST['nome'] ?? ''
        );

        $immagine = trim(
            $_POST['immagine'] ?? ''
        );

        $rilevanza = filter_input(
            INPUT_POST,
            'rilevanza',
            FILTER_VALIDATE_INT
        );

        $categoria = trim(
            $_POST['categoria'] ?? 'nessuna'
        );


        $categorieValide = [
            'nessuna',
            'ambientale',
            'sociale'
        ];

        if(!in_array($categoria, $categorieValide, true)) {

            $_SESSION['errore_indicatore'] =
                "Categoria non valida.";

            header('Location: indicatori.php');
            exit;
        }


        if($nome === '') {

            $_SESSION['errore_indicatore'] =
                "Il nome dell'indicatore è obbligatorio.";

            header('Location: indicatori.php');
            exit;
        }

        if(
            $rilevanza === false ||
            $rilevanza < 0 ||
            $rilevanza > 10
        ) {

            $_SESSION['errore_indicatore'] =
                "La rilevanza deve essere compresa tra 0 e 10.";

            header('Location: indicatori.php');
            exit;
        }

        /* CAMPI SPECIFICI */

        $codiceNormativa = null;
        $ambitoSociale = null;
        $frequenzaRilevazione = null;

        /* AMBIENTALE */

        if($categoria === 'ambientale') {

            $codiceNormativa = trim(
                $_POST['codice_normativa'] ?? ''
            );

            if($codiceNormativa === '') {

                $_SESSION['errore_indicatore'] =
                    "Inserisci il codice della normativa ambientale.";

                header('Location: indicatori.php');
                exit;
            }
        }

        /*SOCIALE*/

        if($categoria === 'sociale') {

            $ambitoSociale = trim(
                $_POST['ambito_sociale'] ?? ''
            );

            $frequenzaRilevazione = trim(
                $_POST['frequenza_rilevazione'] ?? ''
            );

            if(
                $ambitoSociale === '' ||
                $frequenzaRilevazione === ''
            ) {

                $_SESSION['errore_indicatore'] =
                    "Compila tutti i campi dell'indicatore sociale.";

                header('Location: indicatori.php');
                exit;
            }
        }

        if($immagine === '') {
            $immagine = null;
        }

        /* CREAZIONE MODEL */

        $indicatore = new IndicatoreESG(
            null,
            $nome,
            $immagine,
            $rilevanza,
            $categoria,
            $codiceNormativa,
            $ambitoSociale,
            $frequenzaRilevazione
        );


        try {

            $risultato = $this->repo->create(
                $indicatore
            );

            if(!$risultato) {

                $_SESSION['errore_indicatore'] =
                    "Impossibile creare l'indicatore ESG.";

                header('Location: indicatori.php');
                exit;
            }

            salvaEvento(
                "Creato indicatore ESG: " .
                $nome .
                " - categoria: " .
                $categoria
            );

            $_SESSION['successo_indicatore'] =
                "Indicatore ESG creato correttamente.";

        } catch(PDOException $e) {

            if($e->getCode() === '23000') {

                $_SESSION['errore_indicatore'] =
                    "Esiste già un indicatore ESG con questo nome.";

            } else {

                $_SESSION['errore_indicatore'] =
                    "Errore durante la creazione dell'indicatore ESG.";
            }
        }

        header('Location: indicatori.php');
        exit;
    }


    public function delete() {

        $this->verificaAmministratore();

        if($_SERVER['REQUEST_METHOD'] !== 'POST') {

            header('Location: indicatori.php');
            exit;
        }


        $idIndicatore = filter_input(
            INPUT_POST,
            'id',
            FILTER_VALIDATE_INT
        );

        if(!$idIndicatore || $idIndicatore <= 0) {

            $_SESSION['errore_indicatore'] =
                "Indicatore non valido.";

            header('Location: indicatori.php');
            exit;
        }

        try {

            $risultato = $this->repo->delete(
                $idIndicatore
            );

            if(!$risultato) {

                $_SESSION['errore_indicatore'] =
                    "Impossibile eliminare l'indicatore ESG.";

                header('Location: indicatori.php');
                exit;
            }

            salvaEvento(
                "Eliminato indicatore ESG ID: " .
                $idIndicatore
            );

            $_SESSION['successo_indicatore'] =
                "Indicatore ESG eliminato correttamente.";

        } catch(PDOException $e) {

            $_SESSION['errore_indicatore'] =
                "Errore durante l'eliminazione dell'indicatore ESG.";
        }

        header('Location: indicatori.php');
        exit;
    }
}

?>