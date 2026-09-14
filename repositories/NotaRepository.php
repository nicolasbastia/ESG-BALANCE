<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/NotaRevisione.php';

class NotaRepository {

    private $pdo;

    public function __construct() {

        global $pdo;

        $this->pdo = $pdo;
    }


    public function create(NotaRevisione $nota) {

        $sql = "

            CALL sp_inserisci_nota(
                ?,
                ?,
                ?,
                ?
            )

        ";

        $stmt = $this->pdo->prepare($sql);

        $result = $stmt->execute([
            $nota->id_revisore,
            $nota->id_voce_bilancio,
            $nota->data_nota,
            $nota->testo
        ]);

        $stmt->closeCursor();

        return $result;
    }

    /* LISTA NOTE DEL REVISORE */

    public function getByRevisore($idRevisore) {

        $sql = "

            SELECT
                n.id_nota,
                n.id_revisore,
                n.id_voce_bilancio,
                n.data_nota,
                n.testo,
                vt.nome AS voce

            FROM nota_revisore n

            JOIN voce_bilancio vb
                ON n.id_voce_bilancio = vb.id_voce_bilancio

            JOIN voce_template vt
                ON vb.id_voce = vt.id_voce

            WHERE n.id_revisore = ?

            ORDER BY n.data_nota DESC

        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            $idRevisore
        ]);

        $note = [];

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $note[] = new NotaRevisione(
                $row['id_nota'],
                $row['id_revisore'],
                $row['id_voce_bilancio'],
                $row['data_nota'],
                $row['testo'],
                $row['voce']
            );
        }

        return $note;
    }

    /* NOTE DEL BILANCIO */

    public function getByBilancio($idBilancio) {

        $sql = "

            SELECT
                n.id_nota,
                n.id_revisore,
                n.id_voce_bilancio,
                n.data_nota,
                n.testo,
                vt.nome AS voce,
                u.username AS revisore

            FROM nota_revisore n

            JOIN voce_bilancio vb
                ON n.id_voce_bilancio = vb.id_voce_bilancio

            JOIN voce_template vt
                ON vb.id_voce = vt.id_voce

            JOIN revisore_esg r
                ON n.id_revisore = r.id_utente

            JOIN utente u
                ON r.id_utente = u.id_utente

            WHERE vb.id_bilancio = ?

            ORDER BY n.data_nota DESC

        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            $idBilancio
        ]);

        $note = [];

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $note[] = new NotaRevisione(
                $row['id_nota'],
                $row['id_revisore'],
                $row['id_voce_bilancio'],
                $row['data_nota'],
                $row['testo'],
                $row['voce'],
                $row['revisore']
            );
        }

        return $note;
    }

    /* NOTE DEL REVISORE PER UNO SPECIFICO BILANCIO */

    public function getByRevisoreEBilancio(
        $idRevisore,
        $idBilancio
    ) {

        $sql = "

            SELECT
                n.id_nota,
                n.id_revisore,
                n.id_voce_bilancio,
                n.data_nota,
                n.testo,
                vt.nome AS voce

            FROM nota_revisore n

            JOIN voce_bilancio vb
                ON n.id_voce_bilancio = vb.id_voce_bilancio

            JOIN voce_template vt
                ON vb.id_voce = vt.id_voce

            WHERE n.id_revisore = ?
            AND vb.id_bilancio = ?

            ORDER BY n.data_nota DESC

        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            $idRevisore,
            $idBilancio
        ]);

        $note = [];

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $note[] = new NotaRevisione(
                $row['id_nota'],
                $row['id_revisore'],
                $row['id_voce_bilancio'],
                $row['data_nota'],
                $row['testo'],
                $row['voce']
            );
        }

        return $note;
    }
}

?>