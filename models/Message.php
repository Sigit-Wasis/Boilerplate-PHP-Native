<?php
require_once __DIR__. '/../config/database.php';
function getUser(): array { // Mengembalikan array, bukan array nullable (?)
    // 1. Ambil Koneksi
    $conn = getDBConnection();
    
    // 2. Siapkan Query
    // Query yang benar: Pastikan Anda mengambil kolom yang digunakan di tampilan: 
    // id, username, nama_lengkap, dan foto. Jika DB Anda tidak punya kolom ini,
    // Anda harus menggantinya (misalnya, ganti 'username' dengan 'name').
    $sql = "SELECT id, username, nama_lengkap, foto FROM users";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $users = [];
    
    // 3. Ambil Semua Hasil dengan Perulangan
    // Loop melalui setiap baris hasil dan masukkan ke dalam array $users
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }
    
    // 4. Tutup Koneksi
    $stmt->close();
    $conn->close();
    
    return $users; // Mengembalikan array dari semua user (bisa kosong [])
}