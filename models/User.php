<?php
require_once __DIR__ . '/../config/database.php';

// Fungsi CRUD: Create
function createUser($name, $email) {
    $conn = getDBConnection();
    
    $name = htmlspecialchars(stripslashes(trim($name)));
    $email = htmlspecialchars(stripslashes(trim($email)));
    
    $sql = "INSERT INTO users (name, email) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $name, $email);
    
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    
    return $result;
}

// Fungsi find user by ID
function getUserById($id) {
    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    $conn->close();
    return $result;
}

// Fungsi CRUD: Update
function updateUser($id, $name, $email) {
    $conn = getDBConnection();

    $name = htmlspecialchars(stripslashes(trim($name)));
    $email = htmlspecialchars(stripslashes(trim($email)));
    $id = (int)$id;

    $sql = "UPDATE users SET name = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $name, $email, $id);
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $result;
}

// Fungsi CRUD: Delete
function deleteUser($id) {
    $conn = getDBConnection();
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
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