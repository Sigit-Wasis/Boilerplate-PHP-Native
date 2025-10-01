<?php

session_start();

require_once __DIR__ . '/../../models/Highlight.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';

    if (empty($name)) {
        echo "catatan wajib diisi!";
        exit;
    }

    // kumpulkan file yang diupload
    $uploadedFile = '';

    if (!empty($_FILES['image']['tmp_name'])) {
        $file = $_FILES['image'];
        
        if ($file['error'] === UPLOAD_ERR_OK) {
            $upload_dir = __DIR__ . '/../../public/img/highlights/';
            
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            // Validasi tipe
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            
            $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            
            if (in_array($mime_type, $allowed_types) && $file['size'] <= 5242880) {
                $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = uniqid('highlight_') . '_' . time() . '.' . $file_extension;
                $targetPath = $upload_dir . $filename;
                
                if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                    $uploadedFile = $filename; // Hanya nama file, bukan path lengkap
                }
            }
        }
    }

    $user_id =$_SESSION['user']['id']; //ambil user_id dari session

    // simpan ke DB lewat model
    createHighlightWithImages($name,$user_id,$uploadedFile);


    header("Location: /profil");
    exit;
} else {
    echo "Akses tidak valid!";
}