<?php
session_start();
require_once __DIR__. '/../../models/Post.php';

if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
    $targetDir = __DIR__. '/../../uploads/';
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

    $fileName   = uniqid() . '_' . basename($_FILES['profile_pic']['name']);
    $targetFile = $targetDir . $fileName;

    if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $targetFile)) {
        // Simpan path ke session
        $_SESSION['profile_pic'] = '/uploads/' . $fileName;

        // 🔹 Tambahkan data statis di sini
        $user_id = $_SESSION['user_id'] ?? 1; // fallback user id
        $caption = "Foto profil baru";        // statis (hardcode)
        $status  = "aktif";                   // tambahan statis
        $type    = "profile";                 // contoh field statis lain

        // Simpan ke database (model Post.php perlu menyesuaikan parameter)
        createPostWithImages($caption, $user_id, $_SESSION['profile_pic'], $status, $type);

        // Redirect kembali ke profil
        header('Location: /profil');
        exit;
    } else {
        echo "Gagal upload foto!";
    }
} else {
    echo "Tidak ada file yang diupload!";
}
?>