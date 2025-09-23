<?php
require_once __DIR__ . '/../config/database.php';

// Fungsi untuk mendapatkan semua data jabatan
function getJabatan() {
    $conn = getDBConnection();

    $sql = "SELECT * FROM jabatan";
    $result = $conn->query($sql);

    $jabatan = [];
    if ($result && $result->num_rows > 0) { // perbaikan: cek $result sebelum akses num_rows
        while($row = $result->fetch_assoc()) {
            $jabatan[] = $row;
        }
    }
    
    $conn->close();
    return $jabatan;
}