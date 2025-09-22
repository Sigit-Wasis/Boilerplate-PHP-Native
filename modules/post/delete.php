<?php
require_once _DIR_ . '/../../models/Post.php';

$id = $_GET['id'] ?? null;

if ($id && deleteUser($id)) {
    header("Location: /post");
    exit;
} else {
    echo "Gagal menghapus post!";
}