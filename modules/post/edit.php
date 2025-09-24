 <?php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../config/database.php';

$pdo = getDB();
$id_post = $_GET['id'] ?? null;

if (!$id_post) {
    echo "<div class='alert alert-danger'>ID post tidak ditemukan!</div>";
    require_once __DIR__ . '/../../includes/footer.php';
    exit;
}

// Ambil data post
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id_post = ?");
$stmt->execute([$id_post]);
$post = $stmt->fetch();

if (!$post) {
    echo "<div class='alert alert-danger'>Post tidak ditemukan!</div>";
    require_once __DIR__ . '/../../includes/footer.php';
    exit;
}

// Ambil gambar terkait post
$stmtImg = $pdo->prepare("
    SELECT i.id_image, i.image_name, i.image_path 
    FROM image i
    JOIN pv_post_image pvi ON i.id_image = pvi.id_image
    WHERE pvi.id_post = ?");
$stmtImg->execute([$id_post]);
$images = $stmtImg->fetchAll();
?>

<div class="container">
    <h2>Edit Post</h2>
    <form action="/post/update.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_post" value="<?= $post['id_post']; ?>">

        <div class="mb-3">
            <label class="form-label">Judul Post</label>
            <input type="text" class="form-control" name="title" 
                   value="<?= htmlspecialchars($post['title']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Isi Post</label>
            <textarea class="form-control" name="content" required><?= htmlspecialchars($post['content']); ?></textarea>
        </div>

        <h5>📷 Gambar Lama:</h5>
        <?php if (!empty($images)): ?>
            <?php foreach ($images as $img): ?>
                <div style="margin-bottom:10px;">
                    <img src="/<?= htmlspecialchars($img['image_path']); ?>" width="100">
                    <label>
                        <input type="checkbox" name="delete_images[]" value="<?= $img['id_image']; ?>">
                        Hapus
                    </label>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p><i>Tidak ada gambar</i></p>
        <?php endif; ?>

        <h5>➕ Tambah Gambar Baru:</h5>
        <input type="file" name="images[]" multiple><br><br>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="/post" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php
require_once __DIR__ . '/../../includes/footer.php';
?>
