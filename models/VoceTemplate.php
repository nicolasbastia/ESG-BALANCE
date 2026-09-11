<?php

class VoceTemplate {

    public $id_voce;
    public $nome;
    public $descrizione;
    public $id_amministratore;

    public function __construct(
        $id_voce,
        $nome,
        $descrizione,
        $id_amministratore = null
    ) {

        $this->id_voce = $id_voce;
        $this->nome = $nome;
        $this->descrizione = $descrizione;
        $this->id_amministratore = $id_amministratore;
    }
}

?>