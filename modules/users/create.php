<?php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../models/User.php';
?>

<div class="container">
    <h2>Tambah User Baru</h2>
    <form action="/user/store" method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="/user" class="btn btn-secondary">Batal</a>
    </form>
</div>
<?php
require_once __DIR__ . '/../../includes/footer.php';
?>