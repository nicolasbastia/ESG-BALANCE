<?php

session_start();

require_once __DIR__ . '/../repositories/UtenteRepository.php';

class AuthController {

    public function login() {

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $username = $_POST['username'];
            $password = $_POST['password'];

            $repo = new UtenteRepository();

            $utente = $repo->login(
                $username,
                $password
            );

            if($utente) {

                $_SESSION['utente'] = [

                    'id' => $utente->id,
                    'username' => $utente->username,
                    'ruolo' => $utente->ruolo

                ];

                /*
                |--------------------------------------------------------------------------
                | DATI AGGIUNTIVI REVISORE
                |--------------------------------------------------------------------------
                */

                if($utente->ruolo === 'revisore') {

                    $datiRevisore = $repo->getDatiRevisore(
                        $utente->id
                    );

                    if($datiRevisore) {

                        $_SESSION['utente']['numero_revisioni'] =
                            $datiRevisore['numero_revisioni'];

                        $_SESSION['utente']['indice_affidabilita'] =
                            $datiRevisore['indice_affidabilita'];
                    }
                }

                header('Location: index.php');

                exit;
            }

            else {

                $errore = "Credenziali non valide";
            }
        }

        require __DIR__ . '/../views/auth/login.php';
    }
}

?>