<?php

require_once __DIR__ . '/../config/db.php';

class CompetenzaRepository {

    private $pdo;

    public function __construct() {

        global $pdo;

        $this->pdo = $pdo;
    }

    /*
    |--------------------------------------------------------------------------
    | LISTA COMPETENZE DISPONIBILI
    |--------------------------------------------------------------------------
    */

    public function getAll() {

        $sql = "

            SELECT

                id_competenza,
                nome

            FROM competenza

            ORDER BY nome

        ";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | COMPETENZE DEL REVISORE
    |--------------------------------------------------------------------------
    */

    public function getByRevisore($idUtente) {

        $sql = "

            SELECT

                cr.id_utente,
                cr.id_competenza,
                cr.livello,
                c.nome

            FROM competenza_revisore cr

            JOIN competenza c
            ON cr.id_competenza = c.id_competenza

            WHERE cr.id_utente = ?

            ORDER BY c.nome

        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            $idUtente
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | AGGIUNGI COMPETENZA
    |--------------------------------------------------------------------------
    */

    public function create(

        $idUtente,
        $idCompetenza,
        $livello

    ) {

        if($livello < 0 || $livello > 5) {

            return false;
        }

        $sql = "

            INSERT INTO competenza_revisore(

                id_utente,
                id_competenza,
                livello

            )

            VALUES(

                ?,
                ?,
                ?

            )

        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([

            $idUtente,
            $idCompetenza,
            $livello

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | MODIFICA LIVELLO
    |--------------------------------------------------------------------------
    */

    public function update(

        $idUtente,
        $idCompetenza,
        $livello

    ) {

        if($livello < 0 || $livello > 5) {

            return false;
        }

        $sql = "

            UPDATE competenza_revisore

            SET livello = ?

            WHERE id_utente = ?
            AND id_competenza = ?

        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([

            $livello,
            $idUtente,
            $idCompetenza

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | ELIMINA COMPETENZA
    |--------------------------------------------------------------------------
    */

    public function delete(

        $idUtente,
        $idCompetenza

    ) {

        $sql = "

            DELETE FROM competenza_revisore

            WHERE id_utente = ?
            AND id_competenza = ?

        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([

            $idUtente,
            $idCompetenza

        ]);
    }
}
?>