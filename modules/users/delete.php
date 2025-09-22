<?php
require_once __DIR__ . '/../../models/User.php';

$id = $_GET['id'] ?? null;

if ($id && deleteUser($id)) {
    header("Location: /user");
    exit;
} else {
    echo "Gagal menghapus user!";
}
