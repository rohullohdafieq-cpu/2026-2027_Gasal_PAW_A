<?php
// Validasi Nama (hanya alfabet, petik satu, dan tanda hubung)
function validateName(&$errors, $field_list, $field_name)
{
    $pattern = "/^[a-zA-Z'-]+$/";
    if (!isset($field_list[$field_name]) || empty(trim($field_list[$field_name]))) {
        $errors[$field_name] = 'wajib diisi';
    } else if (!preg_match($pattern, $field_list[$field_name])) {
        $errors[$field_name] = 'hanya boleh berisi huruf alfabet';
    }
}

// Validasi Email (format email valid)
function validateEmail(&$errors, $field_list, $field_name)
{
    if (!isset($field_list[$field_name]) || empty(trim($field_list[$field_name]))) {
        $errors[$field_name] = 'wajib diisi';
    } else if (!filter_var($field_list[$field_name], FILTER_VALIDATE_EMAIL)) {
        $errors[$field_name] = 'format email tidak valid';
    }
}

// Validasi Kata Sandi (minimal 6 karakter)
function validatePassword(&$errors, $field_list, $field_name)
{
    if (!isset($field_list[$field_name]) || empty($field_list[$field_name])) {
        $errors[$field_name] = 'wajib diisi';
    } else if (strlen($field_list[$field_name]) < 6) {
        $errors[$field_name] = 'minimal 6 karakter';
    }
}

// Validasi Detail Alamat (minimal 10 karakter)
function validateAddress(&$errors, $field_list, $field_name)
{
    if (!isset($field_list[$field_name]) || empty(trim($field_list[$field_name]))) {
        $errors[$field_name] = 'wajib diisi';
    } else if (strlen(trim($field_list[$field_name])) < 10) {
        $errors[$field_name] = 'minimal 10 karakter';
    }
}

// Validasi Pilihan Dropdown Kota (wajib memilih)
function validateCity(&$errors, $field_list, $field_name)
{
    if (!isset($field_list[$field_name]) || empty($field_list[$field_name])) {
        $errors[$field_name] = 'wajib dipilih';
    }
}

// Validasi Radio Button Jenis Kelamin (wajib memilih)
function validateGender(&$errors, $field_list, $field_name)
{
    if (!isset($field_list[$field_name]) || empty($field_list[$field_name])) {
        $errors[$field_name] = 'wajib dipilih';
    }
}
?>