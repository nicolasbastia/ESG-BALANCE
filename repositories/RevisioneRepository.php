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
                b.id_bilancio,
                b.id_azienda,
                b.data_creazione,
                b.stato,
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

    /*
    |--------------------------------------------------------------------------
    | CONTROLLO ASSEGNAZIONE
    |--------------------------------------------------------------------------
    */

    public function isAssegnato(
        $idBilancio,
        $idRevisore
    ) {

        $sql = "

            SELECT COUNT(*)

            FROM revisione

            WHERE id_bilancio = ?
            AND id_revisore = ?

        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            $idBilancio,
            $idRevisore
        ]);

        return $stmt->fetchColumn() > 0;
    }

    /*
    |--------------------------------------------------------------------------
    | CONTROLLO VOCE APPARTENENTE AL BILANCIO
    |--------------------------------------------------------------------------
    */

    public function voceAppartieneAlBilancio(
        $idVoceBilancio,
        $idBilancio
    ) {

        $sql = "

            SELECT COUNT(*)

            FROM voce_bilancio

            WHERE id_voce_bilancio = ?
            AND id_bilancio = ?

        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            $idVoceBilancio,
            $idBilancio
        ]);

        return $stmt->fetchColumn() > 0;
    }

    /*
    |--------------------------------------------------------------------------
    | ASSEGNA REVISORE
    |--------------------------------------------------------------------------
    */

    public function assegna(
        $idBilancio,
        $idRevisore
    ) {

        /*
        |--------------------------------------------------------------------------
        | CONTROLLO ASSEGNAZIONE GIA ESISTENTE
        |--------------------------------------------------------------------------
        */

        if(
            $this->isAssegnato(
                $idBilancio,
                $idRevisore
            )
        ) {

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

        $result = $stmt->execute([
            $idBilancio,
            $idRevisore
        ]);

        $stmt->closeCursor();

        if(!$result) {

            return false;
        }

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
                r.id_revisione,
                r.id_bilancio,
                r.id_revisore,
                r.data_assegnazione,

                CASE
                    WHEN g.id_giudizio IS NOT NULL
                        THEN 'conclusa'
                    ELSE r.stato
                END AS stato,

                u.username,
                a.nome AS azienda

            FROM revisione r

            JOIN utente u
                ON r.id_revisore = u.id_utente

            JOIN bilancio b
                ON r.id_bilancio = b.id_bilancio

            JOIN azienda a
                ON b.id_azienda = a.id_azienda

            LEFT JOIN giudizio_revisore g
                ON g.id_bilancio = r.id_bilancio
                AND g.id_revisore = r.id_revisore

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
                $row['stato'],
                $row['username'],
                $row['azienda']
            );
        }

        return $revisioni;
    }

    /*
    |--------------------------------------------------------------------------
    | REVISIONI DEL REVISORE
    |--------------------------------------------------------------------------
    */

    public function getRevisioniRevisore($idRevisore) {

        $sql = "

            SELECT
                r.id_revisione,
                r.id_bilancio,
                r.id_revisore,
                r.data_assegnazione,

                CASE
                    WHEN g.id_giudizio IS NOT NULL
                        THEN 'conclusa'
                    ELSE r.stato
                END AS stato,

                a.nome AS azienda

            FROM revisione r

            JOIN bilancio b
                ON r.id_bilancio = b.id_bilancio

            JOIN azienda a
                ON b.id_azienda = a.id_azienda

            LEFT JOIN giudizio_revisore g
                ON g.id_bilancio = r.id_bilancio
                AND g.id_revisore = r.id_revisore

            WHERE r.id_revisore = ?

            ORDER BY r.data_assegnazione DESC

        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            $idRevisore
        ]);

        $revisioni = [];

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $revisioni[] = new Revisione(
                $row['id_revisione'],
                $row['id_bilancio'],
                $row['id_revisore'],
                $row['data_assegnazione'],
                $row['stato'],
                null,
                $row['azienda']
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

        $stmt->execute([
            $idBilancio
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>