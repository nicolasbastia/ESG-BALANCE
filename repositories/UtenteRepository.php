<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Utente.php';

class UtenteRepository {

    private $pdo;

    public function __construct() {

        global $pdo;

        $this->pdo = $pdo;
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    public function login(
        $username,
        $password
    ) {

        $sql = "

            SELECT
                id_utente,
                username,
                password,
                codice_fiscale,
                data_nascita,
                luogo_nascita,
                ruolo

            FROM utente

            WHERE username = ?

            LIMIT 1

        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            $username
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        /*
        |--------------------------------------------------------------------------
        | PASSWORD VERIFY
        |--------------------------------------------------------------------------
        */

        if(
            $row &&
            password_verify(
                $password,
                $row['password']
            )
        ) {

            return new Utente(
                $row['id_utente'],
                $row['username'],
                $row['ruolo'],
                $row['codice_fiscale'],
                $row['data_nascita'],
                $row['luogo_nascita']
            );
        }

        return null;
    }
}

?>