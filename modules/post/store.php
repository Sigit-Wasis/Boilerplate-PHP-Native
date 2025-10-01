<?php

session_start();

require_once __DIR__ . '/../../models/Post.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $caption = $_POST['caption'] ?? '';

    if (empty($caption)) {
        echo "Caption wajib diisi!";
        exit;
    }

    // kumpulkan file yang diupload
    $uploadedFiles = [];
if (!empty($_FILES['images']['name'][0])) {
    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
        if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
            $filename = uniqid() . "_" . basename($_FILES['images']['name'][$key]);
            $targetPath = __DIR__ . '/../../public/img/' . $filename;

            if (move_uploaded_file($tmp_name, $targetPath)) {
                // ambil resolusi gambar
                $resolution = null;
                $imageInfo = @getimagesize($targetPath);
                if ($imageInfo) {
                    $resolution = $imageInfo[0] . "x" . $imageInfo[1];
                }

                // simpan detail lengkap file
                $uploadedFiles[] = [
                    'file'       => $filename,                       // nama unik
                    'type_file'  => $_FILES['images']['type'][$key], // MIME type
                    'size'       => $_FILES['images']['size'][$key], // ukuran
                    'resolution' => $resolution,                     // resolusi
                    'image_path' => 'public/img/' . $filename        // path relatif
                ];
            }
        }
    }
}

    $user_id = $_SESSION['user']['id']; // ambil user_id dari session

    // simpan ke DB lewat model
    createPostWithImages($caption, $user_id, $uploadedFiles);

    header("Location: /profil");
    exit;
} else {
    echo "Akses tidak valid!";
}