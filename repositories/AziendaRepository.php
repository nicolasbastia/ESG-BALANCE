<?php

require_once __DIR__ . '/../config/db.php';

class AziendaRepository {

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

    public function getByResponsabile($idResponsabile) {

        $sql = "

            SELECT *

            FROM azienda

            WHERE id_responsabile = ?

            ORDER BY nome

        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([$idResponsabile]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


        /*
    |--------------------------------------------------------------------------
    | AFFIDABILITA AZIENDA DEL RESPONSABILE
    |--------------------------------------------------------------------------
    */

    public function getAffidabilitaByResponsabile($idResponsabile) {

        $sql = "

            SELECT
                v.id_azienda,
                v.nome,
                v.bilanci_approvati,
                v.bilanci_conclusi,
                v.percentuale_affidabilita

            FROM vista_affidabilita_aziende v

            JOIN azienda a
            ON v.id_azienda = a.id_azienda

            WHERE a.id_responsabile = ?

            ORDER BY v.nome

        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([$idResponsabile]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /*
    |--------------------------------------------------------------------------
    | AFFIDABILITA TUTTE LE AZIENDE
    |--------------------------------------------------------------------------
    */

    public function getTutteAffidabilita() {

        $sql = "

            SELECT
                id_azienda,
                nome,
                bilanci_approvati,
                bilanci_conclusi,
                percentuale_affidabilita

            FROM vista_affidabilita_aziende

            ORDER BY nome

        ";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(

        $nome,
        $ragioneSociale,
        $partitaIva,
        $settore,
        $numeroDipendenti,
        $logo,
        $idResponsabile

    ) {

        $sql = "

            CALL sp_registra_azienda(
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

            $nome,
            $ragioneSociale,
            $partitaIva,
            $settore,
            $numeroDipendenti,
            $logo,
            $idResponsabile

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
        CALL sp_elimina_azienda(?)
    ";

    $stmt = $this->pdo->prepare($sql);

    $result = $stmt->execute([$id]);

    $stmt->closeCursor();

    return $result;
    }
}
?>