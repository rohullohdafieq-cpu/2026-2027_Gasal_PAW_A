<?php

// soal 6.1
$arr1 = array("A");
echo 'Array awal: ("A")<br>';
array_push($arr1, "B");
echo 'Hasil array_push: ' . implode(" ", $arr1) . '<br><br>';

$arr_a = array("A", "B");
$arr_b = array("C");
echo 'Array awal: ("A", "B") digabung dengan ("C")<br>';
$merged = array_merge($arr_a, $arr_b);
echo 'Hasil array_merge: ' . implode(" ", $merged) . '<br><br>';

$arr_assoc = array("x" => 1, "y" => 2);
echo 'Array awal: ("x" => 1, "y" => 2)<br>';
$values = array_values($arr_assoc);
echo 'Hasil array_values: ' . implode(" ", $values) . '<br><br>';

$arr_search = array("A", "B", "C");
echo 'Mencari "B" pada array: ("A", "B", "C")<br>';
$key = array_search("B", $arr_search);
echo 'Hasil array_search: ' . $key . '<br><br>';

$arr_filter = array(0, 1, false, 2, "", 3, "array");
echo 'Array awal: (0, 1, false, 2, "", 3, "array")<br>';
$filtered = array_filter($arr_filter);
echo 'Hasil array_filter: ' . implode(" ", $filtered) . '<br><br>';

$numbers = array(3, 1, 2);
echo 'Array awal: (3, 1, 2)<br>';

$num_sort = $numbers;
sort($num_sort);
echo 'Hasil sort: ' . implode(" ", $num_sort) . '<br>';

$num_rsort = $numbers;
rsort($num_rsort);
echo 'Hasil rsort: ' . implode(" ", $num_rsort) . '<br><br>';

$age = array("Peter" => 35, "Ben" => 37, "Joe" => 43);
echo 'Array awal: ("Peter"=>35, "Ben"=>37, "Joe"=>43)<br>';

$arr_asort = $age;
asort($arr_asort);
$res_asort = array();
foreach ($arr_asort as $k => $v) { $res_asort[] = "$k=> $v"; }
echo 'Hasil asort: ' . implode(", ", $res_asort) . ',<br>';

$arr_ksort = $age;
ksort($arr_ksort);
$res_ksort = array();
foreach ($arr_ksort as $k => $v) { $res_ksort[] = "$k=> $v"; }
echo 'Hasil ksort: ' . implode(", ", $res_ksort) . ',<br>';

$arr_arsort = $age;
arsort($arr_arsort);
$res_arsort = array();
foreach ($arr_arsort as $k => $v) { $res_arsort[] = "$k=> $v"; }
echo 'Hasil arsort: ' . implode(", ", $res_arsort) . ',<br>';

$arr_krsort = $age;
krsort($arr_krsort);
$res_krsort = array();
foreach ($arr_krsort as $k => $v) { $res_krsort[] = "$k=> $v"; }
echo 'Hasil krsort: ' . implode(", ", $res_krsort) . ',';
?>