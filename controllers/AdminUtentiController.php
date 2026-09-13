<?php

session_start();

require_once __DIR__ . '/../repositories/AdminUtentiRepository.php';

class AdminUtentiController {

    private $repo;

    public function __construct() {
        $this->repo = new AdminUtentiRepository();
    }

    /**
     * Controlla che l'utente sia autenticato
     * e che abbia ruolo amministratore.
     */
    private function checkAccess() {

        if(
            !isset($_SESSION['utente']) ||
            ($_SESSION['utente']['ruolo'] ?? '') !== 'amministratore'
        ) {
            header('Location: /esg-balance/index.php');
            exit;
        }
    }

    /**
     * Pagina principale di consultazione utenti.
     */
    public function index() {

        $this->checkAccess();

        $revisori = $this->repo->getRevisoriConCompetenze();

        $responsabili = $this->repo->getResponsabiliConAziende();

        require __DIR__ . '/../views/admin/utenti.php';
    }
}

?>