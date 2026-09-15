<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Bilancio.php';

class BilancioRepository {

    private $pdo;

    public function __construct() {

        global $pdo;

        $this->pdo = $pdo;
    }


    public function create(Bilancio $bilancio) {

        $sql = "

            CALL sp_crea_bilancio(
                ?,
                ?
            )

        ";

        $stmt = $this->pdo->prepare($sql);

        $result = $stmt->execute([

            $bilancio->id_azienda,
            $bilancio->data_creazione

        ]);

        $stmt->closeCursor();

        return $result;
    }


    public function delete($idBilancio) {

        $sql = "

            CALL sp_elimina_bilancio(?)

        ";

        $stmt = $this->pdo->prepare($sql);

        $result = $stmt->execute([
            $idBilancio
        ]);

        $stmt->closeCursor();

        return $result;
    }

    /* LISTA BILANCI DEL RESPONSABILE */

    public function getByResponsabile($idResponsabile) {

        $sql = "

            SELECT
                b.id_bilancio,
                a.nome AS nome_azienda,
                b.id_azienda,
                b.data_creazione,
                b.stato
            

            FROM bilancio b

            JOIN azienda a
                ON b.id_azienda = a.id_azienda

            WHERE a.id_responsabile = ?

            ORDER BY b.data_creazione DESC

        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            $idResponsabile
        ]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $bilanci = [];

        foreach($rows as $row) {

            $bilanci[] = new Bilancio(

                $row['id_bilancio'],
                $row['id_azienda'],
                $row['data_creazione'],
                $row['stato'],
                $row['nome_azienda']

            );
        }

        return $bilanci;
    }

    /* LISTA AZIENDE DEL RESPONSABILE */

    public function getAziendeResponsabile($idResponsabile) {

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

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* DETTAGLIO BILANCIO */

    public function getDettaglioBilancio($idBilancio) {

        $sql = "

            SELECT
                vb.id_voce_bilancio,
                vt.nome AS voce,
                vb.valore,
                ie.nome AS indicatore,
                vi.valore_indicatore,
                vi.fonte,
                vi.data_rilevazione

            FROM voce_bilancio vb

            JOIN voce_template vt
                ON vb.id_voce = vt.id_voce

            LEFT JOIN voce_indicatore vi
                ON vb.id_voce_bilancio = vi.id_voce_bilancio

            LEFT JOIN indicatore_esg ie
                ON vi.id_indicatore = ie.id_indicatore

            WHERE vb.id_bilancio = ?

            ORDER BY vt.nome

        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            $idBilancio
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}

?>