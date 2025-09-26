<?php
session_start();
require_once __DIR__ . '/../../models/User.php';

if (!isset($_SESSION['user'])) {
    header("Location: /login");
    exit;
}

$id = $_GET['id'] ?? null;

if ($id && deleteUser($id)) {
    header("Location: /user");
    exit;
} else {
    echo "Gagal menghapus user!";
}
