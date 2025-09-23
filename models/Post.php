<?php
require_once __DIR__ . '/../config/database.php';

// CREATE POST DENGAN 1 IMAGE (tanpa looping)
function createPostWithImages($caption, $user_id, $images = []) {
    $conn = getDBConnection();

    // 1. Insert ke table posts
    $sqlPost = "INSERT INTO posts (caption, user_id, created_at) VALUES (?, ?, NOW())"; 
    $stmtPost = $conn->prepare($sqlPost);
    if (!$stmtPost) {
        die("Error prepare post: " . $conn->error);
    }
    $stmtPost->bind_param("si", $caption, $user_id);
    $stmtPost->execute();
    $id_post = $stmtPost->insert_id;
    $stmtPost->close();
}

    // 2. Kalau ada gambar, ambil hanya 1 (tanpa looping) 
    if (!empty($images)) {
        $img_path = $images[0]; // hanya ambil gambar pertama

        // 2. Kalau ada gambar, ambil hanya 1 (tanpa looping)
if (!empty($images)) {
    $img = $images[0]; // ambil gambar pertama (array $images harus simpan detail file)

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
    $id_image = $stmtImg->insert_id;
    $stmtImg->close();

    // Insert ke table pv_post_image
    $sqlPivot = "INSERT INTO pv_post_image (id_post, id_image) VALUES (?, ?)";
    $stmtPivot = $conn->prepare($sqlPivot);
    if (!$stmtPivot) {
        die("Error prepare pivot: " . $conn->error);
    }
    $stmtPivot->bind_param("ii", $id_post, $id_image);
    $stmtPivot->execute();
    $stmtPivot->close();
}

    $conn->close();
    return $id_post;
}

// GET POST beserta 1 gambar
function getPostsWithImages() {
    $conn = getDBConnection();
    $sql = "SELECT p.id AS id_post, p.caption, p.created_at, u.username,
                   i.id AS id_image, i.image_path
            FROM posts p
            JOIN users u ON p.user_id = u.id
            LEFT JOIN pv_post_image pi ON p.id = pi.id_post
            LEFT JOIN image i ON pi.id_image = i.id
            ORDER BY p.created_at DESC";
    $result = $conn->query($sql);

    $posts = [];
    while ($row = $result->fetch_assoc()) {
        $posts[] = [
            'id_post'    => $row['id_post'],
            'caption'    => $row['caption'],
            'created_at' => $row['created_at'],
            'username'   => $row['username'],
            'image'      => $row['image_path'] ?? null // cuma 1 gambar
        ];
    }
    $conn->close();
    return $posts;
}
?>