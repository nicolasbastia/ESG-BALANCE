<?php

class Azienda {

    public $id;
    public $nome;
    public $ragione_sociale;
    public $partita_iva;
    public $settore;
    public $numero_dipendenti;
    public $logo;
    public $id_responsabile;

    public function __construct(

        $id,
        $nome,
        $ragione_sociale,
        $partita_iva,
        $settore,
        $numero_dipendenti,
        $logo,
        $id_responsabile

    ) {

        $this->id = $id;
        $this->nome = $nome;
        $this->ragione_sociale = $ragione_sociale;
        $this->partita_iva = $partita_iva;
        $this->settore = $settore;
        $this->numero_dipendenti = $numero_dipendenti;
        $this->logo = $logo;
        $this->id_responsabile = $id_responsabile;
    }
}
?>