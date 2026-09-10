<?php

class VoceTemplate {

    public $id_voce;
    public $nome;
    public $descrizione;

    public function __construct(

        $id_voce,
        $nome,
        $descrizione

    ) {

        $this->id_voce = $id_voce;
        $this->nome = $nome;
        $this->descrizione = $descrizione;
    }
}
?>