<?php
require_once __DIR__ . '/../config/database.php';

// Fungsi untuk sanitasi input
function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Fungsi untuk mendapatkan semua data jabatan
function getJabatan() {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM jabatan");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}