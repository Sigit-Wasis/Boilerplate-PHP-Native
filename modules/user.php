<?php
require_once __DIR__ . '/../includes/header.php';

$users = getUsers(); // fungsi dari models/User.php
?>
<div class="container">
    <h2>📌 Data User</h2>
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Nama Lengkap</th>
                <th>Email</th>
                <th>Alamat</th>
                <th>Foto</th>
                <th>Profile Pic</th>
                <th>Dibuat</th>
                <th>Update Terakhir</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']); ?></td>
                        <td><?= htmlspecialchars($user['username']); ?></td>
                        <td><?= htmlspecialchars($user['nama_lengkap']); ?></td>
                        <td><?= htmlspecialchars($user['email']); ?></td>
                        <td><?= htmlspecialchars($user['alamat']); ?></td>
                        <td>
                            <?php if (!empty($user['foto'])): ?>
                                <img src="/uploads/<?= htmlspecialchars($user['foto']); ?>" alt="foto" width="50">
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($user['profile_pic'])): ?>
                                <img src="/uploads/<?= htmlspecialchars($user['profile_pic']); ?>" alt="profile" width="50">
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($user['created_at']); ?></td>
                        <td><?= htmlspecialchars($user['updated_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="9">Belum ada data user.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>