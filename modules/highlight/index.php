<?php
// models/Highlight.php

function createHighlight($user_id, $name, $image) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("INSERT INTO highlights (user_id, name, image, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$user_id, $name, $image]);
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        error_log("Error creating highlight: " . $e->getMessage());
        return false;
    }
}

function getUserHighlights($user_id) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM highlights WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching highlights: " . $e->getMessage());
        return [];
    }
}

function deleteHighlight($id, $user_id) {
    global $pdo;
    
    try {
        // Ambil data highlight untuk hapus file gambar
        $stmt = $pdo->prepare("SELECT image FROM highlights WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $user_id]);
        $highlight = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($highlight) {
            // Hapus file gambar
            $image_path = __DIR__ . '/../public/img/highlights/' . $highlight['image'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
            
            // Hapus dari database
            $stmt = $pdo->prepare("DELETE FROM highlights WHERE id = ? AND user_id = ?");
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
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("UPDATE highlights SET name = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$new_name, $id, $user_id]);
        return true;
    } catch (PDOException $e) {
        error_log("Error updating highlight: " . $e->getMessage());
        return false;
    }
}