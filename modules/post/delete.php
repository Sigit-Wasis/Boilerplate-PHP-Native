<?php
require_once __DIR__ . '/../../config/database.php';

$id_post = $_GET['id'] ?? 0;

if (!$id_post) {
    echo "<script>alert('ID Post tidak valid!'); window.location.href='index.php';</script>";
    exit;
}

try {
    $pdo = getDB();
    $pdo->beginTransaction();
    
    // 1. Ambil semua gambar terkait post
    $sqlGetImages = "SELECT i.id_image, i.image_path
                     FROM image i
                     JOIN pv_post_image pvi ON i.id_image = pvi.id_image
                     WHERE pvi.id_post = ?";
    $stmtGetImages = $pdo->prepare($sqlGetImages);
    $stmtGetImages->execute([$id_post]);
    $images = $stmtGetImages->fetchAll();

    // 2. Hapus relasi di pivot table
    $sqlDeletePivot = "DELETE FROM pv_post_image WHERE id_post = ?";
    $stmtDeletePivot = $pdo->prepare($sqlDeletePivot);
    $stmtDeletePivot->execute([$id_post]);

    // 3. Hapus gambar yang tidak dipakai post lain
    foreach ($images as $image) {
        $sqlCheckUsage = "SELECT COUNT(*) as cnt FROM pv_post_image WHERE id_image = ?";
        $stmtCheckUsage = $pdo->prepare($sqlCheckUsage);
        $stmtCheckUsage->execute([$image['id_image']]);
        $usage = $stmtCheckUsage->fetch();

        if ($usage && $usage['cnt'] == 0) {
            // Hapus record dari tabel image
            $sqlDeleteImage = "DELETE FROM image WHERE id_image = ?";
            $stmtDeleteImage = $pdo->prepare($sqlDeleteImage);
            $stmtDeleteImage->execute([$image['id_image']]);

            // Hapus file fisik
            $filePath = __DIR__ . '/../../' . $image['image_path'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
    }

    // 4. Hapus post
    $sqlDeletePost = "DELETE FROM posts WHERE id_post = ?";
    $stmtDeletePost = $pdo->prepare($sqlDeletePost);
    $stmtDeletePost->execute([$id_post]);

    if ($stmtDeletePost->rowCount() > 0) {
        $pdo->commit();
        echo "<script>alert('Post berhasil dihapus!'); window.location.href='index.php';</script>";
    } else {
        throw new Exception('Post tidak ditemukan atau gagal dihapus');
    }

} catch (Exception $e) {
    $pdo->rollBack();
    echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href='index.php';</script>";
}
