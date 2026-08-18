<?php

class Bilancio {

    public $id;
    public $id_azienda;
    public $data_creazione;
    public $stato;

    public function __construct(

        $id,
        $id_azienda,
        $data_creazione,
        $stato

    ) {

        $this->id = $id;
        $this->id_azienda = $id_azienda;
        $this->data_creazione = $data_creazione;
        $this->stato = $stato;
    }
}
?>