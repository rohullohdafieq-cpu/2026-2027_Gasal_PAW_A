<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Data Diri</title>
</head>
<body>

<?php
$errors = array();

// Memeriksa apakah form sudah dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'validate.inc';

    // Memanggil semua fungsi validasi
    validateName($errors, $_POST, 'nama');
    validateEmail($errors, $_POST, 'email');
    validatePassword($errors, $_POST, 'kata_sandi');
    validateAddress($errors, $_POST, 'detail_alamat');
    validateCity($errors, $_POST, 'kota');
    validateGender($errors, $_POST, 'jenis_kelamin');

    // Cek apakah ada error
    if ($errors) {
        echo '<h1>Terjadi kesalahan, harap perbaiki inputan berikut:</h1>';
        echo '<ul>';
        foreach ($errors as $field => $error) {
            echo "<li>Kolom <strong>$field</strong>: $error</li>";
        }
        echo '</ul>';
        
        // Tampilkan kembali form dengan isian data sebelumnya (sticky form)
        include 'form.inc';
    } else {
        echo '<h1>Form berhasil dikirim tanpa kesalahan!</h1>';
    }
} else {
    // Tampilan awal saat pertama kali dibuka
    include 'form.inc';
}
?>

</body>
</html>