<?php
require_once _DIR_ . '/../../includes/header.php';
require_once _DIR_ . '/../../models/Post.php';

// pastikan ada parameter id
$id = $_GET['id'] ?? null;

if (!$id) {
    echo "<div class='alert alert-danger'>ID post tidak ditemukan!</div>";
    require_once _DIR_ . '/../../includes/footer.php';
    exit;
}

$user = getUserById($id); // fungsi ini harus ada di models/Post.php

if (!$user) {
    echo "<div class='alert alert-danger'>Post tidak ditemukan!</div>";
    require_once _DIR_ . '/../../includes/footer.php';
    exit;
}
?>

<div class="container">
    <h2>Edit Post</h2>
    <form action="/post/update" method="POST">
        <input type="hidden" name="id" value="<?= $user['id'] ?>">

        <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="/post" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php
require_once _DIR_ . '/../../includes/footer.php';
?>