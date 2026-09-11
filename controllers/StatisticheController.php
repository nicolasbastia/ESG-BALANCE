<?php

require_once __DIR__ . '/../repositories/StatisticheRepository.php';

class StatisticheController
{
    private $repo;

    public function __construct()
    {
        $this->repo = new StatisticheRepository();
    }

    public function index()
    {
        session_start();

        if(!isset($_SESSION['utente'])) {

            header('Location: /esg-balance/login.php');
            exit;
        }

        $utente = $_SESSION['utente'];

        $numeroAziende = $this->repo->getNumeroAziende();

        $numeroRevisori = $this->repo->getNumeroRevisori();

        $aziendaPiuAffidabile = $this->repo->getAziendaPiuAffidabile();

        $classificaBilanci = $this->repo->getClassificaBilanci();

        include __DIR__ . '/../views/statistiche.php';
    }
}