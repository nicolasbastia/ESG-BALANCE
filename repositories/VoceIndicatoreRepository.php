<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/VoceIndicatore.php';

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

            SELECT
                id_indicatore,
                nome,
                immagine,
                rilevanza

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
                vb.id_voce_bilancio,
                vb.id_bilancio,
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

        $stmt->execute([
            $idBilancio
        ]);

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
                vi.id_voce_bilancio,
                vi.id_indicatore,
                vi.valore_indicatore,
                vi.fonte,
                vi.data_rilevazione,
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

        $stmt->execute([
            $idBilancio
        ]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $collegamenti = [];

        foreach($rows as $row) {

            $collegamenti[] = new VoceIndicatore(
                $row['id_voce_bilancio'],
                $row['id_indicatore'],
                $row['valore_indicatore'],
                $row['fonte'],
                $row['data_rilevazione'],
                $row['nome_voce'],
                $row['nome_indicatore']
            );
        }

        return $collegamenti;
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(VoceIndicatore $voceIndicatore) {

        $sql = "

            CALL sp_collega_indicatore_voce(
                ?,
                ?,
                ?,
                ?,
                ?
            )

        ";

        $stmt = $this->pdo->prepare($sql);

        $result = $stmt->execute([
            $voceIndicatore->id_voce_bilancio,
            $voceIndicatore->id_indicatore,
            $voceIndicatore->valore_indicatore,
            $voceIndicatore->fonte,
            $voceIndicatore->data_rilevazione
        ]);

        $stmt->closeCursor();

        return $result;
    }
}

?>