<?php


$dat = fopen("tekst.txt", "r+");


$str_tekst = fread($dat, filesize("tekst.txt"));


fclose($dat);

echo "<h1>$str_tekst</h1>";


$izrezani_tekst = explode(" ", $str_tekst);


$dat = fopen("tekst.txt", "a");

fwrite($dat, "\n\nIzrezane riječi\n");

foreach($izrezani_tekst as $rijec) {
    fwrite($dat, $rijec . "\n");
}

fclose($dat);


$prvo_k = strpos($str_tekst, "k") + 1;
$ukupno_k = substr_count($str_tekst, "k");

echo "<h2>Prvi put se slovo k pojavljuje na $prvo_k . mjestu i ukupno se pojavljuje $ukupno_k puta.</h2>";

?>
