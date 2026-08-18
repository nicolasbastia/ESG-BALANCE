<?php

require_once __DIR__ . '/mongo.php';

function salvaEvento($evento) {

    global $mongoDB;

    $collection = $mongoDB->eventi;

    $collection->insertOne([

        'evento' => $evento,

        'timestamp' => new \MongoDB\BSON\UTCDateTime()

    ]);
}
?>