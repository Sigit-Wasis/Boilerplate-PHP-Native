<?php
// models/Highlight.php
require_once __DIR__ . '/../config/database.php';

function createHighlight($user_id, $name, $image) {
    $conn = getDBConnection();
    
    try {
        $stmt = $conn->prepare("INSERT INTO highlights (user_id, name, image, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$user_id, $name, $image]);
        return $conn->lastInsertId();
    } catch (PDOException $e) {
        error_log("Error creating highlight: " . $e->getMessage());
        return false;
    }
}

function getUserHighlights($user_id) {
    $conn = getDBConnection();
    
    $user_id = mysqli_real_escape_string($conn, $user_id);
    $query = "SELECT * FROM highlights WHERE user_id = '$user_id' ORDER BY created_at DESC";
    
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        $highlights = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $highlights[] = $row;
        }
        mysqli_free_result($result);
        mysqli_close($conn);
        return $highlights;
    }
    
    mysqli_close($conn);
    return [];
}

function deleteHighlight($id, $user_id) {
    $conn = getDBConnection();
    
    try {
        // Ambil data highlight untuk hapus file gambar
        $stmt = $conn->prepare("SELECT image FROM highlights WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $user_id]);
        $highlight = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($highlight) {
            // Hapus file gambar
            $image_path = __DIR__ . '/../public/img/highlights/' . $highlight['image'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
            
            // Hapus dari database
            $stmt = $conn->prepare("DELETE FROM highlights WHERE id = ? AND user_id = ?");
            $stmt->execute([$id, $user_id]);
            return true;
        }
        return false;
    } catch (PDOException $e) {
        error_log("Error deleting highlight: " . $e->getMessage());
        return false;
    }
}

function updateHighlightName($id, $user_id, $new_name) {
   $conn = getDBConnection();
    
    try {
        $stmt = $conn->prepare("UPDATE highlights SET name = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$new_name, $id, $user_id]);
        return true;
    } catch (PDOException $e) {
        error_log("Error updating highlight: " . $e->getMessage());
        return false;
    }
}

function createhighlightWithImages($name,$user_id, $images) {
    $conn = getDBConnection();

    // 1. Insert ke table highlights
    $sqlPost = "INSERT INTO highlights (name, user_id, image, created_at) VALUES (?, ?, ?, NOW())"; 
    $stmtPost = $conn->prepare($sqlPost);
    if (!$stmtPost) {
        die("Error prepare post: " . $conn->error);
    }

    $stmtPost->bind_param("sis", $name, $user_id, $images);
    $stmtPost->execute();
    $stmtPost->close();

    $conn->close();
    return true;
}