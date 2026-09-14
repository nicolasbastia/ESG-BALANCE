<?php

class VoceBilancio {

    public $id_voce_bilancio;
    public $id_bilancio;
    public $id_voce;
    public $valore;

    public $nome_voce;

    public function __construct(
        $id_voce_bilancio,
        $id_bilancio,
        $id_voce,
        $valore,
        $nome_voce = null
    ) {

        $this->id_voce_bilancio = $id_voce_bilancio;
        $this->id_bilancio = $id_bilancio;
        $this->id_voce = $id_voce;
        $this->valore = $valore;
        $this->nome_voce = $nome_voce;
    }
}

?>