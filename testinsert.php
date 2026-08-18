<?php

require_once 'config/mongo.php';

$collection = $mongoDB->eventi;

$result = $collection->insertOne([

    'evento' => 'MongoDB funziona',

    'timestamp' => new \MongoDB\BSON\UTCDateTime()

]);

echo "INSERITO";
?>