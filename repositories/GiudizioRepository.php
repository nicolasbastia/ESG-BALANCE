<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/GiudizioRevisione.php';

class GiudizioRepository {

    private $pdo;

    public function __construct() {

        global $pdo;

        $this->pdo = $pdo;
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(GiudizioRevisione $giudizio) {

        $sql = "

            CALL sp_inserisci_giudizio(
                ?,
                ?,
                ?,
                ?,
                ?
            )

        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([

            $giudizio->id_bilancio,
            $giudizio->id_revisore,
            $giudizio->esito,
            $giudizio->data_giudizio,
            $giudizio->rilievi

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

            $giudizio->id_revisore

        ]);

        $stmt->closeCursor();

        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | GET BY BILANCIO E REVISORE
    |--------------------------------------------------------------------------
    */

    public function getByBilancio(

        $idBilancio,
        $idRevisore

    ) {

        $sql = "

            SELECT
                id_giudizio,
                id_bilancio,
                id_revisore,
                esito,
                data_giudizio,
                rilievi

            FROM giudizio_revisore

            WHERE id_bilancio = ?

            AND id_revisore = ?

            LIMIT 1

        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([

            $idBilancio,
            $idRevisore

        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$row) {

            return null;
        }

        return new GiudizioRevisione(

            $row['id_giudizio'],
            $row['id_bilancio'],
            $row['id_revisore'],
            $row['esito'],
            $row['data_giudizio'],
            $row['rilievi']

        );
    }

    /*
    |--------------------------------------------------------------------------
    | CONTROLLO ESISTENZA GIUDIZIO
    |--------------------------------------------------------------------------
    */

    public function esisteGiudizio(

        $idBilancio,
        $idRevisore

    ) {

        $sql = "

            SELECT COUNT(*)

            FROM giudizio_revisore

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
    | TUTTI I GIUDIZI DEL BILANCIO
    |--------------------------------------------------------------------------
    */

    public function getTuttiByBilancio($idBilancio) {

        $sql = "

            SELECT

                g.*,
                u.username AS revisore

            FROM giudizio_revisore g

            JOIN revisore_esg r
                ON g.id_revisore = r.id_utente

            JOIN utente u
                ON r.id_utente = u.id_utente

            WHERE g.id_bilancio = ?

            ORDER BY g.data_giudizio DESC

        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            $idBilancio
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>