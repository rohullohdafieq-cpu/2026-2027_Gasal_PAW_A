<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Pemrosesan Data</title>
</head>
<body>

    <?php
    require 'validate.inc';

    $errors = array();

    validateName($errors, $_POST, 'nama');

    if ($errors) {
        echo 'Errors:<br/>';
        foreach ($errors as $field => $error) {
            echo "$field $error<br/>";
        }
    } else {
        echo 'Data OK!';
    }
    ?>

</body>
</html>