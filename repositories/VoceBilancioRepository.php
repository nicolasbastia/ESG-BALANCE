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
    | Salva valore (sostituisce insert/update)
    |--------------------------------------------------------------------------
    */

        public function salvaValore(
        $idBilancio,
        $idVoce,
        $valore
    ) {

        $sql = "
            CALL sp_salva_voce_bilancio(?, ?, ?)
        ";

        $stmt = $this->pdo->prepare($sql);

        $result = $stmt->execute([
            $idBilancio,
            $idVoce,
            $valore
        ]);

        $stmt->closeCursor();

        return $result;
    }
}
?>