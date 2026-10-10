<?php

// soal 4.1
$height = array("Andy"=>"176", "Barry"=>"165", "Charlie"=>"170");

$height["David"] = "180";
$height["Ethan"] = "172";
$height["Frank"] = "168";
$height["George"] = "175";
$height["Harry"] = "182";

$i = 0;
$total = count($height);
echo "height = ( ";
foreach ($height as $name => $val) {
    echo '"' . $name . '"=>"' . $val . '"';
    if ($i < $total - 1) {
        echo ", ";
    }
    $i++;
}
echo " )<br><br>";

foreach ($height as $x => $x_value) {
    echo $x . " is " . $x_value . " cm tall.<br>";
}


echo "<br>";


// soal 4.2
$weight = array("Andy"=>"70", "Barry"=>"65", "Charlie"=>"75");

$i = 0;
$total = count($weight);
echo "weight = ( ";
foreach ($weight as $name => $val) {
    echo '"' . $name . '"=>"' . $val . '"';
    if ($i < $total - 1) {
        echo ", ";
    }
    $i++;
}
echo " )<br><br>";

$keys = array_keys($weight);
$total_data = count($keys);

for ($x = 0; $x < $total_data; $x++) {
    $nama = $keys[$x];
    $berat = $weight[$nama];
    echo $nama . " is " . $berat . " kg.<br>";
}
?>