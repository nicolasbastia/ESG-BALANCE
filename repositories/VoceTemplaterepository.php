<?php

require_once __DIR__ . '/../config/db.php';

class VoceTemplateRepository {

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

            SELECT *

            FROM voce_template

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
    $descrizione,
    $idAmministratore
    ) {

    $sql = "
        CALL sp_crea_voce_template(?, ?, ?)
    ";

    $stmt = $this->pdo->prepare($sql);

    $result = $stmt->execute([
        $nome,
        $descrizione,
        $idAmministratore
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
        CALL sp_elimina_voce_template(?)
    ";

    $stmt = $this->pdo->prepare($sql);

    $result = $stmt->execute([$id]);

    $stmt->closeCursor();

    return $result;
    }
}
?>