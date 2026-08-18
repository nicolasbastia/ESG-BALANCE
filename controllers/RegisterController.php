<?php

require_once __DIR__ . '/../repositories/RegisterRepository.php';

class RegisterController {

    private $repo;

    public function __construct() {

        $this->repo = new RegisterRepository();
    }

    public function index() {

        require __DIR__ . '/../views/auth/register.php';
    }

    public function register() {

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $this->repo->createUser($_POST);

            header('Location: login.php');

            exit;
        }
    }
}
?>