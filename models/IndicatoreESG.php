<?php

class IndicatoreESG {

    public $id_indicatore;
    public $nome;
    public $immagine;
    public $rilevanza;

    public $categoria;

    /* DATI INDICATORE AMBIENTALE */

    public $codice_normativa;

    /* DATI INDICATORE SOCIALE  */

    public $ambito_sociale;
    public $frequenza_rilevazione;

    
    public function __construct(
        $id_indicatore,
        $nome,
        $immagine,
        $rilevanza,
        $categoria = 'nessuna',
        $codice_normativa = null,
        $ambito_sociale = null,
        $frequenza_rilevazione = null
    ) {

        $this->id_indicatore = $id_indicatore;
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