<?php
require_once __DIR__ . '/../config/database.php';

// FUNGSI UNTUK UPLOAD FOTO
function uploadImage($file) {
    $uploadDir = '../uploads/posts/';
    
    // Buat direktori jika belum ada
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    // Validasi file
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    $maxSize = 5 * 1024 * 1024; // 5MB
    
    if (!in_array($file['type'], $allowedTypes)) {
        throw new Exception("Tipe file tidak diizinkan. Hanya JPEG, PNG, dan GIF yang diperbolehkan.");
    }
    
    if ($file['size'] > $maxSize) {
        throw new Exception("Ukuran file terlalu besar. Maksimal 5MB.");
    }
    
    // Generate nama file unik
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('post_') . '.' . $extension;
    $filepath = $uploadDir . $filename;
    
    // Upload file
    if (!move_uploaded_file($file['tmp_name'], $filepath)) {
        throw new Exception("Gagal mengupload file.");
    }
    
    // Dapatkan resolusi gambar
    $imageInfo = getimagesize($filepath);
    $resolution = $imageInfo[0] . 'x' . $imageInfo[1];
    
    return [
        'file' => $filename,
        'type_file' => $file['type'],
        'size' => $file['size'],
        'resolution' => $resolution,
        'path' => $filepath
    ];
}

// CREATE POST DENGAN IMAGE
function createPostWithImages($caption, $user_id, $uploadedFile = null) {
    $conn = getDBConnection();

    try {
        // Mulai transaction
        $conn->autocommit(false);

        // 1. Insert ke table posts
        $sqlPost = "INSERT INTO posts (caption, user_id, created_at) VALUES (?, ?, NOW())"; 
        $stmtPost = $conn->prepare($sqlPost);
        if (!$stmtPost) {
            throw new Exception("Error prepare post: " . $conn->error);
        }
        $stmtPost->bind_param("si", $caption, $user_id);
        $stmtPost->execute();
        $id_post = $stmtPost->insert_id;
        $stmtPost->close();

        // 2. Jika ada file yang diupload
        if ($uploadedFile && $uploadedFile['error'] == UPLOAD_ERR_OK) {
            // Upload gambar
            $imageData = uploadImage($uploadedFile);

            // Insert ke table media
            $sqlImg = "INSERT INTO media (file, type_file, size, resolution) VALUES (?, ?, ?, ?)";
            $stmtImg = $conn->prepare($sqlImg);
            if (!$stmtImg) {
                throw new Exception("Error prepare media: " . $conn->error);
            }
            $stmtImg->bind_param("ssis", $imageData['file'], $imageData['type_file'], $imageData['size'], $imageData['resolution']);
            $stmtImg->execute();
            $id_image = $stmtImg->insert_id;
            $stmtImg->close();

            // Insert ke table pv_post_image
            $sqlPivot = "INSERT INTO pv_post_image (id_post, id_image) VALUES (?, ?)";
            $stmtPivot = $conn->prepare($sqlPivot);
            if (!$stmtPivot) {
                throw new Exception("Error prepare pivot: " . $conn->error);
            }
            $stmtPivot->bind_param("ii", $id_post, $id_image);
            $stmtPivot->execute();
            $stmtPivot->close();
        }

        // Commit transaction
        $conn->commit();
        $conn->close();
        return $id_post;

    } catch (Exception $e) {
        // Rollback jika ada error
        $conn->rollback();
        $conn->close();
        throw $e;
    }
}

// GET POST beserta gambar
function getPostsWithImages() {
    $conn = getDBConnection();
    $sql = "SELECT p.id AS id_post, p.caption, p.created_at, u.username,
                   m.id AS id_image, m.file, m.type_file
            FROM posts p
            JOIN users u ON p.user_id = u.id
            LEFT JOIN pv_post_image pm ON p.id = pm.id_post
            LEFT JOIN media m ON pm.id_image = m.id
            ORDER BY p.created_at DESC";
    $result = $conn->query($sql);

    $posts = [];
    while ($row = $result->fetch_assoc()) {
        $imagePath = null;
        if ($row['file']) {
            $imagePath = '../uploads/posts/' . $row['file'];
        }
        
        $posts[] = [
            'id_post'    => $row['id_post'],
            'caption'    => $row['caption'],
            'created_at' => $row['created_at'],
            'username'   => $row['username'],
            'image'      => $imagePath,
            'image_type' => $row['type_file'] ?? null
        ];
    }
    $conn->close();
    return $posts;
}

