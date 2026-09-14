<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/VoceTemplate.php';

class VoceTemplateRepository {

    private $pdo;

    public function __construct() {

        global $pdo;

        $this->pdo = $pdo;
    }


    public function create(VoceTemplate $voce) {

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
            $voce->id_amministratore

        ]);

        $stmt->closeCursor();

        return $result;
    }


    public function delete($idVoce) {


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


    /* LISTA */

    public function getAll() {

        $sql = "

            SELECT
                id_voce,
                nome,
                descrizione,
                id_amministratore

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
                $row['descrizione'],
                $row['id_amministratore']

            );
        }

        return $voci;
    }
}

?>