<?php

class Revisione {

    public $id_revisione;
    public $id_bilancio;
    public $id_revisore;
    public $data_assegnazione;
    public $username;
    public $azienda;
    public $stato;

    public function __construct(
        $id_revisione,
        $id_bilancio,
        $id_revisore,
        $data_assegnazione,
        $username = null,
        $azienda = null,
        $stato = null
    ) {

        $this->id_revisione = $id_revisione;
        $this->id_bilancio = $id_bilancio;
        $this->id_revisore = $id_revisore;
        $this->data_assegnazione = $data_assegnazione;
        $this->username = $username;
        $this->azienda = $azienda;
        $this->stato = $stato;
    }
}
?>
