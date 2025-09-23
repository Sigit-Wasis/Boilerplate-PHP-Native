<?php
require_once __DIR__. '/../../config/database.php';

// Tampilkan header
require_once __DIR__. '/../../includes/header.php';
?>

<div class="container mt-4">
    <h2 class="mb-3">Daftar Post</h2>
    <a href="/post/create" class="btn btn-primary mb-3">+ Tambah Post</a>

    <?php if ($getPosts && $getPosts->num_rows > 0): ?>
        <div class="list-group">
            <?php while ($row = $getPosts->fetch_assoc()): ?>
                <div class="list-group-item">
                    <h5>@<?= htmlspecialchars($row['username']) ?></h5>
                    <p><?= htmlspecialchars($row['caption']) ?></p>
                    <small class="text-muted">Dibuat: <?= $row['created_at'] ?></small>
                    <div class="mt-2">
                        <a href="/post/edit?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="/post/delete?id=<?= $row['id'] ?>" 
                           onclick="return confirm('Yakin ingin menghapus post ini?')" 
                           class="btn btn-sm btn-danger">Delete</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">Belum ada postingan.</div>
    <?php endif; ?>
</div>

<?php
// Tutup koneksi & footer
require_once __DIR__. '/../../includes/footer.php';
?>