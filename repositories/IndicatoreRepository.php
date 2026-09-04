<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/IndicatoreESG.php';

class IndicatoreRepository {

    private $pdo;

    public function __construct() {

        global $pdo;

        $this->pdo = $pdo;
    }

    /*
    |--------------------------------------------------------------------------
    | LISTA
    |--------------------------------------------------------------------------
    */

    public function getAll() {

        $sql = "

            SELECT

                ie.*,

                ia.codice_normativa,

                iso.ambito_sociale,
                iso.frequenza_rilevazione,

                CASE

                    WHEN ia.id_indicatore IS NOT NULL
                    THEN 'ambientale'

                    WHEN iso.id_indicatore IS NOT NULL
                    THEN 'sociale'

                    ELSE 'nessuna'

                END AS categoria

            FROM indicatore_esg ie

            LEFT JOIN indicatore_ambientale ia
            ON ie.id_indicatore = ia.id_indicatore

            LEFT JOIN indicatore_sociale iso
            ON ie.id_indicatore = iso.id_indicatore

            ORDER BY ie.nome

        ";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | INSERT
    |--------------------------------------------------------------------------
    */

    public function create(

        $nome,
        $immagine,
        $rilevanza,
        $categoria,
        $codiceNormativa = null,
        $ambitoSociale = null,
        $frequenzaRilevazione = null

    ) {

        try {

            $this->pdo->beginTransaction();

            /*
            |--------------------------------------------------------------------------
            | INSERT INDICATORE BASE
            |--------------------------------------------------------------------------
            */

            $sql = "

                INSERT INTO indicatore_esg(

                    nome,
                    immagine,
                    rilevanza

                )

                VALUES(

                    ?,
                    ?,
                    ?

                )

            ";

            $stmt = $this->pdo->prepare($sql);

            $stmt->execute([

                $nome,
                $immagine,
                $rilevanza

            ]);

            $idIndicatore = $this->pdo->lastInsertId();

            /*
            |--------------------------------------------------------------------------
            | INDICATORE AMBIENTALE
            |--------------------------------------------------------------------------
            */

            if($categoria === 'ambientale') {

                $sql = "

                    INSERT INTO indicatore_ambientale(

                        id_indicatore,
                        codice_normativa

                    )

                    VALUES(

                        ?,
                        ?

                    )

                ";

                $stmt = $this->pdo->prepare($sql);

                $stmt->execute([

                    $idIndicatore,
                    $codiceNormativa

                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | INDICATORE SOCIALE
            |--------------------------------------------------------------------------
            */

            if($categoria === 'sociale') {

                $sql = "

                    INSERT INTO indicatore_sociale(

                        id_indicatore,
                        ambito_sociale,
                        frequenza_rilevazione

                    )

                    VALUES(

                        ?,
                        ?,
                        ?

                    )

                ";

                $stmt = $this->pdo->prepare($sql);

                $stmt->execute([

                    $idIndicatore,
                    $ambitoSociale,
                    $frequenzaRilevazione

                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | NESSUNA CATEGORIA
            |--------------------------------------------------------------------------
            |
            | Non facciamo nessun altro INSERT.
            |--------------------------------------------------------------------------
            */

            $this->pdo->commit();

            return true;

        } catch(PDOException $e) {

            if($this->pdo->inTransaction()) {

                $this->pdo->rollBack();
            }

            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function delete($id) {

        /*
        |--------------------------------------------------------------------------
        | Se hai ON DELETE CASCADE nelle due tabelle specializzate,
        | basta eliminare dalla tabella base.
        |--------------------------------------------------------------------------
        */

        $sql = "

            DELETE FROM indicatore_esg

            WHERE id_indicatore = ?

        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([$id]);
    }
}
?>