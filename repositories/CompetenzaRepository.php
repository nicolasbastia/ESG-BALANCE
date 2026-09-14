<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Competenza.php';

class CompetenzaRepository {

    private $pdo;

    public function __construct() {

        global $pdo;

        $this->pdo = $pdo;
    }

    /* LISTA COMPETENZE DISPONIBILI */

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

    /* AGGIUNGI COMPETENZA */

    public function create(Competenza $competenza) {


        if(!$competenza->isValid()) {

            return false;
        }


        if($this->exists(
            $competenza->id_utente,
            $competenza->id_competenza
        )) {

            return false;
        }


        $sql = "

            CALL sp_aggiungi_competenza_revisore(
                ?,
                ?,
                ?
            )

        ";

        $stmt = $this->pdo->prepare($sql);

        $result = $stmt->execute([
            $competenza->id_utente,
            $competenza->id_competenza,
            $competenza->livello
        ]);

        $stmt->closeCursor();

        return $result;
    }

    /* MODIFICA LIVELLO */

    public function update(Competenza $competenza) {

        if(!$competenza->isValid()) {

            return false;
        }

        $sql = "

            CALL sp_modifica_competenza_revisore(
                ?,
                ?,
                ?
            )

        ";

        $stmt = $this->pdo->prepare($sql);

        $result = $stmt->execute([
            $competenza->id_utente,
            $competenza->id_competenza,
            $competenza->livello
        ]);

        $stmt->closeCursor();

        return $result;
    }

    /* ELIMINA COMPETENZA */

    public function delete(
        $idUtente,
        $idCompetenza
    ) {

        $sql = "

            CALL sp_elimina_competenza_revisore(
                ?,
                ?
            )

        ";

        $stmt = $this->pdo->prepare($sql);

        $result = $stmt->execute([
            $idUtente,
            $idCompetenza
        ]);

        $stmt->closeCursor();

        return $result;
    }

    /* COMPETENZE DEL REVISORE */

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

        $competenze = [];

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $competenze[] = new Competenza(
                $row['id_utente'],
                $row['id_competenza'],
                $row['livello'],
                $row['nome']
            );
        }

        return $competenze;
    }

    /* CONTROLLO COMPETENZA GIA PRESENTE */

    public function exists(
        $idUtente,
        $idCompetenza
    ) {

        $sql = "

            SELECT COUNT(*)

            FROM competenza_revisore

            WHERE id_utente = ?
            AND id_competenza = ?

        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            $idUtente,
            $idCompetenza
        ]);

        return $stmt->fetchColumn() > 0;
    }


}

?>