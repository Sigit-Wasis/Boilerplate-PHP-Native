<?php
// Konfigurasi database
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS',);
define('DB_NAME', 'boilerplate');

// Fungsi koneksi database
function getDBConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }
    
    return $conn;
}
?>