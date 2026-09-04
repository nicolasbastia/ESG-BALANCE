<?php

class IndicatoreESG {

    public $id;
    public $nome;
    public $immagine;
    public $rilevanza;

    public $categoria;

    public $codice_normativa;

    public $ambito_sociale;

    public $frequenza_rilevazione;


    public function __construct(

        $id,
        $nome,
        $immagine,
        $rilevanza,
        $categoria = 'nessuna',
        $codice_normativa = null,
        $ambito_sociale = null,
        $frequenza_rilevazione = null

    ) {

        $this->id = $id;

        $this->nome = $nome;

        $this->immagine = $immagine;

        $this->rilevanza = $rilevanza;

        $this->categoria = $categoria;

        $this->codice_normativa = $codice_normativa;

        $this->ambito_sociale = $ambito_sociale;

        $this->frequenza_rilevazione = $frequenza_rilevazione;
    }
}
?>