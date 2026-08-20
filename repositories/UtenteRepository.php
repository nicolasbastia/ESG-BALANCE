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
                $row['ruolo']

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

            (
                SELECT COUNT(*)
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

    return $stmt->fetch(PDO::FETCH_ASSOC);
}
}
?>