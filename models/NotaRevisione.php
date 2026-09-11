<?php

class NotaRevisione {

    public $id_nota;
    public $id_revisore;
    public $id_voce_bilancio;
    public $data_nota;
    public $testo;

    public $voce;
    public $revisore;

    public function __construct(
        $id_nota,
        $id_revisore,
        $id_voce_bilancio,
        $data_nota,
        $testo,
        $voce = null,
        $revisore = null
    ) {

        $this->id_nota = $id_nota;
        $this->id_revisore = $id_revisore;
        $this->id_voce_bilancio = $id_voce_bilancio;
        $this->data_nota = $data_nota;
        $this->testo = $testo;

        $this->voce = $voce;
        $this->revisore = $revisore;
    }
}

?>