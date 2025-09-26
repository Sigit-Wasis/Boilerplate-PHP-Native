<?php
session_start();
require_once __DIR__ . '/../../models/User.php';

if (!isset($_SESSION['user'])) {
    header("Location: /login");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id    = $_POST['id'] ?? null;
    $name  = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';

    if ($id && updateUser($id, $name, $email)) {
        header("Location: /user");
        exit;
    } else {
        echo "Gagal mengupdate user!";
    }
} else {
    echo "Akses tidak valid!";
}
