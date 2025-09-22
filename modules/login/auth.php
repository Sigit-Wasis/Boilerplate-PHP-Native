<?php
require_once __DIR__ . '/../../models/User.php';

// Proses autentikasi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    echo "Email: $email, Password: $password<br>";

    if (authenticateUser($email, $password)) {
        // sukses → redirect ke dashboard atau halaman utama
        header("Location: /profil");
        exit;
    } else {
        echo "Email atau password salah!";
    }
} else {
    echo "Akses tidak valid!";
}

?>