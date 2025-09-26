<?php
session_start();
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../models/User.php';

if (!isset($_SESSION['user'])) {
    header("Location: /login");
    exit;
}

$users = getUsers(); // fungsi dari models/User.php
?>
<div class="container">
    <h2>Data User</h2>
    <!-- Create -->
    <a href="/user/create" class="btn btn-primary mb-3">Tambah User</a>

    <?php if (!empty($users)): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $index => $user): ?>
                    <tr>
                        <td><?= $index + 1; ?></td>
                        <td><?= htmlspecialchars($user['name']); ?></td>
                        <td><?= htmlspecialchars($user['email']); ?></td>
                        <td>
                            <a href="/user/edit?id=<?= $user['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="/user/delete?id=<?= $user['id']; ?>" 
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Yakin ingin menghapus user ini?')">
                                Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">Belum ada data user.</div>
    <?php endif; ?>
</div>
<?php
require_once __DIR__ . '/../../includes/footer.php';
?>
