<?php

if(isset($_GET["ime"]) && isset($_GET["prezime"])) {
    $ime = $_GET["ime"];
    $prezime = $_GET["prezime"];
    echo "<h1>$ime $prezime</h1>";
} else {
    echo "<h1>Dogodila se pogreška. T-T</h1>";
}

?>
