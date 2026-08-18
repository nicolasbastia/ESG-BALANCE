<?php

class IndicatoreESG {

    public $id;
    public $nome;
    public $immagine;
    public $rilevanza;

    public function __construct(
        $id,
        $nome,
        $immagine,
        $rilevanza
    ) {

        $this->id = $id;
        $this->nome = $nome;
        $this->immagine = $immagine;
        $this->rilevanza = $rilevanza;
    }
}
?>