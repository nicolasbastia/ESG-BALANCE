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
            CALL sp_aggiungi_competenza_revisore(?, ?, ?)
        ";

        $stmt = $this->pdo->prepare($sql);

        $result = $stmt->execute([
            $idUtente,
            $idCompetenza,
            $livello
        ]);

        $stmt->closeCursor();

        return $result;
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
            CALL sp_modifica_competenza_revisore(?, ?, ?)
        ";

        $stmt = $this->pdo->prepare($sql);

        $result = $stmt->execute([
            $idUtente,
            $idCompetenza,
            $livello
        ]);

        $stmt->closeCursor();

        return $result;
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
            CALL sp_elimina_competenza_revisore(?, ?)
        ";

        $stmt = $this->pdo->prepare($sql);

        $result = $stmt->execute([
            $idUtente,
            $idCompetenza
        ]);

        $stmt->closeCursor();

        return $result;
    }
}
?>