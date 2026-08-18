<?php

class GiudizioRevisione {

    public $id_giudizio;
    public $id_bilancio;
    public $esito;
    public $data_giudizio;
    public $rilievi;

    public function __construct(

        $id_giudizio,
        $id_bilancio,
        $esito,
        $data_giudizio,
        $rilievi

    ) {

        $this->id_giudizio = $id_giudizio;

        $this->id_bilancio = $id_bilancio;

        $this->esito = $esito;

        $this->data_giudizio = $data_giudizio;

        $this->rilievi = $rilievi;
    }
}
?>