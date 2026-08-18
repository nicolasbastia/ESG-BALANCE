<?php

class VoceTemplate {

    public $id;
    public $nome;
    public $descrizione;

    public function __construct(

        $id,
        $nome,
        $descrizione

    ) {

        $this->id = $id;
        $this->nome = $nome;
        $this->descrizione = $descrizione;
    }
}
?>