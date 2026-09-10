<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/ProfiloResponsabile.php';

class ProfiloResponsabileRepository {

    private $pdo;

    public function __construct() {

        global $pdo;

        $this->pdo = $pdo;
    }

    /*
    |--------------------------------------------------------------------------
    | RECUPERA PROFILO
    |--------------------------------------------------------------------------
    */

    public function getByUtente($idUtente) {

        $sql = "

            SELECT
                ra.id_utente,
                ra.cv_pdf,
                u.username

            FROM responsabile_aziendale ra

            JOIN utente u
            ON ra.id_utente = u.id_utente

            WHERE ra.id_utente = ?

        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([$idUtente]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$row) {
            return null;
        }

        return new ProfiloResponsabile(
            $row['id_utente'],
            $row['cv_pdf'],
            $row['username']
        );
    }

    /*
    |--------------------------------------------------------------------------
    | AGGIORNA CV
    |--------------------------------------------------------------------------
    */

    public function updateCv($idUtente, $cvPdf) {

            $sql = "
                CALL sp_aggiorna_cv_responsabile(?, ?)
            ";

            $stmt = $this->pdo->prepare($sql);

            $result = $stmt->execute([
                $idUtente,
                $cvPdf
            ]);

            $stmt->closeCursor();

            return $result;
        }

    /*
    |--------------------------------------------------------------------------
    | ELIMINA CV
    |--------------------------------------------------------------------------
    */

        public function deleteCv($idUtente) {

        $sql = "
            CALL sp_elimina_cv_responsabile(?)
        ";

        $stmt = $this->pdo->prepare($sql);

        $result = $stmt->execute([
            $idUtente
        ]);

        $stmt->closeCursor();

        return $result;
    }
}
?>