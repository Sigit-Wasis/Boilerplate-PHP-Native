<?php
require_once __DIR__ . '/../config/database.php';

// Fungsi untuk sanitasi input
function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Fungsi CRUD: Create
function createUser($name, $email) {
    $conn = getDBConnection();
    
    $name = sanitizeInput($name);
    $email = sanitizeInput($email);
    
    $sql = "INSERT INTO users (name, email) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $name, $email);
    
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    
    return $result;
}

// Fungsi CRUD: Read
function getUsers() {
    $conn = getDBConnection();
    
    $sql = "SELECT * FROM users";
    $result = $conn->query($sql);
    
    $users = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }
    
    $conn->close();
    return $users;
}
?>