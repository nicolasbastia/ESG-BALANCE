<?php

class EmailUtente {

    public $id_email;
    public $id_utente;
    public $email;

    public function __construct(
        $id_email,
        $id_utente,
        $email
    ) {

        $this->id_email = $id_email;
        $this->id_utente = $id_utente;
        $this->email = $email;
    }

    public function isValidEmail() {

        return filter_var($this->email, FILTER_VALIDATE_EMAIL) !== false;
    }
}
?>
