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

    /**
     * Calcola l'affidabilità come percentuale
     * @return float|null
     */
    public function getAffidabilitaPercentuale() {

        if ($this->indice_affidabilita === null) {
            return null;
        }

        return $this->indice_affidabilita;
    }

    /**
     * Verifica se il revisore è affidabile (indice > 80%)
     * @return bool
     */
    public function isAffidabile() {

        if ($this->indice_affidabilita === null) {
            return false;
        }

        return $this->indice_affidabilita > 80;
    }

    /**
     * Ritorna il livello di affidabilità (basso, medio, alto)
     * @return string
     */
    public function getLivelloAffidabilita() {

        if ($this->indice_affidabilita === null) {
            return 'non determinato';
        }

        if ($this->indice_affidabilita >= 80) {
            return 'alto';
        } elseif ($this->indice_affidabilita >= 60) {
            return 'medio';
        } else {
            return 'basso';
        }
    }
}
?>
