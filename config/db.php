<?php

$host = "localhost";
$port = "3307";
$dbname = "esg_balance";
$user = "root";
$password = "root";

try {

    $pdo = new PDO(

        "mysql:host=$host;port=$port;dbname=$dbname",

        $user,
        $password

    );

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

}

catch(PDOException $e) {

    die(
        "Errore DB: " .
        $e->getMessage()
    );
}
?>