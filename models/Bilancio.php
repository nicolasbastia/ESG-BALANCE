<?php

class Bilancio {

    public $id_bilancio;
    public $id_azienda;
    public $data_creazione;
    public $stato;

    public $nome_azienda;

    public function __construct(

        $id_bilancio,
        $id_azienda,
        $data_creazione,
        $stato,
        $nome_azienda = null

    ) {

        $this->id_bilancio = $id_bilancio;
        $this->id_azienda = $id_azienda;
        $this->data_creazione = $data_creazione;
        $this->stato = $stato;
        $this->nome_azienda = $nome_azienda;
    }
}

?>