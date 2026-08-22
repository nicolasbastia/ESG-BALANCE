<?php

require_once __DIR__ . '/../config/db.php';

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

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | AGGIORNA CV
    |--------------------------------------------------------------------------
    */

    public function updateCv($idUtente, $cvPdf) {

        $sql = "

            UPDATE responsabile_aziendale

            SET cv_pdf = ?

            WHERE id_utente = ?

        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            $cvPdf,
            $idUtente
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | ELIMINA CV
    |--------------------------------------------------------------------------
    */

    public function deleteCv($idUtente) {

        $sql = "

            UPDATE responsabile_aziendale

            SET cv_pdf = NULL

            WHERE id_utente = ?

        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([$idUtente]);
    }
}
?>