<?php

require_once 'index.php';

$buildings = $app->createBuildings();

if(!$app->isLogged()) {
    header("Location: login.php");
    exit;
}

if(isset($_GET['id'])) {
    $buildings->evolve($_GET['id']);
    header("Location: buildings.php");
    exit;
}

loadTemplate("buildings", $buildings);