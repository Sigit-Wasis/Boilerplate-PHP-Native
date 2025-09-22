<?php
require_once __DIR__ . '/../../models/Post.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $caption = $_POST['caption'] ?? '';

    if (empty($caption)) {
        echo "Caption wajib diisi!";
        exit;
    }

    // kumpulkan file yang diupload
    $uploadedFiles = [];
    if (!empty($_FILES['images']['name'][0])) {
        $uploadDir = __DIR__ . '/../../public/img/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                $fileType = $_FILES['images']['type'][$key];
                $fileSize = $_FILES['images']['size'][$key];
                // Validasi tipe dan ukuran file
                if ($fileSize > 5 * 1024 * 1024) continue;
                if (!in_array($fileType, ['image/jpeg', 'image/png', 'image/gif', 'image/webp'])) continue;

                $filename = uniqid() . "_" . basename($_FILES['images']['name'][$key]);
                $targetPath = $uploadDir . $filename;

                if (move_uploaded_file($tmp_name, $targetPath)) {
                    $uploadedFiles[] = 'public/img/' . $filename; // path relatif
                }
            }
        }
    }

    // simpan ke DB lewat model
    $user_id = $_SESSION['user_id'] ?? 1; // Ganti dengan session user login
    createPostWithImages($user_id, $caption, $uploadedFiles);

    header("Location: /modules/post/index.php?success=1");
    exit;
} else {
    echo "Akses tidak valid!";
}