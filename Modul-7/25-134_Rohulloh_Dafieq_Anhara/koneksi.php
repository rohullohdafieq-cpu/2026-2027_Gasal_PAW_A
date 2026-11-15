<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "tp_modul07"; // Sesuaikan dengan nama database Anda

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>