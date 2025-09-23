<?php
require_once __DIR__ . '/../../models/Post.php';

$id = $_GET['id'] ?? null;

if ($id && deleteUser($id)) {
    header("Location: /post");
    exit;
} else {
    echo "Gagal menghapus post!";
}