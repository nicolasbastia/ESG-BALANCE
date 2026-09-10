<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Revisione.php';

class RevisioneRepository {

    private $pdo;

    public function __construct() {

        global $pdo;

        $this->pdo = $pdo;
    }

    /*
    |--------------------------------------------------------------------------
    | BILANCI
    |--------------------------------------------------------------------------
    */

    public function getBilanci() {

        $sql = "

            SELECT

                b.*,
                a.nome AS azienda

            FROM bilancio b

            JOIN azienda a
            ON b.id_azienda = a.id_azienda

            ORDER BY b.data_creazione DESC

        ";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | REVISORI
    |--------------------------------------------------------------------------
    */

    public function getRevisori() {

        $sql = "

            SELECT

                u.id_utente,
                u.username

            FROM utente u

            JOIN revisore_esg r
            ON u.id_utente = r.id_utente

            ORDER BY u.username

        ";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function assegna(

    $idBilancio,
    $idRevisore

) {

    /*
    |--------------------------------------------------------------------------
    | CONTROLLO ASSEGNAZIONE GIA ESISTENTE
    |--------------------------------------------------------------------------
    */

    $check = "

        SELECT COUNT(*)

        FROM revisione

        WHERE id_bilancio = ?
        AND id_revisore = ?

    ";

    $stmt = $this->pdo->prepare($check);

    $stmt->execute([

        $idBilancio,
        $idRevisore

    ]);

    if($stmt->fetchColumn() > 0) {

        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | CREA ASSEGNAZIONE
    |--------------------------------------------------------------------------
    */

    $sql = "

        CALL sp_assegna_revisore(
            ?,
            ?,
            CURDATE()
        )

    ";

    $stmt = $this->pdo->prepare($sql);

    $stmt->execute([

        $idBilancio,
        $idRevisore

    ]);

    $stmt->closeCursor();

    /*
    |--------------------------------------------------------------------------
    | AGGIORNA AFFIDABILITA REVISORE
    |--------------------------------------------------------------------------
    */

    $sql = "

        CALL sp_aggiorna_affidabilita_revisore(?)

    ";

    $stmt = $this->pdo->prepare($sql);

    $stmt->execute([

        $idRevisore

    ]);

    $stmt->closeCursor();

    return true;
}

    /*
    |--------------------------------------------------------------------------
    | LISTA REVISIONI
    |--------------------------------------------------------------------------
    */

    public function getRevisioni() {

        $sql = "

            SELECT

                r.*,
                u.username,
                a.nome AS azienda

            FROM revisione r

            JOIN utente u
            ON r.id_revisore = u.id_utente

            JOIN bilancio b
            ON r.id_bilancio = b.id_bilancio

            JOIN azienda a
            ON b.id_azienda = a.id_azienda

            ORDER BY r.id_revisione DESC

        ";

        $stmt = $this->pdo->query($sql);

        $revisioni = [];

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $revisioni[] = new Revisione(
                $row['id_revisione'],
                $row['id_bilancio'],
                $row['id_revisore'],
                $row['data_assegnazione'],
                $row['username'],
                $row['azienda'],
                isset($row['stato']) ? $row['stato'] : null
            );
        }

        return $revisioni;
    }

    /*
    |--------------------------------------------------------------------------
    | REVISIONI REVISORE
    |--------------------------------------------------------------------------
    */

    public function getRevisioniRevisore($idRevisore) {

        $sql = "

            SELECT

                r.*,
                a.nome AS azienda,
                b.id_bilancio,
                b.stato

            FROM revisione r

            JOIN bilancio b
            ON r.id_bilancio = b.id_bilancio

            JOIN azienda a
            ON b.id_azienda = a.id_azienda

            WHERE r.id_revisore = ?

            ORDER BY r.data_assegnazione DESC

        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([$idRevisore]);

        $revisioni = [];

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $revisioni[] = new Revisione(
                $row['id_revisione'],
                $row['id_bilancio'],
                $row['id_revisore'],
                $row['data_assegnazione'],
                null,
                $row['azienda'],
                $row['stato']
            );
        }

        return $revisioni;
    }

    /*
    |--------------------------------------------------------------------------
    | DETTAGLIO BILANCIO REVISIONE
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
}
?>