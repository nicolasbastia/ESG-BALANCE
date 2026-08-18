<?php

class Utente {

    public $id;
    public $username;
    public $ruolo;

    public function __construct(
        $id,
        $username,
        $ruolo
    ) {

        $this->id = $id;
        $this->username = $username;
        $this->ruolo = $ruolo;
    }
}
?>