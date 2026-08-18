<?php

require_once __DIR__ . '/../config/db.php';

class VoceTemplateRepository {

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

            FROM voce_template

            ORDER BY nome

        ";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(

        $nome,
        $descrizione,
        $idAmministratore

    ) {

        $sql = "

            INSERT INTO voce_template(

                nome,
                descrizione,
                id_amministratore

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
            $descrizione,
            $idAmministratore

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function delete($id) {

        $sql = "

            DELETE FROM voce_template

            WHERE id_voce = ?

        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([$id]);
    }
}
?>