<?php
require_once __DIR__ . '/../config/database.php';

// CREATE POST DENGAN 1 IMAGE (tanpa looping)
function createPostWithImages($caption, $images = []) {
    $conn = getDBConnection();

    $user_id = 1; // contoh user_id, sesuaikan dengan sesi login

    // 1. Insert ke table posts
    $sqlPost = "INSERT INTO posts (caption, user_id, created_at) VALUES (?, ?, NOW())"; 
    $stmtPost = $conn->prepare($sqlPost);
    if (!$stmtPost) {
        die("Error prepare post: " . $conn->error);
    }

    $stmtPost->bind_param("si", $caption, $user_id);
    $stmtPost->execute();
    $id_post = $conn->insert_id; // ambil ID dari koneksi, bukan statement
    $stmtPost->close();

    echo "Post created with ID: " . $id_post . "<br>";
    echo "Caption: " . htmlspecialchars($caption) . "<br>";
    echo "User ID: " . $user_id . "<br>";
    echo "Number of images: " . count($images) . "<br>";

    // 2. Kalau ada gambar, simpan semua dengan looping
    if (!empty($images)) {
        foreach ($images as $img) {
            // pastikan $img berbentuk array dengan key yg sesuai
            if (!isset($img['file'], $img['type_file'], $img['size'], $img['resolution'])) {
                continue; // skip kalau datanya gak lengkap
            }

            echo "Processing image: " . htmlspecialchars($img['file']) . "<br>";

            $file       = $img['file'];        // nama file unik
            $type_file  = $img['type_file'];   // mime type (misal image/jpeg)
            $size       = $img['size'];        // ukuran file (bytes)
            $resolution = $img['resolution'];  // resolusi (misal 1080x720)

            // Insert ke table media
            $sqlImg = "INSERT INTO media (file, type_file, size, resolution) VALUES (?, ?, ?, ?)";
            $stmtImg = $conn->prepare($sqlImg);
            if (!$stmtImg) {
                die("Error prepare image: " . $conn->error);
            }
            $stmtImg->bind_param("ssis", $file, $type_file, $size, $resolution);
            $stmtImg->execute();
            $id_image = $conn->insert_id;
            $stmtImg->close();

            // Insert ke table pv_post_image
            $sqlPivot = "INSERT INTO pv_post_image (post_id, media_id) VALUES (?, ?)";
            $stmtPivot = $conn->prepare($sqlPivot);
            if (!$stmtPivot) {
                die("Error prepare pivot: " . $conn->error);
            }
            $stmtPivot->bind_param("ii", $id_post, $id_image);
            $stmtPivot->execute();
            $stmtPivot->close();
        }
    }

    $conn->close();
    return $id_post;
}


// GET POST beserta 1 gambar
function getAllPosts() {
    $conn = getDBConnection();
    $sql = "SELECT p.id AS id_post, p.caption, p.created_at, u.username,
                   m.id AS id_image, m.file
            FROM posts p
            JOIN users u ON p.user_id = u.id
            LEFT JOIN pv_post_image pi ON p.id = pi.post_id
            LEFT JOIN media m ON pi.media_id = m.id
            ORDER BY p.created_at DESC";
    $result = $conn->query($sql);

    $posts = [];
    while ($row = $result->fetch_assoc()) {
        $posts[] = [
            'id_post'    => $row['id_post'],
            'caption'    => $row['caption'],
            'created_at' => $row['created_at'],
            'username'   => $row['username'],
            'image'      => $row['file'] ?? null // cuma 1 gambar
        ];
    }
    $conn->close();
    return $posts;
}
?>