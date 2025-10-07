<?php
// models/Follow.php
require_once __DIR__ . '/../config/database.php';

function followUser($follower_id, $following_id) {
    // Assumes getDBConnection() returns a mysqli object
    $conn = getDBConnection();
    
    if ($conn === false) {
        return ['success' => false, 'message' => 'Database connection failed'];
    }

    // Start a transaction for data integrity
    $conn->begin_transaction(); 

    try {
        // --- 1. Check if already following (SELECT) ---
        // Use $conn for mysqli prepare
        $stmt_check = $conn->prepare("SELECT id FROM follows WHERE follower_id = ? AND following_id = ?");
        $stmt_check->bind_param("ii", $follower_id, $following_id);
        $stmt_check->execute();
        
        // Use get_result() and num_rows to check if a row exists in mysqli
        $result_check = $stmt_check->get_result();
        
        if ($result_check->num_rows > 0) {
            $stmt_check->close();
            $conn->rollback(); // Rollback the transaction since we're exiting
            $conn->close();
            return ['success' => false, 'message' => 'Already following'];
        }
        $stmt_check->close();

        // --- 2. Insert follow relationship (INSERT) ---
        // Assuming table name is 'follow' and columns are 'following_id'
        $stmt_insert = $conn->prepare("INSERT INTO follows (follower_id, following_id, created_at) VALUES (?, ?, NOW())");
        $stmt_insert->bind_param("ii", $follower_id, $following_id);
        
        if (!$stmt_insert->execute()) {
            $stmt_insert->close();
            throw new Exception("Failed to insert follow record.");
        }
        $stmt_insert->close();

        
        // If all successful, commit the transaction
        $conn->commit();
        $conn->close();
        return ['success' => true, 'message' => 'Successfully followed'];
        
    } catch (Exception $e) {
        // Rollback transaction on any error
        $conn->rollback();
        $conn->close();
        return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
    }
}

function unfollowUser($follower_id, $following_id) {
    // Asumsi getDBConnection() mengembalikan objek mysqli
    $conn = getDBConnection();
    
    if ($conn === false) {
        return ['success' => false, 'message' => 'Database connection failed'];
    }

    // Gunakan transaksi untuk memastikan semua operasi (DELETE dan UPDATE) berhasil atau gagal semua
    $conn->begin_transaction(); 

    try {
        // --- 1. Hapus Hubungan Follow (DELETE) ---
        // Perhatikan: Gunakan $conn->prepare() untuk mysqli
        $stmt_delete = $conn->prepare("DELETE FROM follows WHERE follower_id = ? AND following_id = ?");
        
        // Asumsi nama tabel Anda adalah 'follow' dan kolomnya 'following_id' (sesuai contoh sebelumnya)
        // Jika nama tabel Anda adalah 'follows' dan kolomnya 'following_id', sesuaikan di sini.
        // Asumsi: Tabel 'follow', Kolom 'following_id'
        
        $stmt_delete->bind_param("ii", $follower_id, $following_id);
        $stmt_delete->execute();
        
        $deleted_rows = $stmt_delete->affected_rows;
        $stmt_delete->close();

        if ($deleted_rows > 0) {            
            // Jika semua berhasil, commit transaksi
            $conn->commit();
            $conn->close();
            return ['success' => true, 'message' => 'Successfully unfollowed'];
        }
        
        // Jika $deleted_rows == 0, berarti pengguna tidak mengikuti user ini
        $conn->rollback();
        $conn->close();
        return ['success' => false, 'message' => 'Not following this user'];
        
    } catch (Exception $e) {
        // Rollback jika ada kesalahan di tengah proses
        $conn->rollback();
        $conn->close();
        // Anda harus memastikan pengecualian mysqli ditangani, tapi Exception catch-all ini cukup
        return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
    }
}

function isFollowing($follower_id, $following_id) {
    $conn = getDBConnection();
    
    if ($conn === false) {
        return false; // Kembalikan false jika koneksi gagal
    }

    $sqlCheck = "SELECT id FROM follows WHERE follower_id = ? AND following_id = ?";
    $stmt = $conn->prepare($sqlCheck);

    if ($stmt === false) {
        return false; // Kembalikan false jika prepare gagal
    }

    $stmt->bind_param("ii", $follower_id, $following_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $isFollowing = $result->num_rows > 0;

    $stmt->close();
    // $conn->close(); // Opsional, tergantung apakah koneksi dikelola secara global

    return $isFollowing;
}

// AJAX Handler
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    session_start();
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user']['id'])) {
        echo json_encode(['success' => false, 'message' => 'Not logged in']);
        exit;
    }
    
    $follower_id = $_SESSION['user']['id'];
    $following_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    
    if ($following_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
        exit;
    }
    
    if ($follower_id === $following_id) {
        echo json_encode(['success' => false, 'message' => 'Cannot follow yourself']);
        exit;
    }
    
    if ($_POST['action'] === 'follow') {
        $result = followUser($follower_id, $following_id);
    } else if ($_POST['action'] === 'unfollow') {
        $result = unfollowUser($follower_id, $following_id);
    } else {
        $result = ['success' => false, 'message' => 'Invalid action'];
    }
    
    echo json_encode($result);
    exit;
}
?>