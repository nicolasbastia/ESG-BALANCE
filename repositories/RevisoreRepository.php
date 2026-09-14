<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Revisore.php';

class RevisoreRepository {

    private $pdo;

    public function __construct() {

        global $pdo;

        $this->pdo = $pdo;
    }


    public function getByUtente($idUtente) {

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