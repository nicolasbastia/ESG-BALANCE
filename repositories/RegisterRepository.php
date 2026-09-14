<?php

require_once __DIR__ . '/../config/db.php';

class RegisterRepository {

    private $pdo;

    public function __construct() {

        global $pdo;

        $this->pdo = $pdo;
    }


    public function createUser($data) {

        try {


            $this->pdo->beginTransaction();

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

            $stmt->closeCursor();

            if(
                !$result ||
                !isset($result['id_utente'])
            ) {

                throw new Exception(
                    "Errore durante la creazione dell'utente."
                );
            }

            $idUtente = $result['id_utente'];


            foreach($data['emails'] as $email) {

                $email = trim($email);

                if($email !== '') {

                    $sql = "

                        CALL sp_aggiungi_email_utente(
                            ?,
                            ?
                        )

                    ";

                    $stmt = $this->pdo->prepare($sql);

                    $stmt->execute([
                        $idUtente,
                        $email
                    ]);

                    $stmt->closeCursor();
                }
            }


            if($data['ruolo'] === 'revisore') {

                $sql = "

                    CALL sp_crea_revisore_esg(?)

                ";

                $stmt = $this->pdo->prepare($sql);

                $stmt->execute([
                    $idUtente
                ]);

                $stmt->closeCursor();
            }


            if($data['ruolo'] === 'responsabile') {

                $sql = "

                    CALL sp_crea_responsabile_aziendale(?)

                ";

                $stmt = $this->pdo->prepare($sql);

                $stmt->execute([
                    $idUtente
                ]);

                $stmt->closeCursor();
            }



            $this->pdo->commit();

            return true;

        } catch(Throwable $e) {



            if($this->pdo->inTransaction()) {

                $this->pdo->rollBack();
            }

            throw $e;
        }
    }
}

?>