<?php

require_once __DIR__ . '/../repositories/RegisterRepository.php';

class RegisterController {

    private $repo;

    public function __construct() {

        $this->repo = new RegisterRepository();
    }

    /*
    |--------------------------------------------------------------------------
    | FORM REGISTRAZIONE
    |--------------------------------------------------------------------------
    */

    public function index() {

        require __DIR__ . '/../views/auth/register.php';
    }

    /*
    |--------------------------------------------------------------------------
    | REGISTRAZIONE
    |--------------------------------------------------------------------------
    */

    public function register() {

        if($_SERVER['REQUEST_METHOD'] !== 'POST') {

            header('Location: register.php');
            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | RECUPERO DATI
        |--------------------------------------------------------------------------
        */

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $codiceFiscale = strtoupper(
            trim($_POST['codice_fiscale'] ?? '')
        );
        $dataNascita = $_POST['data_nascita'] ?? '';
        $luogoNascita = trim($_POST['luogo_nascita'] ?? '');
        $ruolo = $_POST['ruolo'] ?? '';

        $emails = $_POST['emails'] ?? [];

        /*
        |--------------------------------------------------------------------------
        | VALIDAZIONE CAMPI OBBLIGATORI
        |--------------------------------------------------------------------------
        */

        if(
            $username === '' ||
            $password === '' ||
            $codiceFiscale === '' ||
            $dataNascita === '' ||
            $luogoNascita === '' ||
            $ruolo === ''
        ) {

            $errore = "Compila tutti i campi obbligatori.";

            require __DIR__ . '/../views/auth/register.php';

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDAZIONE RUOLO
        |--------------------------------------------------------------------------
        */

        $ruoliValidi = [
            'amministratore',
            'revisore',
            'responsabile'
        ];

        if(!in_array($ruolo, $ruoliValidi, true)) {

            $errore = "Ruolo non valido.";

            require __DIR__ . '/../views/auth/register.php';

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDAZIONE PASSWORD
        |--------------------------------------------------------------------------
        */

        if(strlen($password) < 8) {

            $errore = "La password deve contenere almeno 8 caratteri.";

            require __DIR__ . '/../views/auth/register.php';

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDAZIONE CODICE FISCALE
        |--------------------------------------------------------------------------
        */

        if(strlen($codiceFiscale) !== 16) {

            $errore = "Il codice fiscale deve contenere 16 caratteri.";

            require __DIR__ . '/../views/auth/register.php';

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDAZIONE EMAIL
        |--------------------------------------------------------------------------
        */

        $emailsValide = [];

        foreach($emails as $email) {

            $email = trim($email);

            if($email === '') {

                continue;
            }

            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {

                $errore = "Inserisci indirizzi email validi.";

                require __DIR__ . '/../views/auth/register.php';

                return;
            }

            /*
            |--------------------------------------------------------------------------
            | EVITA EMAIL DUPLICATE NELLO STESSO FORM
            |--------------------------------------------------------------------------
            */

            if(!in_array($email, $emailsValide, true)) {

                $emailsValide[] = $email;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | ALMENO UNA EMAIL
        |--------------------------------------------------------------------------
        */

        if(empty($emailsValide)) {

            $errore = "Inserisci almeno un indirizzo email.";

            require __DIR__ . '/../views/auth/register.php';

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | DATI DA PASSARE AL REPOSITORY
        |--------------------------------------------------------------------------
        */

        $data = [

            'username' => $username,
            'password' => $password,
            'codice_fiscale' => $codiceFiscale,
            'data_nascita' => $dataNascita,
            'luogo_nascita' => $luogoNascita,
            'ruolo' => $ruolo,
            'emails' => $emailsValide

        ];

        /*
        |--------------------------------------------------------------------------
        | CREAZIONE UTENTE
        |--------------------------------------------------------------------------
        */

        try {

            $this->repo->createUser($data);

            header('Location: login.php');

            exit;

        } catch(PDOException $e) {

            /*
            |--------------------------------------------------------------------------
            | VINCOLI UNIQUE DATABASE
            |--------------------------------------------------------------------------
            */

            if($e->getCode() === '23000') {

                $errore =
                    "Username o codice fiscale già registrato.";

            } else {

                $errore =
                    "Errore durante la registrazione. Riprova.";
            }

            require __DIR__ . '/../views/auth/register.php';

            return;

        } catch(Throwable $e) {

            $errore =
                "Errore durante la registrazione. Riprova.";

            require __DIR__ . '/../views/auth/register.php';

            return;
        }
    }
}

?>