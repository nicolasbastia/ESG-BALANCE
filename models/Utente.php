<?php

class Utente {

    public $id_utente;
    public $username;
    public $ruolo;
    public $codice_fiscale;
    public $data_nascita;
    public $luogo_nascita;

    public function __construct(
        $id_utente,
        $username,
        $ruolo,
        $codice_fiscale = null,
        $data_nascita = null,
        $luogo_nascita = null
    ) {

        $this->id_utente = $id_utente;
        $this->username = $username;
        $this->ruolo = $ruolo;
        $this->codice_fiscale = $codice_fiscale;
        $this->data_nascita = $data_nascita;
        $this->luogo_nascita = $luogo_nascita;
    }

    /**
     * Verifica se l'utente è un amministratore
     * @return bool
     */
    public function isAdmin() {

        return $this->ruolo === 'amministratore';
    }

    /**
     * Verifica se l'utente è un revisore
     * @return bool
     */
    public function isRevisore() {

        return $this->ruolo === 'revisore';
    }

    /**
     * Verifica se l'utente è un responsabile
     * @return bool
     */
    public function isResponsabile() {

        return $this->ruolo === 'responsabile';
    }
}
?>