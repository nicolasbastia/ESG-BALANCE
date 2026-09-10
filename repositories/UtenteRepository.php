<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Utente.php';
require_once __DIR__ . '/../models/Revisore.php';

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

            SELECT *

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

    /*
    |--------------------------------------------------------------------------
    | DATI REVISORE
    |--------------------------------------------------------------------------
    */

    public function getDatiRevisore($idUtente) {

        $sql = "

            SELECT

                r.id_utente,
                (
                    SELECT COUNT(DISTINCT id_bilancio)
                    FROM giudizio_revisore
                    WHERE id_revisore = r.id_utente
                ) AS numero_revisioni,

                r.indice_affidabilita

            FROM revisore_esg r

            WHERE r.id_utente = ?

        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            $idUtente
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$row) {
            return null;
        }

        return new Revisore(
            $row['id_utente'],
            $row['numero_revisioni'],
            $row['indice_affidabilita']
        );
    }
}
?>