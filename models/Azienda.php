<?php

class Azienda {

    public $id_azienda;
    public $nome;
    public $ragione_sociale;
    public $partita_iva;
    public $settore;
    public $numero_dipendenti;
    public $logo;
    public $nr_bilanci;
    public $id_responsabile;

    public function __construct(

        $id_azienda,
        $nome,
        $ragione_sociale,
        $partita_iva,
        $settore,
        $numero_dipendenti,
        $logo,
        $nr_bilanci,
        $id_responsabile

    ) {

        $this->id_azienda = $id_azienda;
        $this->nome = $nome;
        $this->ragione_sociale = $ragione_sociale;
        $this->partita_iva = $partita_iva;
        $this->settore = $settore;
        $this->numero_dipendenti = $numero_dipendenti;
        $this->logo = $logo;
        $this->nr_bilanci = $nr_bilanci;
        $this->id_responsabile = $id_responsabile;
    }
}
?>