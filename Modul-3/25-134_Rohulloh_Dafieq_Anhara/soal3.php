<?php

// soal 3.1
$height = array("Andy"=>"176", "Barry"=>"165", "Charlie"=>"170");

$height["David"] = "180";
$height["Ethan"] = "172";
$height["Frank"] = "168";
$height["George"] = "175";
$height["Harry"] = "182";

$formatted = array_map(function($k, $v) {
    return "\"$k\"=>\"$v\"";
}, array_keys($height), array_values($height));

echo "height = ( " . implode(", ", $formatted) . " )<br>";

echo "Nilai dengan indeks terakhir: " . end($height) . "<br><br>";

unset($height["Barry"]);

$formatted = array_map(function($k, $v) {
    return "\"$k\"=>\"$v\"";
}, array_keys($height), array_values($height));

echo "height = ( " . implode(", ", $formatted) . " )<br>";

echo "Nilai dengan indeks terakhir setelah dihapus: " . end($height);


echo "<br>";


// soal 3.2
$weight = array("Andy"=>"70", "Barry"=>"65", "Charlie"=>"75");

$formatted = array_map(function($k, $v) {
    return "\"$k\"=>\"$v\"";
}, array_keys($weight), array_values($weight));

echo "weight = ( " . implode(", ", $formatted) . " )<br>";

$values = array_values($weight);
echo "Data kedua: " . $values[1];
?>