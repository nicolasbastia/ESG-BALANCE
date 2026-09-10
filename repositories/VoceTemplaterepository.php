<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/VoceTemplate.php';

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

            SELECT
                id_voce,
                nome,
                descrizione

            FROM voce_template

            ORDER BY nome

        ";

        $stmt = $this->pdo->query($sql);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $voci = [];

        foreach($rows as $row) {

            $voci[] = new VoceTemplate(

                $row['id_voce'],
                $row['nome'],
                $row['descrizione']

            );
        }

        return $voci;
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(

        VoceTemplate $voce,
        $idAmministratore

    ) {

        $sql = "

            CALL sp_crea_voce_template(
                ?,
                ?,
                ?
            )

        ";

        $stmt = $this->pdo->prepare($sql);

        $result = $stmt->execute([

            $voce->nome,
            $voce->descrizione,
            $idAmministratore

        ]);

        $stmt->closeCursor();

        return $result;
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function delete($idVoce) {

        /*
        |--------------------------------------------------------------------------
        | CONTROLLO SE LA VOCE È GIÀ UTILIZZATA
        |--------------------------------------------------------------------------
        */

        $sql = "

            SELECT COUNT(*)

            FROM voce_bilancio

            WHERE id_voce = ?

        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            $idVoce
        ]);

        $utilizzata = $stmt->fetchColumn();

        if($utilizzata > 0) {

            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | ELIMINAZIONE
        |--------------------------------------------------------------------------
        */

        $sql = "

            CALL sp_elimina_voce_template(?)

        ";

        $stmt = $this->pdo->prepare($sql);

        $result = $stmt->execute([
            $idVoce
        ]);

        $stmt->closeCursor();

        return $result;
    }
}
?>