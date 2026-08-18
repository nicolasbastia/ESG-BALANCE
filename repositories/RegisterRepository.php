<?php

require_once __DIR__ . '/../config/db.php';

class RegisterRepository {

    private $pdo;

    public function __construct() {

        global $pdo;

        $this->pdo = $pdo;
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE USER
    |--------------------------------------------------------------------------
    */

    public function createUser($data) {

        $sql = "

            INSERT INTO utente(

                username,
                password,
                codice_fiscale,
                data_nascita,
                luogo_nascita,
                ruolo

            )

            VALUES(

                ?,
                ?,
                ?,
                ?,
                ?,
                ?

            )

        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([

            $data['username'],
            password_hash(
                $data['password'],
                PASSWORD_DEFAULT
            ),
            $data['codice_fiscale'],
            $data['data_nascita'],
            $data['luogo_nascita'],
            $data['ruolo']

        ]);

        $idUtente = $this->pdo->lastInsertId();

        /*
        |--------------------------------------------------------------------------
        | EMAIL
        |--------------------------------------------------------------------------
        */

        foreach($data['emails'] as $email) {

            if(!empty($email)) {

                $sql = "

                    INSERT INTO email_utente(

                        id_utente,
                        email

                    )

                    VALUES(

                        ?,
                        ?

                    )

                ";

                $stmt = $this->pdo->prepare($sql);

                $stmt->execute([

                    $idUtente,
                    $email

                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | TABELLE RUOLI
        |--------------------------------------------------------------------------
        */

        if($data['ruolo'] == 'revisore') {

            $sql = "

                INSERT INTO revisore_esg(

                    id_utente

                )

                VALUES(?)

            ";

            $stmt = $this->pdo->prepare($sql);

            $stmt->execute([$idUtente]);
        }

        if($data['ruolo'] == 'responsabile') {

            $sql = "

                INSERT INTO responsabile_aziendale(

                    id_utente

                )

                VALUES(?)

            ";

            $stmt = $this->pdo->prepare($sql);

            $stmt->execute([$idUtente]);
        }

        return true;
    }
}
?>