<?php
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
                    $uploadedFiles[] = 'public/img/' . $filename; // path relatif
                }
            }
        }
    }

    // simpan ke DB lewat model
    createPostWithImages($caption, $uploadedFiles);

    header("Location: /profil");
    exit;
} else {
    echo "Akses tidak valid!";
}
