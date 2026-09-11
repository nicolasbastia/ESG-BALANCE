<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Azienda.php';

class AziendaRepository {

    private $pdo;

    public function __construct() {

        global $pdo;

        $this->pdo = $pdo;
    }

    /*
    |--------------------------------------------------------------------------
    | LISTA AZIENDE DEL RESPONSABILE
    |--------------------------------------------------------------------------
    */

    public function getByResponsabile($idResponsabile) {

        $sql = "

            SELECT
                id_azienda,
                nome,
                ragione_sociale,
                partita_iva,
                settore,
                numero_dipendenti,
                logo,
                nr_bilanci,
                id_responsabile

            FROM azienda

            WHERE id_responsabile = ?

            ORDER BY nome

        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            $idResponsabile
        ]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $aziende = [];

        foreach($rows as $row) {

            $aziende[] = new Azienda(

                $row['id_azienda'],
                $row['nome'],
                $row['ragione_sociale'],
                $row['partita_iva'],
                $row['settore'],
                $row['numero_dipendenti'],
                $row['logo'],
                $row['nr_bilanci'],
                $row['id_responsabile']

            );
        }

        return $aziende;
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

        $stmt->execute([
            $idResponsabile
        ]);

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

    public function create(Azienda $azienda) {

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

            $azienda->nome,
            $azienda->ragione_sociale,
            $azienda->partita_iva,
            $azienda->settore,
            $azienda->numero_dipendenti,
            $azienda->logo,
            $azienda->id_responsabile

        ]);

        $stmt->closeCursor();

        return $result;
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function delete($idAzienda) {

        $sql = "

            CALL sp_elimina_azienda(?)

        ";

        $stmt = $this->pdo->prepare($sql);

        $result = $stmt->execute([
            $idAzienda
        ]);

        $stmt->closeCursor();

        return $result;
    }
}

?>