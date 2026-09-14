<?php

require_once __DIR__ . '/../config/db.php';

class AdminUtentiRepository {

    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }


    public function getRevisoriConCompetenze() {

        $sql = "
            SELECT
                u.id_utente,
                u.username,
                r.indice_affidabilita,
                c.id_competenza,
                c.nome AS competenza,
                cr.livello
            FROM utente u
            INNER JOIN revisore_esg r
                ON r.id_utente = u.id_utente
            LEFT JOIN competenza_revisore cr
                ON cr.id_utente = r.id_utente
            LEFT JOIN competenza c
                ON c.id_competenza = cr.id_competenza
            ORDER BY
                u.username ASC,
                c.nome ASC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $revisori = [];

        foreach($rows as $row) {

            $idRevisore = $row['id_utente'];

            if(!isset($revisori[$idRevisore])) {

                $revisori[$idRevisore] = [
                    'id_utente' => $row['id_utente'],
                    'username' => $row['username'],
                    'indice_affidabilita' => $row['indice_affidabilita'],
                    'competenze' => []
                ];
            }

            if($row['id_competenza'] !== null) {

                $revisori[$idRevisore]['competenze'][] = [
                    'id_competenza' => $row['id_competenza'],
                    'nome' => $row['competenza'],
                    'livello' => $row['livello']
                ];
            }
        }

        return array_values($revisori);
    }


    public function getResponsabiliConAziende() {

        $sql = "
            SELECT
                u.id_utente,
                u.username,
                ra.cv_pdf,
                a.id_azienda,
                a.nome AS azienda
            FROM utente u
            INNER JOIN responsabile_aziendale ra
                ON ra.id_utente = u.id_utente
            LEFT JOIN azienda a
                ON a.id_responsabile = ra.id_utente
            ORDER BY
                u.username ASC,
                a.nome ASC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $responsabili = [];

        foreach($rows as $row) {

            $idResponsabile = $row['id_utente'];

            if(!isset($responsabili[$idResponsabile])) {

                $responsabili[$idResponsabile] = [
                    'id_utente' => $row['id_utente'],
                    'username' => $row['username'],
                    'cv_pdf' => $row['cv_pdf'],
                    'aziende' => []
                ];
            }

            if($row['id_azienda'] !== null) {

                $responsabili[$idResponsabile]['aziende'][] = [
                    'id_azienda' => $row['id_azienda'],
                    'nome' => $row['azienda']
                ];
            }
        }

        return array_values($responsabili);
    }
}
?>