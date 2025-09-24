<?php
require_once __DIR__ . '/../../models/Post.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id    = $_POST['id'] ?? null;
    $name  = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';

    if ($id && updateUser($id, $name, $email)) {
        header("Location: /post");
        exit;
    } else {
        echo "Gagal mengupdate post";
    }
} else {
    echo "Akses tidak valid!";
}