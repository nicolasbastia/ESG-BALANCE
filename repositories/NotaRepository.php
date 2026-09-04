<?php

require_once __DIR__ . '/../config/db.php';

class NotaRepository {

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

        $idRevisore,
        $idVoceBilancio,
        $testo

    ) {

        $sql = "

            INSERT INTO nota_revisore(

                id_revisore,
                id_voce_bilancio,
                testo,
                data_nota

            )

            VALUES(

                ?,
                ?,
                ?,
                NOW()

            )

        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([

            $idRevisore,
            $idVoceBilancio,
            $testo

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | LISTA NOTE
    |--------------------------------------------------------------------------
    */

    public function getByRevisore($idRevisore) {

        $sql = "

            SELECT

                n.*,
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

        $stmt->execute([$idRevisore]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | NOTE BY BILANCIO
    |--------------------------------------------------------------------------
    */

    public function getByBilancio($idBilancio) {

        $sql = "

            SELECT

                n.*,
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

        $stmt->execute([$idBilancio]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getByRevisoreEBilancio(

    $idRevisore,
    $idBilancio

) {

    $sql = "

        SELECT

            n.*,
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

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}
?>