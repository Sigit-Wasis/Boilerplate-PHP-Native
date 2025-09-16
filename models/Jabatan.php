<?php
require_once __DIR__ . '/../config/database.php';

// Fungsi untuk mendapatkan semua data jabatan
function getJabatan() {
    $conn = getDBConnection();

    $sql = "SELECT * FROM jabatan";
    $result = $conn->query($sql);

    $jabatan = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $jabatan[] = $row;
        }
    }
    
    $conn->close();
    return $jabatan;
}