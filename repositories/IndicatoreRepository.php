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
                ie.id_indicatore,
                ie.nome,
                ie.immagine,
                ie.rilevanza,

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

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $indicatori = [];

        foreach($rows as $row) {

            $indicatori[] = new IndicatoreESG(
                $row['id_indicatore'],
                $row['nome'],
                $row['immagine'],
                $row['rilevanza'],
                $row['categoria'],
                $row['codice_normativa'],
                $row['ambito_sociale'],
                $row['frequenza_rilevazione']
            );
        }

        return $indicatori;
    }

    /*
    |--------------------------------------------------------------------------
    | INSERT
    |--------------------------------------------------------------------------
    */

    public function create(IndicatoreESG $indicatore) {

        $sql = "

            CALL sp_crea_indicatore_esg(
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?
            )

        ";

        $stmt = $this->pdo->prepare($sql);

        $result = $stmt->execute([
            $indicatore->nome,
            $indicatore->immagine,
            $indicatore->rilevanza,
            $indicatore->categoria,
            $indicatore->codice_normativa,
            $indicatore->ambito_sociale,
            $indicatore->frequenza_rilevazione
        ]);

        $stmt->closeCursor();

        return $result;
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function delete($idIndicatore) {

        $sql = "

            CALL sp_elimina_indicatore_esg(?)

        ";

        $stmt = $this->pdo->prepare($sql);

        $result = $stmt->execute([
            $idIndicatore
        ]);

        $stmt->closeCursor();

        return $result;
    }
}

?>