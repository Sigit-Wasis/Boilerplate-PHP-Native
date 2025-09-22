<?php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../models/Post.php';
?>

<div class="container">
    <h2>Tambah Post Baru</h2>
    <form action="/post/store.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">Judul Post</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Isi Post</label>
            <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
        </div>

        <div class="mb-3">
            <label for="images" class="form-label">Upload Gambar</label>
            <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
        </div>
 
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="/post" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php
require_once __DIR__ . '/../../includes/footer.php';
?>
