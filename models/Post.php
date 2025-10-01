<?php
require_once __DIR__ . '/../config/database.php';

// CREATE POST DENGAN 1 IMAGE (tanpa looping)
function createPostWithImages($caption,$user_id, $images = []) {
    $conn = getDBConnection();

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
function getAllPosts($user_id = 0) {
    $conn = getDBConnection();

    $sql = "SELECT p.id AS id_post, p.caption, p.created_at, u.username, u.nama_lengkap,
                   m.id AS id_image, m.file,
                   COUNT(l.id) AS like_count,
                   MAX(CASE WHEN l.user_id = ? THEN 1 ELSE 0 END) AS is_liked
            FROM posts p
            JOIN users u ON p.user_id = u.id
            LEFT JOIN pv_post_image pi ON p.id = pi.post_id
            LEFT JOIN media m ON pi.media_id = m.id
            LEFT JOIN likes l ON p.id = l.post_id
            GROUP BY p.id, p.caption, p.created_at, u.username, u.nama_lengkap, m.id, m.file
            ORDER BY p.created_at DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $posts = [];
    while ($row = $result->fetch_assoc()) {
        $posts[] = [
            'id_post'      => $row['id_post'],
            'caption'      => $row['caption'],
            'created_at'   => $row['created_at'],
            'username'     => $row['username'],
            'nama_lengkap' => $row['nama_lengkap'],
            'image'        => $row['file'] ?? null,
            'like_count'   => $row['like_count'],
            'is_liked'     => $row['is_liked'] == 1
        ];
    }

    $stmt->close();
    $conn->close();

    return $posts;
}
?>