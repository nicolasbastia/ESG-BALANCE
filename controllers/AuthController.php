<?php

session_start();

require_once __DIR__ . '/../repositories/UtenteRepository.php';
require_once __DIR__ . '/../repositories/RevisoreRepository.php';

class AuthController {

    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    public function login() {

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            /*
            |--------------------------------------------------------------------------
            | RECUPERO DATI FORM
            |--------------------------------------------------------------------------
            */

            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            /*
            |--------------------------------------------------------------------------
            | CONTROLLO CAMPI
            |--------------------------------------------------------------------------
            */

            if(
                $username === '' ||
                $password === ''
            ) {

                $errore = "Inserisci username e password.";

                require __DIR__ . '/../views/auth/login.php';

                return;
            }

            /*
            |--------------------------------------------------------------------------
            | LOGIN
            |--------------------------------------------------------------------------
            */

            $utenteRepo = new UtenteRepository();

            $utente = $utenteRepo->login(
                $username,
                $password
            );

            if($utente) {

                /*
                |--------------------------------------------------------------------------
                | RIGENERA ID SESSIONE
                |--------------------------------------------------------------------------
                */

                session_regenerate_id(true);

                /*
                |--------------------------------------------------------------------------
                | DATI UTENTE IN SESSIONE
                |--------------------------------------------------------------------------
                */

                $_SESSION['utente'] = [

                    'id' => $utente->id_utente,
                    'username' => $utente->username,
                    'ruolo' => $utente->ruolo

                ];

                /*
                |--------------------------------------------------------------------------
                | DATI AGGIUNTIVI REVISORE
                |--------------------------------------------------------------------------
                */

                if($utente->isRevisore()) {

                    $revisoreRepo = new RevisoreRepository();

                    $datiRevisore = $revisoreRepo->getByUtente(
                        $utente->id_utente
                    );

                    if($datiRevisore) {

                        $_SESSION['utente']['numero_revisioni'] =
                            $datiRevisore->numero_revisioni;

                        $_SESSION['utente']['indice_affidabilita'] =
                            $datiRevisore->indice_affidabilita;

                        $_SESSION['utente']['livello_affidabilita'] =
                            $datiRevisore->getLivelloAffidabilita();
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | REDIRECT DASHBOARD
                |--------------------------------------------------------------------------
                */

                header('Location: /esg-balance/index.php');

                exit;
            }

            /*
            |--------------------------------------------------------------------------
            | CREDENZIALI ERRATE
            |--------------------------------------------------------------------------
            */

            $errore = "Credenziali non valide";
        }

        require __DIR__ . '/../views/auth/login.php';
    }
}

?>