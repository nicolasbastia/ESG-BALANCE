<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/IndicatoreESG.php';

class IndicatoreRepository {

    private $pdo;

    public function __construct() {

        global $pdo;

        $this->pdo = $pdo;
    }

    /*
    |--------------------------------------------------------------------------
    | LISTA
    |--------------------------------------------------------------------------
    */

    public function getAll() {

        $sql = "

            SELECT *

            FROM indicatore_esg

            ORDER BY nome

        ";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | INSERT
    |--------------------------------------------------------------------------
    */

    public function create(
        $nome,
        $immagine,
        $rilevanza
    ) {

        $sql = "

            INSERT INTO indicatore_esg(

                nome,
                immagine,
                rilevanza

            )

            VALUES(

                ?,
                ?,
                ?

            )

        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([

            $nome,
            $immagine,
            $rilevanza

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function delete($id) {

        $sql = "

            DELETE FROM indicatore_esg

            WHERE id_indicatore = ?

        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([$id]);
    }
}
?>