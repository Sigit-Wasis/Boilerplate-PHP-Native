<?php
require_once __DIR__ . '/../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Akses tidak valid!";
    exit;
}

$pdo = getDB();
$id_post = $_POST['id_post'] ?? null;
$title   = trim($_POST['title'] ?? '');
$content = trim($_POST['content'] ?? '');
$delete_images = $_POST['delete_images'] ?? [];

if (!$id_post || empty($title) || empty($content)) {
    echo "<script>alert('ID, judul, dan konten wajib diisi!'); window.history.back();</script>";
    exit;
}

try {
    $pdo->beginTransaction();

    // Update post
    $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ?, updated_at = NOW() WHERE id_post = ?");
    $stmt->execute([$title, $content, $id_post]);

    // Hapus gambar lama jika dipilih
    if (!empty($delete_images)) {
        foreach ($delete_images as $id_image) {
            $stmtPath = $pdo->prepare("SELECT image_path FROM image WHERE id_image = ?");
            $stmtPath->execute([$id_image]);
            $img = $stmtPath->fetch();

            if ($img) {
                // Hapus pivot
                $stmtDel = $pdo->prepare("DELETE FROM pv_post_image WHERE id_post = ? AND id_image = ?");
                $stmtDel->execute([$id_post, $id_image]);

                // Cek apakah masih dipakai post lain
                $stmtCheck = $pdo->prepare("SELECT COUNT(*) as cnt FROM pv_post_image WHERE id_image = ?");
                $stmtCheck->execute([$id_image]);
                $count = $stmtCheck->fetchColumn();

                if ($count == 0) {
                    // Hapus image record
                    $stmtImg = $pdo->prepare("DELETE FROM image WHERE id_image = ?");
                    $stmtImg->execute([$id_image]);

                    // Hapus file fisik
                    $filePath = __DIR__ . '/../../' . $img['image_path'];
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }
        }
    }

    // Upload gambar baru
    if (!empty($_FILES['images']['name'][0])) {
        $uploadDir = __DIR__ . '/../../public/img/posts/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        foreach ($_FILES['images']['name'] as $key => $filename) {
            if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                $tmpName = $_FILES['images']['tmp_name'][$key];
                $extension = pathinfo($filename, PATHINFO_EXTENSION);
                $newFilename = uniqid("post_{$id_post}_") . '.' . $extension;
                $uploadPath = $uploadDir . $newFilename;
                $relativePath = 'public/img/posts/' . $newFilename;

                if (move_uploaded_file($tmpName, $uploadPath)) {
                    $stmtImg = $pdo->prepare("INSERT INTO image (image_name, image_path) VALUES (?, ?)");
                    $stmtImg->execute([$newFilename, $relativePath]);
                    $id_image = $pdo->lastInsertId();

                    $stmtPivot = $pdo->prepare("INSERT INTO pv_post_image (id_post, id_image) VALUES (?, ?)");
                    $stmtPivot->execute([$id_post, $id_image]);
                }
            }
        }
    }

    $pdo->commit();
    header("Location: /post");
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    echo "Gagal update post: " . $e->getMessage();
}
?>
