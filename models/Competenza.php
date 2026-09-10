<?php

class Competenza {

    public $id_utente;
    public $id_competenza;
    public $livello;
    public $nome;

    public function __construct(
        $id_utente,
        $id_competenza,
        $livello,
        $nome = null
    ) {

        $this->id_utente = $id_utente;
        $this->id_competenza = $id_competenza;
        $this->livello = $livello;
        $this->nome = $nome;
    }

    /**
     * Valida il livello della competenza
     * @return bool
     */
    public function isValid() {

        return $this->livello >= 0 && $this->livello <= 5;
    }
}
?>
