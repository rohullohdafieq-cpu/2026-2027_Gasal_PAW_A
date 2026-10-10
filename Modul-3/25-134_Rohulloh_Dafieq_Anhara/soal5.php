<?php

// soal 5.1
$students = [
    ["Alex", "220401", "0812345678"],
    ["Bianca", "220402", "0812345687"],
    ["Candice", "220403", "0812345665"]
];

echo "Data awal:<br>";
echo "students = <br>";
$total_awal = count($students);
for ($i = 0; $i < $total_awal; $i++) {
    echo '  ("' . implode('", "', $students[$i]) . '")';
    if ($i < $total_awal - 1)
    echo "<br>";
}
echo "<br><br>";

array_push($students, 
    ["Daniel", "220404", "0812345611"],
    ["Elena", "220405", "0812345622"],
    ["Fiona", "220406", "0812345633"],
    ["Gabe", "220407", "0812345644"],
    ["Hannah", "220408", "0812345655"]
);

echo "Data setelah ditambah 5 data lain:<br>";
echo "students = <br>";
$total_akhir = count($students);
for ($i = 0; $i < $total_akhir; $i++) {
    echo '  ("' . implode('", "', $students[$i]) . '")';
    if ($i < $total_akhir - 1)
    echo "<br>";
}
?>