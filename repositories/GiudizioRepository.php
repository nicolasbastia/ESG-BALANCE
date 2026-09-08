<?php

require_once __DIR__ . '/../config/db.php';

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

    public function create(

        $idBilancio,
        $idRevisore,
        $esito,
        $rilievi

    ) {

        $sql = "

            CALL sp_inserisci_giudizio(
                ?,
                ?,
                ?,
                CURDATE(),
                ?
            )

        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([

            $idBilancio,
            $idRevisore,
            $esito,
            $rilievi

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
    | GET BY BILANCIO
    |--------------------------------------------------------------------------
    */

    public function getByBilancio(

    $idBilancio,
    $idRevisore

) {

    $sql = "

        SELECT *

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

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

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