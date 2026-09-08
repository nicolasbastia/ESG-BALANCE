<?php

require_once __DIR__ . '/../config/db.php';

class StatisticheRepository {

    private $pdo;

    public function __construct() {

        global $pdo;

        $this->pdo = $pdo;
    }

    public function getNumeroAziende() {

        $sql = "
            SELECT numero_aziende
            FROM vista_numero_aziende
        ";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getNumeroRevisori() {

        $sql = "
            SELECT numero_revisori
            FROM vista_numero_revisori
        ";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAziendaPiuAffidabile() {

        $sql = "
            SELECT
                id_azienda,
                nome,
                bilanci_approvati,
                bilanci_conclusi,
                percentuale_affidabilita
            FROM vista_affidabilita_aziende
            WHERE percentuale_affidabilita IS NOT NULL
            ORDER BY percentuale_affidabilita DESC
            LIMIT 1
        ";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getClassificaBilanci() {

        $sql = "
            SELECT
                id_bilancio,
                azienda,
                totale_indicatori_esg
            FROM vista_classifica_bilanci
            ORDER BY totale_indicatori_esg DESC
        ";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}