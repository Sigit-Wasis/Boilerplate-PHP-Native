<?php
session_start(); // Wajib di awal sebelum output apapun

require_once __DIR__ . '/../../models/User.php';

// Proses autentikasi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $user = authenticateUser($email, $password);

    if ($user) {
        // Simpan informasi user ke session
        $_SESSION['user'] = [
            'id'    => $user['id'],
            'email' => $user['email'],
            'nama_lengkap'  => $user['nama_lengkap'] ?? ''
        ];

        // Redirect ke dashboard / profil
        header("Location: /profil");
        exit;
    } else {
        echo "Email atau password salah!";
    }
} else {
    echo "Akses tidak valid!";
}