<?php

class VoceBilancio {

    public $id_bilancio;
    public $id_voce;
    public $valore;

    public function __construct(

        $id_bilancio,
        $id_voce,
        $valore

    ) {

        $this->id_bilancio = $id_bilancio;
        $this->id_voce = $id_voce;
        $this->valore = $valore;
    }
}
?>