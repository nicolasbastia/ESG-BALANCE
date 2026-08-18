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

            INSERT INTO bilancio(

                id_azienda,
                data_creazione

            )

            VALUES(

                ?,
                ?

            )

        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([

            $idAzienda,
            $dataCreazione

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function delete($id) {

        /*
        |--------------------------------------------------------------------------
        | ELIMINA GIUDIZI
        |--------------------------------------------------------------------------
        */

        $sql = "

            DELETE FROM giudizio_revisore

            WHERE id_bilancio = ?

        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([$id]);

        /*
        |--------------------------------------------------------------------------
        | ELIMINA NOTE REVISIONE
        |--------------------------------------------------------------------------
        */

        $sql = "

            DELETE nr

            FROM nota_revisore nr

            JOIN voce_bilancio vb
            ON nr.id_voce_bilancio = vb.id_voce_bilancio

            WHERE vb.id_bilancio = ?

        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([$id]);

        /*
        |--------------------------------------------------------------------------
        | ELIMINA REVISIONI
        |--------------------------------------------------------------------------
        */

        $sql = "

            DELETE FROM revisione

            WHERE id_bilancio = ?

        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([$id]);

        /*
        |--------------------------------------------------------------------------
        | ELIMINA COLLEGAMENTI ESG
        |--------------------------------------------------------------------------
        */

        $sql = "

            DELETE vi

            FROM voce_indicatore vi

            JOIN voce_bilancio vb
            ON vi.id_voce_bilancio = vb.id_voce_bilancio

            WHERE vb.id_bilancio = ?

        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([$id]);

        /*
        |--------------------------------------------------------------------------
        | ELIMINA VOCI BILANCIO
        |--------------------------------------------------------------------------
        */

        $sql = "

            DELETE FROM voce_bilancio

            WHERE id_bilancio = ?

        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([$id]);

        /*
        |--------------------------------------------------------------------------
        | ELIMINA BILANCIO
        |--------------------------------------------------------------------------
        */

        $sql = "

            DELETE FROM bilancio

            WHERE id_bilancio = ?

        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([$id]);
    }
}
?>