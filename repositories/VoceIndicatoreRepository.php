<?php

require_once __DIR__ . '/../config/db.php';

class VoceIndicatoreRepository {

    private $pdo;

    public function __construct() {

        global $pdo;

        $this->pdo = $pdo;
    }

    /*
    |--------------------------------------------------------------------------
    | LISTA INDICATORI
    |--------------------------------------------------------------------------
    */

    public function getIndicatori() {

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
    | LISTA VOCI BILANCIO
    |--------------------------------------------------------------------------
    */

    public function getVociBilancio($idBilancio) {

        $sql = "

            SELECT

                vb.*,
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
    | LISTA COLLEGAMENTI ESG
    |--------------------------------------------------------------------------
    */

    public function getCollegamenti($idBilancio) {

        $sql = "

            SELECT

                vi.*,
                vt.nome AS nome_voce,
                ie.nome AS nome_indicatore

            FROM voce_indicatore vi

            JOIN voce_bilancio vb
            ON vi.id_voce_bilancio = vb.id_voce_bilancio

            JOIN voce_template vt
            ON vb.id_voce = vt.id_voce

            JOIN indicatore_esg ie
            ON vi.id_indicatore = ie.id_indicatore

            WHERE vb.id_bilancio = ?

            ORDER BY vt.nome

        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([$idBilancio]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

   public function create(

        $idVoceBilancio,
        $idIndicatore,
        $valore,
        $fonte,
        $data

    ) {

        $sql = "

            INSERT INTO voce_indicatore(

                id_voce_bilancio,
                id_indicatore,
                valore_indicatore,
                fonte,
                data_rilevazione

            )

            VALUES(

                ?,
                ?,
                ?,
                ?,
                ?

            )

        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([

            $idVoceBilancio,
            $idIndicatore,
            $valore,
            $fonte,
            $data

        ]);
    }
}
?>