// CONTOH PENGGUNAAN - Handler untuk form POST
function handlePostSubmission() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        try {
            $caption = trim($_POST['caption'] ?? '');
            $user_id = $_SESSION['user_id'] ?? null; // Asumsi user sudah login
            
            if (!$user_id) {
                throw new Exception("User harus login terlebih dahulu.");
            }
            
            if (empty($caption)) {
                throw new Exception("Caption tidak boleh kosong.");
            }
            
            // Handle file upload jika ada
            $uploadedFile = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] != UPLOAD_ERR_NO_FILE) {
                $uploadedFile = $_FILES['image'];
            }
            
            // Buat post
            $post_id = createPostWithImages($caption, $user_id, $uploadedFile);
            
            return [
                'success' => true,
                'message' => 'Post berhasil dibuat!',
                'post_id' => $post_id
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
}

// DELETE POST DAN GAMBARNYA
function deletePost($post_id, $user_id) {
    $conn = getDBConnection();
    
    try {
        $conn->autocommit(false);
        
        // Cek apakah post milik user yang sedang login
        $sqlCheck = "SELECT id FROM posts WHERE id = ? AND user_id = ?";
        $stmtCheck = $conn->prepare($sqlCheck);
        $stmtCheck->bind_param("ii", $post_id, $user_id);
        $stmtCheck->execute();
        $result = $stmtCheck->get_result();
        
        if ($result->num_rows == 0) {
            throw new Exception("Post tidak ditemukan atau Anda tidak memiliki akses.");
        }
        $stmtCheck->close();
        
        // Dapatkan file gambar untuk dihapus
        $sqlGetImage = "SELECT m.file FROM media m 
                       JOIN pv_post_image pm ON m.id = pm.id_image 
                       WHERE pm.id_post = ?";
        $stmtGetImage = $conn->prepare($sqlGetImage);
        $stmtGetImage->bind_param("i", $post_id);
        $stmtGetImage->execute();
        $resultImage = $stmtGetImage->get_result();
        
        $imagesToDelete = [];
        while ($row = $resultImage->fetch_assoc()) {
            $imagesToDelete[] = '../uploads/posts/' . $row['file'];
        }
        $stmtGetImage->close();
        
        // Hapus dari pv_post_image
        $sqlDeletePivot = "DELETE FROM pv_post_image WHERE id_post = ?";
        $stmtDeletePivot = $conn->prepare($sqlDeletePivot);
        $stmtDeletePivot->bind_param("i", $post_id);
        $stmtDeletePivot->execute();
        $stmtDeletePivot->close();
        
        // Hapus dari media (jika tidak digunakan post lain)
        $sqlDeleteMedia = "DELETE FROM media WHERE id IN (
            SELECT pm.id_image FROM pv_post_image pm WHERE pm.id_post = ?
        ) AND id NOT IN (
            SELECT DISTINCT pm2.id_image FROM pv_post_image pm2 WHERE pm2.id_post != ?
        )";
        $stmtDeleteMedia = $conn->prepare($sqlDeleteMedia);
        $stmtDeleteMedia->bind_param("ii", $post_id, $post_id);
        $stmtDeleteMedia->execute();
        $stmtDeleteMedia->close();
        
        // Hapus post
        $sqlDeletePost = "DELETE FROM posts WHERE id = ?";
        $stmtDeletePost = $conn->prepare($sqlDeletePost);
        $stmtDeletePost->bind_param("i", $post_id);
        $stmtDeletePost->execute();
        $stmtDeletePost->close();
        
        $conn->commit();
        
        // Hapus file fisik
        foreach ($imagesToDelete as $imagePath) {
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        $conn->close();
        return true;
        
    } catch (Exception $e) {
        $conn->rollback();
        $conn->close();
        throw $e;
    }
}
?>