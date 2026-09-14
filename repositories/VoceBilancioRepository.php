<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/VoceBilancio.php';

class VoceBilancioRepository {

    private $pdo;

    public function __construct() {

        global $pdo;

        $this->pdo = $pdo;
    }

    /* LISTA VOCI TEMPLATE */

    public function getTemplate() {

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

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* LISTA VOCI BILANCIO */

    public function getByBilancio($idBilancio) {

        $sql = "

            SELECT
                vb.id_voce_bilancio,
                vb.id_bilancio,
                vb.id_voce,
                vb.valore,
                vt.nome AS nome_voce

            FROM voce_bilancio vb

            JOIN voce_template vt
                ON vb.id_voce = vt.id_voce

            WHERE vb.id_bilancio = ?

            ORDER BY vt.nome

        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            $idBilancio
        ]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $voci = [];

        foreach($rows as $row) {

            $voci[] = new VoceBilancio(
                $row['id_voce_bilancio'],
                $row['id_bilancio'],
                $row['id_voce'],
                $row['valore'],
                $row['nome_voce']
            );
        }

        return $voci;
    }


    public function salvaValore(VoceBilancio $voceBilancio) {

        $sql = "

            CALL sp_salva_voce_bilancio(
                ?,
                ?,
                ?
            )

        ";

        $stmt = $this->pdo->prepare($sql);

        $result = $stmt->execute([
            $voceBilancio->id_bilancio,
            $voceBilancio->id_voce,
            $voceBilancio->valore
        ]);

        $stmt->closeCursor();

        return $result;
    }
}

?>