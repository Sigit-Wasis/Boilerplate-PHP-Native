<?php
require_once __DIR__ . '/../../models/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';

    if (createUser($name, $email)) {
        // sukses → redirect ke list user
        header("Location: /user");
        exit;
    } else {
        echo "Gagal menyimpan user!";
    }
} else {
    echo "Akses tidak valid!";
}
