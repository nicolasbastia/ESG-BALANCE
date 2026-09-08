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

    $sql = "
        CALL sp_crea_indicatore_esg(?, ?, ?, ?, ?, ?, ?)
    ";

    $stmt = $this->pdo->prepare($sql);

    $result = $stmt->execute([
        $nome,
        $immagine,
        $rilevanza,
        $categoria,
        $codiceNormativa,
        $ambitoSociale,
        $frequenzaRilevazione
    ]);

    $stmt->closeCursor();

    return $result;
}
    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function delete($id) {

    $sql = "
        CALL sp_elimina_indicatore_esg(?)
    ";

    $stmt = $this->pdo->prepare($sql);

    $result = $stmt->execute([$id]);

    $stmt->closeCursor();

    return $result;
    }
}
?>