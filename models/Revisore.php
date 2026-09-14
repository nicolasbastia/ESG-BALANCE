<?php

class Revisore {

    public $id_utente;
    public $numero_revisioni;
    public $indice_affidabilita;

    public function __construct(
        $id_utente,
        $numero_revisioni = 0,
        $indice_affidabilita = null
    ) {

        $this->id_utente = $id_utente;
        $this->numero_revisioni = $numero_revisioni;
        $this->indice_affidabilita = $indice_affidabilita;
    }

    /* CONTROLLO AFFIDABILITA */
    public function getAffidabilitaPercentuale() {

        if ($this->indice_affidabilita === null) {
            return null;
        }

        return (float) $this->indice_affidabilita;
    }


    public function isAffidabile() {

        if ($this->indice_affidabilita === null) {
            return false;
        }

        return $this->indice_affidabilita >= 80;
    }


    public function getLivelloAffidabilita() {

        if ($this->indice_affidabilita === null) {
            return 'non determinato';
        }

        if ($this->indice_affidabilita >= 80) {
            return 'alto';
        }

        if ($this->indice_affidabilita >= 60) {
            return 'medio';
        }

        return 'basso';
    }
}
?>