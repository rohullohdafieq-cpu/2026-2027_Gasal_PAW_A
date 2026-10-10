<?php

// soal 1.1
$fruits = array("Apel", "Pisang", "Jeruk");

array_push($fruits, "Mangga", "Semangka", "Melon", "Durian", "Rambutan");

echo "fruits = ( \"" . implode("\", \"", $fruits) . "\" )<br>";

$max_index = count($fruits) - 1;
echo "Nilai dengan indeks tertinggi: " . $fruits[$max_index];


echo "<br>";


// soal 1.2
$fruits = array("Apel", "Pisang", "Jeruk");

$key = array_search("Pisang", $fruits);
if ($key !== false) {
    unset($fruits[$key]);
    $fruits = array_values($fruits); 
    echo "Data Pisang dihapus.<br>";
}

echo "fruits = ( \"" . implode("\", \"", $fruits) . "\" )<br>";

$max_index = count($fruits) - 1;
echo "Nilai dengan indeks tertinggi: " . $fruits[$max_index];
?>
