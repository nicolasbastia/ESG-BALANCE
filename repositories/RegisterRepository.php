<?php

require_once __DIR__ . '/../config/db.php';

class RegisterRepository {

    private $pdo;

    public function __construct() {

        global $pdo;

        $this->pdo = $pdo;
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE USER
    |--------------------------------------------------------------------------
    */

    public function createUser($data) {

        $sql = "

            CALL sp_registra_utente(
                ?,
                ?,
                ?,
                ?,
                ?,
                ?
            )

        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([

            $data['username'],
            password_hash(
                $data['password'],
                PASSWORD_DEFAULT
            ),
            $data['codice_fiscale'],
            $data['data_nascita'],
            $data['luogo_nascita'],
            $data['ruolo']

        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $idUtente = $result['id_utente'];

        $stmt->closeCursor();

        /*
        |--------------------------------------------------------------------------
        | EMAIL
        |--------------------------------------------------------------------------
        */

        foreach($data['emails'] as $email) {

            if(!empty($email)) {

                $sql = "
                    CALL sp_aggiungi_email_utente(?, ?)
                ";

                $stmt = $this->pdo->prepare($sql);

                $stmt->execute([
                    $idUtente,
                    $email
                ]);

                $stmt->closeCursor();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | TABELLE RUOLI
        |--------------------------------------------------------------------------
        */

        if($data['ruolo'] == 'revisore') {

                $sql = "
                    CALL sp_crea_revisore_esg(?)
                ";

                $stmt = $this->pdo->prepare($sql);

                $stmt->execute([$idUtente]);

                $stmt->closeCursor();
            }
            
        if($data['ruolo'] == 'responsabile') {

                $sql = "
                    CALL sp_crea_responsabile_aziendale(?)
                ";

                $stmt = $this->pdo->prepare($sql);

                $stmt->execute([$idUtente]);

                $stmt->closeCursor();
            }

        return true;
    }
}
?>