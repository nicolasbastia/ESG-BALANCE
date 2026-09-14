<?php

class Revisione {

    public $id_revisione;
    public $id_bilancio;
    public $id_revisore;
    public $data_assegnazione;
    public $stato;

    public $username;
    public $azienda;

    public function __construct(
        $id_revisione,
        $id_bilancio,
        $id_revisore,
        $data_assegnazione,
        $stato = null,
        $username = null,
        $azienda = null
    ) {

        $this->id_revisione = $id_revisione;
        $this->id_bilancio = $id_bilancio;
        $this->id_revisore = $id_revisore;
        $this->data_assegnazione = $data_assegnazione;
        $this->stato = $stato;

        $this->username = $username;
        $this->azienda = $azienda;
    }
}

?>