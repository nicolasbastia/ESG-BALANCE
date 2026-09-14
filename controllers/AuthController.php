<?php

session_start();

require_once __DIR__ . '/../repositories/UtenteRepository.php';
require_once __DIR__ . '/../repositories/RevisoreRepository.php';

class AuthController {


    public function login() {

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            if(
                $username === '' ||
                $password === ''
            ) {

                $errore = "Inserisci username e password.";

                require __DIR__ . '/../views/auth/login.php';

                return;
            }

            $utenteRepo = new UtenteRepository();

            $utente = $utenteRepo->login(
                $username,
                $password
            );

            if($utente) {

                session_regenerate_id(true);

                $_SESSION['utente'] = [

                    'id' => $utente->id_utente,
                    'username' => $utente->username,
                    'ruolo' => $utente->ruolo

                ];

                /* DATI AGGIUNTIVI REVISORE*/

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


                header('Location: /esg-balance/index.php');

                exit;
            }


            $errore = "Credenziali non valide";
        }

        require __DIR__ . '/../views/auth/login.php';
    }
}

?>