<?php
session_start();
require_once __DIR__ . '/../../models/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';

    if (createUser($name, $email)) {
        // sukses → redirect ke list user
        $_SESSION['success'] = "User berhasil dibuat!";
        header("Location: /user");
        exit;
    } else {
        $_SESSION['error'] = "Gagal menyimpan user!";
    }
} else {
    $_SESSION['error'] = "Akses tidak valid!";
}
