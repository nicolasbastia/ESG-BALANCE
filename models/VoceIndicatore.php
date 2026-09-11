<?php

class VoceIndicatore {

    public $id_voce_bilancio;
    public $id_indicatore;
    public $valore_indicatore;
    public $fonte;
    public $data_rilevazione;

    public $nome_voce;
    public $nome_indicatore;

    public function __construct(
        $id_voce_bilancio,
        $id_indicatore,
        $valore_indicatore,
        $fonte,
        $data_rilevazione,
        $nome_voce = null,
        $nome_indicatore = null
    ) {

        $this->id_voce_bilancio = $id_voce_bilancio;
        $this->id_indicatore = $id_indicatore;
        $this->valore_indicatore = $valore_indicatore;
        $this->fonte = $fonte;
        $this->data_rilevazione = $data_rilevazione;

        $this->nome_voce = $nome_voce;
        $this->nome_indicatore = $nome_indicatore;
    }
}

?>