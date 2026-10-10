<?php

// soal 2.1
$fruits = array("Apel", "Pisang", "Jeruk");

array_push($fruits, "Mangga", "Semangka", "Melon", "Durian", "Rambutan");

$arrlength = count($fruits);

// Menampilkan panjang array
echo "Panjang array saat ini: " . $arrlength . "<br><br>";

for ($x = 0; $x < $arrlength; $x++) {
    echo $fruits[$x];
    echo "<br>";
}


echo "<br>";


// soal 2.2
$vegies = array("Carrot", "Broccoli", "Spinach");

// Menghitung panjang array $vegies
$vegiesLength = count($vegies);

for ($i = 0; $i < $vegiesLength; $i++) {
    echo $vegies[$i];
    echo "<br>";
}
?>