<?php

require_once __DIR__ . '/../config/db.php';

class VoceBilancioRepository {

    private $pdo;

    public function __construct() {

        global $pdo;

        $this->pdo = $pdo;
    }

    /*
    |--------------------------------------------------------------------------
    | LISTA VOCI TEMPLATE
    |--------------------------------------------------------------------------
    */

    public function getTemplate() {

        $sql = "

            SELECT *

            FROM voce_template

            ORDER BY nome

        ";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | LISTA VOCI BILANCIO
    |--------------------------------------------------------------------------
    */

        public function getByBilancio($idBilancio) {

            $sql = "

                SELECT

                    vb.id_voce,
                    vb.valore,
                    vt.nome

                FROM voce_bilancio vb

                JOIN voce_template vt
                ON vb.id_voce = vt.id_voce

                WHERE vb.id_bilancio = ?

                ORDER BY vt.nome

            ";

            $stmt = $this->pdo->prepare($sql);

            $stmt->execute([$idBilancio]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

    /*
    |--------------------------------------------------------------------------
    | INSERT/UPDATE
    |--------------------------------------------------------------------------
    */

    public function salvaValore(

        $idBilancio,
        $idVoce,
        $valore

    ) {

        /*
        |--------------------------------------------------------------------------
        | CONTROLLO ESISTENZA
        |--------------------------------------------------------------------------
        */

        $check = "

            SELECT *

            FROM voce_bilancio

            WHERE id_bilancio = ?
            AND id_voce = ?

        ";

        $stmt = $this->pdo->prepare($check);

        $stmt->execute([

            $idBilancio,
            $idVoce

        ]);

        $esiste = $stmt->fetch();

        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        */

        if($esiste) {

            $sql = "

                UPDATE voce_bilancio

                SET valore = ?

                WHERE id_bilancio = ?
                AND id_voce = ?

            ";

            $stmt = $this->pdo->prepare($sql);

            return $stmt->execute([

                $valore,
                $idBilancio,
                $idVoce

            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | INSERT
        |--------------------------------------------------------------------------
        */

        $sql = "

            INSERT INTO voce_bilancio(

                id_bilancio,
                id_voce,
                valore

            )

            VALUES(

                ?,
                ?,
                ?

            )

        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([

            $idBilancio,
            $idVoce,
            $valore

        ]);
    }
}
?>