<?php

require_once __DIR__ . '/../config/db.php';

class BilancioRepository {

    private $pdo;

    public function __construct() {

        global $pdo;

        $this->pdo = $pdo;
    }

    /*
    |--------------------------------------------------------------------------
    | LISTA BILANCI
    |--------------------------------------------------------------------------
    */

    public function getByResponsabile($idResponsabile) {

        $sql = "

            SELECT

                b.*,
                a.nome AS nome_azienda

            FROM bilancio b

            JOIN azienda a
            ON b.id_azienda = a.id_azienda

            WHERE a.id_responsabile = ?

            ORDER BY b.data_creazione DESC

        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([$idResponsabile]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | LISTA AZIENDE RESPONSABILE
    |--------------------------------------------------------------------------
    */

    public function getAziendeResponsabile($idResponsabile) {

        $sql = "

            SELECT *

            FROM azienda

            WHERE id_responsabile = ?

            ORDER BY nome

        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([$idResponsabile]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | DETTAGLIO BILANCIO
    |--------------------------------------------------------------------------
    */

    public function getDettaglioBilancio($idBilancio) {

        $sql = "

            SELECT

                vb.id_voce_bilancio,
                vt.nome AS voce,
                vb.valore,
                ie.nome AS indicatore,
                vi.valore_indicatore,
                vi.fonte,
                vi.data_rilevazione

            FROM voce_bilancio vb

            JOIN voce_template vt
            ON vb.id_voce = vt.id_voce

            LEFT JOIN voce_indicatore vi
            ON vb.id_voce_bilancio = vi.id_voce_bilancio

            LEFT JOIN indicatore_esg ie
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
    | CREATE BILANCIO
    |--------------------------------------------------------------------------
    */

    public function create(

    $idAzienda,
    $dataCreazione

    ) {

        $sql = "

            CALL sp_crea_bilancio(
                ?,
                ?
            )

        ";

        $stmt = $this->pdo->prepare($sql);

        $result = $stmt->execute([

            $idAzienda,
            $dataCreazione

        ]);

        $stmt->closeCursor();

        return $result;
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

        public function delete($id) {

        $sql = "
            CALL sp_elimina_bilancio(?)
        ";

        $stmt = $this->pdo->prepare($sql);

        $result = $stmt->execute([$id]);

        $stmt->closeCursor();

        return $result;
    }
}
?>