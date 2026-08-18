<?php

require_once __DIR__ . '/../config/db.php';

class GiudizioRepository {

    private $pdo;

    public function __construct() {

        global $pdo;

        $this->pdo = $pdo;
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(

        $idBilancio,
        $idRevisore,
        $esito,
        $rilievi

    ) {

        $sql = "

            INSERT INTO giudizio_revisore(

                id_bilancio,
                id_revisore,
                esito,
                rilievi,
                data_giudizio

            )

            VALUES(

                ?,
                ?,
                ?,
                ?,
                NOW()

            )

        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([

            $idBilancio,
            $idRevisore,
            $esito,
            $rilievi

        ]);

        /*
        |--------------------------------------------------------------------------
        | UPDATE STATO BILANCIO
        |--------------------------------------------------------------------------
        */

        $update = "

            UPDATE bilancio

            SET stato = ?

            WHERE id_bilancio = ?

        ";

        $stmt = $this->pdo->prepare($update);

        return $stmt->execute([

            $esito,
            $idBilancio

        ]);
    }
    
    /*
    |--------------------------------------------------------------------------
    | GET BY BILANCIO
    |--------------------------------------------------------------------------
    */

    public function getByBilancio($idBilancio) {

        $sql = "

            SELECT *

            FROM giudizio_revisore

            WHERE id_bilancio = ?

            LIMIT 1

        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([$idBilancio]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>